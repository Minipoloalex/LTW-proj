const subWrapper = document.getElementById("subWrapper");
const navPopup = document.getElementById("navPopup");

window.addEventListener("resize", function () {
  if (window.matchMedia("(min-width: 961px)").matches) {
    navPopup.classList.remove("active");
  }
})

subWrapper.addEventListener('click', function toggleSubMenu() {
  const navbar = document.querySelector(".navbar");
  // const navItems = document.querySelectorAll(".nav-item");
  const subMenu = document.getElementById("subMenu");
  const navPopup = document.getElementById("navPopup");

  // const subHeaderItems = document.querySelectorAll("#subWrapper .material-symbols-outlined, #subWrapper #subHeaderTitle");
  const subHeader = document.querySelector(".nav-submenu-header");
  console.log("click");

  // if (window.matchMedia("(min-width: 600px)").matches) {
  if (window.matchMedia("(min-width: 961px)").matches) {
    //big screen
    if (subMenu.style.display === "block") {
      // para fechar
      subMenu.style.display = "none";
      subWrapper.style.height = "5rem";
      subWrapper.style.alignItems = "center";
      subHeader.style.paddingTop = "0rem";
    } else {
      // para abrir
      subMenu.style.display = "block";
      subWrapper.style.height = "fit-content";
      subWrapper.style.alignItems = "start";
      subHeader.style.paddingTop = "2.05rem";
    }

    // Close submenu when mouse leaves navbar
    const navbar = document.querySelector(".navbar");
    navbar.addEventListener("mouseleave", function () {
      // para fechar
      subMenu.style.display = "none";
      subWrapper.style.height = "5rem";
      subWrapper.style.alignItems = "center";
      subHeader.style.paddingTop = "0rem";
    });
  }
  else {
    //small screen
    if (navPopup.style.display === "block") {
      navPopup.classList.remove("active");
    } else {
      navPopup.classList.toggle("active");
      // document.addEventListener('click', function(event) {
      //     const outsideClick = (!navPopup.contains(event.target));
      //     console.log(outsideClick);
      //     if (outsideClick) { navPopup.classList.remove("active"); }
      //   });
    }
  }
});
