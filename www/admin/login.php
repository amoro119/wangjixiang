<?php
require_once(dirname(__FILE__).'/../include/common.inc.php');
require_once(DEDEINC.'/userlogin.class.php');
if(empty($dopost))
{
	$dopost = '';
}

//��ⰲװĿ¼��ȫ��
if( is_dir(dirname(__FILE__).'/../install') )
{
	if(!file_exists(dirname(__FILE__).'/../install/install_lock.txt') )
	{
  	$fp = fopen(dirname(__FILE__).'/../install/install_lock.txt', 'w') or die('��װĿ¼��д��Ȩ�ޣ��޷�����д�������ļ����밲װ���ɾ����װĿ¼��');
  	fwrite($fp,'ok');
  	fclose($fp);
	}
	//Ϊ�˷�ֹδ֪��ȫ�����⣬ǿ�ƽ��ð�װ������ļ�
	if( file_exists("../install/index.php") ) {
		@rename("../install/index.php", "../install/index.php.bak");
	}
	if( file_exists("../install/module-install.php") ) {
		@rename("../install/module-install.php", "../install/module-install.php.bak");
	}
}
//����̨Ŀ¼�Ƿ����
$cururl = GetCurUrl();
if(preg_match('/dede\/login/i',$cururl))
{
	$redmsg = '<br />&nbsp;&nbsp;&nbsp;&nbsp;<font color=\'red\'>���Ĺ���Ŀ¼�������а���Ĭ������dede��������FTP������޸�Ϊ�������ƣ����������ȫ��</b></font>';
}
else
{
	$redmsg = '';
}

//��¼���
$admindirs = explode('/',str_replace("\\",'/',dirname(__FILE__)));
$admindir = $admindirs[count($admindirs)-1];
if($dopost=='login')
{
	$validate = empty($validate) ? '' : strtolower(trim($validate));
	$svali = strtolower(GetCkVdValue());
	if(($validate=='' || $validate != $svali) && preg_match("/6/",$safe_gdopen)){
		ResetVdValue();
		ShowMsg('��֤�벻��ȷ!','');
	} else {
		$cuserLogin = new userLogin($admindir);
		if(!empty($userid) && !empty($pwd))
		{
			$res = $cuserLogin->checkUser($userid,$pwd);

			//success
			if($res==1)
			{
				$cuserLogin->keepUser();
				if(!empty($gotopage))
				{
					ShowMsg('�ɹ���¼������ת����������ҳ��',$gotopage);
					exit();
				}
				else
				{
					ShowMsg('�ɹ���¼������ת����������ҳ��',"index.php");
					exit();
				}
			}

			//error
			else if($res==-1)
			{
				ShowMsg('����û���������!','');
			}
			else
			{
				ShowMsg('����������!','');
			}
		}

		//password empty
		else
		{
			ShowMsg('�û�������û��д����!','');
		}
	}
}
include('templets/login.htm');

?>