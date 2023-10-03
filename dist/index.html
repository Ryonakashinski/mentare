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
    import {
      initializeApp
    } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
    import {
      getDatabase,
      set,
      ref,
      update,
    } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-firestore.js";
    import {
      getAuth,
      createUserWithEmailAndPassword,
      signInWithEmailAndPassword,
      onAuthStateChanged,
      signOut,
    } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-auth.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: "AIzaSyApaD_ljhGSaG9jHmjqjSPXcScBbUcbkyI",
      authDomain: "map-chat-ryo.firebaseapp.com",
      databaseURL: "https://map-chat-ryo-default-rtdb.asia-southeast1.firebasedatabase.app",
      projectId: "map-chat-ryo",
      storageBucket: "map-chat-ryo.appspot.com",
      messagingSenderId: "480374890328",
      appId: "1:480374890328:web:1f970901ba52bec8ba0f2d"
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