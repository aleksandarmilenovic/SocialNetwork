<?php
require_once('PHPMailer/PHPMailerAutoload.php');

class Mail{


    public static function sendMail($subject,$body,$addres){
      $mail = new PHPMailer();
      $mail->isSMTP();
      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'ssl';
      $mail->Host = 'smtp.gmail.com';
      $mail->Port = '465';
      $mail->isHTML();
      $mail->Username = 'thecomicbookstore276@gmail.com';
      $mail->Password = 'PPeerroo';
      $mail->SetFrom('no-reply');
      $mail->Subject = $subject;
      $mail->Body = $body;
      $mail->AddAddress($addres);

      $mail->Send();
    }

}
 ?>
