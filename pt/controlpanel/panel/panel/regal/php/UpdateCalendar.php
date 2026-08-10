<?php

error_reporting(E_ALL);
require_once 'vendor/autoload.php';
require_once 'vendor/purifier/HTMLPurifier.auto.php';

use RegalCalendar\Config;
use RegalCalendar\Database\Connection;

// establish connection
$database = new Connection(
    Config::DATABASE_TYPE,
    Config::DATABASE_HOST,
    Config::DATABASE_USERNAME,
    Config::DATABASE_PASSWORD,
    Config::DATABASE_NAME,
    Config::DATABASE_PATH
);
$config = HTMLPurifier_Config::createDefault();

// filter HTML content
$purifier = new HTMLPurifier($config);

$action = isset ($_POST["action"]) ? filter_var($_POST["action"], FILTER_SANITIZE_STRING) : 0;
    
// new event
if ($action == 1) {
    $title = filter_var($_POST["title"], FILTER_SANITIZE_STRING);
    $text = $purifier->purify($_POST["text"]);
    $location = filter_var($_POST["location"], FILTER_SANITIZE_STRING);
    $cityWeather = filter_var($_POST["cityWeather"], FILTER_SANITIZE_STRING);
    $previewText = filter_var($_POST["previewText"], FILTER_SANITIZE_STRING);
    $icon = filter_var($_POST["icon"], FILTER_SANITIZE_STRING);
    $time= filter_var($_POST["hour"], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST["date"], FILTER_SANITIZE_STRING);

    $database->addEvent($title, $text, $location, $cityWeather, $previewText, $icon, $time, $date) ;

// update event
} elseif ($action == 2) {
    $id = filter_var($_POST["event_id"], FILTER_SANITIZE_STRING);
    $title = filter_var($_POST["title"], FILTER_SANITIZE_STRING);
    $text = $purifier->purify($_POST["text"]);
    $location = filter_var($_POST["location"], FILTER_SANITIZE_STRING);
    $cityWeather = filter_var($_POST["cityWeather"], FILTER_SANITIZE_STRING);
    $previewText = filter_var($_POST["previewText"], FILTER_SANITIZE_STRING);
    $icon = filter_var($_POST["icon"], FILTER_SANITIZE_STRING);
    $time= filter_var($_POST["hour"], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST["date"], FILTER_SANITIZE_STRING);
    
    $database->updateEvent($id, $title, $text, $location, $cityWeather, $previewText, $icon, $time, $date);
    
// delete event
} elseif ($action == 3) {
    $id = filter_var($_POST["event_id"], FILTER_SANITIZE_STRING);
    
    $database->deleteEvent($id);
} elseif ($action == 4) {
    $name = $_POST["name"];
    
    $database->importEvents($name);
} elseif ($action == 5) {
    $id = filter_var($_POST["event_id"], FILTER_SANITIZE_STRING);
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = $_POST["email"];
    $result = filter_var($email, FILTER_VALIDATE_EMAIL);
    
    if ($result) {
        $database->addSubscription($id, $email, $name);
    }
}
