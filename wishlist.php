<?php
  include("userheader.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop With US</title>
  </head>
<body>

<style>

    body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  margin: 0;
  padding: 0;
}

.carousel-container {
  width: 100%;
  height: 50vh; /* Adjust height as desired */
  overflow: hidden;
  position: relative;
}

.carousel-track {
  display: flex;
  transition: transform 0.5s ease-in-out;
  height: 100%;
  width: calc(100% * 6); /* Accommodate all images */
}

.carousel-track img {
  height: 100%; /* Ensures images fill container height */
  width: auto;
  object-fit: cover;
  opacity: 0.7; /* Add slight transparency for layering effect */
  transition: opacity 0.3s ease-in-out; /* Smooth opacity transition */
}

.carousel-track img:nth-child(1) {
  opacity: 1; /* Make the first image fully opaque initially */
}

</style>
  <div class="carousel-container">
    <div class="carousel-track" id="carouselTrack">
    <img src="https://media.istockphoto.com/id/1213508052/photo/woman-pointing-at-blank-digital-tablet-screen-in-supermarket.jpg?s=2048x2048&w=is&k=20&c=50-nNc4xLVtwPbnUCCHs65yHmbmOYrDmk9qp4ap5_Gk=" alt="photo">
        <img src="https://media.istockphoto.com/id/1298938764/photo/online-shopping.jpg?s=2048x2048&w=is&k=20&c=vxCW1oZMp01Dijo5A3_5FI9FlLvb66ucA5qzvvBhcJs=" alt="photo">
        <img src="https://media.istockphoto.com/id/1226781783/photo/girl-shopping-on-online-store-from-home.jpg?s=2048x2048&w=is&k=20&c=H8yYzd12AYVmY0eY7CGd4jrUy69jZOZZGaiZ71btna4=" alt="photo">
        <img src="https://media.istockphoto.com/id/510572900/photo/distribution-warehouse-international-package-shipping-global-freight-transportation-concept.jpg?s=2048x2048&w=is&k=20&c=U7AyMt0MAMpoXChKb3lcMyvtv3cvxdAMpfpQa7SkQb0=" alt="photo">
        <img src="https://media.istockphoto.com/id/1186368533/photo/e-commerce-online-shopping-marketing-mobile-phone.jpg?s=2048x2048&w=is&k=20&c=1MLabJdRDNYC1g_u9Aj70Kvf0NJlYYCGuaOjOZZCwUQ=" alt="photo">
        <img src="https://media.istockphoto.com/id/1208411337/photo/consumer-reviews-concepts-with-bubble-people-review-comments-and-smartphone-rating-or.jpg?s=2048x2048&w=is&k=20&c=DhzOm4FX2gqjpS6uGZ8uu71c1Pnb8yTz__B6B3mYF3M=" alt="photo">
    </div>
  </div>

  <script>

    const carouselTrack = document.getElementById('carouselTrack');
const totalImages = carouselTrack.children.length; // Number of images
let currentIndex = 0;

function moveNext() {
  currentIndex = (currentIndex + 1) % totalImages;
  updateCarousel();
}

function updateCarousel() {
  const translateValue = -currentIndex * 100; // Translate by 100% per image
  carouselTrack.style.transform = `translateX(${translateValue}%)`;

  // Update image opacity for layering effect
  for (let i = 0; i < totalImages; i++) {
    const image = carouselTrack.children[i];
    image.style.opacity = i === currentIndex ? 1 : 0.7;
  }
}

setInterval(moveNext, 3000); // Adjust timing for desired speed (milliseconds)

  </script> </body>
</html>



