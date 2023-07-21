<?php
$fileName = $_FILES['file']['name']; // Get uploaded file name | Отримуємо ім'я завантаженого файлу
$chunkIndex = $_POST['chunkIndex']; // Get current chunk index | Отримуємо індекс поточної частини
$totalChunks = $_POST['totalChunks']; // Get total number of chunks | Отримуємо загальну кількість частин

$chunkFile = $fileName . '.part' . $chunkIndex; // Create chunk file name | Створюємо ім'я файлу частини
move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $chunkFile); // Move chunk file to uploads folder | Переміщуємо файл частини до теки uploads

// If this was the last chunk
// Якщо це була остання частина
if ($chunkIndex == $totalChunks - 1) {
    // Reassemble all chunks
    // Збираємо всі частини в один файл
    $outFile = fopen('uploads/' . $fileName, 'wb'); // Open output file | Відкриваємо вихідний файл
    for ($i = 0; $i < $totalChunks; $i++) {
        $inFile = fopen('uploads/' . $fileName . '.part' . $i, 'rb'); // Open input chunk file | Відкриваємо вхідний файл частини
        while ($chunk = fread($inFile, 8192)) {
            fwrite($outFile, $chunk); // Write chunk to output file | Записуємо частину до вихідного файлу
        }
        fclose($inFile); // Close input chunk file | Закриваємо вхідний файл частини
        unlink('uploads/' . $fileName . '.part' . $i); // Delete input chunk file | Видаляємо вхідний файл частини
    }
    fclose($outFile); // Close output file | Закриваємо вихідний файл
}

echo 'OK'; // Send response | Надсилаємо відповідь
?>
