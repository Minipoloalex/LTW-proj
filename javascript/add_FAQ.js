const form = document.getElementById('faq-form');
const faqSection = document.getElementById('faqs');

const addFaqFeedback = document.getElementById('add-faq-feedback');
if (form) {
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        const question = document.getElementById('question-form').value;
        const json = await postData('../api/api_FAQ.php', {'question': question});

        if (json['error']) {
            displayFeedback(addFaqFeedback, json);
            form.reset();
            return;
        }
        else {
            displayFeedback(addFaqFeedback, json);
            form.reset();

            const type = json['type'];

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
                setTextContent(question, json['question']);
                faq.appendChild(question);

                const answer = document.createElement('textarea');
                answer.classList.add('answer');
                answer.classList.add('input-readonly');
                answer.setAttribute('name', 'answer');
                answer.setAttribute('id', 'answer');
                answer.setAttribute('rows', '1');
                answer.setAttribute('readonly', '');
                setTextContent(answer, json['answer'] ?? '');
                faq.appendChild(answer);

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
                    hideBtn.classList.add('hide-faq');
                    hideBtn.setAttribute('id', 'hideBtn');
                    hideBtn.innerHTML = '<span class="material-symbols-outlined">visibility_off</span>';
                    faq.appendChild(hideBtn);

                    displayBtn.classList.add('hide-faq');
                    displayBtn.setAttribute('id', 'displayBtn');
                    displayBtn.setAttribute('hidden', '');
                    displayBtn.innerHTML = '<span class="material-symbols-outlined">visibility</span>';
                    faq.appendChild(displayBtn);

                } else {
                    hideBtn.classList.add('hide-faq');
                    hideBtn.setAttribute('id', 'hideBtn');
                    hideBtn.setAttribute('hidden', '');
                    hideBtn.innerHTML = '<span class="material-symbols-outlined">visibility_off</span>';
                    faq.appendChild(hideBtn);

                    displayBtn.classList.add('hide-faq');
                    displayBtn.setAttribute('id', 'displayBtn');
                    displayBtn.innerHTML = '<span class="material-symbols-outlined">visibility</span>';
                    faq.appendChild(displayBtn);
                }
                const modal = document.createElement('div');
                modal.classList.add('modal');
                modal.classList.add('d-none');

                const modalContent = document.createElement('div');
                modalContent.classList.add('modalContent');

                const closeButton = document.createElement('span');
                closeButton.classList.add('close');
                closeButton.textContent = '×';
                modalContent.appendChild(closeButton);

                const message = document.createElement('p');
                message.textContent = 'Are you sure you want to delete this FAQ?';
                modalContent.appendChild(message);

                const deleteButtonModal = document.createElement('button');
                deleteButtonModal.classList.add('confirm-del');
                deleteButtonModal.textContent = 'Delete';
                modalContent.appendChild(deleteButtonModal);

                modal.appendChild(modalContent);

                faq.appendChild(modal);

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

                const feedback = document.createElement('div');
                feedback.classList.add('feedback-message');
                feedback.textContent = '';

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
        }  
    });

    form.addEventListener('focusin', function () {
        clearAllDisplayFeedback();
    });
    form.addEventListener('change', function () {
        clearAllDisplayFeedback();
    });
}
