const cTimeLeft = 60;

var arrShapeTypes = ["circle", "square"];
var iPlayHeight;
var iPlayWidth;
var iTimeLeft = cTimeLeft;
var intTimer;
var intClkCircle;
var intClkNonCircle;
var intTotalReactTime;

window.onload = onLoad;
window.addEventListener("resize", resize);

function onLoad() {
    resize();
    document.getElementById("spTimeLeft").innerHTML = iTimeLeft;
}

function resize() {
    // Set the height of the play area
    var iDocHeight = window.innerHeight;
    var iOtherHeights = document.getElementById("divHeader").offsetHeight + document.getElementById("divInfo").offsetHeight;
    iPlayHeight = ((iDocHeight - iOtherHeights) - 10);
    iPlayWidth = document.getElementById("divHeader").offsetWidth;

    document.getElementById("divPlayArea").style.height = iPlayHeight + 'px';
}

function newShape() {
    var iNewSize = Math.floor((Math.random() * ((document.getElementById("divPlayArea").offsetWidth - 10) / 5)) + 20);
    var oNewShape = document.createElement("div");
    var iMaxTop = iPlayHeight - iNewSize;
    var iMaxLeft = iPlayWidth - iNewSize - 16;
   
    oNewShape.className = arrShapeTypes[Math.floor(Math.random() * 2)];
    oNewShape.style.width = iNewSize + 'px';
    oNewShape.style.height = iNewSize + 'px';
    oNewShape.style.backgroundColor = "rgb("+Math.floor(Math.random() * 255)+", "+Math.floor(Math.random() * 255)+", "+Math.floor(Math.random() * 255)+")";
    oNewShape.style.border = "1px solid black";
    oNewShape.style.display = "block";
    oNewShape.style.position = "absolute";
    oNewShape.style.top = Math.floor(Math.random() * iMaxTop) + 'px';
    oNewShape.style.left = Math.floor(Math.random() * iMaxLeft) + 'px';
    oNewShape.setAttribute("createTime", Date.now());
    document.getElementById("divPlayArea").appendChild(oNewShape);

    oNewShape.onclick = clickShape;
    setTimeout(deleteShape.bind(null, oNewShape), Math.floor(Math.random() * 4000) + 2000);

    if (iTimeLeft > 1) setTimeout(newShape, Math.floor(Math.random() * 1500))
}

function clickShape() {
    var srcElement = event.target;
    var iAvReactTime;

    if (srcElement.className == "circle") {
        intClkCircle++
        document.getElementById("spClkCircle").innerHTML = intClkCircle;
        intTotalReactTime += (Date.now() - srcElement.getAttribute("createTime"));
        iAvReactTime = intTotalReactTime / intClkCircle;
        document.getElementById("spAvReact").innerHTML = (iAvReactTime / 1000).toFixed(5);
        document.getElementById("spAvReact").style.color = "black";
    }
    else {
        intClkNonCircle++
        document.getElementById("spClkNonCircle").innerHTML = intClkNonCircle;
        intTotalReactTime += 5000;
        iAvReactTime = intTotalReactTime / intClkCircle;
        document.getElementById("spAvReact").innerHTML = (iAvReactTime / 1000).toFixed(5);
        document.getElementById("spAvReact").style.color = "red";
    }

    srcElement.style.display = "none";
}

function deleteShape(oDeleteShape) {
    if (oDeleteShape.className == "circle" && oDeleteShape.style.display != "none") intTotalReactTime += 5000;
    document.getElementById("divPlayArea").removeChild(oDeleteShape);
}

function clkStart() {
    iTimeLeft = cTimeLeft;
    intClkCircle = 0;
    intClkNonCircle = 0;
    intTotalReactTime = 0;
    document.getElementById("spClkCircle").innerHTML = intClkCircle;
    document.getElementById("spClkNonCircle").innerHTML = intClkNonCircle;
    document.getElementById("spTimeLeft").innerHTML = iTimeLeft;
    document.getElementById("spAvReact").innerHTML = "";

    intTimer = setInterval(countdown, 1000);
    document.getElementById("btnStart").style.display = "none";

    setTimeout(newShape, Math.floor(Math.random() * 1000))
    
}

function countdown() {
    iTimeLeft--;

    if (iTimeLeft >= 0) {
        document.getElementById("spTimeLeft").innerHTML = iTimeLeft;
    }
    else {
        clearInterval(intTimer);

        var arrChildren = document.getElementById("divPlayArea").children;
        for (var i = 0; i < arrChildren.length; i++) {
            if (arrChildren[i] != null) arrChildren[i].style.display = "none";
        }

        document.getElementById("btnStart").value = "Restart";
        document.getElementById("btnStart").style.display = "block";
    }
}