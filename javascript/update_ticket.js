function updateTicketInformation(event) {
    event.preventDefault()
    console.log("Event is button: " + event.target)
    const form = "#update-ticket-form"
    const ticketID = document.querySelector(form + " #ticket-id").getAttribute("value")
    const ticketPriority = document.querySelector(form + " input[type='radio'][name='priority']:checked").getAttribute("value")
    const ticketDepartment = document.querySelector(form + " select[name='department']").value
    const ticketAgent = document.querySelector(form + " select[name='agent']").value
    const ticketHashtags = document.querySelectorAll(form + " input[type='checkbox'][name='hashtags[]']:checked")

    console.log("Ticket ID: " + ticketID)
    console.log("Ticket Department: " + ticketDepartment)
    console.log("Ticket Agent: " + ticketAgent)
    console.log("Ticket Priority: " + ticketPriority)
    console.log("Ticket hashtags: " + ticketHashtags)
    for (const ticketHashtag of ticketHashtags) {
        console.log(ticketHashtag.value)
    }
}

const changeTicketInfoForm = document.querySelector("#update-ticket-form")
console.log("Ticket form: " + changeTicketInfoForm)

if (changeTicketInfoForm) {
    changeTicketInfoForm.addEventListener("submit", updateTicketInformation)
    console.log(changeTicketInfoForm)
}
