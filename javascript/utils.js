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

function getCsrf() {
    return document.querySelector("input[name='csrf']").getAttribute("value")
}

function setCsrf(csrfValue) {
    const csrf = document.querySelectorAll("input[name='csrf']")
    for (const input of csrf) {
        input.setAttribute("value", csrfValue)
    }
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


/*mensagens de erro*/
const FeedbackMessage = document.getElementById('feedback-message');

function displayMessage(message, error = true) {
    FeedbackMessage.textContent = message;
    if (error){
        FeedbackMessage.classList.add('error-message');
        FeedbackMessage.classList.remove('success-message');
    }
    else{
        FeedbackMessage.classList.add('success-message');
        FeedbackMessage.classList.remove('error-message');
    }
}
