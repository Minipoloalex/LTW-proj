const cardContainer = document.getElementById("card-container");
const cardCountElem = document.getElementById("card-count");
const cardTotalElem = document.getElementById("card-total");
const loader = document.getElementById("loader");
const title = document.getElementById("title");
let flag;
let data = {};
let throttleTimer = false;
let cardLimit;
let cardIncrease;
let currentPage;
let pageCount;
let cardType = 'invalid';
if (title) {
  cardType = title.getAttribute("data-type");
}

const addCards = (pageIndex) => {
  window.removeEventListener("scroll", handleInfiniteScroll);
  currentPage = pageIndex;
  const startRange = (pageIndex - 1) * cardIncrease;
  const endRange = currentPage == pageCount ? cardLimit : pageIndex * cardIncrease;

  queryMore(endRange);

  cardCountElem.innerHTML = endRange;

  for (let i = startRange + 1; i <= endRange; i++) {
    createCard(i);
  }
  window.addEventListener("scroll", handleInfiniteScroll);
};

function createCard(index) {
  const card = document.createElement("div");
  card.className = "card";
  console.log(cardType);
  switch (cardType) {
    case 'ticket': {
      const curr = data.tickets[index - 1];
      drawTicketCard(card, curr);
      break;
    };
    case 'user': {
      break;
    };
    case 'department': {
      const curr = data.departments[index - 1];
      // console.log(curr);
      drawDepartmentCard(card, curr);
      break;
    };
    default: {
      console.error('Error: invalid type');
    };
  };
  cardContainer.appendChild(card);
}

async function queryMore(endRange) {
  if (endRange % 12 == 0) {

    switch (cardType) {
      case 'ticket': {
        const json = await getTickets2(checkedValues, (endRange / 12) + 1);
        data.tickets = data.tickets.concat(json.tickets);
        break;
      }
      case 'user': {
        break;
      }
      case 'department': {
        const json = await getDepartments2(checkedValues, (endRange / 12) + 1);
        data.departments = data.departments.concat(json.departments);
        break;
      }
      default:
        console.error('Error: invalid type');
    }
  }
}

function throttle(callback, time) {
  if (throttleTimer) return;
  throttleTimer = true;
  setTimeout(() => {
    callback();
    throttleTimer = false;
  }, time);
}

const handleInfiniteScroll = () => {
  throttle(() => {
    const endOfPage =
      window.innerHeight + window.pageYOffset >= document.body.offsetHeight;
    if (currentPage === pageCount) {
      removeInfiniteScroll();
    }
    else if (endOfPage) {
      addCards(currentPage + 1);
    }

  }, 1000);
};

const removeInfiniteScroll = () => {
  loader.remove();
  flag = false;
  window.removeEventListener("scroll", handleInfiniteScroll);
};

if (cardContainer) {
  window.onload = async function () {
    // const type = 'ticket';
    console.log(cardType);
    switch (cardType) {
      case 'ticket': {
        const tickets = await getAllTickets();
        getCards(tickets);
        break;
      }
      case 'user': {
        const users = await getAllUsers();
        break;
      }
      case 'department': {
        const departments = await getAllDepartments();
        getCards(departments);
        break;
      }
    }
  }
}
function getCards(content) {
  data = content;
  // cardType = type;
  cardContainer.innerHTML = '';
  cardLimit = data.count;
  cardTotalElem.innerHTML = cardLimit;
  cardIncrease = 4;
  pageCount = Math.ceil(cardLimit / cardIncrease);
  currentPage = 1;
  if (!checkLoader() && currentPage < pageCount) {
    cardContainer.after(loader);
  }

  flag = false;
  addCards(currentPage);
  flag = true;
}

async function getAllTickets() {
  const response = await fetch("../api/api_filter_tickets.php");
  if (response.ok) {
    const data = await response.json();
    return data;
  } else {
    console.error('Error: ' + res.status);
  }
}

async function getAllDepartments() {
  const response = await fetch("../api/api_departments.php");
  if (response.ok) {
    const data = await response.json();
    console.log(data);
    return data;
  } else {
    console.error('Error: ' + res.status);
  }
}

function checkLoader() {
  const loader = document.getElementById("loader");
  if (loader) return true;

  else return false;
}

function drawDepartmentCard(card, curr) {
  console.log(curr);
  card.innerHTML = `
  <article>
  <header>
  <span class="card-title">${curr.departmentName}</span>
  </header>
  </article>
  `;
}


function drawTicketCard(card, curr) {
  // const article = document.createElement('article');
  // const link = document.createElement('a');
  // link.href = `../pages/individual_ticket.php?id=${curr.ticketid}`;
  // const header = document.createElement('header');
  // const title = document.createElement('span');
  // title.classList.add('card-title');
  // title.textContent = curr.title;
  // header.appendChild(title);
  // link.appendChild(header);
  // const div = document.createElement('div');
  // const statusLabel = document.createElement('label');
  // statusLabel.textContent = 'Status:';
  // div.appendChild(statusLabel);
  // const status = document.createElement('span');
  // status.classList.add('card-info');
  // status.textContent = curr.status ? curr.status : "None";
  // div.appendChild(status);
  // div.appendChild(document.createElement('br'));
  // const hashtagsLabel = document.createElement('label');
  // hashtagsLabel.textContent = 'Hashtags:';
  // div.appendChild(hashtagsLabel);
  // div.appendChild(document.createElement('br'));
  // if (curr.hashtags.length > 0) {
  //   const hashtags = curr.hashtags.map(hashtag => {
  //     const span = document.createElement('span');
  //     span.classList.add('card-info', 'card-hashtags');
  //     span.textContent = hashtag.hashtagname;
  //     return span;
  //   });
  //   hashtags.forEach(hashtag => {
  //     div.appendChild(hashtag);
  //     div.appendChild(document.createElement('br'));
  //   });
  // } else {
  //   const noHashtags = document.createElement('span');
  //   noHashtags.classList.add('card-info');
  //   noHashtags.textContent = 'None';
  //   div.appendChild(noHashtags);
  // }
  // const assignedAgentLabel = document.createElement('label');
  // assignedAgentLabel.textContent = 'Assigned agent:';
  // div.appendChild(assignedAgentLabel);
  // div.appendChild(document.createElement('br'));
  // const assignedAgent = document.createElement('span');
  // assignedAgent.classList.add('card-info');
  // assignedAgent.textContent = curr.assignedagent ? curr.assignedagent : "None";
  // div.appendChild(assignedAgent);
  // div.appendChild(document.createElement('br'));
  // const departmentLabel = document.createElement('label');
  // departmentLabel.textContent = 'Department:';
  // div.appendChild(departmentLabel);
  // const department = document.createElement('span');
  // department.classList.add('card-info');
  // department.textContent = curr.departmentName ? curr.departmentName : "Not defined";
  // div.appendChild(department);
  // div.appendChild(document.createElement('br'));
  // const priorityLabel = document.createElement('label');
  // priorityLabel.textContent = 'Priority:';
  // div.appendChild(priorityLabel);
  // const priority = document.createElement('span');
  // priority.classList.add('card-info', 'card-priority');
  // priority.textContent = curr.priority ? curr.priority : "Not defined";
  // div.appendChild(priority);
  // link.appendChild(div);
  // article.appendChild(link);
  // card.appendChild(article);
  // console.log(card);

  // // Add class to set background color based on priority value
  // if (curr.priority === 'high') {
  //   card.querySelector('.card-priority').classList.add('highP');
  // } else if (curr.priority === 'medium') {
  //   card.querySelector('.card-priority').classList.add('mediumP');
  // } else if (curr.priority === 'low') {
  //   card.querySelector('.card-priority').classList.add('lowP');
  // }
  // else if (curr.priority === null) {
  //   card.querySelector('.card-priority').classList.add('noneP');
  // }

  //   card.innerHTML = `
  //   <article>
  //   <a href="../pages/individual_ticket.php?id=${curr.ticketid}">
  //     <header>
  //       <span class="card-title">${curr.title}</span>
  //     </header>
  //     <div>
  //       <label class="status">Status:</label>
  //       <span class="card-info card-status">${curr.status ? curr.status : "None"}</span><br>


  //       <label class="hashtags">Hashtags:</label><br>
  //     <div class="hashtags-container">
  //       ${curr.hashtags.length > 0 ?
  //       curr.hashtags.map(hashtag => `<span class="card-info card-hashtags">${hashtag.hashtagname}</span>`).join('<br>') :
  //       '<span class="card-info">None</span>'}
  //     </div>

  //       <label class="agent">Assigned agent:</label><br>
  //       <span class="card-info card-agent">${curr.assignedagent ? curr.assignedagent : "None"}</span><br>
  //       <label class="deparment">Department:</label>
  //       <span class="card-info card-department">${curr.departmentName ? curr.departmentName : "Not defined"}</span><br>
  //       <label class=priority">Priority:</label>
  //       <span class="card-info card-priority">${curr.priority ? curr.priority : "Not defined"}</span><br>
  //     </div>
  //   </a>
  // </article>
  //   `

  // const card = document.createElement("div");
  // card.classList.add("card");

  const article = document.createElement("article");

  const link = document.createElement("a");
  link.href = `../pages/individual_ticket.php?id=${curr.ticketid}`;

  const header = document.createElement("header");
  const titleSpan = document.createElement("span");
  titleSpan.classList.add("card-title");
  titleSpan.textContent = curr.title;
  header.appendChild(titleSpan);

  const contentDiv = document.createElement("div");

  const statusLabel = document.createElement("label");
  statusLabel.classList.add("status");
  statusLabel.textContent = "Status: ";
  contentDiv.appendChild(statusLabel);

  const statusSpan = document.createElement("span");
  statusSpan.classList.add("card-info", "card-status");
  statusSpan.textContent = curr.status ? curr.status : "None";
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
      hashtagSpan.textContent = hashtag.hashtagname;
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
  agentSpan.textContent = curr.assignedagent ? curr.assignedagent : "None";
  contentDiv.appendChild(agentSpan);
  contentDiv.appendChild(document.createElement("br"));

  const departmentLabel = document.createElement("label");
  departmentLabel.classList.add("department");
  departmentLabel.textContent = "Department: ";
  contentDiv.appendChild(departmentLabel);

  const departmentSpan = document.createElement("span");
  departmentSpan.classList.add("card-info", "card-department");
  departmentSpan.textContent = curr.departmentName ? curr.departmentName : "Not defined";
  contentDiv.appendChild(departmentSpan);
  contentDiv.appendChild(document.createElement("br"));

  const priorityLabel = document.createElement("label");
  priorityLabel.classList.add("priority");
  priorityLabel.textContent = "Priority: ";
  contentDiv.appendChild(priorityLabel);

  const prioritySpan = document.createElement("span");
  prioritySpan.classList.add("card-info", "card-priority");
  prioritySpan.textContent = curr.priority ? curr.priority : "Not defined";
  contentDiv.appendChild(prioritySpan);
  contentDiv.appendChild(document.createElement("br"));

  link.appendChild(header);
  link.appendChild(contentDiv);
  article.appendChild(link);
  card.appendChild(article);

  console.log(card);

  // Add class to set background color based on priority value
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
