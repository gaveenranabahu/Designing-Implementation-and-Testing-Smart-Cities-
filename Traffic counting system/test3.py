import cv2
import numpy as np
import pickle
import pandas as pd
from ultralytics import YOLO
import cvzone
import requests
from datetime import datetime
import time


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

    cars_list_outgoing = []
    trucks_list_outgoing = []
    buses_list_outgoing = []

    cars_list_incoming = []
    trucks_list_incoming = []
    buses_list_incoming = []

    a = results[0].boxes.data
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
            if cx < 510:  # Adjust the x-coordinate threshold for outgoing vehicles
                cars_list_outgoing.append([cx, cy])
                cv2.rectangle(frame, (x1, y1), (x2, y2), (255, 255, 255), 2)
            else:
                cars_list_incoming.append([cx, cy])
                cv2.rectangle(frame, (x1, y1), (x2, y2), (255, 0, 0), 2)
        elif 'truck' in c:
            if cx < 510:  # Adjust the x-coordinate threshold for outgoing vehicles
                trucks_list_outgoing.append([cx, cy])
                cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 0, 255), 2)
            else:
                trucks_list_incoming.append([cx, cy])
                cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 0), 2)
        elif 'bus' in c:
            if cx < 510:  # Adjust the x-coordinate threshold for outgoing vehicles
                buses_list_outgoing.append([cx, cy])
                cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 255, 255), 2)
            else:
                buses_list_incoming.append([cx, cy])
                cv2.rectangle(frame, (x1, y1), (x2, y2), (0, 0, 255), 2)

    counter_cars_outgoing = []
    counter_trucks_outgoing = []
    counter_buses_outgoing = []

    counter_cars_incoming = []
    counter_trucks_incoming = []
    counter_buses_incoming = []

    list2 = []

    for i, polyline in enumerate(polylines):
        list2.append(i)
        cv2.polylines(frame, [polyline], True, (0, 255, 0), 2)
        cvzone.putTextRect(frame, f'{area_names[i]}', tuple(polyline[0]), 1, 1)

        for i1 in cars_list_outgoing:
            cx1 = i1[0]
            cy1 = i1[1]
            result = cv2.pointPolygonTest(polyline, ((cx1, cy1)), False)
            if result >= 0:
                cv2.circle(frame, (cx1, cy1), 5, (255, 0, 0), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_cars_outgoing.append(cx1)

        for i2 in trucks_list_outgoing:
            cx2 = i2[0]
            cy2 = i2[1]
            result = cv2.pointPolygonTest(polyline, ((cx2, cy2)), False)
            if result >=0: 
                cv2.circle(frame, (cx2, cy2), 5, (0, 255, 0), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_trucks_outgoing.append(cx2)

        for i3 in buses_list_outgoing:
            cx3 = i3[0]
            cy3 = i3[1]
            result = cv2.pointPolygonTest(polyline, ((cx3, cy3)), False)
            if result >= 0:
                cv2.circle(frame, (cx3, cy3), 5, (0, 255, 255), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_buses_outgoing.append(cx3)

        for i4 in cars_list_incoming:
            cx4 = i4[0]
            cy4 = i4[1]
            result = cv2.pointPolygonTest(polyline, ((cx4, cy4)), False)
            if result >= 0:
                cv2.circle(frame, (cx4, cy4), 5, (255, 0, 0), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_cars_incoming.append(cx4)

        for i5 in trucks_list_incoming:
            cx5 = i5[0]
            cy5 = i5[1]
            result = cv2.pointPolygonTest(polyline, ((cx5, cy5)), False)
            if result >= 0:
                cv2.circle(frame, (cx5, cy5), 5, (0, 255, 0), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_trucks_incoming.append(cx5)

        for i6 in buses_list_incoming:
            cx6 = i6[0]
            cy6 = i6[1]
            result = cv2.pointPolygonTest(polyline, ((cx6, cy6)), False)
            if result >= 0:
                cv2.circle(frame, (cx6, cy6), 5, (0, 255, 255), -1)
                cv2.polylines(frame, [polyline], True, (0, 0, 255), 2)
                counter_buses_incoming.append(cx6)

    car_count_outgoing = len(counter_cars_outgoing)
    truck_count_outgoing = len(counter_trucks_outgoing)
    bus_count_outgoing = len(counter_buses_outgoing)
    free_space_outgoing = len(list2) - car_count_outgoing - truck_count_outgoing - bus_count_outgoing

    car_count_incoming = len(counter_cars_incoming)
    truck_count_incoming = len(counter_trucks_incoming)
    bus_count_incoming = len(counter_buses_incoming)
    free_space_incoming = len(list2) - car_count_incoming - truck_count_incoming - bus_count_incoming

    cvzone.putTextRect(frame, f'OUTGOING VEHICLES', (50, 50), 2, 2)
    cvzone.putTextRect(frame, f'CAR COUNTER: {car_count_outgoing}', (50, 100), 2, 2)
    cvzone.putTextRect(frame, f'TRUCK COUNTER: {truck_count_outgoing}', (50, 150), 2, 2)
    cvzone.putTextRect(frame, f'BUS COUNTER: {bus_count_outgoing}', (50, 200), 2, 2)
   

    cvzone.putTextRect(frame, f'INCOMING VEHICLES', (600, 50), 2, 2)
    cvzone.putTextRect(frame, f'CAR COUNTER: {car_count_incoming}', (600, 100), 2, 2)
    cvzone.putTextRect(frame, f'TRUCK COUNTER: {truck_count_incoming}', (600, 150), 2, 2)
    cvzone.putTextRect(frame, f'BUS COUNTER: {bus_count_incoming}', (600, 200), 2, 2)
   

    fullcount = car_count_outgoing + truck_count_outgoing + bus_count_outgoing + car_count_incoming + truck_count_incoming + bus_count_incoming
    if fullcount>0 :

        # Get the current timestamp

        # Define the data to be sent
        data = {
            'car_count_outgoing': car_count_outgoing,
            'truck_count_outgoing': truck_count_outgoing,
            'bus_count_outgoing': bus_count_outgoing,
            'free_space_outgoing': free_space_outgoing,
            'car_count_incoming': car_count_incoming,
            'truck_count_incoming': truck_count_incoming,
            'bus_count_incoming': bus_count_incoming,
            'free_space_incoming': free_space_incoming,
            'place': 'Downtown'
        }

        # Send a POST request to the PHP script
        response = requests.post('https://porsgrunntraffic.com/Insert_data.php', data=data)

        # Check the response
        if response.status_code == 200:
            print("Data sent successfully.")
        else:
            print("Error sending data.")
        time.sleep(0.2)
    else:
        print("Data not sent")

    cv2.imshow('FRAME', frame)
    key = cv2.waitKey(50) & 0xFF
    if key == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()