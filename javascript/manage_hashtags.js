const addNewHashtagButton = document.getElementById('create-hashtag');
const newHashtagInp = document.querySelector('#add-new-hashtag input');
const hashtagList = document.querySelector('.hashtag-list');
const deleteHashtagButton = document.querySelector('button#delete-hashtag');
const deletehashtagInp = document.querySelector('#delete-hashtag-form #hashtag-search');

if (addNewHashtagButton) {
    addNewHashtagButton.addEventListener('click', async (event) => {
        event.preventDefault();
        const hashtag = newHashtagInp.value;
        const json = await postData('../api/api_hashtag.php', { 'hashtagName': hashtag });
        
        // displayFeedback(manageHashtags, json);
        if (json['error']) {
            console.log(json['error']);
        }
        else {
            addToDataList(json['hashtagName']);
            addToHashtagList(json['hashtagName']);
            newHashtagInp.value = '';
        }
    });
}


if(deleteHashtagButton){
    deleteHashtagButton.addEventListener('click', async (event) => {
        event.preventDefault();
        const hashtag = deletehashtagInp.value;
        const json = await deleteData('../api/api_hashtag.php', { 'hashtagName': hashtag });

        // displayFeedback(manageHashtags, json);
        if (json['error']) {
            console.log(json['error']);
        }
        else {
            removeFromDataList(hashtag);
            removeFromHashtagList(hashtag);
            deletehashtagInp.value = '';
        }
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
