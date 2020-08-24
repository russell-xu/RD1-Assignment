// Declare variables
let counties = document.querySelector(".counties")
let weather = document.querySelector(".weather")
let tomorrow = document.querySelector(".tomorrow")
let acquired = document.querySelector(".acquired")
let third_day = document.querySelector(".third_day")
let forth_day = document.querySelector(".forth_day")
let fifth_day = document.querySelector(".fifth_day")
let sixth_day = document.querySelector(".sixth_day")
let observation_name = document.querySelector(".observation_name")
let one_hour_rainfall = document.querySelector(".one_hour_rainfall")
let twenty_hour_rainfall = document.querySelector(".twenty_hour_rainfall")


// Grab weather data
fetch('https://opendata.cwb.gov.tw/api/v1/rest/datastore/F-D0047-091?Authorization=CWB-80FDA3D4-E6D7-43E1-83BB-28D6A7C568EA&elementName=Wx', { method: 'get' })
    .then(function (response) {
        return response.json()
    }).then(function (data) {
        console.log(data)
        counties.innerHTML += data.records.locations[0].location[0].locationName
        weather.innerHTML += data.records.locations[0].location[0].weatherElement[0].time[0].elementValue[0].value
        tomorrow.innerHTML += data.records.locations[0].location[0].weatherElement[0].time[2].elementValue[0].value
        acquired.innerHTML += data.records.locations[0].location[0].weatherElement[0].time[4].elementValue[0].value
        third_day.innerHTML += data.records.locations[0].location[0].weatherElement[0].time[6].elementValue[0].value
        forth_day.innerHTML += data.records.locations[0].location[0].weatherElement[0].time[8].elementValue[0].value
        fifth_day.innerHTML += data.records.locations[0].location[0].weatherElement[0].time[10].elementValue[0].value
        sixth_day.innerHTML += data.records.locations[0].location[0].weatherElement[0].time[12].elementValue[0].value
    }).catch(function (err) {
        console.log(err)
    })

// Grab rainfall data
fetch('https://opendata.cwb.gov.tw/api/v1/rest/datastore/O-A0002-001?Authorization=CWB-80FDA3D4-E6D7-43E1-83BB-28D6A7C568EA&parameterName=CITY', { method: 'get' })
    .then(function (response) {
        return response.json()
    }).then(function (data) {
        console.log(data)
        observation_name.innerHTML += data.records.location[0].locationName
        one_hour_rainfall.innerHTML += data.records.location[0].weatherElement[1].elementValue
        twenty_hour_rainfall.innerHTML += data.records.location[0].weatherElement[6].elementValue
    }).catch(function (err) {
        console.log(err)
    })