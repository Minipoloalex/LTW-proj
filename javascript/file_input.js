// .file-name {
//     display: inline-block;
//     margin-left: 1em;
//     font-size: 0.8em;
//     color: #666;
//     white-space: nowrap;
//     overflow: hidden;
//     text-overflow: ellipsis;
//   }
const fileInput = document.getElementById('upload-image');
if (fileInput) {
    const fileName = document.querySelector('.file-name')
    const removeImageBtn = document.getElementById('remove-image-btn');
    const uploadImageBtn = document.getElementById('upload-image-btn');
    
    const clearFileInput = () => {
        fileName.textContent = 'No file selected'
        removeImageBtn.classList.add('d-none')
        uploadImageBtn.classList.remove('d-none')
    }

    fileInput.addEventListener('input', () => {
        if (fileInput.files.length > 0) {
          fileName.textContent = fileInput.files[0].name
          removeImageBtn.classList.remove('d-none')
          uploadImageBtn.classList.add('d-none')
        } else {
            clearFileInput()
        }
      });
    removeImageBtn.addEventListener('click', () => {
        fileInput.value = null
        clearFileInput()
    });
}
