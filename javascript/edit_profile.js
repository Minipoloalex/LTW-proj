const editBtn = document.getElementById('edit-btn');
const saveBtn = document.getElementById('save-btn');
const cancelBtn = document.getElementById('cancel-btn');
const inputs = document.querySelectorAll('input:not(#type)');
const toggleInps = document.querySelectorAll('#old-password, #type');
const toggleLabs = document.querySelectorAll('label[for="old-password"], label[for="type"]');
const changepass = document.getElementById('changepass');
const newpassInp = document.getElementById('new-password');
const newpassLab = document.querySelector('label[for="new-password"]');

/*buscar valores dos inputs*/
const thename = document.getElementById('name');
const email = document.getElementById('email');
const username = document.getElementById('username');
const oldpass = document.getElementById('old-password');

async function postProfileData(data) {
    console.log(data);
    console.log(encodeForAjax(data))
    return await fetch('../api/api_edit_profile.php', {
        method: 'post',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: encodeForAjax(data)
    })
}

function toggleProfile() {
    for (const lab of toggleLabs)
        lab.toggleAttribute('hidden');

    //esconder password
    for (const inp of toggleInps)
        inp.toggleAttribute('hidden');

    //colocar readonly
    for (var input of inputs) {
        input.toggleAttribute('readonly'); //toggle passa sempre para o oposto do atual (interruptor)
        // !TODO: change this classes and do this in css instead
        input.style.border = (input.style.border === '1px solid rgb(51, 51, 51)') ? 'none' : '1px solid rgb(51, 51, 51)';
        input.style.borderRadius = (input.style.borderRadius === '5px') ? '0px' : '5px';
        input.style.backgroundColor = (input.style.backgroundColor === 'white') ? 'transparent' : 'white';
    }

    //mudar texto do botão
    // editBtn.textContent = (editBtn.textContent === 'Save') ? 'Edit' : 'Save';
    editBtn.toggleAttribute('hidden');
    saveBtn.toggleAttribute('hidden');
    cancelBtn.toggleAttribute('hidden');

    //aparecer botao de mudar password
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
        
        const res = await postProfileData({
            'name': thename.value,
            'email': email.value,
            'username': username.value,
            'oldpass': oldpass.value,
            'newpass': newpassInp.value,
            'editpass': checkChangeState(),
            'csrf': getCsrf()
        });
        console.log(res);
        const json = await res.json();
        console.log(json);
        if (!res.ok) {
            displayMessage(json['error']);
            return; // why?
        }
        else {
            displayMessage(json['success'], false);
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
        FeedbackMessage.classList.remove('error-message');
        FeedbackMessage.classList.remove('success-message');
        FeedbackMessage.textContent = '';
        toggleProfile();
    });
}


function checkChangeState() {
    if (changepass.textContent === 'Cancel') {
        return 'yes'; //se o botao de cancelar mudar de password estiver visivel, retorna true
    }
    return 'no';
}

// function checkEditState() {
//     if (editBtn.textContent === 'Save') {
//         return true; //se o botao de cancelar mudar de password estiver visivel, retorna true
//     }
//     return false;
// }
