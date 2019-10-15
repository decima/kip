// Get the modal
var modalFileBrowser = document.getElementById("fileBrowser");
var modalLinkBrowser = document.getElementById("linkBrowser");


// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modalFileBrowser) {
        modalFileBrowser.style.display = "none";
    }
    if (event.target == modalLinkBrowser) {
        modalLinkBrowser.style.display = "none";
    }
}

function save(content, callback) {
    let xhr = new XMLHttpRequest();
    xhr.open("PUT", saveDocument);
    xhr.send(content);
    xhr.onload = function () {
        callback(xhr.status < 300 && xhr.status > 199);
    };
}

// Create IE + others compatible event handler
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

// Listen to message from child window
eventer(messageEvent, function (e) {
    console.log('parent received message!:  ', e.data);
    result = JSON.parse(e.data);
    if (result.action == "media") {
        _replaceSelection(cm, stat.image, options.insertTexts.image, result.filename);
        modalFileBrowser.style.display = "none";
    }
    if (result.action == "link") {
        console.log(stat)
        _replaceSelection(cm, false, options.insertTexts.link, result.filename);
        modalLinkBrowser.style.display = "none";

    }
    console.log(result);
}, false);
var options, cm, stat;
var simplemde = new SimpleMDE({
    autosave: {
        enabled: true,
        uniqueId: rawPath,
        delay: 1000,
    },
    toolbar: [
        {
            name: "save",
            action: function (editor) {
                saveAction()
            },
            className: "fa fa-floppy-o",
            title: "Save"
        },
        {
            name: "quit",
            action: function (editor) {
                window.location.href = readLocation
            },
            className: "fa fa-times",
            title: "Close"
        },
        "bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link",
        {
            name: "link-internal",
            action: function (editor) {
                modalLinkBrowser.style.display = "block";
                options = editor.options;

                cm = editor.codemirror;
                stat = editor.getState(cm);
            },
            className: "fa fa-file-text",
            title: "Link to a page"
        },
        {
            name: "image",
            action: function (editor) {
                modalFileBrowser.style.display = "block";


                options = editor.options;

                cm = editor.codemirror;
                stat = editor.getState(cm);

            },
            className: "fa fa-image",
            title: "Upload Image"
        }
    ],
});
simplemde.codemirror.on("change", function () {
    console.log(simplemde.value());
});
setInterval(function () {
    var content = simplemde.value();
    save(content, function () {
    });
}, 10000);

function saveAction() {
    var content = simplemde.value();
    save(content, function () {
        alert("saved");
    });
}

const el = document.querySelector('textarea');


function _replaceSelection(cm, active, startEnd, url) {
    if (/editor-preview-active/.test(cm.getWrapperElement().lastChild.className))
        return;

    var text;
    var start = startEnd[0];
    var end = startEnd[1];
    var startPoint = cm.getCursor("start");
    var endPoint = cm.getCursor("end");
    if (url) {
        end = end.replace("#url#", url);
    }
    if (active) {
        text = cm.getLine(startPoint.line);
        start = text.slice(0, startPoint.ch);
        end = text.slice(startPoint.ch);
        cm.replaceRange(start + end, {
            line: startPoint.line,
            ch: 0
        });
    } else {
        text = cm.getSelection();
        cm.replaceSelection(start + text + end);

        startPoint.ch += start.length;
        if (startPoint !== endPoint) {
            endPoint.ch += start.length;
        }
    }
    cm.setSelection(startPoint, endPoint);
    cm.focus();
}