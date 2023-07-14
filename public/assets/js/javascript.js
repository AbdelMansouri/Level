const navbar = document.querySelector("#navbar");
let scrollPosition = window.pageYOffset;

window.addEventListener("scroll", () => {
  const currentPosition = window.pageYOffset;

  if (currentPosition > scrollPosition) {
    if (currentPosition > navbar.offsetHeight) {
      navbar.style.transform = "translateY(-100%)";
      navbar.style.transition = "transform 1s"; // Durée de transition de 0,5 seconde
    }
  } else {
    navbar.style.transform = "translateY(0)";
    navbar.style.transition = "transform 1s"; // Durée de transition de 0,5 seconde
  }

  scrollPosition = currentPosition;
});




