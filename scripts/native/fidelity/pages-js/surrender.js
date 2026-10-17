// Scroll effect
  window.addEventListener('scroll', () => {
    document.getElementById('site-nav').classList.toggle('scrolled', window.scrollY > 40);
  });
