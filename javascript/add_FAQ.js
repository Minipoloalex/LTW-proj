const form = document.getElementById('faq-form');
// const successMessage = document.getElementById('success-message');
// const errorMessage = document.getElementById('error-message');
// const feedbackMessage = document.getElementById('feedback-message');
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
            const json = await response.json();
            if (response.ok) {
                displayMessage(json['success'], false);
                form.reset();
                
                    const type = json['type']; //user type;

                    if (type !== 'Client') {
                        let faq = document.createElement('article');
                        faq.setAttribute('class', 'faq');
                        faq.setAttribute('data-id', json['id']);

                        let question = document.createElement('textarea');
                        question.setAttribute('class', 'question input-readonly');
                        question.setAttribute('name', 'question');
                        question.setAttribute('id', 'question');
                        question.setAttribute('maxlength', '100');
                        question.setAttribute('rows', '1');
                        question.setAttribute('readonly', '');
                        question.textContent = json['question'];
                        faq.appendChild(question);

                        let answer = document.createElement('textarea');
                        answer.setAttribute('class', 'answer input-readonly');
                        answer.setAttribute('name', 'answer');
                        answer.setAttribute('id', 'answer');
                        answer.setAttribute('rows', '1');
                        answer.setAttribute('readonly', '');
                        answer.textContent = json['answer'] ?? '';
                        faq.appendChild(answer);

                        //if (type !== 'Client') {
                        let editBtn = document.createElement('button');
                        editBtn.setAttribute('class', 'edit-faq');
                        editBtn.setAttribute('id', 'editFaqBtn');
                        editBtn.innerHTML = '<span class="material-symbols-outlined">edit</span>';
                        faq.appendChild(editBtn);

                        let saveBtn = document.createElement('button');
                        saveBtn.setAttribute('class', 'save-faq');
                        saveBtn.setAttribute('id', 'saveFaqBtn');
                        saveBtn.setAttribute('hidden', '');
                        saveBtn.innerHTML = '<span class="material-symbols-outlined">save</span>';
                        faq.appendChild(saveBtn);

                        let deleteBtn = document.createElement('button');
                        deleteBtn.setAttribute('class', 'delete-faq');
                        deleteBtn.setAttribute('id', 'deleteFaqBtn');
                        deleteBtn.innerHTML = '<span class="material-symbols-outlined">delete</span>';
                        faq.appendChild(deleteBtn);

                        
                        let hideBtn = document.createElement('button');
                        let displayBtn = document.createElement('button');

                        if (json['displayed'] === 1) {
                            // let hideBtn = document.createElement('button');
                            hideBtn.setAttribute('class', 'hide-faq');
                            hideBtn.setAttribute('id', 'hideBtn');
                            hideBtn.innerHTML = '<span class="material-symbols-outlined">visibility_off</span>';
                            faq.appendChild(hideBtn);

                            // let displayBtn = document.createElement('button');
                            displayBtn.setAttribute('class', 'hide-faq');
                            displayBtn.setAttribute('id', 'displayBtn');
                            displayBtn.setAttribute('hidden', '');
                            displayBtn.innerHTML = '<span class="material-symbols-outlined">visibility</span>';
                            faq.appendChild(displayBtn);

                        } else {
                            // let hideBtn = document.createElement('button');
                            hideBtn.setAttribute('class', 'hide-faq');
                            hideBtn.setAttribute('id', 'hideBtn');
                            hideBtn.setAttribute('hidden', '');
                            hideBtn.innerHTML = '<span class="material-symbols-outlined">visibility_off</span>';
                            faq.appendChild(hideBtn);

                            // let displayBtn = document.createElement('button');
                            displayBtn.setAttribute('class', 'hide-faq');
                            displayBtn.setAttribute('id', 'displayBtn');
                            displayBtn.innerHTML = '<span class="material-symbols-outlined">visibility</span>';
                            faq.appendChild(displayBtn);
                        }

                        if (json['answer'] === null) {
                            let answerFaqBtn = document.createElement('button');
                            answerFaqBtn.setAttribute('class', 'answer-faq');
                            answerFaqBtn.setAttribute('id', 'answerFaq');
                            answerFaqBtn.textContent = 'Answer question';
                            faq.appendChild(answerFaqBtn);

                            let saveAnswerBtn = document.createElement('button');
                            saveAnswerBtn.setAttribute('class', 'save-answer');
                            saveAnswerBtn.setAttribute('id', 'saveAnswerBtn');
                            saveAnswerBtn.setAttribute('hidden', '');
                            saveAnswerBtn.textContent = 'Save answer';
                            faq.appendChild(saveAnswerBtn);

                            handleAnswer(answerFaqBtn);
                        }

                        handleEdit(editBtn);
                        handleDelete(deleteBtn);
                        handleDisplay(displayBtn);
                        handleHide(hideBtn);

                        // }

                        faqSection.appendChild(faq);
                        handleTextAreas(question);
                        handleTextAreas(answer);

                    }
            } else {
                displayMessage(json['error']);
                throw new Error('Network response was not ok');
            }

        }).catch(function (error) {
            console.error('There was a problem with the fetch operation:', error);
            // displayMessage(json['error']);
        });

        form.reset();
    });

    form.addEventListener('focusin', function () {
        // successMessage.style.display = 'none';
        // errorMessage.style.display = 'none';
        FeedbackMessage.classList.remove('error-message');
        FeedbackMessage.classList.remove('success-message');
        FeedbackMessage.textContent = '';
    });
    form.addEventListener('change', function () {
        // successMessage.style.display = 'none';
        // errorMessage.style.display = 'none';
        FeedbackMessage.classList.remove('error-message');
        FeedbackMessage.classList.remove('success-message');
        FeedbackMessage.textContent = '';
        
    });
}