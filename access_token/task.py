#coding=utf-8
from apscheduler.schedulers.blocking import BlockingScheduler
from apscheduler.triggers.cron import CronTrigger
from datetime import datetime
import ssl 
ssl._create_default_https_context = ssl._create_unverified_context 
import json
import urllib2
import time
import hashlib
from MySQL import *

def getMySqlConfig():
	file = open("config.json")
	config = json.load(file)
	file.close	
	
	return config
	
def getWXConfig():
	config = getMySqlConfig()
	db = MySQL(config)
	db.query('select wx from config')
	result = db.fetchAllRows()
	wxInf = ''
	if result:
		for row in result:
			wxInf = row[0]
	db.close()
	
	return json.loads(wxInf)
    
def getUrl(wxInf):
	url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&'
	url = url + 'appid=' + wxInf['AppID'] + '&'
	url = url + 'secret=' + wxInf['AppSecret']
	
	return url
	
def getToken(url):	
	content = urllib2.urlopen(url)
	if content.getcode()!=200:
		return ''
	
	sscJson=json.loads(content.read().decode())
	content.close()
	
	return sscJson
	
def setToken(token, wxInf):	
	url = 'http://localhost/accessToken/'+token['access_token'] + '/' + hashlib.md5(token['access_token'] + wxInf['accessToken']).hexdigest()
	print('set token:' + token['access_token'])
	content = urllib2.urlopen(url)
	if content.getcode()!=200:
		return -1
	print('setToken:' + content.read().decode())	
	content.close()
	
	return 0
	
def task():
	wxInf = getWXConfig()	
	url = getUrl(wxInf)
	token = getToken(url)
	setToken(token, wxInf)
	
def updateOrderStatus():
	nowtime = time.time()
	timeout = 2 * 60 * 60
	config = getMySqlConfig()
	
	db = MySQL(config)
	sql='update orders set status=4 where status=0 and createtime + '+str(timeout)+' <= '+str(nowtime)+''
	db.update(sql)	
	db.close()
	
wxInf = getWXConfig()
url = getUrl(wxInf)
token = getToken(url)
if(0 != setToken(token, wxInf)):
	print 'get weixin token error.'
	exit()

tick = token['expires_in'] - 10
print 'tick:' + str(tick)

sched = BlockingScheduler()
sched.add_job(task, 'interval', seconds=tick)
sched.add_job(updateOrderStatus, 'interval', seconds=60)
sched.start()
