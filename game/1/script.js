const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const gameOverMessage = document.getElementById('gameOver');
const restartButton = document.getElementById('restartButton');

const box = 20;
let snake = [];
snake[0] = { x: 9 * box, y: 10 * box };

let food = {
    x: Math.floor(Math.random() * 19 + 1) * box,
    y: Math.floor(Math.random() * 19 + 1) * box
};

let score = 0;
let d;
let gameOver = false;
let lastTime = 0;
const speed = 100; // Скорость змейки в миллисекундах

document.addEventListener('keydown', direction);
document.getElementById('up').addEventListener('click', () => direction({ keyCode: 87 })); // W
document.getElementById('left').addEventListener('click', () => direction({ keyCode: 65 })); // A
document.getElementById('right').addEventListener('click', () => direction({ keyCode: 68 })); // D
document.getElementById('down').addEventListener('click', () => direction({ keyCode: 83 })); // S
restartButton.addEventListener('click', restartGame);

function direction(event) {
    let key = event.keyCode;
    if (key == 65 && d != 'RIGHT') { // A
        d = 'LEFT';
    } else if (key == 87 && d != 'DOWN') { // W
        d = 'UP';
    } else if (key == 68 && d != 'LEFT') { // D
        d = 'RIGHT';
    } else if (key == 83 && d != 'UP') { // S
        d = 'DOWN';
    }
}

function collision(newHead, array) {
    for (let i = 0; i < array.length; i++) {
        if (newHead.x == array[i].x && newHead.y == array[i].y) {
            return true;
        }
    }
    return false;
}

function draw(time) {
    if (gameOver) return;

    if (time - lastTime < speed) {
        requestAnimationFrame(draw);
        return;
    }
    lastTime = time;

    ctx.clearRect(0, 0, canvas.width, canvas.height);

    for (let i = 0; i < snake.length; i++) {
        ctx.fillStyle = (i == 0) ? '#4caf50' : '#ffffff';
        ctx.fillRect(snake[i].x, snake[i].y, box, box);

        ctx.strokeStyle = '#ff4444';
        ctx.strokeRect(snake[i].x, snake[i].y, box, box);
    }

    ctx.fillStyle = '#ff4444';
    ctx.fillRect(food.x, food.y, box, box);

    let snakeX = snake[0].x;
    let snakeY = snake[0].y;

    if (d == 'LEFT') snakeX -= box;
    if (d == 'UP') snakeY -= box;
    if (d == 'RIGHT') snakeX += box;
    if (d == 'DOWN') snakeY += box;

    if (snakeX == food.x && snakeY == food.y) {
        score++;
        food = {
            x: Math.floor(Math.random() * 19 + 1) * box,
            y: Math.floor(Math.random() * 19 + 1) * box
        };
    } else {
        snake.pop();
    }

    let newHead = {
        x: snakeX,
        y: snakeY
    };

    if (snakeX < 0 || snakeY < 0 || snakeX >= canvas.width || snakeY >= canvas.height || collision(newHead, snake)) {
        gameOver = true;
        gameOverMessage.classList.remove('hidden');
        return;
    }

    snake.unshift(newHead);

    ctx.fillStyle = '#ffffff';
    ctx.font = '45px Arial';
    ctx.fillText(score, 2 * box, 1.6 * box);

    requestAnimationFrame(draw);
}

function restartGame() {
    snake = [];
    snake[0] = { x: 9 * box, y: 10 * box };
    food = {
        x: Math.floor(Math.random() * 19 + 1) * box,
        y: Math.floor(Math.random() * 19 + 1) * box
    };
    score = 0;
    d = undefined;
    gameOver = false;
    gameOverMessage.classList.add('hidden');
    requestAnimationFrame(draw);
}

requestAnimationFrame(draw);