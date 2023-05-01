const searchBtn = document.getElementById("search-btn")
const input = document.getElementById("hashtagSearch")
if (searchBtn && input) {
    searchBtn.addEventListener("click", addHashtag)
    input.addEventListener("input", function() {})
}

function addHashtag() {
  /*
   * This function does not add the hashtag to the ticket in the database
   * that is the responsibility of the "Save" button
   */
    const input = document.getElementById("hashtagSearch")
    const hashtag = input.value
    postHashtag({'hashtagName': hashtag})
    .catch(error => console.log(error))
    .then((response) => response.json())
    .catch(error => console.log(error))
    .then((json) => {
        if (json['error']) {
            console.error(json['error'])
        }
        else if (json['success']) {
            console.log(json['success'])
            const hashtagList = document.getElementById("hashtagList")
            const hashtagItem = document.createElement("li")
            hashtagItem.textContent = hashtag
            hashtagList.appendChild(hashtagItem)
            input.value = ""
        } else {
            alert("Hashtag does not exist")
        }
    })
}
function inputEventListener(event) {
  /*close any already open lists of autocompleted values*/
  const inputText = this.value;
  // closeAllLists()

  if (inputText) return false
  currentFocus = -1;
  /*create a DIV element that will contain the items (values):*/
  const autocompleteList = document.createElement("div")
  autocompleteList.setAttribute("id", "autocomplete-list")

  /*append the DIV element as a child of the autocomplete container:*/
  this.parentNode.appendChild(autocompleteList)
  /*for each item in the array...*/
  const hashtags = ["#hashtag1", "#hashtag2", "#hashtag3", "#hashtag4", "#hashtag5"]
  for (const hashtag of hashtags) {
    /*check if the item starts with the same letters as the text field value:*/
    if (hashtag.substr(0, inputText.length).toUpperCase() === inputText.toUpperCase()) {
      /*create a DIV element for each matching element:*/
      const autocompleteItem = document.createElement("DIV")
      /*make the matching letters bold:*/
      autocompleteItem.innerHTML = "<strong>" + hashtag.substr(0, val.length) + "</strong>";
      autocompleteItem.innerHTML += hashtag.substr(val.length);
      
      /*insert a input field that will hold the current array item's value:*/
      // WTF: autocompleteItem.innerHTML += "<input type='hidden' value='" + hashtag + "'>";
      autocompleteItem.addEventListener("click", function(event) {
        input.value = this.getElementsByTagName("input")[0].value;
        // closeAllLists();
      });
      autocompleteList.appendChild(autocompleteItem);
    }
  }
}
function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", inputEventListener);
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        const autocompleteList = document.getElementById(this.id + "autocomplete-list");
        const x = x.querySelectorAll("");
        if (e.keyCode == 40) {  // DOWN KEY
          currentFocus++;
          addActive(x);
        } else if (e.keyCode == 38) { // UP KEY
          currentFocus--;
          addActive(x);
        } else if (e.keyCode == 13) { // ENTER KEY
          e.preventDefault(); /* prevent form from being submitted */
          if (currentFocus > -1) {
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(autocompleteList) {
      if (!autocompleteList) return false;
      
      removeActive(x);

      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(autocompleteList) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (const autocompleteItem of autocompleteList) {
        autocompleteItem.classList.remove("autocomplete-active");
      }
    }
    
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

function closeAllLists(elmnt) {
  /*close all autocomplete lists in the document,
  except the one passed as an argument:*/
  var x = document.getElementsByClassName("autocomplete-items");
  for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}