function getCurrentTabUrl(callback) {
    var queryInfo = {
        active: true,
        currentWindow: true
    };
    chrome.tabs.query(queryInfo, function (tabs) {
        var tab = tabs[0];
        var url = tab.url;
        console.assert(typeof url == 'string', 'tab.url should be a string');
        var title = tab.title;
        callback(url, title);
    });
}

function renderStatus(statusText, error) {
    if (error) {
        document.getElementById('status').setAttribute('class', 'error');
    }
    document.getElementById('status').textContent = statusText;
}

document.addEventListener('DOMContentLoaded', function () {
    getCurrentTabUrl(function (url, title) {
        renderStatus('Sending: ' + title, false);

        getSender(function (email, prefix, types, fb) {
            if (types['checkemail']) {
                if (email) {
                    sendUrlEmail(url, prefix + " " + title, email,
                        function (message) {
                            renderStatus(message, false);
                        }, function (errorMessage) {
                            renderStatus(errorMessage, true);
                        });
                } else {
                    renderStatus("Error: Set email in options.", true);
                }
            }
            if (types['checkfb']) {
                // if (fb) {
                sendUrlFB(url, prefix + " " + title, types['checkfb'], fb,
                    function (message) {
                        renderStatus(message, false);
                    }, function (errorMessage) {
                        renderStatus(errorMessage, true);
                    });
                // } else {
                //     renderStatus("Error: Set fb in options.", true);
                // }
            }

            if (types['checkslack']) {
                sendUrlSlack(url, prefix + " " + title, types['checkslack'],
                    function (message) {
                        renderStatus(message, false);
                    }, function (errorMessage) {
                        renderStatus(errorMessage, true);
                    });
            }
        });
    });
});
