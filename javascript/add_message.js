const messageButton = document.querySelector("#message-form button[type='submit']")
if (messageButton) {
    messageButton.addEventListener('click', submitNewMessage)
}

async function postNewMessage(messageText, ticketID, fileInput) {
    if (fileInput && fileInput.files.length > 0) {
        const file = fileInput.files[0]
        const formData = new FormData()
        formData.append('message', messageText)
        formData.append('ticketID', ticketID)
        formData.append('image', file)
        formData.append('csrf', getCsrf())
        const response = await fetch('../api/api_add_message.php', {
            method: 'POST',
            body: formData
        })
        const json = await response.json()
        setCsrf(json['csrf'])
        displayFeedback(json)
        return json
    }
    const json = await postData('../api/api_add_message.php', {
        'message': messageText,
        'ticketID': ticketID,
    })
    // console.log(json)
    displayFeedback("add-message-feedback", json)
    return json
}
async function submitNewMessage(event) {
    event.preventDefault()
    
    const messagesList = getIndividualTicketMessagesList()
    const messageInput = document.querySelector('#message-form textarea')
    
    const fileInput = document.getElementById('upload-image');

    const messageText = messageInput.value
    const ticketID = getIndividualTicketID()
    console.log(messageText)
    messageInput.value = ""

    const json = await postNewMessage(messageText, ticketID, fileInput)
    console.log(json)
    if (json['success']) {
        if (fileInput.files.length > 0) {
            addMessageWithFileToDOM(messagesList, json['text'], json['username'], json['date'], json['id'])
        }
        else addMessageToDOM(messagesList, json['text'], json['username'], json['date'])
    }
}

function addMessageWithFileToDOM(messagesList, messageText, username, dateText, messageID) {
    const newMessage = getPlainNewMessage()
    newMessage.classList.add("has-image")
    
    const header = getHeader(username, dateText)
    const messageBody = getPlainMessageBody(messageText)

    const viewImageButton = getViewImageButton()
    messageBody.appendChild(viewImageButton)

    const imageContainer = getImageContainer()
    const closeImageButton = getCloseImageButton()
    const image = document.createElement("img")
    image.setAttribute("src", "../actions/action_view_image.php?messageID=" + messageID)
    image.setAttribute("alt", "Attached image")

    imageContainer.appendChild(closeImageButton)
    imageContainer.appendChild(image)

    messageBody.appendChild(imageContainer)

    newMessage.appendChild(header)
    newMessage.appendChild(messageBody)

    messagesList.appendChild(newMessage)
}

function addMessageToDOM(messagesList, messageText, username, dateText) {
    const newMessage = getPlainNewMessage()
    const header = getHeader(username, dateText)
    const messageBody = getPlainMessageBody(messageText)

    newMessage.appendChild(header)
    newMessage.appendChild(messageBody)

    messagesList.appendChild(newMessage)
}


function getImageContainer() {
    const imageContainer = document.createElement("div")
    imageContainer.classList.add("image-container")
    imageContainer.classList.add("d-none")
    return imageContainer
}
function getCloseImageButton() {
    const closeImageButton = document.createElement("button")
    closeImageButton.classList.add("close-image")
    closeImageButton.textContent = "Close"
    closeImageButton.addEventListener("click", clickOnCloseImageButton)
    return closeImageButton
}

function getViewImageButton() {
    const viewImageButton = document.createElement("button")
    viewImageButton.classList.add("view-image")
    viewImageButton.textContent = "View attached image"
    viewImageButton.addEventListener("click", clickOnViewImageButton)
    return viewImageButton
}

function getPlainMessageBody(messageText) {
    const messageBody = document.createElement("div")
    messageBody.classList.add("message-body")

    const text = document.createElement("p")
    text.classList.add("text")
    text.textContent = messageText
    messageBody.appendChild(text)
    return messageBody
}
function getPlainNewMessage() {
    const newMessage = document.createElement("article")
    newMessage.classList.add("message")
    newMessage.classList.add("self")
    newMessage.setAttribute("title", "self")
    return newMessage
}
function getHeader(username, dateText) {
    const header = document.createElement("header")
    
    const user = document.createElement("span")
    user.classList.add("user")
    user.textContent = username
    header.appendChild(user)
    
    const date = document.createElement("span")
    date.classList.add("date")
    date.textContent = dateText
    header.appendChild(date)
    return header
}

function getIndividualTicketID() {
    return document.querySelector('article#individual-ticket').getAttribute("data-id")
}
function getIndividualTicketMessagesList() {
    return document.querySelector("#messages-list")
}
