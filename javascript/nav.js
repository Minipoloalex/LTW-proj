function toggleSubMenu() {
  const navbar = document.querySelector(".navbar");
  const navItems = document.querySelectorAll(".nav-item");
  const subMenu = document.getElementById("subMenu");
  const subWrapper = document.getElementById("subWrapper");
  const subHeaderItems = document.querySelectorAll("#subWrapper .material-symbols-outlined, #subWrapper #subHeaderTitle");
  const subHeader = document.querySelector(".nav-submenu-header");
  console.log(subHeader);
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
  } else {
    //small screen
      if(navbar.style.height === "5rem"){
        navbar.style.height = "10rem";
        for (var element of navItems) {
          element.style.marginTop = "auto";
        }

      } else {
        navbar.style.height = "5rem";
      }
  }
}
