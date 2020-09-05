<?php
require_once("../mysql_connect.php");

$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);

$name = $decoded['name'];

$sql_seven_day_weather = <<<multi
SELECT
    `locationName`,
    `Wx`,
    `Wx_id`,
    `MinT`,
    `MaxT`,
    DATE_FORMAT(`time`, '%m/%d') AS `time`
FROM
    `seven_day_weather`
WHERE
    `locationName` = '$name'
multi;
$seven_day_weather = $db->prepare($sql_seven_day_weather);
$seven_day_weather->execute();
$rows = $seven_day_weather->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, true);
