<?php

namespace app\services;

// use PHPMailer\PHPMailer\PHPMailer;

class MailerService
{

    private $mail;
    function __construct()
    {
        // $this->mail = new PHPMailer();
        // $this->mail->isSMTP();
        // $this->mail->Host = 'smtp.gmail.com';
        // $this->mail->SMTPAuth = true;
        // $this->mail->Username = 'opcode3@gmail.com';
        // $this->mail->Password = 'idutpuxmgonnuipm';
        // $this->mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        // $this->mail->Port = 465;
        // $this->mail->isHTML(true);
        // $this->mail->setFrom('plan4retrieve@gmail.com', "Plan4Retrieve Team");
    }



    // function __construct()
    // {
    //     $this->mail = new PHPMailer();
    //     $this->mail->isSMTP();
    //     $this->mail->Username = 'support@plan4retrieve.com';
    //     $this->mail->Password = 'UNr&2Fjwif&E&?a';
    //     $this->mail->Host = 'https://sxb1plzcpnl504197.prod.sxb1.secureserver.net:2083/cpsess8863873726/frontend/jupiter/index.html?login=1&post_login=97224101631132';
    //     $this->mail->SMTPAuth = true;

    //     $this->mail->SMTPSecure = 'tls';
    //     $this->mail->Port = 587;

    //     $this->mail->isHTML(true);
    //     $this->mail->setFrom('support@plan4retrieve.com', "Plan4Retrieve Team");
    // }


    function sendNotification($fullname, $email, $number, $scammer, $amount)
    {

        $loginDateTime = date('Y-m-d H:i:s'); // Current date and time

        // Admin's email address (replace with the admin's actual email)
        $to = 'support@plan4retrieve.com';

        // Email content
        $subject = "User Login Notification";
        $message = "Dear Admin,<br/><br/>I want to notify you that a user has logged into their account.<br/><br/><b>User Information:</b><br/><br/><strong>- Fullname:</strong> $fullname<br/><strong>- Email:</strong> $email<br/><strong>- Phone number:</strong> $number<br/><strong>- Scam Broker/Platform:</strong> $scammer<br/><strong>- Amount Lost:</strong> $amount<br/><strong>- Created Date:</strong> $loginDateTime<br/><br/> Please review the details at your earliest convenience and advise on the necessary steps to address this matter. <br/><br/>Best Regards,<br/> <strong> Plan4Retrieve MailBot<strong>.";

        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;
        $this->mail->Body    = $message;
        if (!$this->mail->send()) {
            return "Mailer Error: ".$this->mail->ErrorInfo;
        } else {
            return "Message has been sent";
        }
    }

    function sendOTPNotification($otp, $to, $username)
    {

        // $requestedDateTime = date('Y-m-d H:i:s'); // Current date and time
        // $userIP = $_SERVER['REMOTE_ADDR']; // User's IP address

        $user = ucfirst($username);

        // Email content
        $subject = "Password Recovery OTP";
        $message = "
            <html>
            <head>
                <title>Password Recovery OTP</title>
            </head>
            <body>
                <p>Dear $user,</p>
                <p>You have requested to reset the password for your account at Runjikap App. If you did not make this request, please ignore this email.</p>
                <p>Use the following One-Time Password (OTP) to complete the password reset process:</p>
                <p><strong>OTP: $otp</strong></p>
                <p>Please enter this OTP on the password reset page within 2 hours. After this time, the OTP will expire, and you will need to request a new one.</p>
                <p>If you encounter any issues or did not initiate this password recovery, please contact our support team immediately at support@runjikap-finq.com or +1(301)372-9072.</p>
                <p>Thank you,<br>
                Runjikap Team</p>
            </body>
            </html>
        ";

        $this->mail->addAddress($to);
        $this->mail->Subject = $subject;
        $this->mail->Body    = $message;
        if (!$this->mail->send()) {
            return "Mailer Error: " . $this->mail->ErrorInfo;
        } else {
            return "Message has been sent";
        }
    }
}
