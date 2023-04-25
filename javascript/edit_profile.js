// function editProfile() {
//     // console.log("func");
//     const savedForm = document.querySelector('.saved_profile');
//     const editForm = document.querySelector('.edit_profile');
//     const editBtn = document.getElementById('edit-btn');
//     const saveBtn = document.getElementById('save-btn');
//     // console.log(editBtn);
//     if (editBtn) {
//         editBtn.addEventListener('click', () => {
//             console.log("edit");
//             savedForm.style.display = "none";
//             editForm.style.display = "block";
//         })
//     };
//     if (saveBtn) {
//         saveBtn.addEventListener('click', () => {
//             // drawProfile();
//             savedForm.style.display = "block";
//             editForm.style.display = "none";
//         })
//     };
// }

const editBtn = document.getElementById('edit-btn');
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
const csrf = document.getElementById('csrf');

/*mensagens de erro*/
const successM = document.getElementById('success-message');
const errorM = document.getElementById('error-message');

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

if(editBtn){
/*verificar se posso fazer toggle para 'edit' outra vez (apos dar save), apenas se os dados estiverem certos*/
editBtn.addEventListener('click', async () => {
    // mudar tipo do botão
    // editBtn.setAttribute('type', editBtn.getAttribute('type') === 'button' ? 'submit' : 'button');

    if (checkEditState()) {
        const res = await postProfileData({ 'name': thename.value, 'email': email.value, 'username': username.value, 'oldpass': oldpass.value, 'newpass': newpassInp.value, 'editpass': checkChangeState(), 'csrf': csrf.value })
            // .catch(() => console.log('Network Error'))
            // .then(response => response.json())
            // .catch(() => console.log('Error parsing JSON'))
            // .then(json => {
            //     console.log(json)
            //     if (json['error']) {
            //         console.error(json['error'])
            //         return
            //     }

            // })

            console.log(res);
            const json = await res.json();
            console.log(json);
            if (!res.ok){
                errorM.style.display = 'block';
                return;}
            else{
                successM.style.display = 'block';
            }
            
    }

    for (var lab of toggleLabs)
        lab.toggleAttribute('hidden');

    //esconder password
    for (var inp of toggleInps)
        inp.toggleAttribute('hidden');

    //colocar readonly
    for (var input of inputs) {
        input.toggleAttribute('readonly'); //toggle passa sempre para o oposto do atual (interruptor)
        input.style.border = (input.style.border === '1px solid rgb(51, 51, 51)') ? 'none' : '1px solid rgb(51, 51, 51)';
        input.style.borderRadius = (input.style.borderRadius === '5px') ? '0px' : '5px';
        input.style.backgroundColor = (input.style.backgroundColor === 'white') ? 'transparent' : 'white';
    }

    //mudar texto do botão
    editBtn.textContent = (editBtn.textContent === 'Save') ? 'Edit' : 'Save';

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
);

changepass.addEventListener('click', () => {
    newpassInp.toggleAttribute('hidden');
    newpassLab.toggleAttribute('hidden');
    changepass.textContent = (changepass.textContent === 'Change password') ? 'Cancel' : 'Change password';

    
})

editBtn.addEventListener('click', function () {
    successM.style.display = 'none';
    errorM.style.display = 'none';
});
// editBtn.addEventListener('change', function () {
//     successM.style.display = 'none';
//     errorM.style.display = 'none';
// });

}

function checkChangeState() {
    if (changepass.textContent === 'Cancel') {
        return 'yes'; //se o botao de cancelar mudar de password estiver visivel, retorna true
    }
    return 'no';
}

function checkEditState() {
    if (editBtn.textContent === 'Save') {
        return true; //se o botao de cancelar mudar de password estiver visivel, retorna true
    }
    return false;
}


