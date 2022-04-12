<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/api.php';

class INSTALLER extends API {

	protected $Log;
	protected $Validate = [];
  protected $Steps = 2;
  protected $Progress = 0;
  protected $Database;
  protected $SMTP;
	protected $Structure = [];
	protected $Skeleton = [];

  protected function success(){
		$this->log("Installation has completed successfully at ".date("Y-m-d H:i:s")."!");
		file_put_contents(dirname(__FILE__,3) . '/tmp/progress.install', 'success'.PHP_EOL , LOCK_EX);
		return [
			"success" => $this->getField('Installation has completed'),
			"output" => [
				"status" => $this->isInstalled(),
			],
		];
  }

  protected function error($log = [], $force = false){
    if($this->Debug){ $this->log(json_encode($log, JSON_PRETTY_PRINT)); }
		file_put_contents(dirname(__FILE__,3) . '/tmp/progress.install', 'error'.PHP_EOL , LOCK_EX);
		if(is_file(dirname(__FILE__,3) . '/config/config.json')){ unlink(dirname(__FILE__,3) . '/config/config.json'); }
		return [
			"error" => $this->getField('Installation terminated with error'),
			"output" => [
				"status" => $this->isInstalled(),
				"currentProgress" => $this->Progress,
				"endProgress" => $this->Steps,
			],
		];
  }

  protected function log($txt = " ", $force = false){
    return file_put_contents($this->Log, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
  }

  protected function addProgress(){
    $this->Progress++;
		file_put_contents(dirname(__FILE__,3) . '/tmp/progress.install', $this->Progress.PHP_EOL , LOCK_EX);
  }

  public function init($settings = []){

    // Init Installer
    if(is_file(dirname(__FILE__,3) . '/tmp/complete.install')){ unlink(dirname(__FILE__,3) . '/tmp/complete.install'); }
		if(is_file(dirname(__FILE__,3) . '/tmp/progress.install')){ unlink(dirname(__FILE__,3) . '/tmp/progress.install'); }
		$configuration = [];

    // Init Log
    $this->Log = dirname(__FILE__,3) . '/tmp/install.log';
    if(is_file($this->Log)){ unlink($this->Log); }
    $this->log("===========================================================================");
    $this->log("  Installation Log ".date("Y-m-d H:i:s")."");
    $this->log("===========================================================================");
    $this->log("");

		// Init Steps
		foreach($settings as $validation => $configs){
			array_push($this->Validate,$validation);
			switch($validation){
				case'smtp':
					$this->Steps = $this->Steps + 5;
					break;
				case'imap':
					$this->Steps = $this->Steps + 4;
					break;
				case'general':
					$this->Steps = $this->Steps + 8;
					break;
				case'sql':
					$this->Steps = $this->Steps + 4 + 8;
					if(file_exists(dirname(__FILE__,3) . '/dist/data/structure.json')){
				    $this->Structure = json_decode(file_get_contents(dirname(__FILE__,3) . '/dist/data/structure.json'),true);
						$this->Steps = $this->Steps + count($this->Structure);
					}
					if(file_exists(dirname(__FILE__,3) . '/dist/data/skeleton.json')){
				    $this->Skeleton = json_decode(file_get_contents(dirname(__FILE__,3) . '/dist/data/skeleton.json'),true);
						$this->Steps = $this->Steps + count($this->Skeleton);
					}
					break;
			}
		}
    file_put_contents(dirname(__FILE__,3) . '/tmp/complete.install', $this->Steps.PHP_EOL , FILE_APPEND | LOCK_EX);

    // Verify if alreay installed
    if($this->isInstalled()){
      $this->log("Application is already installed!");
      return $this->error($settings);
    } else { $this->addProgress(); }

    // Validation & Configuration
		// IMAP
		if(in_array('imap',$this->Validate)){
			if(isset($settings['imap'])){
				$this->addProgress();
				if(isset($settings['imap']['host'],$settings['imap']['username'],$settings['imap']['password'],$settings['imap']['port'],$settings['imap']['encryption'])){
					$this->addProgress();
					if($this->Auth->IMAP->login($settings['imap']['username'], $settings['imap']['password'],$settings['imap']['host'],$settings['imap']['port'],$settings['imap']['encryption'])){
						$this->addProgress();
						$this->log("IMAP Authenticated");
						$configuration['imap'] = $settings['imap'];
			      $this->log("IMAP Set!");
						$this->addProgress();
					} else {
						$this->log("Unable to authenticate on IMAP server");
			      return $this->error($settings);
					}
				} else {
					$this->log("Missing IMAP settings");
					return $this->error($settings);
				}
	    } else {
	      $this->log("No IMAP settings provided!");
	      return $this->error($settings);
	    }
		}
		// SMTP
		if(in_array('smtp',$this->Validate)){
			if(isset($settings['smtp'])){
				$this->addProgress();
				if(isset($settings['imap']['host'],$settings['imap']['username'],$settings['imap']['password'],$settings['imap']['port'],$settings['imap']['encryption'])){
					$this->addProgress();
					if($this->Auth->SMTP->login($settings['smtp']['username'],$settings['smtp']['password'],$settings['smtp']['host'],$settings['smtp']['port'],$settings['smtp']['encryption'])){
						$this->addProgress();
						$this->log("SMTP Authenticated");
						$configuration['smtp'] = $settings['smtp'];
			      $this->log("SMTP Set!");
						$this->addProgress();
						$this->SMTP = new MAILER($settings['smtp'],$this->Fields);
					} else {
						$this->log("Unable to authenticate on SMTP server");
			      return $this->error($settings);
					}
				} else {
					$this->log("Missing SMTP settings");
					return $this->error($settings);
				}
	    } else {
	      $this->log("No SMTP settings provided!");
	      return $this->error($settings);
	    }
		}
		// SQL
		if(in_array('sql',$this->Validate)){
			if(isset($settings['sql'])){
				$this->addProgress();
	      if(isset($settings['sql']['host'],$settings['sql']['username'],$settings['sql']['password'],$settings['sql']['database'])){
					$this->addProgress();
					$this->Database = new Database($settings['sql']['host'],$settings['sql']['username'],$settings['sql']['password'],$settings['sql']['database']);
					if($this->Database->isConnected()){
						$configuration['sql'] = $settings['sql'];
			      $this->log("SQL Set!");
						$this->addProgress();
						// Purge Database
						foreach($this->Database->getTables() as $table){
							$this->log("Dropping $table from ".$settings['sql']['database']);
							$this->Database->query('DROP TABLE `'.$table.'`');
							$this->log("$table dropped");
						}
						$this->log("Database Cleared!");
						$this->addProgress();
						// Create Database Structure
						foreach($this->Structure as $table => $frames){
							$this->log("Creating $table");
							$structure = [];
							$structure[$table] = $frames;
							$this->Database->restoreStructure($structure);
							$this->log("$table created");
							$this->addProgress();
						}
						// Insert Default Records
						$file = dirname(__FILE__,3) . '/dist/data/skeleton.json';
            $this->log("Restoring database skeleton data from ".$file, true);
            $this->Skeleton = json_decode(file_get_contents($file),true);
						foreach($this->Skeleton as $table => $records){
							$this->log("Starting $table restoration");
							$skeleton = [];
							$skeleton[$table] = $records;
							$this->Database->restoreData($skeleton);
							$this->log("$table restored");
							$this->addProgress();
						}
						$this->log("Database skeleton data restored");
					} else {
						$this->log("Unable to connect to SQL Database");
			      return $this->error($settings);
					}
				} else {
		      $this->log("Missing SQL settings");
		      return $this->error($settings);
		    }
	    } else {
	      $this->log("No SQL settings provided!");
	      return $this->error($settings);
	    }
		}
		// General
		if(in_array('general',$this->Validate)){
			if(isset($settings['general']['language'])){
				$this->addProgress();
				if(in_array($settings['general']['language'],$this->Languages)){
					$configuration['language'] = $settings['general']['language'];
		      $this->log("Language Set!");
					$this->addProgress();
				} else {
					$this->log("Language not available!");
		      return $this->error($settings);
				}
	    } else {
	      $this->log("No language provided!");
	      return $this->error($settings);
	    }
			if(isset($settings['general']['timezone'])){
				$this->addProgress();
				if(in_array($settings['general']['timezone'],$this->Timezones)){
					$this->addProgress();
		      $configuration['timezone'] = $settings['general']['timezone'];
		      date_default_timezone_set($configuration['timezone']);
		      $this->log("Timezone Set!");
					$this->addProgress();
				} else {
					$this->log("Timezone not available!");
		      return $this->error($settings);
				}
	    } else {
	      $this->log("No timezone provided!");
	      return $this->error($settings);
	    }
			if(isset($settings['general']['administrator'],$settings['general']['password'])){
				$this->addProgress();
				$uppercase = preg_match('@[A-Z]@', $settings['general']['password']);
				$lowercase = preg_match('@[a-z]@', $settings['general']['password']);
				$number    = preg_match('@[0-9]@', $settings['general']['password']);
				$specialChars = preg_match('@[^\w]@', $settings['general']['password']);
				if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($settings['general']['password']) < 8){
		      $this->log("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character.");
		      return $this->error($settings);
				} else {
					$this->addProgress();
					if(!filter_var($settings['general']['administrator'], FILTER_VALIDATE_EMAIL)) {
						$this->log("Administrator is not a valid email address");
			      return $this->error($settings);
					} else {
						$this->addProgress();
						// Save the Administration email
						$configuration['administration'] = $settings['general']['administrator'];
						// Create Administrator
						$administrator = [
							'username' => $settings['general']['administrator'],
							'password' => password_hash($settings['general']['password'], PASSWORD_BCRYPT, array("cost" => 10)),
							'status' => 1,
							'name' => 'Administrator',
							'dateRegistered' => date("Y-m-d H:i:s"),
						];
						if(in_array('sql',$this->Validate)){
							$keyActivation = sha1(mt_rand(10000,99999).time().$settings['general']['administrator']);
							$administrator['type'] = 'sql';
							if(in_array('smtp',$this->Validate)){
								$administrator['status'] = 0;
								$administrator['keyActivation'] = password_hash($keyActivation, PASSWORD_BCRYPT, array("cost" => 10));
							}
							$statement = $this->Database->prepare('insert','users', $administrator);
							if($administrator['id'] = $this->Database->query($statement,$administrator)->lastInsertID()){
								$this->log("User Administrator created");
								$this->addProgress();
								$group1 = ["name" => "administrators","isLocked" => 1];
								$group2 = ["name" => "users","isLocked" => 1];
								$role1 = ["name" => "administrators","permissions" => json_encode(["isAdministrator" => 1]),"isLocked" => 1];
								$role2 = ["name" => "users","permissions" => json_encode(["timelineRegister" => 1,"timelineStatus" => 1,"timelineComments" => 1,"timelineMessages" => 1]),"isLocked" => 1];
								$statement = $this->Database->prepare('insert','groups', $group1);
								if($group1['id'] = $this->Database->query($statement,$group1)->lastInsertID()){
									$this->log("Group ".$group1['name']." created");
									$this->addProgress();
									$statement = $this->Database->prepare('insert','groups', $group2);
									if($group2['id'] = $this->Database->query($statement,$group2)->lastInsertID()){
										$this->log("Group ".$group2['name']." created");
										$this->addProgress();
										$statement = $this->Database->prepare('insert','roles', $role1);
										if($role1['id'] = $this->Database->query($statement,$role1)->lastInsertID()){
											$this->log("Role ".$role1['name']." created");
											$this->addProgress();
											$statement = $this->Database->prepare('insert','roles', $role2);
											if($role2['id'] = $this->Database->query($statement,$role2)->lastInsertID()){
												$this->log("Role ".$role2['name']." created");
												$this->addProgress();
												if($this->Database->createRelationship(['groups' => $group1['id']],[['roles' => $role1['id']],['roles' => $role2['id']]])){
													$this->log("Group ".$group1['name']." linked to role ".$role1['name']." and role ".$role2['name']);
													$this->addProgress();
													if($this->Database->createRelationship(['groups' => $group2['id']],[['roles' => $role2['id']]])){
														$this->log("Group ".$group2['name']." linked to role ".$role2['name']);
														$this->addProgress();
														if($this->Database->createRelationship(['users' => $administrator['id']],[['groups' => $group1['id']]])){
															$this->log("User ".$administrator['name']." linked to group ".$group1['name']);
															$this->addProgress();
															// Send activation email
															if(in_array('smtp',$this->Validate)){
																$links = [
																	"logo" => $this->URL."dist/img/logo.png"
																];
																$this->SMTP->customization("Support",$links);
																$links = [
																	"logo" => $this->URL."dist/img/logo.png",
																	"support" => str_replace('.git','',$this->Manifest['repository']),
																];
																$this->SMTP->customization($this->Manifest['name'],$links);
																$body = 'Dear Administrator,<br>';
																$body .= 'Your application was successfully installed.<br><br>';
																$body .= 'To activate your account, click the link below and sign in with your new account.<br><br>';
																$body .= '<a href="'.$this->URL.'?key='.$keyActivation.'" style="color:#0088cc" class="arrow-right">Click here to activate your account</a>';
																$extra = [
																	'title' => 'Activate your account',
																	'subject' => 'Activate your account',
																];
																if($this->SMTP->send($settings['general']['administrator'],$body,$extra)){
																	$this->log("Activation email sent");
																	$this->addProgress();
																} else {
																	$this->log("Unable to send activation email");
																	return $this->error($settings);
																}
															}
														}
													}
												}
											}
										}
									}
								}
							} else {
								$this->log("Unable to create the Administrator");
					      return $this->error($settings);
							}
						}
					}
		    }
			} else {
	      $this->log("No administrator/password provided!");
	      return $this->error($settings);
	    }
		}
    // Saving Settings
    if($this->setSettings($configuration)){
			$this->addProgress();
			if($this->Progress >= $this->Steps){ return $this->success(); } else {
				$this->log("The installation did not complete");
	      return $this->error($settings);
			}
    } else {
      $this->log("Unable to complete the installation");
      return $this->error($settings);
    }
  }
}
