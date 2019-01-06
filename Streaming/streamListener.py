import web
import json
import time
from threading import Thread
import logging

# Reason for Log https://stackoverflow.com/questions/49140405/how-to-suppress-web-py-output?rq=1
class Log:
    def __init__(self, xapp, logname="wsgi"):
        class O:
            def __init__(self, xapp, logname="wsgi"):
                self.logger = logging.getLogger(logname)
            def write(self, s):
                if s[-1] == '\n':
                    s = s[:-1]
                if s == "":
                    return
                if self.ignore(s):
                    return
                self.logger.debug(s)
        self.app = xapp
        self.f = O(logname)
    def __call__(self, environ, start_response):
        environ['wsgi.errors'] = self.f
        return self.app(environ, start_response)

# Handles all POST requests (reads the file from the payload, saves it in FrameImages)
class RequestHandler:

	def POST(self):
		global frameCount
		file = web.input()['image']
		fileDir = "FrameImages/"
		fileName = "Stream1Frame"

		fout = open(fileDir +'/'+ fileName + ".jpg",'w')
		fout.write(file)
		fout.close()
		#print "Received image frame"
		frameCount = frameCount + 1

def calculateFPS():		# Thread that outputs frames per second
	global frameCount
	while True:
		print "FPS: " + str(frameCount)
		frameCount = 0
		time.sleep(1)

def startFPSThread():
	global frameCount
	fpsThread = Thread(target = calculateFPS)
	frameCount = 0
	fpsThread.daemon = True		# Allows me to kill it with Ctrl+C
	fpsThread.start()

def createListener():
	urls = (
		'/', 'RequestHandler'
	)
	return web.application(urls, globals())

if __name__ == "__main__":
	startFPSThread()
	app = createListener()
	app.run(Log)		# Log allows us to ignore any POST/GET notifications in the terminal. Clutters up terminal if you leave them