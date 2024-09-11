function triggerFileInput() {
    document.getElementById('hiddenInput').click();
    console.log('triggered');
}

function handleImageDragAndDrop() {
    const dropArea = document.querySelector('.border');
    const dragText = dropArea.querySelector("p");
    
    dropArea.addEventListener("dragover", (event) => {
        event.preventDefault();
        dropArea.classList.add("active");
        dragText.textContent = "Release to Upload File";
    });

    dropArea.addEventListener("dragleave", (event) => {
        event.preventDefault();
        dropArea.classList.remove("active");
        dragText.textContent = "Drag and Drop file here";
    });

    dropArea.addEventListener("drop", (event) => {
        event.preventDefault();

        const file = event.dataTransfer.files[0];
        const fileType = file.type;

        const validExtensions = ["image/jpeg", "image/jpg", "image/png"];
        if (validExtensions.includes(fileType)) {
            const fileReader = new FileReader(); 
            fileReader.onload = () => {
                const fileURL = fileReader.result; 
                const imgTag = `<img src="${fileURL}" alt="">`; 
                dropArea.innerHTML = imgTag; 
            };
            fileReader.readAsDataURL(file);
        } else {
            alert("This is not an image file");
            dropArea.classList.remove("active");
        }
    });
}

function handleImageUpload() {
    const imageInput = document.querySelector('#hiddenInput');

    imageInput.addEventListener("change", function() {
        const reader = new FileReader();

        reader.addEventListener("load", () => {
            const uploadedImage = reader.result;
            console.log(uploadedImage);
            document.querySelector('.border').style.backgroundImage = `url(${uploadedImage})`;
            const displayNone = document.querySelectorAll('.border * '); 
            displayNone.forEach(element => {
                element.style.display = 'none';
            });
        });
        reader.readAsDataURL(this.files[0]);
    });
}

handleImageUpload();
handleImageDragAndDrop();
