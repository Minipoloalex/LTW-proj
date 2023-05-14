const cardContainer = document.getElementById("card-container");
const cardCountElem = document.getElementById("card-count");
const cardTotalElem = document.getElementById("card-total");
const loader = document.getElementById("loader");
let flag;
let data;
let throttleTimer = false;
let cardLimit;
let cardIncrease;
let currentPage;
let pageCount;

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
  const curr = data.tickets[index - 1];
  card.className = "card";
  card.innerHTML = `
  <article>
  <a href="../pages/individual_ticket.php?id=${curr.ticketid}">
    <header>
      <span class="card-title">${curr.title}</span>
    </header>
    <div>
      <label>Status:</label>
      <span class="card-info">${curr.status ? curr.status : "None"}</span><br>
      
      <label>Hashtags:</label>
      ${curr.hashtags.length > 0 ?
      curr.hashtags.map(hashtag => `<span class="card-info card-hashtags">${hashtag.hashtagname}</span>`).join('<br>') :
      '<span class="card-info">None</span>'}
      <br>
      <label>Assigned agent:</label>
      <span class="card-info">${curr.assignedagent ? curr.assignedagent : "None"}</span><br>
      <label>Department:</label>
      <span class="card-info">${curr.departmentName ? curr.departmentName : "Not defined"}</span><br>
      <label>Priority:</label>
      <span class="card-info card-priority">${curr.priority ? curr.priority : "Not defined"}</span><br>
    </div>
  </a>
</article>
  `
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

  cardContainer.appendChild(card);
}

async function queryMore(endRange) {
  if (endRange % 12 == 0) {
    const json = await getTickets2(checkedValues, (endRange / 12) + 1);
    data.tickets = data.tickets.concat(json.tickets);
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
    const tickets = await getTickets();
    getCards(tickets);
  }
}
function getCards(content) {
  data = content;
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

async function getTickets() {
  const response = await fetch("../api/api_filter_tickets.php");
  if (response.ok) {
    const data = await response.json();
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

