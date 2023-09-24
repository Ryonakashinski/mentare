// sign up form
document.addEventListener("DOMContentLoaded", function () {
  const signupForm = document.getElementById("signup-form");
  const signupEmail = document.getElementById("signup-emeil");
  const signupPassword = document.getElementById("signup-password");
  const signupButton = document.getElementById("signup-button");
  const loginButton = document.getElementById("login-button");

  //sign-up event listener
  signupButton.addEventListener("click", function () {
    const email = signupEmail.value;
    const password = signupPassword.value;

    firebase
      .auth()
      .creteUserWithEmailAndPassword(email, password)
      .then((userCredential) => {
        //successful sign-up
        const user = userCredential.user;
        console.log("user signed up:", user);
        // it would be better to redirect to a new page or perfoem other actions here.
      })
      .catch((error) => {
        const errorCode = error.code;
        const errorMessage = error.message;
        console.error("sign-up error:", errorCode, errorMessage);
      });
  });
  // Login event listener
  loginButton.addEventListener("click", function () {
    const email = signupEmail.value;
    const password = signupPassword.value;

    firebase
      .auth()
      .signInWithEmailAndPassword(email, password)
      .then((userCredential) => {
        // Successful login
        const user = userCredential.user;
        console.log("user logged in:", user);
        //you can redirect to a new page or perform other actions here.
      })
      .catch((error) => {
        const errorCode = error.code;
        const errorMessage = error.message;
        console.error("Login error:");
      });
  });
});
