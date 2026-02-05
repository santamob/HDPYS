<?php




// erman sami demirer 2022-03-15
require_once('class.phpmailer.php');
require_once('class.smtp.php');
$Office365_mail = new PHPMailer(true);

$Office365_mail->IsSMTP();
$Office365_mail->SMTPAuth = true;

$Office365_mail->Host = "smtp.office365.com:587";
//$Office365_mail->Port = 587; //25; // or 587
// $Office365_mail->SMTPSecure = 'tls'; // if Port is 587
$Office365_mail->Username = "etkbilgilendirme@santafarma.net";
$Office365_mail->Password = "Rum35389";



// Typical mail data

$Office365_mail->SetFrom("edemirer@santafarma.com.tr", "onlinecode org");
$Office365_mail->addAddress("shaddeler@santafarma.com.tr");
$Office365_mail->Subject = "Add Your Subject";
$Office365_mail->Body    = "This is the HTML message body For <b>Setting up PHPMailer with Office365 SMTP using php</b>";

try
{
$Office365_mail->Send();
echo "Setting up PHPMailer with Office365 SMTP using php Success!";
}
catch(Exception $exception)
{
echo "<pre>";
var_dump($exception);
echo "</pre>";
//Something went bad
echo "PHPMailer with Office365 Fail :: " . $Office365_mail->ErrorInfo;
}

?>