#!/usr/bin/env python
import cgi, cgitb, shapefile,shutil,zipfile,os,sendmail
cgitb.enable()
form = cgi.FieldStorage()
test = form.getvalue("polygon")
ordernr = int(form.getvalue("orderid"))
os.chdir('..')
w = shapefile.Writer(shapefile.POLYLINE)
i = 0
filename = 'shapefiles/okart' + str(ordernr)
w.field('ordrenr','N','40')
w.field('Polygonnr','N','3')
i = 0
for latlng in test:
	if latlng != 'empty':
		polys  = latlng.split('|')
		subpoly = []
		for coords in polys:
			lng = coords.split(', ')[0]
			lat = coords.split(', ')[1]
			coord = [float(lng), float(lat)]
			subpoly.append(coord)
		polygon = []
		polygon.append(subpoly)
		w.poly(parts=polygon)
		w.record(ordernr,i)	
		i = i+1
w.save(filename)
prjfile = 'prj/template.prj'
prjdest = filename+'.prj'
shutil.copyfile(prjfile, prjdest)
zipfilename = filename+'.zip'
archive = zipfile.ZipFile(zipfilename, 'w')
os.chdir('./shapefiles/')
archive.write('okart'+str(ordernr)+'.prj')
archive.write('okart'+str(ordernr)+'.shp')
archive.write('okart'+str(ordernr)+'.dbf')
archive.write('okart'+str(ordernr)+'.shx')
archive.close()
os.remove('okart'+str(ordernr)+'.prj')
os.remove('okart'+str(ordernr)+'.shp')
os.remove('okart'+str(ordernr)+'.dbf')
os.remove('okart'+str(ordernr)+'.shx')
print("Location: ../shapefiles/okart"+str(ordernr)+".zip\n")