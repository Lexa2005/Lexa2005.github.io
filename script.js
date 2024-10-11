// Функция для создания психоделических элементов
function createPsychedelicElements() {
    const container = document.querySelector('.container');
    const numElements = 20;

    for (let i = 0; i < numElements; i++) {
        const element = document.createElement('div');
        element.classList.add('psychedelic-element');
        element.style.top = `${Math.random() * 100}%`;
        element.style.left = `${Math.random() * 100}%`;
        element.style.animationDuration = `${Math.random() * 10 + 5}s`;
        container.appendChild(element);
    }
}

// Запуск создания психоделических элементов при загрузке страницы
window.onload = createPsychedelicElements;