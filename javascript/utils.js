const entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
};
function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return entityMap[s];
    });
}

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

async function postData(path, data) {
    data['csrf'] = getCsrf()
    const response = await fetch(path, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: encodeForAjax(data)
        }
    );
    const json = await response.json()
    
    setCsrf(json['csrf'])
    return json;
}

async function getData(path, data) {
    const response = await fetch(path + "?" + encodeForAjax(data), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            },
        }
    )    
    return await response.json()
}

function getCsrf() {
    return document.querySelector("body").getAttribute("data-csrf")
}

function setCsrf(csrfValue) {
    if (!csrfValue) return
    const body = document.querySelector("body")
    if (body) body.setAttribute("data-csrf", csrfValue)

    const csrfInputs = document.querySelectorAll("input[name='csrf']")
    for (const input of csrfInputs) input.setAttribute("value", csrfValue)
}


const tx = document.getElementsByTagName("textarea");
for (let i = 0; i < tx.length; i++) {
    handleTextAreas(tx[i]);
    //   tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;overflow-y:hidden;");
    //   tx[i].addEventListener("input", OnInput, false);
    //   tx[i].addEventListener("input", function() {
    //     this.value = this.value.replace(/(\r\n|\n|\r){2,}/gm, '$1');
    //   });
}

function handleTextAreas(textarea) {
    function OnInput() {
        this.style.height = 0;
        this.style.height = (this.scrollHeight) + "px";
    }

    textarea.setAttribute("style", "height:" + (textarea.scrollHeight) + "px;overflow-y:hidden;");
    textarea.addEventListener("input", OnInput, false);
    textarea.addEventListener("input", function () {
        this.value = this.value.replace(/(\r\n|\n|\r){2,}/gm, '$1');
    });
}


function displayMessage(feedbackMessage, message, error = true, remove = false) {
    console.log(feedbackMessage);
    feedbackMessage.textContent = message;
    if (error){
        feedbackMessage.classList.add('error-message');
        feedbackMessage.classList.remove('success-message');
    }
    else{
        feedbackMessage.classList.add('success-message');
        feedbackMessage.classList.remove('error-message');
    }
    if (remove) {
        setTimeout(function() {
            feedbackMessage.remove()
        }, 5000);
    } else {
        setTimeout(function() {
            clearFeedbackMessage(feedbackMessage);
        }, 5000);
    }
}

function displayFeedback(element, json, remove = false) {
    if (json['error']) {
        displayMessage(element, json['error'], true, remove)
    }
    else displayMessage(element, json['success'], false, remove)
}

function clearFeedbackMessage(message) {
    message.textContent = '';
    message.classList.remove('error-message');
    message.classList.remove('success-message');
}

// function clearDisplayFeedback(feedbackMessage) {
//     clearFeedbackMessage(feedbackMessage);
// }

function clearAllDisplayFeedback() {
    const feedbackMessages = document.querySelectorAll('.feedback-message');
    for (const feedbackMessage of feedbackMessages) {
        clearFeedbackMessage(feedbackMessage);
    }
}



function getPasswordRegex() {
    return /^(?=.*[0-9])(?=.*[!@#$%^&])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&]{6,}$/;
}
function getEmailRegex() {
    return /^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/
}
function getUsernameRegex() {
    return /^[A-Za-zÀ-ÖØ-öø-ÿ0-9_\-. ]+$/;
}
function getNameRegex() {
    return /^[A-Za-zÀ-ÖØ-öø-ÿ\- ]+$/;
}
function isEmpty(string) {
    return !string || string.length === 0;
}
