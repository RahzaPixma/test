<?php

error_reporting(E_ALL);
require_once 'vendor/autoload.php';

use RegalCalendar\Config;
use RegalCalendar\Twitter\TwitterOAuth;

// set connection
$connection = new TwitterOAuth(
    Config::CONSUMER_KEY,
    Config::CONSUMER_SECRET,
    Config::ACCESS_TOKEN,
    Config::ACCESS_TOKEN_SECRET
);

// constructs search query
$tweets = $connection->get("https://api.twitter.com/1.1/search/tweets.json?q=". Config::SEARCH_QUERY);

echo json_encode($tweets);
