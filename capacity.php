<!DOCTYPE ht <!DOCTYPE html>
<html lang="en">

<head>
       <title>>Capacity Task</title>
       
    <meta charset="utf-8">
     
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
    body {
        font-family: "Apple Chancery", Times, serif;
        background-color: #D6D6D6;
    }

    .center {
        text-align: center;
    }

    body,
    td,
    th {
        color: #06F;
    }

    .larger {
        font-size: larger;

    }

    table {
        margin-left: auto;
        margin-right: auto;
        border: 1px solid;
    }

    table>tr,
    th,
    td {
        border: 1px solid;
        padding: 5px;
    }

    .cella {
        background-color: white;
    }

    .cellb {
        background-color: lightblue;
    }
    </style>
</head>

<body>
    <h3 class="center">COA123 - Web Programming</h3>
    <h2 class="center">Individual Coursework - Wedding Planner</h2>

    <h1 class="center">Task 2 - Capacity (capacity.php)</h1>

    <?php
  $servername = "sci-mysql";
  $username = "coa123wuser";
  $password = "grt64dkh!@2FD";
  $dbname = "coa123wdb";

  //get max and min capacity
  $maxCapacity = $_REQUEST["maxCapacity"];
  $minCapacity = $_REQUEST["minCapacity"];


  if (!is_numeric($maxCapacity)) {
    echo "<h4 class = \"center\">Invalid max capacity. Enter a number</h4>";
  } else
  if (!is_numeric($minCapacity)) {
    echo "<h4 class = \"center\">Invalid min capacity. Enter a number</h4>";
  } else if ($minCapacity > $maxCapacity) {
    echo "Max capacity must be greater than min capacity";
  } else {


    // Create connection
    $conn = new mysqli($servername, $username, $password);
    //select the database
    $conn->select_db($dbname);

    //sql query
    //Write a php script (capacity.php) to list (in an HTML table) the names and venue prices of licensed
    //venues within a minimum and maximum capacity (inclusive). 
    $sql = "
SELECT name as 'name', weekday_price, weekend_price
FROM venue
WHERE capacity >= $minCapacity AND capacity <= $maxCapacity AND licensed = 1
";

    $result = $conn->query($sql);

    echo "<table><tr><th>name</th><th>weekday price</th><th>weekend price</th></tr><tr>";
    $class = "cella";
    while ($row = $result->fetch_assoc()) {
      $class = $class == "cella" ? "cellb" : "cella";
      echo "<tr>
      <td class=$class>" . $row["name"] . "</td>
      <td class=$class>" . $row["weekday_price"] . "</td>
      <td class=$class>" . $row["weekend_price"] . "</td>
      </tr>";
    }
    echo "</table>";
  }

  ?>



</body>

</html>