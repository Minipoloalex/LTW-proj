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

  let json;
  switch (cardType) {
    case 'ticket': {
      const pageType = filterBtn.parentElement.getAttribute('data-pageType');
      json = await getTicketsByPage(checkedValues, pageType);
      break;
    }
    case 'department': {
      json = await getDepartmentsByPage(checkedValues);
      break;
    }
    case 'user': {
      json = await getUsersByPage(checkedValues);
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
  getCards(data);
}

async function getTicketsByPage(checkedValues, pageType, page = 0) {
  const data = {...checkedValues, page: page, pageType: pageType};
  const path = '../api/api_ticket.php';
  return json = await getData(path, data);
}

async function getDepartmentsByPage(checkedValues, page = 0) {
  const data = {...checkedValues, page: page};
  const path = '../api/api_department.php';
  return json = await getData(path, data);
}

async function getUsersByPage(checkedValues, page = 0) {
  const data = {...checkedValues, page: page};
  const path = '../api/api_user.php';
  return json = await getData(path, data);
}

function clearFilters(cardType) {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
  getFilterValues(cardType);
}

const dropdownToggles = document.querySelectorAll(".dropdown-toggle");
if (dropdownToggles) {
    dropdownToggles.forEach(dropdown => {
    const caretIcon = dropdown.querySelector("i");
    dropdown.addEventListener("click", () => {
      dropdown.classList.toggle("clicked");
      caretIcon.classList.toggle("fa-caret-right");
      caretIcon.classList.toggle("fa-caret-down");
    });
  })
}
