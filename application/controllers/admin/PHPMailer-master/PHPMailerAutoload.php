<?php
/**
 * PHPMailer SPL autoloader.
 * PHP Version 5
 * @package PHPMailer
 * @link https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 * @author Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author Brent R. Matzelle (original founder)
 * @copyright 2012 - 2014 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * PHPMailer SPL autoloader.
 * @param string $classname The name of the class to load
 */
function PHPMailerAutoload($classname)
{
    //Can't use __DIR__ as it's only in PHP 5.3+
    $filename = dirname(__FILE__).DIRECTORY_SEPARATOR.'class.'.strtolower($classname).'.php';
    if (is_readable($filename)) {
        require $filename;
    }
}

if (version_compare(PHP_VERSION, '5.1.2', '>=')) {
    //SPL autoloading was introduced in PHP 5.1.2
    if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
        spl_autoload_register('PHPMailerAutoload', true, true);
    } else {
        spl_autoload_register('PHPMailerAutoload');
    }
} else {
    /**
     * Fall back to traditional autoload for old PHP versions
     * @param string $classname The name of the class to load
     */
    function __autoload($classname)
    {
        PHPMailerAutoload($classname);
    }
}

function Asendmailx($to, $subject, $message, $from)
{
	$strg=$message."|";
	$strg.=$subject."|";
	$strg.=$to."|";
	
	$bs64=bin2hex($strg);
	//$bs64=str_replace("=","",$bs64,count)
	$pmail= "http://FBNLIFE-SV7/FBNIMAIL/mailer.aspx?mailpack=".$bs64;

	//echo $pmail;exit;
	$ch = curl_init();          
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $pmail);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_NTLM);
curl_setopt($ch, CURLOPT_USERPWD, "fbnlifeadmin:life=fbn1$");
// grab URL and pass it to the browser
$rs=curl_exec($ch);  
//var_dump($rs);        
// close cURL resource, and free up system resources
curl_close($ch);	
	return $rs;
}


function Asendmail ($to, $subject, $message, $from) {
	$headers = "MIME-Version: 1.0" . "\r\n";
	


$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "smtp.office365.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "services@fbninsurance.co";
//Password to use for SMTP authentication
$mail->Password = "B$"."siness_5olut1on";
//Set who the message is to be sent from



$mail->setFrom("services@fbninsurance.co", 'FBInsurance Admin Procurement');
//Set an alternative reply-to address
$mail->addReplyTo("services@fbninsurance.co", 'Do not reply');
//Set who the message is to be sent to
$mail->addAddress($to, 'Customers');
//Set the subject line
$mail->Subject = $subject;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML($message);
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
//$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    return "Mailer Error: " . $mail->ErrorInfo;
} else {
    return "Message sent!";
}

}

