function editProfile() {
    console.log("func");
    const savedForm = document.querySelector('.saved_profile');
    const editForm = document.querySelector('.edit_profile');
    const editBtn = document.getElementById('edit-btn');
    const saveBtn = document.getElementById('save-btn');
    console.log(editBtn);
    if (editBtn) {
        editBtn.addEventListener('click', () => {
            console.log("edit");
            savedForm.style.display = "none";
            editForm.style.display = "block";
        })
    };
    if (saveBtn) {
        saveBtn.addEventListener('click', () => {
            // drawProfile();
            savedForm.style.display = "block";
            editForm.style.display = "none";
        })
    };
}