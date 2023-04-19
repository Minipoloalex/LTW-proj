function toggleSubMenu() {
  var subMenu = document.getElementById("subMenu");
  var subHeader = document.getElementById("subHeader");
  // var asd = subHeader.querySelectorAll(".material-symbols-outlined, .link-text")
  
  
  if (subMenu.style.display === "block") {
    subMenu.style.display = "none";
    subHeader.style.height = "5rem";
  } else {
    subMenu.style.display = "block";
    subHeader.style.height = "fit-content";
    // asd.forEach( (element) => {
    //   element.style.alignItems = "start";
    // });
    // asd.style.position
  }

  // Close submenu when mouse leaves navbar
  var navbar = document.querySelector('.navbar');
  navbar.addEventListener('mouseleave', function() {
    subMenu.style.display = "none";
    subHeader.style.height = "5rem";
  });
}
