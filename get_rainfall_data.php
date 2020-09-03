<?php
$sql_clear_data = <<<multi
DELETE
FROM
    `rainfall_data`;
multi;
$clear_data = $db->prepare($sql_clear_data);
$clear_data->execute();

$json_url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=CWB-80FDA3D4-E6D7-43E1-83BB-28D6A7C568EA";
$json = file_get_contents($json_url);
$links = json_decode($json, TRUE);

$sql_insert_rainfall_data = <<<multi
INSERT INTO `rainfall_data`(
    `locationName`,
    `RAIN`,
    `HOUR_24`,
    `CITY`,
    `TOWN`,
    `ATTRIBUTE`
)
VALUES(?, ?, ?, ?, ?, ?)
multi;
$insert_rainfall_data = $db->prepare($sql_insert_rainfall_data);

$location = $links['records']['location'];
foreach (array_keys($location) as $key) {
  $locationName = $location[$key]['locationName'];
  $RAIN = $location[$key]['weatherElement'][1]['elementValue'];
  $HOUR_24 = $location[$key]['weatherElement'][6]['elementValue'];
  $CITY = $location[$key]['parameter'][0]['parameterValue'];
  $TOWN = $location[$key]['parameter'][2]['parameterValue'];
  $ATTRIBUTE = $location[$key]['parameter'][4]['parameterValue'];

  $insert_rainfall_data->execute([$locationName, $RAIN, $HOUR_24, $CITY, $TOWN, $ATTRIBUTE]);
}
exit();
