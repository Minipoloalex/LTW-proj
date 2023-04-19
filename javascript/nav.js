function toggleSubMenu() {
  var subMenu = document.getElementById("subMenu");
  var subHeader = document.getElementById("subHeader");
  if (subMenu.style.display === "block") {
    subMenu.style.display = "none";
  } else {
    subMenu.style.display = "block";
    subHeader.style.height = "fit-content";
  }
  if (subMenu.style.display === "none") {
    subHeader.style.height = "5rem";
  }
}