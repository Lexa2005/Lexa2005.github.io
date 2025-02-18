self.onmessage = function() {
    let sum = 0;
    for (let i = 0; i < 10000000; i++) {
        sum += Math.sqrt(i) * Math.sin(i);
    }
    self.postMessage('done');
};