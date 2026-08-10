<?php

namespace RegalCalendar;

class Config
{
    /* Show messages */
    const SQL_MESSAGES = true; // Show error messages

    /* Define database connection configuration */
    const DATABASE_HOST = "localhost"; // host name or IP address
    const DATABASE_USERNAME = "root"; // database username
    const DATABASE_PASSWORD = "suhair007"; // database username password
    const DATABASE_NAME = "pt"; // database name
    const DATABASE_TYPE = "mysql"; // database type, can be mysql or sqlite
    const DATABASE_PATH = "../datasource/regalcalendar.sql"; // path of SQLite database file

    /* Twitter configuration */
    const SEARCH_QUERY = ""; // search criteria
    const CONSUMER_KEY = ""; // consumer key
    const CONSUMER_SECRET = ""; // consumer secret
    const ACCESS_TOKEN = ""; // access token
    const ACCESS_TOKEN_SECRET = ""; // access token secret

    /* Email configuration */
    const EMAIL_SERVER = "";
    const EMAIL_PORT = 465;
    const EMAIL_TYPE = "ssl";
    const EMAIL_USERNAME = "";
    const EMAIL_PASSWORD = "";
    const EMAIL_TEMPLATE = "Source/Templates/template.html";
}
