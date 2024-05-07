import cv2
import numpy as np
import pickle
import pandas as pd
from ultralytics import YOLO
import cvzone

with open("freedomtech", "rb") as f:
    data = pickle.load(f)
    polylines, area_names = data['polylines'], data['area_names']

my_file = open("coco.txt", "r")
data = my_file.read()
class_list = data.split("\n")

model = YOLO('yolov8s.pt')

cap = cv2.VideoCapture('Highway.mp4')
count = 0

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

    cars_list = []
    trucks_list = []
    buses_list = []

    a=results[0].boxes.data
    px = pd.DataFrame(a).astype("float")

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
            cars_list.append([cx, cy])
            cv2.rectangle(frame, (x1, y1), (x2, y2), (255, 255, 255), 2)
        elif 'truck' in c:
            trucks_list.append([cx, cy])
            cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 0, 255), 2)
        elif 'bus' in c:
            buses_list.append([cx, cy])
            cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 255), 2)

    counter_cars = []
    counter_trucks = []
    counter_buses = []
    list2 = []

    for i, polyline in enumerate(polylines):
        list2.append(i)
        cv2.polylines(frame, [polyline], True, (0, 255, 0), 2)
        cvzone.putTextRect(frame, f'{area_names[i]}', tuple(polyline[0]), 1, 1)

        for i1 in cars_list:
            cx1 = i1[0]
            cy1 = i1[1]
            result = cv2.pointPolygonTest(polyline, ((cx1, cy1)), False)
            if result >= 0:
                cv2.circle(frame, (cx1, cy1), 5, (255, 0, 0), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_cars.append(cx1)

        for i2 in trucks_list:
            cx2 = i2[0]
            cy2 = i2[1]
            result = cv2.pointPolygonTest(polyline, ((cx2, cy2)), False)
            if result >= 0:
                cv2.circle(frame, (cx2, cy2), 5, (0, 255, 0), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_trucks.append(cx2)

        for i3 in buses_list:
            cx3 = i3[0]
            cy3 = i3[1]
            result = cv2.pointPolygonTest(polyline, ((cx3, cy3)), False)
            if result >= 0:
                cv2.circle(frame, (cx3, cy3), 5, (0, 255, 255), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_buses.append(cx3)

    car_count = len(counter_cars)
    truck_count = len(counter_trucks)
    bus_count = len(counter_buses)
    free_space = len(list2) - car_count - truck_count - bus_count

    cvzone.putTextRect(frame, f'CARCOUNTER:-{car_count}', (50, 50), 2, 2)
    cvzone.putTextRect(frame, f'TRUCKCOUNTER:-{truck_count}', (50, 100), 2, 2)
    cvzone.putTextRect(frame, f'BUSCOUNTER:-{bus_count}', (50, 150), 2, 2)
    cvzone.putTextRect(frame, f'free_space:-{free_space}', (50, 200), 2, 2)

    cv2.imshow('FRAME', frame)
    key = cv2.waitKey(1) & 0xFF

cap.release()
cv2.destroyAllWindows()