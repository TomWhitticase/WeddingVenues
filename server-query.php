<?php
//database login details
$servername = "sql8.freemysqlhosting.net";
$username = "sql8567995";
$password = "6lK89C8ZiD!@2FD";
$dbname = "sql8567995";

//sanitise inputs to avoid malicous code injection
function sanitise_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Create connection
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//select the database
$conn->select_db($dbname);

//request data
$dates = explode(',', sanitise_input($_REQUEST["dates"]));
$partySize = sanitise_input($_REQUEST["partySize"]);
$cateringGrade = sanitise_input($_REQUEST["cateringGrade"]);

$results = array();
foreach ($dates as $date) {
    //sql query to get suitable venues
    $suitableVenuesSql = "
    SELECT 
    venue.venue_id, 
    name, 
    capacity as 'Capacity', 
    CONCAT('£', catering.cost) as 'Catering Cost pp', 
    CONCAT('£', weekend_price) as 'Weekend Price', 
    CONCAT('£', weekday_price) as 'Weekday Price', 
    IF(licensed, 'Yes', 'No') as 'Licensed', 
    (
        SELECT 
        DISTINCT COUNT(venue_booking.venue_id) 
        FROM 
        venue_booking 
        WHERE 
        venue_booking.venue_id = venue.venue_id
    ) as 'Total Bookings' 
    FROM 
    venue 
    LEFT JOIN catering ON venue.venue_id = catering.venue_id 
    WHERE 
    catering.grade = $cateringGrade 
    AND venue.capacity > $partySize 
    AND NOT EXISTS (
        SELECT 
        venue_id 
        FROM 
        venue_booking 
        WHERE 
        venue_booking.booking_date = '$date' 
        AND venue_booking.venue_id = venue.venue_id
    )";

    $result = $conn->query($suitableVenuesSql);
    $suitableVenues = array();
    while ($r = mysqli_fetch_assoc($result)) {
        array_push($suitableVenues, $r);
    }
    $results[$date] = $suitableVenues;
}
echo json_encode($results);