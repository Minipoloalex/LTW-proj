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
const cardWidth = 225;

function updateCardIncrease() {
  console.log(cardContainer.clientWidth);
  const cardsPerRow = Math.floor(cardContainer.clientWidth / cardWidth); // Adjust the width (200) according to your card size

  if (cardsPerRow >= 4) {
    cardIncrease = 4;
  } else if (cardsPerRow === 3) {
    cardIncrease = 3;
  } else if (cardsPerRow === 2) {
    cardIncrease = 2;
  } else {
    cardIncrease = 1;
  }
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
  card.classList.add("card");
  console.log(cardType);
  switch (cardType) {
    case 'ticket': {
      const curr = data.tickets[index - 1];
      drawTicketCard(card, curr);
      break;
    };
    case 'user': {
      const curr = data.users[index - 1];
      drawUserCard(card, curr);
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
        const json = await getUsers2(checkedValues, (endRange / 12) + 1);
        data.users = data.users.concat(json.users);
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

// const handleInfiniteScroll = () => {
//   throttle(() => {
//     const endOfPage =
//       window.innerHeight + window.pageYOffset >= document.body.offsetHeight;
//     if (currentPage === pageCount) {
//       removeInfiniteScroll();
//     }
//     else if (endOfPage) {
//       addCards(currentPage + 1);
//     }

//   }, 1000);
// };

// const handleInfiniteScroll = () => {
//   throttle(() => {
//     const endOfPage =
//       window.innerHeight + window.pageYOffset >= document.documentElement.scrollHeight;
//     if (currentPage === pageCount) {
//       removeInfiniteScroll();
//     } else if (endOfPage) {
//       addCards(currentPage + 1);
//     }
//   }, 1000);
// };

const handleInfiniteScroll = () => {
  throttle(() => {
    const endOfPage =
      window.innerHeight + window.pageYOffset >= document.documentElement.scrollHeight-10;
    if (currentPage === pageCount) {
      removeInfiniteScroll();
    } else if (endOfPage) {
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
    updateCardIncrease();
    switch (cardType) {
      case 'ticket': {
        const tickets = await getPartialTickets();
        getCards(tickets);
        break;
      }
      case 'user': {
        const users = await getPartialUsers();
        getCards(users);
        break;
      }
      case 'department': {
        const departments = await getPartialDepartments();
        getCards(departments);
        break;
      }
    }
  }
}

// window.addEventListener('resize', updateCardIncrease);

function getCards(content) {
  data = content;
  // cardType = type;
  cardContainer.innerHTML = '';
  cardLimit = data.count;
  cardTotalElem.innerHTML = cardLimit;
  // cardIncrease = 4;
  pageCount = Math.ceil(cardLimit / cardIncrease);
  currentPage = 1;
  if (!checkLoader() && currentPage < pageCount) {
    cardContainer.after(loader);
  }

  flag = false;
  addCards(currentPage);
  flag = true;
}

async function getPartialTickets() {
  const response = await fetch("../api/api_filter_tickets.php");
  if (response.ok) {
    const data = await response.json();
    return data;
  } else {
    console.error('Error: ' + res.status);
  }
}

async function getPartialDepartments() {
  const response = await fetch("../api/api_departments.php");
  if (response.ok) {
    const data = await response.json();
    console.log(data);
    return data;
  } else {
    console.error('Error: ' + response.status);
  }
}

async function getPartialUsers() {
  const response = await fetch("../api/api_users.php");
  if (response.ok) {
    const data = await response.json();
    console.log(data);
    return data;
  } else {
    console.error('Error: ' + response.status);
  }
}

async function getAllDepartments() {
  const response = await fetch("../api/api_departments.php?all=true");
  if (response.ok) {
    const data = await response.json();
    console.log("All deps: ", data);
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


async function drawUserCard(card, curr) {
  console.log(curr);
  const deps = await getAllDepartments();

  card.innerHTML = `
    <article>
      <header>
        <span class="card-title">${curr.name}</span>
      </header>

      <div>
        <label>Username: </label>
        <span class="card-info">${curr.username}</span><br>

        <label>Email: </label>
        <span class="card-info">${curr.email}</span><br>

        
        <label>Department: </label>
        <select class="department-select" ${curr.user_type === 'Client' ? "disabled" : ""}>
          <option value=''>None</option>
          ${curr.user_type !== 'Client' ?
      deps.map(dep => `<option value="${dep.departmentId}" ${curr.department === dep.departmentName ? 'selected' : ''}>${dep.departmentName}</option>`).join('') :
      ''}
          </select><br>
      

        <label>Role: </label>
        <select class="user-type-select">
          <option value="Client" ${curr.user_type === 'Client' ? 'selected' : ''}>Client</option>
          <option value="Agent" ${curr.user_type === 'Agent' ? 'selected' : ''}>Agent</option>
          <option value="Admin" ${curr.user_type === 'Admin' ? 'selected' : ''}>Admin</option>
        </select><br>

        <label>Created tickets: </label>
        <span class="card-info">${curr.nr_tickets_created}</span><br>

        <label>Solved tickets: </label>
        <span class="card-info">${curr.nr_tickets_assigned}</span><br>
      </div>
    </article>
  `;

  const departmentSelect = card.querySelector('.department-select');
  const userTypeSelect = card.querySelector('.user-type-select');

  departmentSelect.addEventListener('change', async function () {
    console.log("Department changed");
    const json = await patchData('../api/api_users.php', { id: curr.id, user_type: userTypeSelect.value, department: departmentSelect.value });
    console.log(json);
  });


  userTypeSelect.addEventListener('change', async function () {
    console.log("User type changed");
    // Handle department select based on user type
    // const departmentSelect = card.querySelector('.department-select');
    if (typeSelectedValue === 'Client') {
      departmentSelect.value = ''; // Select the "None" option
      departmentSelect.disabled = true; // Disable the department select
    } else {
      departmentSelect.disabled = false; // Enable the department select
      departmentSelect.innerHTML = `
      <option value="">None</option>
      ${deps.map(dep => `<option value="${dep.departmentId}" ${curr.department === dep.departmentName ? 'selected' : ''}>${dep.departmentName}</option>`).join('')}
    `; // Update the department select options
    }

    const json = await patchData('../api/api_users.php', { id: curr.id, user_type: userTypeSelect.value, department: departmentSelect.value });
    console.log(json);
  });


}



function drawDepartmentCard(card, curr) {
  card.classList.add("small-card");
  console.log(curr);
  card.innerHTML = `
  <article>
  <header>
  <span class="card-title">${curr.departmentName}</span>
  </header>

  <div>
  <label>Number of tickets:</label>
  <span class="card-info">${curr.nrTickets}</span><br>

  <label>Number of agents:</label>
  <span class="card-info">${curr.nrAgents}</span><br>
  
  </article>
  `;
}


function drawTicketCard(card, curr) {

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


const skeletonCards = document.querySelectorAll('.skeleton-card');
window.addEventListener('resize', updateSkeletonCards);

if(cardType === 'department') {
  skeletonCards.forEach((card) => {
    card.classList.add('small-card');
  });
}
function updateSkeletonCards() {
  if (skeletonCards) {
    const cardsPerRow = Math.floor(cardContainer.clientWidth / cardWidth); // Adjust the width (200) according to your card size
    console.log(cardsPerRow);
    // Hide all skeleton cards
    skeletonCards.forEach((card) => {
      card.classList.add('d-none');
    });

    // Show the required number of skeleton cards
    for (let i = 0; i < cardsPerRow; i++) {
      if (skeletonCards[i]) {
        skeletonCards[i].classList.remove('d-none');
      }
    }
  }
}

updateSkeletonCards(); // Call the function on page load
