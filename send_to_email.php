<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

function sendInvoiceEmail($email, $invoicePath, $orderNumber) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sehatools@gmail.com';
        $mail->Password = 'keav xohf nzeu ifvs';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('sehatools@gmail.com', 'SEHATOOLS');
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
