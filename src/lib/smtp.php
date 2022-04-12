<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/vendor/PHPMailer/src/Exception.php';
require_once dirname(__FILE__,3) . '/vendor/PHPMailer/src/PHPMailer.php';
require_once dirname(__FILE__,3) . '/vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class MAILER{

	public $Mailer; // Contains the PHPMailer Class
	public $Status = false;
  protected $Language; // Contains the Language Class
	protected $URL; // Contains the main URL
	protected $Brand = "Mailer"; // Contains the brand name
	protected $Links = []; // Contains the various links required

	public function __construct($smtp = null,$languageArray = []){
		// Setup Language
		$this->Language = $languageArray;

		// Setup URL
		if(isset($_SERVER['HTTP_HOST'])){
			$this->URL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://";
			$this->URL .= $_SERVER['HTTP_HOST'].'/';
		}

		// Setup PHPMailer
		if($smtp != null && $this->login($smtp['username'],$smtp['password'],$smtp['host'],$smtp['port'],$smtp['encryption'])){
			$this->Status = true;
			$this->Mailer = new PHPMailer(true);
			$this->Mailer->isSMTP();
	    $this->Mailer->Host = $smtp['host'];
	    $this->Mailer->SMTPAuth = true;
	    $this->Mailer->Username = $smtp['username'];
	    $this->Mailer->Password = $smtp['password'];
			$this->Mailer->SMTPDebug = false;
			if(in_array($smtp['encryption'],['STARTTLS','starttls'])){ $this->Mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; }
			if(in_array($smtp['encryption'],['SSL','ssl'])){
				$this->Mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				$this->Mailer->SMTPOptions = [
					'ssl' => [
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					]
				];
			}
	    $this->Mailer->Port = $smtp['port'];
		}
	}

	public function customization($brand = "Mailer",$links = []){
		$this->Brand = $brand;
		if(isset($links['support'])){ $this->Links['support'] = $links['support']; }
		if(isset($links['trademark'])){ $this->Links['trademark'] = $links['trademark']; }
		if(isset($links['policy'])){ $this->Links['policy'] = $links['policy']; }
		if(isset($links['logo'])){ $this->Links['logo'] = $links['logo']; }
	}

	public function isConnected(){
		return is_bool($this->Status) && $this->Status ? true:false;
	}

	public function login($username,$password,$host,$port,$encryption = null){
		//Create a new SMTP instance
		$smtp = new SMTP;
		//Enable connection-level debug output
		// $smtp->do_debug = SMTP::DEBUG_CONNECTION;
		try {
			$options = [];
			// Set Encryption
			if(in_array($encryption,['SSL','ssl'])){
				$host = 'ssl://'.$host;
				$options = [
					'ssl' => [
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					]
				];
			}
	    //Connect to an SMTP server
	    if (!$smtp->connect($host,$port,30,$options)) { throw new Exception('Connect failed'); }
	    //Say hello
	    if (!$smtp->hello(gethostname())) { throw new Exception('EHLO failed: ' . $smtp->getError()['error']); }
	    //Get the list of ESMTP services the server offers
	    $e = $smtp->getServerExtList();
	    //If server can do TLS encryption, use it
	    if (is_array($e) && array_key_exists('STARTTLS', $e)) {
        $tlsok = $smtp->startTLS();
        if (!$tlsok) {
          throw new Exception('Failed to start encryption: ' . $smtp->getError()['error']);
        }
        //Repeat EHLO after STARTTLS
        if (!$smtp->hello(gethostname())) {
          throw new Exception('EHLO (2) failed: ' . $smtp->getError()['error']);
        }
        //Get new capabilities list, which will usually now include AUTH if it didn't before
        $e = $smtp->getServerExtList();
	    }
	    //If server supports authentication, do it (even if no encryption)
	    if (is_array($e) && array_key_exists('AUTH', $e)) {
        if ($smtp->authenticate($username, $password)) {
          return true;
        } else {
          throw new Exception('Authentication failed: ' . $smtp->getError()['error']);
        }
	    }
		} catch (Exception $e) {
			error_log('SMTP error: '.$e->getMessage(), 0);
	    return false;
		}
	}

	public function send($email, $message, $extra = []){
		$this->Mailer->ClearAllRecipients();
		if(isset($extra['subject'])){ $this->Mailer->Subject = $extra['subject']; }
		else { $this->Mailer->Subject = $this->Brand; }
		if(isset($extra['from'])){ $this->Mailer->setFrom($extra['from']); }
		else { $this->Mailer->setFrom($this->Mailer->Username, $this->Brand); }
		if(isset($extra['replyto'])){ $this->Mailer->addReplyTo($extra['replyto']); }
		$this->Mailer->addAddress($email);
		$this->Mailer->clearAttachments();
		if((isset($extra['attachments']))&&(is_array($extra['attachments']))){
			foreach($extra['attachments'] as $attachment){
				$this->Mailer->addAttachment($attachment);
			}
		}
		$this->Mailer->isHTML(true);
		if(isset($extra['subject'])){ $this->Mailer->Subject = $extra['subject']; }
		else { $this->Mailer->Subject = $this->Brand; }
		$acceptReplies = false;
		if(isset($extra['acceptReplies']) && ($extra['acceptReplies'] == false || $extra['acceptReplies'] == 'false')){$acceptReplies = true;}
		$this->Mailer->Body = '';
		$this->Mailer->Body .= '
		<meta http-equiv="Content-Type" content="text/html">
		<meta name="viewport" content="width=device-width">
		<style type="text/css">
			a { text-decoration: none; color: #0088CC; }
			a:hover { text-decoration: underline }
			body {
				font-size: 18px;
				width: 100% !important;
				background-color: white;
				margin: 0;
				padding: 0;
				font-family:\'Helvetica Neue\',\'Arial\',\'Helvetica\',\'Verdana\',sans-serif;
				color: #333333;
				line-height: 26px;
			}
		</style>
		<meta name="format-detection" content="telephone=no">
		<table style="border-collapse: collapse;" width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" align="center">
			<tbody>
				<tr><td class="top-padding" style="line-height:120px;" width="100%">&nbsp;</td></tr>
				<tr>
					<td valign="top">
						<table style="border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0" border="0">
							<tbody>
								<tr style="width:100%!important;" align="center">
									<td>
										<table style="border-collapse: collapse;" width="692" cellspacing="0" cellpadding="0" border="0" align="center">
											<tbody>
												<tr width="100%" border="0" cellspacing="0" cellpadding="0">
													<td style="padding:0px 0px 0px 0px;" align="center">
														<div style="color:#495057; text-align: center; padding-bottom: 20px;">';
														if(isset($this->Links['logo']) && $this->Links['logo'] != '' && $this->Links['logo'] != '#'){
															$this->Mailer->Body .= '<img src="'.$this->Links['logo'].'" style="width:100px;vertical-align: middle;border-style: none; margin-right: 24px;">';
														}
														$this->Mailer->Body .= '<b style="font-family:\'Helvetica Neue\',\'Arial\',\'Helvetica\',\'Verdana\',sans-serif; font-size:52px; font-weight: 200; line-height:56px;vertical-align: -20px;">'.$this->Brand.'</b>
														</div>
													</td>
												</tr>';
												if(isset($extra['title'])){
													$this->Mailer->Body .= '
														<tr>
															<td style="padding:0px 0px 0px 0px;" valign="top" align="center">
																<table style="border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
																	<tbody>
																		<tr align="center">
																			<td class="heading" style="font-family:\'Helvetica Neue\',\'Arial\',\'Helvetica\',\'Verdana\',sans-serif; font-size:36px; line-height:40px; font-weight: 200;padding:20px 0px 32px 0px; margin:0; border: 0; display:block; text-align:center;" width="90%" align="center">'.$extra['title'].'</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>';
													}
		$this->Mailer->Body .= '
											</tbody>
										</table>
										<table style="border-collapse: collapse; min-height:425px;" width="692px" cellspacing="0" cellpadding="0" border="0" align="center">
											<tbody>
												<tr>
													<td style="color:#333333; padding:0px 0px 64px 0px; margin:0px;vertical-align: text-top;" class="emailcontent" width="692px">
														<table style="border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
															<tbody>
																<tr>
																	<td>
																		<table style="border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
																			<tbody>
																				<tr>
																					<td style="padding:7px 0 19px; margin:0; font-family:\'Helvetica Neue\',\'Arial\',\'Helvetica\',\'Verdana\',sans-serif; color: #333333;font-size:18px; line-height: 26px; width:692px; text-align:justify">
																						'.$message.'
																					</td>
																				</tr>
																			</tbody>
																		</table>';
		$this->Mailer->Body .= '
																		<table style="border-collapse: collapse;" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
																			<tbody>
																				<tr>
																					<td style="padding:7px 0 19px; margin:0; font-family:\'Helvetica Neue\',\'Arial\',\'Helvetica\',\'Verdana\',sans-serif; color: #333333;font-size:18px; line-height: 26px; width:692px">
																						Sincerely,<br>
																						'.$this->Brand.'
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>';
							$this->Mailer->Body .= '
								<tr style="width:100%!important; background-color:#343A40;" align="center">
									<td class="footer" style="padding-top: 64px; padding-bottom: 64px">
										<table style="border-collapse: collapse;" width="692" cellspacing="0" cellpadding="0" border="0" align="center">
											<tbody>
												<tr width="100%" border="0" cellspacing="0" cellpadding="0">
													<td style="font-family:\'Helvetica Neue\',\'Arial\',\'Helvetica\',\'Verdana\',sans-serif;color:#999999; text-align:center; font-size:12px; line-height:16px; padding:4px;" align="center">
														TM and copyright &copy; '.date('Y').'
													</td>
												</tr>
												<tr width="100%" border="0" cellspacing="0" cellpadding="0">
													<td style="font-family:\'Helvetica Neue\',\'Arial\',\'Helvetica\',\'Verdana\',sans-serif;text-align:center; font-size:12px; line-height:16px; color:#999999" align="center">';
														if(isset($this->Links['trademark']) && $this->Links['trademark'] != '' && $this->Links['trademark'] != '#'){
															$this->Mailer->Body .= '<a style="margin-left:4px;margin-right:4px;color:#ffffff;" href="'.$this->Links['trademark'].'" moz-do-not-send="true">All Rights Reserved</a>';
														}
														if(isset($this->Links['policy']) && $this->Links['policy'] != '' && $this->Links['policy'] != '#'){
															$this->Mailer->Body .= '<a style="margin-left:4px;margin-right:4px;color:#ffffff;" href="'.$this->Links['policy'].'" moz-do-not-send="true">Privacy Policy</a>';
														}
														if(isset($this->Links['support']) && $this->Links['support'] != '' && $this->Links['support'] != '#'){
															$this->Mailer->Body .= '<a style="margin-left:4px;margin-right:4px;color:#ffffff;" href="'.$this->Links['support'].'" moz-do-not-send="true">Support</a>';
														}
													$this->Mailer->Body .= '
													</td>
												</tr>';
												if($acceptReplies){
													$this->Mailer->Body .= '
														<tr width="100%" border="0" cellspacing="0" cellpadding="0">
															<td style="font-family:\'Helvetica Neue\',\'Arial\',\'Helvetica\',\'Verdana\',sans-serif;color:#999999; text-align:center; font-size:12px; line-height:16px; padding:4px;padding-top:32px; " align="center">
																This message was sent to you from an email address that does not accept incoming messages.<br>
																Any replies to this message will not be read. If you have questions, please visit <a href="'.$this->URL.'?p=contact" style="color: #ffffff" moz-do-not-send="true">'.$this->URL.'?p=contact</a>.
															</td>
														</tr>';
													}
		$this->Mailer->Body .= '
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		';
		try { $this->Mailer->send(); return true; }
		catch (phpmailerException $e) { error_log($e, 0);return false; }
		catch (Exception $e) { error_log($e, 0);return false; }
	}
}
