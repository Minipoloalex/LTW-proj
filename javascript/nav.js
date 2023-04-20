function toggleSubMenu() {
  const subMenu = document.getElementById("subMenu");
  const subHeader = document.getElementById("subHeader");
  const subHeaderLogo = document.querySelector("#subHeader .material-symbols-outlined")
  // var asd = subHeader.querySelectorAll(".material-symbols-outlined, .link-text")
  
  
  if (subMenu.style.display === "block") {
    // para fechar
    subMenu.style.display = "none";
    subHeader.style.height = "5rem";
  } else {
    // para abrir
    subMenu.style.display = "block";
    subHeader.style.height = "fit-content"; 
    //styles com toggle
    // asd.forEach( (element) => {
    //   element.style.alignItems = "start";
    // });
    // asd.style.position
  }

  // Close submenu when mouse leaves navbar
  const navbar = document.querySelector(".navbar");
  navbar.addEventListener("mouseleave", function() {
    // para fechar
    subMenu.style.display = "none";
    subHeader.style.height = "5rem";
  });
}
