// !WARNING: WIP, trying to write it like prof wanted

// const filterBtn = document.getElementById('filter-values');
// const clearFilterBtn = document.getElementById('clear-filters');

// if (filterBtn) {
//   const type = filterBtn.parentElement().getAttribute('data-type');
//   filterBtn.addEventListener('click', 
//   );
// }

// if (clearFilterBtn) {
//   clearFilterBtn.addEventListener('click', );


// }



/*================================================================================*/
// !NOTE: Old way. Works

async function getFilterValues(type) {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  const checkedValues = {};
  console.log(type);
  checkboxes.forEach(checkbox => {
    if (checkbox.checked) {
      if (checkedValues[checkbox.name]) {
        // checkedValues[checkbox.name].push(`"${checkbox.value}"`);
        checkedValues[checkbox.name].push(checkbox.value);
      } else {
        // checkedValues[checkbox.name] = [`"${checkbox.value}"`];
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
  switch (type) {
    case 'ticket': {
      json = await getTickets2(checkedValues);
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
  console.log(data);
  // cardContainer.innerHTML = '';

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
  
  console.log(typeof checkedValues);
  const data = {...checkedValues, page: page};
  // data['page'] = page;
  console.log(data);
  const path = '../api/api_filter_tickets.php';
  return json = await getData(path, data);
}

function clearFilters(type) {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
  getFilterValues(type);
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
