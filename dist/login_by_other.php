<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>

  <body>
    <form method='POST' action="login_action.php" >

    <div id="login-box" class="modal">
      <div class="left">
        <h1>Login</h1>
        <br />
        <input type="text" id="username" placeholder="username" />

        <input type="text" id="email" placeholder="Email" />
        <input type="password" id="password" placeholder="Password" />
        <input type="submit" id="login" name="login" value="Login" />
      </div>
      <div class="right">
        <span class="loginwith">Log in with<br />social network</span>
        <button class="social-signin facebook">Log in with facebook</button>
        <button class="social-signin X">Log in with X</button>
        <button class="social-signin google" id="login">
          Log in with Google
        </button>
      </div>
      <div class="or">OR</div>
    </div>
    </form>
  </body>

  <script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-app.js";
    import {
      getAuth,
      GoogleAuthProvider,
      signInWithRedirect,
      getRedirectResult,
    } from "https://www.gstatic.com/firebasejs/10.4.0/firebase-auth.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: "AIzaSyDfd5EVQJylrnJvkE7Ye1C-furfJzWDNJU",
      authDomain: "auth-a5cd8.firebaseapp.com",
      projectId: "auth-a5cd8",
      storageBucket: "auth-a5cd8.appspot.com",
      messagingSenderId: "789837485416",
      appId: "1:789837485416:web:991d0566c3571ce382f2dd",
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const provider = new GoogleAuthProvider(app);

    login.addEventListener("click", (e) => {
      signInWithRedirect(auth, provider);
      getRedirectResult(auth)
        .then((result) => {
          // This gives you a Google Access Token. You can use it to access Google APIs.
          const credential = GoogleAuthProvider.credentialFromResult(result);
          const token = credential.accessToken;

          // The signed-in user info.
          const user = result.user;
          // IdP data available using getAdditionalUserInfo(result)
          // ...
        })
        .catch((error) => {
          // Handle Errors here.
          const errorCode = error.code;
          const errorMessage = error.message;
          // The email of the user's account used.
          const email = error.customData.email;
          // The AuthCredential type that was used.
          const credential = GoogleAuthProvider.credentialFromError(error);
          // ...
        });
    });
  </script>
</html>
