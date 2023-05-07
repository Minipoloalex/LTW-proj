const form = document.getElementById('faq-form');
const successMessage = document.getElementById('success-message');
const errorMessage = document.getElementById('error-message');
const faqSection = document.getElementById('faqs');
if (form) {
    form.addEventListener('submit', function (e) {

        const question = document.getElementById('question-form').value;
        console.log(question);
        console.log(form);

        e.preventDefault();
        // const data = new URLSearchParams();
        // data.append('question', question);
        const data = { 'question': question };

        // Here you can send the question to your backend for processing/storage
        // For example, you can use fetch() to send an HTTP POST request:
        fetch('../api/api_FAQ.php', {
            // fetch('../api/api_add_FAQ.php', {
            method: 'POST',
            headers: {
                // 'Content-Type': 'application/json'
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            // body: JSON.stringify({ question: question })
            body: encodeForAjax(data)

        }).then(async function (response) {
            if (response.ok) {
                successMessage.style.display = 'block';
                form.reset();
                // faq. type.
                const res = await response.json();
                console.log(res);
                const type = res.type;
                
                // new faq:
                let html = `
                    <article class="faq" data-id="${res.id}">
                    <textarea id="question" name="question" class="question input-readonly"
                    value="${res.question}" maxlength="100" rows="1" readonly>${res.question}</textarea>

                    <textarea id="answer" name="answer" class="answer input-readonly"
                    value="${res.answer ?? ''}" rows="1" readonly>${res.answer ?? ''}</textarea>
                    `;

                if (type !== 'Client') {
                    html += `
                    <button id="editFaqBtn" class="edit-faq"><span class="material-symbols-outlined">edit</span></button>
                    <button id="saveFaqBtn" class="save-faq" hidden><span class="material-symbols-outlined">save</span></button>
                    <button id="deleteFaqBtn" class="delete-faq"><span class="material-symbols-outlined">delete</span></button>
                    `;

                    if (res.displayed === 1) {
                        html += `
                        <button id="hideBtn" class="hide-faq"><span class="material-symbols-outlined">visibility_off</span></button>
                        `;
                    } else {
                        html += `
                        <button id="displayBtn" class="hide-faq"><span class="material-symbols-outlined">visibility</span></button>
                        `;
                    }

                    if (res.answer === null) {
                        html += `
                        <button id="answerFaq" class="answer-faq">Answer question</button>
                        <button id="saveAnswerBtn" class="save-answer" hidden>Save answer</button>
                        `;
                    }
                }

                html += '</article>';
                faqSection.innerHTML += html;

            } else {
                throw new Error('Network response was not ok');
            }
        }).catch(function (error) {
            console.error('There was a problem with the fetch operation:', error);
            errorMessage.style.display = 'block';
        });

        // successMessage.style.display = 'block';
        form.reset();
    });

    form.addEventListener('focusin', function () {
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';
    });
    form.addEventListener('change', function () {
        successMessage.style.display = 'none';
        errorMessage.style.display = 'none';
    });
}