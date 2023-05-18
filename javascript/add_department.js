const addDepartmentForm = document.getElementById('addDepartmentForm');
const addDepartmentFeedback = document.getElementById('add-department-feedback');

if(addDepartmentForm) {
addDepartmentForm.addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevent form submission

    const departmentNameInput = addDepartmentForm.querySelector('#department_name');
    const departmentName = departmentNameInput.value;

    const json = await postData('../api/api_departments.php', {department_name: departmentName});

    // Handle the response and display the added department
    displayFeedback(addDepartmentFeedback, json);
    if (json['success']) {
        const addedDepartment = json.department_name;
        // TODO: do something with added department
        console.log(json);
        console.log(addedDepartment);
        departmentNameInput.value = '';
    }
});
}