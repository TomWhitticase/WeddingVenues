<!DOCTYPE html>
<html lang="en">

<head>
       <title>>Catering Task</title>
       
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
    <h1 class="center">Task 1 - Catering (catering.php)</h1>
    <?php
  $costs = [$_REQUEST['c1'], $_REQUEST['c2'], $_REQUEST['c4'], $_REQUEST['c4'], $_REQUEST['c5']];
  $min = $_REQUEST["min"];
  $max = $_REQUEST["max"];



  $error = false;
  if (!is_numeric($_REQUEST['c1'])) {
    $error = true;
  }
  if (!is_numeric($_REQUEST['c2'])) {
    $error = true;
  }
  if (!is_numeric($_REQUEST['c3'])) {
    $error = true;
  }
  if (!is_numeric($_REQUEST['c4'])) {
    $error = true;
  }
  if (!is_numeric($_REQUEST['c5'])) {
    $error = true;
  }
  if (!is_numeric($min)) {
    $error = true;
  }
  if (!is_numeric($max)) {
    $error = true;
  }


  if ($error) {
    echo "<h4 class = \"center\">Invalid input. Values must be integers</h4>";
  } else {

    if ($max < $min) {
      echo "<h4 class = \"center\">Max must be greater than min</h4>";
    } else {
      echo "<table><tr><th>cost per person →<br>↓ party size </th>";
      foreach ($costs as $cost) echo "<th>$cost</th>";
      echo "</tr><tr>";
      $class = "cella";
      foreach (range($min, $max, 5) as $psize) {
        $class = $class == "cella" ? "cellb" : "cella";
        echo "<tr><td>" . $psize . "</td>";
        foreach ($costs as $cost) echo "<td class=$class>" . $cost * $psize . "</td>";
        echo "</tr>";
      }
      echo "</table>";
    }
  }

  ?>
</body>

</html>