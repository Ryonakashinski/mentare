<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Map</title>
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
      integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
      crossorigin="" />

    <script
      src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
      integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
      crossorigin=""></script>

    <!-- search control by Leaflet Control Geocoder -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
      #map {
        width: auto;
        height: 1500px;
      }
      .popup {
        font-size: 1.5rem;
      }
    </style>
  </head>

  <body>
    <div id="map"></div>
    <script src="script.js"></script>
  </body>

  <script>
    const map = L.map("map");
    // Initializes map

    map.setView([33.5892, 130.4018], 1);
    // Sets initial coordinates and zoom level

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
      maxZoom: 15,
      attribution: "© OpenStreetMap",
    }).addTo(map);
    // Sets map data source and associates with map

    // ------------------search function!
    function error(error) {
      console.warn("ERROR(" + error.code + "): " + error.message);
    }
    var searchControl = L.Control.geocoder({
      defaultMarkGeocode: false,
    }).addTo(map);
    searchControl.on("markgeocode", function (event) {
      // Clear any existing markers on the map
      map.eachLayer(function (layer) {
        if (layer instanceof L.Marker) {
          map.removeLayer(layer);
        }
      });
      // Add a marker to the selected location
      var marker = L.marker(event.geocode.center)
        .bindPopup(event.geocode.name)
        .addTo(map);
      // -------------search function!
      //
      //
      //
      // --------make a polygon after you search----------
      var marker = L.marker([33.5892, 130.4018]).addTo(map);
      var circle = L.circle([33.5892, 130.4018], {
        color: "red",
        fillColor: "#f03",
        fillOpacity: 0.5,
        radius: 500,
      }).addTo(map);
      var polygon = L.polygon([
        [33.588, 130.4018],
        [33.587, 130.4018],
        [33.586, 130.4018],
      ]).addTo(map);
      marker.bindPopup("<b>Hello world!</b><br>I am Ryo!").openPopup();
      circle.bindPopup("I am Ryo!");
      polygon.bindPopup("I am a polygon.");
      var popup = L.popup()
        .setLatLng([33.5892, 130.4018])
        .setContent("I am a standalone popup.")
        .openOn(map);
      function onMapClick(e) {
        alert("You clicked the map at " + e.latlng);
      }

      map.on("click", onMapClick);
      var popup = L.popup();

      // Zoom to the selected location
      map.setView(event.geocode.center, 10);
    });
    // ------------------search function!

    // ------ the locations of friends -------------------------
    const data = {
      Millie: {
        coords: [31.1334, 121.4868],
        title: "Millie",
        address: `
                    <b>China</b><br>
                    Shanghai<br>
                    Millie<br>`,
        website: "",
      },

      Millie: {
        coords: [31.1334, 121.4868],
        title: "Millie",
        address: `
                    <b>China</b><br>
                    Shanghai<br>
                    Millie<br>`,
        website: "",
      },
    };

    const customIcon = L.icon({
      iconUrl: "./friend.png",
      iconSize: [20, 20],
    });
    // ------------the loop function-------------------------
    for (let key in data) {
      const friend = data[key];

      L.marker(friend.coords, {
        title: friend.title,
        icon: customIcon,
      })
        .bindPopup(
          `
                <span class = "popup">
                   ${friend.address}
                    <a href = "${friend.website}" target = "_blank">Message</a><br>
                </span>

                `
        )
        .addTo(map);
    }

    const dataH = {
      cdh: {
        coords: [30.6463, 104.045],
        title: "Hostel",
        address: `
                    <b>China</b><br>
                    Chengdu<br>
                    Chengdu Dreams Travel International Youth Hostel<br>`,
        website:
          "https://www.booking.com/hotel/cn/chengdu-dreams-travel.html?aid=304142&label=gen173bo-1FCAEoggI46AdIM1gDaHWIAQGYATG4ARfIAQzYAQHoAQH4AQOIAgGYAiGoAgO4AoeD8qIGwAIB0gIkM2E5YjdjMWItOTI4Zi00ODlmLTg4ZTctNTdmNDdkYTg5OTlk2AIF4AIB&sid=c30630a4d48a00c1b51c52c30486e877&all_sr_blocks=37886416_369674138_1_42_0;checkin=2023-05-12;checkout=2023-05-13;dest_id=378864;dest_type=hotel;dist=0;group_adults=1;group_children=0;hapos=1;highlighted_blocks=37886416_369674138_1_42_0;hpos=1;matching_block_id=37886416_369674138_1_42_0;no_rooms=1;req_adults=1;req_children=0;room1=A;sb_price_type=total;sr_order=popularity;sr_pri_blocks=37886416_369674138_1_42_0__7448;srepoch=1683784307;srpvid=11f7293892ce018f;type=total;ucfs=1&#hotelTmpl",
      },
    };
    const customHostel = L.icon({
      iconUrl: "./hostel.png",
      iconSize: [20, 20],
    });
    // -------------------------------------
    for (let key in dataH) {
      const hostel = dataH[key];

      L.marker(hostel.coords, {
        title: hostel.title,
        icon: customHostel,
      })
        .bindPopup(
          `
                <span class = "popup">
                   ${hostel.address}
                    <a href = "${hostel.website}" target = "_blank">Book</a><br>
                </span>

                `
        )
        .addTo(map);
    }

    // change the color where is selected.-------------------------

    // first of all, i want to select the place if i click on the map

    // -------------------------------------------------
    let marker, circle, zoomed;

    // 位置情報の取得に成功した場合に実行される関数（サンプルでは showPosition）
    function showPosition(position) {
      console.log(position.coords.latitude);
      console.log(position.coords.longitude);
      const lat = position.coords.latitude;
      const lng = position.coords.longitude;
      $("#output").html(`<p>latitude:${lat}</p><p>longitude:${lng}</p>`);
    }

    function success(pos) {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      const accuracy = pos.coords.accuracy;

      if (marker) {
        map.removeLayer(marker);
        map.removeLayer(circle);
      }
      // Removes any existing marker and circule (new ones about to be set)

      marker = L.marker([lat, lng]).addTo(map);
      circle = L.circle([lat, lng], { radius: accuracy }).addTo(map);
      // Adds marker to the map and a circle for accuracy

      if (!zoomed) {
        zoomed = map.fitBounds(circle.getBounds());
      }
      // Set zoom to boundaries of accuracy circle

      map.setView([lat, lng]);
      // Set map focus to current user position
    }
    // 位置情報の取得に失敗した場合に実行される関数（サンプルでは showError）．
    function showError(error) {
      if (error.code === 1) {
        alert("Please allow geolocation access");
      } else {
        alert("Cannot get current location");
      }
    }
    // 位置情報の取得に必要なオプション（前項で紹介したもの，サンプルでは option）．
    const options = {
      enableHighAccuracy: true,
      // using GPS
      timeout: 5000,
      // Time to return a position successfully before error (default infinity)
      maximumAge: 2000,
      // Milliseconds for which it is acceptable to use cached position (default 0)
    };

    // 位置情報取得の処理
    navigator.geolocation.getCurrentPosition(showPosition, showError, options);
    // Fires success function immediately and when user position changes
  </script>
</html>

<!-- search function doesnt work -->
