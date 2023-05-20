const addDepartmentForm = document.getElementById('addDepartmentForm');

if(addDepartmentForm) {
    const addDepartmentFeedback = document.getElementById('add-department-feedback');
    addDepartmentForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const departmentNameInput = addDepartmentForm.querySelector('#department_name');
        const departmentName = departmentNameInput.value;

        const json = await postData('../api/api_department.php', {department_name: departmentName});

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