/* ----- DRAW CARDS ----- */
async function drawUserCard(card, curr) {
  const deps = await getAllDepartments();

  card.classList.add("card");
  card.setAttribute("data-id", curr.id);
  card.setAttribute("data-type", cardType);
  const article = document.createElement("article");

  const header = document.createElement("header");
  const titleSpan = document.createElement("span");
  titleSpan.classList.add("card-title");
  setTextContent(titleSpan, curr.name);
  header.appendChild(titleSpan);

  const contentDiv = document.createElement("div");

  const usernameLabel = document.createElement("label");
  usernameLabel.textContent = "Username: ";
  contentDiv.appendChild(usernameLabel);
  contentDiv.appendChild(document.createElement("br"));

  const usernameSpan = document.createElement("span");
  usernameSpan.classList.add("card-info");
  setTextContent(usernameSpan, curr.username);
  contentDiv.appendChild(usernameSpan);
  contentDiv.appendChild(document.createElement("br"));

  const emailLabel = document.createElement("label");
  emailLabel.textContent = "Email: ";
  contentDiv.appendChild(emailLabel);
  contentDiv.appendChild(document.createElement("br"));

  const emailSpan = document.createElement("span");
  emailSpan.classList.add("card-info", "card-email");
  setTextContent(emailSpan, curr.email);
  contentDiv.appendChild(emailSpan);
  contentDiv.appendChild(document.createElement("br"));

  const departmentLabel = document.createElement("label");
  departmentLabel.textContent = "Department: ";
  contentDiv.appendChild(departmentLabel);

  const departmentSelect = document.createElement("select");
  departmentSelect.classList.add("department-select");
  departmentSelect.disabled = curr.user_type === 'Client';
  departmentSelect.innerHTML = `
    <option value="">None</option>
    ${curr.user_type !== 'Client' ?
      deps.map(dep => `<option value="${dep.departmentId}" ${curr.department === dep.departmentName ? 'selected' : ''}>${dep.departmentName}</option>`).join('') :
      deps.map(dep => `<option value="${dep.departmentId}">${dep.departmentName}</option>`).join('')}
    `;
  contentDiv.appendChild(departmentSelect);
  contentDiv.appendChild(document.createElement("br"));

  const roleLabel = document.createElement("label");
  roleLabel.textContent = "Role: ";
  contentDiv.appendChild(roleLabel);

  const userTypeSelect = document.createElement("select");
  userTypeSelect.classList.add("user-type-select");
  userTypeSelect.innerHTML = `
    <option value="Client" ${curr.user_type === 'Client' ? 'selected' : ''}>Client</option>
    <option value="Agent" ${curr.user_type === 'Agent' ? 'selected' : ''}>Agent</option>
    <option value="Admin" ${curr.user_type === 'Admin' ? 'selected' : ''}>Admin</option>
    `;
  contentDiv.appendChild(userTypeSelect);
  contentDiv.appendChild(document.createElement("br"));

  const nrTicketsCreatedLabel = document.createElement("label");
  nrTicketsCreatedLabel.textContent = "Created tickets: ";
  contentDiv.appendChild(nrTicketsCreatedLabel);

  const nrTicketsCreatedSpan = document.createElement("span");
  nrTicketsCreatedSpan.classList.add("card-info");
  setTextContent(nrTicketsCreatedSpan, curr.nr_tickets_created);
  contentDiv.appendChild(nrTicketsCreatedSpan);
  contentDiv.appendChild(document.createElement("br"));

  const nrTicketsAssignedLabel = document.createElement("label");
  nrTicketsAssignedLabel.textContent = "Solved tickets: ";
  contentDiv.appendChild(nrTicketsAssignedLabel);

  const nrTicketsAssignedSpan = document.createElement("span");
  nrTicketsAssignedSpan.classList.add("card-info");
  nrTicketsAssignedSpan.textContent = curr.nr_tickets_assigned;
  contentDiv.appendChild(nrTicketsAssignedSpan);
  contentDiv.appendChild(document.createElement("br"));

  article.appendChild(header);
  article.appendChild(contentDiv);

  let deleteCardBtn;
  if (userType === 'Admin') {
    deleteCardBtn = document.createElement("button");
    deleteCardBtn.classList.add("delete-faq");
    deleteCardBtn.classList.add("delete-card");
    deleteCardBtn.classList.add("openModal");
    deleteCardBtn.innerHTML = '<span class="material-symbols-outlined">delete</span>';
    contentDiv.appendChild(deleteCardBtn);

    const modal = document.createElement("div");
    modal.classList.add("modal");
    modal.classList.add("d-none");

    const modalContent = document.createElement("div");
    modalContent.classList.add("modalContent");

    const closeButton = document.createElement("span");
    closeButton.classList.add("close");
    closeButton.textContent = '×';
    modalContent.appendChild(closeButton);

    const message = document.createElement("p");
    message.textContent = "Are you sure you want to delete this user?";
    modalContent.appendChild(message);

    const deleteButtonModal = document.createElement("button");
    deleteButtonModal.classList.add("confirm-del");
    deleteButtonModal.textContent = "Delete";
    modalContent.appendChild(deleteButtonModal);

    modal.appendChild(modalContent);

    article.appendChild(modal);
  }

  card.appendChild(article);

  departmentSelect.addEventListener('change', async function () {
    const json = await patchData('../api/api_user.php', { id: curr.id, user_type: userTypeSelect.value, department: departmentSelect.value });
    departmentSelect.innerHTML = `
      <option value="">None</option>
      ${deps.map(dep => `<option value="${dep.departmentId}" ${json['department'] === dep.departmentName ? 'selected' : ''}>${dep.departmentName}</option>`).join('')}`;
  });


  userTypeSelect.addEventListener('change', async function () {
    if (userTypeSelect.value === 'Client') {
      departmentSelect.value = '';
      departmentSelect.disabled = true;
    } else {
      departmentSelect.disabled = false;
    }
    await patchData('../api/api_user.php', { id: curr.id, user_type: userTypeSelect.value, department: departmentSelect.value });

  });

  if (userType === 'Admin') handleDeleteCard(deleteCardBtn);
}

function drawDepartmentCard(card, curr) {
  card.classList.add("small-card");
  card.setAttribute("data-id", curr.departmentId);
  card.setAttribute("data-type", cardType);
  const article = document.createElement("article");

  const header = document.createElement("header");
  const titleSpan = document.createElement("span");
  titleSpan.classList.add("card-title");
  setTextContent(titleSpan, curr.departmentName);
  header.appendChild(titleSpan);

  const contentDiv = document.createElement("div");

  const nrTicketsLabel = document.createElement("label");
  nrTicketsLabel.textContent = "Number of tickets: ";
  contentDiv.appendChild(nrTicketsLabel);

  const nrTicketsSpan = document.createElement("span");
  nrTicketsSpan.classList.add("card-info");
  setTextContent(nrTicketsSpan, curr.nrTickets);
  contentDiv.appendChild(nrTicketsSpan);
  contentDiv.appendChild(document.createElement("br"));

  const nrAgentsLabel = document.createElement("label");
  nrAgentsLabel.textContent = "Number of agents: ";
  contentDiv.appendChild(nrAgentsLabel);

  const nrAgentsSpan = document.createElement("span");
  nrAgentsSpan.classList.add("card-info");
  setTextContent(nrAgentsSpan, curr.nrAgents);
  contentDiv.appendChild(nrAgentsSpan);
  contentDiv.appendChild(document.createElement("br"));

  article.appendChild(header);
  article.appendChild(contentDiv);

  let deleteCardBtn
  if (userType === 'Admin') {
    deleteCardBtn = document.createElement('button');
    deleteCardBtn.classList.add('delete-faq');
    deleteCardBtn.classList.add('delete-card');
    deleteCardBtn.classList.add('openModal');
    deleteCardBtn.innerHTML = '<span class="material-symbols-outlined">delete</span>';
    contentDiv.appendChild(deleteCardBtn);

    const modal = document.createElement("div");
    modal.classList.add("modal");
    modal.classList.add("d-none");

    const modalContent = document.createElement("div");
    modalContent.classList.add("modalContent");

    const closeButton = document.createElement("span");
    closeButton.classList.add("close");
    closeButton.textContent = '×';
    modalContent.appendChild(closeButton);

    const message = document.createElement("p");
    message.textContent = "Are you sure you want to delete this department?";
    modalContent.appendChild(message);

    const deleteButtonModal = document.createElement("button");
    deleteButtonModal.classList.add("confirm-del");
    deleteButtonModal.textContent = "Delete";
    modalContent.appendChild(deleteButtonModal);

    modal.appendChild(modalContent);
    
    article.appendChild(modal);
  }

  card.appendChild(article);

  if (userType == "Admin") handleDeleteCard(deleteCardBtn);
}

function drawTicketCard(card, curr) {
  card.classList.add("hover-card");
  card.setAttribute("data-id", curr.ticketid);
  card.setAttribute("data-type", cardType);
  const article = document.createElement("article");

  const link = document.createElement("a");
  link.href = `../pages/individual_ticket.php?id=${curr.ticketid}`;

  const header = document.createElement("header");
  const titleSpan = document.createElement("span");
  titleSpan.classList.add("card-title");
  setTextContent(titleSpan, curr.title);
  header.appendChild(titleSpan);

  const contentDiv = document.createElement("div");

  const statusLabel = document.createElement("label");
  statusLabel.classList.add("status");
  statusLabel.textContent = "Status: ";
  contentDiv.appendChild(statusLabel);

  const statusSpan = document.createElement("span");
  statusSpan.classList.add("card-info", "card-status");
  setTextContent(statusSpan, curr.status ? curr.status : "None");
  contentDiv.appendChild(statusSpan);
  contentDiv.appendChild(document.createElement("br"));

  const hashtagsLabel = document.createElement("label");
  hashtagsLabel.classList.add("hashtags");
  hashtagsLabel.textContent = "Hashtags: ";
  contentDiv.appendChild(hashtagsLabel);
  contentDiv.appendChild(document.createElement("br"));

  const hashtagsContainerDiv = document.createElement("div");
  hashtagsContainerDiv.classList.add("hashtags-container");
  if (curr.hashtags.length > 0) {
    curr.hashtags.forEach(hashtag => {
      const hashtagSpan = document.createElement("span");
      hashtagSpan.classList.add("card-info", "card-hashtags");
      setTextContent(hashtagSpan, hashtag.hashtagname);
      hashtagsContainerDiv.appendChild(hashtagSpan);
      hashtagsContainerDiv.appendChild(document.createElement("br"));
    });
  } else {
    const noneSpan = document.createElement("span");
    noneSpan.classList.add("card-info");
    noneSpan.textContent = "None";
    hashtagsContainerDiv.appendChild(noneSpan);
  }
  contentDiv.appendChild(hashtagsContainerDiv);

  const agentLabel = document.createElement("label");
  agentLabel.classList.add("agent");
  agentLabel.textContent = "Assigned agent: ";
  contentDiv.appendChild(agentLabel);
  contentDiv.appendChild(document.createElement("br"));

  const agentSpan = document.createElement("span");
  agentSpan.classList.add("card-info", "card-agent");
  setTextContent(agentSpan, curr.assignedagent ? curr.assignedagent : "None");
  contentDiv.appendChild(agentSpan);
  contentDiv.appendChild(document.createElement("br"));

  const departmentLabel = document.createElement("label");
  departmentLabel.classList.add("department");
  departmentLabel.textContent = "Department: ";
  contentDiv.appendChild(departmentLabel);

  const departmentSpan = document.createElement("span");
  departmentSpan.classList.add("card-info", "card-department");
  setTextContent(departmentSpan, curr.departmentName ? curr.departmentName : "Not defined");
  contentDiv.appendChild(departmentSpan);
  contentDiv.appendChild(document.createElement("br"));

  const priorityLabel = document.createElement("label");
  priorityLabel.classList.add("priority");
  priorityLabel.textContent = "Priority: ";
  contentDiv.appendChild(priorityLabel);

  const prioritySpan = document.createElement("span");
  prioritySpan.classList.add("card-info", "card-priority");
  setTextContent(prioritySpan, curr.priority ? curr.priority : "Not defined");
  contentDiv.appendChild(prioritySpan);
  contentDiv.appendChild(document.createElement("br"));

  link.appendChild(header);
  link.appendChild(contentDiv);
  article.appendChild(link);

  let deleteCardBtn;
  if (userType === 'Admin') {
    deleteCardBtn = document.createElement('button');
    deleteCardBtn.classList.add('delete-faq');
    deleteCardBtn.classList.add('delete-card');
    deleteCardBtn.classList.add('openModal');
    deleteCardBtn.innerHTML = '<span class="material-symbols-outlined">delete</span>';
    contentDiv.appendChild(deleteCardBtn);

    const modal = document.createElement("div");
    modal.classList.add("modal");
    modal.classList.add("d-none");

    const modalContent = document.createElement("div");
    modalContent.classList.add("modalContent");

    const closeButton = document.createElement("span");
    closeButton.classList.add("close");
    closeButton.textContent = '×';
    modalContent.appendChild(closeButton);

    const message = document.createElement("p");
    message.textContent = "Are you sure you want to delete this ticket?";
    modalContent.appendChild(message);

    const deleteButtonModal = document.createElement("button");
    deleteButtonModal.classList.add("confirm-del");
    deleteButtonModal.textContent = "Delete";
    modalContent.appendChild(deleteButtonModal);

    modal.appendChild(modalContent);

    article.appendChild(modal);
  }

  card.appendChild(article);

  if (userType === 'Admin') handleDeleteCard(deleteCardBtn);

  if (curr.priority === 'high') {
    card.querySelector('.card-priority').classList.add('highP');
  } else if (curr.priority === 'medium') {
    card.querySelector('.card-priority').classList.add('mediumP');
  } else if (curr.priority === 'low') {
    card.querySelector('.card-priority').classList.add('lowP');
  }
  else if (curr.priority === null) {
    card.querySelector('.card-priority').classList.add('noneP');
  }
}
