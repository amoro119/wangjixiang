<?php
require_once(dirname(__FILE__).'/guestbook/guestbook.inc.php');
require_once(DEDEINC.'/datalistcp.class.php');
if(empty($action)) $action = '';
//�޸�����
if($action=='admin')
{

	include_once(dirname(__FILE__).'/guestbook/edit.inc.php');

	exit();
}
//��������
else if($action=='save')
{
	if(!empty($_COOKIE['GUEST_BOOK_POS'])) $GUEST_BOOK_POS = $_COOKIE['GUEST_BOOK_POS'];
	else $GUEST_BOOK_POS = 'guestbook.php';
	if(empty($validate)) $validate=='';
	else $validate = strtolower($validate);
	$svali = GetCkVdValue();
	if($validate=='' || $validate!=$svali)
	{
	 	ShowMsg("��֤�벻��ȷ!","");
	 	exit();
	}
	$ip = GetIP();
	$dtime = time();
	$uname = trimMsg($uname);
	$email = trimMsg($email);
	$homepage = trimMsg($homepage);
	$homepage = eregi_replace('http://','',$homepage);
	$qq = trimMsg($qq);
	$msg = trimMsg(cn_substrR($msg, 1024), 1);
	$tid = empty($tid) ? 0 : intval($tid);
	$reid = empty($reid) ? 0 : intval($reid);

	if($msg=='' || $uname=='') {
		showMsg('����������������ݲ���Ϊ��!','-1');
		exit();
	}
	$title = HtmlReplace( cn_substrR($title,60), 1 );
	if($title=='') $title = '�ޱ���';
	
	if($reid != 0)
	{
		$row = $dsql->GetOne("Select msg From `#@__guestbook` where id='$reid' ");
		$msg = "<div class=\\'rebox\\'>".addslashes($row['msg'])."</div>\n".$msg;
	}

	$query = "INSERT INTO `#@__guestbook`(title,tid,mid,uname,email,homepage,qq,face,msg,ip,dtime,ischeck)
                  VALUES ('$title','$tid','{$g_mid}','$uname','$email','$homepage','$qq','$img','$msg','$ip','$dtime','$needCheck'); ";
	$dsql->ExecuteNoneQuery($query);
	$gid = $dsql->GetLastID();
	if($needCheck==1)
	{
		require_once(DEDEINC."/oxwindow.class.php");
		$msg = "
		<font color='red'><b>�ɹ����ͻ�ظ����ԣ�</b></font> &nbsp; <a href='guestbook.php' style='font-size:14px;font-weight:bold'><u>���Ѿ�֪���ˣ�����˷���&gt;&gt;</u></a>";
  	$wintitle = "���Է����ɹ���ʾ";
		$wecome_info = "���Է����ɹ���";
		$win = new OxWindow();
		$win->Init("","js/blank.js","post");
		$win->AddTitle("��ʾ��");
		$win->AddMsgItem("<div style='padding:20px;line-height:300%;font-size:14px'>$msg</div>");
		$winform = $win->GetWindow("hand");
		$win->Display();
	}
	else {
		ShowMsg('�ɹ�����һ�����ԣ�������˺������ʾ��','guestbook.php',0,3000);
	}
	exit();
}
//��ʾ��������
else
{
	setcookie('GUEST_BOOK_POS',GetCurUrl(),time()+3600,'/');

	if($g_isadmin) $sql = 'select * from `#@__guestbook` order by id desc';
	else $sql = 'select * from `#@__guestbook` where ischeck=1 order by id desc';

	$dlist = new DataListCP();
	$dlist->pageSize = 10;
	$dlist->SetParameter('gotopagerank',$gotopagerank);
	$dlist->SetTemplate(DEDETEMPLATE.'/plus/guestbook.htm');
	$dlist->SetSource($sql);
  $dlist->Display();
}
?>