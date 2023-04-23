async function getFilterValues() {
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

  // Fetch API
  // const res = await fetch('tickets.php', {
  const res = await fetch('../api/api_filter_tickets.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: json
  })
  // .then(response => {
  //   if (response.ok) {
  //     return response.text();
  //   } else {
  //     throw new Error('Error: ' + response.statusText);
  //   }
  // })
  // .then(responseText => {
  //   console.log(responseText);
  // })
  // .catch(error => {
  //   console.error(error);
  // });
  if (res.ok) {
    const tickets = await res.json();
    const tableData = document.querySelector('#tableData');
    tableData.innerHTML = '';

    for (const ticket of tickets) {
      console.log(ticket);
      console.log(ticket.hashtags);
      tableData.innerHTML += `
        <tr>
          <td>${ticket.title}</td>
          <td>${ticket.id}</td>
          <td>${ticket.username}</td>
          <td>${ticket.status}</td>
          <td>${ticket.submitdate}</td>
          <td>${ticket.priority}</td>
          <td>
            <ul>
            ${ticket.hashtags.map(hashtag => `<li>${hashtag.hashtagname}</li>`).join('')}
            </ul>
          </td>
          <td>${ticket.description}</td>
          <td>${ticket.assignedagent}</td>
          <td>${ticket.departmentName}</td>
        </tr>
      `;
    }
  } else {
    console.error('Error: ' + res.status);
  }



  // XMLHttpRequest
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