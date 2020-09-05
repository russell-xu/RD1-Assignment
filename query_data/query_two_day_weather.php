<?php
require_once("../mysql_connect.php");

$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);

$name = $decoded['name'];

$sql_two_day_weather = <<<multi
SELECT
    `locationName`,
    `Wx`,
    `Wx_id`,
    `AT`,
    `CI_describe`,
    `PoP6h`,
    DATE_FORMAT(`time`, '%m/%d') AS `time`
FROM
    `two_day_weather`
WHERE
    `locationName` = '$name'
multi;
$two_day_weather = $db->prepare($sql_two_day_weather);
$two_day_weather->execute();
$rows = $two_day_weather->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, true);
