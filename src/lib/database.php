<?php

class Database{

  protected $connection;
	protected $query;
  protected $show_errors = TRUE;
  protected $query_closed = TRUE;
	public $query_count = 0;
	protected $results;
	protected $status = false;

	public function __construct($host = 'localhost', $username = 'root', $password = '', $database = '', $charset = 'utf8'){
    $this->connection = $this->connect($host, $username, $password, $database, $charset);
    if(property_exists($this->connection,'connect_error')){ $this->status = true; }
	}

  public function isConnected(){
    return $this->status;
  }

  public function connect($host = 'localhost', $username = 'root', $password = '', $database = '', $charset = 'utf8'){
    $connection = new mysqli($host, $username, $password, $database);
		if($connection->connect_error){ return false; }
		$connection->name = $database;
    $connection->limit = 500;
		$connection->set_charset($charset);
    return $connection;
  }

	public function setLimit($set){
    if($this->connection){ $this->connection->limit = $set; }
	}

  public function query($query) {
    if($this->connection){
      if(!$this->query_closed){ $this->query->close(); }
  		if ($this->query = $this->connection->prepare($query)) {
        if (func_num_args() > 1) {
          $x = func_get_args();
          $args = array_slice($x, 1);
  				$types = '';
          $args_ref = array();
          foreach ($args as $k => &$arg) {
  					if (is_array($args[$k])) {
  						foreach ($args[$k] as $j => &$a) {
  							$types .= $this->_gettype($args[$k][$j]);
  							$args_ref[] = &$a;
  						}
  					} else {
            	$types .= $this->_gettype($args[$k]);
              $args_ref[] = &$arg;
  					}
          }
  				array_unshift($args_ref, $types);
          call_user_func_array(array($this->query, 'bind_param'), $args_ref);
        }
        $this->query->execute();
       	if ($this->query->errno) {
  				$this->error('Unable to process MySQL query (check your params) - ' . $this->query->error);
       	}
        $this->query_closed = FALSE;
      } else {
        echo $this->error('Unable to prepare MySQL statement (check your syntax) - ' . $this->connection->error);
    	}
  		return $this;
    }
  }

  public function fetchAll($callback = null){
    if($this->connection){
      $params = array();
      $row = array();
      $meta = $this->query->result_metadata();
      while ($field = $meta->fetch_field()) {
        $params[] = &$row[$field->name];
      }
      call_user_func_array(array($this->query, 'bind_result'), $params);
      $result = array();
      while ($this->query->fetch()) {
        $r = array();
        foreach ($row as $key => $val) {
          $r[$key] = $val;
        }
        if ($callback != null && is_callable($callback)) {
          $value = call_user_func($callback, $r);
          if ($value == 'break') break;
        } else {
          $result[] = $r;
        }
      }
      $this->query->close();
      $this->query_closed = TRUE;
  		return $result;
    }
	}

	public function error($error) { return json_encode($error, JSON_PRETTY_PRINT); }

	private function close() { return $this->connection->close(); }

	private function _gettype($var) {
    if (is_string($var)) return 's';
    if (is_float($var)) return 'd';
    if (is_int($var)) return 'i';
    return 'b';
	}

  public function lastInsertID() {
  	return $this->connection->insert_id;
  }

	public function numRows() {
    if($this->connection){
  		$this->query->store_result();
  		return $this->query->num_rows;
    }
	}

  private function isAssoc(array $arr){
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
  }

  public function prepare($type, $table, $data = [], $options = []){
    if($this->connection){
      if(is_array($data) && is_array($options)){
        $type = strtoupper($type);
        if($type == 'INSERT'){ $type = 'INSERT INTO'; }
        $hasValues = $this->isAssoc($data);
        if($hasValues && in_array($type,['SELECT']) && !empty($data) && empty($options)){
          foreach(array_keys($data) as $key){
            if(in_array($key,['conditions','primary','operation'])){
              $options = $data;
              $data = [];
              $hasValues = $this->isAssoc($data);
              break;
            }
          }
        }
        if(in_array($table,$this->getTables()) && in_array($type,['INSERT INTO','UPDATE','SELECT','DELETE'])){
          if(in_array($type,['INSERT INTO','UPDATE']) && empty($data)){ return false; }
          $primary = $this->getPrimary($table);
          $auto = $this->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'.$table.'" AND TABLE_SCHEMA = "'.$this->connection->name.'" AND COLUMN_NAME = "'.$primary.'" AND EXTRA like "%auto_increment%"')->numRows();
          if($hasValues){ $columns = array_keys($data); } else { $columns = $data; }
          $condition = ' WHERE';
          $part1 = '';
          $part2 = '';
          $part3 = '';
          if(isset($options['conditions']) && is_array($options['conditions']) && !empty($options['conditions'])){
            $headers = $this->getHeaders($table);
            if(isset($options['operation']) && in_array(strtoupper($options['operation']),['AND','OR'])){
              $optype = strtoupper($options['operation']);
            } else { $optype = 'AND'; }
            $genConditions = function($key, $operation){
              switch($operation){
                case"olderThen":
                  $condition = 'DATEDIFF(NOW(),`'.$key.'`) > ?';
                  break;
                case"earlierThen":
                  $condition = 'DATEDIFF(NOW(),`'.$key.'`) <= ?';
                  break;
                default:
                  $condition = 'UPPER(`'.$key.'`) '.$operation.' ?';
                  break;
              }
              return $condition;
            };
            foreach($options['conditions'] as $key => $operation){
              if(is_array($operation)){
                foreach($operation as $k => $op){
                  if(in_array($k,$headers)){
                    if(substr($condition, -1) == '?'){ $condition .= ' '.$optype; }
                    $condition .= ' '.$genConditions($k,$op);
                  }
                }
              } else {
                if(in_array($key,$headers)){
                  if(substr($condition, -1) == '?'){ $condition .= ' '.$optype; }
                  $condition .= ' '.$genConditions($key,$operation);
                }
              }
            }
          } elseif(in_array($type,['UPDATE','DELETE'])||(isset($options['primary']) && $options['primary']))
          { $condition .= ' `'.$primary.'` = ?'; } else { $condition = ''; }
          switch($type){
            case'INSERT INTO':
              $condition = '';
              $part2 = ' (';
              $part3 = ' VALUES (';
              break;
            case'UPDATE':
              $part2 = ' SET';
              break;
            case'SELECT':
              $part1 = ' * FROM';
              break;
            case'DELETE':
              $part1 = ' FROM';
              break;
          }
          foreach($columns as $column){
            switch($type){
              case'INSERT INTO':
                if(!$auto || $column != $primary){
                  if(substr($part2, -1) != '('){ $part2 = $part2.','; }
                  $part2 = $part2.'`'.$column.'`';
                  if(substr($part3, -1) != '('){ $part3 = $part3.','; }
                  $part3 = $part3.'?';
                }
                break;
              case'UPDATE':
                if(substr($part2, -1) == '?'){ $part2 = $part2.','; }
                $part2 = $part2.' `'.$column.'` = ?';
                break;
            }
          }
          switch($type){
            case'INSERT INTO':
              $part2 = $part2.')';
              $part3 = $part3.')';
              break;
          }
          $query = $type.$part1.' `'.$table.'`'.$part2.$part3.$condition;
          return $query;
        } else { return false; }
      } else { return false; }
    }
  }

  public function getTables(){
    if($this->connection && property_exists($this->connection,'name')){
      $tables = $this->query('SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ?', $this->connection->name)->fetchAll();
      $results = [];
      foreach($tables as $table){
  			if(!in_array($table['TABLE_NAME'],$results)){
        	array_push($results,$table['TABLE_NAME']);
  			}
      }
      return $results;
    }
  }

  public function getPrimary($table){
    return $this->query('SELECT k.column_name FROM information_schema.table_constraints t JOIN information_schema.key_column_usage k USING(constraint_name,table_schema,table_name) WHERE t.constraint_type="PRIMARY KEY" AND t.table_schema="'.$this->connection->name.'" AND t.table_name="'.$table.'"')->fetchAll()[0]['column_name'];
  }

	protected function isJson($string) {
		json_decode($string);
		return json_last_error() === JSON_ERROR_NONE;
	}

	public function getHeaders($table){
    if($this->connection && property_exists($this->connection,'name')){
      $headers = $this->query('SELECT * FROM information_schema.COLUMNS WHERE TABLE_NAME = ? AND TABLE_SCHEMA = ?', $table,$this->connection->name)->fetchAll();
      $results = [];
      foreach($headers as $header){
        array_push($results,$header['COLUMN_NAME']);
      }
      return $results;
    }
  }

  public function getRelationshipsTo($table, $id){
    $relationships = [];
    $statement = $this->prepare('select','relationships', ['conditions' => ['relations' => 'LIKE']]);
    $relationship = $this->query($statement,'%{"'.$table.'":'.$id.'}%')->fetchAll();
    if(count($relationship) > 0){
      foreach($relationship as $relation){
        $meta = json_decode($relation['meta'],true);
        $relations = json_decode($relation['owner'],true);
        foreach($relations as $key => $id){
          if(!isset($relationships[$key][$id])){
            $record = $this->query($this->prepare('select',$key, ['conditions' => ['id' => '=']]),$id)->fetchAll();
            if(count($record) > 0){
              $relationships[$key][$id] = $record[0];
              $relationships[$key][$id]['on'] = $relation['on'];
              $relationships[$key][$id]['meta'] = $meta;
            }
          }
        }
      }
    }
    return $relationships;
  }

  public function getRelationshipsOf($table, $id){
    $relationships = [];
    $statement = $this->prepare('select','relationships', ['conditions' => ['owner' => '=']]);
    $relationship = $this->query($statement,'{"'.$table.'":'.$id.'}')->fetchAll();
    if(count($relationship) > 0){
      foreach($relationship as $relation){
        $meta = json_decode($relation['meta'],true);
        foreach($meta as $type => $values){
          $values['on'] = $relation['on'];
          $relationships[$type][$relation['on']] = $values;
        }
        $relations = json_decode($relation['relations'],true);
        foreach($relations as $records){
          $key = array_keys($records)[0];
          $id = $records[$key];
          if(!isset($relationships[$key][$id])){
            $record = $this->query($this->prepare('select',$key, ['conditions' => ['id' => '=']]),$id)->fetchAll();
            if(count($record) > 0){
              $relationships[$key][$id] = $record[0];
              $relationships[$key][$id]['on'] = $relation['on'];
              $relationships[$key][$id]['meta'] = $meta;
            }
          }
        }
      }
    }
    return $relationships;
  }

  public function createRelationship($to = [], $of = [], $meta = []){
    if(is_array($to) && !empty($to)){
      // Sanitization
      $table = array_keys($to)[0];
      $id = $to[$table];
      $statement = $this->prepare('select',$table, ['conditions' => ['id' => '=']]);
      if($this->query($statement,$id)->numRows() > 0){
        $owner = [];
        $owner[$table] = $id;
        $owner = json_encode($owner);
        $relations = [];
        if(!is_array($of)){ $of = []; }
        if($this->isAssoc($of)){ $of = [$of]; }
        foreach($of as $related){
          foreach($related as $table => $id){
            $statement = $this->prepare('select',$table, ['conditions' => ['id' => '=']]);
            if($this->query($statement,$id)->numRows()){
              $relation = [];
              $relation[$table] = $id;
              array_push($relations,$relation);
            }
          }
        }
        $relations = json_encode($relations);
        if(!is_array($meta)){ $meta = []; }
        $meta = json_encode($meta);
        $statement = $this->prepare('insert','relationships',['owner','relations','meta']);
        if($this->query($statement,['owner' => $owner,'relations' => $relations,'meta' => $meta])){
          return true;
        }
      }
    }
    return false;
  }

  public function deleteRelationships($table, $id){
    $statement = $this->prepare('select','relationships', ['conditions' => ['owner' => '=']]);
    $relationship = $this->query($statement,'{"'.$table.'":'.$id.'}')->fetchAll();
    if(count($relationship) > 0){
      foreach($relationship as $relation){
        $statement = $this->prepare('delete','relationships',$relation,['conditions' => ['id' => '=']]);
        if(!$this->query($statement,$relation['id'])){ return false; }
      }
    }
    $statement = $this->prepare('select','relationships', ['conditions' => ['relations' => 'LIKE']]);
    $relationship = $this->query($statement,'%{"'.$table.'":'.$id.'}%')->fetchAll();
    if(count($relationship) > 0){
      foreach($relationship as $relation){
        $relations = json_decode($relation['relations'],true);
        foreach($relations as $key => $array){
          if(array_keys($array)[0] == $table && array_values($array)[0] == $id){
            unset($relations[$key]);
          }
        }
        $relation['relations'] = json_encode($relations);
        $values = array_values($relation);
        array_push($values,$relation['id']);
        $statement = $this->prepare('update','relationships',$relation, ['conditions' => ['id' => '=']]);
        if(!$this->query($statement,$values)){ return false; }
      }
    }
    return true;
  }

  public function create($data = []){
    if(!is_array($data)){ $data = []; }
    $return = [];
    $run = function($records){
      $output = [];
      $tables = $this->getTables();
      foreach($records as $table => $record){
        if(in_array($table,$tables)){
          $primary = $this->getPrimary($table);
          foreach($record as $key => $value){ if(is_array($value)){ $record[$key] = json_encode($value); } }
          $statement = $this->prepare('insert',$table,$record);
          if($record[$primary] = $this->query($statement,$record)->lastInsertID()){
            $output[$table][$record[$primary]] = $record;
          }
        }
      }
      return $output;
    };
    if($this->isAssoc($data)){ $return = array_merge($run($data),$return); }
    else { foreach($data as $records){ $return = array_merge($run($data),$return); } }
    return $return;
  }

  public function read($data = []){
    $return = [];
    $run = function($records){
      $output = [];
      $tables = $this->getTables();
      foreach($records as $table => $record){
        if(in_array($table,$tables)){
          $primary = $this->getPrimary($table);
          if(is_int($record)){ $record[$primary] = $record; }
          if(isset($record[$primary])){
            $conditions = [];
            $conditions[$primary] = '=';
            $statement = $this->prepare('select',$table, $conditions);
            $query = $this->query($statement,$record[$primary])->fetchAll();
            if(count($query) > 0){
              foreach($query[0] as $key => $value){ if($this->isJson($value)){ $query[0][$key] = json_decode($value); } }
              $output[$table][$query[0][$primary]] = $query[0];
            }
          }
        }
      }
      return $output;
    };
    if(is_array($data)){
      if($this->isAssoc($data)){ $return = array_merge($run($data),$return); }
      else { foreach($data as $records){ $return = array_merge($run($records),$return); } }
    } else {
      $tables = $this->getTables();
      if(in_array($data,$tables)){
        $statement = $this->prepare('select',$data);
        $return = $this->query($statement)->fetchAll();
      }
    }
    return $return;
  }

  public function update($data = []){
    if(!is_array($data)){ $data = []; }
    $return = [];
    $run = function($records){
      $output = [];
      $tables = $this->getTables();
      foreach($records as $table => $record){
        if(in_array($table,$tables)){
          $primary = $this->getPrimary($table);
          if(isset($record[$primary])){
            foreach($record as $key => $value){ if(is_array($value)){ $record[$key] = json_encode($value); } }
            $values = array_values($record);
            array_push($values,$record[$primary]);
            $statement = $this->prepare('update',$table,$record);
            if($this->query($statement,$values)){
              $output[$table][$record[$primary]] = $record;
            }
          }
        }
      }
      return $output;
    };
    if($this->isAssoc($data)){ $return = array_merge($run($data),$return); }
    else { foreach($data as $records){ $return = array_merge($run($data),$return); } }
    return $return;
  }

  public function delete($data = []){
    if(!is_array($data)){ $data = []; }
    $return = [];
    $run = function($records){
      $output = [];
      $tables = $this->getTables();
      foreach($records as $table => $record){
        if(in_array($table,$tables)){
          $primary = $this->getPrimary($table);
          if(is_int($record)){ $record[$primary] = $record; }
          if(isset($record[$primary])){
            $conditions = [];
            $conditions[$primary] = '=';
            $statement = $this->prepare('delete',$table,[$primary],$conditions);
            if($this->query($statement,$record[$primary])){
              if(!isset($output[$table])){ $output[$table] = []; }
              array_push($output[$table],$record[$primary]);
            }
          }
        }
      }
      return $output;
    };
    if($this->isAssoc($data)){ $return = array_merge($run($data),$return); }
    else { foreach($data as $records){ $return = array_merge($run($data),$return); } }
    return $return;
  }

  public function backupStructure($tables = []){
    if($this->connection){
      if(!is_array($tables)){ $tables = [$tables]; }
  		foreach($this->query('SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ?',$this->connection->name)->fetchAll() as $fields){
        if(empty($tables) || (!empty($tables) && in_array($fields['TABLE_NAME'],$tables))){
          $structure[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['order'] = $fields['ORDINAL_POSITION'];
  				$structure[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['columnType'] = $fields['COLUMN_TYPE'];
  				$structure[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['default'] = $fields['COLUMN_DEFAULT'];
  				$structure[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['isNullable'] = $fields['IS_NULLABLE'];
  				$structure[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['dataType'] = $fields['DATA_TYPE'];
  				$structure[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['key'] = $fields['COLUMN_KEY'];
  				$structure[$fields['TABLE_NAME']][$fields['COLUMN_NAME']]['extra'] = $fields['EXTRA'];
    			$structure[$fields['TABLE_NAME']][$fields['ORDINAL_POSITION']] = $fields['COLUMN_NAME'];
        }
  		}
  		if(isset($structure)){ return $structure; } else { return false; }
    }
	}

	public function restoreStructure($structure){
    if($this->connection){
  		$current = $this->backupStructure();
  		foreach($structure as $table_name => $table){
  			if(isset($current[$table_name])){
  				foreach($table as $column_name => $column){
  					if(!is_int($column_name)){
  						if(isset($current[$table_name][$column_name])){
                if($current[$table_name][$column_name] !== $structure[$table_name][$column_name]){
                  $query = 'ALTER TABLE `'.$table_name.'` CHANGE `'.$column_name.'` `'.$column_name.'` '.$structure[$table_name][$column_name]['columnType'];
                  if($structure[$table_name][$column_name]['isNullable'] == 'YES'){ $query .= ' NULL'; } else { $query .= ' NOT NULL'; }
                  if($structure[$table_name][$column_name]['default'] == 'CURRENT_TIMESTAMP'){ $query .= ' DEFAULT CURRENT_TIMESTAMP'; }
                  elseif($structure[$table_name][$column_name]['default'] == null){ $query .= ' DEFAULT NULL'; }
                  else { $query .= ' DEFAULT "'.$structure[$table_name][$column_name]['default'].'"'; }
                  if($structure[$table_name][$column_name]['extra'] == "on update CURRENT_TIMESTAMP"){
                    $query .= ' ON UPDATE CURRENT_TIMESTAMP';
                  }
                  $this->query($query);
                  if($structure[$table_name][$column_name]['key'] == 'UNI'){
                    $this->query('ALTER TABLE `'.$table_name.'` ADD UNIQUE(`'.$column_name.'`)');
                  } elseif($current[$table_name][$column_name]['key'] == 'UNI' && $structure[$table_name][$column_name]['key'] == ''){
                    $index = $this->query("SHOW INDEX FROM `".$table_name."` WHERE `Column_name` = '".$column_name."'")->fetchAll();
                    if(count($index) > 0){
                      $this->query("ALTER TABLE `".$table_name."` DROP INDEX `".$index[0]["Key_name"]."`");
                    }
                  }
  							}
  						} else {
  							$this->query('ALTER TABLE `'.$table_name.'` ADD `'.$column_name.'` '.$structure[$table_name][$column_name]['columnType'].' AFTER `'.$structure[$table_name][$structure[$table_name][$column_name]['order']-1].'`');
                $query = 'ALTER TABLE `'.$table_name.'` CHANGE `'.$column_name.'` `'.$column_name.'` '.$structure[$table_name][$column_name]['columnType'];
                if($structure[$table_name][$column_name]['isNullable'] == 'YES'){ $query .= ' NULL'; } else { $query .= ' NOT NULL'; }
                if($structure[$table_name][$column_name]['default'] == 'CURRENT_TIMESTAMP'){ $query .= ' DEFAULT CURRENT_TIMESTAMP'; }
                elseif($structure[$table_name][$column_name]['default'] == null){ $query .= ' DEFAULT NULL'; }
                else { $query .= ' DEFAULT "'.$structure[$table_name][$column_name]['default'].'"'; }
                if($structure[$table_name][$column_name]['extra'] == "on update CURRENT_TIMESTAMP"){
                  $query .= ' ON UPDATE CURRENT_TIMESTAMP';
                }
                $this->query($query);
                if($structure[$table_name][$column_name]['key'] == 'UNI'){
                  $this->query('ALTER TABLE `'.$table_name.'` ADD UNIQUE(`'.$column_name.'`)');
                }
  						}
  						set_time_limit(20);
  					}
  				}
  			} else {
  				$this->query('CREATE TABLE `'.$table_name.'` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id))');
  				$this->query('ALTER TABLE `'.$table_name.'` auto_increment = 100000');
  				$this->query('ALTER TABLE `'.$table_name.'` row_format=dynamic');
  				set_time_limit(20);
  				foreach($structure[$table_name] as $column_order => $column_name){
  					if((is_int($column_order))&&($column_name) != 'id'){
              $this->query('ALTER TABLE `'.$table_name.'` ADD `'.$column_name.'` '.$structure[$table_name][$column_name]['columnType']);
              $query = 'ALTER TABLE `'.$table_name.'` CHANGE `'.$column_name.'` `'.$column_name.'` '.$structure[$table_name][$column_name]['columnType'];
              if($structure[$table_name][$column_name]['isNullable'] == 'YES'){ $query .= ' NULL'; } else { $query .= ' NOT NULL'; }
              if($structure[$table_name][$column_name]['default'] == 'CURRENT_TIMESTAMP'){ $query .= ' DEFAULT CURRENT_TIMESTAMP'; }
              elseif($structure[$table_name][$column_name]['default'] == null){ $query .= ' DEFAULT NULL'; }
              else { $query .= ' DEFAULT "'.$structure[$table_name][$column_name]['default'].'"'; }
              if($structure[$table_name][$column_name]['extra'] == "on update CURRENT_TIMESTAMP"){
                $query .= ' ON UPDATE CURRENT_TIMESTAMP';
              }
              $this->query($query);
              if($structure[$table_name][$column_name]['key'] == 'UNI'){
                $this->query('ALTER TABLE `'.$table_name.'` ADD UNIQUE(`'.$column_name.'`)');
              }
  						set_time_limit(20);
  					}
  				}
  			}
  		}
      return true;
    }
	}

	public function backupData($options = []){
    if($this->connection){
  		foreach($this->getTables() as $table){
        $SQLoptions = '';
    		$SQLargs = [];
        if((isset($options['maxID']))||(isset($options['minID']))){
          $primary = $this->getPrimary($table);
          $auto = $this->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'.$table.'" AND TABLE_SCHEMA = "'.$this->connection->name.'" AND COLUMN_NAME = "'.$primary.'" AND EXTRA like "%auto_increment%"')->numRows();
    			$SQLoptions = ' WHERE';
    			if(isset($options['maxID'])){ $SQLoptions .= ' `'.$primary.'` <= ?'; array_push($SQLargs,$options['maxID']); }
    			if(isset($options['minID'])){ $SQLoptions .= ' `'.$primary.'` >= ?'; array_push($SQLargs,$options['minID']); }
    		}
  			if(!empty($SQLargs)){ $results = $this->query('SELECT * FROM `'.$table.'`'.$SQLoptions,$SQLargs); }
  			else { $results = $this->query('SELECT * FROM `'.$table.'`'); }
  			if($results != null){ $records[$table] = $results->fetchAll(); }
  		}
  		if(isset($records)){ return $records; } else { return false; }
    }
	}

	public function restoreData($data, $options = []){
    if($this->connection){
      if(isset($options['asNew'])){ $asNew = $options['asNew']; } else { $asNew = false; }
      if(isset($options['minID'])){ $minID = $options['minID']; } else { $minID = 0; }
      if(isset($options['maxID'])){ $maxID = $options['minID']; } else { $maxID = 0; }
  		foreach($data as $table => $records){
        $primary = $this->getPrimary($table);
        $auto = $this->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'.$table.'" AND TABLE_SCHEMA = "'.$this->connection->name.'" AND COLUMN_NAME = "'.$primary.'" AND EXTRA like "%auto_increment%"')->numRows();
        foreach($records as $record){
          if((!$minID || ($record[$primary] >= $minID)) && (!$maxID || ($record[$primary] <= $maxID))){
            if(!$asNew){ $find = $this->query($this->prepare('select',$table,['primary' => true]), $record[$primary])->numRows(); } else { $find = 0; }
            if($find){
              $values = array_values($record);
              array_push($values,$record[$primary]);
              if($query = $this->prepare('update',$table, $record)){
                $this->query($query,$values);
              }
            } else {
              $values = $record;
              unset($values[$primary]);
              $values = array_values($values);
              if($query = $this->prepare('insert',$table, $record)){
                $this->query($query,$values);
                if(!$asNew){
                  $values = [];
                  array_push($values,$record[$primary]);
                  array_push($values,$this->lastInsertID());
                  if($query = $this->prepare('update',$table, [$primary])){
                    $this->query($query,$values);
                  }
                }
              }
            }
          }
        }
  		}
      return true;
    }
	}
}
