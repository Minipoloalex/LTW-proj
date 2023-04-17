function encodeForAjax(data) {
    return Object.keys(data).map(function(k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

async function postData(data) {
    console.log(data)
    console.log(encodeForAjax(data))
    return fetch('../actions/action_add_message.php', {
        method: 'post',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: encodeForAjax(data)
    })
}

const messageForm = document.querySelector("#message-form")

// <?php function output_message(Message $message) { ?>
//     <article class="message">
//         <span class="user">UserID: <?=$message->userID?></span>
//         <span class="date">DATE: <?=date('F j', $message->date)?></span>
//         <p class="message">CONTENT: <?=$message->text?></p>
//     </article>
// <?php }?>

messageForm.addEventListener('submit', (event) => {
    event.preventDefault()

    const messagesList = document.querySelector('#messages-list')
    const messageInput = document.querySelector('#message-input')
    
    const messageText = messageInput.value
    const ticketID = messageInput.getAttribute("data-id")

    messageInput.value = ""
    
    postData({'message': messageText, 'ticketID': ticketID})
    .catch(() => console.error('Network Error'))
    .then(response => response.json())
    .catch(() => console.error('Error parsing JSON'))
    .then(json => {
        console.log(json)

        const newMessage = document.createElement("article")
        newMessage.classList.add("message")
        
        const user = document.createElement("span")
        user.classList.add("user")
        user.textContent = "UserID: " + json['userID'] + " "
        
        const date = document.createElement("span")
        date.classList.add("user")
        date.textContent = "DATE: " + json['date']
        
        const text = document.createElement("p")
        text.classList.add("message")
        
        text.textContent = 'CONTENT: ' + json['text']


        newMessage.appendChild(user)
        newMessage.appendChild(date)
        newMessage.appendChild(text)

        messagesList.appendChild(newMessage)
    })
})
