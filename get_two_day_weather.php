<?php
$sql_clear_data = <<<multi
DELETE
FROM
    `two_day_weather`;
multi;
$stmt = $db->prepare($sql_clear_data);
$stmt->execute();

$json_url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-089?Authorization=CWB-80FDA3D4-E6D7-43E1-83BB-28D6A7C568EA";
$json = file_get_contents($json_url);
$links = json_decode($json, TRUE);

$location = $links['records']['locations'][0]['location'];

foreach (array_keys($location) as $key) {
  for ($i = 2; $i < 5; $i += 2) {
    $locationName = $location[$key]['locationName'];
    $PoP12h = $location[$key]['weatherElement'][0]['time'][$i]['elementValue'][0]['value'];
    $Wx = $location[$key]['weatherElement'][1]['time'][$i]['elementValue'][0]['value'];
    // $Wx_id = $location[$key]['weatherElement'][1]['time'][0]['elementValue'][1]['value'];
    $AT = $location[$key]['weatherElement'][2]['time'][$i]['elementValue'][0]['value'];
    $T = $location[$key]['weatherElement'][3]['time'][$i]['elementValue'][0]['value'];
    $RH = $location[$key]['weatherElement'][4]['time'][$i]['elementValue'][0]['value'];
    $CI = $location[$key]['weatherElement'][5]['time'][$i]['elementValue'][0]['value'];
    // $CI_describe = $location[$key]['weatherElement'][5]['time'][0]['elementValue'][1]['value'];
    $WeatherDescription = $location[$key]['weatherElement'][6]['time'][$i]['elementValue'][0]['value'];
    $PoP6h = $location[$key]['weatherElement'][7]['time'][$i]['elementValue'][0]['value'];
    $WS = $location[$key]['weatherElement'][8]['time'][$i]['elementValue'][0]['value'];
    $WD = $location[$key]['weatherElement'][9]['time'][$i]['elementValue'][0]['value'];
    $Td = $location[$key]['weatherElement'][10]['time'][$i]['elementValue'][0]['value'];
    $time = $location[$key]['weatherElement'][0]['time'][$i]['startTime'];

    $sql_insert_two_day_weather = <<<multi
    INSERT INTO `two_day_weather`(
        `locationName`,
        `PoP12h`,
        `Wx`,
        `AT`,
        `T`,
        `RH`,
        `CI`,
        `WeatherDescription`,
        `PoP6h`,
        `WS`,
        `WD`,
        `Td`,
        `time`
    )
    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    multi;
    $stmt = $db->prepare($sql_insert_two_day_weather);
    $stmt->execute([$locationName, $PoP12h, $Wx, $AT, $T, $RH, $CI, $WeatherDescription, $PoP6h, $WS, $WD, $Td, $time]);

    // echo $locationName . "<br>";
    // echo $PoP12h . "<br>";
    // echo $Wx . "<br>";
    // echo $AT . "<br>";
    // echo $T . "<br>";
    // echo $RH . "<br>";
    // echo $CI . "<br>";
    // echo $WeatherDescription . "<br>";
    // echo $PoP6h . "<br>";
    // echo $WS . "<br>";
    // echo $WD . "<br>";
    // echo $Td . "<br>";
    // echo $time . "<br>";
  }
}
