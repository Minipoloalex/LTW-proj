const addHashtagButton = document.getElementById("add-hashtag");
const input = document.getElementById("hashtag-search");
if (addHashtagButton && input) {
    addHashtagButton.addEventListener("click", addHashtag);
}

const removeHashtagButtons = document.querySelectorAll(".hashtag .remove-hashtag")
for (const button of removeHashtagButtons) {
    button.addEventListener("click", removeHashtagItem);
}


function postHashtag(data) {
    return fetch(
        '../api/api_check_hashtag.php',
        {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: encodeForAjax(data)
        })
}
function addHashtag(event) {
    /*
    * This function does not add the hashtag to the ticket in the database
    * that is the responsibility of the "Save" button
    */
    event.preventDefault();
    const hashtag = input.value;
    postHashtag({'hashtagName': hashtag, 'csrf': getCsrf()})
    .catch(error => console.log(error))
    .then((response) => response.json())
    .catch(error => console.log(error))
    .then((json) => {
        setCsrf(json['csrf'])
        if (json['error']) {
            console.error(json['error'])
        }
        else if (json['success']) {
            console.log(json['success'])
            input.value = ""
            const hashtagID = json['hashtagID']

            const hashtagList = document.querySelector("#hashtags section#hashtag-items")
            const hashtagItems = hashtagList.querySelectorAll("input")
            for (const item of hashtagItems) {
                if (parseInt(item.value) === hashtagID || parseInt(item.id) === hashtagID) {
                    console.log("Hashtag is already present in the UI")
                    return
                }
            }
            const hashtagArticle = document.createElement("article")
            hashtagArticle.classList.add("hashtag")

            const hashtagInputItem = document.createElement("input")
            hashtagInputItem.setAttribute("id", hashtagID)
            hashtagInputItem.setAttribute("value", hashtagID)
            hashtagInputItem.setAttribute("type", "hidden")
            hashtagInputItem.setAttribute("name", "hashtags[]")
            hashtagInputItem.setAttribute("checked", "")

            const hashtagLabelItem = document.createElement("label")
            hashtagLabelItem.setAttribute("for", hashtagID)
            hashtagLabelItem.textContent = hashtag

            const hashtagCloseItem = document.createElement("a")
            hashtagCloseItem.setAttribute("href", "#")
            hashtagCloseItem.setAttribute("class", "remove-hashtag")
            hashtagCloseItem.textContent = "X"
            hashtagCloseItem.addEventListener("click", removeHashtagItem)

            hashtagArticle.appendChild(hashtagInputItem)
            hashtagArticle.appendChild(hashtagLabelItem)
            hashtagArticle.appendChild(hashtagCloseItem)
            hashtagList.appendChild(hashtagArticle)
            
            removeFromDataList(hashtag)
        }
    });
}


function removeFromDataList(hashtag) {
    const hashtagDataList = document.getElementById("hashtag-datalist");
    const hashtagDataItems = hashtagDataList.querySelectorAll("option");

    for (const item of hashtagDataItems) {
        if (item.value === hashtag) {
            item.remove();
            break;
        }
    }
}

function addToDataList(hashtag) {   // called from X (remove hashtag) eventListener
    const hashtagDataList = document.getElementById("hashtag-datalist");
    const newHashtagItem = document.createElement("option");
    newHashtagItem.value = hashtag;
    hashtagDataList.appendChild(newHashtagItem);
}

function removeHashtagItem(event) {
    event.preventDefault();
    const hashtagArticle = event.target.parentNode;
    const hashtag = hashtagArticle.querySelector("label").textContent;
    addToDataList(hashtag);
    hashtagArticle.remove();
}
