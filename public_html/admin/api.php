<?php
//are all the params set?



//Do we have a correct user?

//do we have an existing CRN?

if(!empty($_GET['user']) && !empty($_GET['pw']))
{
    $db = new db("mysql:host=localhost;dbname=linnett1_damco", "linnett1_damco", "L3sl3yL2");

    $sql='SELECT id from adminlist where user="'.$_GET['user'].'" and password="'.$_GET['pw'].'"';

    $res=$db->run($sql);
    if(!empty($res))
    {
        if(!empty($_GET['crn']))
        {
            $sql='select logoFile as target from submits where firstName="'.$_GET['crn'].'"';
            $res=$db->run($sql);
            if(!empty($res))
            {
                $target=$res[0]['target'];
                outputXML($target);
            }
            else
            {
                outputXML('','CRNNotKnown');
            }
        }
        else
        {
            outputXML('','Other','CRN not supplied');
        }
    }
    else
    {
        outputXML('','Other','Invalid user and pw combination');
    }
}
else
{
    outputXML('','Other','user and pw not supplied');
}

function outputXML($target='',$error='',$other='')
{
    $aError['NoImagesAvailable']='The image is not available';
    $aError['CRNNotKnown']='Unknown CRN provided';
    $aError['SystemNotAvailable']='System is currently not responding';
    $aError['Other']='';

    //CHANGE THESE WHEN GOING LIVE!!!!
    $url='http://damco.webjunky.co.za/admin/uploads/';
    $file_path='/home/linnett1/public_html/damco/admin/uploads/';

    if(!empty($error))
    {
        if(array_key_exists($error,$aError))
        {
            if($error=='Other')
            {
                $output='<pod><error reason="'.$error.'">'.$other.'</error></pod>';
            }
            else
            {
                $output='<pod><error reason="'.$error.'">'.$aError[$error].'</error></pod>';
            }
        }
        else
        {
            $output='<pod><error reason="Other">An unknown error occurred</error></pod>';
        }
    }
    else
    {
        $url=$url.$target;

        $file=$file_path.$target;

        if($size = getimagesize($file))
        {
            $aType=explode('/',$size['mime']);
            $type=strtoupper($aType[1]);
            $output='<pod><image type="'.$type.'" src="'.$url.'" '.$size[3].' /></pod>';
        }
        else
        {
            $output='<pod><error reason="NoImagesAvailable">The image is not available</error></pod>';
        }


        //
    }
    header('Content-Type: text/xml; charset=utf-8');
    echo('<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.$output);
    exit();
}

////DB CLASS
class db extends PDO {
	private $error;
	private $sql;
	private $bind;
	private $errorCallbackFunction;
	private $errorMsgFormat;

	public function __construct($dsn, $user="", $passwd="") {
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try {
			parent::__construct($dsn, $user, $passwd, $options);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}

	private function debug() {
		if(!empty($this->errorCallbackFunction)) {
			$error = array("Error" => $this->error);
			if(!empty($this->sql))
				$error["SQL Statement"] = $this->sql;
			if(!empty($this->bind))
				$error["Bind Parameters"] = trim(print_r($this->bind, true));

			$backtrace = debug_backtrace();
			if(!empty($backtrace)) {
				foreach($backtrace as $info) {
					if($info["file"] != __FILE__)
						$error["Backtrace"] = $info["file"] . " at line " . $info["line"];
				}
			}

			$msg = "";
			if($this->errorMsgFormat == "html") {
				if(!empty($error["Bind Parameters"]))
					$error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
				$css = trim(file_get_contents(dirname(__FILE__) . "/error.css"));
				$msg .= '<style type="text/css">' . "\n" . $css . "\n</style>";
				$msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
				foreach($error as $key => $val)
					$msg .= "\n\t<label>" . $key . ":</label>" . $val;
				$msg .= "\n\t</div>\n</div>";
			}
			elseif($this->errorMsgFormat == "text") {
				$msg .= "SQL Error\n" . str_repeat("-", 50);
				foreach($error as $key => $val)
					$msg .= "\n\n$key:\n$val";
			}

			$func = $this->errorCallbackFunction;
			$func($msg);
		}
	}

	public function delete($table, $where, $bind="") {
		$sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
		$this->run($sql, $bind);
	}

	private function filter($table, $info) {
		$driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
		if($driver == 'sqlite') {
			$sql = "PRAGMA table_info('" . $table . "');";
			$key = "name";
		}
		elseif($driver == 'mysql') {
			$sql = "DESCRIBE " . $table . ";";
			$key = "Field";
		}
		else {
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
			$key = "column_name";
		}

		if(false !== ($list = $this->run($sql))) {
			$fields = array();
			foreach($list as $record)
				$fields[] = $record[$key];
			return array_values(array_intersect($fields, array_keys($info)));
		}
		return array();
	}

	private function cleanup($bind) {
		if(!is_array($bind)) {
			if(!empty($bind))
				$bind = array($bind);
			else
				$bind = array();
		}
		return $bind;
	}

	public function insert_bulk($table, $cols, $data)
	{

		$iFields=count($cols);
		$iData=count($data);

		for($i = 0; $i < $iFields; $i++)
        {
            $pholder[] = '?';
        }

		$sql = 'INSERT INTO '. $table . ' (`' . implode("`, `", $cols) . '`) VALUES ';

		$placeholder='('.implode(',',$pholder).')';

		$qPart = array_fill(0, $iData, $placeholder);

		$sql.=  implode(",",$qPart);
;
		$stmt = $this->prepare($sql);

		$iBind = 0;
		foreach($data as $item)
		{
			foreach($item as $key=>$value)
			{
				$iBind++;
				$stmt->bindValue($iBind, $value);
			}
		}
		$stmt->execute();
	}

	public function insert($table, $info) {
		$fields = $this->filter($table, $info);
		$sql = "INSERT INTO " . $table . " (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
		$bind = array();
		foreach($fields as $field)
			$bind[":$field"] = $info[$field];
		return $this->run($sql, $bind);
	}

	public function run($sql, $bind="") {
		$this->sql = trim($sql);
		$this->bind = $this->cleanup($bind);
		$this->error = "";

		try {
			$pdostmt = $this->prepare($this->sql);
			if($pdostmt->execute($this->bind) !== false) {
				if(preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql))
					return $pdostmt->fetchAll(PDO::FETCH_ASSOC);
				elseif(preg_match("/^(" . implode("|", array("delete", "insert", "update")) . ") /i", $this->sql))
					return $pdostmt->rowCount();
			}
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			$this->debug();
			return false;
		}
	}

	public function select($table, $where="", $bind="", $fields="*") {
		$sql = "SELECT " . $fields . " FROM " . $table;
		if(!empty($where))
			$sql .= " WHERE " . $where;
		$sql .= ";";
		return $this->run($sql, $bind);
	}

	public function setErrorCallbackFunction($errorCallbackFunction, $errorMsgFormat="html") {
		//Variable functions for won't work with language constructs such as echo and print, so these are replaced with print_r.
		if(in_array(strtolower($errorCallbackFunction), array("echo", "print")))
			$errorCallbackFunction = "print_r";

		if(function_exists($errorCallbackFunction)) {
			$this->errorCallbackFunction = $errorCallbackFunction;
			if(!in_array(strtolower($errorMsgFormat), array("html", "text")))
				$errorMsgFormat = "html";
			$this->errorMsgFormat = $errorMsgFormat;
		}
	}

	public function update($table, $info, $where, $bind="") {
		$fields = $this->filter($table, $info);
		$fieldSize = sizeof($fields);

		$sql = "UPDATE " . $table . " SET ";
		for($f = 0; $f < $fieldSize; ++$f) {
			if($f > 0)
				$sql .= ", ";
			$sql .= $fields[$f] . " = :update_" . $fields[$f];
		}
		$sql .= " WHERE " . $where . ";";

		$bind = $this->cleanup($bind);
		foreach($fields as $field)
			$bind[":update_$field"] = $info[$field];

		return $this->run($sql, $bind);
	}
}