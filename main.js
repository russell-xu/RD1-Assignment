let county_name = "臺中市"

document.addEventListener("DOMContentLoaded", () => {
  change_title_county()
  query_current_weather()
  query_two_day_weather()
  query_seven_day_weather()
  query_rainfall_data()
})

let query_county = document.querySelector('#query_county')
query_county.addEventListener('click', () => {
  let counties = document.querySelector('#counties')
  
  county_name = counties.value
  change_title_county()
  query_current_weather()
  query_two_day_weather()
  query_seven_day_weather()
  query_rainfall_data()
})

let change_title_county = () => {
  let title_county = document.querySelector('#title_county')
  title_county.innerHTML = county_name
}

let query_current_weather = () => {
  fetch('query_data/query_current_weather.php', {
    method: 'POST',
    body: JSON.stringify(
      {
        name: county_name
      }
    ),
    headers: new Headers({
      'Content-Type': 'application/json'
    })
  })
    .then((response) => {
      return response.json()
    })
    .then((myJson) => {
      console.log(myJson)
      let CI = myJson[0].CI
      let MaxT = myJson[0].MaxT
      let MinT = myJson[0].MinT
      let PoP = myJson[0].PoP
      let Wx = myJson[0].Wx
      let Wx_id = myJson[0].Wx_id

      let current_weather_box = document.querySelector('#current_weather_box')
      current_weather_box.innerHTML = `
      <div class="card text-center text-white bg-dark">
        <img src="./img/${Wx_id}.svg" class="card-img-top current_weather_img" alt="">
        <div class="card-body">
          <h5 class="card-title">今日</h5>
          <p class="card-text">${Wx}</p>
          <p class="card-text">溫度：${MinT} - ${MaxT}°C</p>
          <p class="card-text">降雨機率：${PoP}%</p>
          <p class="card-text">${CI}</p>
        </div>
      </div>
      `
    })
}

let query_two_day_weather = () => {
  fetch('query_data/query_two_day_weather.php', {
    method: 'POST',
    body: JSON.stringify(
      {
        name: county_name
      }
    ),
    headers: new Headers({
      'Content-Type': 'application/json'
    })
  })
    .then((response) => {
      return response.json()
    })
    .then((myJson) => {
      console.log(myJson)
      let two_day_weather_box = document.querySelector('#two_day_weather_box')
      two_day_weather_box.innerHTML = ''

      myJson.forEach(data => {
        let CI_describe = data.CI_describe
        let PoP6h = data.PoP6h
        let AT = data.AT
        let Wx = data.Wx
        let Wx_id = data.Wx_id
        let time = data.time

        two_day_weather_box.innerHTML += `
        <div class="card text-center text-white bg-dark flex-fill">
          <img src="./img/${Wx_id}.svg" class="card-img-top two_weather_img" alt="">
          <div class="card-body">
            <h5 class="card-title">${time}</h5>
            <p class="card-text">${Wx}</p>
            <p class="card-text">體感溫度：${AT}°C</p>
            <p class="card-text">降雨機率：${PoP6h}%</p>
            <p class="card-text">${CI_describe}</p>
          </div>
        </div>
        `

      });
    })
}

let query_seven_day_weather = () => {
  fetch('query_data/query_seven_day_weather.php', {
    method: 'POST',
    body: JSON.stringify(
      {
        name: county_name
      }
    ),
    headers: new Headers({
      'Content-Type': 'application/json'
    })
  })
    .then((response) => {
      return response.json()
    })
    .then((myJson) => {
      console.log(myJson)
      let seven_day_weather_box = document.querySelector('#seven_day_weather_box')
      seven_day_weather_box.innerHTML = ''

      myJson.forEach(data => {
        let Wx_id = data.Wx_id
        let MinT = data.MinT
        let MaxT = data.MaxT
        let time = data.time

        seven_day_weather_box.innerHTML += `
        <div class="col">
          <div class="card text-center text-white bg-dark">
            <img src="./img/${Wx_id}.svg" class="card-img-top seven_weather_img" alt="">
            <div class="card-body">
              <h5 class="card-title">${time}</h5>
              <p class="card-text">${MinT} - ${MaxT}°C</p>
            </div>
          </div>
        </div>
        `

      });
    })
}

let query_rainfall_data = () => {
  fetch('query_data/query_rainfall_data.php', {
    method: 'POST',
    body: JSON.stringify(
      {
        name: county_name
      }
    ),
    headers: new Headers({
      'Content-Type': 'application/json'
    })
  })
    .then((response) => {
      return response.json()
    })
    .then((myJson) => {
      console.log(myJson)
      let rainfall_data_box = document.querySelector('#rainfall_data_box')
      rainfall_data_box.innerHTML = ''

      myJson.forEach(data => {
        let locationName = data.locationName
        let RAIN = data.RAIN
        let HOUR_24 = data.HOUR_24
        let TOWN = data.TOWN

        rainfall_data_box.innerHTML += `
        <tr>
          <th scope="row">${locationName}</th>
          <td>${RAIN}</td>
          <td>${HOUR_24}</td>
          <td>${TOWN}</td>
        </tr>
        `

      });
    })
}