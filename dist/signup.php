<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Firebase Authentication</title>
    <link rel="stylesheet" type="text/css" href="../css/signup.css" />
  </head>

  <body>
    <form method="POST" action="signup_action.php">
    <div id="signup-box" class="modal">
      <div class="left">
        <h1>Signup</h1>
        <br />
        <input type="text" name="name" placeholder="Name" />
        <input type="text" name="email" placeholder="Email" />
        <input type="password" name="password" placeholder="Password" />
        <input type="submit" name="signup" value="Signup" />
        Already have an account? <a href="login.php">Login</a>
      </div>
    </div>
    </form>

    <script type="module">
      // Import the functions you need from the SDKs you need
      import { initializeApp } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-app.js";
      import {
        getDatabase,
        set,
        ref,
        update,
      } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-database.js";
      import {
        getAuth,
        createUserWithEmailAndPassword,
        signInWithEmailAndPassword,
        onAuthStateChanged,
        signOut,
      } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-auth.js";
      // TODO: Add SDKs for Firebase products that you want to use
      // https://firebase.google.com/docs/web/setup#available-libraries

      // Your web app's Firebase configuration
      const firebaseConfig = {
        apiKey: "AIzaSyA0KwWaB8VkLxu3CJdwWiTrQuVNtKEe6gg",
        authDomain: "authentication-app-25138.firebaseapp.com",
        databaseURL:
          "https://authentication-app-25138-default-rtdb.firebaseio.com",
        projectId: "authentication-app-25138",
        storageBucket: "authentication-app-25138.appspot.com",
        messagingSenderId: "1015831326478",
        appId: "1:1015831326478:web:d6705f7f4c65dfcdc7b15f",
      };

      // Initialize Firebase
      const app = initializeApp(firebaseConfig);
      const database = getDatabase(app);
      const auth = getAuth();
      const signup = document.getElementById("signup");
      const login = document.getElementById("login");
      const logout = document.getElementById("logout");

      signup.addEventListener("click", (e) => {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var username = document.getElementById("name").value;

        createUserWithEmailAndPassword(auth, email, password)
          .then((userCredential) => {
            // Sign up
            const user = userCredential.user;

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
      });
      </script>
  </body>
</html>
