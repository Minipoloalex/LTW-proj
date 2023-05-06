// const entityMap = {
//     "&": "&amp;",
//     "<": "&lt;",
//     ">": "&gt;",
//     '"': '&quot;',
//     "'": '&#39;',
//     "/": '&#x2F;'
// };
function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
        return entityMap[s];
    });
}

// function encodeForAjax(data) {
//     return Object.keys(data).map(function(k) {
//         return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
//     }).join('&')
// }

async function postDataMessage(data) {
    console.log(data)
    console.log(encodeForAjax(data))
    return fetch('../api/api_add_message.php', {
        method: 'post',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: encodeForAjax(data)
    })
}

function submitNewMessage(event) {
    event.preventDefault()
    
    const messagesList = document.querySelector('#messages-list')
    const messageInput = document.querySelector('#message-input')
    
    const messageText = messageInput.value
    const ticketID = messageInput.getAttribute("data-id")
    console.log(messageText)
    messageInput.value = ""
    
    postDataMessage({'message': messageText, 'ticketID': ticketID})
    .catch(() => console.error('Network Error'))
    .then(response => response.json())
    .catch(() => console.error('Error parsing JSON'))
    .then(json => {
        console.log(json)
        if (json['error']) {
            console.error(json['error'])
            return
        }
        const newMessage = document.createElement("article")
        newMessage.classList.add("message")
        newMessage.classList.add("self")    // Note: current user can only add messages from himself
        
        const header = document.createElement("header")
        
        const user = document.createElement("span")
        user.classList.add("user")
        user.textContent = json['username']
        header.appendChild(user)
        
        const date = document.createElement("span")
        date.classList.add("date")
        date.textContent = "DATE: " + json['date']
        header.appendChild(date)

        const text = document.createElement("p")
        text.classList.add("text")
        
        text.textContent = 'CONTENT: ' +  json['text']


        newMessage.appendChild(header)
        newMessage.appendChild(text)

        messagesList.appendChild(newMessage)
    })
}

const messageButton = document.querySelector("button#add-message")
if (messageButton) {
    messageButton.addEventListener('click', submitNewMessage)
}
