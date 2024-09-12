document.addEventListener('DOMContentLoaded', () => {
    const directoryNameInput = document.querySelector('#directoryNameInput');
    const directoryForm = document.querySelector('#directoryForm');
    const directoryTree = document.querySelector('.directory__list');
    const parentIdInput = document.querySelector('#parentIdInput');
    const addFolderButton = document.querySelector('#addFolderButton');
    const addFileButton = document.querySelector('#addFileButton');
    const deleteButton = document.querySelector('#deleteButton');
    const selectedPathSpan = document.querySelector('#selectedPath'); 
    const fileInput = document.querySelector('#fileInput');
    const downloadButton = document.querySelector('#downloadButton');
    const photosImg = document.querySelector('.photos__img');
    deleteButton.disabled = true;
    addFolderButton.disabled = true;

    let selectedItemId = null;
    let selectedItemType = '';
    let selectedFilePath = ''; 

    directoryNameInput.addEventListener('input', () => {
        if (directoryNameInput.value.trim() !== '') {
            addFolderButton.disabled = false;
            addFolderButton.classList.add('active');
        } else {
            addFolderButton.disabled = true;
            addFolderButton.classList.remove('active');
        }
    });

    directoryTree.addEventListener('click', (event) => {
        const folderTarget = event.target.closest('.directory__folder');
        const fileTarget = event.target.closest('.directory__file');

        if (folderTarget) {
            document.querySelectorAll('.directory__folder').forEach(folder => folder.classList.remove('selected'));
            document.querySelectorAll('.directory__file').forEach(file => file.classList.remove('selected'));
            folderTarget.classList.add('selected');
            selectedItemId = folderTarget.dataset.id;
            selectedItemType = 'directory';
            parentIdInput.value = selectedItemId;
            directoryNameInput.disabled = false;
            
            const folderPath = folderTarget.dataset.path;
            selectedPathSpan.textContent = folderPath;
            selectedPathSpan.classList.add('selected');

            addFileButton.disabled = false;
            deleteButton.disabled = false;
            addFileButton.classList.add('active');
            deleteButton.classList.add('active');
            downloadButton.disabled = true;
            downloadButton.classList.remove('active');
            
            photosImg.src = '../public/images/noimage.jpeg';
        } else if (fileTarget) {
            document.querySelectorAll('.directory__file').forEach(file => file.classList.remove('selected'));
            document.querySelectorAll('.directory__folder').forEach(folder => folder.classList.remove('selected'));
            fileTarget.classList.add('selected');
            selectedItemId = fileTarget.dataset.id;
            selectedItemType = 'file';

            directoryNameInput.value = '';
            directoryNameInput.disabled = true;

            const folderPath = fileTarget.closest('.directory__item').querySelector('.directory__folder').dataset.path;
            const fileName = fileTarget.textContent.trim();
            selectedFilePath = `${folderPath}${fileName}`;
            selectedPathSpan.textContent = selectedFilePath;
            selectedPathSpan.classList.add('selected');

            const isImage = /\.(jpg|jpeg|png|gif)$/i.test(fileName);
            if (isImage) {
                let fullFilePath = fileTarget.dataset.path;
                photosImg.src = `../uploads/${fullFilePath}`;
                photosImg.style.display = 'block';
            } else {
                photosImg.src = '../public/images/noimage.jpeg';
            }

            addFolderButton.disabled = true;
            addFolderButton.classList.remove('active');
            addFileButton.disabled = true;
            addFileButton.classList.remove('active');
            deleteButton.disabled = false;
            deleteButton.classList.add('active');
            downloadButton.disabled = false;
            downloadButton.classList.add('active');
        }
    });

    addFileButton.addEventListener('click', (event) => {
        event.preventDefault();
        if (selectedItemId && selectedItemType === 'directory') {
            fileInput.click();
        }
    });

    directoryForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const name = directoryNameInput.value;
        const parentId = parentIdInput.value;

        try {
            const response = await fetch('/create-directory', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ name, parentId })
            });

            if (response.ok) {
                location.reload();
            } else {
                if (response.status === 400) {
                    alert('Количество символов должно быть меньше 255');
                    location.reload();
                } else {
                    alert('Ошибка при создании каталога');
                    location.reload();
                }
            }
        } catch (error) {
            alert('Ошибка при создании каталога');
            location.reload();
        }
    });

    fileInput.addEventListener('change', async () => {
        const file = fileInput.files[0];
        if (file && selectedItemId) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('directoryId', selectedItemId);

            try {
                const response = await fetch('/create-file', {
                    method: 'POST',
                    body: formData,
                });

                if (response.ok) {
                    location.reload();
                } else {
                    if (response.status === 413) {
                        alert("Файл слишком большой");
                        location.reload();
                    } else if (response.status === 400) {
                        alert("Недопустимый тип файла");
                        location.reload();
                    } else {
                        alert("Ошибка во время загрузки файла");
                        location.reload();
                    }
                }
            } catch (error) {
                alert("Ошибка во время загрузки файла");
                location.reload();
            }
        }
    });

    deleteButton.addEventListener('click', async () => {
        if (selectedItemId && selectedItemType) {
            fetch('/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ itemId: selectedItemId, itemType: selectedItemType })
            }).then(() => location.reload());
        }
    });

    downloadButton.addEventListener('click', () => {
        if (selectedFilePath) {
            const link = document.createElement('a');
            link.href = `/download?filename=${encodeURIComponent(selectedFilePath)}`;
            link.download = selectedFilePath.split('/').pop();
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    });
});
