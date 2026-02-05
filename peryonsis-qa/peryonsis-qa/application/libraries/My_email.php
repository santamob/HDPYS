<?php


//require_once($_SERVER["DOCUMENT_ROOT"]."/third_party/mailler/class.phpmailer.php");

include APPPATH . "/third_party/mailler/class.phpmailer.php";

class My_Email {
	
	public $mail;
	
	function __construct() {
		
		$this->mail = new PHPMailer();
	}
	
	public function sendEmail($subject, $body, $alici, $FromName, $cc=''){

		// $this->mail->IsSMTP(); // send via SMTP 
		// //$this->mail->Host     = "smtp.office365.com:25"; // SMTP servers 
		// $this->mail->Host     = "santafarma-net.mail.protection.outlook.com:25";
		// //$this->mail->SMTPSecure = "tls";
		// $this->mail->port = 25;
		// //$this->mail->SMTPAuth = true; // turn on SMTP authentication 
		// //$this->mail->Username = "yanitlama@santafarma.net"; // SMTP username 
		// //$this->mail->Password = "96241119aA"; // SMTP password 
		// $this->mail->From     = "yanitlama@santafarma.net"; 
		// $this->mail->FromName = $FromName;
		// $this->mail->AddAddress($alici);
		// $this->mail->IsHTML(true);
		// $this->mail->CharSet = "utf-8";
		
		
		$this->mail->IsSMTP(); // send via SMTP 
		$this->mail->Host  = "172.20.20.5";
		//$mail->IsSMTP(); // send via SMTP 
		//$mail->Host     = "smtp.office365.com:25"; // SMTP servers 
		//$mail->SMTPSecure = "tls";
		//$mail->port = 25;
	   /*  $mail->SMTPAuth = true; // turn on SMTP authentication 
		$mail->Username = "yanitlama@santafarma.net"; // SMTP username 
		$mail->Password = "96241119aA"; // SMTP password  */
		$this->mail->From     = "yanitlama@santafarma.net"; 
		$this->mail->FromName = $FromName;


		$this->mail->IsHTML(true);
		$this->mail->CharSet = "utf-8";
		
		$this->mail->Subject  =  $subject;
		$this->mail->Body  =  $body;

		
		if(is_array($alici))
		{
			foreach ($alici as $to) {
				$this->mail->AddAddress($to);
			}
		}
		else
		{
			$this->mail->AddAddress($alici);
		}


		$this->mail->AddCC($cc);	
		
		
		$sonuc = $this->mail->Send();
		return $sonuc;
	} 
	
}




?>