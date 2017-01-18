function sendUrlEmail(link, title, email, callback, errorCallback) {
    var req = new XMLHttpRequest();
    var url = "https://sharetoself.com/email/send";
    var params = "link=" + link + "&title=" + title + "&email=" + email
        + "&id=" + chrome.runtime.id;

    req.onreadystatechange = function () {
        try {
            if (req.readyState == 4) {
                if (req.status == 200) {
                    clearServerError();
                    if (req.responseText === 'true') {
                        callback("Sent successfully!");
                    } else if (req.responseText === 'IDENTIFY') {
                        errorCallback('Error: Identify yourself in options!');
                    } else if (req.responseText === 'VERIFY') {
                        errorCallback('Error: Verify your email!');
                    } else if (req.responseText === 'LIMIT') {
                        errorCallback('Error: Sorry but you are sending emails to fast! Keep calm and try again');
                    } else if (req.responseText === 'BAN') {
                        errorCallback('Error: Sorry but you are banned! Keep calm and try again after 24 hours');
                    } else {
                        setServerError(req.responseText);
                        errorCallback('Error: Connect with developer!');
                    }
                } else {
                    setServerError(req.status + ' ' + req.responseText);
                    errorCallback('Error: Try again later or connect with developer.');
                }
            }
        }
        catch (e) {
            errorCallback('Error, check your connection!');
        }
    };

    req.open("POST", url, true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(params);

}

function sendUrlFB(link, title, type, fb, callback, errorCallback) {
    var req = new XMLHttpRequest();
    var url = "https://sharetoself.com/fb/send";
    // var url = "https://sharetoself.com/chromeExt.php";
    var params = "link=" + link + "&title=" + title + "&fb=" + fb
        + "&id=" + chrome.runtime.id;

    req.onreadystatechange = function () {
        try {
            if (req.readyState == 4) {
                if (req.status == 200) {
                    clearServerError();
                    if (req.responseText.indexOf('true') !== -1) {
                        callback("Sent successfully!");
                        var fb =  req.responseText.substr(req.responseText.indexOf(":") + 1);
                        chrome.storage.sync.set({
                            fb: fb},
                            function() {console.log('Saved: ' + fb);});
                    } else if (req.responseText === 'IDENTIFY') {
                        errorCallback('Error: Identify yourself in options!');
                    } else if (req.responseText === 'VERIFY') {
                        errorCallback('Error: Verify your email!');
                    } else if (req.responseText === 'LIMIT') {
                        errorCallback('Error: Sorry but you are sending emails to fast! Keep calm and try again');
                    } else if (req.responseText === 'BAN') {
                        errorCallback('Error: Sorry but you are banned! Keep calm and try again after 24 hours');
                    } else {
                        setServerError(req.responseText);
                        errorCallback('Error: Connect with developer!');
                    }
                } else {
                    setServerError(req.status + ' ' + req.responseText);
                    errorCallback('Error: Try again later or connect with developer.');
                }
            }
        }
        catch (e) {
            errorCallback('Error, check your connection!');
        }
    };

    req.open("POST", url, true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(params);

}

function getSender(callback) {
    chrome.storage.sync.get({
        email: '',
        prefix: '',
        identyfied: '',
        error: '',
        checkemail: '',
        checkfb: '',
        checkslack: '',
        fb: '',
    }, function (items) {
        var types = {};
        if(items.checkemail) {
            types['checkemail'] = items.checkemail;
        }
        if(items.checkfb) {
            types['checkfb'] = items.checkfb;
        }
        if(items.checkslack) {
            types['checkslack'] = items.checkslack;
        }
        callback(items.email, items.prefix, types, items.fb);
    });
}

function identifyExt(id, email) {
    var req = new XMLHttpRequest();
    var url = "https://sharetoself.com/email/identify/";
    var params = "id=" + id + "&email=" + email;

    req.onreadystatechange = function () {
        try {
            if (req.readyState == 4) {
                if (req.status == 200) {
                    clearServerError();
                    var isIdentified = req.responseText;
                    var identified;
                    if (isIdentified === 'true' || isIdentified === '1') {
                        identified = isIdentified;
                    } else {
                        setServerError(isIdentified);
                        return;
                    }
                    chrome.storage.sync.set({
                        identyfied: identified
                    }, function () {
                    });
                } else {
                    setServerError(req.status + ' ' + req.responseText);
                }
            }
        }
        catch (e) {
            console.log("Error: " + e.description);
        }
    };

    req.open("POST", url, true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(params);
}

function statusMessageShow(message, tab, error) {
    if (tab) {
        if (!tab.url.startsWith('chrome://')) {
            var code = [
                'var d = document.createElement("div");',
                'if (' + error + ') {',
                    'd.style.backgroundColor = "#d2191a";',
                    'd.style.border = "1px solid #fff";',
                    'd.style.color = "white";',
                    'd.style.fontWeight = "600";',
                '} else {',
                    'd.style.backgroundColor = "#fbfbfb";',
                    'd.style.border = "1px solid #c7c7c7";',
                '}',
                'd.style.position = "fixed";',
                'd.style.top = "5px";',
                'd.style.right = "5px";',
                'd.style.zIndex = "9999";',
                'd.style.padding = "10px 30px 10px 30px";',
                'document.body.appendChild(d);',
                'd.setAttribute("id", "' + chrome.runtime.id + '");',
                'd.innerText="' + message + '";'
            ].join("\n");
            chrome.tabs.executeScript(tab.id, {code: code});

        }
    }
}

function statusMessageHide(message, tab, error) {
    if (tab) {
        if (!tab.url.startsWith('chrome://')) {
            var code = [
                'var d = document.getElementById("' + chrome.runtime.id + '");',
                'if (' + error + ') {',
                    'd.style.backgroundColor = "#d2191a";',
                    'd.style.border = "1px solid #000";',
                    'd.style.color = "white";',
                    'd.style.fontWeight = "600";}',
                'd.innerText="' + message + '";',
                'setTimeout(function(){document.body.removeChild(d)}, 2000)'
            ].join("\n");
            chrome.tabs.executeScript(tab.id, {code: code});
        }
    }
}

function statusMessage(message, tab, error) {
    statusMessageShow(message, tab, error);
    statusMessageHide(message, tab, error);
}

function setServerError(error) {
    chrome.storage.sync.set({
        error: error
    }, function () {
    });
}
function clearServerError() {
    chrome.storage.sync.set({
        error: ''
    }, function () {
    });
}