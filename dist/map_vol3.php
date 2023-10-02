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
        A single bubble quickly pops and disappears. However, when many bubbles come together, they gradually form drops, then flow into streams, then rivers, and become the ocean. Be Bubble, my friend.
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
        <button id="sendComment" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Send Comment</button>
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
      getDocs,
      where,
      deleteDoc,
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
      // const commentsCollection = collection(db, "comments");

      // Define the commentText variable to capture the comment text
      let commentText = "";

      // Define formattedTimestamp at a higher scope
      let formattedTimestamp = "";

      // Function to add a popup to a specific location marker
      function addPopupToLocation(lat, lng, comment, commentId, formattedTimestamp) {
        const popup = L.popup(); // Create a popup without content
        const popupContent = `
        <div class="bg-blue-200 p-4 rounded-lg shadow-lg max-w-md mx-auto mt-4">
      <div class="text-blue font-bold">Name: ${name}</div>
      <div class="text-gray-600">Date: ${getDate()}</div>
      <p class="text-blue">Comment: ${comment}</p>
      <div class="mt-4">
        <button class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-full delete-button" data-comment-id="${commentId}">Delete</button>
      </div>
    </div>
        `; // Customize the message content




        const marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup(popupContent).openPopup();
        // Set the popup content with your customized HTML
        popup.setContent(popupContent);

        // Store the marker object with the commentId as the key
        commentMarkers[commentId] = marker;

        // Attach a click event listener to the "Delete" button
        const deleteButton = marker.getPopup().getElement().querySelector(".delete-button");
        deleteButton.addEventListener("click", function() {
          map.removeLayer(marker); // Remove the marker from the map
          deleteComment(commentId); // Delete the comment from Firestore
        });


      }

      // Function to load and display comments from local storage
      function loadCommentsFromLocalStorage() {
        const commentsData = JSON.parse(localStorage.getItem("commentsData"));

        if (commentsData && Array.isArray(commentsData)) {
          commentsData.forEach((commentData) => {
            addPopupToLocation(
              commentData.latitude,
              commentData.longitude,
              commentData.name,
              commentData.text,
              commentData.commentId
            );
          });
        }
      }

      // Function to save a new comment to local storage
      function saveCommentToLocalStorage(commentData) {
        const commentsData = JSON.parse(localStorage.getItem("commentsData")) || [];
        commentsData.push(commentData);
        localStorage.setItem("commentsData", JSON.stringify(commentsData));
      }

      // Function to delete a comment from local storage
      function deleteCommentFromLocalStorage(commentId) {
        const commentsData = JSON.parse(localStorage.getItem("commentsData")) || [];
        const updatedCommentsData = commentsData.filter((commentData) => commentData.commentId !== commentId);
        localStorage.setItem("commentsData", JSON.stringify(updatedCommentsData));
      }


      // Function to delete a comment and its associated marker
      function deleteComment(commentId) {
        // Delete the comment from Firebase Firestore
        const commentsCollection = collection(db, "comments");
        const commentRef = doc(commentsCollection, commentId);

        deleteDoc(commentRef)
          .then(() => {
            console.log(`Comment with ID ${commentId} deleted from Firestore`);
            // Remove the marker from the commentMarkers object
            if (commentMarkers[commentId]) {
              map.removeLayer(commentMarkers[commentId]);
              delete commentMarkers[commentId];
            }
          })
          .catch((error) => {
            console.error(`Error deleting comment from Firestore: ${error}`);
          });
      }

      // When the page loads, load and display comments from local storage
      window.addEventListener("load", function() {
        loadCommentsFromLocalStorage();
      });


      // send the data to firebase.
      $("#sendComment").on("click", function(event) {
        event.preventDefault(); // Prevent default form submission behavior
        function generateCommentId() {
          // Generate a unique ID, for example, using a timestamp and a random number
          const timestamp = new Date().getTime();
          const random = Math.floor(Math.random() * 10000); // You can adjust the range as needed
          const commentId = `${timestamp}-${random}`;
          return commentId;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
          //it gets the current location
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          const name = $("#name").val();
          const comment = $("#comment").val();
          // Add a marker with a popup for the new message
          const marker = L.marker([lat, lng]).addTo(map);
          const commentId = generateCommentId(); // Generate a unique comment ID

          const popupContent = `
          <div class="bg-blue-200 p-4 rounded-lg shadow-lg max-w-md mx-auto mt-4">
  <div class="text-blue font-bold">Name: ${name}</div>
  <div class="text-gray-600">Date: ${getDate()}</div>
  <p class="text-blue">Comment: ${comment}</p>
    <div class="mt-4">
      <button class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-full delete-button">Delete</button>
    </div>
  </div>
    `;
          marker.bindPopup(popupContent).openPopup();

          // Add event listeners to the buttons within the popup
          const popup = marker.getPopup();

          const deleteButton = marker.getPopup().getElement().querySelector(".delete-button");
          deleteButton.addEventListener("click", function() {

            map.removeLayer(marker); // Remove the marker from the map

            // You can also delete the comment from Firestore here if needed
            // Delete the comment from Firebase Firestore
            const commentsCollection = collection(db, "comments");
            const commentQuery = query(commentsCollection, where("name", "==", name)); // Assuming "name" is a field that identifies comments by the user's name

            // Implement the Firestore deletion logic if required
            // Query Firestore to find the comment to delete
            getDocs(commentQuery)
              .then((querySnapshot) => {
                const deletePromises = [];

                querySnapshot.forEach((doc) => {
                  // Delete the comment document from Firestore
                  const deletePromise = deleteDoc(doc.ref)
                    .then(() => {
                      console.log(`Comment by ${name} deleted from Firestore`);
                    })
                    .catch((error) => {
                      console.error(`Error deleting comment from Firestore: ${error}`);
                    });

                  deletePromises.push(deletePromise);
                });

                // Wait for all delete promises to complete
                return Promise.all(deletePromises);
              })
              .catch((error) => {
                console.error(`Error querying Firestore for comments: ${error}`);
              });
          });

          // After creating the marker and popup, you can add the comment data to Firestore
          const commentsCollection = collection(db, "comments");
          const commentData = {
            name: name,
            text: comment,
            latitude: lat,
            longitude: lng,
            time: serverTimestamp(),
          };

          addDoc(commentsCollection, commentData)
            .then(() => {
              console.log("Comment added successfully to Firestore");
              // Clear the comment input field
              $("#comment").val("");
            })
            .catch((error) => {
              console.error(`Error adding comment to Firestore: ${error}`);
            });
        });
      });

      // Function to get the current date in a specific format
      function getDate() {
        const currentDate = new Date();
        const year = currentDate.getFullYear();
        const month = String(currentDate.getMonth() + 1).padStart(2, '0');
        const day = String(currentDate.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
      }
    });


    // Call the function to display comments from Firestore when the page loads
    // window.addEventListener("load", displayCommentsFromFirestore);
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
              sendComment(lat, lng, commentId);
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