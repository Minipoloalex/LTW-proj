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
    return document.querySelector("body").getAttribute("data-csrf")
}

function setCsrf(csrfValue) {
    const body = document.querySelector("body")
    if (body) body.setAttribute("data-csrf", csrfValue)

    const csrfInputs = document.querySelectorAll("input[name='csrf']")
    for (const input of csrfInputs) input.setAttribute("value", csrfValue)
}


const tx = document.getElementsByTagName("textarea");
for (let i = 0; i < tx.length; i++) {
  tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;overflow-y:hidden;");
  tx[i].addEventListener("input", OnInput, false);
}

function OnInput() {
  this.style.height = 0;
  this.style.height = (this.scrollHeight) + "px";
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
