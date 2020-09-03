<?php
session_start();
require_once("mysql_connect.php");

// require_once("get_current_weather.php");
// require_once("get_two_day_weather.php");
// require_once("get_seven_day_weather.php");

// require_once("get_rainfall_data.php");

$_SESSION['county'] = "臺中市";
$county = $_SESSION['county'];

if (isset($_POST['query_county'])) {
  $county = $_POST['county'];
}


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
    `locationName` = '$county'
multi;
$current_weather = $db->prepare($sql_current_weather);
$current_weather->execute();


$sql_two_day_weather = <<<multi
SELECT
    `locationName`,
    `Wx`,
    `Wx_id`,
    `AT`,
    `T`,
    `CI_describe`,
    `PoP6h`,
    `WS`,
    `WD`,
    DATE_FORMAT(`time`, '%m/%d') AS `time`
FROM
    `two_day_weather`
WHERE
    `locationName` = '$county'
multi;
$two_day_weather = $db->prepare($sql_two_day_weather);
$two_day_weather->execute();


$sql_seven_day_weather = <<<multi
SELECT
    `locationName`,
    `T`,
    `WS`,
    `MaxAT`,
    `Wx`,
    `Wx_id`,
    `MinT`,
    `MinAT`,
    `MaxT`,
    `WD`,
    DATE_FORMAT(`time`, '%m/%d') AS `time`
FROM
    `seven_day_weather`
WHERE
    `locationName` = '$county'
multi;
$seven_day_weather = $db->prepare($sql_seven_day_weather);
$seven_day_weather->execute();


$sql_rainfall_data = <<<multi
SELECT
    *
FROM
    `rainfall_data`
WHERE
    `CITY` = '$county'
multi;
$rainfall_data = $db->prepare($sql_rainfall_data);
$rainfall_data->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
  <title>Document</title>
</head>

<body>

  <div class="container">
    <div class="row">
      <div class="col">
        <h1>目前縣市：<?= $county ?></h1>
        <form action="" method="post">
          <div class="form-group">
            <label for="counties">選擇縣市</label>
            <select class="custom-select" id="counties" name="county">
              <option value="雲林縣">-</option>
              <option value="雲林縣">雲林縣</option>
              <option value="南投縣">南投縣</option>
              <option value="連江縣">連江縣</option>
              <option value="臺東縣">臺東縣</option>
              <option value="金門縣">金門縣</option>
              <option value="宜蘭縣">宜蘭縣</option>
              <option value="屏東縣">屏東縣</option>
              <option value="苗栗縣">苗栗縣</option>
              <option value="澎湖縣">澎湖縣</option>
              <option value="臺北市">臺北市</option>
              <option value="新竹縣">新竹縣</option>
              <option value="花蓮縣">花蓮縣</option>
              <option value="高雄市">高雄市</option>
              <option value="彰化縣">彰化縣</option>
              <option value="新竹市">新竹市</option>
              <option value="新北市">新北市</option>
              <option value="基隆市">基隆市</option>
              <option value="臺中市">臺中市</option>
              <option value="臺南市">臺南市</option>
              <option value="桃園市">桃園市</option>
              <option value="嘉義縣">嘉義縣</option>
              <option value="嘉義市">嘉義市</option>
            </select>
          </div>
          <div class="form-group">
            <input class="btn btn-primary" type="submit" name="query_county" value="搜尋">
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <?php while ($current_weather_rows = $current_weather->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="col">
          <div class="card">
            <img src="./img/<?= $current_weather_rows['Wx_id'] ?>.svg" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title text-center"><?= $current_weather_rows['time'] ?></h5>
              <p class="card-text text-center"><?= $current_weather_rows['Wx'] ?></p>
              <p class="card-text text-center"><?= $current_weather_rows['MinT'] . ' - ' . $current_weather_rows['MaxT'] ?>°C</p>
              <p class="card-text text-center"><?= $current_weather_rows['PoP'] ?>%</p>
              <p class="card-text text-center"><?= $current_weather_rows['CI'] ?></p>
            </div>
          </div>
        </div>
      <?php
      } ?>
      <?php while ($two_day_weather_rows = $two_day_weather->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="col">
          <div class="card">
            <img src="./img/<?= $two_day_weather_rows['Wx_id'] ?>.svg" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title text-center"><?= $two_day_weather_rows['time'] ?></h5>
              <p class="card-text text-center"><?= $two_day_weather_rows['Wx'] ?></p>
              <p class="card-text text-center"><?= $two_day_weather_rows['AT'] ?>°C</p>
              <p class="card-text text-center"><?= $two_day_weather_rows['T'] ?>°C</p>
              <p class="card-text text-center"><?= $two_day_weather_rows['CI_describe'] ?></p>
              <p class="card-text text-center"><?= $two_day_weather_rows['PoP6h'] ?>%</p>
              <p class="card-text text-center"><?= $two_day_weather_rows['WS'] ?></p>
              <p class="card-text text-center"><?= $two_day_weather_rows['WD'] ?></p>
            </div>
          </div>
        </div>
      <?php
      } ?>
    </div>
    <div class="row">
      <?php while ($seven_day_weather_rows = $seven_day_weather->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="col">
          <div class="card">
            <img src="./img/<?= $seven_day_weather_rows['Wx_id'] ?>.svg" class="card-img-top" alt="">
            <div class="card-body">
              <h5 class="card-title text-center"><?= $seven_day_weather_rows['time'] ?></h5>
              <p class="card-text text-center"><?= $seven_day_weather_rows['Wx'] ?></p>
              <p class="card-text text-center"><?= $seven_day_weather_rows['T'] ?></p>
              <p class="card-text text-center"><?= $seven_day_weather_rows['MinT'] . ' - ' . $seven_day_weather_rows['MaxT'] ?>°C</p>
              <p class="card-text text-center">體感溫°C：<?= $seven_day_weather_rows['MinAT'] . ' - ' . $seven_day_weather_rows['MaxAT'] ?>°C</p>
              <p class="card-text text-center"><?= $seven_day_weather_rows['WS'] ?></p>
              <p class="card-text text-center"><?= $seven_day_weather_rows['WD'] ?></p>
            </div>
          </div>
        </div>
      <?php
      } ?>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <?php while ($rainfall_data_rows = $rainfall_data->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title text-center"><?= $rainfall_data_rows['locationName'] ?></h5>
              <p class="card-text text-center">1小時累積雨量：<?= $rainfall_data_rows['RAIN'] == -998 ? 0 : $rainfall_data_rows['RAIN'] ?>mm</p>
              <p class="card-text text-center">24小時累積雨量：<?= $rainfall_data_rows['HOUR_24'] == -998 ? 0 : $rainfall_data_rows['HOUR_24'] ?>mm</p>
              <p class="card-text text-center"><?= $rainfall_data_rows['TOWN'] ?></p>
              <p class="card-text text-center"><?= $rainfall_data_rows['ATTRIBUTE'] ?></p>
            </div>
          </div>
        </div>
      <?php
      } ?>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>