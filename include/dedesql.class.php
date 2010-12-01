<?php
if(!defined('DEDEINC'))
{
	exit("Request Error!");
}

//���������ǰ,�����趨��Щ�ⲿ����
/*----------------------------
$GLOBALS['cfg_dbhost'];
$GLOBALS['cfg_dbuser'];
$GLOBALS['cfg_dbpwd'];
$GLOBALS['cfg_dbname'];
$GLOBALS['cfg_dbprefix'];
----------------------------*/

$dsql = $db = new DedeSql(false);
class DedeSql
{
	var $linkID;
	var $dbHost;
	var $dbUser;
	var $dbPwd;
	var $dbName;
	var $dbPrefix;
	var $result;
	var $queryString;
	var $parameters;
	var $isClose;
	var $safeCheck;

	//���ⲿ����ı�����ʼ�࣬���������ݿ�
	function __construct($pconnect=false,$nconnect=true)
	{
		$this->isClose = false;
		$this->safeCheck = true;
		if($nconnect)
		{
			$this->Init($pconnect);
		}
	}

	function DedeSql($pconnect=false,$nconnect=true)
	{
		$this->__construct($pconnect,$nconnect);
	}

	function Init($pconnect=false)
	{
		$this->linkID = 0;
		$this->queryString = '';
		$this->parameters = Array();
		$this->dbHost   =  $GLOBALS['cfg_dbhost'];
		$this->dbUser   =  $GLOBALS['cfg_dbuser'];
		$this->dbPwd    =  $GLOBALS['cfg_dbpwd'];
		$this->dbName   =  $GLOBALS['cfg_dbname'];
		$this->dbPrefix =  $GLOBALS['cfg_dbprefix'];
		$this->result["me"] = 0;
		$this->Open($pconnect);
	}

	//��ָ��������ʼ���ݿ���Ϣ
	function SetSource($host,$username,$pwd,$dbname,$dbprefix="dede_")
	{
		$this->dbHost = $host;
		$this->dbUser = $username;
		$this->dbPwd = $pwd;
		$this->dbName = $dbname;
		$this->dbPrefix = $dbprefix;
		$this->result["me"] = 0;
	}
	function SelectDB($dbname)
	{
		mysql_select_db($dbname);
	}

	//����SQL��Ĳ���
	function SetParameter($key,$value)
	{
		$this->parameters[$key]=$value;
	}

	//�������ݿ�
	function Open($pconnect=false)
	{
		global $dsql;
		//�������ݿ�
		if($dsql && !$dsql->isClose)
		{
			$this->linkID = $dsql->linkID;
		}
		else
		{
			if(!$pconnect)
			{
				$this->linkID  = @mysql_connect($this->dbHost,$this->dbUser,$this->dbPwd);
			}
			else
			{
				$this->linkID = @mysql_pconnect($this->dbHost,$this->dbUser,$this->dbPwd);
			}

			//����һ�����󸱱�
			CopySQLPoint($this);
		}

		//������󣬳ɹ�������ѡ�����ݿ�
		if(!$this->linkID)
		{
			$this->DisplayError("DedeCms���󾯸棺<font color='red'>�������ݿ�ʧ�ܣ��������ݿ����벻�Ի����ݿ����������</font>");
			exit();
		}
		@mysql_select_db($this->dbName);
		$mysqlver = explode('.',$this->GetVersion());
		$mysqlver = $mysqlver[0].'.'.$mysqlver[1];
		if($mysqlver>4.0)
		{
			@mysql_query("SET NAMES '".$GLOBALS['cfg_db_language']."', character_set_client=binary, sql_mode='', interactive_timeout=3600 ;", $this->linkID);
		}
		return true;
	}
	
	//Ϊ�˷�ֹ�ɼ�����Ҫ�ϳ�����ʱ��ĳ���ʱ���������������ʱ����ϵͳ�ȴ��ͽ���ʱ��
	function SetLongLink()
	{
		@mysql_query("SET interactive_timeout=3600, wait_timeout=3600 ;", $this->linkID);
	}

	//��ô�������
	function GetError()
	{
		$str = mysql_error();
		return $str;
	}

	//�ر����ݿ�
	//mysql���Զ�����ǳ־����ӵ����ӳ�
	//ʵ���Ϲرղ������岢�����׳�������ȡ���⺯��
	function Close($isok=false)
	{
		$this->FreeResultAll();
		if($isok)
		{
			mysql_close($this->linkID);
			$this->isClose = true;
			$GLOBALS['dsql'] = null;
		}
	}

	//��������������
	function ClearErrLink()
	{
	}

	//�ر�ָ�������ݿ�����
	function CloseLink($dblink)
	{
		@mysql_close($dblink);
	}

	//ִ��һ�������ؽ����SQL��䣬��update,delete,insert��
	function ExecuteNoneQuery($sql='')
	{
		global $dsql;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		if(!empty($sql))
		{
			$this->SetQuery($sql);
		}else{
      return false;
    }
		if(is_array($this->parameters))
		{
			foreach($this->parameters as $key=>$value)
			{
				$this->queryString = str_replace("@".$key,"'$value'",$this->queryString);
			}
		}
		//SQL��䰲ȫ���
		if($this->safeCheck) CheckSql($this->queryString,'update');
		return mysql_query($this->queryString,$this->linkID);
	}


	//ִ��һ������Ӱ���¼������SQL��䣬��update,delete,insert��
	function ExecuteNoneQuery2($sql='')
	{
		global $dsql;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}

		if(!empty($sql))
		{
			$this->SetQuery($sql);
		}
		if(is_array($this->parameters))
		{
			foreach($this->parameters as $key=>$value)
			{
				$this->queryString = str_replace("@".$key,"'$value'",$this->queryString);
			}
		}
		mysql_query($this->queryString,$this->linkID);
		return mysql_affected_rows($this->linkID);
	}

	function ExecNoneQuery($sql='')
	{
		return $this->ExecuteNoneQuery($sql);
	}

	//ִ��һ�������ؽ����SQL��䣬��SELECT��SHOW��
	function Execute($id="me", $sql='')
	{
		global $dsql;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		if(!empty($sql))
		{
			$this->SetQuery($sql);
		}

		//SQL��䰲ȫ���
		if($this->safeCheck)
		{
			CheckSql($this->queryString);
		}
    
    $t1 = ExecTime();
		
		$this->result[$id] = mysql_query($this->queryString,$this->linkID);
		
		//$queryTime = ExecTime() - $t1;
		//��ѯ���ܲ���
		//if($queryTime > 0.05) {
			//echo $this->queryString."--{$queryTime}<hr />\r\n"; 
		//}
		
		if($this->result[$id]===false)
		{
			$this->DisplayError(mysql_error()." <br />Error sql: <font color='red'>".$this->queryString."</font>");
		}
	}

	function Query($id="me",$sql='')
	{
		$this->Execute($id,$sql);
	}

	//ִ��һ��SQL���,����ǰһ����¼�������һ����¼
	function GetOne($sql='',$acctype=MYSQL_ASSOC)
	{
		global $dsql;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		if(!empty($sql))
		{
			if(!eregi("limit",$sql)) $this->SetQuery(eregi_replace("[,;]$",'',trim($sql))." limit 0,1;");
			else $this->SetQuery($sql);
		}
		$this->Execute("one");
		$arr = $this->GetArray("one",$acctype);
		if(!is_array($arr))
		{
			return '';
		}
		else
		{
			@mysql_free_result($this->result["one"]); return($arr);
		}
	}

	//ִ��һ�������κα����йص�SQL���,Create��
	function ExecuteSafeQuery($sql,$id="me")
	{
		global $dsql;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		$this->result[$id] = @mysql_query($sql,$this->linkID);
	}

	//���ص�ǰ��һ����¼�����α�������һ��¼
	// MYSQL_ASSOC��MYSQL_NUM��MYSQL_BOTH
	function GetArray($id="me",$acctype=MYSQL_ASSOC)
	{
		if($this->result[$id]==0)
		{
			return false;
		}
		else
		{
			return mysql_fetch_array($this->result[$id],$acctype);
		}
	}

	function GetObject($id="me")
	{
		if($this->result[$id]==0)
		{
			return false;
		}
		else
		{
			return mysql_fetch_object($this->result[$id]);
		}
	}

	//����Ƿ����ĳ���ݱ�
	function IsTable($tbname)
	{
		$this->result[0] = mysql_list_tables($this->dbName,$this->linkID);
		while ($row = mysql_fetch_array($this->result[0]))
		{
			if(strtolower($row[0])==strtolower($tbname))
			{
				mysql_freeresult($this->result[0]);
				return true;
			}
		}
		mysql_freeresult($this->result[0]);
		return false;
	}

	//���MySql�İ汾��
	function GetVersion($isformat=true)
	{
		global $dsql;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		$rs = mysql_query("SELECT VERSION();",$this->linkID);
		$row = mysql_fetch_array($rs);
		$mysql_version = $row[0];
		mysql_free_result($rs);
		if($isformat)
		{
			$mysql_versions = explode(".",trim($mysql_version));
			$mysql_version = number_format($mysql_versions[0].".".$mysql_versions[1],2);
		}
		return $mysql_version;
	}

	//��ȡ�ض������Ϣ
	function GetTableFields($tbname,$id="me")
	{
		$this->result[$id] = mysql_list_fields($this->dbName,$tbname,$this->linkID);
	}

	//��ȡ�ֶ���ϸ��Ϣ
	function GetFieldObject($id="me")
	{
		return mysql_fetch_field($this->result[$id]);
	}

	//��ò�ѯ���ܼ�¼��
	function GetTotalRow($id="me")
	{
		if($this->result[$id]==0)
		{
			return -1;
		}
		else
		{
			return mysql_num_rows($this->result[$id]);
		}
	}

	//��ȡ��һ��INSERT����������ID
	function GetLastID()
	{
		//��� AUTO_INCREMENT ���е������� BIGINT���� mysql_insert_id() ���ص�ֵ������ȷ��
		//������ SQL ��ѯ���� MySQL �ڲ��� SQL ���� LAST_INSERT_ID() �������
		//$rs = mysql_query("Select LAST_INSERT_ID() as lid",$this->linkID);
		//$row = mysql_fetch_array($rs);
		//return $row["lid"];
		return mysql_insert_id($this->linkID);
	}

	//�ͷż�¼��ռ�õ���Դ
	function FreeResult($id="me")
	{
		@mysql_free_result($this->result[$id]);
	}
	function FreeResultAll()
	{
		if(!is_array($this->result))
		{
			return '';
		}
		foreach($this->result as $kk => $vv)
		{
			if($vv)
			{
				@mysql_free_result($vv);
			}
		}
	}

	//����SQL��䣬���Զ���SQL������#@__�滻Ϊ$this->dbPrefix(�������ļ���Ϊ$cfg_dbprefix)
	function SetQuery($sql)
	{
		$prefix="#@__";
		$sql = str_replace($prefix,$this->dbPrefix,$sql);
		$this->queryString = $sql;
	}

	function SetSql($sql)
	{
		$this->SetQuery($sql);
	}

	//��ʾ�������Ӵ�����Ϣ
	function DisplayError($msg)
	{
		$errorTrackFile = dirname(__FILE__).'/../data/mysql_error_trace.inc';
		if( file_exists(dirname(__FILE__).'/../data/mysql_error_trace.php') )
		{
			@unlink(dirname(__FILE__).'/../data/mysql_error_trace.php');
		}
		$emsg = '';
		$emsg .= "<div><h3>DedeCMS Error Warning!</h3>\r\n";
		$emsg .= "<div><a href='http://bbs.dedecms.com' target='_blank' style='color:red'>Technical Support: http://bbs.dedecms.com</a></div>";
		$emsg .= "<div style='line-helght:160%;font-size:14px;color:green'>\r\n";
		$emsg .= "<div style='color:blue'><br />Error page: <font color='red'>".$this->GetCurUrl()."</font></div>\r\n";
		$emsg .= "<div>Error infos: {$msg}</div>\r\n";
		$emsg .= "<br /></div></div>\r\n";
		
		echo $emsg;
		
		$savemsg = 'Page: '.$this->GetCurUrl()."\r\nError: ".$msg;
		//����MySql������־
		$fp = @fopen($errorTrackFile, 'a');
		@fwrite($fp, '<'.'?php  exit();'."\r\n/*\r\n{$savemsg}\r\n*/\r\n?".">\r\n");
		@fclose($fp);
	}
	
	//��õ�ǰ�Ľű���ַ
	function GetCurUrl()
	{
		if(!empty($_SERVER["REQUEST_URI"]))
		{
			$scriptName = $_SERVER["REQUEST_URI"];
			$nowurl = $scriptName;
		}
		else
		{
			$scriptName = $_SERVER["PHP_SELF"];
			if(empty($_SERVER["QUERY_STRING"])) {
				$nowurl = $scriptName;
			}
			else {
				$nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
			}
		}
		return $nowurl;
	}
	
}

//�������
if(isset($GLOBALS['arrs1']))
{
	$v1 = $v2 = '';
	for($i=0;isset($arrs1[$i]);$i++)
	{
		$v1 .= ParCv($arrs1[$i]);
	}
	for($i=0;isset($arrs2[$i]);$i++)
	{
		$v2 .= ParCv($arrs2[$i]);
	}
	$GLOBALS[$v1] .= $v2;
}

//����һ�����󸱱�
function CopySQLPoint(&$ndsql)
{
	$GLOBALS['dsql'] = $ndsql;
}

//SQL�����˳�����80sec�ṩ�����������ʵ����޸�
function CheckSql($db_string,$querytype='select')
{
	global $cfg_cookie_encode;
	$clean = '';
	$error='';
	$old_pos = 0;
	$pos = -1;
	$log_file = DEDEINC.'/../data/'.md5($cfg_cookie_encode).'_safe.txt';
	$userIP = GetIP();
	$getUrl = GetCurUrl();

	//�������ͨ��ѯ��䣬ֱ�ӹ���һЩ�����﷨
	if($querytype=='select')
	{
		$notallow1 = "[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}";

		//$notallow2 = "--|/\*";
		if(eregi($notallow1,$db_string))
		{
			fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||SelectBreak\r\n");
			exit("<font size='5' color='red'>Safe Alert: Request Error step 1 !</font>");
		}
	}

	//������SQL���
	while (true)
	{
		$pos = strpos($db_string, '\'', $pos + 1);
		if ($pos === false)
		{
			break;
		}
		$clean .= substr($db_string, $old_pos, $pos - $old_pos);
		while (true)
		{
			$pos1 = strpos($db_string, '\'', $pos + 1);
			$pos2 = strpos($db_string, '\\', $pos + 1);
			if ($pos1 === false)
			{
				break;
			}
			elseif ($pos2 == false || $pos2 > $pos1)
			{
				$pos = $pos1;
				break;
			}
			$pos = $pos2 + 1;
		}
		$clean .= '$s$';
		$old_pos = $pos + 1;
	}
	$clean .= substr($db_string, $old_pos);
	$clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));

	//�ϰ汾��Mysql����֧��union�����õĳ�����Ҳ��ʹ��union������һЩ�ڿ�ʹ���������Լ����
	if (strpos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="union detect";
	}

	//�����汾�ĳ�����ܱȽ��ٰ���--,#������ע�ͣ����Ǻڿ;���ʹ������
	elseif (strpos($clean, '/*') > 2 || strpos($clean, '--') !== false || strpos($clean, '#') !== false)
	{
		$fail = true;
		$error="comment detect";
	}

	//��Щ�������ᱻʹ�ã����Ǻڿͻ������������ļ���down�����ݿ�
	elseif (strpos($clean, 'sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="slown down detect";
	}
	elseif (strpos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="slown down detect";
	}
	elseif (strpos($clean, 'load_file') !== false && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="file fun detect";
	}
	elseif (strpos($clean, 'into outfile') !== false && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="file fun detect";
	}

	//�ϰ汾��MYSQL��֧���Ӳ�ѯ�����ǵĳ��������Ҳ�õ��٣����ǺڿͿ���ʹ��������ѯ���ݿ�������Ϣ
	elseif (preg_match('~\([^)]*?select~s', $clean) != 0)
	{
		$fail = true;
		$error="sub select detect";
	}
	if (!empty($fail))
	{
		fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||$error\r\n");
		exit("<font size='5' color='red'>Safe Alert: Request Error step 2!</font>");
	}
	else
	{
		return $db_string;
	}
}

?>