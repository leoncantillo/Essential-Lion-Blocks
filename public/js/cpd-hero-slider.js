document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.cpd-hero-slide');
    if (slides.length <= 1) return;

    let index = 0;
    slides[index].classList.add('is-active');

    setInterval(() => {
        slides[index].classList.remove('is-active');
        index = (index + 1) % slides.length;
        slides[index].classList.add('is-active');
    }, 5000);
});
