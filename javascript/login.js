/*show password*/

const passwordInput = document.getElementById("password");
const showPassButton = document.getElementById("showpass");
const hidePassButton = document.getElementById("hidepass");

showPassButton.addEventListener("click", function() {
  passwordInput.type = "text";
  showPassButton.hidden = true;
  hidePassButton.hidden = false;
});

hidePassButton.addEventListener("click", function() {
  passwordInput.type = "password";
  hidePassButton.hidden = true;
  showPassButton.hidden = false;
});
