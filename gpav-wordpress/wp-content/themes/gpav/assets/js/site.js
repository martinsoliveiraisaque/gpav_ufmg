(() => {
  const img = document.getElementById("banner-img");
  const frame = document.getElementById("banner-frame");
  if (!img || !frame) return;

  const slides = window.GPAV_SLIDES || [];
  if (!slides.length) return;

  let i = 0;
  let playing = true;

  const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const setSlide = (idx) => {
    i = (idx + slides.length) % slides.length;
    img.src = slides[i].src;
    img.alt = slides[i].alt;
  };

  const next = () => setSlide(i + 1);

  const updateAria = () => {
    frame.setAttribute(
      "aria-label",
      playing ? (document.documentElement.lang.startsWith("pt") ? "Pausar apresentação" : "Pause slideshow") : (document.documentElement.lang.startsWith("pt") ? "Reproduzir apresentação" : "Play slideshow")
    );
    frame.classList.toggle("is-paused", !playing);
  };

  // Toggle play/pause on click
  const toggle = () => {
    playing = !playing;
    updateAria();
  };

  frame.addEventListener("click", toggle);

  // Keyboard access: Enter/Space toggles
  frame.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault();
      toggle();
    }
  });

  updateAria();

  if (!prefersReduced) {
    setInterval(() => {
      if (playing) next();
    }, 7000);
  }
})();
