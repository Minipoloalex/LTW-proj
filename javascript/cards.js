const cardContainer = document.getElementById("card-container");
const cardCountElem = document.getElementById("card-count");
const cardTotalElem = document.getElementById("card-total");
const loader = document.getElementById("loader");
let flag;

// !TODO: descobrir maneira de arranjar tipo dos dados (ticket, user, etc)

if (cardContainer) {
  window.onload = async function () {
    const tickets = await getTickets();
    getCards(tickets);
  }

  function getCards(data) {
    console.log(flag);
    cardContainer.innerHTML = '';

    const cardLimit = data.count; //99;
    cardTotalElem.innerHTML = cardLimit;
    const cardIncrease = 4;
    const pageCount = Math.ceil(cardLimit / cardIncrease);
    let currentPage = 1;
    if (!checkLoader() && currentPage < pageCount) {
      console.log("new loader", loader);
      cardContainer.after(loader);
    }

    const createCard = (index) => {
      const card = document.createElement("div");
      const curr = data.tickets[index - 1];
      console.log(curr);
      card.className = "card";
      card.innerHTML = `
      <article>
  <a href="../pages/individual_ticket.php?id=${curr.ticketid}">
    <header>
      <span class="card-title">${curr.title}</span><br>
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
      else if(curr.priority === null){
        card.querySelector('.card-priority').classList.add('noneP');
      }

      cardContainer.appendChild(card);
    };

    const addCards = (pageIndex) => {
      currentPage = pageIndex;
      console.log(currentPage);
      const startRange = (pageIndex - 1) * cardIncrease;
      const endRange = currentPage == pageCount ? cardLimit : pageIndex * cardIncrease;

      cardCountElem.innerHTML = endRange;

      for (let i = startRange + 1; i <= endRange; i++) {
        createCard(i);
      }
    };

    // window.onload = function () {
    //   addCards(currentPage);
    // };


    /*optimization*/
    let throttleTimer;
    const throttle = (callback, time) => {
      if (throttleTimer) return;
      throttleTimer = true;
      setTimeout(() => {
        callback();
        throttleTimer = false;
      }, time);
    };


    /*infinite scroll*/
    const removeInfiniteScroll = () => {
      loader.remove();
      // loader.toggleAttribute("hidden");
      flag = false;
      window.removeEventListener("scroll", handleInfiniteScroll);
    };

    const handleInfiniteScroll = () => {
      throttle(() => {
        const endOfPage =
          window.innerHeight + window.pageYOffset >= document.body.offsetHeight;
        if (endOfPage) {
          addCards(currentPage + 1);
        }
        if (currentPage === pageCount) {
          removeInfiniteScroll();
        }
      }, 1000);
    };

    // getEventListeners(window);
    window.removeEventListener("scroll", handleInfiniteScroll);
    flag = false;
    // loader.toggleAttribute("hidden");
    addCards(currentPage);
    flag = true;
    window.addEventListener("scroll", handleInfiniteScroll);
    console.log(flag);
  }
}

// function buildCard(obj) {
//   <div class="card">
//     <article>
//       <span class="card-title"></span>
//       <span class="card-info"></span>
//       <span class="card-info card-description"></span>
//       <span class="card-info card-hashtags"></span>

//     </article>
//   </div>
// }

async function getTickets() {
  const response = await fetch("../api/api_filter_tickets.php");
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

