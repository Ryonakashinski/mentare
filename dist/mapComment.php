<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Awa Awa Map</title>

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <!-- leaflet js -->
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

  <!-- Leaflet Control Geocoder -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <!-- <link rel="stylesheet" href="../css/map.css" /> -->
  <!-- tailwind
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" /> -->


  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<!-- the interactive web map -->

<body class="bg-blue-100 text-blue-800 font-sans">
  <h1 class="title text-3xl text-center mt-8">Awa Awa Map</h1>
  <div id="container" class="flex flex-col items-center justify-center mt-4">
    <div id="map" class="w-full h-96 bg-blue-300 rounded-lg shadow-lg mb-8"></div>
    <div id="content-container" class="bg-white p-4 rounded-lg shadow-lg max-w-md mx-auto mt-4">
      <h1 class="text-2xl font-semibold mb-4">Welcome to Awa Awa Map</h1>
      <p class="text-lg">
        Create your own bubble. Be a bubble. It's okay, just tiny bubbles. If you
        make lots of tiny bubbles, they will become water.
      </p>
      <div class="bubble-container mt-8">
        <!-- Generate bubbles using JavaScript -->
        <script src="home.js"></script>
      </div>
    </div>

    <!-- Comment Section -->
    <div id="comment-container" class="bg-white p-4 rounded-lg shadow-lg max-w-md mx-auto mt-4">
      <h2 class="text-2xl font-semibold mb-4">Comments</h2>
      <div class="mb-4">
        <label for="name" class="text-lg">Your Name:</label>
        <input type="text" id="name" class="w-full p-2 border rounded mt-1" />
      </div>
      <div class="mb-4">
        <label for="comment" class="text-lg">Your Comment:</label>
        <textarea id="comment" rows="4" cols="30" placeholder="Add your comment here" class="w-full p-2 border rounded mt-1"></textarea>
      </div>
      <div>
        <button id="saveComment" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Comment</button>
      </div>
    </div>
    <ul id="commentsList" class="mt-4 bg-white rounded-lg shadow-lg p-4"></ul>
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

    import {
      initializeApp
    } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
    import {
      getFirestore,
      collection,
      addDoc,
      serverTimestamp,
      onSnapshot,
      query,
      orderBy,
      getDocs
    } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";

    document.addEventListener("DOMContentLoaded", function() {
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
          const commentsCollection = collection(db, "comments");
          // Define the commentText variable to capture the comment text
          let commentText = "";
          // Define the marker variable at a higher scope
          let marker;
          // Define formattedTimestamp at a higher scope
          let formattedTimestamp = "";

          // Function to add a popup to a specific location marker
          function addPopupToLocation(lat, lng, message, commentId, formattedTimestamp) {
            const popup = L.popup().setContent('<p>Your popup content goes here.</p>');

            const popupContent = `
      <div class="bg-blue-200 p-4 rounded-lg shadow-lg max-w-md mx-auto mt-4">
      <span class="text-2xl font-bold">${name}</span>: ${message}<br>
      <textarea id="comment" rows="4" cols="30" placeholder="Add your comment here" class="w-full p-2 border rounded mt-2 text-lg"></textarea>
      <button onclick="saveComment(${lat}, ${lng})" class="bg-blue-500 text-white px-4 py-2 rounded mt-2 hover:bg-blue-600 text-lg">Save</button>
      <button onclick="deleteComment('${commentId}', ${lat}, ${lng})" class="bg-red-500 text-white px-4 py-2 rounded mt-2 hover:bg-red-600 text-lg">Delete</button>
    </div>
      `; // Customize the message content

            marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(popup);
            // Set the popup content with your customized HTML
            popup.setContent(popupContent);



            // Function to add a new comment to Firestore
            function addCommentToFirestore(name, text, lat, lng) {
              const commentData = {
                name: name,
                text: text,
                latitude: lat,
                longitude: lng,
                time: serverTimestamp(),
              };

              // Add the comment document to Firestore
              addDoc(commentsCollection, commentData)
                .then(() => {
                  console.log("Comment added successfully");
                  // Clear the comment input field
                  clearCommentInput();

                  // After saving the comment, update the UI (if needed)
                  // For example, you can display the comment on the map.
                  displayCommentsOnMap();
                })
                .catch((error) => {
                  console.error("Error adding comment:", error);
                });
            }

            // Function to clear the comment input field
            function clearCommentInput() {
              const textElement = document.getElementById("comment");
              textElement.value = "";
            }

            // Function to display comments on the map
            function displayCommentsOnMap() {
              const commentsCollection = collection(db, "comments"); // Reference to the "comments" collection in Firestore.
              const querySnapshot = query(commentsCollection, orderBy("time", "asc")); // Create a query to order comments by time (you can modify this as needed)

              // Listen for changes in the comments collection
              onSnapshot(querySnapshot, (snapshot) => {
                snapshot.forEach((doc) => {
                  const commentData = doc.data();
                  const lat = commentData.latitude;
                  const lng = commentData.longitude;
                  const message = `${commentData.name}: ${commentData.text}`;
                  const timestamp = commentData.time; // Get the timestamp as milliseconds

                  if (timestamp) { // Check if timestamp is not null or undefined
                    const formattedTimestamp = timestamp.toMillis();
                    // Include the timestamp in the message
                    const messageWithTime = `${message}<br>Time: ${formattedTimestamp}`;
                    // Format the timestamp to a human-readable date and time
                    const formattedDate = new Date(formattedTimestamp).toLocaleString();
                    // Add a marker with a popup for the comment
                    addPopupToLocation(lat, lng, messageWithTime, doc.id, formattedTimestamp);
                  }
                });
              });
            }

            // Function to retrieve user comment data from Firestore
            async function displayCommentsFromFirestore() {
              const commentsCollection = collection(db, "comments"); // Need to have a "comments" collection in Firestore
              const querySnapshot = await getDocs(commentsCollection); // Query Firestore to get the user's comment data (replace with appropriate query)

              querySnapshot.forEach((doc) => {
                const commentData = doc.data();
                const lat = commentData.latitude;
                const lng = commentData.longitude;
                const message = `${commentData.name}: ${commentData.text}`;
                // Add a marker with a popup for the comment
                addPopupToLocation(lat, lng, message, doc.id, formattedTimestamp);
              });
            }


            // SAVE function
            // Event listener for the "Save Comment" button
            $("#saveComment").on("click", function(event) {
              event.preventDefault(); // Prevent default form submission behavior

              // Get user input
              const name = $("#name").val();
              const textElement = document.getElementById("comment"); // Get the textarea element
              const text = textElement.value; // Get the text value from the textarea

              // Check if name and text are not empty
              if (name.trim() === "" || text.trim() === "") {
                console.error("Name and comment text cannot be empty.");
                return;
              }

              navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                // Add the comment to Firestore
                addCommentToFirestore(name, text, lat, lng);
              });
              // Function to display messages from the "output" list on the map
              function displayOutputOnMap() {
                const outputList = document.getElementById("output");
                const messages = outputList.getElementsByTagName("li");

                for (const message of messages) {
                  const messageText = message.textContent;
                  const lat = parseFloat(message.getAttribute("data-lat"));
                  const lng = parseFloat(message.getAttribute("data-lng"));

                  if (!isNaN(lat) && !isNaN(lng)) {
                    // Add a popup to the specified location
                    addPopupToLocation(lat, lng, message, commentId, formattedTimestamp);
                  }
                }
              }
              // Add an event listener to the "Save" button
              popup.on("popupopen", function() {
                const saveCommentButton = document.querySelector(`[data-lat='${lat}'][data-lng='${lng}'] button[data-action='save']`);
                saveCommentButton.addEventListener("click", function() {
                  const commentId = saveCommentButton.getAttribute("data-commentId"); // Get the commentId from the button's data attribute
                  handleSaveClick(lat, lng);
                });
                const deleteButton = document.querySelector(`[data-action="delete"][data-commentId="${commentId}"][data-lat="${lat}"][data-lng="${lng}"]`);
                deleteButton.addEventListener("click", function() {

                  deleteComment(commentId, lat, lng);
                });
              });
              // Function to handle the "Save" button click
              function handleSaveClick(lat, lng, name, text) {
                const commentData = {
                  text: text,
                  time: serverTimestamp(),
                  latitude: lat,
                  longitude: lng,
                  name: name,
                };
                addDoc(commentsCollection, commentData)
                  .then(() => {
                    console.log("Comment added successfully");
                    $("#text").val("");
                    displayCommentsOnMap();
                  })
                  .catch((error) => {
                    console.error("Error adding comment:", error);
                  });
              }





            });









            // Delete

            // Add a click event listener to the "Delete Comment" button inside each popup
            map.on("popupopen", function(event) {
              const popup = event.popup;
              const commentIdMatch = popup.getContent().match(/deleteComment\('([^']+)'/);
              if (commentIdMatch && commentIdMatch[1]) {
                const deleteButton = popup.getContent().querySelector(".delete-button");
                if (deleteButton) {
                  deleteButton.addEventListener("click", function() {
                    const commentId = commentIdMatch[1];
                    const lat = popup.getLatLng().lat;
                    const lng = popup.getLatLng().lng;
                    deleteComment(commentId, lat, lng);
                  });
                }
              }
            });

            // Function to delete a comment and its associated marker and popup
            async function deleteComment(commentId, lat, lng) {
              try {
                // Delete the comment document from Firestore
                await deleteDoc(doc(commentsCollection, commentId));
                console.log("Comment deleted successfully");

                // Remove the marker associated with the comment
                map.eachLayer(function(layer) {
                  if (layer instanceof L.Marker) {
                    const markerCommentId = layer
                      .getPopup()
                      .getContent()
                      .match(/deleteComment\('([^']+)'/);
                    if (markerCommentId && markerCommentId[1] === commentId) {
                      map.removeLayer(layer);
                    }
                  }
                });
              } catch (error) {
                console.error("Error deleting comment:", error);
              }
            }

            // Attach a click event listener to the "Save Comment" button
            document.getElementById("saveComment").addEventListener("click", function() {
              if (marker) {
                const lat = marker.getLatLng().lat;
                const lng = marker.getLatLng().lng;
                saveComment(lat, lng);
              } else {
                console.error("Marker is not defined. Ensure that a marker is created.");
              }
            });

            // Initial display of comments
            displayCommentsOnMap();

            // Call the function to display comments from Firestore when the page loads
            window.addEventListener("load", displayCommentsFromFirestore);
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let marker;
      if ("geolocation" in navigator) {
        // map initialization. L is for leaflip
        map = L.map("map").setView([0, 0], 2);

        // Create a marker and circle with default position (will be updated)
        const marker = L.marker([0, 0]).addTo(map); // Define the marker here
        var circle = L.circle([0, 0], {
          radius: 1000
        }).addTo(map);

        // Create a popup
        const popup = L.popup().setContent("This is your bubble!");

        // Use the Geolocation API to get your current location

        navigator.geolocation.getCurrentPosition(function(position) {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;

          // Set the marker and circle to your current location
          marker.setLatLng([lat, lng]);
          circle.setLatLng([lat, lng]);

          // Set the map view to your current location
          map.setView([lat, lng], 12);

          // Bind the popup to the marker and open it
          marker.bindPopup(popup).openPopup();

          const saveCommentButton = document.querySelector(`[data-lat='${lat}'][data-lng='${lng}'] button[data-action='save']`);
          if (saveCommentButton) {
            saveCommentButton.addEventListener("click", function() {
              saveComment(lat, lng, commentId);
            });
          }
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
        "https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}.png", {
          attribution: "©OpenStreetMap, ©CartoDB",
        }
      ).addTo(map);

      // CartoDB's Positron map tiles for the labels
      var positronLabels = L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png", {
          attribution: "©OpenStreetMap, ©CartoDB",
          pane: "labels",
        }
      ).addTo(map);

      L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 5,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
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