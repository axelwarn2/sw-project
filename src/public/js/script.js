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

    const setButtonState = (button, isActive) => {
        button.disabled = !isActive;
        button.classList.toggle('active', isActive);
    };

    const updateSelectedPath = (path) => {
        selectedPathSpan.textContent = path;
        selectedPathSpan.classList.add('selected');
    };

    const clearSelection = () => {
        document.querySelectorAll('.directory__folder, .directory__file').forEach(item => item.classList.remove('selected'));
        selectedItemId = null;
        selectedItemType = '';
        selectedFilePath = '';
        updateSelectedPath('');
        setButtonState(addFileButton, false);
        setButtonState(deleteButton, false);
        setButtonState(downloadButton, false);
    };

    const handleFolderSelection = (folderTarget) => {
        clearSelection();
        folderTarget.classList.add('selected');
        selectedItemId = folderTarget.dataset.id;
        selectedItemType = 'directory';
        parentIdInput.value = selectedItemId;
        directoryNameInput.disabled = false;

        const folderPath = folderTarget.dataset.path;
        updateSelectedPath(folderPath);

        setButtonState(addFileButton, true);
        setButtonState(deleteButton, true);
        setButtonState(downloadButton, false);
        photosImg.src = '../public/images/noimage.jpeg';
    };

    const handleFileSelection = (fileTarget) => {
        clearSelection();
        fileTarget.classList.add('selected');
        selectedItemId = fileTarget.dataset.id;
        selectedItemType = 'file';
    
        directoryNameInput.value = '';
        directoryNameInput.disabled = true;

        const filePath = fileTarget.dataset.path;
        const fileName = fileTarget.textContent.trim();
        selectedFilePath = filePath;
        updateSelectedPath(selectedFilePath);

        const isImage = /\.(jpg|jpeg|png|gif)$/i.test(fileName);
        photosImg.src = isImage ? `../uploads/${filePath}` : '../public/images/noimage.jpeg';
        photosImg.style.display = 'block';

        setButtonState(addFolderButton, false);
        setButtonState(addFileButton, false);
        setButtonState(deleteButton, true);
        setButtonState(downloadButton, true);
    };
    

    directoryNameInput.addEventListener('input', () => {
        const isActive = directoryNameInput.value.trim() !== '';
        setButtonState(addFolderButton, isActive);
    });

    directoryTree.addEventListener('click', (event) => {
        const folderTarget = event.target.closest('.directory__folder');
        const fileTarget = event.target.closest('.directory__file');

        if (folderTarget) {
            handleFolderSelection(folderTarget);
        } else if (fileTarget) {
            handleFileSelection(fileTarget);
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

            if (!response.ok) {
                throw new Error(response.status === 400 ? 'Количество символов должно быть меньше 255' : 'Ошибка при создании каталога');
            }
            location.reload();
        } catch (error) {
            alert(error.message);
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

                if (!response.ok) {
                    const error = response.status === 413 ? 'Файл слишком большой' : response.status === 400 ? 'Недопустимый тип файла' : 'Ошибка во время загрузки файла';
                    throw new Error(error);
                }
                location.reload();
            } catch (error) {
                alert(error.message);
                location.reload();
            }
        }
    });

    deleteButton.addEventListener('click', async () => {
        if (selectedItemId && selectedItemType) {
            try {
                await fetch('/delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ itemId: selectedItemId, itemType: selectedItemType })
                });
                location.reload();
            } catch (error) {
                alert('Ошибка при удалении элемента');
            }
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
