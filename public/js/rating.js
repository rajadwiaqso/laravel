const stars = document.querySelectorAll('.star');
const ratingValue = document.getElementById('rating-value');

stars.forEach(star => {
  star.addEventListener('click', () => {
    const value = parseInt(star.dataset.value);
    ratingValue.value = value;


    stars.forEach(s => {
      if (parseInt(s.dataset.value) <= value) {
        s.classList.add('active');
      } else {
        s.classList.remove('active');
      }
    });
  });
});