const registerButton = document.querySelector('.registerform button.create');
if (registerButton) {
    const registerFeedback = document.getElementById('feedback-message');
    const displayRegisterErrorMessage = (event, message) => {
        displayMessage(registerFeedback, message);
        event.preventDefault();
    }

    registerButton.addEventListener('click', function (event) {
        const password = document.querySelector('input[name="password"]').value;
        const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
        const username = document.querySelector('input[name="username"]').value;
        const name = document.querySelector('input[name="name"]').value;
        const email = document.querySelector('input[name="email"]').value;
        if (isEmpty(email) || isEmpty(username) || isEmpty(username) || isEmpty(password) || isEmpty(confirmPassword)) {
            displayRegisterErrorMessage(event, 'Please fill in all the fields');
            return;
        }
        if (!name.match(getNameRegex())) {
            displayRegisterErrorMessage(event, 'Please enter a valid name');
            return;
        }
        if (!username.match(getUsernameRegex())) {
            displayRegisterErrorMessage(event, 'Please enter a valid username');
            return;
        }
        if (!email.toLowerCase().match(getEmailRegex())) {
            displayRegisterErrorMessage(event, 'Please enter a valid email address');
            return;
        }
        if (password !== confirmPassword) {
            displayRegisterErrorMessage(event, 'Passwords do not match');
            return;
        }
        if (password.length < 8) {
            displayRegisterErrorMessage(event, 'The password must be at least 8 characters long');
            return;
        }
        else if (!password.match(getPasswordRegex())) {
            displayRegisterErrorMessage(event, 'The password must contain at least one uppercase letter, one lowercase letter, one number and one special character');
            return;
        }
    })
}
