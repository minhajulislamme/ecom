
function handleFile(file) {
    const fileInput = document.getElementById('file-input-single');
    const imagePreview = document.getElementById('image-preview-single');
    const uploadText = document.getElementById('upload-text-single');
    const loadingIndicator = document.getElementById('loading-indicator');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            uploadText.classList.add('hidden');
            loadingIndicator.classList.add('hidden');
        };
        reader.readAsDataURL(file);
        loadingIndicator.classList.remove('hidden');
    }
}
function handleDragOver(event) {
    event.preventDefault();
    const dropArea = document.getElementById('drop-area-single');
    dropArea.classList.add('border-orange-500');
}
function handleDragLeave(event) {
    const dropArea = document.getElementById('drop-area-single');
    dropArea.classList.remove('border-orange-500');
}
function handleDrop(event) {
    event.preventDefault();
    const dropArea = document.getElementById('drop-area-single');
    dropArea.classList.remove('border-orange-500');

    const files = event.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
}
function previewSingleImage(event) {
    const file = event.target.files[0];
    const imagePreview = document.getElementById('image-preview-single');
    const uploadText = document.getElementById('upload-text-single');
    const loadingIndicator = document.getElementById('loading-indicator');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            uploadText.classList.add('hidden');
            loadingIndicator.classList.add('hidden');
        };
        reader.readAsDataURL(file);
        loadingIndicator.classList.remove('hidden');
    }
}
function handleDropSingle(event) {
    event.preventDefault();
    const dropArea = document.getElementById('drop-area-single');
    dropArea.classList.remove('border-orange-500');

    const files = event.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
}

