<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_SoftConfig');
if(empty($dopost)) $dopost = '';

//����
if($dopost=="save")
{
	if($dfrank>0 || $dfywboy>0) $gotojump = 1;
	$query = "UPDATE `#@__softconfig` SET
   		`downtype` = '$downtype' ,
   		`gotojump` ='$gotojump' ,
   		`ismoresite` = '$ismoresite',
   		`islocal` = '$islocal',
   		`sites` = '$sites',
   		`moresitedo` = '$moresitedo',
   		`dfrank` = '$dfrank',
   		`dfywboy` = '$dfywboy',
   		`argrange` = '$argrange',
   		downmsg = '$downmsg' ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg('�ɹ����������', 'soft_config.php');
	exit();
}

//��ȡ����
$row = $dsql->GetOne("select * From `#@__softconfig` ");
if(!is_array($row))
{
	$dsql->ExecuteNoneQuery("INSERT INTO `#@__softconfig`(`downtype`,`ismoresite`,`islocal`,`gotojump`,`sites`,`downmsg`,`moresitedo`,`dfrank`,`dfywboy`, `argrange`)
	VALUES ('1', '0','1', '0', '' ,'$downmsg','1', '0', '0', '0'); ");
	$row['downtype']   = 1;
	$row['ismoresite'] = 0;
	$row['islocal']    = 1;
	$row['gotojump']   = 0;
	$row['sites']      = '';
	$row['moresitedo']      = '1';
	$row['dfrank']      = '0';
	$row['dfywboy']      = '0';
	$row['downmsg']    = '';
	$row['argrange'] = 0;
}
include DedeInclude('templets/soft_config.htm');
exit();

?>