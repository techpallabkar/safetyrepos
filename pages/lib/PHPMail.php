<?php
class PHPMail {
	public $path 		= '';
	public $to_email 	= '';
	public $to_name 	= '';
	public $from_email 	= '';
	public $from_name 	= '';
	public $subject 	= '';
	public $body 		= '';
	public $attachment = '';
	public $SMTPDebug 	= 0;
	public $Debugoutput = 'html';
	public $isHTML 		= TRUE;
	
	private $WordWrap = 50;
	
	public function send_mail() {
		
		if( $this->to_email == '' ) return 'Mail to email address is empty.';
		if( !file_exists( LIB_PATH . '/PHPMailer/PHPMailerAutoload.php' ) ) return 'PHPMailer library file not included.';
		
		require_once( LIB_PATH . '/PHPMailer/PHPMailerAutoload.php' );
		
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug  = $this->SMTPDebug;
		$mail->Debugoutput = $this->Debugoutput;
		
		$mail->addAddress($this->to_email, $this->to_name);
		$mail->SetFrom($this->from_email, $this->from_name);
		$mail->MsgHTML($this->body);
		if( $this->attachment != '' ) {
			$file_name = @end( explode( '/', $this->attachment ) );
			$mail->AddAttachment($this->attachment, $file_name);
		}
		$mail->WordWrap = $this->WordWrap;
		$mail->isHTML($this->isHTML);
		$mail->Subject = $this->subject;
		$mail->Body    = $this->body;
		
		return $mail->send();
	}
	
	public function sendCustomMail($basePAth = './', $toMail = '', $toName = '', $fromMail = '', $fromName = '', $subject = '', $messageBody = '', $attachments = '') {
		if( !file_exists( $basePAth.'PHPMailer/PHPMailerAutoload.php' ) ) return false;
		
		if( $toMail == "" || $messageBody == "" ) return false;

		require_once ($basePAth.'PHPMailer/PHPMailerAutoload.php');
		
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug  = 0;
		$mail->Debugoutput = 'html';

		if( $fromName != "" ) $mail->FromName = $fromName;
		if( $fromMail != "" ) $mail->SetFrom($fromMail, $fromName);

		$mail->addAddress($toMail, $toName);
		$mail->MsgHTML($messageBody);
		if( $attachments != '' ) {
			$fileNameArr 	= explode( '/',  $attachments);
			$fileName 	= end($fileNameArr);
			$mail->AddAttachment($attachments, $fileName);
		}
		$mail->WordWrap = 50;
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $messageBody;

		if(!$mail->send()) {
			return false;
		}
		return true;
	}
}
