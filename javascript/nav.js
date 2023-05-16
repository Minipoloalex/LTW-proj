const subWrapper = document.getElementById("subWrapper");
const navPopup = document.getElementById("navPopup");

window.addEventListener("resize", function () {
  if (window.matchMedia("(min-width: 961px)").matches) {
    navPopup.classList.remove("active");
  }
})

subWrapper.addEventListener('click', function toggleSubMenu() {
  const navPopup = document.getElementById("navPopup");

  if (window.matchMedia("(min-width: 961px)").matches) {
    //big screen
    if (subWrapper.classList.contains("active")) {
      subWrapper.classList.remove("active");
    } else {
      subWrapper.classList.add("active");
    }

    // Close submenu when mouse leaves navbar
    const navbar = document.querySelector(".navbar");
    navbar.addEventListener("mouseleave", function () {
      subWrapper.classList.remove("active");
    });
  }
  else {
    //small screen
    if (navPopup.classList.contains("active")) {
      navPopup.classList.remove("active");
    } else {
      navPopup.classList.toggle("active");
    }
  }
});
