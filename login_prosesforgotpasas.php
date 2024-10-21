<?php
include "config.php";
require 'android/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
require 'android/vendor/autoload.php';
error_reporting(E_ALL ^ E_NOTICE);

if (isset($_REQUEST['commit'])) {
    $login = mysqli_query($con, "SELECT * FROM `admin` WHERE `username`='$_POST[username]'");
    $ketemu = mysqli_num_rows($login);


    // Apabila username dan password ditemukan
    if ($ketemu == 1) {
        $data_log = mysqli_fetch_array($login);
        if ($data_log['level'] == "5") {
            $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            mysqli_query($con, "UPDATE `admin` SET `code`='$randomNumber' WHERE `username`='$_POST[username]'");
            $username = $_POST['username'];
            $biodata_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_POST[username]'"));
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
                $mail->Body = '
                    <html>
                    <head>
                    <style>
                        .email-container {
                            background-color: #e3f2fd;
                            padding: 20px;
                            font-family: Arial, sans-serif;
                            color: #333333;
                        }
                        .email-content {
                            background-color: #fdf5e6;
                            border: 1px solid #dddddd;
                            padding: 20px;
                            text-align: center;
                            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                            border-radius: 10px;
                        }
                        .header {
                            margin-bottom: 20px;
                            text-align: center;
                            background-color: #28a745;
                            padding: 15px;
                            border-radius: 10px;
                        }
                        .header img {
                            width: 100px;
                        }
                        .code {
                            font-size: 24px;
                            color: #ffffff;
                            font-weight: bold;
                            background-color: #ff5722;
                            padding: 10px 20px;
                            display: inline-block;
                            border-radius: 5px;
                            margin-top: 20px;
                        }
                        .text-large {
                            font-size: 18px;
                        }
                    </style>
                    </head>
                    <body>
                    <div class="email-container">
                        <div class="header">
                            <img src="https://logbook-fk.apps.undip.ac.id/koas/images/undipsolid.png" alt="Universitas Diponegoro Logo">
                        </div>
                        <div class="email-content">
                            <p class="text-large">Hi <b>' . $nama . '</b>,</p>
                            <p class="text-large">This is the code for your E-Logbook Koas password reset:</p>
                            <p class="code">' . $randomNumber . '</p>
                            <p class="text-large">Please use this code to reset your password.</p>
                        
                        </div>
                    </div>
                    </body>
                    </html>';


                $mail->send();

                echo "
                <script>
                    window.location.href=\"login_forgotpass2?username=$username&email=$email\";
                </script>
                ";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $randomNumber = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            mysqli_query($con, "UPDATE `admin` SET `code`='$randomNumber' WHERE `username`='$_POST[username]'");
            $username = $_POST['username'];
            $dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$_POST[username]'"));
            $nim = $dosen['nip'];
            $email = $dosen['email'];
            $nama = $dosen['nama'];

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
                $mail->Body =
                    '
                  <html>
                    <head>
                    <style>
                        .email-container {
                            background-color: #e3f2fd;
                            padding: 20px;
                            font-family: Arial, sans-serif;
                            color: #333333;
                        }
                        .email-content {
                            background-color: #fdf5e6;
                            border: 1px solid #dddddd;
                            padding: 20px;
                            text-align: center;
                            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                            border-radius: 10px;
                        }
                        .header {
                            margin-bottom: 20px;
                            text-align: center;
                            background-color: #28a745;
                            padding: 15px;
                            border-radius: 10px;
                        }
                        .header img {
                            width: 100px;
                        }
                        .code {
                            font-size: 24px;
                            color: #ffffff;
                            font-weight: bold;
                            background-color: #ff5722;
                            padding: 10px 20px;
                            display: inline-block;
                            border-radius: 5px;
                            margin-top: 20px;
                        }
                        .text-large {
                            font-size: 18px;
                        }
                    </style>
                    </head>
                    <body>
                    <div class="email-container">
                        <div class="header">
                            <img src="https://logbook-fk.apps.undip.ac.id/koas/images/undipsolid.png" alt="Universitas Diponegoro Logo">
                        </div>
                        <div class="email-content">
                            <p class="text-large">Hi <b>' . $nama . '</b>,</p>
                            <p class="text-large">This is the code for your E-Logbook Koas password reset:</p>
                            <p class="code">' . $randomNumber . '</p>
                            <p class="text-large">Please use this code to reset your password.</p>
                        
                        </div>
                    </div>
                    </body>
                    </html>';



                $mail->send();

                echo "
                <script>
                    window.location.href=\"login_forgotpass2?username=$username&email=$email\";
                </script>
                ";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    } else {
        echo "
		<script>
			window.location.href=\"loginfailed\";
		</script>
		";
    }
} else {
    echo "
		<script>
			window.location.href=\"login\";
		</script>
		";
}
