<?php
class Encription {
	private $key 		= '';
	private $iv_size 	= 0;
	private $iv 		= 0;
	
	public function __construct() {
		$this->key 		= pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
		$this->iv_size 	= mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
		$this->iv 		= mcrypt_create_iv($this->iv_size, MCRYPT_RAND);
	}
	
	public function vvs_encript( $plaintext = '' ) {
		$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $plaintext, MCRYPT_MODE_CBC, $this->iv);
		$ciphertext = $this->iv . $ciphertext;
		return base64_encode($ciphertext);
	}
	
	public function vvs_decript($ciphertext_base64 = '') {
		$ciphertext_dec = base64_decode($ciphertext_base64);
		$iv_dec 		= substr($ciphertext_dec, 0, $this->iv_size);
		$ciphertext_dec = substr($ciphertext_dec, $this->iv_size);
		return trim( mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec) );
	}
}
