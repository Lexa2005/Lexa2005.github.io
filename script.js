document.addEventListener('DOMContentLoaded', () => {
    const card = document.querySelector('.card');
    setTimeout(() => {
        card.classList.add('visible');
    }, 500);
});