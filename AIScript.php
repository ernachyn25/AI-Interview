<?php
 $answer = isset($_POST['answer']) ? $_POST['answer'] : '';
 
// Gantilah path ke executable Python di bawah sesuai dengan instalasi Anaconda Anda
$pythonExecutable = 'C:/Users/Jason/anaconda3/python.exe';
// Gantilah path ke skrip Python Anda di bawah
$pythonScript = 'nb.py';

// $argument1 = json_encode([
//     'Sebelumnya saya bekerja sebagai dokter',
//     'Karena saya adalah pecinta kopi dan ingin mengetahui lebih banyak lagi tentang kopi',
//     'Pertama saya akan berkoordinasi dengan rekan kerja yang lain agar pekerjaan menjadi lebih efektif',
//     'Espresso yang sempurna hanya dapat dicapai dengan menggunakan biji kopi termahal',
//     'Saya mencoba menemukan solusi win-win untuk memuaskan semua pihak yang terlibat dalam konflik'
// ]);

$argument1 = json_encode($answer);
// Bangun command untuk dieksekusi
$command = "$pythonExecutable  $pythonScript '$argument1' ";
// Jalankan command dan dapatkan outputnya
try{
    $output = shell_exec($command);
    echo($output);
}catch(Exception $error){
    echo "Error: " . $error->getMessage();

}

?>