// Back to Top Button
let backToTopButton = document.getElementById("back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function () {
  scrollFunction();
};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    backToTopButton.style.display = "block";
  } else {
    backToTopButton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
backToTopButton.addEventListener("click", function (event) {
  event.preventDefault(); // Prevent default anchor behavior
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});
