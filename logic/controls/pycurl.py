#! /usr/bin/python  
# coding=utf-8


import urllib2
import time
import sys
# import json

# print time.time()
arr = sys.argv
# print arr
if len(arr) > 1:
	try:
	    url = arr[1]
	    f = urllib2.urlopen(url, timeout=10)
	    print f.readlines()[0]
	    # temp = f.readlines().split("|");
	    # print temp
	except Exception, e:
	    print e
