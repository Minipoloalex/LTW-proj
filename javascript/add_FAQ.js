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
                response.json().then(data => {
                    console.log(data);
                    const type = data.type;
                    let faq = document.createElement('article');
                    faq.setAttribute('class', 'faq');
                    faq.setAttribute('data-id', data.id);
                
                    let question = document.createElement('textarea');
                    question.setAttribute('class', 'question input-readonly');
                    question.setAttribute('name', 'question');
                    question.setAttribute('id', 'question');
                    question.setAttribute('maxlength', '100');
                    question.setAttribute('rows', '1');
                    question.setAttribute('readonly', '');
                    question.textContent = data.question;
                    faq.appendChild(question);
                
                    let answer = document.createElement('textarea');
                    answer.setAttribute('class', 'answer input-readonly');
                    answer.setAttribute('name', 'answer');
                    answer.setAttribute('id', 'answer');
                    answer.setAttribute('rows', '1');
                    answer.setAttribute('readonly', '');
                    answer.textContent = data.answer ?? '';
                    faq.appendChild(answer);
                
                    if (type !== 'Client') {
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
                
                        if (data.displayed === 1) {
                            let hideBtn = document.createElement('button');
                            hideBtn.setAttribute('class', 'hide-faq');
                            hideBtn.setAttribute('id', 'hideBtn');
                            hideBtn.innerHTML = '<span class="material-symbols-outlined">visibility_off</span>';
                            faq.appendChild(hideBtn);
                        } else {
                            let displayBtn = document.createElement('button');
                            displayBtn.setAttribute('class', 'hide-faq');
                            displayBtn.setAttribute('id', 'displayBtn');
                            displayBtn.innerHTML = '<span class="material-symbols-outlined">visibility</span>';
                            faq.appendChild(displayBtn);
                        }
                
                        if (data.answer === null) {
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
                        }
                    }
                
                    faqSection.appendChild(faq);
                });
                // response.json().then(data => {
                //     // faq. type.~
                //     console.log(data);
                //     const type = data.type;
                    
                //     // new faq:
                //     let html = `
                //         <article class="faq" data-id="${data.id}">
                //         <textarea id="question" name="question" class="question input-readonly"
                //         value="${data.question}" maxlength="100" rows="1" readonly>${data.question}</textarea>
        
                //         <textarea id="answer" name="answer" class="answer input-readonly"
                //         value="${data.answer ?? ''}" rows="1" readonly>${data.answer ?? ''}</textarea>
                //         `;
        
                //     if (type !== 'Client') {
                //         html += `
                //         <button id="editFaqBtn" class="edit-faq"><span class="material-symbols-outlined">edit</span></button>
                //         <button id="saveFaqBtn" class="save-faq" hidden><span class="material-symbols-outlined">save</span></button>
                //         <button id="deleteFaqBtn" class="delete-faq"><span class="material-symbols-outlined">delete</span></button>
                //         `;
        
                //         if (data.displayed === 1) {
                //             html += `
                //             <button id="hideBtn" class="hide-faq"><span class="material-symbols-outlined">visibility_off</span></button>
                //             `;
                //         } else {
                //             html += `
                //             <button id="displayBtn" class="hide-faq"><span class="material-symbols-outlined">visibility</span></button>
                //             `;
                //         }
        
                //         if (data.answer === null) {
                //             html += `
                //             <button id="answerFaq" class="answer-faq">Answer question</button>
                //             <button id="saveAnswerBtn" class="save-answer" hidden>Save answer</button>
                //             `;
                //         }
                //     }
        
                //     html += '</article>';
                //     faqSection.innerHTML += html;
                // });
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