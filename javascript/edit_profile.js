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

editBtn.addEventListener('click', () => {

    for (var lab of toggleLabs)
        lab.toggleAttribute('hidden');

    //esconder password
    for (var inp of toggleInps)
        inp.toggleAttribute('hidden');

    //colocar readonly
    for (var input of inputs) {
        input.toggleAttribute('readonly'); //toggle passa sempre para o oposto do atual (interruptor)
        input.style.border = (input.style.border === '1px solid rgb(51, 51, 51)') ? 'none': '1px solid rgb(51, 51, 51)';
        input.style.borderRadius = (input.style.borderRadius === '5px') ? '0px': '5px';
        input.style.backgroundColor = (input.style.backgroundColor === 'white') ? 'transparent' : 'white';
    }

    //mudar tipo do botão
    // editBtn.setAttribute('type', editBtn.getAttribute('type') === 'button' ? 'submit' : 'button');

    //mudar texto do botão
    editBtn.textContent = (editBtn.textContent === 'Save') ? 'Edit' : 'Save';

    //aparecer botao de mudar password
    changepass.toggleAttribute('hidden');

});

changepass.addEventListener('click', () => {
    newpassInp.toggleAttribute('hidden');
    newpassLab.toggleAttribute('hidden');
    changepass.textContent = (changepass.textContent === 'Change password') ? 'Cancel' : 'Change password';

})

function checkstate() {
    if (changepass.textContent === 'Cancel') {
        return true; //se o botao de cancelar mudar de password estiver visivel, retorna true
    }
    return false;
}