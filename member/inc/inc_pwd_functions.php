<?php
if(!defined('DEDEMEMBER'))
{
	exit("dedecms");
}

//��֤�����ɺ���
function random($length, $numeric = 0)
{
	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
	if($numeric)
	{
		$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
	}
	else
	{
		$hash = '';
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
		$max = strlen($chars) - 1;
		for($i = 0; $i < $length; $i++)
		{
			$hash .= $chars[mt_rand(0, $max)];
		}
	}
	return $hash;
}

//�ʼ����ͺ���
function sendmail($email, $mailtitle, $mailbody, $headers)
{
	global $cfg_sendmail_bysmtp, $cfg_smtp_server, $cfg_smtp_port, $cfg_smtp_usermail, $cfg_smtp_user, $cfg_smtp_password, $cfg_adminemail;
	if($cfg_sendmail_bysmtp == 'Y')
	{
		$mailtype = 'TXT';
		require_once(DEDEINC.'/mail.class.php');
		$smtp = new smtp($cfg_smtp_server,$cfg_smtp_port,true,$cfg_smtp_usermail,$cfg_smtp_password);
		$smtp->debug = false;
		$smtp->sendmail($email,$cfg_webname,$cfg_smtp_usermail, $mailtitle, $mailbody, $mailtype);
	}
	else
	{
		@mail($email, $mailtitle, $mailbody, $headers);
	}
}

//�����ʼ���typeΪINSERT�½���֤�룬UPDATE�޸���֤�룻
function newmail($mid,$userid,$mailto,$type,$send)
{
	global $db,$cfg_adminemail,$cfg_webname,$cfg_basehost,$cfg_memberurl;
	$mailtime = time();
	$randval = random(8);
	$mailtitle = $cfg_webname.":�����޸�";
	$mailto = $mailto;
	$headers = "From: ".$cfg_adminemail."\r\nReply-To: $cfg_adminemail";
	$mailbody = "�װ���".$userid."��\r\n���ã���л��ʹ��".$cfg_webname."����\r\n".$cfg_webname."Ӧ����Ҫ�������������룺��ע�������û��������룬����������Ϣ�Ƿ�й©����\r\n������ʱ��½����Ϊ��".$randval." ���������ڵ�½������ַȷ���޸ġ�\r\n".$cfg_basehost.$cfg_memberurl."/resetpassword.php?dopost=getpasswd&id=".$mid;
	if($type == 'INSERT')
	{
		$key = md5($randval);
		$sql = "INSERT INTO `#@__pwd_tmp` (`mid` ,`membername` ,`pwd` ,`mailtime`)VALUES ('$mid', '$userid',  '$key', '$mailtime');";
		if($db->ExecuteNoneQuery($sql))
		{
			if($send == 'Y')
			{
				sendmail($mailto,$mailtitle,$mailbody,$headers);
				return showmsg('EMAIL�޸���֤���Ѿ����͵�ԭ�������������', 'login.php','','5000');
			}
			elseif($send == 'N')
			{
				return showmsg('�Ժ���ת���޸�ҳ', $cfg_basehost.$cfg_memberurl."/resetpassword.php?dopost=getpasswd&amp;id=".$mid."&amp;key=".$randval);
			}
		}
		else
		{
			return showmsg('�Բ����޸�ʧ�ܣ�����ϵ����Ա', 'login.php');
		}
	}
	elseif($type == 'UPDATE')
	{
		$key = md5($randval);
		$sql = "UPDATE `#@__pwd_tmp` SET `pwd` = '$key',mailtime = '$mailtime'  WHERE `mid` ='$mid';";
		if($db->ExecuteNoneQuery($sql))
		{
			if($send == 'Y')
			{
				sendmail($mailto,$mailtitle,$mailbody,$headers);
				showmsg('EMAIL�޸���֤���Ѿ����͵�ԭ�������������', 'login.php');
			}
			elseif($send == 'N')
			{
				return showmsg('�Ժ���ת���޸�ҳ', $cfg_basehost.$cfg_memberurl."/resetpassword.php?dopost=getpasswd&amp;id=".$mid."&amp;key=".$randval);
			}
		}
		else
		{
			showmsg('�Բ����޸�ʧ�ܣ��������Ա��ϵ', 'login.php');
		}
	}
}

//��ѯ��Ա��Ϣmail�û����������ַ��userid�û���
function member($mail,$userid)
{
	global $db;
	$sql = "Select mid,email,safequestion From #@__member where email='$mail' AND userid = '$userid'";
	$row = $db->GetOne($sql);
	if(!is_array($row))
	{
		return ShowMsg("�Բ����û�ID�������","-1");
	}
	else
	{
		return $row;
	}
}

//��ѯ�Ƿ��͹���֤��,midΪ��ԱID��useridΪ��Ա���ƣ�mailto�����ʼ���ַ��sendΪY�����ʼ���ΪN�������ʼ�Ĭ��ΪY
function sn($mid,$userid,$mailto, $send = 'Y')
{
	global $db;
	$tptim= (60*10);
	$dtime = time();
	$sql = "Select * From #@__pwd_tmp where mid = '$mid'";
	$row = $db->GetOne($sql);
	if(!is_array($row))
	{

		//�������ʼ���
		newmail($mid,$userid,$mailto,'INSERT',$send);
	}

	//10���Ӻ�����ٴη�������֤�룻
	elseif($dtime - $tptim > $row['mailtime'])
	{
		newmail($mid,$userid,$mailto,'UPDATE',$send);
	}

	//���·����µ���֤��ȷ���ʼ���
	else
	{
		return showmsg('�Բ�����10���Ӻ�����������', 'login.php');
	}
}
?>