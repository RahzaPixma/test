<?php

error_reporting(E_ALL);
require_once 'vendor/autoload.php';

use RegalCalendar\Config;
use RegalCalendar\Database\Connection;

// set connection
$database = new Connection(
    Config::DATABASE_TYPE,
    Config::DATABASE_HOST,
    Config::DATABASE_USERNAME,
    Config::DATABASE_PASSWORD,
    Config::DATABASE_NAME,
    Config::DATABASE_PATH
);
$mode = isset ($_GET["mode"]) ? filter_var($_GET["mode"], FILTER_SANITIZE_STRING) : 0;

// get events for calendar
if ($mode == 1) {
    $month = isset ($_GET["month"]) ? filter_var($_GET["month"], FILTER_SANITIZE_STRING) : 0;
    $year = isset ($_GET["year"]) ? filter_var($_GET["year"], FILTER_SANITIZE_STRING) : 0;

	/*
	$month2 = 100 + intval($month);
	$month3 = substr( (string)$month2, 2,2);
	
	$year2 = 10000 + intval($year);
	$year3 = substr( (string)$year2, 2,4);
	
    echo $database->getEvents($month3, $year3);
	*/

	 echo $database->getEvents($month, $year);
	 
// get events for grid
} elseif ($mode == 2) {
    $draw = isset ($_GET["draw"]) ? filter_var($_GET["draw"], FILTER_SANITIZE_STRING) : 0;
    $start = isset ($_GET["start"]) ? filter_var($_GET["start"], FILTER_SANITIZE_STRING) : 0;
    $length = isset ($_GET["length"]) ? filter_var($_GET["length"], FILTER_SANITIZE_STRING) : 0;
    $search = isset ($_GET["search"]) ? $_GET["search"] : 0;
    $order = isset ($_GET["order"]) ? $_GET['order'] :0 ;

    echo $database->getAllEvents($draw, $start, $length, $order, $search);

// get subscriptions by event
} elseif ($mode == 3) {
    $event = isset ($_GET["event"]) ? filter_var($_GET["event"], FILTER_SANITIZE_STRING) : 0;

    echo $database->getSubscriptionsByEvent($event);
}

/* DEBUG 
$year = '2016';
$month = '06';
echo "tarikh LIKE '%$year-$month%'";
$mode = 2;
echo $database->getEvents($month, $year);
*/