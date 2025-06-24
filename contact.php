<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'] ?? '';
  $email = $_POST['email'] ?? '';
  $message = $_POST['message'] ?? '';

  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'youssizakaria681@gmail.com';
    $mail->Password = 'APP_PASSWORD_HNA';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom($email, $name);
    $mail->addAddress('youssizakaria681@gmail.com');
    $mail->Subject = 'New message from Youssi Chic contact form';
    $mail->Body = $message;

    $mail->send();
    $msg = "<p style='color:green;'>✅ Message sent successfully!</p>";
  } catch (Exception $e) {
    $msg = "<p style='color:red;'>❌ Failed to send: {$mail->ErrorInfo}</p>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact - Youssi Chic</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: #f8f8f8;
    }
    .contact-container {
      max-width: 600px;
      margin: 60px auto;
      background: #fff;
      padding: 30px;
      border-radius: 14px;
      box-shadow: 0 0 14px rgba(0, 0, 0, 0.05);
    }
    .contact-container h2 {
      text-align: center;
      color: #333;
    }
    form input, form textarea {
      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }
    form button {
      background-color: #d66b28;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
    }
    .contact-info {
      text-align: center;
      margin-top: 20px;
    }
    .contact-info p {
      margin: 10px 0;
      font-size: 16px;
    }
    .contact-info img {
      width: 20px;
      vertical-align: middle;
      margin-right: 8px;
    }
  </style>
</head>
<body>
  <div class="contact-container">
    <h2>Contact Us</h2>
    <?= $msg ?>
    <form method="POST">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" rows="6" placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>

    <div class="contact-info">
      <p>
        <img src="images/icons/gmail.png" alt="Email">
        <a href="mailto:youssizakaria681@gmail.com">youssizakaria681@gmail.com</a>
      </p>
      <p>
        <img src="images/icons/whatsapp.png" alt="WhatsApp">
        <a href="https://wa.me/212712439464" target="_blank">+212 712 439 464</a>
      </p>
    </div>
  </div>
</body>
</html>






