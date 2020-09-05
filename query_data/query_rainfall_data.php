<?php
require_once("../mysql_connect.php");

$content = trim(file_get_contents("php://input"));
$decoded = json_decode($content, true);

$name = $decoded['name'];

$sql_rainfall_data = <<<multi
SELECT
    *
FROM
    `rainfall_data`
WHERE
    `CITY` = '$name'
multi;
$rainfall_data = $db->prepare($sql_rainfall_data);
$rainfall_data->execute();
$rows = $rainfall_data->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($rows, true);
