const canvas = document.getElementById("game");
const ctx = canvas.getContext("2d");
const resolution = 10;
const COLS = canvas.width / resolution;
const ROWS = canvas.height / resolution;
const fpsInput = document.getElementById("fps");
const fpsValueDisplay = document.getElementById("fpsValue");
let grid = buildGrid();
let spawnMode = 'cell'; // Default spawn mode
let fps = 30; // Default FPS
let lastFrameTime = 0;
const buttons = document.querySelectorAll('.button');

buttons.forEach(button => {
    button.addEventListener('click', () => {
        // If the clicked button is already active, remove the active class
        if (button.classList.contains('active')) {
            button.classList.remove('active');
            spawnMode = null; // Deselect all modes
        } else {
            // Remove active class from all buttons first
            buttons.forEach(btn => btn.classList.remove('active'));
            // Activate the clicked button
            button.classList.add('active');
            spawnMode = button.id;
        }
        // Handle random generation and reset actions
        if (button.id === 'random') {
            grid = buildGrid(true);
            render(grid);
        }
        if (button.id === 'reset') {
            grid = buildGrid();
            render(grid);
        }
    });
});

canvas.addEventListener('click', (event) => {
    if (!spawnMode) return; // If no mode is selected, do nothing
    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    const col = Math.floor(x / resolution);
    const row = Math.floor(y / resolution);
    if (spawnMode === 'cell') {
        grid[row][col] = 1; // Spawn a single cell
    } else if (spawnMode === 'glider') {
        spawnGlider(row, col); // Spawn a glider
    } else if (spawnMode === 'delete') {
        grid[row][col] = 0; // Delete the cell
    }
    render(grid);
});

fpsInput.addEventListener('input', () => {
    fps = parseInt(fpsInput.value);
    fpsValueDisplay.textContent = `${fps}`;
});

function buildGrid(randomize = false) {
    return new Array(ROWS).fill(null).map(() =>
        new Array(COLS).fill(0).map(() => (randomize ? Math.random() > 0.8 ? 1 : 0 : 0))
    );
}

function spawnGlider(row, col) {
    // Add a glider shape (rotated version)
    if (row + 2 < ROWS && col + 2 < COLS) {
        grid[row][col + 1] = 1;
        grid[row + 1][col + 2] = 1;
        grid[row + 2][col] = 1;
        grid[row + 2][col + 1] = 1;
        grid[row + 2][col + 2] = 1;
    }
}

function render(grid) {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    grid.forEach((row, y) => {
        row.forEach((cell, x) => {
            ctx.beginPath();
            ctx.rect(x * resolution, y * resolution, resolution, resolution);
            ctx.fillStyle = cell ? "#00ff00" : "#000000";
            ctx.fill();
            ctx.strokeStyle = "#111";
            ctx.stroke();
        });
    });
}

function nextGen(grid) {
    const next = grid.map(arr => [...arr]);
    for (let row = 0; row < grid.length; row++) {
        for (let col = 0; col < grid[row].length; col++) {
            const cell = grid[row][col];
            let numNeighbors = 0;
            for (let y = -1; y < 2; y++) {
                for (let x = -1; x < 2; x++) {
                    if (x === 0 && y === 0) continue;
                    // Toroidal wrapping
                    const newRow = (row + y + ROWS) % ROWS;
                    const newCol = (col + x + COLS) % COLS;
                    numNeighbors += grid[newRow][newCol];
                }
            }
            if (cell === 1 && (numNeighbors < 2 || numNeighbors > 3)) {
                next[row][col] = 0;
            } else if (cell === 0 && numNeighbors === 3) {
                next[row][col] = 1;
            }
        }
    }
    return next;
}

function update(currentTime) {
    const deltaTime = currentTime - lastFrameTime;
    if (deltaTime > 1000 / fps) {
        grid = nextGen(grid);
        render(grid);
        lastFrameTime = currentTime;
    }
    requestAnimationFrame(update);
}

render(grid); // Initial render
requestAnimationFrame(update);