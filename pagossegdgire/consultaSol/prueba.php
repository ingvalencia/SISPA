<?php

$format = 'Y-m-d';
$date = '2010-10-32';

$d = DateTime::createFromFormat($format, $date);
    $k = $d && $d->format($format) == $date;
	
	if($k==0){ echo "www"; }
	echo $d->format($format);
	
	