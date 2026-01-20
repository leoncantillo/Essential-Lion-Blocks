document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.cpd-hero-slide');
    if (slides.length <= 1) return;

    let index = 0;

    setInterval(() => {
        slides[index].style.display = 'none';
        index = (index + 1) % slides.length;
        slides[index].style.display = 'block';
    }, 5000);
});