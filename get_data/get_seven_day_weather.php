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
    `Wx`,
    `Wx_id`,
    `MinT`,
    `MaxT`,
    `time`
)
VALUES
multi;

$location = $links['records']['locations'][0]['location'];

foreach (array_keys($location) as $key) {
  for ($i = 0; $i < 13; $i += 2) {
    $locationName = $location[$key]['locationName'];
    $Wx = $location[$key]['weatherElement'][6]['time'][$i]['elementValue'][0]['value'];
    $Wx_id = $location[$key]['weatherElement'][6]['time'][$i]['elementValue'][1]['value'];
    $MinT = $location[$key]['weatherElement'][8]['time'][$i]['elementValue'][0]['value'];
    $MaxT = $location[$key]['weatherElement'][12]['time'][$i]['elementValue'][0]['value'];
    $time = $location[$key]['weatherElement'][0]['time'][$i]['startTime'];

    $sql_insert_seven_day_weather .= "('$locationName', '$Wx', '$Wx_id', '$MinT', '$MaxT', '$time'),";
  }
}

$insert_seven_day_weather = $db->prepare(substr_replace($sql_insert_seven_day_weather, '', -1));
$insert_seven_day_weather->execute();
