async function postFaqData(data) {
  console.log(data);
  return await fetch('../api/api_edit_FAQ.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: encodeForAjax(data)
  })
}

async function deleteFaqData(data) {
  console.log(data);
  return await fetch('../api/api_edit_FAQ.php', {
    method: 'delete',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: encodeForAjax(data)
  })
}

const editFaqBtns = document.querySelectorAll('#editFaqBtn');

if (editFaqBtns) {
  editFaqBtns.forEach((editFaqBtn) => {
    const faq = editFaqBtn.parentElement;

    const question = faq.querySelector('#question');
    const answer = faq.querySelector('#answer');
    const saveFaqBtn = faq.querySelector('#saveFaqBtn');
    const deleteFaqBtn = faq.querySelector('#deleteFaqBtn');
    console.log(question.value);
    console.log(answer.value);

    const toggle = () => {
      editFaqBtn.toggleAttribute('hidden');
      saveFaqBtn.toggleAttribute('hidden');
      deleteFaqBtn.toggleAttribute('hidden');

      question.toggleAttribute('readonly');
      question.classList.toggle("input-readonly");
      question.classList.toggle("input-write");

      answer.toggleAttribute('readonly');
      answer.classList.toggle("input-readonly");
      answer.classList.toggle("input-write");

    }

    editFaqBtn.addEventListener('click', () => {
      toggle();
    });

    saveFaqBtn.addEventListener('click', async () => {
      console.log(question.value);
      console.log(answer.value);

      const res = await postFaqData({ 'id': faq.getAttribute("data-id"),'question': question.value, 'answer': answer.value});
      
      if (res.ok) {
        const a = await res.json();
        console.log(a);
        console.log("sucess");
        toggle();
      } else {
        console.error('Error: ' + res.status);
      }

    });

    deleteFaqBtn.addEventListener('click', async () => {
      // !TODO: request to api to delete faq
      const res = await deleteFaqData({ 'question': question.value, 'answer': answer.value});
      if (res.ok) {
        console.log("sucess");
        // delete element
        faq.remove();
      } else {
        console.error('Error: ' + res.status);
      }
    })
  });
}

// !TODO: answer faq

// !TODO: delete faq

// !TODO: hide/show faq
