<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_User');
require_once(DEDEINC."/typelink.class.php");
if(empty($dopost))
{
	$dopost='';
}
if($dopost=='add')
{
	if(ereg("[^0-9a-zA-Z_@!\.-]", $pwd) || ereg("[^0-9a-zA-Z_@!\.-]", $userid))
	{
		ShowMsg('�������û������Ϸ���<br />��ʹ��[0-9a-zA-Z_@!.-]�ڵ��ַ���', '-1', 0, 3000);
		exit();
	}
	$safecodeok = substr(md5($cfg_cookie_encode.$randcode), 0, 24);
	if($safecode != $safecodeok )
	{
		ShowMsg('����д��ȫ��֤����','-1',0,3000);
		exit();
	}
	$row = $dsql->GetOne("Select count(*) as dd from `#@__member` where userid like '$userid' ");
	if($row['dd']>0)
	{
		ShowMsg('�û����Ѵ��ڣ�','-1');
		exit();
	}
	$mpwd = md5($pwd);
	$pwd = substr(md5($pwd), 5, 20);

  $typeid = join(',', $typeids);
	if($typeid=='0') $typeid = '';
	
	//����ǰ̨��Ա�ʺ�
	$adminquery = "INSERT INTO `#@__member` (`mtype`,`userid`,`pwd`,`uname`,`sex`,`rank`,`money`,`email`,
	               `scores` ,`matt` ,`face`,`safequestion`,`safeanswer` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
               VALUES ('����','$userid','$mpwd','$uname','��','100','0','$email','1000','10','','0','','0','','0',''); ";
	$dsql->ExecuteNoneQuery($adminquery);

	$mid = $dsql->GetLastID();
	if($mid <= 0 )
	{
		die($dsql->GetError().' ���ݿ����');
	}
	
	//��̨����Ա
	$inquery = "Insert Into `#@__admin`(id,usertype,userid,pwd,uname,typeid,tname,email)
													values('$mid','$usertype','$userid','$pwd','$uname','$typeid','$tname','$email'); ";
	$rs = $dsql->ExecuteNoneQuery($inquery);
	
	$adminquery = "INSERT INTO `#@__member_person` (`mid`,`onlynet`,`sex`,`uname`,`qq`,`msn`,`tel`,`mobile`,`place`,`oldplace`,`birthday`,`star`,
 	              `income` , `education` , `height` , `bodytype` , `blood` , `vocation` , `smoke` , `marital` , `house` ,`drink` , `datingtype` , `language` , `nature` , `lovemsg` , `address`,`uptime`)
                VALUES ('$mid', '1', '��', '{$userid}', '', '', '', '', '0', '0','1980-01-01', '1', '0', '0', '160', '0', '0', '0', '0', '0', '0','0', '0', '', '', '', '','0'); ";
	$dsql->ExecuteNoneQuery($adminquery);
	
	$adminquery = "INSERT INTO `#@__member_tj` (`mid`,`article`,`album`,`archives`,`homecount`,`pagecount`,`feedback`,`friend`,`stow`)
 	                VALUES ('$mid','0','0','0','0','0','0','0','0'); ";
	$dsql->ExecuteNoneQuery($adminquery);
	
	$adminquery = "Insert Into `#@__member_space`(`mid` ,`pagesize` ,`matt` ,`spacename` ,`spacelogo` ,`spacestyle`, `sign` ,`spacenews`)
	            Values('$mid','10','0','{$uname}�Ŀռ�','','person','',''); ";
	$dsql->ExecuteNoneQuery($adminquery);
	
	ShowMsg('�ɹ�����һ���û���', 'sys_admin_user.php');
	exit();
}
$randcode = mt_rand(10000, 99999);
$safecode = substr(md5($cfg_cookie_encode.$randcode), 0, 24);
$typeOptions = '';
$dsql->SetQuery(" Select id,typename From `#@__arctype` where reid=0 And (ispart=0 Or ispart=1) ");
$dsql->Execute('op');
while($row = $dsql->GetObject('op'))
{
	$topc = $row->id;
	$typeOptions .= "<option value='{$row->id}' class='btype'>{$row->typename}</option>\r\n";
	$dsql->SetQuery(" Select id,typename From `#@__arctype` where reid={$row->id} And (ispart=0 Or ispart=1) ");
	$dsql->Execute('s');
	while($row = $dsql->GetObject('s'))
	{
		$typeOptions .= "<option value='{$row->id}' class='stype'>��{$row->typename}</option>\r\n";
	}
}

include DedeInclude('templets/sys_admin_user_add.htm');

?>