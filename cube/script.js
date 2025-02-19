let scene = document.querySelector('.scene');
let cube = document.querySelector('.cube');
let isDragging = false;
let startX, startY;
let rotateX = 0, rotateY = 0;

// Элементы управления
const colorPicker = document.getElementById('colorPicker');
const shadowToggle = document.getElementById('shadowToggle');
const rotationSpeed = document.getElementById('rotationSpeed');
const opacity = document.getElementById('opacity');

// Остановка анимации
function stopAnimation() {
    scene.classList.add('no-animation');
}

// Вращение с помощью мыши
scene.addEventListener('mousedown', (e) => {
    isDragging = true;
    startX = e.clientX;
    startY = e.clientY;
    stopAnimation();
});

scene.addEventListener('mousemove', (e) => {
    if (isDragging) {
        let deltaX = e.clientX - startX;
        let deltaY = e.clientY - startY;

        rotateY += deltaX * 0.5;
        rotateX -= deltaY * 0.5;

        updateCubeRotation();

        startX = e.clientX;
        startY = e.clientY;
    }
});

scene.addEventListener('mouseup', () => {
    isDragging = false;
});

scene.addEventListener('mouseleave', () => {
    isDragging = false;
});

// Вращение с помощью клавиатуры
document.addEventListener('keydown', (e) => {
    const rotationStep = 5;

    switch (e.key) {
        case 'ArrowUp':
            rotateX -= rotationStep;
            break;
        case 'ArrowDown':
            rotateX += rotationStep;
            break;
        case 'ArrowLeft':
            rotateY -= rotationStep;
            break;
        case 'ArrowRight':
            rotateY += rotationStep;
            break;
    }

    stopAnimation();
    updateCubeRotation();
});

// Обновление вращения куба
function updateCubeRotation() {
    cube.style.transform = `rotateY(${rotateY}deg) rotateX(${rotateX}deg)`;
}

// Настройки
colorPicker.addEventListener('input', () => {
    const color = colorPicker.value;
    document.querySelectorAll('.face').forEach(face => {
        face.style.background = color;
    });
});

shadowToggle.addEventListener('change', () => {
    const faces = document.querySelectorAll('.face');
    if (shadowToggle.checked) {
        faces.forEach(face => {
            face.style.boxShadow = '0 0 20px rgba(0, 0, 0, 0.5)';
        });
    } else {
        faces.forEach(face => {
            face.style.boxShadow = 'none';
        });
    }
});

rotationSpeed.addEventListener('input', () => {
    const speed = rotationSpeed.value;
    scene.style.animationDuration = `${10 - speed}s`;
});

opacity.addEventListener('input', () => {
    const opacityValue = opacity.value / 100;
    document.querySelectorAll('.face').forEach(face => {
        face.style.opacity = opacityValue;
    });
});