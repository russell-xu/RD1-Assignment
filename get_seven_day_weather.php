<?php
$sql_clear_data = <<<multi
DELETE
FROM
    `seven_day_weather`;
multi;
$clear_data = $db->prepare($sql_clear_data);
$clear_data->execute();

$json_url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-80FDA3D4-E6D7-43E1-83BB-28D6A7C568EA";
$json = file_get_contents($json_url);
$links = json_decode($json, TRUE);

$sql_insert_seven_day_weather = <<<multi
INSERT INTO `seven_day_weather`(
    `locationName`,
    `T`,
    `RH`,
    `MinCI`,
    `WS`,
    `MaxAT`,
    `Wx`,
    `MaxCI`,
    `MinT`,
    `WeatherDescription`,
    `MinAT`,
    `MaxT`,
    `WD`,
    `Td`,
    `time`
)
VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
multi;
$insert_seven_day_weather = $db->prepare($sql_insert_seven_day_weather);

$location = $links['records']['locations'][0]['location'];

foreach (array_keys($location) as $key) {
  for ($i = 0; $i < 13; $i += 2) {
    $locationName = $location[$key]['locationName'];
    $T = $location[$key]['weatherElement'][1]['time'][$i]['elementValue'][0]['value'];
    $RH = $location[$key]['weatherElement'][2]['time'][$i]['elementValue'][0]['value'];
    $MinCI = $location[$key]['weatherElement'][3]['time'][$i]['elementValue'][0]['value'];
    $WS = $location[$key]['weatherElement'][4]['time'][$i]['elementValue'][0]['value'];
    $MaxAT = $location[$key]['weatherElement'][5]['time'][$i]['elementValue'][0]['value'];
    $Wx = $location[$key]['weatherElement'][6]['time'][$i]['elementValue'][0]['value'];
    $Wx_id = $location[$key]['weatherElement'][6]['time'][$i]['elementValue'][1]['value'];
    $MaxCI = $location[$key]['weatherElement'][7]['time'][$i]['elementValue'][0]['value'];
    $MinT = $location[$key]['weatherElement'][8]['time'][$i]['elementValue'][0]['value'];
    $WeatherDescription = $location[$key]['weatherElement'][10]['time'][$i]['elementValue'][0]['value'];
    $MinAT = $location[$key]['weatherElement'][11]['time'][$i]['elementValue'][0]['value'];
    $MaxT = $location[$key]['weatherElement'][12]['time'][$i]['elementValue'][0]['value'];
    $WD = $location[$key]['weatherElement'][13]['time'][$i]['elementValue'][0]['value'];
    $Td = $location[$key]['weatherElement'][14]['time'][$i]['elementValue'][0]['value'];
    $time = $location[$key]['weatherElement'][0]['time'][$i]['startTime'];

    $insert_seven_day_weather->execute([$locationName, $T, $RH, $MinCI, $WS, $MaxAT, $Wx, $Wx_id, $MaxCI, $MinT, $WeatherDescription, $MinAT, $MaxT, $WD, $Td, $time]);
  }
}
