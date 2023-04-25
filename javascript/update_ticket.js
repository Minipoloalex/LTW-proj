function encodeForAjax(data) {
    return Object.keys(data).map(function(k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

function postDataTicketInfo(data) {
    return fetch("../api/api_update_ticket.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: encodeForAjax(data)
    })
}

function postClosedTicket(data) {
    return fetch("../api/api_close_ticket.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: encodeForAjax(data)
    })
}


function updateTicketInformation(event) {
    event.preventDefault()

    const form = "#update-ticket-form"
    const ticketID = document.querySelector(form + " #ticket-id").getAttribute("value")
    const ticketPriority = document.querySelector(form + " input[type='radio'][name='priority']:checked").getAttribute("value")
    const ticketDepartment = document.querySelector(form + " select[name='department']").value
    const ticketAgent = document.querySelector(form + " select[name='agent']").value
    const ticketHashtags = document.querySelectorAll(form + " input[type='checkbox'][name='hashtags[]']:checked")
    const ticketHashtagIDs = []
    for (const hashtag of ticketHashtags) {
        ticketHashtagIDs.push(hashtag.getAttribute("value"))
    }
    postDataTicketInfo({
        'ticketID': ticketID,
        'department': ticketDepartment,
        'agent': ticketAgent,
        'priority': ticketPriority,
        'hashtags': ticketHashtagIDs
    })
    .catch(() => console.error("Network Error"))
    .then(response => response.json())
    .catch(() => console.error("Error parsing JSON"))
    .then(json => {
        if (json['error']) {
            console.error(json['error'])
        }
        else if (json['success']) {
            console.log(json['success'])
            const updatedPrio = document.querySelector("#ticket-priority")
            updatedPrio.textContent = "Priority: " + (json['priority'] ?? "None")

            const updatedDept = document.querySelector("#ticket-department")
            updatedDept.textContent = "Department: " + (json['department'] ?? "None")

            const updatedAgent = document.querySelector("#ticket-agent")
            updatedAgent.textContent = "Agent: " + (json['agent'] ?? "None")

            const updatedHashtags = document.querySelector("#ticket-hashtags")
            updatedHashtags.innerHTML = ""

            for (const hashtag of json['hashtags']) {
                // <li class="hashtag"><?=$hashtag->hashtagname?></li>
                const newHashtag = document.createElement("li")
                newHashtag.classList.add("hashtag")
                newHashtag.textContent = hashtag;
                updatedHashtags.appendChild(newHashtag)
            }
        }
    })
}

const changeTicketInfoButton = document.querySelector("button#update-ticket")

if (changeTicketInfoButton) {
    changeTicketInfoButton.addEventListener("click", updateTicketInformation)
}

const closeTicketButton = document.querySelector("button#close-ticket")
if (closeTicketButton) {
    closeTicketButton.addEventListener("click", closeTicket)
}

function closeTicket(event) {
    event.preventDefault()
    const form = "#update-ticket-form"
    const ticketID = document.querySelector(form + " #ticket-id").getAttribute("value")
    postClosedTicket({
        'ticketID': ticketID,
        'status': 'closed'
    })
    .catch(() => console.error("Network Error"))
    .then(response => response.json())
    .catch(() => console.error("Error parsing JSON"))
    .then(json => {
        if (json['error']) {
            console.error(json['error'])
        }
        else if (json['success']) {
            console.log(json['success'])
            const status = document.querySelector("#individual-ticket #ticket-status")
            status.textContent = 'closed'
            status.classList.add("closed")
            
            closeTicketButton.remove()

            const ticketForm = document.querySelector("#update-ticket-form")
            ticketForm.remove()
            
            const messageForm = document.querySelector("#message-form")
            messageForm.remove()

            const reopenTicketForm = document.createElement("form")
            reopenTicketForm.setAttribute("id", "reopen-ticket-form")
            reopenTicketForm.setAttribute("method", "post")
            reopenTicketForm.setAttribute("action", "../actions/action_reopen_ticket.php")
            
            const reopenTicketID = document.createElement("input")
            reopenTicketID.setAttribute("type", "hidden")
            reopenTicketID.setAttribute("name", "ticketID")
            reopenTicketID.setAttribute("value", ticketID)
            reopenTicketForm.appendChild(reopenTicketID)

            const reopenTicketButton = document.createElement("button")
            reopenTicketButton.setAttribute("id", "reopen-ticket")
            reopenTicketButton.setAttribute("type", "submit")
            reopenTicketButton.textContent = "Reopen Ticket"
            reopenTicketForm.appendChild(reopenTicketButton)
            
            status.insertAdjacentElement("afterend", reopenTicketForm)
        }
    })
}
