<?php
require_once(dirname(__FILE__).'/config.php');
$mid = isset($mid) && is_numeric($mid) ? $mid : 0;
AjaxHead();

//��ʾ����
if($action == 'show')
{
	$sql = "SELECT * FROM #@__member_group WHERE mid={$cfg_ml->M_ID} ORDER BY id DESC";
	$dsql->Execute('me',$sql);
	echo "<select name='membergroup' id='m_{$mid}' style='width:100px'>";
	while($arr = $dsql->GetArray('me'))
	{
	  echo "		<option value='{$arr['id']}'>{$arr['groupname']}</option>\r\n";
	}
	echo "</select>";
	echo '<button onclick="postMemberGroup(\''.$mid.'\')" class="bt3">�ύ</button> <button type="button" onclick="location.reload();" class="bt3">ȡ��</button>';
}

//�༭����
elseif($action == 'post')
{
	if(empty($membergroup)){
		echo "����û�����÷��飡";
		exit;
	}
	$sql = "UPDATE `#@__member_friends` SET `groupid`='{$membergroup}' WHERE `fid`='{$mid}' AND `mid`='{$cfg_ml->M_ID}';";
	$dsql->ExecuteNoneQuery($sql);
	$row = $dsql->GetOne("SELECT groupname FROM #@__member_group WHERE mid = {$cfg_ml->M_ID} AND id={$membergroup}");
	echo "&nbsp;".$row['groupname']."&nbsp;&nbsp;<a href='#' onclick='EditMemberGroup($mid);return false;'>�޸�</a>";
}

//��ʾ����
elseif($action == 'desshow')
{
	$sql = "SELECT * FROM  #@__member_friends WHERE `fid`='{$mid}' AND `mid`='{$cfg_ml->M_ID}'";
	$row = $dsql->getone($sql);
	echo '<input id="m_'.$mid.'" name="mdescription" value="'.$row['description'].'" class="intxt" style="width:100px;"/>';
	echo '<button onclick="postDescription(\''.$mid.'\')" class="bt3">�ύ</button> <button type="button" onclick="location.reload();" class="bt3">ȡ��</button>';  
}

//�༭����
elseif($action == 'despost')
{
	$sql = "UPDATE `#@__member_friends` SET `description`='{$mdescription}' WHERE `fid`='{$mid}' AND `mid`='{$cfg_ml->M_ID}'";
	$dsql->ExecuteNoneQuery($sql);
	$row = $dsql->GetOne("SELECT description FROM #@__member_friends WHERE  `fid`='{$mid}' AND `mid`='{$cfg_ml->M_ID}'");
	echo "&nbsp;".$row['description']."&nbsp;&nbsp;<a href='#' onclick='EditDescription($mid);return false;'>�޸�</a>";
}
?>