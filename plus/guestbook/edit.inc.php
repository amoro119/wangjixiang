<?php
if(!defined('DEDEINC')) exit('Request Error!');

if(!empty($_COOKIE['GUEST_BOOK_POS'])) $GUEST_BOOK_POS = $_COOKIE['GUEST_BOOK_POS'];
else $GUEST_BOOK_POS = "guestbook.php";

$id = intval($id);
if(empty($job)) $job='view';

if($job=='del' && $g_isadmin)
{
	$dsql->ExecuteNoneQuery(" Delete From `#@__guestbook` where id='$id' ");
	ShowMsg("�ɹ�ɾ��һ�����ԣ�",$GUEST_BOOK_POS);
	exit();
}
else if($job=='check' && $g_isadmin)
{
	$dsql->ExecuteNoneQuery(" update `#@__guestbook` set ischeck=1 where id='$id' ");
	ShowMsg("�ɹ����һ�����ԣ�",$GUEST_BOOK_POS);
	exit();
}
else if($job=='editok')
{
	$remsg = trim($remsg);
	if($remsg!='')
	{
		//����Ա�ظ�������HTML
		if($g_isadmin)
		{
			$msg = "<div class=\\'rebox\\'>".$msg."</div>\n".$remsg; 
			//$remsg <br><font color=red>����Ա�ظ���</font>
		}
		else
		{
				$row = $dsql->GetOne("Select msg From `#@__guestbook` where id='$id' ");
				$oldmsg = "<div class=\\'rebox\\'>".addslashes($row['msg'])."</div>\n";
				$remsg = trimMsg(cn_substrR($remsg, 1024), 1);
				$msg = $oldmsg.$remsg;
		}
	}
	$dsql->ExecuteNoneQuery("update `#@__guestbook` set `msg`='$msg', `posttime`='".time()."' where id='$id' ");
	ShowMsg("�ɹ����Ļ�ظ�һ�����ԣ�",$GUEST_BOOK_POS);
	exit();
}

if($g_isadmin)
{
	$row = $dsql->GetOne("select * from `#@__guestbook` where id='$id'");
	require_once(DEDETEMPLATE.'/plus/guestbook-admin.htm');
}
else
{
	$row = $dsql->GetOne("select id,title from `#@__guestbook` where id='$id'");
	require_once(DEDETEMPLATE.'/plus/guestbook-user.htm');
}
?>