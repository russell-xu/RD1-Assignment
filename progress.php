<?php
require_once("mysql_connect.php");

require_once("get_data/get_current_weather.php");
require_once("get_data/get_two_day_weather.php");
require_once("get_data/get_seven_day_weather.php");

require_once("get_data/get_rainfall_data.php");

header("Location: view.html");
exit();
?>