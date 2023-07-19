<?php
// Define a constant for the upload directory (Визначаємо константу для директорії завантаження)
define('UPLOAD_DIR', 'uploads/');

// Check if the $_FILES['file'] array is not empty (Перевіряємо, чи не пустий масив $_FILES['file'])
if (!empty($_FILES['file'])) {
    // Get the original file name (Отримуємо оригінальне ім'я файлу)
    $fileName = $_POST['filename'];
    // Define the target directory (Визначаємо цільову директорію)
    $targetDir = UPLOAD_DIR . $fileName;

    // Open the file for writing (Відкриваємо файл для запису)
    $out = fopen($targetDir, "ab");
    // If the file is opened successfully (Якщо файл вдало відкрито)
    if ($out) {
        // Open the temporary file for reading (Відкриваємо тимчасовий файл для читання)
        $in = fopen($_FILES['file']['tmp_name'], "rb");
        // If the temporary file is opened successfully (Якщо тимчасовий файл вдало відкрито)
        if ($in) {
            // Read and write file chunks (Читаємо і записуємо фрагменти файлу)
            while ($chunk = fread($in, 4096)) {
                fwrite($out, $chunk, 4096);
            }
        }
        // Close the files (Закриваємо файли)
        fclose($in);
        fclose($out);

        // Delete the temporary file (Видаляємо тимчасовий файл)
        unlink($_FILES['file']['tmp_name']);
    }

    // Send a response with a successful status (Надсилаємо відповідь із успішним статусом)
    echo json_encode([
        'status' => true,
        'message' => 'File uploaded successfully',
        'messageua' => 'Файл успішно завантажено'
        
    ]);
} else {
    // Send a response with a failed status (Надсилаємо відповідь із невдалим статусом)
    echo json_encode([
        'status' => false,
        'message' => 'No file uploaded'
    ]);
}
?>