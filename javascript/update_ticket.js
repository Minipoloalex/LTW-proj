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

    const form = "#ticket-info"
    const ticketID = document.querySelector(form + " #ticket-id").getAttribute("value")
    const ticketPriority = document.querySelector(form + " input[type='radio'][name='priority']:checked").getAttribute("value")
    const ticketDepartment = document.querySelector(form + " select[name='department']").value
    const ticketAgent = document.querySelector(form + " select[name='agent']").value
    const ticketHashtags = document.querySelectorAll(form + " input[name='hashtags[]']")
    const ticketHashtagIDs = []
    for (const hashtag of ticketHashtags) {
        ticketHashtagIDs.push(hashtag.getAttribute("value"))
    }
    postDataTicketInfo({
        'ticketID': ticketID,
        'department': ticketDepartment,
        'agent': ticketAgent,
        'priority': ticketPriority,
        'hashtags': ticketHashtagIDs,
        'csrf': getCsrf()
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
            ticketStatus = document.querySelector("#individual-ticket #ticket-status")
            for (const status of ticketStatus.classList) {
                ticketStatus.classList.remove(status)
            }
            ticketStatus.textContent = json['status'];
            if (json['status'].toLowerCase() == 'in progress') ticketStatus.classList.add("in-progress")
            else ticketStatus.classList.add(json['status'])
            
            console.log('Hello')
            console.log(json['status'])
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
    const ticketID = document.querySelector("#ticket-info #ticket-id").getAttribute("value")
    postClosedTicket({
        'ticketID': ticketID,
        'status': 'closed',
        'csrf': getCsrf()
    })
    .catch(() => console.error("Network Error"))
    .then(response => response.json())
    .catch(() => console.error("Error parsing JSON"))
    .then(json => {
        setCsrf(json['csrf'] ?? '')
        if (json['error']) {
            console.error(json['error'])
        }
        else if (json['success']) {
            console.log(json['success'])

            const status = document.querySelector("#individual-ticket #ticket-status")
            for (st of status.classList) {
                status.classList.remove(st)
            }
            status.textContent = 'closed'
            status.classList.add("closed")

            // Remove the forms
            const messageForm = document.querySelector("#message-form")
            messageForm.remove()
            const priority = document.querySelector("#priority")
            if (priority) priority.remove()
            const department = document.querySelector("#department")
            if (department) department.remove()
            const agent = document.querySelector("#agent")
            if (agent) agent.remove()
            const hashtags = document.querySelector("#hashtags")
            if (hashtags) hashtags.remove()
            const closeTicket = document.querySelector("#close-ticket")
            if (closeTicket) closeTicket.remove()
            const updateTicket = document.querySelector("#update-ticket")
            if (updateTicket) updateTicket.remove()


            const reopenTicketForm = document.createElement("form")
            reopenTicketForm.setAttribute("id", "reopen-ticket-form")
            reopenTicketForm.setAttribute("method", "post")
            reopenTicketForm.setAttribute("action", "../actions/action_reopen_ticket.php")
            
            const reopenTicketID = document.createElement("input")
            reopenTicketID.setAttribute("type", "hidden")
            reopenTicketID.setAttribute("id", "ticket-id")
            reopenTicketID.setAttribute("name", "ticketID")
            reopenTicketID.setAttribute("value", ticketID)
            reopenTicketForm.appendChild(reopenTicketID)

            const reopenTicketCSRF = document.createElement("input")
            reopenTicketCSRF.setAttribute("type", "hidden")
            reopenTicketCSRF.setAttribute("name", "csrf")
            reopenTicketCSRF.setAttribute("value", json['csrf'])
            reopenTicketForm.appendChild(reopenTicketCSRF)

            const reopenTicketButton = document.createElement("button")
            reopenTicketButton.setAttribute("id", "reopen-ticket")
            reopenTicketButton.setAttribute("type", "submit")
            reopenTicketButton.textContent = "Reopen Ticket"
            reopenTicketForm.appendChild(reopenTicketButton)
            
            const prioritySpan = document.createElement("span")
            prioritySpan.setAttribute("id", "priority")
            prioritySpan.textContent = "Priority: " + (json['priority'] ?? "None")
            
            const departmentSpan = document.createElement("span")
            departmentSpan.setAttribute("id", "department")
            departmentSpan.textContent = "Department: " + (json['department'] ?? "None")
            
            const agentSpan = document.createElement("span")
            agentSpan.setAttribute("id", "agent")
            agentSpan.textContent = "Agent: " + (json['agent'] ?? "None")

            const hashtagsDiv = document.createElement('div')
            hashtagsDiv.setAttribute("id", 'hashtags')
            const hashtagsLegend = document.createElement('legend')
            hashtagsLegend.textContent = 'Hashtags'
            hashtagsDiv.appendChild(hashtagsLegend)

            const hashtagsList = document.createElement('ul')
            json['hashtags'].forEach(function(hashtag) {
              const hashtagLi = document.createElement('li')
              hashtagLi.classList.add('hashtag')
              hashtagLi.textContent = hashtag
              hashtagsList.appendChild(hashtagLi)
            })
            hashtagsDiv.appendChild(hashtagsList)
            
            const ticketInfoSection = document.querySelector("#ticket-info")
            ticketInfoSection.appendChild(prioritySpan)
            ticketInfoSection.appendChild(hashtagsDiv)
            ticketInfoSection.appendChild(departmentSpan)
            ticketInfoSection.appendChild(agentSpan)
            ticketInfoSection.appendChild(reopenTicketForm)
        }
    })
}
