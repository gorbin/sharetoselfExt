function save_options() {
    var email = document.getElementById('email').value;
    var prefix = document.getElementById('prefix').value;
    var checkemail = document.getElementById('checkemail').checked;
    var checkfb = document.getElementById('checkfb').checked;
    var checkslack = document.getElementById('checkslack').checked;

    if (email) {
        if (!validateEmail(email)) {
            // $('<input type="submit">').hide().appendTo($('#email-form')).click().remove();
            var field = document.getElementById('email');
            field.style.color = "#d2191a";
            field.style.border = "1px solid #d2191a";
            statusMessageOptions("Error: Invalid email address.", true);
            return;
        }
    }

    chrome.storage.sync.set({
        email: email,
        prefix: prefix,
        checkemail: checkemail,
        checkfb: checkfb,
        checkslack: checkslack,
    }, function () {
        var field = document.getElementById('email');
        field.style.color = "Black";
        field.style.border = "1px solid #a9a9a9"
        statusMessageOptions("Saved");
        if (email) {
            identifyExt(chrome.runtime.id, email);
        }
        restore_options();
    });

}

function restore_options() {
    chrome.storage.sync.get({
        email: '',
        prefix: '',
        identyfied: '',
        error: '',
        checkemail: '',
        checkfb: '',
        checkslack: '',
    }, function (items) {
        document.getElementById('email').value = items.email;
        document.getElementById('prefix').value = items.prefix;
        document.getElementById('checkemail').checked = items.checkemail;
        document.getElementById('checkfb').checked = items.checkfb;
        document.getElementById('checkslack').checked = items.checkslack;
        // if(items.email && !items.identyfied) {
        //     statusMessageOptions("Extension could not identified! Try to save email in options again.", true);
        // }
        // if(!items.error){
        //     setErrorCallback();
        // }
    });
}

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

function statusMessageOptions(message, error) {
    if (!error && document.getElementById(chrome.runtime.id)) {
        document.body.removeChild(document.getElementById(chrome.runtime.id));
    }
    var d = document.createElement("div");
    setStatus(d, error);
    document.body.appendChild(d);
    d.setAttribute("id", chrome.runtime.id);
    d.innerText = message;
    if (!error) {
        setTimeout(function () {
            document.body.removeChild(d)
        }, 2000);
    }
}

function setStatus(d, error) {
    if (error) {
        d.style.backgroundColor = "#d2191a";
        d.style.border = "1px solid #fff";
        d.style.color = "white";
        d.style.fontWeight = "600";
    } else {
        d.style.backgroundColor = "#fbfbfb";
        d.style.border = "1px solid #c7c7c7";
    }
    d.style.position = "fixed";
    d.style.top = "5px";
    d.style.zIndex = "9999";
    d.style.padding = "10px 30px 10px 30px";
    d.style.left = "50%";
    d.style.transform = "translateX(-50%)";
}

// function setErrorCallback(){
//     var b = document.createElement("button");
//     b.setAttribute("id", chrome.runtime.id);
//     b.setAttribute("class", "error-btn");
//     document.getElementByClass("end").appendChild(b);
//     b.innerText = "Send Error";
//     b.addEventListener('click', sendError());
// }

document.addEventListener('DOMContentLoaded', fbinit);

document.addEventListener('DOMContentLoaded', restore_options);
document.addEventListener('DOMContentLoaded', slackInit);

document.getElementById('save').addEventListener('click', save_options);

function fbinit() {
    document.getElementById("fbinit").setAttribute("data-ref", chrome.runtime.id);
    window.fbAsyncInit = function () {
        FB.init({
            appId: '1746289228945988',
            xfbml: true,
            version: 'v2.7'
        });
    };

    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
}

function slackInit() {
    document.getElementById('slackLink')
        .setAttribute("href", 'https://slack.com/oauth/authorize?scope=bot&client_id=2243620212.87255959762&state=' + chrome.runtime.id);
}