// Инициализация Three.js
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer();
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);

// Добавление чайника Ньюэлла
const geometry = new THREE.TeapotBufferGeometry(0.5);
const material = new THREE.MeshStandardMaterial({ color: 0xffffff });
const teapot = new THREE.Mesh(geometry, material);
scene.add(teapot);

// Настройка освещения
const light = new THREE.DirectionalLight(0xffffff, 1);
light.position.set(5, 5, 5).normalize();
scene.add(light);

// Настройка камеры
camera.position.z = 5;

// Анимация
function animate() {
  requestAnimationFrame(animate);
  teapot.rotation.x += 0.01;
  teapot.rotation.y += 0.01;
  renderer.render(scene, camera);
}
animate();

// Обработка событий для управления
const colorPicker = document.getElementById('colorPicker');
const shadowToggle = document.getElementById('shadowToggle');
const rotationSpeed = document.getElementById('rotationSpeed');
const opacity = document.getElementById('opacity');

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