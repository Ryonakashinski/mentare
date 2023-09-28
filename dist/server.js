const express = require("express");
const bodyParser = require("body-parser");
const app = express();
const port = process.env.PORT || 3000;

app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());

// Add your Firebase configuration here
const firebaseConfig = {
  apiKey: "AIzaSyA0KwWaB8VkLxu3CJdwWiTrQuVNtKEe6gg",
  authDomain: "authentication-app-25138.firebaseapp.com",
  databaseURL: "https://authentication-app-25138-default-rtdb.firebaseio.com",
  projectId: "authentication-app-25138",
  storageBucket: "authentication-app-25138.appspot.com",
  messagingSenderId: "1015831326478",
  appId: "1:1015831326478:web:d6705f7f4c65dfcdc7b15f",
};

// Handle GET request
app
  .get("/api/data", (req, res) => {
    // Implement code to retrieve data from your Firebase database
    // Example:
    // const data = ...; // Retrieve data from Firebase
    const user = userCredential.user;
    // res.json(data);
    set(ref(database, "users/" + user.uid), {
      username: username,
      email: email,
    });
    alert("user created");
  })
  .catch((error) => {
    const errorCode = error.code;
    const errorMessage = error.message;

    alert(errorMessage);
  });

// Handle POST request
app
  .post("/api/data", (req, res) => {
    // Implement code to add data to your Firebase database
    // Example:
    // const newData = req.body;
    const dt = new Date();
    update(ref(database, "users/" + user.uid), {
      last_login: dt,
    });
    alert("User loged in!");
  })
  .catch((error) => {
    const errorCode = error.code;
    const errorMessage = error.message;

    alert(errorMessage);
  });
// Add newData to Firebase
// res.json({ message: 'Data added successfully' });

app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
