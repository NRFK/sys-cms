<?php

$allowed_ips = array('81.144.237.92');

if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips))
{
	require('temp.html');
	exit;
}
else
{
	
}	

?>