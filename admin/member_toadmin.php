<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Edit');
if(empty($dopost))
{
	$dopost = '';
}
if(empty($fmdo))
{
	$fmdo = '';
}
$ENV_GOBACK_URL = isset($_COOKIE['ENV_GOBACK_URL']) ? 'member_main.php' : '';
$row = array();
/*----------------
function __Toadmin()
����Ϊ����Ա
----------------*/
if($dopost == "toadmin")
{
	$pwd = trim($pwd);
	if($pwd!='' && ereg("[^0-9a-zA-Z_@!\.-]",$pwd))
	{
		ShowMsg('���벻�Ϸ�����ʹ��[0-9a-zA-Z_@!.-]�ڵ��ַ���','-1',0,3000);
		exit();
	}
	$safecodeok = substr(md5($cfg_cookie_encode.$randcode),0,24);
	if($safecodeok!=$safecode)
	{
		ShowMsg("����д��ȷ�İ�ȫ��֤����","member_toadmin.php?id={$id}");
		exit();
	}
	$pwdm = '';
	if($pwd!='')
	{
		$inputpwd = ",pwd";
		$inputpwdv = ",'".substr(md5($pwd),5,20)."'";
		$pwdm = ",pwd='".md5($pwd)."'";
	}else{
		$row = $dsql->GetOne("select * from #@__member where mid='$id'");
		$password = $row['pwd'];
		$inputpwd = ",pwd";
		$pwd = substr($password,5,20);
		$inputpwdv = ",'".$pwd."'";
		$pwdm = ",pwd='".$password."'";
	}
	$typeids=(empty($typeids))? "" : $typeids;
	if($typeids=='')
	{
		ShowMsg("��Ϊ�ù���Աָ��������Ŀ��","member_toadmin.php?id={$id}");
		exit();
	}
	$typeid = join(',',$typeids);
	if($typeid=='0') $typeid = '';
	if($id!=1)
	{
		$query = "Insert Into `#@__admin`(id,usertype,userid$inputpwd,uname,typeid,tname,email)
					values('$id','$usertype','$userid'$inputpwdv,'$uname','$typeid','$tname','$email')";
	}
	else
	{
		$query = "Insert Into `#@__admin`(id,userid$inputpwd,uname,typeid,tname,email)
					values('$id','$userid'$inputpwdv,'$uname','$typeid','$tname','$email')";
	}
	$dsql->ExecuteNoneQuery($query);
	$query = "Update `#@__member` set rank='100',uname='$uname',matt='10',email='$email'$pwdm where mid='$id'";
	$dsql->ExecuteNoneQuery($query);
	$row = $dsql->GetOne("Select * From #@__admintype where rank='$usertype'");
	$floginid = $cuserLogin->getUserName();
	$fromid = $cuserLogin->getUserID();
	$subject = "��ϲ���Ѿ��ɹ�����Ϊ����Ա";
	$message = "�װ��Ļ�Ա{$userid},���Ѿ��ɹ�����Ϊ{$row['typename']},�������Ȩ����ͬ��վ��������Ա��ϵ��";
	$sendtime = $writetime = time();
	$inquery = "INSERT INTO `#@__member_pms` (`floginid`,`fromid`,`toid`,`tologinid`,`folder`,`subject`,`sendtime`,`writetime`,`hasview`,`isadmin`,`message`)
      VALUES ('$floginid','$fromid','$id','$userid','inbox','$subject','$sendtime','$writetime','0','0','$message'); ";
	$dsql->ExecuteNoneQuery($inquery);
	ShowMsg("�ɹ�����һ���ʻ���","member_main.php");
	exit();
}	
$id = ereg_replace("[^0-9]","",$id);
//��ʾ�û���Ϣ
$randcode = mt_rand(10000,99999);
$safecode = substr(md5($cfg_cookie_encode.$randcode),0,24);	
$typeOptions = '';
$typeid=(empty($typeid))? '' : $typeid;
$typeids = explode(',',$typeid);
$dsql->SetQuery("Select id,typename From `#@__arctype` where reid=0 And (ispart=0 Or ispart=1)");
$dsql->Execute('op');
while($nrow = $dsql->GetObject('op'))
{
	$typeOptions .= "<option value='{$nrow->id}' class='btype'".(in_array($nrow->id, $typeids) ? ' selected' : '').">{$nrow->typename}</option>\r\n";
	$dsql->SetQuery("Select id,typename From #@__arctype where reid={$nrow->id} And (ispart=0 Or ispart=1)");
	$dsql->Execute('s');
	while($nrow = $dsql->GetObject('s'))
	{
		$typeOptions .= "<option value='{$nrow->id}' class='stype'".(in_array($nrow->id, $typeids) ? ' selected' : '').">��{$nrow->typename}</option>\r\n";
	}
}
$row = $dsql->GetOne("select * from #@__member where mid='$id'");
include DedeInclude('templets/member_toadmin.htm');
?>