<?php
$sql_clear_data = <<<multi
DELETE
FROM
    `current_weather`;
multi;
$clear_data = $db->prepare($sql_clear_data);
$clear_data->execute();

$json_url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-80FDA3D4-E6D7-43E1-83BB-28D6A7C568EA";
$json = file_get_contents($json_url);
$links = json_decode($json, TRUE);

$sql_insert_current_weather = <<<multi
INSERT INTO current_weather(
    `locationName`,
    `Wx`,
    `PoP`,
    `MinT`,
    `CI`,
    `MaxT`
)
VALUES(?, ?, ?, ?, ?, ?, ?, ?);
multi;
$insert_current_weather = $db->prepare($sql_insert_current_weather);

$location = $links['records']['location'];

foreach (array_keys($location) as $key) {
  for ($i = 0; $i < 3; $i++) {
    $locationName = $location[$key]['locationName'];
    $Wx = $location[$key]['weatherElement'][0]['time'][$i]['parameter']['parameterName'];
    $Wx_id = $location[$key]['weatherElement'][0]['time'][$i]['parameter']['parameterValue'];
    $PoP = $location[$key]['weatherElement'][1]['time'][$i]['parameter']['parameterName'];
    $MinT = $location[$key]['weatherElement'][2]['time'][$i]['parameter']['parameterName'];
    $CI = $location[$key]['weatherElement'][3]['time'][$i]['parameter']['parameterName'];
    $MaxT = $location[$key]['weatherElement'][4]['time'][$i]['parameter']['parameterName'];
    $time = $location[$key]['weatherElement'][0]['time'][$i]['startTime'];

    $insert_current_weather->execute([$locationName, $Wx, $Wx_id, $PoP, $MinT, $CI, $MaxT, $time]);
  }
}
