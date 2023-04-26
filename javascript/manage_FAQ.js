const displayBtn = document.getElementById('displayBtn');
const hideBtn = document.getElementById('hideBtn');
const editFaqBtn = document.getElementById('editFaqBtn');
const question = document.getElementById('question');
const answer = document.getElementById('answer');

async function postFaqData(data) {
    console.log(data);
    console.log(encodeForAjax(data))
    return await fetch('../api/api_edit_faq.php', {
        method: 'post',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: encodeForAjax(data)
    })
}


editFaqBtn.addEventListener('click', async () => {
    const res = await postFaqData({ 'question': question.value, 'answer': answer.value, 'csrf': csrf.value })
    console.log(res);
    const json = await res.json();
    console.log(json);
    if (!res.ok) {
        // errorM.style.display = 'block';
        return;
    }
    // else {
    //     successM.style.display = 'block';
    // }

    for (var input of inputs) {
        input.toggleAttribute('readonly'); //toggle passa sempre para o oposto do atual (interruptor)
        input.style.border = (input.style.border === '1px solid rgb(51, 51, 51)') ? 'none' : '1px solid rgb(51, 51, 51)';
        input.style.borderRadius = (input.style.borderRadius === '5px') ? '0px' : '5px';
        input.style.backgroundColor = (input.style.backgroundColor === 'white') ? 'transparent' : 'white';
    }

    question.toggleAttribute('readonly');
    answer.toggleAttribute('readonly');
});

// answer.addEventListener('click', () => {
//     const res = await postFaqData({ 'question': question.value, 'answer': answer.value, 'csrf': csrf.value })
    
// })