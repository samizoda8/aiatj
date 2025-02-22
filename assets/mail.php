<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Убедись, что PHPMailer установлен через Composer

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST["phone"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($phone) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Заполните все поля корректно.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Настройки SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.yourdomain.com'; // Замени на SMTP своего почтового сервиса
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@yourdomain.com'; // Почта отправителя
        $mail->Password = 'your_password'; // Пароль от почты
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Отправитель и получатель
        $mail->setFrom('your_email@yourdomain.com', 'AI Academy');
        $mail->addAddress('info@aiacademy.tj', 'AI Academy'); // Основной email для приема сообщений
        $mail->addReplyTo($email, $name);

        // Контент письма
        $mail->isHTML(true);
        $mail->Subject = "Новое сообщение от $name";
        $mail->Body = "<h3>Новое сообщение:</h3>
                       <p><strong>Имя:</strong> $name</p>
                       <p><strong>Email:</strong> $email</p>
                       <p><strong>Телефон:</strong> $phone</p>
                       <p><strong>Сообщение:</strong> $message</p>";

        $mail->send();
        http_response_code(200);
        echo "Ваше сообщение успешно отправлено!";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Ошибка при отправке: {$mail->ErrorInfo}";
    }
} else {
    http_response_code(403);
    echo "Ошибка: доступ запрещен.";
}
?>
