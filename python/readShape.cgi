#!/usr/bin/env python
import sys,cgi, cgitb, shapefile,shutil,zipfile,os,sendmail
print "Content-Type: text/plain\n\n"
sf = shapefile.Reader("AreaTest_4326")
shapes = sf.shapes()
print "{"
print "    \"type\": \"FeatureCollection\","
print "    \"features\": ["
for shape in shapes[:-1]:
	print "        {"
	print "            \"type\": \"Feature\","
	print "            \"geometry\": {"
	print "                \"type\": \"Polygon\","
	print "                \"coordinates\": ["
	print shape.points
	print "]"
	print "            }"
	print "        },",
print "        {"
print "            \"type\": \"Feature\","
print "            \"geometry\": {"
print "                \"type\": \"Polygon\","
print "                \"coordinates\": ["
print shapes[-1].points
print "]"
print "            }"
print "        }",
print "    ]"
print "}"