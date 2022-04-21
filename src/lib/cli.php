<?php

// Import Librairies
require_once dirname(__FILE__,3) . '/src/lib/api.php';

class CLI extends API {

  public function __construct(){
    parent::__construct();
    // Setup Logger
    $this->Log = dirname(__FILE__,3) . "/tmp/cli.log";
		if(isset($this->Settings['log']['cli']['status'])){ $this->Logger = $this->Settings['log']['cli']['status']; }
    if(isset($this->Settings['log']['cli']['location'])){ $this->Log = $this->Settings['log']['cli']['location']; }
  }

  protected function request($text, $mode = 'single', $max = 5){
    if($mode == 'single'){
      echo $text . ' ';
      $handle = fopen ("php://stdin","r");
      return str_replace("\n",'',fgets($handle));
    } elseif($mode == 'multi'){
      echo $text . " (END)" . PHP_EOL;
      $count = 0;
      $return = '';
      do {
        $line = fgets(STDIN);
        if(in_array(str_replace("\n",'',$line),['END','EXIT','QUIT','EOF',':q',':Q',''])){
          if($max <= 0){ $max = 1; }
          $count = $max;
        }
        else { $return .= $line; $count++; }
      } while ($count < $max || $max <= 0);
      return $return;
    }
  }

  public function debug($data = [],$options = []){
    if(isset($this->Settings['debug']) && $this->Settings['debug']){ $this->Settings['debug'] = false; }
    else { $this->Settings['debug'] = true; }
    $this->setSettings();
    $this->Debug = $this->Settings['debug'];
    if($this->Debug){ $this->log("Debug enabled"); }
    else { $this->log("Debug disabled"); }
  }

  public function backup($data = [],$options = []){
    if(isset($this->Settings['sql'])){
      if($database = new Database($this->Settings['sql']['host'], $this->Settings['sql']['username'], $this->Settings['sql']['password'], $this->Settings['sql']['database'])){
        if(in_array('structure',$options) || empty($options)){
          $this->log("Saving database structure");
          $file = dirname(__FILE__,3) . '/dist/data/structure.json';
          if(file_put_contents($file, json_encode($database->backupStructure(), JSON_PRETTY_PRINT) , LOCK_EX)){
            $this->log("Database structure saved in ".$file);
          }
        }
        if(in_array('data',$options) || empty($options)){
          $this->log("Saving database data");
          if(empty($data)){
            $file = dirname(__FILE__,3) . '/dist/data/skeleton.json';
            if(file_put_contents($file, json_encode($database->backupData(['maxID' => 99999]), JSON_PRETTY_PRINT) , LOCK_EX)){
              $this->log("Database skeleton data saved in ".$file);
            }
            $file = dirname(__FILE__,3) . '/dist/data/sample.json';
            if(file_put_contents($file, json_encode($database->backupData(['minID' => 100000]), JSON_PRETTY_PRINT) , LOCK_EX)){
              $this->log("Database sample data saved in ".$file);
            }
          } else {
            $file = dirname(__FILE__,3) . $data[0];
            if(file_put_contents($file, json_encode($database->backupData(), JSON_PRETTY_PRINT) , LOCK_EX)){
              $this->log("Database data saved in ".$file);
            }
          }
        }
      } else { $this->error($database); }
    }
  }

  public function restore($data = [],$options = []){
    if(isset($this->Settings['sql'])){
      if($database = new Database($this->Settings['sql']['host'], $this->Settings['sql']['username'], $this->Settings['sql']['password'], $this->Settings['sql']['database'])){
        if(in_array('structure',$options) || empty($options)){
          $file = dirname(__FILE__,3) . '/dist/data/structure.json';
          $this->log("Restoring database structure from ".$file);
          $structure = json_decode(file_get_contents($file),true);
          if($database->restoreStructure($structure)){
            $this->log("database structure restored");
          }
        }
        if(in_array('data',$options) || empty($options)){
          if(empty($data)){
            $file = dirname(__FILE__,3) . '/dist/data/skeleton.json';
            $this->log("Restoring database skeleton data from ".$file);
            $records = json_decode(file_get_contents($file),true);
            if($database->restoreData($records)){
              $this->log("Database skeleton data restored");
            }
            $file = dirname(__FILE__,3) . '/dist/data/sample.json';
            $this->log("Restoring database sample data from ".$file);
            $records = json_decode(file_get_contents($file),true);
            if($database->restoreData($records)){
              $this->log("Database sample data restored");
            }
          } else {
            $file = dirname(__FILE__,3) . $data[0];
            $this->log("Restoring database data from ".$file);
            $records = json_decode(file_get_contents($file),true);
            if(in_array('asNew',$options)){
              if($database->restoreData($records,['asNew' => true])){
                $this->log("Database data restored");
              }
            } else {
              if($database->restoreData($records)){
                $this->log("Database data restored");
              }
            }
          }
        }
      } else { $this->error($database); }
    }
  }

  public function clear(){
    $this->log("Clearing all logs");
    if(is_file(dirname(__FILE__,3) . '/tmp/api.log')){ file_put_contents(dirname(__FILE__,3) . '/tmp/api.log', PHP_EOL , LOCK_EX); }
    if(is_file(dirname(__FILE__,3) . '/tmp/application.log')){ file_put_contents(dirname(__FILE__,3) . '/tmp/application.log', PHP_EOL , LOCK_EX); }
    if(is_file(dirname(__FILE__,3) . '/tmp/cli.log')){ file_put_contents(dirname(__FILE__,3) . '/tmp/cli.log', PHP_EOL , LOCK_EX); }
    if(is_file(dirname(__FILE__,3) . '/tmp/access.log')){ file_put_contents(dirname(__FILE__,3) . '/tmp/access.log', PHP_EOL , LOCK_EX); }
    if(is_file(dirname(__FILE__,3) . '/tmp/install.log')){ file_put_contents(dirname(__FILE__,3) . '/tmp/install.log', PHP_EOL , LOCK_EX); }
    $this->log("Logs cleared");
  }

  public function version($args = null){
    if(isset($this->Manifest['version'])){ echo "Version: ".$this->Manifest['version']."\n"; }
    if(isset($this->Manifest['build'])){ echo "Build: ".$this->Manifest['build']."\n"; }
  }

  public function publish($data = [],$options = []){
    if(isset($this->Settings['name'],$this->Manifest['name']) && $this->Manifest['name'] != $this->Settings['name']){ $this->Manifest['name'] = $this->Settings['name']; }
    if(!isset($this->Manifest['name']) || $this->Manifest['name'] == null || $this->Manifest['name'] == ''){ $this->Manifest['name'] = str_replace("\n",'',shell_exec("basename `git rev-parse --show-toplevel`")); }
    if(!isset($this->Manifest['build'])){ $this->Manifest['build'] = 0; }
    $this->log("Updating manifest");
    $this->Manifest['build'] = $this->Manifest['build']+1;
    $this->Manifest['branch'] = str_replace("\n",'',shell_exec("git rev-parse --abbrev-ref HEAD"));
    $this->Manifest['repository'] = str_replace("\n",'',shell_exec("git config --get remote.origin.url"));
    $this->Manifest['version'] = date("y.m").'-'.$this->Manifest['branch'];
    if($this->setManifest()){
      $this->log("Manifest updated");
      $this->log("Updating version to ".$this->Manifest['version']);
      if($this->setVersion($this->Manifest['version'])){
        $this->log("Version updated");
        $this->log("Updating build to ".$this->Manifest['build']);
        if($this->setBuild($this->Manifest['build'])){
          $this->log("Build updated");
          if(isset($this->Manifest['version'],$this->Manifest['build'],$this->Manifest['branch'])){
            $this->log("Updating ChangeLog");
            // Update ChangeLog
            $changelog = "## Version ".$this->Manifest['version']." Build: ".$this->Manifest['build'].PHP_EOL;
            $files = shell_exec("git diff HEAD --name-only");
            foreach(explode("\n",$files) as $file){
              if(!empty($file) && !in_array($file,['dist/data/build.json','dist/data/manifest.json','dist/data/version.json','dist/data/structure.json','dist/data/skeleton.json','dist/data/sample.json','CHANGELOG.md'])){
                foreach(explode("\n",$this->request($this->getField("What changes did you make to")." ".$file."?",'multi',0)) as $change){
                  if(!empty($change)){ $changelog .= "* (".$file."): ".$change.PHP_EOL; }
                }
              }
            }
            foreach(explode("\n",$this->request($this->getField("Do you have any other changes?"),'multi',0)) as $change){
              if(!empty($change)){ $changelog .= "* ".$change.PHP_EOL; }
            }
            $file = dirname(__FILE__,3) . '/CHANGELOG.md';
            $lines = file( $file , FILE_IGNORE_NEW_LINES );
            $lines[1] = PHP_EOL.$changelog;
            file_put_contents( $file , implode( "\n", $lines ) );
            $file = dirname(__FILE__,3) . '/LATEST.md';
            $json = fopen($file, 'w');
        		fwrite($json, $changelog);
        		fclose($json);
            $this->log("Updating ReadMe");
            // Update README.md
            $git = explode('//',$this->Manifest['repository'])[1];
            $username = explode('/',$git)[1];
            $repository = str_replace('.git','',explode('/',$git)[2]);
            $file = dirname(__FILE__,3) . '/README.md';
            $lines = file( $file , FILE_IGNORE_NEW_LINES );
            $isName = true;
            foreach($lines as $key => $line){
              if(strpos($line, '# ') !== false && $isName){ $lines[$key] = '# '.$this->Manifest['name'];$isName = false; }
              if(strpos($line, '![License](https://img.shields.io/github/license/') !== false){ $lines[$key] = '![License](https://img.shields.io/github/license/'.$username.'/'.$repository.'?style=for-the-badge)'; }
              if(strpos($line, '![GitHub repo size](https://img.shields.io/github/repo-size/') !== false){ $lines[$key] = '![GitHub repo size](https://img.shields.io/github/repo-size/'.$username.'/'.$repository.'?style=for-the-badge&logo=github)'; }
              if(strpos($line, '![GitHub top language](https://img.shields.io/github/languages/top/') !== false){ $lines[$key] = '![GitHub top language](https://img.shields.io/github/languages/top/'.$username.'/'.$repository.'?style=for-the-badge)'; }
              if(strpos($line, '![Version badge](https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fraw.githubusercontent.com%2F') !== false){ $lines[$key] = '![Version badge](https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fraw.githubusercontent.com%2F'.$username.'%2F'.$repository.'%2F'.$this->Manifest['branch'].'%2Fdist%2Fdata%2Fversion.json)'; }
              if(strpos($line, '![Build badge](https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fraw.githubusercontent.com%2F') !== false){ $lines[$key] = '![Build badge](https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fraw.githubusercontent.com%2F'.$username.'%2F'.$repository.'%2F'.$this->Manifest['branch'].'%2Fdist%2Fdata%2Fbuild.json)'; }
              if(strpos($line, 'git clone https://github.com/') !== false){ $lines[$key] = 'git clone https://github.com/'.$username.'/'.$repository; }
            }
            file_put_contents( $file , implode( "\n", $lines ) );
            // Update Workflows
            $file = dirname(__FILE__,3) . '/.github/templates/dev.yml';
            $lines = file( $file , FILE_IGNORE_NEW_LINES );
            $lines[16] = '        name: "'.$this->Manifest['version'].'.'.$this->Manifest['build'].'"';
            file_put_contents( $file , implode( "\n", $lines ) );
            $file = dirname(__FILE__,3) . '/.github/workflows/pre-release.yml';
            $lines = file( $file , FILE_IGNORE_NEW_LINES );
            $lines[16] = '        name: "'.$this->Manifest['version'].'.'.$this->Manifest['build'].'"';
            file_put_contents( $file , implode( "\n", $lines ) );
            $file = dirname(__FILE__,3) . '/.github/workflows/stable.yml';
            $lines = file( $file , FILE_IGNORE_NEW_LINES );
            $lines[16] = '        name: "'.$this->Manifest['version'].'.'.$this->Manifest['build'].'"';
            file_put_contents( $file , implode( "\n", $lines ) );
          }
          $this->log("Pushing changes to repository on branch ".$this->Manifest['branch']);
          shell_exec("git add . && git commit -m '".$changelog."' && git push origin ".$this->Manifest['branch']);
          $this->log("Repository updated");
          $this->log("Published on ".$this->Manifest['repository']);
        }
      }
    }
  }

  public function settings($data = [],$options = []){
    if(empty($options)){
      $this->log(json_encode($this->Settings, JSON_PRETTY_PRINT));
    } elseif(in_array('language',$options)){
      if(empty($data)){ $this->log($this->Settings['language']); }
      elseif(in_array($data[0],$this->Languages)){
        $this->Settings['language'] = $data[0];
        $this->setSettings();
        $this->log("Language set to ".$this->Settings['language']);
      }
    }
  }

  public function disable($data = [],$options = []){
    if(empty($options)){
      $answer = $this->request($this->getField("What is the username of the user to disable?"));
      if($this->Auth->deactivate($answer)){
        $this->log("[".$answer."] has been disabled");
      } else { $this->log("[".$answer."] Unable to disable this account"); }
    }
  }

  public function enable($data = [],$options = []){
    if(empty($options)){
      $answer = $this->request($this->getField("What is the username of the user to enable?"));
      if($this->Auth->reactivate($answer)){
        $this->log("[".$answer."] has been enabled");
      } else { $this->log("[".$answer."] Unable to enable this account"); }
    }
  }

  public function translate($data = [],$options = []){
    if($this->Translator){
      $codes = [
        'dutch' => 'nl',
        'english' => 'en',
        'french' => 'fr',
        'german' => 'de',
        'italian' => 'it',
        'polish' => 'pl',
        'portuguese' => 'pt',
        'russian' => 'ru',
        'spanish' => 'es',
      ];
      if(empty($data)){
        foreach($codes as $language => $code){
          if($language != $this->Language){
            if(file_exists(dirname(__FILE__,3) . "/dist/languages/".$language.".json")){
              $content = json_decode(file_get_contents(dirname(__FILE__,3) . "/dist/languages/".$language.".json"),true);
            } else { $content = []; }
            foreach($this->Fields as $key => $text){
              if($key != '' && $text != ''){
                $content[$key] = htmlspecialchars_decode($this->Translator->translate($text,['source' => $codes[$this->Language], 'target' => $codes[$language]])['text'],ENT_QUOTES);
                if(preg_match('~^\p{Lu}~u', $key)){ $content[$key] = ucfirst($content[$key]); }
                if($key == strtoupper($key)){ $content[$key] = strtoupper($content[$key]); }
              }
            }
            $json = fopen(dirname(__FILE__,3).'/dist/languages/'.$language.'.json', 'w');
        		fwrite($json, json_encode($content, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        		fclose($json);
            $this->log("Application translated to: ".$language);
          }
        }
      }
    }
  }

  public function test($data = [],$options = []){
    // echo shell_exec('tail -F '.dirname(__FILE__,3)."/tmp/*.log");
  }
}
