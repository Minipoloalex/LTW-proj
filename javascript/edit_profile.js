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

    // changePass button appears
    changepass.toggleAttribute('hidden');

    //apagar old password apos dar save
    oldpass.value = '';

    //apagar new password apos dar save
    newpassInp.value = '';
    newpassInp.setAttribute('hidden','hidden');
    newpassLab.setAttribute('hidden','hidden');
    changepass.textContent = 'Change password';

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
        }
    });
    changepass.addEventListener('click', () => {
        newpassInp.toggleAttribute('hidden');
        newpassLab.toggleAttribute('hidden');
        changepass.textContent = (changepass.textContent === 'Change password') ? 'Cancel' : 'Change password';
    });
}

if (cancelBtn) {
    cancelBtn.addEventListener('click', () => {
        toggleProfile();
    });
}
if (editBtn) {
    /*verificar se posso fazer toggle para 'edit' outra vez (apos dar save), apenas se os dados estiverem certos*/

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
