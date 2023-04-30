async function getFilterValues() {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  const checkedValues = {};

  checkboxes.forEach(checkbox => {
    if (checkbox.checked) {
      if (checkedValues[checkbox.name]) {
        checkedValues[checkbox.name].push(`"${checkbox.value}"`);
      } else {
        checkedValues[checkbox.name] = [`"${checkbox.value}"`];
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
  const res = await fetch('../api/api_filter_tickets.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: json
  })
  console.log(res);
  if (res.ok) {
    // const resData = await res.json();
    // updateTicketTable(resData);

    const tickets = await res.json();

    const cardContainer = document.getElementById("card-container");
    cardContainer.innerHTML = '';
    getCards(tickets);
    
    
    // const tableData = document.querySelector('#tableData');
    // console.log(tableData);
    // tableData.innerHTML = '';

    // for (const ticket of tickets) {   // TODO: test special chars
    //   tableData.innerHTML += `
    //     <tr>
    //       <td>${ticket.title}</td>
    //       <td>${ticket.id}</td>
    //       <td>${ticket.username}</td>
    //       <td>${ticket.status}</td>
    //       <td>${ticket.submitdate}</td>
    //       <td>${ticket.priority}</td>
    //       <td>
    //         <ul>
    //         ${ticket.hashtags.map(hashtag => `<li>${hashtag.hashtagname}</li>`).join('')}
    //         </ul>
    //       </td>
    //       <td>${ticket.description}</td>
    //       <td>${ticket.assignedagent}</td>
    //       <td>${ticket.departmentName}</td>
    //     </tr>
    //   `;
    // }


  } else {
    console.error('Error: ' + res.status);
  }

}

function clearFilters() {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  checkboxes.forEach(checkbox => {
    checkbox.checked = false;
  });
  getFilterValues();
}


function updateTicketTable(tickets) {
  
    const tableData = document.querySelector('#tableData');
    console.log(tableData);
    tableData.innerHTML = '';

    for (const ticket of tickets) {
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
}




// const clearFiltersBtn = document.getElementById('clear-filters');
// const getFilterValuesBtn = document.getElementById('filter-values');
// console.log(getFilterValuesBtn);
// if (getFilterValuesBtn && clearFiltersBtn) {

//   async function filter () {
//     // event.preventDefault();
//     const checkboxes = document.querySelectorAll('input[type="checkbox"]');
//     const checkedValues = {};
  
//     checkboxes.forEach(checkbox => {
//       if (checkbox.checked) {
//         if (checkedValues[checkbox.name]) {
//           checkedValues[checkbox.name].push(`"${checkbox.value}"`);
//         } else {
//           checkedValues[checkbox.name] = [`"${checkbox.value}"`];
//         }
//       } else {
//         if (!checkedValues[checkbox.name]) {
//           checkedValues[checkbox.name] = [];
//         }
//       }
//     });
//     console.log(checkedValues);
  
//     const json = JSON.stringify(checkedValues);
  
//     // Fetch API
//     const res = await fetch('../api/api_filter_tickets.php', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/json'
//       },
//       body: json
//     })
//     console.log(res);
//     if (res.ok) {
//       // const resData = await res.json();
//       // updateTicketTable(resData);
  
//       const tickets = await res.json();
  
//       const tableData = document.querySelector('#tableData');
//       console.log(tableData);
//       tableData.innerHTML = '';
  
//       for (const ticket of tickets) {
//         tableData.innerHTML += `
//         <tr>
//           <td>${ticket.title}</td>
//           <td>${ticket.id}</td>
//           <td>${ticket.username}</td>
//           <td>${ticket.status}</td>
//           <td>${ticket.submitdate}</td>
//           <td>${ticket.priority}</td>
//           <td>
//             <ul>
//             ${ticket.hashtags.map(hashtag => `<li>${hashtag.hashtagname}</li>`).join('')}
//             </ul>
//           </td>
//           <td>${ticket.description}</td>
//           <td>${ticket.assignedagent}</td>
//           <td>${ticket.departmentName}</td>
//         </tr>
//       `;
//       }
  
//     } else {
//       console.error('Error: ' + res.status);
//     }
//   }
  
//   getFilterValuesBtn.addEventListener('click', filter());

//   clearFiltersBtn.addEventListener('click', function () {
//     // event.preventDefault();
//     const checkboxes = document.querySelectorAll('input[type="checkbox"]');
//     checkboxes.forEach(checkbox => {
//       checkbox.checked = false;
//     });
//     filter();
//   });

// }



// function updateTicketTable(tickets) {

//   const tableData = document.querySelector('#tableData');
//   console.log(tableData);
//   tableData.innerHTML = '';

//   for (const ticket of tickets) {
//     tableData.innerHTML += `
//         <tr>
//           <td>${ticket.title}</td>
//           <td>${ticket.id}</td>
//           <td>${ticket.username}</td>
//           <td>${ticket.status}</td>
//           <td>${ticket.submitdate}</td>
//           <td>${ticket.priority}</td>
//           <td>
//             <ul>
//             ${ticket.hashtags.map(hashtag => `<li>${hashtag.hashtagname}</li>`).join('')}
//             </ul>
//           </td>
//           <td>${ticket.description}</td>
//           <td>${ticket.assignedagent}</td>
//           <td>${ticket.departmentName}</td>
//         </tr>
//       `;
//   }
// }