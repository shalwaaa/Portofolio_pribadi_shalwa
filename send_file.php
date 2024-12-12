<?php
// Ambil data dari form
$name = $_POST['contactName'];
$email = $_POST['contactEmail'];
$subject = $_POST['contactSubject'];
$message = $_POST['contactMessage'];

// Format data untuk file
$data = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message\n";

// Simpan data sebagai file TXT di server sementara
$filename = "submission_" . date("Y-m-d_H-i-s") . ".txt";
file_put_contents($filename, $data);

// Kirim email dengan file TXT sebagai lampiran
$to = "shalwahafidzinemail@example.com"; // Ganti dengan email kamu
$subject = "New Form Submission";
$body = "Hi, \n\nYou have a new form submission. See the attached file for details.";

// Header untuk pengiriman email dengan lampiran
$headers = "From: "; // Ganti dengan pengirim yang valid
$boundary = md5("random");

$headers .= "\r\nMIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"";

// Isi email
$messageBody = "--{$boundary}\r\n";
$messageBody .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
$messageBody .= "Content-Transfer-Encoding: 7bit\r\n";
$messageBody .= "\r\n{$body}\r\n";
$messageBody .= "--{$boundary}\r\n";
$messageBody .= "Content-Type: text/plain; name=\"{$filename}\"\r\n";
$messageBody .= "Content-Disposition: attachment; filename=\"{$filename}\"\r\n";
$messageBody .= "Content-Transfer-Encoding: base64\r\n";
$messageBody .= "\r\n" . chunk_split(base64_encode(file_get_contents($filename))) . "\r\n";
$messageBody .= "--{$boundary}--";

// Kirim email
if (mail($to, $subject, $messageBody, $headers)) {
    echo "Email berhasil dikirim!";
} else {
    echo "Gagal mengirim email.";
}

// Hapus file sementara
unlink($filename);
?>
