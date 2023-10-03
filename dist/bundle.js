/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./dist/auth.js":
/*!**********************!*\
  !*** ./dist/auth.js ***!
  \**********************/
/***/ (() => {

eval("// sign up form\r\ndocument.addEventListener(\"DOMContentLoaded\", function () {\r\n  const signupForm = document.getElementById(\"signup-form\");\r\n  const signupEmail = document.getElementById(\"signup-emeil\");\r\n  const signupPassword = document.getElementById(\"signup-password\");\r\n  const signupButton = document.getElementById(\"signup-button\");\r\n  const loginButton = document.getElementById(\"login-button\");\r\n\r\n  //sign-up event listener\r\n  signupButton.addEventListener(\"click\", function () {\r\n    const email = signupEmail.value;\r\n    const password = signupPassword.value;\r\n\r\n    firebase\r\n      .auth()\r\n      .creteUserWithEmailAndPassword(email, password)\r\n      .then((userCredential) => {\r\n        //successful sign-up\r\n        const user = userCredential.user;\r\n        console.log(\"user signed up:\", user);\r\n        // it would be better to redirect to a new page or perfoem other actions here.\r\n      })\r\n      .catch((error) => {\r\n        const errorCode = error.code;\r\n        const errorMessage = error.message;\r\n        console.error(\"sign-up error:\", errorCode, errorMessage);\r\n      });\r\n  });\r\n  // Login event listener\r\n  loginButton.addEventListener(\"click\", function () {\r\n    const email = signupEmail.value;\r\n    const password = signupPassword.value;\r\n\r\n    firebase\r\n      .auth()\r\n      .signInWithEmailAndPassword(email, password)\r\n      .then((userCredential) => {\r\n        // Successful login\r\n        const user = userCredential.user;\r\n        console.log(\"user logged in:\", user);\r\n        //you can redirect to a new page or perform other actions here.\r\n      })\r\n      .catch((error) => {\r\n        const errorCode = error.code;\r\n        const errorMessage = error.message;\r\n        console.error(\"Login error:\");\r\n      });\r\n  });\r\n});\r\n\n\n//# sourceURL=webpack:///./dist/auth.js?");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./dist/auth.js"]();
/******/ 	
/******/ })()
;