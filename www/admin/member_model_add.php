<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_New');
$mysql_version = $dsql->GetVersion();
$mysql_versions = explode(".",trim($mysql_version));
$mysql_version = $mysql_versions[0].".".$mysql_versions[1];
if(empty($action)){
	$row = $dsql->GetOne("Select id From #@__member_model order by id desc limit 0,1 ");
	if(is_array($row)){
		$newid = $row['id']+1;
	}else{
		$newid = 1;
	}
	include(DEDEADMIN."/templets/member_model_add.htm");
}else{
	if(ereg("[^0-9-]",$id)||empty($id)){
		ShowMsg("<font color=red>'��Աģ��ID'</font>����Ϊ���֣�","-1");
		exit();
	}
	if($table==""){
		ShowMsg("��������Ϊ�գ�","-1");
		exit();
	}
	$state = isset($state) && is_numeric($state) ? $state : 0;
	$name = htmlspecialchars($name);
	$row = $dsql->GetOne("SELECT * FROM #@__member_model WHERE id='$id' OR `table` LIKE '$table' OR name LIKE '$name' ");
	if(is_array($row)){
		ShowMsg("���ܻ�Աģ�͵ġ�ID���������ơ������ݿ����Ѵ��ڣ������ظ�ʹ�ã�","-1");
		exit();
	}
	$query = "SHOW TABLES FROM {$dsql->dbName} ";
	$dsql->SetQuery($query);
	$dsql->execute();
	while($row = $dsql->getarray()){
		if(empty($row[0])){
			$row[0] = '';
		}if($table == $row[0]){
			showmsg('ָ���ı������ݿ����ظ�', '-1');
			exit();
		}
	}
	$sql = "CREATE TABLE IF NOT EXISTS  `$table`(
	`mid` int(10) unsigned NOT NULL auto_increment,
	";
	if($mysql_version < 4.1){
		$sql .= " PRIMARY KEY  (`mid`)\r\n) TYPE=MyISAM; ";
	}else{
		$sql .= " PRIMARY KEY  (`mid`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
	}
	if($dsql->executenonequery($sql)){
		$query = "INSERT INTO #@__member_model (`id`, `name`, `table`, `description`, `issystem`, `state`) VALUES ('$id', '$name', '$table', '$description', 0, '$state')";
		$dsql->executenonequery($query);
		//���»�Աģ�ͻ���
	  UpDateMemberModCache();
		showmsg('��Աģ�ʹ����ɹ�������������ֶ�', 'member_model_main.php');
	}else{
		showmsg('��Աģ�ʹ���ʧ��', '-1');
	}
}


?>