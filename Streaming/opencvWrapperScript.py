import cv2
import os
import time
import requests

imageList = []

for file in os.listdir("."):
    if file.endswith(".jpg"):
        imageList.append(file)

frameNum = 0
frameCount = 10

print "Sending images"

while True:
    #time.sleep(1)
    imageName = imageList[frameNum]
    imageMat = cv2.imread(imageName, 0)

    ########################################################
    ######               IMPORTANT PART               ######
    # Note: All the surrounding code is just used to send images to this class.
    # Orlando's code already has that

    #cv2.imshow("Image Stream", imageMat)
    # TODO: I will need to temporarily create the Image1.jpg/Image2.jpg/etc. file and then delete it after I'm done sending it

    # Curl Command equivalent "curl -F "MAX_FILE_SIZE=512000" -F
    # "image1=@/Users/martinmorales/Desktop/LMA
    # Crawler/opencv-streaming/Image3.jpg" http://localhost:8888";

    # Reference:
    # https://stackoverflow.com/questions/26000336/execute-curl-command-within-a-python-script
    #url = "crawlerserver.utep.edu:8000"
    url = "http://127.0.0.1:8080"
    data = {
        'MAX_FILE_SIZE': 512000
    }
    files = {
        'image': open(imageName, 'rb')
    }
    response = requests.post(url, data=data, files=files)
    
    ######               IMPORTANT PART               ######
    ########################################################

    # Change picture
    frameNum = frameNum + 1
    if frameNum == len(imageList):
        frameNum = 0

    k = cv2.waitKey(33)
    if k == 27:    # Esc key to stop
        break
