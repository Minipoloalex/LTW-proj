// !WARNING: WIP, trying to write it like prof wanted

const filterBtn = document.getElementById('filter-values');
const clearFilterBtn = document.getElementById('clear-filters');
let checkedValues = {};



if (filterBtn) {
  const cardType = filterBtn.parentElement.getAttribute('data-type');
  filterBtn.addEventListener('click', () => getFilterValues(cardType));
}

if (clearFilterBtn) {
  const cardType = filterBtn.parentElement.getAttribute('data-type');
  clearFilterBtn.addEventListener('click', () => clearFilters(cardType));
}



/*================================================================================*/
// !NOTE: Old way. Works

async function getFilterValues(cardType) {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkedValues = {};
  checkboxes.forEach(checkbox => {
    if (checkbox.checked) {
      if (checkedValues[checkbox.name]) {
        checkedValues[checkbox.name].push(checkbox.value);
      } else {
        checkedValues[checkbox.name] = [checkbox.value];
      }
    } else {
      if (!checkedValues[checkbox.name]) {
        checkedValues[checkbox.name] = [];
      }
    }
  });
  console.log(checkedValues);


  // Fetch API
  let json;
  switch (cardType) {
    case 'ticket': {
      json = await getTickets2(checkedValues);
      break;
    }
    case 'department': {
      json = await getDepartments2(checkedValues);
      break;
    }
    case 'user': {
      json = await getUsers2(checkedValues);
      break;
    }
    default: {
      console.error('Error: invalid type');
    }
  }
  if (json['error']) {
    console.error(json['error']);
    return;
  }
  console.log(json);
  const data = json;
  console.log("Filter: ", cardType);
  // getCards(data, cardType);
  getCards(data);

  /*
  console.log(res);
  if (res.ok) {
  
    const data = await res.json();

    // cardContainer.innerHTML = '';
    console.log(data);
    getCards(data);
  } else {
    console.error('Error: ' + res.status);
  }
  */
}

async function getTickets2(checkedValues, page = 0) {
  const data = {...checkedValues, page: page};
  // data['page'] = page;
  const path = '../api/api_filter_tickets.php';
  return json = await getData(path, data);
}

async function getDepartments2(checkedValues, page = 0) {
  const data = {...checkedValues, page: page};
  // data['page'] = page;
  const path = '../api/api_departments.php';
  return json = await getData(path, data);
}

async function getUsers2(checkedValues, page = 0) {
  const data = {...checkedValues, page: page};
  // data['page'] = page;
  const path = '../api/api_users.php';
  return json = await getData(path, data);
}

function clearFilters(cardType) {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
  getFilterValues(cardType);
}


/*dropdown */
const filterToggle = document.querySelector(".filter-toggle");
if (filterToggle) {
  const caretIcon = filterToggle.querySelector("i");

  filterToggle.addEventListener("click", function() {
    filterToggle.classList.toggle("clicked");
    caretIcon.classList.toggle("fa-caret-right");
    caretIcon.classList.toggle("fa-caret-down");
  });
}

const dropdowns = document.querySelectorAll("div.filter-options div");
dropdowns.forEach(dropdown => {

  const caretIcon = dropdown.querySelector("i");

  dropdown.addEventListener("click", () => {
    dropdown.classList.toggle("clicked");
    caretIcon.classList.toggle("fa-caret-right");
    caretIcon.classList.toggle("fa-caret-down");
  });
}
);