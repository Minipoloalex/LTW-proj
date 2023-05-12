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
    console.log(data);
    
    const cardLimit = data.length; //99;
    cardTotalElem.innerHTML = cardLimit;
    const cardIncrease = 4;
    const pageCount = Math.ceil(cardLimit / cardIncrease);
    let currentPage = 1;
    if (!checkLoader() && currentPage < pageCount) { 
      console.log("new loader", loader);
      cardContainer.after(loader);
    }

    // not needed
    // const getRandomColor = () => {
    //   const h = Math.floor(Math.random() * 360);
    //   return `hsl(${h}deg, 90%, 85%)`;
    // };

    const createCard = (index) => {
      const card = document.createElement("div");
      const curr = data[index - 1];
      console.log(curr);
      card.className = "card";
      card.innerHTML = `
      <article>
        <header>
        <span class="card-title">${curr. title }</span><br>
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
      </article>
      `

      // card.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
      // card.style.borderRadius = '10px';
      // card.style.height = '300px';
      // card.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.3);';
      // card.style.padding = '10px';
      // card.style.margin = '10px';
      // card.style.width = '300px';
      cardContainer.appendChild(card);


      //index; //card inside elements
      // card.innerHTML += data[index-1].description;
      // card.innerHTML += data[index-1].date;
      // card.innerHTML += data[index-1].status;
      // card.innerHTML += data[index-1].priority;
    };

    const addCards = (pageIndex) => {
      currentPage = pageIndex;

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

