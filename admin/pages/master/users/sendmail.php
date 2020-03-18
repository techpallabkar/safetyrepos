<?php
if( file_exists( '../../lib/PHPMailer/PHPMailerAutoload.php' ) ) {
	require '../../lib/PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->Host = 'mail.viavitae.co.in';
	$mail->Port = '25';
	$mail->SMTPAuth = true;
	$mail->Username = 'not.official@viavitae.co.in';
	$mail->Password = 'Test1234#';
	$mail->SMTPSecure = 'tls';
	$mail->From = "srimanta12@gmail.com";
	$mail->FromName = 'Admin';
	$mail->addAddress('sonasandip3@gmail.com', "Sandip Kapat");
	$mail->WordWrap = 50;
	$mail->isHTML(true);
	$mail->Subject = 'Test Subject';
	$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Message has been sent';
	}
}
