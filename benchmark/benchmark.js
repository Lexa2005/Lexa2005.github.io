document.getElementById('runBenchmark').addEventListener('click', async function() {
    const resultsElement = document.getElementById('results');
    resultsElement.innerHTML = 'Запуск тестов...';

    let totalScore = 0; // Общий балл производительности
    let testResults = []; // Массив для хранения средних результатов каждого теста

    // Функция для вывода результатов
    function logResult(testName, time, weight = 1) {
        const result = document.createElement('div');
        result.className = 'benchmark-result';

        // Проверка на undefined или некорректные значения
        if (typeof time !== 'number' || isNaN(time)) {
            result.innerHTML = `<strong>${testName}:</strong> Ошибка выполнения теста`;
            resultsElement.appendChild(result);
            return;
        }

        result.innerHTML = `<strong>${testName}:</strong> ${time.toFixed(2)} мс`;
        resultsElement.appendChild(result);

        // Добавляем баллы в общий результат (чем меньше время, тем выше балл)
        const score = (1000 / time) * weight;
        totalScore += score;
        testResults.push({ name: testName, time, score });
    }

    // Функция для запуска теста несколько раз и вычисления среднего значения
    async function runTestMultipleTimes(testFunction, runs = 5) {
        let totalTime = 0;
        for (let i = 0; i < runs; i++) {
            const time = await testFunction();
            if (typeof time === 'number' && !isNaN(time)) {
                totalTime += time;
            }
        }
        return totalTime / runs;
    }

    // Тест 1: Вычисления (CPU)
    function runCPUTest() {
        const startTime = performance.now();
        let sum = 0;
        for (let i = 0; i < 10000000; i++) {
            sum += Math.sqrt(i) * Math.sin(i);
        }
        const endTime = performance.now();
        return endTime - startTime;
    }

    // Тест 2: Операции с DOM
    function runDOMTest() {
        const startTime = performance.now();
        const container = document.createElement('div');
        for (let i = 0; i < 1000; i++) {
            const element = document.createElement('div');
            element.textContent = `Элемент ${i}`;
            container.appendChild(element);
        }
        document.body.appendChild(container);
        document.body.removeChild(container);
        const endTime = performance.now();
        return endTime - startTime;
    }

    // Тест 3: Графика (Canvas)
    function runCanvasTest() {
        const canvas = document.getElementById('canvasTest');
        const ctx = canvas.getContext('2d');
        const startTime = performance.now();
        for (let i = 0; i < 1000; i++) {
            ctx.fillStyle = `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 1)`;
            ctx.fillRect(Math.random() * 500, Math.random() * 500, 50, 50);
        }
        const endTime = performance.now();
        return endTime - startTime;
    }

    // Тест 4: Анимации (JS)
    function runAnimationTest() {
        return new Promise((resolve) => {
            const startTime = performance.now();
            const element = document.createElement('div');
            element.style.width = '100px';
            element.style.height = '100px';
            element.style.backgroundColor = 'red';
            element.style.position = 'absolute';
            document.body.appendChild(element);

            let pos = 0;
            function animate() {
                pos += 1;
                element.style.left = `${pos}px`;
                if (pos < 300) {
                    requestAnimationFrame(animate);
                } else {
                    const endTime = performance.now();
                    document.body.removeChild(element);
                    resolve(endTime - startTime);
                }
            }
            animate();
        });
    }

    // Тест 5: Операции с памятью
    function runMemoryTest() {
        const startTime = performance.now();
        const array = new Array(1000000);
        for (let i = 0; i < array.length; i++) {
            array[i] = { index: i, value: Math.random() };
        }
        const sortedArray = array.sort((a, b) => b.value - a.value);
        const endTime = performance.now();
        return endTime - startTime;
    }

    // Тест 6: Сетевые запросы
    async function runNetworkTest() {
        const startTime = performance.now();
        try {
            const response = await fetch('https://jsonplaceholder.typicode.com/posts');
            const data = await response.json();
            const endTime = performance.now();
            return endTime - startTime;
        } catch (error) {
            console.error('Ошибка сетевого запроса:', error);
            return undefined; // Возвращаем undefined в случае ошибки
        }
    }

    // Тест 7: Работа с большими массивами данных
    function runLargeArrayTest() {
        const startTime = performance.now();
        const array = new Array(1000000).fill().map(() => Math.random());
        const filteredArray = array.filter(x => x > 0.5);
        const endTime = performance.now();
        return endTime - startTime;
    }

    // Тест 8: WebGL (графика)
    function runWebGLTest() {
        const canvas = document.createElement('canvas');
        canvas.width = 500;
        canvas.height = 500;
        document.body.appendChild(canvas);
        const gl = canvas.getContext('webgl');

        if (!gl) {
            console.error('WebGL не поддерживается');
            document.body.removeChild(canvas);
            return undefined;
        }

        const startTime = performance.now();

        // Простой шейдер для теста
        const vertexShaderSource = `
            attribute vec4 a_position;
            void main() {
                gl_Position = a_position;
            }
        `;
        const fragmentShaderSource = `
            void main() {
                gl_FragColor = vec4(1.0, 0.0, 0.0, 1.0);
            }
        `;

        const vertexShader = gl.createShader(gl.VERTEX_SHADER);
        gl.shaderSource(vertexShader, vertexShaderSource);
        gl.compileShader(vertexShader);

        const fragmentShader = gl.createShader(gl.FRAGMENT_SHADER);
        gl.shaderSource(fragmentShader, fragmentShaderSource);
        gl.compileShader(fragmentShader);

        const program = gl.createProgram();
        gl.attachShader(program, vertexShader);
        gl.attachShader(program, fragmentShader);
        gl.linkProgram(program);
        gl.useProgram(program);

        const positionBuffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer);
        const positions = [
            0, 0,
            0, 0.5,
            0.7, 0,
        ];
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(positions), gl.STATIC_DRAW);

        const positionAttributeLocation = gl.getAttribLocation(program, 'a_position');
        gl.enableVertexAttribArray(positionAttributeLocation);
        gl.vertexAttribPointer(positionAttributeLocation, 2, gl.FLOAT, false, 0, 0);

        gl.clearColor(0, 0, 0, 1);
        gl.clear(gl.COLOR_BUFFER_BIT);
        gl.drawArrays(gl.TRIANGLES, 0, 3);

        const endTime = performance.now();
        document.body.removeChild(canvas);
        return endTime - startTime;
    }

    // Тест 9: Работа с аудио
    async function runAudioTest() {
        const startTime = performance.now();
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(440, audioContext.currentTime);
        oscillator.connect(audioContext.destination);
        oscillator.start();
        oscillator.stop(audioContext.currentTime + 1); // 1 секунда звука
        await new Promise((resolve) => {
            oscillator.onended = resolve;
        });
        const endTime = performance.now();
        return endTime - startTime;
    }

    // Тест 10: Работа с Web Workers
    function runWebWorkerTest() {
        return new Promise((resolve) => {
            const startTime = performance.now();
            const worker = new Worker('worker.js');
            worker.postMessage('start');
            worker.onmessage = () => {
                const endTime = performance.now();
                resolve(endTime - startTime);
            };
        });
    }

    // Запуск всех тестов несколько раз и вычисление среднего значения
    const cpuTime = await runTestMultipleTimes(runCPUTest);
    logResult('Вычисления (CPU)', cpuTime, 1.5);

    const domTime = await runTestMultipleTimes(runDOMTest);
    logResult('Операции с DOM', domTime, 1.2);

    const canvasTime = await runTestMultipleTimes(runCanvasTest);
    logResult('Графика (Canvas)', canvasTime, 1.3);

    const animationTime = await runTestMultipleTimes(runAnimationTest);
    logResult('Анимации (JS)', animationTime, 1.1);

    const memoryTime = await runTestMultipleTimes(runMemoryTest);
    logResult('Операции с памятью', memoryTime, 1.4);

    const networkTime = await runTestMultipleTimes(runNetworkTest);
    logResult('Сетевые запросы', networkTime, 1.0);

    const largeArrayTime = await runTestMultipleTimes(runLargeArrayTest);
    logResult('Работа с большими массивами', largeArrayTime, 1.2);

    const webglTime = await runTestMultipleTimes(runWebGLTest);
    logResult('WebGL (графика)', webglTime, 1.5);

    const audioTime = await runTestMultipleTimes(runAudioTest);
    logResult('Работа с аудио', audioTime, 1.3);

    const webWorkerTime = await runTestMultipleTimes(runWebWorkerTest);
    logResult('Web Workers (многопоточность)', webWorkerTime, 1.4);

    // Вычисление общего среднего значения
    const averageTime = testResults.reduce((sum, test) => sum + test.time, 0) / testResults.length;
    const averageScore = testResults.reduce((sum, test) => sum + test.score, 0) / testResults.length;

    // Вывод общего результата
    const totalResult = document.createElement('div');
    totalResult.className = 'benchmark-result total-score';
    totalResult.innerHTML = `
        <strong>Среднее время выполнения:</strong> ${averageTime.toFixed(2)} мс<br>
        <strong>Общий результат:</strong> ${averageScore.toFixed(2)} баллов
    `;
    resultsElement.appendChild(totalResult);
});