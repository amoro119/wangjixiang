<?php
require_once(dirname(__FILE__).'/config.php');
CheckPurview('sys_User');
require_once(DEDEINC.'/typelink.class.php');
if(empty($dopost)) $dopost = '';
$id = ereg_replace('[^0-9]','',$id);

if($dopost=='saveedit')
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
		ShowMsg("����д��ȷ�İ�ȫ��֤����","sys_admin_user_edit.php?id={$id}&dopost=edit");
		exit();
	}
	$pwdm = '';
	if($pwd!='')
	{
		$pwdm = ",pwd='".md5($pwd)."'";
		$pwd = ",pwd='".substr(md5($pwd),5,20)."'";
	}
	if(empty($typeids)){
		$typeid = '';
	}else{
		$typeid = join(',',$typeids);
		if($typeid=='0') $typeid = '';
	}
	if($id!=1){
		$query = "Update `#@__admin` set uname='$uname',usertype='$usertype',tname='$tname',email='$email',typeid='$typeid' $pwd where id='$id'";
	}else{
		$query = "Update `#@__admin` set uname='$uname',tname='$tname',email='$email',typeid='$typeid' $pwd where id='$id'";
	}
	$dsql->ExecuteNoneQuery($query);
	$query = "Update `#@__member` set uname='$uname',email='$email'$pwdm where mid='$id'";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ�����һ���ʻ���","sys_admin_user.php");
	exit();
}
else if($dopost=='delete')
{
	if(empty($userok))
	{
		$userok="";
	}
	if($userok!="yes")
	{
		$randcode = mt_rand(10000,99999);
		$safecode = substr(md5($cfg_cookie_encode.$randcode),0,24);
		require_once(DEDEINC."/oxwindow.class.php");
		$wintitle = "ɾ���û�";
		$wecome_info = "<a href='sys_admin_user.php'>ϵͳ�ʺŹ���</a>::ɾ���û�";
		$win = new OxWindow();
		$win->Init("sys_admin_user_edit.php","js/blank.js","POST");
		$win->AddHidden("dopost",$dopost);
		$win->AddHidden("userok","yes");
		$win->AddHidden("randcode",$randcode);
		$win->AddHidden("safecode",$safecode);
		$win->AddHidden("id",$id);
		$win->AddTitle("ϵͳ���棡");
		$win->AddMsgItem("��ȷ��Ҫɾ���û���$userid ��","50");
		$win->AddMsgItem("��ȫ��֤����<input name='safecode' type='text' id='safecode' size='16' style='width:200px' />&nbsp;(���Ʊ����룺 <font color='red'>$safecode</font> )","30");
		$winform = $win->GetWindow("ok");
		$win->Display();
		exit();
	}
	$safecodeok = substr(md5($cfg_cookie_encode.$randcode),0,24);
	if($safecodeok!=$safecode)
	{
		ShowMsg("����д��ȷ�İ�ȫ��֤����","sys_admin_user.php");
		exit();
	}

	//����ɾ��idΪ1�Ĵ������ʺţ�����ɾ���Լ�
	$rs = $dsql->ExecuteNoneQuery2("Delete From `#@__admin` where id='$id' And id<>1 And id<>'".$cuserLogin->getUserID()."' ");
	if($rs>0)
	{
		//����ǰ̨�û���Ϣ
		$dsql->ExecuteNoneQuery("Update `#@__member` set matt='0' where mid='$id' limit 1");
		ShowMsg("�ɹ�ɾ��һ���ʻ���","sys_admin_user.php");
	}
	else
	{
		ShowMsg("����ɾ��idΪ1�Ĵ������ʺţ�����ɾ���Լ���","sys_admin_user.php",0,3000);
	}
	exit();
}

//��ʾ�û���Ϣ
$randcode = mt_rand(10000,99999);
$safecode = substr(md5($cfg_cookie_encode.$randcode),0,24);
$typeOptions = '';
$row = $dsql->GetOne("Select * From `#@__admin` where id='$id'");
$typeids = explode(',', $row['typeid']);
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
include DedeInclude('templets/sys_admin_user_edit.htm');

?>