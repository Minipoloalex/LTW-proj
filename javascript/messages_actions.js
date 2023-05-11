/* Individual ticket toggling between messages and actions */

const enableMessagesButton = document.querySelector("#enable-messages")
const enableActionsButton = document.querySelector("#enable-actions")
const messages = document.querySelector("#messages-list")
const actions = document.querySelector("#actions-list")
const answerForms = document.querySelector("#answer-forms")

if (enableMessagesButton) {
    enableMessagesButton.addEventListener("click", enable_messages)
    enableActionsButton.addEventListener("click", enable_actions)
}

function enable_messages(event) {
    event.preventDefault()

    messages.classList.remove("d-none")
    answerForms.classList.remove("d-none")

    actions.classList.add("d-none")
    enableMessagesButton.classList.remove("inactive")
    enableActionsButton.classList.add("inactive")
}

function enable_actions(event) {
    event.preventDefault()

    actions.classList.remove("d-none")
    messages.classList.add("d-none")
    answerForms.classList.add("d-none")

    enableActionsButton.classList.remove("inactive")
    enableMessagesButton.classList.add("inactive")
}
