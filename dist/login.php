<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Firebase Authentication</title>
    <link rel="stylesheet" type="text/css" href="../css/signup.css" />
  </head>

  <body>
    
    <form method="POST" action="login_action.php" >

    <div id="login-box" class="modal">
      <div class="left">
        <h1>Login</h1>
        <br />

        <input type="text" name= 'email' placeholder="Email" />
        <input type="password" name='password' placeholder="Password" />
        <input type="submit" name="login" value="Login" />
      </div>
      Don't have an account yet? <a href="signup.php">Sign up</a>
      <!-- <div class="or">OR</div>
      <div class="right">
        <span class="loginwith">Log in with<br />social network</span>
        <button class="social-signin facebook">Log in with facebook</button>
        <button class="social-signin X">Log in with X</button>
        <button class="social-signin google">Log in with Google</button>
      </div> -->
    </div>

    <button id="logout">Logout</button>
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
      document.addEventListener("DOMContentLoaded", function () {
      // const app = initializeApp(firebaseConfig);
      // const database = getDatabase(app);
      // const auth = getAuth();
      const signup = document.getElementById("signup");
      const login = document.getElementById("login");
      const logout = document.getElementById("logout");

      signup.addEventListener("click", (e) => {
        e.preventDefault(); // Prevent form submission (the default behavior)
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

      login.addEventListener("click", (e) => {
        e.preventDefault(); // Prevent form submission (the default behavior)
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;

        signInWithEmailAndPassword(auth, email, password)
          .then((userCredential) => {
            // Signed in
            const user = userCredential.user;

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
      });

      const user = auth.currentUser;
      onAuthStateChanged(auth, (user) => {
        if (user) {
          // User is signed in, see docs for a list of available properties
          // https://firebase.google.com/docs/reference/js/auth.user
          const uid = user.uid;
          // ...
        } else {
          // User is signed out
          // ...
        }
      });

      logout.addEventListener("click", (e) => {
        signOut(auth)
          .then(() => {
            // Sign-out successful.
            alert("user logged out");
          })
          .catch((error) => {
            const errorCode = error.code;
            const errorMessage = error.message;

            alert(errorMessage);
          });
      });
    });
    </script>
  </body>
</html>
