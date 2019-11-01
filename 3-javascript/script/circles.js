var iScore = 0;
var iPrevScore = 0;
var iTime = 60;
var currCircle;
var iInt;

document.addEventListener("DOMContentLoaded", onLoad);
window.addEventListener("resize", drawCircles);

function drawCircles() {
    var setSize;
    var subHeights = document.getElementById("header").offsetHeight + document.getElementById("info").offsetHeight;
    var availHeight = window.innerHeight - subHeights;
    var availWidth = document.body.clientWidth;

    // Work out our heights and widths for the circles
    if (availWidth > availHeight)
        setSize = availHeight / 3;
    else
        setSize = availWidth / 3

    //Subtract for padding
    setSize = setSize - 40;

    // Set the size of each circle
    for (let divCircle of document.getElementsByClassName("circle")) {
        divCircle.style.height = setSize + 'px';
        divCircle.style.width = setSize + 'px';
    }

    // Make the main containing div the right size
    document.getElementById("circles").style.width = ((setSize * 3) + 50) + 'px';
}

function onLoad() {

    drawCircles();

    // Set up the click event handlers
    for (let divCircle of document.getElementsByClassName("circle")) {
        divCircle.onclick = clickCircle;
    }

    restart();
}

function restart() {

    // Reset the timer and set all circles to black
    if (iInt != null) clearInterval(iInt);
    if (currCircle != null) currCircle.style.background = "black";

    // Pick a new starting circle and set it to red
    currCircle = document.getElementById("circle" + (Math.floor(Math.random() * (9 - 1)) + 1));
    currCircle.style.background = "red";

    // Set the score and timer info back to the start
    iTime = 60;
    iScore = 0;

    document.getElementById("score").innerHTML = iScore;
    document.getElementById("time").innerHTML = iTime;
}

function clickCircle() {

    if (event.target.className == "circle" && event.target.style.background == "red") {
        // Play the 'button press' animation by removing it, reflowing and then readding the animation.
        event.target.style.animation = "";
        void event.target.offsetWidth;
        event.target.style.animation = "pulse 0.1s";

        // Change the colour back to black
        event.target.style.background = "black";
 
        // Pick a new circle and set it to red
        currCircle = document.getElementById("circle" + (Math.floor(Math.random() * (9 - 1)) + 1));
        currCircle.style.background = "red";

        // If that was the first click, then start the timer!
        if (iScore == 0) {
            iInt = setInterval(countdown, 1000);
        }

        // Increase the score.
        iScore += 1;
        document.getElementById("score").innerHTML = iScore;
        event.cancelBubble;
    }
    else
        event.cancelBubble;
}

function countdown() {
    iTime -= 1;

    // If the score hasn't changed this second, then flash the circle.
    if (iScore == iPrevScore) {
        currCircle.style.animation = "";
        void currCircle.offsetWidth;
        currCircle.style.animation = "pulse 0.1s linear 0s 3";
    }

    // When the timer gets to zero, set all of the circles to black and change the timer text.
    if (iTime == 0) {
        currCircle.style.background = "black";
        document.getElementById("time").innerHTML = "TIME UP!";
        clearInterval(iInt);
    }
    else {
        // Decrease the timer by one
        document.getElementById("time").innerHTML = iTime;
        iPrevScore = iScore;
    }
}