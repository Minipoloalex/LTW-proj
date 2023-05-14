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
  // window.removeEventListener("scroll", handleInfiniteScroll);
  // console.log("Removing event listener - START OF addCards");
  window.removeEventListener("scroll", handleInfiniteScroll);
  console.log("Removing event listener - START OF addCards");
  currentPage = pageIndex;
  const startRange = (pageIndex - 1) * cardIncrease;
  const endRange = currentPage == pageCount ? cardLimit : pageIndex * cardIncrease;

  queryMore(endRange);

  cardCountElem.innerHTML = endRange;

  for (let i = startRange + 1; i <= endRange; i++) {
    createCard(i);
  }
  console.log(data.tickets);
  window.addEventListener("scroll", handleInfiniteScroll);
  // window.addEventListener("scroll", window.panelHandleInfScroll);
  console.log("New scroll listener added - END OF addCards");
};

function createCard(index) {
  const card = document.createElement("div");
  const curr = data.tickets[index - 1];
  // console.log(curr);
  card.className = "card";
  card.innerHTML = `
  <article>
  <a href="../pages/individual_ticket.php?id=${curr.ticketid}">
    <header>
    <span class="card-title">${curr.title}</span><br>
    </header>
    <div>
    <label>Status:</label>
    <span class="card-info">${curr.status}</span><br>
    <label>Hashtags:</label>
    ${curr.hashtags.map(hashtag => `<span class="card-info card-hashtags">${hashtag.hashtagname}</span>`).join('<br>')}<br>
    <label>Assigned agent:</label>
    <span class="card-info">${curr.assignedagent}</span><br>
    <label>Department:</label>
    <span class="card-info">${curr.departmentName}</span><br>
    </div>
    </a>
  </article>
  `
  cardContainer.appendChild(card);
}

async function queryMore(endRange) {
  if (endRange % 12 == 0) {
    const json = await getTickets2(checkedValues, (endRange / 12) + 1);
    data.tickets = data.tickets.concat(json.tickets);
    console.log("updated data inside queryMore: ", data.tickets);
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
  // loader.toggleAttribute("hidden");
  flag = false;
  window.removeEventListener("scroll", handleInfiniteScroll);
  // window.removeEventListener("scroll", window.panelHandleInfScroll);
  console.log("Removing event listener - END OF removeInfiniteScroll");
};



if (cardContainer) {
  window.onload = async function () {
    console.log("onload");
    const tickets = await getTickets();
    getCards(tickets);
  }
}
  function getCards(content) {
    data = content;
    // cardContainer.innerHTML = '';
    // console.log("data at the beggining of the function", data.tickets);
    // const cardLimit = data.count;
    // cardTotalElem.innerHTML = cardLimit;
    // const cardIncrease = 4;
    // const pageCount = Math.ceil(cardLimit / cardIncrease);
    // let currentPage = 1;
    // if (!checkLoader() && currentPage < pageCount) { 
    //   cardContainer.after(loader);
    // }
    cardContainer.innerHTML = '';
    console.log("data at the beggining of the function", data.tickets);
    cardLimit = data.count;
    cardTotalElem.innerHTML = cardLimit;
    cardIncrease = 4;
    pageCount = Math.ceil(cardLimit / cardIncrease);
    currentPage = 1;
    if (!checkLoader() && currentPage < pageCount) { 
      cardContainer.after(loader);
    }
    /*
    const addCards = (pageIndex) => {
      // window.removeEventListener("scroll", handleInfiniteScroll);
      // console.log("Removing event listener - START OF addCards");
      window.removeEventListener("scroll", window.panelHandleInfScroll);
      console.log("Removing event listener - START OF addCards");
      currentPage = pageIndex;
      const startRange = (pageIndex - 1) * cardIncrease;
      const endRange = currentPage == pageCount ? cardLimit : pageIndex * cardIncrease;

      queryMore(endRange);

      cardCountElem.innerHTML = endRange;

      for (let i = startRange + 1; i <= endRange; i++) {
        createCard(i);
      }
      console.log(data.tickets);
      // window.addEventListener("scroll", handleInfiniteScroll);
      window.addEventListener("scroll", window.panelHandleInfScroll);
      console.log("New scroll listener added - END OF addCards");
    };
    */


    
    /*infinite scroll*/
    /*
    const removeInfiniteScroll = () => {
      loader.remove();
      // loader.toggleAttribute("hidden");
      flag = false;
      // window.removeEventListener("scroll", handleInfiniteScroll);
      window.removeEventListener("scroll", window.panelHandleInfScroll);
      console.log("Removing event listener - END OF removeInfiniteScroll");
    };
    */

    /*
    // const handleInfiniteScroll = () => {
    //   throttle(() => {
    //     const endOfPage =
    //       window.innerHeight + window.pageYOffset >= document.body.offsetHeight;
    //       if (currentPage === pageCount) {
    //         removeInfiniteScroll();
    //       }
    //       else if (endOfPage) {
    //         addCards(currentPage + 1);
    //      }
        
    //   }, 1000);
    // };
    window.panelHandleInfScroll = function handleInfiniteScroll() {
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
    */

    // getEventListeners(window);
    // console.log("Removing event listener - END OF getCards");
    // window.removeEventListener("scroll", handleInfiniteScroll);
    flag = false;
    // loader.toggleAttribute("hidden");
    addCards(currentPage);
    flag = true;
    // window.addEventListener("scroll", handleInfiniteScroll);
    // console.log("New scroll listener added - END OF getCards");
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
    console.log("Updated data inside getTickets()", data.tickets);
    return data;
  } else {
    console.error('Error: ' + res.status);
  }
}

function checkLoader(){
  const loader = document.getElementById("loader");
  if (loader) return true;
  else return false;
}

