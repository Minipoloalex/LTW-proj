function toggleSubMenu() {
  const subMenu = document.getElementById("subMenu");
  const subHeader = document.getElementById("subHeader");
  const subHeaderLogo = document.querySelectorAll("#subHeader .material-symbols-outlined, #subHeader #subHeaderTitle");
  console.log(subHeaderLogo);
  
  if (subMenu.style.display === "block") {
    // para fechar
    subMenu.style.display = "none";
    subHeader.style.height = "5rem";
    subHeader.style.alignItems = "center";
    for (var element of subHeaderLogo) { 
      element.style.marginTop = "0rem";
    }
  } else {
    // para abrir
    subMenu.style.display = "block";
    subHeader.style.height = "fit-content";
    subHeader.style.alignItems = "start";
    for (var element of subHeaderLogo) { 
      element.style.marginTop = "1.7rem";
    }    
    
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
    subHeader.style.alignItems = "center";
    for (var element of subHeaderLogo) { 
      element.style.marginTop = "0rem";
    }
  });
}
