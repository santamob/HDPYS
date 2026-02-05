<?php


//require_once($_SERVER["DOCUMENT_ROOT"]."/third_party/mailler/class.phpmailer.php");

include APPPATH . "/third_party/mailler/class.phpmailer.php";

class My_Email_Test {
	
	public $mail;
	
	function __construct() {
		$this->mail = new PHPMailer();
	}
	
	public function sendEmail($subject, $body, $alici, $FromName,$cc,$with_pern){

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
		
		//$cc = "yguler@santafarma.com.tr";


		// smtp adres: smtp.office365.com
		// port:587
		// ssl: tls
		// mail adresi: yanitlama@santafarma.net
		// şifre: bfkwmwntwbscppdy
		
		$this->mail->IsSMTP(); // send via SMTP 
		//$this->mail->Host  = "172.20.20.5";
		//$mail->IsSMTP(); // send via SMTP 
		//$this->mail->Host     = "smtp.office365.com:587"; // SMTP servers 

		//$this->mail->Host  =  "192.168.1.40";
		$this->mail->Host     = "smtp.office365.com"; // SMTP servers 
		$this->mail->SMTPSecure = "startTls";
		// $this->mail->SMTPOptions = array(
		// 	'ssl' => array(
		// 		'verify_peer' => false,
		// 		'verify_peer_name' => false,
		// 		'allow_self_signed' => true
		// 	)
		// );

		$this->mail->port = 587;
	    //$this->mail->SMTPAuth = true; // turn on SMTP authentication 
		$this->mail->Username = "yanitlama@santafarma.com.tr"; // SMTP username 
		$this->mail->Password = "bfkwmwntwbscppdy"; // SMTP password  

		//$this->mail->Password ="13.Cuma2000**";


		$this->mail->From     = "yanitlama@santafarma.net"; 
		//$this->mail->From  = "webmail.santafarma.com.tr";
		//$this->mail->From  = "web@santafarma.net";
		$this->mail->FromName = $FromName;

		if($with_pern){
			$this->mail->AddAddress('ckasapoglu@santafarma.com.tr');
		}
		else{
			$this->mail->AddAddress($alici);
		}
		

		if(!is_null($cc)){
			$this->mail->AddCC($cc);
		}
		// var_dump($cc);
		// exit();
		//$mail->AddBCC('edemirer@santafarma.com.tr');	
		$this->mail->IsHTML(true);
		$this->mail->CharSet = "utf-8";
		//$this->mail->CharSet = 'UTF-8';
		//$this->mail->Encoding = 'base64';
		$this->mail->SMTPDebug  = 3;

		
		$this->mail->Subject  =  $subject;
		$this->mail->Body  =  $body;
		
		$sonuc = $this->mail->Send();
		return $sonuc;
		
	} 
	
}




?>