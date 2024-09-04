document.addEventListener('DOMContentLoaded', () => {
    const directoryNameInput = document.querySelector('#directoryNameInput');
    const directoryForm = document.querySelector('#directoryForm');
    const directoryTree = document.querySelector('.directory__list');
    const parentIdInput = document.querySelector('#parentIdInput');
    const addFolderButton = document.querySelector('#addFolderButton');
    const addFileButton = document.querySelector('#addFileButton');
    const deleteButton = document.querySelector('#deleteButton');
    const selectedPathLabel = document.querySelector('.photos__label');
    const fileInput = document.querySelector('#fileInput');

    let selectedItemId = null;
    let selectedItemType = '';

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
            folderTarget.classList.add('selected');
            selectedItemId = folderTarget.dataset.id;
            selectedItemType = 'directory';
            parentIdInput.value = selectedItemId;
            selectedPathLabel.textContent = `Выбрано: ${folderTarget.dataset.path}`;

            addFileButton.disabled = false;
            deleteButton.disabled = false;
            addFileButton.classList.add('active');
            deleteButton.classList.add('active');
        } else if (fileTarget) {
            document.querySelectorAll('.directory__file').forEach(file => file.classList.remove('selected'));
            fileTarget.classList.add('selected');
            selectedItemId = fileTarget.dataset.id;
            selectedItemType = 'file';
            const folderPath = fileTarget.closest('.directory__item').querySelector('.directory__folder').dataset.path;
            selectedPathLabel.textContent = `Выбрано: ${folderPath}${fileTarget.textContent.trim()}`;

            deleteButton.disabled = false;
            deleteButton.classList.add('active');
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

        if (name) {
            fetch('/create-directory', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ name, parentId })
            }).then(() => location.reload());
        }
    });

    fileInput.addEventListener('change', async () => {
        const file = fileInput.files[0];
        if (file && selectedItemId) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('directoryId', selectedItemId);

            fetch('/create-file', {
                method: 'POST',
                body: formData
            }).then(() => location.reload());
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
});
