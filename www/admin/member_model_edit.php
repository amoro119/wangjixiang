<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_Edit');
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/oxwindow.class.php");
if(empty($dopost))
{
	$dopost="";
}
$id = (empty($id) ? 0 : intval($id));

/*----------------
function __SaveEdit()
-----------------*/
if($dopost=="save")
{
	$state = isset($state) && is_numeric($state) ? $state : 1;
  $description = htmlspecialchars($description);
	$name = htmlspecialchars($name);
	$query = "update `#@__member_model` set name = '$name', description = '$description', state='$state' where id='$id' ";
	$dsql->ExecuteNoneQuery($query);
	//���»�Աģ�ͻ���
	UpDateMemberModCache();
	ShowMsg("�ɹ�����һ����Աģ�ͣ�","member_model_main.php");
	exit();
}
/*----------------
function __Disabled()
-----------------*/
else if($dopost=="disabled")
{
	@set_time_limit(0);
	CheckPurview('c_Del');
	$row = $dsql->GetOne("Select * From #@__member_model where id='$id'");
	$statenum = ($row['state']==0)? 1 : 0;
	$statestr = ($row['state']==0)? '����' : '����';
	//����ģ��
	$dsql->ExecuteNoneQuery("Update #@__member_model set state={$statenum} where id='$id' ");
	//���»�Աģ�ͻ���
	UpDateMemberModCache();
	ShowMsg("�ɹ�{$statestr}һ����Աģ�ͣ�","member_model_main.php");
	exit();
	
}
/*----------------
function __Copy()
-----------------*/
else if($dopost=="copy")
{
	@set_time_limit(0);
	CheckPurview('c_Del');
	$row = $dsql->GetOne("Select * From #@__member_model where id='$id'");
	$thisname = $row['name'];
	$thistable = $row['table'];
	$thisinfo = $row['info'];
	$row = $dsql->GetOne("Select id From #@__member_model order by id desc limit 0,1 ");
	if(is_array($row))
	{
		$newid = $row['id']+1;
	}
	else
	{
		$newid = 1;
	}
	if(empty($job))
	{
		$job="";
	}
	//ȷ����ʾ
	if($job=="")
	{
		$wintitle = "��Աģ�͹���-���ƻ�Աģ��";
		$wecome_info = "<a href='member_model_main.php'>��Աģ�͹���</a>::���ƻ�Աģ��";
		$win = new OxWindow();
		$win->Init("member_model_edit.php","js/blank.js","POST");
		$win->AddHidden("job","yes");
		$win->AddHidden("dopost",$dopost);
		$win->AddHidden("id",$id);
		$win->AddTitle("��ȷʵҪ���� \"".$thisname."\" �����Աģ�ͣ�");
		$msg ="";
		$msg.="<table width='460' border='0' cellspacing='0' cellpadding='0'>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td width='170' height='24' align='center'>��ģ��ID��</td>\r\n";
    $msg.=" <td width='230'><input name='newid' type='text' id='newid' size='6' value='{$newid}'/></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>��ģ�����ƣ�</td>\r\n";
    $msg.=" <td><input name='newname' type='text' id='newname' value='{$thisname}{$newid}' style='width:250px'/></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>ģ�����ݱ�</td>\r\n";
    $msg.=" <td><input name='newtable' type='text' id='newtable' value='{$thistable}{$newid}' style='width:250px'/></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>ģ���ֶΣ�</td>\r\n";
    $msg.=" <td><font color='red'>ģ���ֶ�Ĭ���Ѿ�����,���ڱ༭��ǰģ���в鿴</font></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>��ģ��������</td>\r\n";
    $msg.=" <td><label>\r\n";
    $msg.=" <textarea name=\"description\" id=\"description\" cols=\"45\" rows=\"5\" onClick=\"this.value=''\">��ģ������</textarea>\r\n";
    $msg.=" </label></td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="<tr>\r\n";
    $msg.=" <td height='24' align='center'>ģ��״̬��</td>\r\n";
    $msg.=" <td><input name='state' type='radio' id='copytemplet' value='1' class='np' checked='checked'/>\r\n";
    $msg.=" ����\r\n";
    $msg.=" &nbsp;\r\n";
    $msg.=" <input name='state' type='radio' id='copytemplet' class='np' value='0'/>\r\n";
    $msg.=" ����</td>\r\n";
    $msg.="</tr>\r\n";
    $msg.="</table>";
    $win->AddMsgItem("<div style='padding:20px;line-height:300%'>$msg</div>");
		$winform = $win->GetWindow("ok");
		$win->Display();
		exit();
	}
	//����
	else if($job=="yes")
	{
  	if(ereg("[^0-9-]",$newid)||empty($newid))
  	{
  		ShowMsg("<font color=red>'��Աģ��ID'</font>����Ϊ���֣�","-1");
  		exit();
  	}
		if($newtable=="")
		{
			ShowMsg("��������Ϊ�գ�","-1");
			exit();
		}
		$state = isset($state) && is_numeric($state) ? $state : 0;
		$newname = htmlspecialchars($newname);
		$row = $dsql->GetOne("Select * from #@__member_model where id='$newid' Or `table` like '$newtable' Or name like '$newname' ");
		if(is_array($row))
		{
			ShowMsg("���ܻ�Աģ�͵ġ�ID���������ơ������ݿ����Ѵ��ڣ������ظ�ʹ�ã�","-1");
			exit();
		}
		//�������ݱ�
		if(!$dsql->IsTable($newtable))
		{
			$dsql->Execute('me',"SHOW CREATE TABLE {$dsql->dbName}.{$thistable}");
			$row = $dsql->GetArray('me', MYSQL_BOTH);
			$tableStruct = $row[1];
			$tb = str_replace('#@__',$cfg_dbprefix,$thistable);
			$tableStruct = preg_replace("/CREATE TABLE `$thistable`/iU","CREATE TABLE `$newtable`",$tableStruct);
			$dsql->ExecuteNoneQuery($tableStruct);
		}
		$query = "insert into #@__member_model (`id`, `name`, `table`, `description`, `issystem`, `state`, `info`) values ('$newid', '$newname', '$newtable', '$description', 0, '$state','$thisinfo')";
		$rs = $dsql->ExecuteNoneQuery($query);
		if($rs)
		{
			UpDateMemberModCache();
			ShowMsg("�ɹ����ƻ�Աģ�ͣ���ת����ϸ����ҳ... ","member_model_edit.php?id={$newid}&dopost=edit");
			exit();
		}
		else
		{
			$errv = $dsql->GetError();
			ShowMsg("ϵͳ������Ѵ�����뷢�͵��ٷ���̳���Լ��ԭ��<br /> ������룺member_model_edit.php?dopost=copy $errv","javascript:;");
			exit();
		}
  	//echo "Do it!";exit();
	}
}
/*----------------
function __Delete()
-----------------*/
else if($dopost=="delete")
{
	@set_time_limit(0);
	CheckPurview('c_Del');
	$row = $dsql->GetOne("Select * From #@__member_model where id='$id'");
  if($row['issystem'] == 1)
  {
		ShowMsg("ϵͳģ��,��ֹɾ��!","-1");
		exit();
  }
	if(empty($job))
	{
		$job="";
	}

	//ȷ����ʾ
	if($job=="")
	{
		$wintitle = "��Աģ�͹���-ɾ����Աģ��";
		$wecome_info = "<a href='member_model_main.php'>��Աģ�͹���</a>::ɾ����Աģ��";
		$win = new OxWindow();
		$win->Init("member_model_edit.php","js/blank.js","POST");
		$win->AddHidden("job","yes");
		$win->AddHidden("dopost",$dopost);
		$win->AddHidden("id",$id);
		$win->AddTitle("����ɾ��������û�Աģ����ص��ļ�������<br />��ȷʵҪɾ�� \"".$row['name']."\" �����Աģ�ͣ�");
		$winform = $win->GetWindow("ok");
		$win->Display();
		exit();
	}

	//����
	else if($job=="yes")
	{
		$row = $dsql->GetOne("Select `table` From `#@__member_model` where id='$id'",MYSQL_ASSOC);
		if(!is_array($row))
		{
			ShowMsg("����ָ���Ļ�Աģ����Ϣ������!","-1");
			exit();
		}

		//ɾ����
		$dsql->ExecuteNoneQuery("DROP TABLE IF EXISTS `{$row['table']}`;");

		//ɾ��Ƶ��������Ϣ
		$dsql->ExecuteNoneQuery("Delete From `#@__member_model` where id='$id'");
		UpDateMemberModCache();
		ShowMsg("�ɹ�ɾ��һ����Աģ�ͣ�","member_model_main.php");
		exit();
	}
}

/*----------------
function edit()
-----------------*/
$row = $dsql->GetOne("Select * From #@__member_model where id='$id'");
include DEDEADMIN."/templets/member_model_edit.htm";

?>