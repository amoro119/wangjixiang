<?php
//��վ������
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_SoftConfig');
if(empty($dopost)) $dopost = '';

//����
if($dopost=="save")
{
	$configfile = DEDEDATA."/cache/inc_remote_config.php";
	$rminfo = serialize(array('rmhost'=>$c_rmhost, 'rmport'=>$c_rmport, 
	                           'rmname'=>$c_rmname, 'rmpwd'=>$c_rmpwd));
	$query = "UPDATE `#@__multiserv_config` SET
   		`remoteuploads` = '$c_remoteuploads' ,
   		`remoteupUrl` ='$c_remoteupUrl' ,
   		`rminfo` = '$rminfo',
   		`servinfo` = '$c_servinfo'";
	$dsql->ExecuteNoneQuery($query);
	//�������û����ļ�
	
	$configstr = "\$remoteuploads = '".$c_remoteuploads."';\r\n";
	$configstr .= "\$remoteupUrl = '".$c_remoteupUrl."';\r\n";
	$configstr .= "\$rmhost = '".$c_rmhost."';\r\n";
	$configstr .= "\$rmport = '".$c_rmport."';\r\n";
	$configstr .= "\$rmname = '".$c_rmname."';\r\n";
	$configstr .= "\$rmpwd = '".$c_rmpwd."';\r\n";
	$configstr = "<"."?php\r\n".$configstr."?".">\r\n";
	
	$fp = fopen($configfile,"w") or die("д���ļ� $safeconfigfile ʧ�ܣ�����Ȩ�ޣ�");
	fwrite($fp,$configstr);
	fclose($fp);
	
	ShowMsg('�ɹ����������', 'sys_multiserv.php');
	exit();
}

//��ȡ����
$row = $dsql->GetOne("SELECT * FROM `#@__multiserv_config` ");
if(!is_array($row))
{
	$dsql->ExecuteNoneQuery("INSERT INTO `#@__multiserv_config` (`remoteuploads`, `remoteupUrl`, `rminfo`, `servinfo`) 
	                         VALUES ('0', 'http://img.dedecms.com', '', '')");
	$row['remoteuploads']   = 1;
	$row['remoteupUrl'] = 'http://img.dedecms.com';
	$row['rminfo']    = '';
	$row['servinfo']   = '';
}
//��������Ϣ���д���
if(!empty($row['rminfo']))
{
	$row['rminfo'] = unserialize($row['rminfo']);
	
}

//��ȡ��Ա�б�
$query = "Select #@__admin.*,#@__admintype.typename From #@__admin left join #@__admintype on #@__admin.usertype=#@__admintype.rank";
$dsql->SetQuery($query);
$dsql->Execute();
while($row3 = $dsql->GetArray())
{
	$adminLists[] = $row3;
}
include DedeInclude('templets/sys_multiserv.htm');
exit();

?>