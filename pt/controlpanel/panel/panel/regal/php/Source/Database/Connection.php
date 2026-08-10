<?php

namespace RegalCalendar\Database;

class Connection
{
    /**
     * @param $databaseType
     * @param $dbHost
     * @param $databaseUsername
     * @param $databasePassword
     * @param $databaseName
     * @param $databasePath
     */
    public function __construct($dbType, $dbHost, $dbUsername, $dbPassword, $dbName, $dbPath)
    {
        $this->dbType = $dbType;
        $this->dbHost = $dbHost;
        $this->dbUsername = $dbUsername;
        $this->dbPassword = $dbPassword;
        $this->dbName = $dbName;
        $this->dbPath = $dbPath;
        
        if ($this->dbType == 'mysql') {
            /* MySQL configuration */
            $this->database = new DatabaseHandler(
                "mysql:host=" . $this->dbHost .
                ";dbname=" . $this->dbName,
                $this->dbUsername,
                $this->dbPassword
            );
        } elseif ($this->dbType == 'sqlite') {
            /* SQLite configuration */
            $this->database = new DatabaseHandler("sqlite:" . $this->dbPath);
        }
        
    }
    
    // retrieves events by month and year
    /**
     * @param $month
     * @param $year
     * @return string
     */
    public function getEvents($month, $year)
    {
        $json = "";
		
/*	
	$month2 = 100 + intval($month);
	$month3 = substr( (string)$month2, 2,2);
	
	$year2 = 10000 + intval($year);
	$year3 = substr( (string)$year2, 2,4);
*/		

        if (isset($this->database)) {
//           $events = $this->database->select("events", "date LIKE '%$month/$year'", "", "*", ""); 
//		   $events = $this->database->select("template_kuliah", "date_format(tarikh,'%d/%m/%Y') LIKE '%$month/$year'", "", "*", ""); 
			$events = $this->database->select("template_kuliah", "date_format(tarikh,'%m/%Y') LIKE '%$month/$year'", "", "id, date_format(tarikh,'%d/%m/%Y') as date, date_format(masa,'%H:%i') as time, concat('fa-align-center') as icon, header as title, concat_ws('<br>',nullif(penceramah,''),nullif(tajuk,''),nullif(hari,''),nullif(waktu,''), concat(IF(status=1,'Papar=Ya','Papar=Tidak')) ) as text, concat('') as location, header as previewText, concat('') as cityWeather", "");			
//			$events = $this->database->select("template_kuliah", "", "", "id, date_format(tarikh,'%d/%m/%Y') as date, date_format(masa,'%H:%i') as time, concat('icon') as icon, header as title, tajuk as text, waktu as location, header as previewText, hari as cityWeather", "");			
            $json = json_encode($events);
            $json = htmlspecialchars($json, ENT_NOQUOTES, 'UTF-8');
        }
        return $json;
    }

    // retrieves events by month and year
    /**
     * @param $idEvent
     * @return array|int|string
     */
    public function getEventById($idEvent)
    {
        $event = array();
        if (isset($this->database)) {
            $event = $this->database->select("events", "id = $idEvent", "", "*", "");
        }
        return $event;
    }

    // retrieves all events, filtered events, and pagination
    /**
     * @param $draw
     * @param $start
     * @param $length
     * @param $order
     * @param $search
     * @return string
     */
    public function getAllEvents($draw, $start, $length, $order, $search)
    {
        // set up column names
        $columnNames = array(
            0 => 'id',
            1 => 'date',
            2 => 'time',
            3 => 'icon',
            4 => 'title',
            5 => 'text',
            6 => 'location',
            7 => 'previewText',
            8 => 'cityWeather'
        );
        
       // order by column and direction
        $column = $columnNames[$order[0]['column']] . " " . $order[0]['dir'];

        $data = array();

        // var_dump($search);
        // if filter is not present
        if (empty($search["value"])) {
            $totalEvents = $events = $this->database->select("events", "", "", "*", "");
            $events = $this->database->select("events", "", "", "*", " ORDER BY $column LIMIT $start , $length");
        } else {
        // filter is present
            $filter = $search["value"];
            $bind = array(
                ":search" => "%$filter%"
            );
            // get total events
            $totalEvents = $this->database->select("events", "", "", "*", " LIMIT $start , $length");
            $events = $this->database->select(
                "events",
                "title LIKE :search OR date LIKE :search OR time LIKE :search OR location LIKE :search",
                $bind,
                "*",
                " ORDER BY $column LIMIT $start ,
                $length"
            );
        }

        if ($events) {
            foreach ($events as $event) {
                $item = array(
                    $event["id"],
                    $event["date"],
                    $event["time"],
                    $event["icon"],
                    $event["title"],
                    $event["text"],
                    $event["location"],
                    $event["previewText"],
                    $event["cityWeather"]
                );
                $data[] = $item;
            }
        }

    // outputs events in json format
        $obj = (object) array(
            "draw" => $draw,
            "recordsTotal" => count($events),
            "recordsFiltered" => count($totalEvents),
            "data" => $data
        );
        
        $json = json_encode($obj);
        return htmlspecialchars($json, ENT_NOQUOTES, 'UTF-8');
    }
    
    // add an event to database
    /**
     * @param $title
     * @param $text
     * @param $location
     * @param $cityWeather
     * @param $previewText
     * @param $icon
     * @param $time
     * @param $date
     */
    public function addEvent($title, $text, $location, $cityWeather, $previewText, $icon, $time, $date)
    {
        // create an event structure
        $event = array (
            'title' => $title,
            'text' => $text,
            'location' => $location,
            'cityWeather' => $cityWeather,
            'previewText' => $previewText ,
            'icon' => $icon,
            'time' => $time,
            'date'  => $date
        );

        $this->database->insert("events", $event);
    }
    
    // update an event
    /**
     * @param $id
     * @param $title
     * @param $text
     * @param $location
     * @param $cityWeather
     * @param $previewText
     * @param $icon
     * @param $time
     * @param $date
     */
    public function updateEvent($id, $title, $text, $location, $cityWeather, $previewText, $icon, $time, $date)
    {
        // create an event structure
        $event = array (
            'title' => $title,
            'text' => $text,
            'location' => $location,
            'cityWeather' => $cityWeather,
            'previewText' => $previewText ,
            'icon' => $icon,
            'time' => $time,
            'date'  => $date
        );

        // update events by id
        $this->database->update("events", $event, "id = $id");
    }
    
    // delete an event
    public function deleteEvent($id)
    {
        $this->database->delete("subscriptions", "idEvent = $id");
        $this->database->delete("events", "id = $id");
    }

    // retrieves subscriptions by event (json)
    /**
     * @param $idEvent
     * @return string
     */
    public function getSubscriptionsByEvent($idEvent)
    {
        $json = "";

        if (isset($this->database)) {
            $events = $this->database->select("subscriptions", "idEvent = $idEvent", "", "id, email, name", "");
            $json = json_encode($events);

            $json = htmlspecialchars($json, ENT_NOQUOTES, 'UTF-8');
        }
        return $json;
    }

    // retrieves subscriptions by event (array)
    /**
     * @param $idEvent
     * @return array|int|string
     */
    public function getSubscriptionsByEventArray($idEvent)
    {
        $events = array();

        if (isset($this->database)) {
            $events = $this->database->select("subscriptions", "idEvent = $idEvent", "", "id, email, name", "");
        }
        return $events;
    }

    // subscribe to an event
    /**
     * @param $idEvent
     * @param $email
     * @param $name
     */
    public function addSubscription($idEvent, $email, $name)
    {
        // check if exists
        $count = Count(
            $this->database->select("subscriptions", "idEvent = $idEvent AND email = '$email' ", "", "*", "")
        );

        if ($count == 0) {
            // create an event structure
            $subscription = array(
                'idEvent' => $idEvent,
                'email' => $email,
                'name' => $name
            );
            $this->database->insert("subscriptions", $subscription);
        }
    }
    
    // import events from *.ics file
    /**
     * @param $name
     */
    public function importEvents($name)
    {
        // defines calendar path
        $path = "files/" . $name;
        $ical   = new ICal($path);

        // retrieves calendar events
        $events = $ical->events();

        // defines timezone
        $region = $ical->cal['VCALENDAR']['X-WR-TIMEZONE'];
        // format iCalendar events to json
        foreach ($events as $event) {
            if (strlen($event['DTSTART']) > 8) {
                $time = substr($event['DTSTART'], 9, 2) . ":" . substr($event['DTSTART'], 11, 2);
            } else {
                $time = "00:00";
            }
            $dt = new \DateTime($time);
            $dt->setTimezone(new \DateTimeZone($region));
            $timeZone = $dt->format('H:i');
    
            $eventDate =
                substr($event['DTSTART'], 6, 2) .
                "/" .
                substr($event['DTSTART'], 4, 2) .
                "/" .
                substr($event['DTSTART'], 0, 4);
    
            $icsEvent = array (
                'title' => $event['SUMMARY'],
                'text' => $event['DESCRIPTION'] ,
                'location' => $event['LOCATION'],
                'cityWeather' => $event['LOCATION'],
                'previewText' => "" ,
                'icon' => "fa-circle",
                'time' => $timeZone,
                'date'  => $eventDate
            );
    
             $this->database->insert("events", $icsEvent);
        }
    }
}
