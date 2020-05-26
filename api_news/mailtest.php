<?php

    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    require 'PHPMailer.php';
    require 'SMTP.php';
    require 'Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    function sendMail($mail, $Username, $password, $to_mail, $to_name, $from_mail, $from_name, $subject, $content)
    {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->Mailer = "smtp";

        $mail->SMTPDebug  = 1;  
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";
        $mail->Port       = 587;
        $mail->Host       = "smtp.gmail.com";
        $mail->Username   = $Username; //"dev.berrahal@gmail.com";
        $mail->Password   = $password; "45619blender";

        $mail->IsHTML(true);
        $mail->AddAddress($to_mail, $to_name);
        $mail->SetFrom($from_mail, $from_name);
        $mail->Subject = $subject; //"Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
        $content = $content; //"<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";

        $mail->MsgHTML($content); 
        if(!$mail->Send())
        {
            return false;
        } else {
            return true;
        }
    }
    

?>