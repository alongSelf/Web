#coding=utf-8
from apscheduler.schedulers.blocking import BlockingScheduler
from apscheduler.triggers.cron import CronTrigger
from datetime import datetime
import json
import urllib2
import time
import hashlib
from MySQL import *

def getconfig():
	file = open("config.json")
	config = json.load(file)
	file.close	
	
	return config
	
def getToken(url):	
	content = urllib2.urlopen(url)
	if content.getcode()!=200:
		return ''
	
	sscJson=json.loads(content.read().decode())
	content.close()
	
	return sscJson

def getUrl():
	config = getconfig()
	
	db = MySQL(config)
	db.query('select wx from config')
	result = db.fetchAllRows()
	wxInf = ''
	if result:
		for row in result:
			wxInf = row[0]
	db.close()
	
	wxInf = json.loads(wxInf)

	url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&'
	url = url + 'appid=' + wxInf['AppID'] + '&'
	url = url + 'secret=' + wxInf['AppSecret']
	return url
	
def setToken(token):
	url = 'http://localhost/accessToken/'+token['access_token'] + '/' + hashlib.md5(token['access_token'] + 'Lsy20130123$#').hexdigest()
	print('set token:' + token['access_token'])
	content = urllib2.urlopen(url)
	content.close()
	
def task():
	setToken(getToken(getUrl()))

url = getUrl()
token = getToken(url)
setToken(token)

tick = token['expires_in'] - 10

sched = BlockingScheduler()
sched.add_job(task, 'interval', seconds=tick)
sched.start()
