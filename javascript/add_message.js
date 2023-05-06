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

    const csrf = getCsrf()
    
    postDataMessage({'message': messageText, 'ticketID': ticketID, 'csrf': csrf})
    .catch(() => console.error('Network Error'))
    .then(response => response.json())
    .catch(() => console.error('Error parsing JSON'))
    .then(json => {
        console.log(json)
        setCsrf(json['csrf'] ?? [])
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
        date.textContent = json['date']
        header.appendChild(date)

        const text = document.createElement("p")
        text.classList.add("text")
        
        text.textContent = json['text']


        newMessage.appendChild(header)
        newMessage.appendChild(text)

        messagesList.appendChild(newMessage)
    })
}

const messageButton = document.querySelector("button#add-message")
if (messageButton) {
    messageButton.addEventListener('click', submitNewMessage)
}
