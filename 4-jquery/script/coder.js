var bHtml = false;
var bCss = false;
var bJS = false;
var bOutput = false;
var intWinOpen = 0;
var intStartSize = null;

$(function () {
    $("#btnHtml, #btnCss, #btnJS, #btnOutput").button();
    $(".controlgroup").controlgroup();
    $("#btnHtml, #btnCss, #btnJS, #btnOutput").click(function (event, ui) {
        //event.preventDefault();
        
        switch (event.target.id) {
            case "btnHtml":
                if (bHtml) {
                    $("#txtHtml").parent().hide();
                    $(this).removeClass("selected");
                    intWinOpen--;
                    bHtml = false;
                }
                else {
                    $("#txtHtml").parent().show();
                    $(this).addClass("selected");
                    intWinOpen++;
                    bHtml = true;
                }
                break;
            case "btnCss":
                if (bCss) {
                    $("#txtCss").parent().hide();
                    $(this).removeClass("selected");
                    intWinOpen--;
                    bCss = false;
                }
                else {
                    $("#txtCss").parent().show();
                    $(this).addClass("selected");
                    intWinOpen++;
                    bCss = true;
                }
                break;
            case "btnJS":
                if (bJS) {
                    $("#txtJS").parent().hide();
                    $(this).removeClass("selected");
                    intWinOpen--;
                    bJS = false;
                }
                else {
                    $("#txtJS").parent().show();
                    $(this).addClass("selected");
                    intWinOpen++;
                    bJS = true;
                }
                break;
            case "btnOutput":
                if (bOutput) {
                    $("#ifOutput").hide();
                    $(this).removeClass("selected");
                    intWinOpen--;
                    bOutput = false;
                }
                else {
                    $("#ifOutput").show();
                    $(this).addClass("selected");
                    intWinOpen++;
                    bOutput = true;
                }
                break;
        }

        resetWins();
    });

    $("#btnHtml").addClass("selected");
    intWinOpen++;
    bHtml = true;

    $("#btnOutput").addClass("selected");
    intWinOpen++;
    bOutput = true;

    $("#txtHtml, #txtCss, #txtJS").resizable(
        {
            handles: "e",
            containment: "parent",
            resize: function (event, ui) { winResize(event, ui); },
            start: function (event, ui) { intStartSize = ui.originalSize.width + ui.element.nextAll(":visible:first").width(); },
            stop: function (event, ui) { intStartSize = null; }
        });

    resetWins();

    $("#txtCss").parent().hide();
    $("#txtJS").parent().hide();

    window.addEventListener("resize", resetWins);
});

function resetWins() {
    $("#container").css("height", document.body.offsetHeight - 60 + 'px');
    $("#ifOutput").css("width", (document.body.clientWidth / intWinOpen - 6) + 'px');
    $("#ifOutput").css("height", document.body.offsetHeight - 62 + 'px');
    $("#txtHtml, #txtCss, #txtJS").parent().css("width", (document.body.clientWidth / intWinOpen - 6) + 'px');
    $("#txtHtml, #txtCss, #txtJS").parent().css("height", document.body.offsetHeight - 62 + 'px');
    $("#txtHtml, #txtCss, #txtJS").css("width", (document.body.clientWidth / intWinOpen - 6) + 'px');
    $("#txtHtml, #txtCss, #txtJS").css("height", document.body.offsetHeight - 62 + 'px');
    
}

function winResize(event, ui) {
    var intRemWidth = intStartSize - ui.size.width;
    $objResize = $(ui.element.nextAll(":visible:first"))

    ui.size.height = document.body.offsetHeight - 62;

    $objResize.css("width", intRemWidth + 'px');
    if ($objResize.hasClass("ui-wrapper")) {
        $(ui.element.nextAll(":visible:first")).children("textarea").css("width", intRemWidth + 'px');
    }

    //$("#txtHtml").text('width: ' + intRemWidth + 'px\nleft: ' + ui.size.width + 'px')
}

function updateOutput() {
    var sHTML = $("#txtHtml").val();

    //$("#ifOutput").contents().find("html").html("<head><style type='text/css'>" + $("#txtCss").val() + "</style></head><body>" + $("#txtHtml").val() + "</body>");
    $("#ifOutput").contents().find("html").html($("#txtHtml").val());
    $("#ifOutput").contents().find("html").find("head").html("<style type='text/css'>" + $("#txtCss").val() + "</style>");


    //alert($("#ifOutput").contents().find("html").html())
    document.getElementById("ifOutput").contentWindow.eval($("#txtJS").val());
}