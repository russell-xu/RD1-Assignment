<?php
session_start();
require_once("mysql_connect.php");

require_once("get_data/get_current_weather.php");
require_once("get_data/get_two_day_weather.php");
require_once("get_data/get_seven_day_weather.php");

require_once("get_data/get_rainfall_data.php");

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
    `CI_describe`,
    `PoP6h`,
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
    `Wx`,
    `Wx_id`,
    `MinT`,
    `MaxT`,
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
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">我是個人氣象站</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">選擇縣市</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#current_weather">近日天氣</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#week_weather">一週天氣</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#rainfall_observation">雨量觀測</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">
    <h1 class="text-center"><?= $county ?></h1>
    <div class="row" id="select_county">
      <div class="col d-flex justify-content-center">
        <form action="" method="post" class="form-inline" id="select_counties">
          <div class="form-group" id="form_select">
            <label for="counties" class="select_form_item">選擇縣市</label>
            <select class="custom-select" id="counties" name="county">
              <option value="<?= $county ?>">-</option>
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
          <div class="form-group select_form_item">
            <input class="btn btn-primary" type="submit" name="query_county" value="搜尋">
          </div>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col d-flex justify-content-center">
        <img src="./img/<?= $county ?>.jpg" alt="" class="img-fluid">
      </div>
    </div>
    <h1 class="text-center anchor_point" id="current_weather">近日天氣</h1>
    <div class="row no-gutters">
      <?php while ($current_weather_rows = $current_weather->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="col">
          <div class="card text-center text-white bg-dark">
            <img src="./img/<?= $current_weather_rows['Wx_id'] ?>.svg" class="card-img-top current_weather_img" alt="">
            <div class="card-body">
              <h5 class="card-title">今日</h5>
              <p class="card-text"><?= $current_weather_rows['Wx'] ?></p>
              <p class="card-text">溫度：<?= $current_weather_rows['MinT'] . ' - ' . $current_weather_rows['MaxT'] ?>°C</p>
              <p class="card-text">降雨機率：<?= $current_weather_rows['PoP'] ?>%</p>
              <p class="card-text"><?= $current_weather_rows['CI'] ?></p>
            </div>
          </div>
        </div>
      <?php
      } ?>

      <div class="col d-flex">
        <?php while ($two_day_weather_rows = $two_day_weather->fetch(PDO::FETCH_ASSOC)) { ?>
          <div class="card text-center text-white bg-dark flex-fill">
            <img src="./img/<?= $two_day_weather_rows['Wx_id'] ?>.svg" class="card-img-top two_weather_img" alt="">
            <div class="card-body">
              <h5 class="card-title"><?= $two_day_weather_rows['time'] ?></h5>
              <p class="card-text"><?= $two_day_weather_rows['Wx'] ?></p>
              <p class="card-text">體感溫度：<?= $two_day_weather_rows['AT'] ?>°C</p>
              <p class="card-text">降雨機率：<?= $two_day_weather_rows['PoP6h'] ?>%</p>
              <p class="card-text"><?= $two_day_weather_rows['CI_describe'] ?></p>
            </div>
          </div>
        <?php
        } ?>
      </div>
    </div>
    <h1 class="text-center anchor_point" id="week_weather">一週天氣</h1>
    <div class="row no-gutters">
      <?php while ($seven_day_weather_rows = $seven_day_weather->fetch(PDO::FETCH_ASSOC)) { ?>
        <div class="col">
          <div class="card text-center text-white bg-dark">
            <img src="./img/<?= $seven_day_weather_rows['Wx_id'] ?>.svg" class="card-img-top seven_weather_img" alt="">
            <div class="card-body">
              <h5 class="card-title"><?= $seven_day_weather_rows['time'] ?></h5>
              <p class="card-text"><?= $seven_day_weather_rows['MinT'] . ' - ' . $seven_day_weather_rows['MaxT'] ?>°C</p>
            </div>
          </div>
        </div>
      <?php
      } ?>
    </div>
    <h1 class="text-center anchor_point" id="rainfall_observation">雨量觀測</h1>
    <div class="row">
      <table class="table table-hover text-center">
        <thead class="thead-dark">
          <tr>
            <th scope="col">觀測站名</th>
            <th scope="col">1小時累積雨量(mm)</th>
            <th scope="col">24小時累積雨量(mm)</th>
            <th scope="col">地區</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($rainfall_data_rows = $rainfall_data->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
              <th scope="row"><?= $rainfall_data_rows['locationName'] ?></th>
              <td><?= $rainfall_data_rows['RAIN'] == -998 ? 0 : $rainfall_data_rows['RAIN'] ?></td>
              <td><?= $rainfall_data_rows['HOUR_24'] == -998 ? 0 : $rainfall_data_rows['HOUR_24'] ?></td>
              <td><?= $rainfall_data_rows['TOWN'] ?></td>
            </tr>
          <?php
          } ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>