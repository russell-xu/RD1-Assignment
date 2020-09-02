<?php
$sql_clear_data = <<<multi
DELETE
FROM
    `current_weather`;
multi;
$stmt = $db->prepare($sql_clear_data);
$stmt->execute();

$json_url = "https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-C0032-001?Authorization=CWB-80FDA3D4-E6D7-43E1-83BB-28D6A7C568EA";
$json = file_get_contents($json_url);
$links = json_decode($json, TRUE);

$location = $links['records']['location'];

foreach (array_keys($location) as $key) {
  $locationName = $location[$key]['locationName'];
  $Wx = $location[$key]['weatherElement'][0]['time'][0]['parameter']['parameterName'];
  $PoP = $location[$key]['weatherElement'][1]['time'][0]['parameter']['parameterName'];
  $MinT = $location[$key]['weatherElement'][2]['time'][0]['parameter']['parameterName'];
  $CI = $location[$key]['weatherElement'][3]['time'][0]['parameter']['parameterName'];
  $MaxT = $location[$key]['weatherElement'][4]['time'][0]['parameter']['parameterName'];

  $sql_insert_current_weather = <<<multi
  INSERT INTO current_weather(
      `locationName`,
      `Wx`,
      `PoP`,
      `MinT`,
      `CI`,
      `MaxT`
  )
  VALUES(?, ?, ?, ?, ?, ?);
  multi;
  $stmt = $db->prepare($sql_insert_current_weather);
  $stmt->execute([$locationName, $Wx, $PoP, $MinT, $CI, $MaxT]);

  //   echo $locationName . "<br>";
  //   echo $Wx . "<br>";
  //   echo $PoP . "<br>";
  //   echo $MinT . "<br>";
  //   echo $CI . "<br>";
  //   echo $MaxT . "<br>";
}
