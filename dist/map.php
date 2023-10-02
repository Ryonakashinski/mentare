<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Awa Awa Map</title>

    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
      integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
      crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <!-- leaflet js -->
    <script
      src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
      integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
      crossorigin=""></script>

    <!-- Leaflet Control Geocoder -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- <link rel="stylesheet" href="../css/map.css" /> -->
    <!-- tailwind
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" /> -->
    <link rel="stylesheet" href="../css/map.css" />
  </head>

  <!-- the interactive web map -->
  <body>
    <h1 class="title">Awa Awa Map</h1>

    <div id="container">
      <div id="map" style="width: 100%; height: 500px;"></div>

      

      
      <!-- Message list container -->
      <ul id="messageList" class="message-list map-block"></ul>

      <div id="content-container">
        <h1>
          Create your own bubble. Be bubble. it's ok just tiny bubble. if you
          make a lots of tiny bubble, it will become water.
        </h1>

        <div class="bubble-container">
          <!-- Generate bubbles using JavaScript -->
          <script src="home.js"></script>
        </div>
      </div>

      <!-- text message -->
      <div id="chatBoxContainer">
        <div id="chatBox">
          <div>
            <label for="name">Name:</label>
            <input type="text" id="name" />
          </div>
          <div>
            <label for="text">Message:</label>
            <input type="text" id="text" />
          </div>
          <div>
            <button id="send">Send</button>
          </div>
        </div>
      </div>
      <ul id="output"></ul>
    </div>

    <script>
      // timestamp conversion
      function convertTimestampToDatetime(timestamp) {
        const _d = timestamp ? new Date(timestamp * 1000) : new Date();
        const Y = _d.getFullYear();
        const m = (_d.getMonth() + 1).toString().padStart(2, "0");
        const d = _d.getDate().toString().padStart(2, "0");
        const H = _d.getHours().toString().padStart(2, "0");
        const i = _d.getMinutes().toString().padStart(2, "0");
        const s = _d.getSeconds().toString().padStart(2, "0");
        return `${Y}/${m}/${d} ${H}:${i}:${s}`;
      }
    </script>
    <!-- firebase -->

    <script type="module">
      // Import the functions you need from the SDKs you need
      import { initializeApp } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
      import {
        getFirestore,
        collection,
        addDoc,
        serverTimestamp,
        onSnapshot,
        query,
        orderBy,
      } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";

      const firebaseConfig = {
        apiKey: "AIzaSyApaD_ljhGSaG9jHmjqjSPXcScBbUcbkyI",
        authDomain: "map-chat-ryo.firebaseapp.com",
        projectId: "map-chat-ryo",
        storageBucket: "map-chat-ryo.appspot.com",
        messagingSenderId: "480374890328",
        appId: "1:480374890328:web:1f970901ba52bec8ba0f2d",
      };

      // Initialize Firebase
      const app = initializeApp(firebaseConfig);

      const db = getFirestore(app);

      // message space
      // Function to add a popup to a specific location marker
      function addPopupToLocation(lat, lng, message) {
        const popupContent = `<p>${message}</p>`; // Customize the message content
        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup(popupContent).openPopup(); // Display message as a popup
      }

      // Function to display messages from the "output" list on the map
      function displayOutputOnMap() {
        const messageList = document.getElementById("messageList");
        const messages = messageList.getElementsByTagName("message-list");

        for (const message of messages) {
          const messageText = message.textContent;
          const lat = parseFloat(message.getAttribute("data-lat"));
          const lng = parseFloat(message.getAttribute("data-lng"));

          if (!isNaN(lat) && !isNaN(lng)) {
            // Add a popup to the specified location
            addPopupToLocation(lat, lng, messageText);
          }
        }
      }

      // Call the function to display "output" messages on the map
      displayOutputOnMap();

      // Function to display messages from Firebase Firestore on the map
      function displayMessagesFromFirestore() {
        const messageList = document.getElementById("messageList");

        // Reference to the "markers" collection
        const markersCollection = collection(db, "markers");

        // Create a query to order messages by time
        const querySnapshot = query(
          markersCollection,
          orderBy("time", "asc") // You can change "asc" to "desc" for reverse order
        );

        // Listen for changes to the query
        onSnapshot(querySnapshot, (snapshot) => {
          snapshot.docChanges().forEach((change) => {
            if (change.type === "added") {
              // When a new message is added, create a list item for it
              const messageData = change.doc.data();
              const messageItem = document.createElement("li");
              messageItem.innerHTML = `<b>${messageData.name}</b>: ${messageData.text}`;
              messageList.appendChild(messageItem);
            }
          });
        });
      }

      // Call the function to display messages from Firestore on page load
      displayMessagesFromFirestore();

      // send the data to firebase.
      $("#send").on("click", function (event) {
        event.preventDefault(); // Prevent default form submission behavior
        navigator.geolocation.getCurrentPosition(function (position) {
          //it gets the current location
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          const name = $("#name").val();
          const text = $("#text").val();

          // Add a marker with a popup for the new message
          const marker = L.marker([lat, lng]).addTo(map);
          const popupContent = `<b>${name}</b>: ${text}<br>`;
          marker.bindPopup(popupContent);

          // Add the marker to the map
          marker.addTo(map);

          // Open the popup immediately when the marker is added
          marker.openPopup();

          // Add the message to Firebase
          const postData = {
            name: name,
            text: text,
            time: serverTimestamp(),
            latitude: lat,
            longitude: lng,
          };

          // Reference to the "markers" collection
          const markersCollection = collection(db, "markers");

          // Add the document to Firestore
          addDoc(markersCollection, postData)
            .then(() => {
              console.log("Document added successfully");
              $("#text").val("");
            })
            .catch((error) => {
              console.error("Error adding document:", error);
            });
        });
      });
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        if ("geolocation" in navigator) {
          // map initialization. L is for leaflip
          var map = L.map("map").setView([0, 0], 2);

          // Create a marker and circle with default position (will be updated)
          var marker = L.marker([0, 0]).addTo(map);
          var circle = L.circle([0, 0], { radius: 1000 }).addTo(map);

          // Create a popup
          var bubblePopup = L.popup().setContent("This is your bubble!");

          // Use the Geolocation API to get your current location

          navigator.geolocation.getCurrentPosition(function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            // Set the marker and circle to your current location
            marker.setLatLng([lat, lng]);
            circle.setLatLng([lat, lng]);

            // Set the map view to your current location
            map.setView([lat, lng], 12);

            // Bind the popup to the marker and open it
            marker.bindPopup(bubblePopup).openPopup();
          });
        } else {
          console.log("Geolocation is not available in this browser.");
        }

        // a blank map
        map.createPane("labels");
        map.getPane("labels").style.zIndex = 650;
        // disable pointer events for it
        map.getPane("labels").style.pointerEvents = "none";
        // CartoDB's Positron map tiles for the map background
        var positron = L.tileLayer(
          "https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}.png",
          {
            attribution: "©OpenStreetMap, ©CartoDB",
          }
        ).addTo(map);

        // CartoDB's Positron map tiles for the labels
        var positronLabels = L.tileLayer(
          "https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png",
          {
            attribution: "©OpenStreetMap, ©CartoDB",
            pane: "labels",
          }
        ).addTo(map);

        //
        //

        L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
          maxZoom: 5,
          attribution:
            '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        }).addTo(map);

        // success function is going to fire!
        function success(pos) {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;
          const accuracy = pos.coords.accuracy;

          // zoom the map to the extent of the circle.

          if (marker) {
            map.removeLayer(marker);
            map.removeLayer(circle);
          }

          if (!zoomed) {
            map.fitBounds(circle.getBounds());
            zoomed = true;
          }
        }

        function error(err) {
          if (err.code === 1) {
            alert("Please allow geolocation access");
          } else {
            alert("Cannot get current location");
          }
        }

        function highlightFeature(e) {
          var layer = e.target;

          layer.setStyle({
            weight: 5,
            color: "#666",
            dashArray: "",
            fillOpacity: 0.7,
          });

          layer.bringToFront();
        }
      });
    </script>
  </body>
</html>
