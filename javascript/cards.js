const cardContainer = document.getElementById("card-container");
const cardCountElem = document.getElementById("card-count");
const cardTotalElem = document.getElementById("card-total");
const loader = document.getElementById("loader");

if (cardContainer) {
  window.onload = async function () {
    const tickets = await getTickets();
    getCards(tickets);
  }

  function getCards(data) {
    console.log(data);
    console.log(data.length);
    const cardLimit = data.length; 99;
    cardTotalElem.innerHTML = cardLimit;
    const cardIncrease = 9;
    const pageCount = Math.ceil(cardLimit / cardIncrease);
    let currentPage = 1;

      
    // not needed
    const getRandomColor = () => {
      const h = Math.floor(Math.random() * 360);
      return `hsl(${h}deg, 90%, 85%)`;
    };

    const createCard = (index) => {
      const card = document.createElement("div");
      card.className = "card";
      card.innerHTML = data[index-1].title; //index; //card inside elements
      card.style.backgroundColor = getRandomColor();
      cardContainer.appendChild(card);
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

    addCards(currentPage);
    window.addEventListener("scroll", handleInfiniteScroll);
  }
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