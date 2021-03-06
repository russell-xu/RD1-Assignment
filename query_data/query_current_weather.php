<?php
require_once("../mysql_connect.php");

$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);

$name = $decoded['name'];

$sql_current_weather = <<<multi
SELECT
    `locationName`,
    `Wx`,
    `Wx_id`,
    `PoP`,
    `MinT`,
    `CI`,
    `MaxT`,
    DATE_FORMAT(`time`, '%m/%d') AS `time`
FROM
    `current_weather`
WHERE
    `locationName` = '$name'
multi;
$current_weather = $db->prepare($sql_current_weather);
$current_weather->execute();
$rows = $current_weather->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, true);
