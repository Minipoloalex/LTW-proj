const editBtn = document.getElementById('edit-btn');
const saveBtn = document.getElementById('save-btn');
const cancelBtn = document.getElementById('cancel-btn');
const inputs = document.querySelectorAll('input:not(#type)');
const toggleInps = document.querySelectorAll('#old-password, #type');
const toggleLabs = document.querySelectorAll('label[for="old-password"], label[for="type"]');
const changepass = document.getElementById('changepass');
const newpassInp = document.getElementById('new-password');
const newpassLab = document.querySelector('label[for="new-password"]');
const editProfileFeedback = document.querySelector('#edit-profile-feedback');

const thename = document.getElementById('name');
const email = document.getElementById('email');
const username = document.getElementById('username');
const oldpass = document.getElementById('old-password');

const showPassButtonOld = document.getElementById("profile-showpass");
const hidePassButtonOld = document.getElementById("profile-hidepass");
const showPassButtonNew = document.getElementById("profile-new-showpass");
const hidePassButtonNew = document.getElementById("profile-new-hidepass");

function toggleProfile() {
    for (const lab of toggleLabs)
        lab.toggleAttribute('hidden');

    for (const inp of toggleInps)
        inp.toggleAttribute('hidden');

    for (var input of inputs) {
        input.toggleAttribute('readonly');
    }

    editBtn.toggleAttribute('hidden');
    saveBtn.toggleAttribute('hidden');
    cancelBtn.toggleAttribute('hidden');

    changepass.toggleAttribute('hidden');

    oldpass.value = '';
    newpassInp.value = '';
    newpassInp.setAttribute('hidden','hidden');
    newpassLab.setAttribute('hidden','hidden');
    changepass.textContent = 'Change password';
    newpassInp.type = "password";
    oldpass.type = "password";

    showPassButtonOld.toggleAttribute('hidden');
}

if (saveBtn) {
    saveBtn.addEventListener('click', async () => {
        const json = await putData('../api/api_user.php', {
            'name': thename.value,
            'email': email.value,
            'username': username.value,
            'oldpass': oldpass.value,
            'newpass': newpassInp.value,
            'editpass': checkChangeState(),
        });
        displayFeedback(editProfileFeedback, json);
        if (json['success']) {
            toggleProfile();
            showPassButtonNew.hidden = true;
            hidePassButtonNew.hidden = true;
            showPassButtonOld.hidden = true;
            hidePassButtonOld.hidden = true;
            oldpass.type = "password";
        }
    });
    changepass.addEventListener('click', () => {
        newpassInp.toggleAttribute('hidden');
        newpassLab.toggleAttribute('hidden');
        changepass.textContent = (changepass.textContent === 'Change password') ? 'Cancel' : 'Change password';
        showPassButtonNew.toggleAttribute('hidden');

        if (newpassInp.hidden === true) {
            showPassButtonNew.hidden = true;
            hidePassButtonNew.hidden = true;
            newpassInp.type = "password";
        }
    });
}

if (cancelBtn) {
    cancelBtn.addEventListener('click', () => {
        toggleProfile();
        if (showPassButtonNew.hidden === false) {
            showPassButtonNew.toggleAttribute('hidden');
            newpassInp.type = "password";
        }   
        if (hidePassButtonNew.hidden === false) {
            hidePassButtonNew.toggleAttribute('hidden');
            newpassInp.type = "password";
        }
        if (showPassButtonOld.hidden === false) {
            showPassButtonOld.toggleAttribute('hidden');
            oldpass.type = "password";
        }
        if (hidePassButtonOld.hidden === false) {
            hidePassButtonOld.toggleAttribute('hidden');
            oldpass.type = "password";
        }
    });
}
if (editBtn) {
    editBtn.addEventListener('click', function () {
        clearAllDisplayFeedback();
        toggleProfile();
    });
}


function checkChangeState() {
    if (changepass.textContent === 'Cancel') {
        return 'yes';
    }
    return 'no';
}

if(showPassButtonNew){
showPassButtonNew.addEventListener('click', () => {
    showPassButtonNew.toggleAttribute('hidden');
    hidePassButtonNew.toggleAttribute('hidden');
    newpassInp.type = "text";
})
}

if(showPassButtonOld){
    showPassButtonOld.addEventListener('click', () => {
    showPassButtonOld.toggleAttribute('hidden');
    hidePassButtonOld.toggleAttribute('hidden');
    oldpass.type = "text";
})
}

if(hidePassButtonNew){
    hidePassButtonNew.addEventListener('click', () => {
    showPassButtonNew.toggleAttribute('hidden');
    hidePassButtonNew.toggleAttribute('hidden');
    newpassInp.type = "password";
})
}

if(hidePassButtonOld){  
    hidePassButtonOld.addEventListener('click', () => {
    showPassButtonOld.toggleAttribute('hidden');
    hidePassButtonOld.toggleAttribute('hidden');
    oldpass.type = "password";
})
}

