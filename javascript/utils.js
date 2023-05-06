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
    return Object.keys(data).map(function(k) {
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
