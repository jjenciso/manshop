document.addEventListener("DOMContentLoaded", function () {
  var images = [
    "img/banner/b.webp",
    "img/banner/banner1.jpg",
    "img/banner/banner5.jpg",
    "img/banner/banner6.webp",
  ];
  var currentIndex = 0;
  var hero = document.getElementById("hero");

  function changeBackground() {
    hero.style.backgroundImage = "url('" + images[currentIndex] + "')";
    currentIndex = (currentIndex + 1) % images.length;
  }

  // Change background every 5 seconds (adjust the duration as needed)
  setInterval(changeBackground, 3000);
});

document.addEventListener("DOMContentLoaded", function () {
  // Ensure the navbar element exists
  var navbar = document.getElementById("navbar");
  if (!navbar) return;

  var links = navbar.getElementsByClassName("page");

  // Function to handle adding and removing the "active" class
  function handleLinkClick(clickedLink) {
    // Remove the active class from all links
    for (var j = 0; j < links.length; j++) {
      links[j].classList.remove("active");
    }

    // Add the active class to the clicked link
    clickedLink.classList.add("active");
  }

  for (var i = 0; i < links.length; i++) {
    links[i].addEventListener("click", function () {
      handleLinkClick(this);
    });

    // Check if the current link's href matches the current URL
    if (window.location.href.indexOf(links[i].getAttribute("href")) > -1) {
      handleLinkClick(links[i]);
    }
  }
});

// Ensure the bar, navbar, and close elements exist
const bar = document.getElementById("bar");
const nav = document.getElementById("navbar");
const close = document.getElementById("close");

if (bar && nav && close) {
  bar.addEventListener("click", () => {
    nav.classList.add("active");
  });

  close.addEventListener("click", () => {
    nav.classList.remove("active");
  });
}
