function handleAnimations() {
    let animations = [
        ["bounce", "bounce"],
        ["flash", "flash"],
        ["pulse", "pulse"],
        ["shake", "shake"],
        ["headShake", "headShake"],
        ["swing", "swing"],
        ["tada", "tada"],
        ["wobble", "wobble"],
        ["jello", "jello"],
        ["heartBeat", "heartBeat"],
        ["bounceIn", "bounceIn"],
        ["bounceInDown", "bounceInUp"],
        ["bounceInLeft", "bounceInRight"],
        ["fadeIn", "fadeIn"],
        ["fadeInDown", "fadeInUp"],
        ["fadeInLeft", "fadeInRight"],
        ["flip", "flip"],
        ["flipInX", "flipInX"],
        ["flipInY", "flipInY"],
        ["rotateIn", "rotateIn"],
        ["rotateInDownLeft", "rotateInDownRight"],
        ["slideInDown", "slideInUp"],
        ["slideInLeft", "slideInRight"],
        ["zoomIn", "zoomIn"],
        ["zoomInDown", "zoomInUp"],
        ["zoomInLeft", "zoomInRight"],
        ["jackInTheBox", "jackInTheBox"]
    ];
    var num = getRandomInt(animations.length);
    animateCSS('[name="top"]', animations[num][0], animationOver);
    animateCSS('[name="bottom"]', animations[num][1], animationOver);
    console.log("top: " + animations[num][0]);
    console.log("bottom: " + animations[num][1]);
}

function addStuff() {
    document.getElementById("main").innerHTML =
    `<h1 name="top" id="status_text" class="header animated"><a href="https://rb.gy/l6u5za">Lexa2005</a></h1>
    <p name="bottom" id="text2" class="textiboi animated">Ordinary guy</p>`;
    handleAnimations();
}

function animateCSS(element, animationName, callback) {
    const node = document.querySelector(element);
    node.classList.add('animated', animationName);
    function handleAnimationEnd() {
        node.classList.remove('animated', animationName);
        node.removeEventListener('animationend', handleAnimationEnd);
        if (typeof callback === 'function') callback();
    }
    node.addEventListener('animationend', handleAnimationEnd);
}

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

window.addEventListener("DOMContentLoaded", () => {
    document.getElementById("status_text").innerHTML = "load...";
});

window.addEventListener("load", () => {
    setTimeout(() => {
        document.getElementById("status_text").innerHTML = "loading...";
        document.getElementById("status_text").classList.add("begin");
        addStuff();
    }, 100);
});

var temp = 0;
function animationOver() {
    temp++;
    if (temp !== 2) return;
    setTimeout(function () {
        console.log("animations over");
        var doc = document.getElementById("main");
        doc.innerHTML +=
        `<div class="textiboi"><a id="typElement"></a></div>
        <div class="textiboi">
        <a class="textiboi animated fadeIn" href="https://github.com/Lexa2005">[github]</a>
        <a class="textiboi animated fadeIn" href="./game/2/gamelife.html">[Game life]</a>
        <a class="textiboi animated fadeIn" href="./game/1/snake.html">[Snake]</a>
        <a class="textiboi animated fadeIn" href="./benchmark/index.html">[Benchmark]</a>
        <a class="textiboi animated fadeIn" href="./cube/index.html">[Cube]</a>
        <a class="textiboi animated fadeIn" href="./RGB/index.html">[RGB]</a>`;
        console.log("running typewrite");
        indefiniteWrite();
    }, 550);
}

function indefiniteWrite() {
    let strs = [
        "I will calculate your ip",
        "Why?",
        "Hi visitor",
        "Wow!",
        "This sentence is a lie",
        "Pie is a lie",
        "Kaliningrad GMT+2."
    ];
    var num = getRandomInt(strs.length);
    console.log("writing: " + strs[num]);
    var typElement = document.getElementById('typElement');
    var typewriter = new Typewriter(typElement, { loop: true });
    typewriter.typeString(strs[num]).pauseFor(5000).deleteAll().pauseFor(100).callFunction(() => {
        typewriter.stop();
        indefiniteWrite();
    }).start();
}