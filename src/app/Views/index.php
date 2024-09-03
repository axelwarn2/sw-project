<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/style.css">
    <title>Файловый менеджер</title>
</head>
<body>
    <main class="main">
        <div class="directory">
            <div class="directory__form">
                <div class="form__name-search">
                    <span class="form__name-directory">
                        <p class="form__label">Наименование директории</p>
                        <input class="form__input" type="text" id="directoryNameInput" name="name">
                    </span>
                </div>
                <div class="form__buttons">
                    <form action="/create-directory" method="POST" class="form">
                        <input type="hidden" id="parentIdInput" name="parentId" value="">
                        <input type="submit" class="form__button" value="Добавить папку" id="addFolderButton">
                    </form>
                    <form action="/upload" method="POST" class="form" enctype="multipart/form-data">
                        <div class="form__buttons">
                            <input type="submit" class="form__button" value="Добавить файл" id="addFileButton" disabled>
                        </div>
                    </form>
                    <form action="/delete" method="POST" class="form">
                        <div class="form__buttons">
                            <input type="hidden" id="itemId" name="itemId">
                            <input type="submit" class="form__button" value="Удалить" id="deleteButton" disabled>
                        </div>
                    </form>
                </div>
            </div>
            <div class="directory__list">
                <?php echo $treeHtml; ?>
            </div>
        </div>

        <div class="photos">
            <div class="photos__selected">
                <p class="photos__label">Выбрано: </p>
                <button class="photos__button" id="downloadButton" disabled>Скачать</button>
            </div>
            <div class="photos__image">
                <img class="photos__img" src="../public/images/noimage.jpeg" alt="No image">
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const directoryNameInput = document.getElementById('directoryNameInput');
            const addFolderButton = document.getElementById('#addFolderButton');
            const directoryTree = document.querySelector('.directory__list');
            const parentIdInput = document.getElementById('#parentIdInput');
            
            directoryTree.addEventListener('click', (event) => {
                const target = event.target.closest('.directory__folder');
                if (target) {
                    document.querySelectorAll('.directory__folder').forEach(folder => folder.classList.remove('selected'));
                    target.classList.add('selected');
                    parentIdInput.value = target.dataset.id || '';
                }
            });

            addFolderButton.addEventListener('click', async (event) => {
                event.preventDefault();

                const name = directoryNameInput.value.trim();
                const parentId = parentIdInput.value;

                if (name) {
                    await fetch('/create-directory', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({ name, parentId })
                    });
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>
