const addNewHashtagButton = document.getElementById('create-hashtag');
const newHashtagInp = document.querySelector('#add-new-hashtag input');
const hashtagList = document.querySelector('.hashtag-list');
const deleteHashtagButton = document.querySelector('button#delete-hashtag');
const deletehashtagInp = document.querySelector('#delete-hashtag-form #hashtag-search');
const hashtagFeedback = document.querySelector('#hashtag-feedback');
if (addNewHashtagButton) {
    addNewHashtagButton.addEventListener('click', async (event) => {
        event.preventDefault();
        const hashtag = newHashtagInp.value;
        const json = await postData('../api/api_hashtag.php', { 'hashtagName': hashtag });
        
        if (json['success']) {
            addToDataList(json['hashtagName']);
            addToHashtagList(json['hashtagName']);
            newHashtagInp.value = '';
        }
        displayFeedback(hashtagFeedback, json);
    });
}

if(deleteHashtagButton){
    deleteHashtagButton.addEventListener('click', async (event) => {
        event.preventDefault();
        const hashtag = deletehashtagInp.value;
        const json = await deleteData('../api/api_hashtag.php', { 'hashtagName': hashtag });

        if (json['success']) {
            removeFromDataList(hashtag);
            removeFromHashtagList(hashtag);
            deletehashtagInp.value = '';
        }
        displayFeedback(hashtagFeedback, json);
    });
}

function addToHashtagList(hashtag) {
    const hashtagListItem = document.createElement("li");
    hashtagListItem.classList.add("list-hashtags");
    setTextContent(hashtagListItem, hashtag);

    hashtagList.prepend(hashtagListItem);
}

function removeFromHashtagList(hashtag) {
    const hashtagListItems = hashtagList.querySelectorAll("li");
    for (const item of hashtagListItems) {
        if (item.textContent == hashtag) {
            deletehashtagInp.value = "";
            item.remove();
            break;
        }
    }
}
