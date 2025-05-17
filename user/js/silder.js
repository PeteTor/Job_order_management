let currentIndex = 0;
const slides = document.querySelectorAll(".slide");
const slideContainer = document.querySelector(".slides");
const total = slides.length;

function showSlide(index) {
  if (index >= total) currentIndex = 0;
  else if (index < 0) currentIndex = total - 1;
  else currentIndex = index;

  const offset = -currentIndex * 100;
  slideContainer.style.transform = `translateX(${offset}%)`;
}

function moveSlide(step) {
  showSlide(currentIndex + step);
}

// Auto-slide every 5 seconds
setInterval(() => {
  moveSlide(1);
}, 5000);

// Initial display
showSlide(currentIndex);
