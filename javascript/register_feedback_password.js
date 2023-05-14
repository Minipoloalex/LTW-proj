const registerButton = document.querySelector('.registerform button.create')
if (registerButton) {
    registerButton.addEventListener('click', function (event) {
        console.log("HELLO INSIDE EVENT LISTENTER")
        event.preventDefault()
        const password = document.querySelector('input[name="password"][type="password"]').value
        if (password.length < 6) {
            displayMessage('Password must be at least 6 characters long')
            event.preventDefault()
            return  
        }
        else if (!password.match(getPasswordRegex())) {
            displayMessage('The password must contain at least one uppercase letter, one lowercase letter, one number and one special character')
            return
        }
    })
}
