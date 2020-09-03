<?php
$sql_clear_data = <<<multi
DELETE
FROM
    `two_day_weather`;
multi;
$clear_data = $db->prepare($sql_clear_data);
$clear_data->execute();

$json_url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-80FDA3D4-E6D7-43E1-83BB-28D6A7C568EA";
$json = file_get_contents($json_url);
$links = json_decode($json, TRUE);

$sql_insert_two_day_weather = <<<multi
INSERT INTO `two_day_weather`(
    `locationName`,
    `Wx`,
    `Wx_id`,
    `AT`,
    `T`,
    `CI_describe`,
    `PoP6h`,
    `WS`,
    `WD`,
    `time`
)
VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
multi;
$insert_two_day_weather = $db->prepare($sql_insert_two_day_weather);
$location = $links['records']['locations'][0]['location'];

foreach (array_keys($location) as $key) {
  for ($i = 0; $i < 5; $i += 2) {
    $locationName = $location[$key]['locationName'];
    $Wx = $location[$key]['weatherElement'][1]['time'][$i]['elementValue'][0]['value'];
    $Wx_id = $location[$key]['weatherElement'][1]['time'][0]['elementValue'][1]['value'];
    $AT = $location[$key]['weatherElement'][2]['time'][$i]['elementValue'][0]['value'];
    $T = $location[$key]['weatherElement'][3]['time'][$i]['elementValue'][0]['value'];
    $CI_describe = $location[$key]['weatherElement'][5]['time'][0]['elementValue'][1]['value'];
    $PoP6h = $location[$key]['weatherElement'][7]['time'][$i]['elementValue'][0]['value'];
    $WS = $location[$key]['weatherElement'][8]['time'][$i]['elementValue'][0]['value'];
    $WD = $location[$key]['weatherElement'][9]['time'][$i]['elementValue'][0]['value'];
    $time = $location[$key]['weatherElement'][0]['time'][$i]['startTime'];

    $insert_two_day_weather->execute([$locationName, $Wx, $Wx_id, $AT, $T, $CI_describe, $PoP6h, $WS, $WD, $time]);
  }
}
