// const table = document.getElementById("ticketTable");
const table = document.querySelector(".display-table")
console.log(table);
console.log(table.rows);
if (table) {
  const rowsPerPage = 5;

  const rowCount = table.rows.length;
  const tableHead = table.rows[0].firstElementChild.tagName === "TH";
  const tr = [];
  let i, ii, j = (tableHead) ? 1 : 0;
  const th = (tableHead ? table.rows[(0)].outerHTML : "");
  const pageCount = Math.ceil(rowCount / rowsPerPage);
  if (pageCount > 1) {
    for (i = j, ii = 0; i < rowCount; i++, ii++)
      tr[ii] = table.rows[i].outerHTML;
    table.insertAdjacentHTML("afterend", "<div id='paginationButtons'></div");
    sort(1);
  }

  function sort(page) {
    let rows = th, s = ((rowsPerPage * page) - rowsPerPage);
    for (i = s; i < (s + rowsPerPage) && i < tr.length; i++)
      rows += tr[i];
    table.innerHTML = rows;
    document.getElementById("paginationButtons").innerHTML = pageButtons(pageCount, page);
  }

  function pageButtons(pageCount, current) {
    let buttons = '';
    const prevButton = (current === 1) ? 'disabled' : '';
    const nextButton = (current === pageCount) ? 'disabled' : '';

    for (let i = 1; i <= pageCount; i++) {
      buttons += `<input type="button" value="${i}" onclick="sort(${i})" ${i === current ? 'disabled' : ''}>`;
    }

    buttons = `<input type="button" value="&lt;" onclick="sort(${current - 1})" ${prevButton}>` + buttons;
    buttons += `<input type="button" value="&gt;" onclick="sort(${current + 1})" ${nextButton}>`;

    return buttons;
  }
}


