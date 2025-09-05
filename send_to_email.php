<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require '/vendor/autoload.php';

// Load .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function sendInvoiceEmail($email, $invoicePath, $orderNumber) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = (int) $_ENV['SMTP_PORT'];

        $mail->setFrom($_ENV['SMTP_USER'], 'SEHATOOLS');
        $mail->addAddress($email);
        $mail->addAttachment($invoicePath);

        $mail->isHTML(true);
        $mail->Subject = 'Invoice for Your Order #' . $orderNumber;
        $mail->Body    = 'Dear Customer, <br> Here is your invoice for order #' . $orderNumber;

        $mail->send();
        echo "Invoice email sent.";
    } catch (Exception $e) {
        echo "Email failed to send. Error: {$mail->ErrorInfo}";
    }
}
?>
