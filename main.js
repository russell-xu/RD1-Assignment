document.addEventListener("DOMContentLoaded", function () {
  query_current_weather()
  // $('#fruitName').change(function () {
  //   query_current_weather()
  // });
})

function query_current_weather() {
  fetch('query_data/query_current_weather.php', {
    method: 'POST',
    body: JSON.stringify(
      {
        // name: encodeURI('臺中市')
        // name: escape('臺中市')
        name: '臺中市'
      }
    ),
    headers: new Headers({
      'Content-Type': 'application/json'
    })
  })
    .then(function (response) {
      return response.json()
    })
    .then(function (myJson) {
      console.log(myJson[0].CI)

    })
}