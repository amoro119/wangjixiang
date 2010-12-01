<?php
require_once(dirname(__FILE__)."/config.php");
require_once DEDEINC.'/membermodel.cls.php';
if($cfg_mb_allowreg=='N')
{
	ShowMsg('ϵͳ�ر������û�ע�ᣡ', 'index.php');
	exit();
}


if(!isset($dopost))
{
	$dopost = '';
}
$step = empty($step)? 1 : intval(preg_replace("/[^\d]/",'', $step));

if($step == 1)
{
	if($cfg_ml->IsLogin())
	{
		if($cfg_mb_reginfo == 'Y')
		{
			//�������ע����ϸ��Ϣ
			if($cfg_ml->fields['spacesta'] == 0 || $cfg_ml->fields['spacesta'] == 1)
			{
				 ShowMsg("��δ�����ϸ���ϣ�������...","index_do.php?fmdo=user&dopost=regnew&step=2",0,1000);
				 exit;
			}
		}
		ShowMsg('���Ѿ���½ϵͳ����������ע�ᣡ', 'index.php');
		exit();
	}
	if($dopost=='regbase')
	{
		$svali = GetCkVdValue();
		if(preg_match("/1/",$safe_gdopen)){
			if(strtolower($vdcode)!=$svali || $svali=='')
			{
				ResetVdValue();
				ShowMsg('��֤�����', '-1');
				exit();
			}
			
		}
		
		$faqkey = isset($faqkey) && is_numeric($faqkey) ? $faqkey : 0;
		if($safe_faq_reg == '1')
		{
			if($safefaqs[$faqkey]['answer'] != $rsafeanswer || $rsafeanswer=='')
			{
				ShowMsg('��֤����𰸴���', '-1');
				exit();
			}
		}
		
		$userid = trim($userid);
		$pwd = trim($userpwd);
		$pwdc = trim($userpwdok);
		$rs = CheckUserID($userid, '�û���');
		if($rs != 'ok')
		{
			ShowMsg($rs, '-1');
			exit();
		}
		if(strlen($userid) > 20 || strlen($uname) > 36)
		{
			ShowMsg('����û������û�����������������ע�ᣡ', '-1');
			exit();
		}
		if(strlen($userid) < $cfg_mb_idmin || strlen($pwd) < $cfg_mb_pwdmin)
		{
			ShowMsg("����û�����������̣�������ע�ᣡ","-1");
			exit();
		}
		if($pwdc != $pwd)
		{
			ShowMsg('��������������벻һ�£�', '-1');
			exit();
		}
		
		$uname = HtmlReplace($uname, 1);
		//�û������ظ����
		if($cfg_mb_wnameone=='N')
		{
			$row = $dsql->GetOne("Select * From `#@__member` where uname like '$uname' ");
			if(is_array($row))
			{
				ShowMsg('�û�������˾���Ʋ����ظ���', '-1');
				exit();
			}
		}
		if(!CheckEmail($email))
		{
			ShowMsg('Email��ʽ����ȷ��', '-1');
			exit();
		}
		
		#api{{
		if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
		{
			$uid = uc_user_register($userid, $pwd, $email);
			if($uid <= 0)
			{
				if($uid == -1)
				{
					ShowMsg("�û������Ϸ���","-1");
					exit();
				}
				elseif($uid == -2)
				{
					ShowMsg("����Ҫ����ע��Ĵ��","-1");
					exit();
				}
				elseif($uid == -3)
				{
					ShowMsg("��ָ�����û��� {$userid} �Ѵ��ڣ���ʹ�ñ���û�����","-1");
					exit();
				}
				elseif($uid == -5)
				{
					ShowMsg("��ʹ�õ�Email ������ע�ᣡ","-1");
					exit();
				}
				elseif($uid == -6)
				{
					ShowMsg("��ʹ�õ�Email�Ѿ�����һ�ʺ�ע�ᣬ��ʹ�����ʺ�","-1");
					exit();
				}
				else
				{
					ShowMsg("עɾʧ�ģ�","-1");
					exit();
				}
			}
			else
			{
				$ucsynlogin = uc_user_synlogin($uid);
			}
		}
		#/aip}}
		
		if($cfg_md_mailtest=='Y')
		{
			$row = $dsql->GetOne("Select mid From `#@__member` where email like '$email' ");
			if(is_array($row))
			{
				ShowMsg('��ʹ�õ�Email�Ѿ�����һ�ʺ�ע�ᣬ��ʹ�����ʺţ�', '-1');
				exit();
			}
		}
	
		//����û����Ƿ����
		$row = $dsql->GetOne("Select mid From `#@__member` where userid like '$userid' ");
		if(is_array($row))
		{
			ShowMsg("��ָ�����û��� {$userid} �Ѵ��ڣ���ʹ�ñ���û�����", "-1");
			exit();
		}
		if($safequestion==0)
		{
			$safeanswer = '';
		}
		else
		{
			if(strlen($safeanswer)>30)
			{
				ShowMsg('����°�ȫ����Ĵ�̫���ˣ��������30�ֽ����ڣ�', '-1');
				exit();
			}
		}
	
		//��Ա��Ĭ�Ͻ��
		$dfscores = 0;
		$dfmoney = 0;
		$dfrank = $dsql->GetOne("Select money,scores From `#@__arcrank` where rank='10' ");
		if(is_array($dfrank))
		{
			$dfmoney = $dfrank['money'];
			$dfscores = $dfrank['scores'];
		}
		$jointime = time();
		$logintime = time();
		$joinip = GetIP();
		$loginip = GetIP();
		$pwd = md5($userpwd);
		
		$spaceSta = ($cfg_mb_spacesta < 0 ? $cfg_mb_spacesta : 0);
		
		$inQuery = "INSERT INTO `#@__member` (`mtype` ,`userid` ,`pwd` ,`uname` ,`sex` ,`rank` ,`money` ,`email` ,`scores` ,
		`matt`, `spacesta` ,`face`,`safequestion`,`safeanswer` ,`jointime` ,`joinip` ,`logintime` ,`loginip` )
	   VALUES ('$mtype','$userid','$pwd','$uname','$sex','10','$dfmoney','$email','$dfscores',
	   '0','$spaceSta','','$safequestion','$safeanswer','$jointime','$joinip','$logintime','$loginip'); ";
		if($dsql->ExecuteNoneQuery($inQuery))
		{
			$mid = $dsql->GetLastID();
	
			//д��Ĭ�ϻ�Ա��ϸ����
			if($mtype=='����'){
				$space='person';
			}else if($mtype=='��ҵ'){
				$space='company';
			}else{
				$space='person';
			}
	
			//д��Ĭ��ͳ������
			$membertjquery = "INSERT INTO `#@__member_tj` (`mid`,`article`,`album`,`archives`,`homecount`,`pagecount`,`feedback`,`friend`,`stow`)
	               VALUES ('$mid','0','0','0','0','0','0','0','0'); ";
			$dsql->ExecuteNoneQuery($membertjquery);
	
			//д��Ĭ�Ͽռ���������
			$spacequery = "Insert Into `#@__member_space`(`mid` ,`pagesize` ,`matt` ,`spacename` ,`spacelogo` ,`spacestyle`, `sign` ,`spacenews`)
		            Values('{$mid}','10','0','{$uname}�Ŀռ�','','$space','',''); ";
			$dsql->ExecuteNoneQuery($spacequery);
	
			//д������Ĭ������
			$dsql->ExecuteNoneQuery("INSERT INTO `#@__member_flink`(mid,title,url) VALUES('$mid','֯�����ݹ���ϵͳ','http://www.dedecms.com'); ");
			
			$membermodel = new membermodel($mtype);
			$modid=$membermodel->modid;
			$modid = empty($modid)? 0 : intval(preg_replace("/[^\d]/",'', $modid));
			$modelform = $dsql->getOne("select * from #@__member_model where id='$modid' ");
			
			if(!is_array($modelform))
			{
				showmsg('ģ�ͱ�������', '-1');
				exit();
			}else{
				$dsql->ExecuteNoneQuery("INSERT INTO `{$membermodel->table}` (`mid`) VALUES ('{$mid}');");
			}
			
			//----------------------------------------------
			//ģ���¼
			//---------------------------
			$cfg_ml = new MemberLogin(7*3600);
			$rs = $cfg_ml->CheckUser($userid, $userpwd);

			
		//�ʼ���֤
		if($cfg_mb_spacesta==-10)
		{
			$userhash = md5($cfg_cookie_encode.'--'.$mid.'--'.$email);
	  	$url = $cfg_basehost.(empty($cfg_cmspath) ? '/' : $cfg_cmspath)."/member/index_do.php?fmdo=checkMail&mid={$mid}&userhash={$userhash}&do=1";
	  	$url = eregi_replace('http://', '', $url);
	  	$url = 'http://'.eregi_replace('//', '/', $url);
	  	$mailtitle = "{$cfg_webname}--��Ա�ʼ���֤֪ͨ";
	  	$mailbody = '';
	  	$mailbody .= "�𾴵��û�[{$uname}]�����ã�\r\n";
	  	$mailbody .= "��ӭע���Ϊ[{$cfg_webname}]�Ļ�Ա��\r\n";
	  	$mailbody .= "Ҫͨ��ע�ᣬ������������һ�����������������������ӵ���ַ���������ַ��\r\n\r\n";
	  	$mailbody .= "{$url}\r\n\r\n";
	  	$mailbody .= "Power by http://www.dedecms.com ֯�����ݹ���ϵͳ��\r\n";
	  
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
		}//End �ʼ���֤
			
			if($cfg_mb_reginfo == 'Y')
			{
			    ShowMsg("��ɻ�����Ϣ��ע�ᣬ������������ϸ����...","index_do.php?fmdo=user&dopost=regnew&step=2",0,1000);
			    exit();
		  } else {
		  	  require_once(DEDEMEMBER."/templets/reg-new3.htm");
		  	  exit;
		  }
			
		}
		else
		{
			ShowMsg("ע��ʧ�ܣ����������Ƿ�����������Ա��ϵ��", "-1");
			exit();
		}
	}
	
	require_once(DEDEMEMBER."/templets/reg-new.htm");
	
}else {
	if(!$cfg_ml->IsLogin())
	{
		ShowMsg("��δ��ɻ�����Ϣ��ע��,�뷵��������д��", "index_do.php?fmdo=user&dopost=regnew");
		exit;
	} else {
			if($cfg_ml->fields['spacesta'] == 2)
			{
				 ShowMsg('���Ѿ���½ϵͳ����������ע�ᣡ', 'index.php');
				 exit;
			}
	}
	$membermodel = new membermodel($cfg_ml->M_MbType);
	$postform = $membermodel->getForm(true);
	if($dopost == 'reginfo')
	{
		//���������ϸ������д
		$dede_fields = empty($dede_fields) ? '' : trim($dede_fields);
		$dede_fieldshash = empty($dede_fieldshash) ? '' : trim($dede_fieldshash);
		$modid = empty($modid)? 0 : intval(preg_replace("/[^\d]/",'', $modid));
		
		if(!empty($dede_fields))
		{
			if($dede_fieldshash != md5($dede_fields.$cfg_cookie_encode))
			{
				showMsg('����У�鲻�ԣ����򷵻�', '-1');
				exit();
			}
		}
		$modelform = $dsql->getOne("select * from #@__member_model where id='$modid' ");
		if(!is_array($modelform))
		{
			showmsg('ģ�ͱ�������', '-1');
			exit();
		}
		
		$inadd_f = '';
		if(!empty($dede_fields))
		{
			$fieldarr = explode(';', $dede_fields);
			if(is_array($fieldarr))
			{
				foreach($fieldarr as $field)
				{
					if($field == '') continue;
					$fieldinfo = explode(',', $field);
					if($fieldinfo[1] == 'textdata')
					{
						${$fieldinfo[0]} = FilterSearch(stripslashes(${$fieldinfo[0]}));
						${$fieldinfo[0]} = addslashes(${$fieldinfo[0]});
					}
					else
					{
						if(empty(${$fieldinfo[0]})) ${$fieldinfo[0]} = '';
						${$fieldinfo[0]} = GetFieldValue(${$fieldinfo[0]}, $fieldinfo[1],0,'add','','diy', $fieldinfo[0]);
					}
					if($fieldinfo[0]=="birthday") ${$fieldinfo[0]}=GetDateMk(${$fieldinfo[0]});
					$inadd_f .= ','.$fieldinfo[0]." ='".${$fieldinfo[0]}."' ";
				}
			}

		}
  
	  $query = "UPDATE `{$membermodel->table}` SET `mid`='{$cfg_ml->M_ID}' $inadd_f WHERE `mid`='{$cfg_ml->M_ID}'; ";
	  if($dsql->executenonequery($query)){
  	  $dsql->ExecuteNoneQuery("UPDATE `#@__member` SET `spacesta`='2' WHERE `mid`='{$cfg_ml->M_ID}'");
  	  require_once(DEDEMEMBER."/templets/reg-new3.htm");
  	  exit;
  	}
	}

	require_once(DEDEMEMBER."/templets/reg-new2.htm");
}



?>