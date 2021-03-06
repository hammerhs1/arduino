<?php
/*
 * The MIT License (MIT)
 * Copyright (c) 2016 tim4dev.com
 *
 */

 define( 'ABSPATH', dirname(__FILE__) . '/' );
require_once( ABSPATH . 'config.php' );
require_once( ABSPATH . 'function.php' );

// проверка правомочности доступа
checkAccess();

if ( ! isset($_GET["id"]) )  {
    echo "<pre>idSensor not found. Exit()</pre>";
    exit();
}
$idSensor = intval($_GET["id"]);

header("Content-Type: text/plain");
header("Content-Disposition: attachment; filename=dht-$idSensor.csv");

define( 'ABSPATH', dirname(__FILE__) . '/' );
require_once( ABSPATH . 'config.php' );

$query_dht = "SELECT dateCreate, temperature, humidity
            FROM $dbtable_dht
            WHERE (idSensor = $idSensor) AND ((errors = 0) OR (errors IS NULL))
            ORDER BY dateCreate";

$db = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname);

/* check connection */
if ( mysqli_connect_errno() ) {
    echo "0\n", 'ERROR. mysqli_connect_error ', mysqli_connect_error();
    exit();
}

//echo $query_dht; echo "\r\n"; exit;

$res = mysqli_query($db, $query_dht);
if ( !$res )
{
    echo "0\n", "ERROR. mysqli_query: ", mysqli_sqlstate($db);
    @ mysqli_close($db);
    exit();
}

echo "dateCreate;temperature;humidity\n"; // заголовки

while($row = mysqli_fetch_array($res)) {
    echo $row["dateCreate"], ';', $row["temperature"], ';', $row["humidity"], "\n";
}

mysqli_close($db);

?>