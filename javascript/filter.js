function getFilterValues() {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  const checkedValues = {};

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

  const json = JSON.stringify(checkedValues);

  fetch('tickets.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: json
  })
  .then(response => {
    if (response.ok) {
      return response.text();
    } else {
      throw new Error('Error: ' + response.statusText);
    }
  })
  .then(responseText => {
    console.log(responseText);
  })
  .catch(error => {
    console.error(error);
  });

  /*
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'tickets.php');
  xhr.setRequestHeader('Content-Type', 'application/json');
  xhr.onload = function () {
    if (xhr.status === 200) {
      // Request was successful
      console.log(xhr.responseText);
    } else {
      // Request failed
      console.error('Error: ' + xhr.statusText);
    }
  };
  xhr.send(json);
  */

}

function clearFilters() {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
  getFilterValues();
}