
const addDepartmentForm = document.getElementById('addDepartmentForm');
addDepartmentForm.addEventListener('submit', async function (event) {
    event.preventDefault(); // Prevent form submission

    const departmentNameInput = document.getElementById('department_name');
    const departmentName = departmentNameInput.value;

    const response = await postData('../api/api_departments.php', { department_name: departmentName });

    // Handle the response and display the added department
    if (response.status === 'success') {
        const addedDepartment = response.department;

        // Reset the form input
        departmentNameInput.value = '';
    } else {
        // Handle error case if needed
        console.error('Failed to add department:', response.message);
    }
});


