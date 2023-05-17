const form = document.getElementById('faq-form');
const faqSection = document.getElementById('faqs');
const addFaqFeedback = document.getElementById('add-faq-feedback');
if (form) {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const question = document.getElementById('question-form').value;
        console.log(question);
        console.log(form);

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
                displayFeedback(addFaqFeedback,json);
                // displayMessage(,json['success'], false);
                form.reset();

                const type = json['type']; //user type;

                if (type !== 'Client') {
                    const faq = document.createElement('article');
                    faq.classList.add('faq');
                    faq.setAttribute('data-id', json['id']);

                    const question = document.createElement('textarea');
                    question.classList.add('question');
                    question.classList.add('input-readonly');
                    question.setAttribute('name', 'question');
                    question.setAttribute('id', 'question');
                    question.setAttribute('maxlength', '100');
                    question.setAttribute('rows', '1');
                    question.setAttribute('readonly', '');
                    question.textContent = json['question'];
                    faq.appendChild(question);

                    const answer = document.createElement('textarea');
                    answer.classList.add('answer');
                    answer.classList.add('input-readonly');
                    answer.setAttribute('name', 'answer');
                    answer.setAttribute('id', 'answer');
                    answer.setAttribute('rows', '1');
                    answer.setAttribute('readonly', '');
                    answer.textContent = json['answer'] ?? '';
                    faq.appendChild(answer);

                    //if (type !== 'Client') {
                    const editBtn = document.createElement('button');
                    editBtn.classList.add('edit-faq');
                    editBtn.setAttribute('id', 'editFaqBtn');
                    editBtn.innerHTML = '<span class="material-symbols-outlined">edit</span>';
                    faq.appendChild(editBtn);

                    const saveBtn = document.createElement('button');
                    saveBtn.classList.add('save-faq');
                    saveBtn.setAttribute('id', 'saveFaqBtn');
                    saveBtn.setAttribute('hidden', '');
                    saveBtn.innerHTML = '<span class="material-symbols-outlined">save</span>';
                    faq.appendChild(saveBtn);

                    const deleteBtn = document.createElement('button');
                    deleteBtn.classList.add('delete-faq');
                    deleteBtn.setAttribute('id', 'deleteFaqBtn');
                    deleteBtn.innerHTML = '<span class="material-symbols-outlined">delete</span>';
                    faq.appendChild(deleteBtn);

                    const hideBtn = document.createElement('button');
                    const displayBtn = document.createElement('button');

                    if (json['displayed'] === 1) {
                        // const hideBtn = document.createElement('button');
                        hideBtn.classList.add('hide-faq');
                        hideBtn.setAttribute('id', 'hideBtn');
                        hideBtn.innerHTML = '<span class="material-symbols-outlined">visibility_off</span>';
                        faq.appendChild(hideBtn);

                        // const displayBtn = document.createElement('button');
                        displayBtn.classList.add('hide-faq');
                        displayBtn.setAttribute('id', 'displayBtn');
                        displayBtn.setAttribute('hidden', '');
                        displayBtn.innerHTML = '<span class="material-symbols-outlined">visibility</span>';
                        faq.appendChild(displayBtn);

                    } else {
                        // const hideBtn = document.createElement('button');
                        hideBtn.classList.add('hide-faq');
                        hideBtn.setAttribute('id', 'hideBtn');
                        hideBtn.setAttribute('hidden', '');
                        hideBtn.innerHTML = '<span class="material-symbols-outlined">visibility_off</span>';
                        faq.appendChild(hideBtn);

                        // const displayBtn = document.createElement('button');
                        displayBtn.classList.add('hide-faq');
                        displayBtn.setAttribute('id', 'displayBtn');
                        displayBtn.innerHTML = '<span class="material-symbols-outlined">visibility</span>';
                        faq.appendChild(displayBtn);
                    }

                    /*  CRIAR ISTO
                    <div class="modal">
            <div class="modalContent">
                <span class="close">×</span>
                <p>Are you sure you want to delete your account</p>
                <button class="confirm-del">Delete</button>
                <!-- <button class="cancel" onclick="hideModal()">Cancel</button> -->
            </div>
        </div>*/

                    // Create the modal container element
                    const modal = document.createElement('div');
                    modal.classList.add('modal');
                    modal.classList.add('d-none');

                    // Create the modal content element
                    const modalContent = document.createElement('div');
                    modalContent.classList.add('modalContent');

                    // Create the close button
                    const closeButton = document.createElement('span');
                    closeButton.classList.add('close');
                    closeButton.innerHTML = '×';
                    modalContent.appendChild(closeButton);

                    // Create the message
                    const message = document.createElement('p');
                    message.innerHTML = 'Are you sure you want to delete?';
                    modalContent.appendChild(message);

                    // Create the delete button
                    const deleteButton = document.createElement('button');
                    deleteButton.classList.add('confirm-del');
                    deleteButton.innerHTML = 'Delete';
                    modalContent.appendChild(deleteButton);

                    // Add the modal content to the modal container
                    modal.appendChild(modalContent);

                    // Add the modal to the page
                    faq.appendChild(modal);


                    // if (json['answer'] === null) {
                        const answerFaqBtn = document.createElement('button');
                        answerFaqBtn.classList.add('answer-faq');
                        answerFaqBtn.setAttribute('id', 'answerFaq');
                        answerFaqBtn.textContent = 'Answer question';
                        faq.appendChild(answerFaqBtn);

                        const saveAnswerBtn = document.createElement('button');
                        saveAnswerBtn.classList.add('save-answer');
                        saveAnswerBtn.setAttribute('id', 'saveAnswerBtn');
                        saveAnswerBtn.setAttribute('hidden', '');
                        saveAnswerBtn.textContent = 'Save answer';
                        faq.appendChild(saveAnswerBtn);

                        // handleAnswer(answerFaqBtn);
                    // }

                    const feedback = document.createElement('div');
                    feedback.classList.add('feedback-message');
                    feedback.textContent = '';

                    // }

                    faqSection.appendChild(faq);
                    handleTextAreas(question);
                    handleTextAreas(answer);
                    faqSection.appendChild(feedback);

                    handleEdit(editBtn);
                    handleDelete(deleteBtn);
                    handleDisplay(displayBtn);
                    handleHide(hideBtn);

                    handleAnswer(answerFaqBtn);
                }
            } else {
                // displayMessage(json['error']);
                displayFeedback(addFaqFeedback, json);
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
        clearAllDisplayFeedback();
    });
    form.addEventListener('change', function () {
        // successMessage.style.display = 'none';
        // errorMessage.style.display = 'none';
        clearAllDisplayFeedback();
    });
}
