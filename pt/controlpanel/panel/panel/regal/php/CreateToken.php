<?php

    error_reporting(E_ALL);
    session_start();
    $token = $_SESSION[ "RegalCalendarToken"]= md5(uniqid(mt_rand(), true));
    echo $token;
