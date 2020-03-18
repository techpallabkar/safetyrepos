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
	
	public function send() {
		
		if( $this->to_email == '' ) return 'Mail to email address is empty.';
		if( !file_exists( $this->path . 'PHPMailer/PHPMailerAutoload.php' ) ) return 'PHPMailer library file not included.';
		
		require_once( $this->path . 'PHPMailer/PHPMailerAutoload.php' );
		
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug  = $this->SMTPDebug;
		$mail->Debugoutput = $this->Debugoutput;
		
		$mail->addAddress($this->to_email, $this->to_email);
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
}
