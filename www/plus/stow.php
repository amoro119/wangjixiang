<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");

$aid = ( isset($aid) && is_numeric($aid) ) ? $aid : 0;
$type=empty($type)? "" : $type;
if($aid==0)
{
	ShowMsg('�ĵ�id����Ϊ��!','javascript:window.close();');
	exit();
}

require_once(DEDEINC."/memberlogin.class.php");
$ml = new MemberLogin();

if($ml->M_ID==0)
{
	ShowMsg('ֻ�л�Ա�������ղز�����','javascript:window.close();');
	exit();
}


//��ȡ�ĵ���Ϣ
$arcRow = GetOneArchive($aid);
if($arcRow['aid']=='')
{
	ShowMsg("�޷��ղ�δ֪�ĵ�!","javascript:window.close();");
	exit();
}
extract($arcRow, EXTR_SKIP);

$addtime = time();
if($type==''){
	$row = $dsql->GetOne("Select * From `#@__member_stow` where aid='$aid' And mid='{$ml->M_ID}' AND type='' ");
	if(!is_array($row))
	{
		$dsql->ExecuteNoneQuery("INSERT INTO `#@__member_stow`(mid,aid,title,addtime) VALUES ('".$ml->M_ID."','$aid','".addslashes($arctitle)."','$addtime'); ");
  }
}else{
	$row = $dsql->GetOne("Select * From `#@__member_stow` where type='$type' and (aid='$aid' And mid='{$ml->M_ID}')");
  if(!is_array($row)){
  	$dsql->ExecuteNoneQuery(" INSERT INTO `#@__member_stow`(mid,aid,title,addtime,type) VALUES ('".$ml->M_ID."','$aid','$title','$addtime','$type'); ");
  }
}

//�����û�ͳ��
$row = $dsql->GetOne("SELECT COUNT(*) AS nums FROM `#@__member_stow` WHERE `mid`='{$ml->M_ID}' ");
$dsql->ExecuteNoneQuery("UPDATE #@__member_tj SET `stow`='$row[nums]' WHERE `mid`='".$ml->M_ID."'");

ShowMsg('�ɹ��ղ�һƪ�ĵ���','javascript:window.close();');
?>