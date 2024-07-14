// Hamburger Sidebar Expand
const hamburger = document.querySelector("#toggle-btn");

hamburger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});
