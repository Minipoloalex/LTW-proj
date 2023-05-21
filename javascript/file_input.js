const fileInput = document.getElementById('upload-image');
if (fileInput) {
    const fileName = document.querySelector('.file-name');
    const removeImageBtn = document.getElementById('remove-image-btn');
    const uploadImageBtn = document.getElementById('upload-image-btn');
    
    const clearFileInput = () => {
        fileName.textContent = 'No file selected';
        removeImageBtn.classList.add('d-none');
        uploadImageBtn.classList.remove('d-none');
    }

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
          fileName.textContent = fileInput.files[0].name;
          removeImageBtn.classList.remove('d-none');
          uploadImageBtn.classList.add('d-none');
        } else {
            clearFileInput();
        }
      });
    removeImageBtn.addEventListener('click', (event) => {
        event.preventDefault();
        fileInput.value = null;
        clearFileInput();
    });
    uploadImageBtn.addEventListener('click', (event) => {
        event.preventDefault();
        fileInput.click();
    });
}


function clickOnDocument(event) {
    const imgContainer = document.querySelector('.image-container:not(.d-none)');
    if (imgContainer.contains(event.target)) return;
    event.preventDefault();
    event.stopPropagation();
    imgContainer.classList.add('d-none');
    document.removeEventListener('click', clickOnDocument);
}

function clickOnCloseImageButton(event) {
    event.preventDefault();
    const container = event.currentTarget.parentElement;
    container.classList.add('d-none');
    document.removeEventListener('click', clickOnDocument);
}

function clickOnViewImageButton(event) {
    event.preventDefault();
    event.stopPropagation();
    const imgContainer = event.currentTarget.parentElement.querySelector('.image-container');
    imgContainer.classList.remove('d-none');

    document.addEventListener('click', clickOnDocument);
}

const viewImageButtons = document.querySelectorAll('.view-image');

if (viewImageButtons) {
    viewImageButtons.forEach((btn) => {
        btn.addEventListener('click', clickOnViewImageButton);
    });
    const closeImageButtons = document.querySelectorAll('.close-image');
    closeImageButtons.forEach((btn) => {
        btn.addEventListener('click', clickOnCloseImageButton);
    });
}
