#!/bin/sh

DEST="/var/www/html"
FILES="access.php bmp180.php  housePower.php  humidity.php  m_temp.php solarPower.php sys_info.php  tabulate.php tempTab.php"

for file in $FILES 
do
	echo $DEST/$file
#	diff $file $DEST/$file
	sudo cp $file $DEST/$file
done 
