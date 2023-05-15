/*login*/

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

/*confirm password*/

const confirmPasswordInput = document.getElementById("confirm-password");
const showConfirmPassButton = document.getElementById("confirm-showpass");
const hideConfirmPassButton = document.getElementById("confirm-hidepass");
// console.log(showConfirmPassButton);

if (showConfirmPassButton) {
showConfirmPassButton.addEventListener("click", function() {
    confirmPasswordInput.type = "text";
    hideConfirmPassButton.hidden = false;
    showConfirmPassButton.hidden = true;
  });
  
hideConfirmPassButton.addEventListener("click", function() {
    confirmPasswordInput.type = "password";
    hideConfirmPassButton.hidden = true;
    showConfirmPassButton.hidden = false;
  });
}
