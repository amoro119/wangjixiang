<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_Pm');
//����û����ĺϷ���
function CheckUserID($uid,$msgtitle='�û���',$ckhas=true)
{
	global $cfg_mb_notallow,$cfg_mb_idmin,$cfg_md_idurl,$cfg_soft_lang,$dsql;
	if($cfg_mb_notallow != '')
	{
		$nas = explode(',',$cfg_mb_notallow);
		if(in_array($uid,$nas))
		{
			return $msgtitle.'Ϊϵͳ��ֹ�ı�ʶ��';
		}
	}
	if($cfg_md_idurl=='Y' && eregi("[^a-z0-9]",$uid))
	{
		return $msgtitle.'������Ӣ����ĸ��������ɣ�';
	}

	if($cfg_soft_lang=='utf-8')
	{
		$ck_uid = utf82gb($uid);
	}
	else
	{
		$ck_uid = $uid;
	}

	for($i=0;isset($ck_uid[$i]);$i++)
	{
			if(ord($ck_uid[$i]) > 0x80)
			{
				if(isset($ck_uid[$i+1]) && ord($ck_uid[$i+1])>0x40)
				{
					$i++;
				}
				else
				{
					return $msgtitle.'���ܺ������룬���������Ӣ����ĸ��������ϣ�';
				}
			}
			else
			{
				if(eregi("[^0-9a-z@\.-]",$ck_uid[$i]))
				{
					return $msgtitle.'���ܺ��� [@]��[.]��[-]�����������ţ�';
				}
			}
	}
	if($ckhas)
	{
		$row = $dsql->GetOne("Select * From `#@__member` where userid like '$uid' ");
		if(is_array($row)) return $msgtitle."�Ѿ����ڣ�";
	}
	return 'ok';
}

if(!isset($action))
{
	$action = '';
}
if($action=="post")
{
	$floginid = $cuserLogin->getUserName();
	$fromid = $cuserLogin->getUserID();
	if($subject=='')
	{
		ShowMsg("����д��Ϣ����!","-1");
		exit();
	}
	$msg = CheckUserID($msgtoid,"�û���",false);
	if($msg!='ok')
	{
		ShowMsg($msg,"-1");
		exit();
	}
	$row = $dsql->GetOne("Select * From `#@__member` where userid like '$msgtoid' ");
	if(!is_array($row))
	{
		ShowMsg("��ָ�����û�������,���ܷ�����Ϣ!","-1");
		exit();
	}
	$subject = cn_substrR(HtmlReplace($subject,1),60);
	$message = cn_substrR(HtmlReplace($message,0),1024);
	$sendtime = $writetime = time();

	//�����ռ���(�ռ��˿ɹ���)
	$inquery = "INSERT INTO `#@__member_pms` (`floginid`,`fromid`,`toid`,`tologinid`,`folder`,`subject`,`sendtime`,`writetime`,`hasview`,`isadmin`,`message`)
      VALUES ('$floginid','$fromid','{$row['mid']}','{$row['userid']}','inbox','$subject','$sendtime','$writetime','0','0','$message'); ";
      
	$dsql->ExecuteNoneQuery($inquery);
	ShowMsg('�����ѳɹ�����','member_pmone.php');
	exit();	
	
}
require_once(DEDEADMIN."/templets/member_pmone.htm");

?>