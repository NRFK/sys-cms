<?php

$db_host      = 'localhost';
$db_user      = 'cms_site';
$db_pass      = '8%0xVtknMIdt6wv!';
$db_name      = 'cms_site';
//$db_ssl_cert  = '/var/www/vhosts/nakedkitchens.dev/akamai-db.crt';
$db_ssl       = false;
$db_port      = 3306; // fixed typo from $db_post

$conn = mysqli_init();

if (!$conn) {
    die('MySQLi initialization failed.');
}

// Enable SSL if required
if ($db_ssl) {
    if (!mysqli_ssl_set($conn, NULL, NULL, $db_ssl_cert, NULL, NULL)) {
        die('Failed to set SSL options.');
    }
}

// Connect to database
if (!mysqli_real_connect($conn, $db_host, $db_user, $db_pass, $db_name, $db_port, NULL, $db_ssl ? MYSQLI_CLIENT_SSL : 0)) {
    die('Connection to database failed: ' . mysqli_connect_error());
}

// Optional: Confirm SSL cipher is in use
if ($db_ssl) {
    $res = $conn->query("SHOW STATUS LIKE 'Ssl_cipher'");
    $row = $res->fetch_assoc();
    if (empty($row['Value'])) {
        die("SSL connection not established. Check certificate or server settings.");
    }
}
?>
