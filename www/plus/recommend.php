<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/channelunit.class.php");
if(!isset($action)) $action = '';

if(isset($arcID)) $aid = $arcID;
$arcID = $aid = (isset($aid) && is_numeric($aid) ? $aid : 0);
$type = (!isset($type) ? "" : $type);

if(empty($aid)) {
	  ShowMsg("�ĵ�ID����Ϊ��!","-1");
	  exit();
}

//��ȡ�ĵ���Ϣ
if($action=='')
{
	if($type=='sys'){
  //��ȡ�ĵ���Ϣ
	  $arcRow = GetOneArchive($aid);
	  if($arcRow['aid']=='') {
		   ShowMsg("�޷���δ֪�ĵ��Ƽ�������!","-1");
		   exit();
	  }
	  extract($arcRow, EXTR_SKIP);
	}else{
		$arcRow=$dsql->GetOne("SELECT s.*,t.* FROM `#@__member_stow` AS s LEFT JOIN `#@__member_stowtype` AS t ON s.type=t.stowname WHERE s.aid='$aid' AND s.type='$type'");
		if(!is_array($arcRow)){
			 ShowMsg("�޷���δ֪�ĵ��Ƽ�������!","-1");
		   exit();
		}
    $arcRow['arcurl']=$arcRow['indexurl']."=".$arcRow['aid'];
    extract($arcRow, EXTR_SKIP);
	}
}
//�����Ƽ���Ϣ
//-----------------------------------
else if($action=='send')
{
	if(!CheckEmail($email))
  {
	  echo "<script>alert('Email��ʽ����ȷ!');history.go(-1);</script>";
	  exit();
  }
  $mailbody = '';
  $msg = htmlspecialchars($msg);
  $mailtitle = "��ĺ��Ѹ����Ƽ���һƪ����";
  $mailbody .= "$msg \r\n\r\n";
  $mailbody .= "Power by http://www.dedecms.com ֯�����ݹ���ϵͳ��";

	$headers = "From: ".$cfg_adminemail."\r\nReply-To: ".$cfg_adminemail;
	
	if($cfg_sendmail_bysmtp == 'Y' && !empty($cfg_smtp_server))
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

  ShowMsg("�ɹ��Ƽ�һƪ����!",$arcurl);
  exit();
}

//��ʾģ��(��PHP�ļ�)
include(DEDETEMPLATE.'/plus/recommend.htm');

?>