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
    }
  });
  console.log(checkedValues);
}