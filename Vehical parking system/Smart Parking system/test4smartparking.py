import cv2
import numpy as np
import pickle
import pandas as pd
from ultralytics import YOLO
import cvzone
import requests
import datetime
import json

with open("freedomtech", "rb") as f:
    data = pickle.load(f)
    polylines, area_names = data['polylines'], data['area_names']

my_file = open("coco.txt", "r")
data = my_file.read()
class_list = data.split("\n")

model = YOLO('yolov8s.pt')

cap = cv2.VideoCapture('Parking.mp4')

count = 0
prev_car_count = 0
prev_free_space = 0

while True:
    ret, frame = cap.read()
    if not ret:
        cap.set(cv2.CAP_PROP_POS_FRAMES, 0)
        continue

    count += 1
    if count % 3 != 0:
        continue

    frame = cv2.resize(frame, (1020, 500))
    frame_copy = frame.copy()
    results = model.predict(frame)
    a = results[0].boxes.data
    px = pd.DataFrame(a).astype("float")
    list1 = []
    for index, row in px.iterrows():
        x1 = int(row[0])
        y1 = int(row[1])
        x2 = int(row[2])
        y2 = int(row[3])
        d = int(row[5])

        c = class_list[d]
        cx = int(x1 + x2) // 2
        cy = int(y1 + y2) // 2
        if 'car' in c:
            list1.append([cx, cy])
    counter1 = []
    list2 = []
    free_space_areas = set(area_names)  # Create a set of all area names
    for i, polyline in enumerate(polylines):
        list2.append(i)
        cv2.polylines(frame, [polyline], True, (0, 255, 0), 2)
        cvzone.putTextRect(frame, f'{area_names[i]}', tuple(polyline[0]), 1, 1)
        for i1 in list1:
            cx1 = i1[0]
            cy1 = i1[1]
            result = cv2.pointPolygonTest(polyline, ((cx1, cy1)), False)
            if result >= 0:
                cv2.circle(frame, (cx1, cy1), 5, (255, 0, 0), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter1.append(cx1)
                free_space_areas.discard(area_names[i])  # Remove the area name if car is detected in it

    car_count = len(counter1)
    free_space = len(list2) - car_count

    if car_count != prev_car_count or free_space != prev_free_space:
        # Get current time
        current_time = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        place = 'Heroya'
        # Send values to PHP file
        data = {'car_count': car_count, 'free_space': free_space, 'time': current_time, 'place': place}
        response = requests.post('https://porsgrunntraffic.com/Insert_parking_data.php', data=data)
        print(response.text)  # Print the response from the PHP file

        prev_car_count = car_count
        prev_free_space = free_space

        # Send empty areas to the database
        empty_areas = ','.join(free_space_areas)
        data = {'empty_areas': empty_areas, 'time': current_time, 'place': place}
        response = requests.post('https://porsgrunntraffic.com/Insert_empty_areas.php', data=data)
        print(response.text)  # Print the response from the PHP file
        print(empty_areas)
        print(current_time)

    cvzone.putTextRect(frame, f'CARCOUNTER:-{car_count}', (50, 50), 2, 2)
    cvzone.putTextRect(frame, f'Free_SPACE:-{free_space}', (50, 160), 2, 2)

    cv2.imshow('FRAME', frame)
    key = cv2.waitKey(1) & 0xFF
    if key == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()