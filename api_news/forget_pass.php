<?php
    // Connect to database
    
    include("db_connect.php");
    
    require 'PHPMailer.php';
    require 'SMTP.php';
    require 'Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $request_method = $_SERVER["REQUEST_METHOD"];
    
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

        $mail->SMTPDebug  = 0;

        $mail->MsgHTML($content); 
        if(!$mail->Send())
        {
            return false;
        } else {
            return true;
        }
    }


	function forgetPass($email)
	{
        global $conn;
        global $mail;

		$query = "SELECT * FROM user WHERE email= '".$email."' ";

		$response = array();
		$result = mysqli_query($conn, $query);
		$rowcount = mysqli_num_rows($result);

		if($rowcount == 1)
		{
			while($row = mysqli_fetch_array($result))
			{
				$response[] = $row;
            }
            

            $content = "<p>Hi ".$response[0]["fname"].",<br />
            <br />
            You are receiving this message because you have requested your password on the DailyNews App.<br />
            <strong>Your password is : ".$response[0]["password"]." .</strong><br />
            <br />
            <br />
            <br />
            If you have not requested your password, you can just delete this email.<br />
            <br />
            Thank you for your trust in our solutions,<br />
            DailyNews Team</p>";


            if(sendMail($mail, "dev.berrahal@gmail.com", "45619blender", $response[0]["email"], $response[0]["fname"]." ".$response[0]["lname"], "Daily-News@gmail.com", "Daily News", "DailyNews - Password request", $content))
            {
                $response=array
                (
                    'status' => 1,
                    'status_message' =>'success'
                );
                echo json_encode($response, JSON_PRETTY_PRINT);
            }else
            {
                $response=array
                (
                    'status' => 0,
                    'status_message' =>'Mot de pass inccorect . '. mysqli_error($conn)
                );
                echo json_encode($response, JSON_PRETTY_PRINT);
            }
		}else
		{
			$response=array
			(
				'status' => 0,
				'status_message' =>'Mot de pass inccorect . '. mysqli_error($conn)
			);
			echo json_encode($response, JSON_PRETTY_PRINT);
		}	
	}
	
	switch($request_method)
	{
		case 'GET':
			// Retrive Products
			if(!empty($_GET["email"]))
			{
				$email=$_GET["email"];
				forgetPass($email);

			}
			break;
			
		default:
			// Invalid Request Method
			header("HTTP/1.0 405 Method Not Allowed");
			break;
	}
?>