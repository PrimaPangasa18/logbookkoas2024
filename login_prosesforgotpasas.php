<?php
include "config.php";
require 'android/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
require 'android/vendor/autoload.php';
error_reporting(E_ALL ^ E_NOTICE);

if(isset($_REQUEST['commit']))
{
	$login=mysqli_query($con,"SELECT * FROM `admin` WHERE `username`='$_POST[username]'");
	$ketemu=mysqli_num_rows($login);
    

  // Apabila username dan password ditemukan
  if ($ketemu == 1 )
	{
		$data_log=mysqli_fetch_array($login);
		if ($data_log['level']=="5")
		{
            $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            mysqli_query($con, "UPDATE `admin` SET `stase`='$randomNumber' WHERE `username`='$_POST[username]'");
            $username = $_POST['username'];
			$biodata_mhsw=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_POST[username]'"));
			$nim = $biodata_mhsw['nim'];
            $email = $biodata_mhsw['email'];
            $nama = $biodata_mhsw['nama'];

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Host       = "ssl://smtp.gmail.com"; // SMTP server
                $mail->Username   = 'akhasadyst@gmail.com';                     //SMTP username
                $mail->Password   = 'ikrsmtxtodnnqalx';                               //SMTP password
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('akhasadyst@gmail.com', 'E Logbook Koas');
                $mail->addAddress($email);     //Add a recipient

                //Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Forgot Password Code';
                $mail->Body    = 'Hi <b>'.$nama.'</b>, This is the code for forgot password <b>'.$randomNumber.'</b>';
                
                $mail->send();
                
                echo "
                <script>
                    window.location.href=\"login_forgotpass2?username=$username\";
                </script>
                ";
               
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
		}
		
		
	}
    else
	{
		echo "
		<script>
			window.location.href=\"loginfailed\";
		</script>
		";
	}
}
else
{ echo "
		<script>
			window.location.href=\"login\";
		</script>
		";}
?>
