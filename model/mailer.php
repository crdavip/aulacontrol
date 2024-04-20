<?php

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

class Mailer
{
    function sendEmail($mail, $subject, $content)
    {
        require '../plugins/phpmailer/src/PHPMailer.php';
        require '../plugins/phpmailer/src/SMTP.php';
        require '../plugins/phpmailer/src/Exception.php';

        $phpmailer = new PHPMailer(true);

        try {
            //Server settings
            $phpmailer->SMTPDebug = SMTP::DEBUG_OFF;                         //Enable verbose debug output (SMTP::DEBUG_OFF)
            $phpmailer->isSMTP();                                            //Send using SMTP
            $phpmailer->Host       = 'smtp.hostinger.com';                   //Set the SMTP server to send through
            $phpmailer->SMTPAuth   = true;                                   //Enable SMTP authentication
            $phpmailer->Username   = 'no-reply@cristiandavid.com.co';        //SMTP username
            $phpmailer->Password   = 'Bmim940101**';                         //SMTP password
            $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $phpmailer->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $phpmailer->setFrom('no-reply@cristiandavid.com.co', 'Ucloth');
            $phpmailer->addAddress($mail);                                   //Add a recipient

            //Content
            $phpmailer->isHTML(true);                                        //Set email format to HTML
            $phpmailer->CharSet = 'UTF-8';
            $phpmailer->Subject = $subject;
            $phpmailer->Body    = $content;

            if ($phpmailer->send()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$phpmailer->ErrorInfo}";
            return false;
        }
    }
}
