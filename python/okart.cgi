#!/usr/bin/env python
import cgi, cgitb, shapefile,shutil,zipfile,os,sendmail
print "Content-Type: text/html\n\n"
form = cgi.FieldStorage()
test = form.getvalue("polygon")
name = form.getvalue("name")
email = form.getvalue("email")
street = form.getvalue("street")
postcode = form.getvalue("postcode")
place = form.getvalue("place")
comments = form.getvalue("comments")
os.chdir('..')
w = shapefile.Writer(shapefile.POLYGON)
i = 0
f = open('order.txt', 'r')
ordernr = int(f.read())+1
f.close()
filename = 'shapefiles/B' + str(ordernr)
w.field('ordrenr','N','40')
w.field('Navn','C','254')
w.field('epost','C','254')
w.field('gateadr','C','254')
w.field('postnr','C','4')
w.field('sted','C','254')
w.field('kommentar','C','254')
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
		w.record(ordernr,name,email,street,postcode,place,comments,i)	
		i = i+1
w.save(filename)
f = open('order.txt','w')
f.write(str(ordernr));
f.close()
prjfile = 'prj/template.prj'
prjdest = filename+'.prj'
shutil.copyfile(prjfile, prjdest)
zipfilename = filename+'.zip'
archive = zipfile.ZipFile(zipfilename, 'w')
os.chdir('./shapefiles/')
archive.write('B'+str(ordernr)+'.prj')
archive.write('B'+str(ordernr)+'.shp')
archive.write('B'+str(ordernr)+'.dbf')
archive.write('B'+str(ordernr)+'.shx')
archive.close()
message = 'Ny bestilling B'+str(ordernr)+' fra okart \nFra:\n'+name+'\n'+email+'\n'+street+'\n'+postcode+' '+place+'\nKommentar:\n'+comments
sendmail.send_mail('torbjvi@stud.ntnu.no', ['okarttest@gmail.com'], 'Ny bestilling fra Okart B'+str(ordernr), message, ['B'+str(ordernr)+'.zip'], 'smtp.stud.ntnu.no')
os.unlink('B'+str(ordernr)+'.zip')
print '<html> '
print '<head> '
print '<meta http-equiv="refresh" content="0;url=../takk.html" /> '
print '<title>You are going to be redirected</title> '
print '</head> '
print '<body> '
print 'Redirecting...'
print '</body> '
print '</html>'