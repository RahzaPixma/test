<?php

error_reporting(E_ALL);
require_once 'vendor/autoload.php';

use RegalCalendar\Config;
use RegalCalendar\Database\Connection;

if (isset($_POST["token"])) {
    session_start();
    if ($_POST["token"] == $_SESSION["RegalCalendarToken"]) {
        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance(Config::EMAIL_SERVER, Config::EMAIL_PORT, Config::EMAIL_TYPE)
            ->setUsername(Config::EMAIL_USERNAME)
            ->setPassword(Config::EMAIL_PASSWORD)
        ;

        // establish connection
        $database = new Connection(
            Config::DATABASE_TYPE,
            Config::DATABASE_HOST,
            Config::DATABASE_USERNAME,
            Config::DATABASE_PASSWORD,
            Config::DATABASE_NAME,
            Config::DATABASE_PATH
        );

        $event = isset ($_POST["event"]) ? filter_var($_POST["event"], FILTER_SANITIZE_STRING) : 0;

        $eventData = $database->getEventById($event)[0];
        $subscriptions = $database->getSubscriptionsByEventArray($event);

        $mailer = Swift_Mailer::newInstance($transport);

        $content = file_get_contents(Config::EMAIL_TEMPLATE);

        $content = str_replace("{title}", $eventData["title"], $content);
        $content = str_replace("{location}", $eventData["location"], $content);
        $content = str_replace("{date}", $eventData["date"] . " " . $eventData["time"], $content);
        $content = str_replace("{content}", $eventData["text"], $content);

        foreach ($subscriptions as $subscription) {
            // Create a message

            $message = Swift_Message::newInstance($eventData["title"])
                ->setContentType("text/html")
                ->setFrom(array(Config::EMAIL_USERNAME))
                ->setTo(array($subscription["email"] => $subscription["name"]))
                ->setBody($content);
            try {
                // Send the message
                $result = $mailer->send($message);
            } catch (Exception $e) {
                echo($e->getMessage());
            }
        }
    }
}
