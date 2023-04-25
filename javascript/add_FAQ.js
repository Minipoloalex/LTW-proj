const form = document.getElementById('faq-form');
const successMessage = document.getElementById('success-message');
const errorMessage = document.getElementById('error-message');
const question = document.getElementById('question').value;
console.log(question);
console.log(form);
form.addEventListener('submit', function (e) {
    e.preventDefault();
    const data = new URLSearchParams();
    data.append('question', question);
    

    // Here you can send the question to your backend for processing/storage
    // For example, you can use fetch() to send an HTTP POST request:
    fetch('../api/api_add_FAQ.php', {
        method: 'POST',
        headers: {
            // 'Content-Type': 'application/json'
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        // body: JSON.stringify({ question: question })
        body: encodeForAjax(data)

    }).then(function (response) {
        if (response.ok) {
            successMessage.style.display = 'block';
            form.reset();
        } else {
            throw new Error('Network response was not ok');
        }
    }).catch(function (error) {
        console.error('There was a problem with the fetch operation:', error);
        errorMessage.style.display = 'block';
    });

    successMessage.style.display = 'block';
    form.reset();
});

// Optional: If you want to show/hide the success/error messages based on user interaction
form.addEventListener('focusin', function () {
    successMessage.style.display = 'none';
    errorMessage.style.display = 'none';
});
form.addEventListener('change', function () {
    successMessage.style.display = 'none';
    errorMessage.style.display = 'none';
});


