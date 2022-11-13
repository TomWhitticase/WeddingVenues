<!DOCTYPE html>
<html lang ="en">
<head>
<title>>Count Task</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
 
<style>
body {
	font-family: "Apple Chancery", Times, serif;
	background-color: #D6D6D6;
}
.center {
	text-align:center;
}
body,td,th {
	color: #06F; 
}
.larger {
	font-size:larger;
}
table {
	margin-left:auto;
    margin-right:auto;
    border: 1px solid;
}
table > tr, th, td{
    border: 1px solid;
    padding: 5px;
}
.cella{
    background-color: white;
}
.cellb{
    background-color: lightblue;
}


</style>
</head>
<body>
<h3 class="center">COA123 - Web Programming</h3>
<h2 class="center">Individual Coursework - Wedding Planner</h2>
<h1 class="center">Task 3 - Count (count.php)</h1>

<?php
$servername = "sci-mysql";
$username = "coa123wuser";
$password = "grt64dkh!@2FD";
$dbname = "coa123wdb";

//get month
$month = strval($_REQUEST["month"]);
if(strlen($month) == 1){
    $month = '0' . $month;
}

// Create connection
$conn = new mysqli($servername, $username, $password);
//select the database
$conn -> select_db($dbname);

//sql query
$sql = "
SELECT venue.name as \"name\", COUNT(venue.venue_id) as \"bookings\"
FROM venue LEFT JOIN venue_booking
ON venue.venue_id = venue_booking.venue_id AND venue_booking.booking_date LIKE '____-$month-__'
GROUP BY venue.venue_id
ORDER BY COUNT(venue.venue_id) DESC
";
$result = $conn->query($sql);

$monthNum = intval($month);
if($monthNum >= 1 && $monthNum <= 12){
    $dateObj   = DateTime::createFromFormat('!m', $monthNum);
    $monthName = $dateObj->format('F'); 

    echo "<h4 class = \"center\">Showing bookings for ".$monthName."</h4>";

    echo "
 <table>
   <tr>
     <th>Name</th>
     <th>Number of Bookings</th> 
   </tr>
   <tr>";
   $class = "cella";
   while($row = $result->fetch_assoc()) {
     if($class == "cella"){
         $class = "cellb";
     }else{
         $class = "cella";
     }
     echo "<tr>";
     echo "<td class=".$class.">".$row["name"]."</td>";
     echo "<td class=".$class.">".$row["bookings"]."</td>";
     echo "</tr>";
   }   
 echo "</table>"; 
}else{
    echo "<h4 class = \"center\">Invalid month. Enter a number between 1 and 12</h4>";
}

?>

  
</body>
</html>
