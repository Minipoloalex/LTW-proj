async function submitNewMessage(event) {
    event.preventDefault()
    
    const messagesList = document.querySelector('#messages-list')
    const messageInput = document.querySelector('#message-form textarea')
    
    const messageText = messageInput.value
    const ticketID = messageInput.getAttribute("data-id")
    console.log(messageText)
    messageInput.value = ""

    const json = await postData('../api/api_add_message.php', {
        'message': messageText,
        'ticketID': ticketID,
    })
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
    date.textContent = json['date']
    header.appendChild(date)

    const text = document.createElement("p")
    text.classList.add("text")
    
    text.textContent = json['text']


    newMessage.appendChild(header)
    newMessage.appendChild(text)

    messagesList.appendChild(newMessage)
}

const messageButton = document.querySelector("#message-form button")
if (messageButton) {
    messageButton.addEventListener('click', submitNewMessage)
}
