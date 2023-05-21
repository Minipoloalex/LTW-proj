const toggleAnswerArray = document.querySelectorAll("button.toggle-faq-answer");
if (toggleAnswerArray.length > 0) {
    for (const toggleAnswer of toggleAnswerArray) {
        toggleAnswer.addEventListener("click", toggleAnswerFaq);
    }
    const submitFaq = document.querySelector("button#faq-message");
    submitFaq.addEventListener("click", addMessageByFaq);
}

async function addMessageByFaq(event) {
    event.preventDefault();
    const faqID = document.querySelector("select[name='faq']").value;
    const jsonFAQ = await getData("../api/api_FAQ.php", {
        'id': faqID,
    });

    if (jsonFAQ['error']) {
        displayFeedback(addMessageFeedback, jsonFAQ);
    }
    else if (jsonFAQ['success']) {
        const messageText = jsonFAQ['question'] + "\n" + jsonFAQ['answer'];
        const jsonMessage = await postNewMessage(messageText, getIndividualTicketID());
        if (jsonMessage['success']) {
            addMessageToDOM(getIndividualTicketMessagesList(), jsonMessage['text'],
            jsonMessage['username'], jsonMessage['date']);
        }
    }
}

function toggleAnswerFaq(event) {
    event.preventDefault();
    const messageForm = document.querySelector("#message-form");
    const faqForm = document.querySelector("#faq-form");

    messageForm.toggleAttribute("hidden");
    faqForm.toggleAttribute("hidden");
}
