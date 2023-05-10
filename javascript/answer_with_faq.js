const toggleAnswer = document.querySelector("button#toggle-faq-answer")
if (toggleAnswer) {
    const submitFaq = document.querySelector("button#faq-message")
    
    toggleAnswer.addEventListener("click", toggleAnswerFaq)
    submitFaq.addEventListener("click", addMessageByFaq)
}

async function addMessageByFaq(event) {
    event.preventDefault()
    const faqID = document.querySelector("select[name='faq']").value
    console.log("id: " + faqID)
    const jsonFAQ = await getData("../api/api_FAQ.php", {
        'id': faqID,
    })
    console.log(jsonFAQ)
    if (jsonFAQ['error']) {
        console.error(jsonFAQ['error'])
        return
    }
    else if (jsonFAQ['success']) {
        const messageText = jsonFAQ['question'] + "\n" + jsonFAQ['answer']
        const jsonMessage = await postNewMessage(messageText, getIndividualTicketID())
        console.log(jsonMessage)
        if (jsonMessage['error']) {
            console.error(jsonMessage['error'])
            return
        }
        else if (jsonMessage['success']) {
            addMessageToDOM(getIndividualTicketMessagesList(),
            jsonMessage['text'], jsonMessage['username'], jsonMessage['date'])
        }
    }
}

function toggleAnswerFaq() {
    const faqText = "Answer with FAQ"
    const messageText = "Answer with message"
    toggleAnswer.textContent = toggleAnswer.textContent === faqText 
    ? messageText : faqText
    const messageForm = document.querySelector("#message-form")
    const faqForm = document.querySelector("#faq-form")

    messageForm.toggleAttribute("hidden")
    faqForm.toggleAttribute("hidden")
}
