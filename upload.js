// Set chunk size to 10MB
// Встановлюємо розмір частини на 10 МБ
var chunkSize = 1024 * 1024 * 10; // 10MB | 10 МБ

// Get upload status element by ID
// Отримуємо елемент статусу завантаження за його ідентифікатором
var uploadStatusElement = document.getElementById('uploadStatus');

// Initialize total size variables
// Ініціалізуємо змінні загального розміру та завантаженого розміру
var totalSize = 0;
var totalSizeUploaded = 0;

// Function to initiate file upload
// Функція для початку завантаження файлу
function uploadFile() {
    // Get selected file from file input element
    // Отримуємо вибраний файл з елементу вибору файлу
    var file = document.getElementById('fileUploader').files[0];
    
    // Set total size to size of selected file
    // Встановлюємо загальний розмір рівним розміру вибраного файлу
    totalSize = file.size;
    
    // Calculate total number of chunks
    // Обчислюємо загальну кількість частин
    var totalChunks = Math.ceil(file.size / chunkSize);
    
    // Upload first chunk
    // Завантажуємо першу частину
    uploadChunk(file, totalChunks, 0);
}

// Function to upload a chunk of the file
// Функція для завантаження частини файлу
function uploadChunk(file, totalChunks, chunkIndex) {
    // Calculate start and end positions of chunk
    // Обчислюємо початкову та кінцеву позиції частини
    var start = chunkIndex * chunkSize;
    var end = Math.min(file.size, start + chunkSize);

    // Slice chunk from file
    // Вирізаємо частину з файлу
    var chunk = file.slice(start, end);

    // Create form data containing chunk, chunk index, and total number of chunks
    // Створюємо форму даних, що містить частину, індекс частини та загальну кількість частин
    var formData = new FormData();
    formData.append('file', chunk, file.name);
    formData.append('chunkIndex', chunkIndex);
    formData.append('totalChunks', totalChunks);

    // Create XMLHttpRequest
    // Створюємо XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Open POST request to upload.php
    // Відкриваємо POST-запит на upload.php
    xhr.open('POST', 'upload.php', true);

    // Set progress event listener to update upload status
    // Встановлюємо обробник подій progress для оновлення статусу завантаження
    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            totalSizeUploaded = (chunkIndex * chunkSize) + e.loaded;
            var percentUploaded = (totalSizeUploaded / totalSize) * 100;
            uploadStatusElement.innerText = 'Uploaded ' + percentUploaded.toFixed(2) + '%'; // Завантажено: X%
        }
    };

    // Set onload event listener to upload next chunk if successful, or display error message if not
    // Встановлюємо обробник подій onload для завантаження наступної частини в разі успіху або відображення повідомлення про помилку в іншому випадку
    xhr.onload = function () {
        if (xhr.status === 200) {
            var uploadedChunks = chunkIndex + 1;
            if (uploadedChunks < totalChunks) {
                uploadChunk(file, totalChunks, uploadedChunks);
            }
        } else {
            uploadStatusElement.innerText = 'Uploadfailed'; // Помилка завантаження
        }
    };

    // Send form data
    // Відправляємо дані форми
    xhr.send(formData);
}
