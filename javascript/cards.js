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
const userType = document.querySelector("body").getAttribute("data-userType");
let cardType = 'invalid';
let pageType = 'invalid';
if (title) {
  cardType = title.getAttribute("data-type");
  if (cardType === 'ticket') {
    pageType = document.getElementById('filter-values').parentElement.getAttribute("data-pageType");
  }
}
const cardWidth = 225;

function updateCardIncrease() {
  const cardsPerRow = Math.floor(cardContainer.clientWidth / cardWidth);

  if (cardsPerRow >= 4) {
    cardIncrease = (cardType === 'department') ? 8 : 4;
  } else if (cardsPerRow === 3) {
    cardIncrease = (cardType === 'department') ? 6 : 3;
  } else if (cardsPerRow === 2) {
    cardIncrease = (cardType === 'department') ? 4 : 2;
  } else {
    cardIncrease = (cardType === 'department') ? 2 : 1;
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
  if (endRange % 24 == 0) {

    switch (cardType) {
      case 'ticket': {
        const json = await getTicketsByPage(checkedValues, pageType, (endRange / 24) + 1);
        data.tickets = data.tickets.concat(json.tickets);
        data.count = json.count;
        cardLimit = data.count;
        cardTotalElem.innerHTML = cardLimit;
        break;
      }
      case 'user': {
        const json = await getUsersByPage(checkedValues, (endRange / 24) + 1);
        data.users = data.users.concat(json.users);
        data.count = json.count;
        cardLimit = data.count;
        cardTotalElem.innerHTML = cardLimit;
        break;
      }
      case 'department': {
        const json = await getDepartmentsByPage(checkedValues, (endRange / 24) + 1);
        data.departments = data.departments.concat(json.departments);
        data.count = json.count;
        cardLimit = data.count;
        cardTotalElem.innerHTML = cardLimit;
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
      window.innerHeight + window.pageYOffset >= document.documentElement.scrollHeight - 10;
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
    clearCheckBoxes();
    updateSkeletonCards();
    updateCardIncrease();
    switch (cardType) {
      case 'ticket': {
        const tickets = await getPartialTickets();
        if (tickets.count !== 0)
          getCards(tickets);
        else noValues("No tickets found");
        break;
      }
      case 'user': {
        const users = await getPartialUsers();
        if (users.count !== 0)
          getCards(users);
        else noValues("No users found");
        break;
      }
      case 'department': {
        const departments = await getPartialDepartments();
        if (departments.count !== 0)
          getCards(departments);
        else noValues("No departments found");
        break;
      }
    }
  }
}


function getCards(content) {
  data = content;
  cardContainer.innerHTML = '';
  refreshNoValues();
  if (data.count === 0) {
    noValues(`No ${cardType}s found`);
    removeInfiniteScroll();
    return;
  }
  cardLimit = data.count;
  cardTotalElem.innerHTML = cardLimit;
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
  return await getData('../api/api_ticket.php', { 'pageType': pageType });
}

async function getPartialDepartments() {
  return await getData("../api/api_department.php", {});
}

async function getPartialUsers() {
  return await getData("../api/api_user.php", {});
}

async function getAllDepartments() {
  return await getData("../api/api_department.php", {'all': true});
}

function checkLoader() {
  const loader = document.getElementById("loader");
  if (loader) return true;

  else return false;
}


const skeletonCards = document.querySelectorAll('.skeleton-card');
window.addEventListener('resize', updateSkeletonCards);

if (cardType === 'department') {
  skeletonCards.forEach((card) => {
    card.classList.add('small-card');
  });
}
function updateSkeletonCards() {
  if (skeletonCards.length > 0) {
    // Adjust the width according to your card size
    const cardsPerRow = Math.floor(cardContainer.clientWidth / cardWidth);

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


function refreshNoValues() {
  const noCards = document.getElementById("no-cards");
  noCards.classList.add("d-none");
  noCards.textContent = "";
}

function noValues(message) {
  const noCards = document.getElementById("no-cards");
  noCards.textContent = message;
  loader.remove();
  cardCountElem.innerHTML = 0;
  cardTotalElem.innerHTML = 0;
  noCards.classList.toggle("d-none");
}

function handleDeleteCard(deleteCardBtn) {
  let card;
  if (cardType === 'ticket') {
    card = deleteCardBtn.parentElement.parentElement.parentElement.parentElement;
  } else {
    card = deleteCardBtn.parentElement.parentElement.parentElement;
  }
  const cardId = card.getAttribute("data-id");

  const modal = card.querySelector(".modal");
  const modalContent = modal.querySelector(".modalContent");
  const x = modal.querySelector(".close");
  const confirm = modal.querySelector(".confirm-del");

  deleteCardBtn.addEventListener('click', async (event) => {
    event.preventDefault();
    event.stopPropagation();
    toggleModal();
    document.addEventListener('click', clickOnDocument);
  });

  x.addEventListener("click", () => {
    toggleModal();
    document.removeEventListener('click', clickOnDocument);
  });

  function toggleModal() {
    modal.classList.toggle("d-none");
  }

  function clickOnDocument(event) {
    if (modalContent.contains(event.target)) return;
    event.preventDefault();
    event.stopPropagation();
    toggleModal();
    document.removeEventListener('click', clickOnDocument);
  }

  confirm.addEventListener("click", async () => {
    const json = await deleteData(`../api/api_${cardType}.php`, { id: cardId });
    if (json['success']) {
      card.remove();
      cardCountElem.innerHTML = cardCountElem.innerHTML - 1;
      cardTotalElem.innerHTML = cardTotalElem.innerHTML - 1;
      if (cardCountElem.innerHTML == 0) {
        noValues("No tickets found");
      }
    }
  });
}