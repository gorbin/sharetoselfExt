chrome.runtime.onInstalled.addListener(function (object) {
    getSender(function (email, prefix, types, fb) {
        count=0;
        types.forEach(function(type, i, arr) {
            if(type){
                count++;
            }
        });
        if(count == 0){
            chrome.tabs.create({url: "/options.html"}, function (tab) {
            });
        }
        // if(types['checkemail']){
        //     if (!email) {
        //         chrome.tabs.create({url: "/options.html"}, function (tab) {
        //         });
        //     } else {
        //         identifyExt(chrome.runtime.id, email);
        //     }
        // }
    });
});

var contexts = ["page", "selection", "link", "image", "video",
    "audio"];
var context;
for (var i = 0; i < contexts.length; i++) {

    context = contexts[i];
    var title = "Share '" + context + "' to self";
    chrome.contextMenus.create({
        "title": title, "contexts": [context],
        "onclick": prepareData
    });
}

function prepareData(info, tab) {
    var content;
    var title;
	
	if(info.hasOwnProperty("mediaType")) {
		switch (info.mediaType) {
        case 'image':
            content = info.srcUrl;
            title = 'Image from ' + tab.title;
            break;
        case 'video':
            content = info.srcUrl;
            title = 'Video from ' + tab.title;
            break;
        case 'audio':
            content = info.srcUrl;
            title = 'Audio from ' + tab.title;
            break;
        default:
		break;
		}
	} else if(info.hasOwnProperty("selectionText")){
		content = info.selectionText;
        title = 'Selection from ' + tab.title;
	} else if(info.hasOwnProperty("linkUrl")){
		content = info.linkUrl;
            title = 'Link from ' + tab.title;
	} else {
		content = info.pageUrl;
        title = tab.title;
	}
	
    if(content && title) {
        getSender(function (email, prefix, types, fb) {
            if(types['checkemail']) {
                if (email) {
                    statusMessageShow("Sending " + prefix + " " + title, tab, false);
                    sendUrlEmail(content, prefix + " " + title, email,
                        function (message) {
                            statusMessageHide(message, tab, false);
                        }, function (errorMessage) {
                            statusMessageHide(errorMessage, tab, true);
                        });
                } else {
                    statusMessage("Error: Set email in options.", tab, true);
                }
            }
            if(types['checkfb']) {
                if (fb) {
                    statusMessageShow("Sending " + prefix + " " + title, tab, false);
                    sendUrlFB(content, prefix + " " + title, types['checkfb'], fb,
                        function (message) {
                            statusMessageHide(message, tab, false);
                        }, function (errorMessage) {
                            statusMessageHide(errorMessage, tab, true);
                        });
                } else {
                    statusMessage("Error: Set email in options.", tab, true);
                }
            }
        });
    }
}
