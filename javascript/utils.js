const inverterdEntityMap = {
    "&amp;": "&",
    "&lt;": "<",
    "&gt;": ">",
    '&quot;': '"',
    '&#039;': "'",
    '&#x2F;': "/"
};
function decodeHtml(string) {
    return String(string).replace(/&(amp|lt|gt|quot|#039|#x2F);/g, function (s) {
        return inverterdEntityMap[s];
    });
}
function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
}

async function bodyFetch(path, data, method) {
    const response = await fetch(path, {
        method: method,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: encodeForAjax(data)
        }
    );
    return await response.json();
}
async function urlFetch(path, data, method) {
    const response = await fetch(path + "?" + encodeForAjax(data), {
        method: method,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            },
        }
    );
    return await response.json();
}
async function postData(path, data) {
    data['csrf'] = getCsrf();
    const json = await bodyFetch(path, data, 'POST');
    setCsrf(json['csrf']);
    return json;
}
async function patchData(path, data) {
    data['csrf'] = getCsrf();
    const json = await urlFetch(path, data, 'PATCH');
    setCsrf(json['csrf']);
    return json;
}
async function putData(path, data) {
    data['csrf'] = getCsrf();
    const json = await bodyFetch(path, data, 'PUT');
    setCsrf(json['csrf']);
    return json;
}
async function deleteData(path, data) {
    data['csrf'] = getCsrf();
    const json = await urlFetch(path, data, 'DELETE');
    setCsrf(json['csrf']);
    return json;
}
async function getData(path, data) {
    return await urlFetch(path, data, 'GET');
}
function getCsrf() {
    return document.querySelector("body").getAttribute("data-csrf");
}

function setCsrf(csrfValue) {
    if (!csrfValue) return;
    const body = document.querySelector("body");
    if (body) body.setAttribute("data-csrf", csrfValue);

    const csrfInputs = document.querySelectorAll("input[name='csrf']");
    for (const input of csrfInputs) input.setAttribute("value", csrfValue);
}


const tx = document.getElementsByTagName("textarea");
for (let i = 0; i < tx.length; i++) {
    handleTextAreas(tx[i]);
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
            feedbackMessage.remove();
        }, 5000);
    } else {
        setTimeout(function() {
            clearFeedbackMessage(feedbackMessage);
        }, 5000);
    }
}

function displayFeedback(element, json, remove = false) {
    if (json['error']) {
        displayMessage(element, json['error'], true, remove);
    }
    else displayMessage(element, json['success'], false, remove);
}

function clearFeedbackMessage(message) {
    message.textContent = '';
    message.classList.remove('error-message');
    message.classList.remove('success-message');
}

function clearAllDisplayFeedback() {
    const feedbackMessages = document.querySelectorAll('.feedback-message');
    for (const feedbackMessage of feedbackMessages) {
        clearFeedbackMessage(feedbackMessage);
    }
}

function getPasswordRegex() {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/;
}
function getEmailRegex() {
    return /^[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/;
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
function setTextContent(element, messageText) {
    console.log(messageText);
    console.log(decodeHtml(messageText));
    element.textContent = decodeHtml(messageText);
}
