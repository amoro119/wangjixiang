<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/oxwindow.class.php");
if(empty($dopost))
{
	$dopost = '';
}
if(empty($fmdo))
{
	$fmdo = '';
}
$ENV_GOBACK_URL = isset($_COOKIE['ENV_GOBACK_URL']) ? 'member_main.php' : '';

/*----------------
function __DelMember()
ɾ����Ա
----------------*/
if($dopost=="delmember")
{
	CheckPurview('member_Del');
	if($fmdo=='yes')
	{
		$id = ereg_replace('[^0-9]','',$id);
		$safecodeok = substr(md5($cfg_cookie_encode.$randcode),0,24);
		if($safecodeok!=$safecode)
		{
			ShowMsg("����д��ȷ�İ�ȫ��֤����","member_do.php?id={$id}&dopost=delmember");
			exit();
		}
		if(!empty($id))
		{
			//ɾ���û���Ϣ
			$row = $dsql->GetOne("Select * From `#@__member` where mid='$id' limit 1 ");
			$rs = 0;
			if($row['matt'] == 10)
			{
				$nrow = $dsql->GetOne("Select * From `#@__admin` where id='$id' limit 1 ");
				//�Ѿ�ɾ�������Ĺ���Ա�ʺ�
				if(!is_array($nrow)) $rs = $dsql->ExecuteNoneQuery2("Delete From `#@__member` where mid='$id' limit 1");
			}
			else
			{
				$rs = $dsql->ExecuteNoneQuery2("Delete From `#@__member` where mid='$id' limit 1");
			}
			if($rs > 0)
			{
				$dsql->ExecuteNoneQuery("Delete From `#@__member_tj` where mid='$id' limit 1");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_space` where mid='$id' limit 1");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_company` where mid='$id' limit 1");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_person` where mid='$id' limit 1");

				//ɾ���û��������
				$dsql->ExecuteNoneQuery("Delete From `#@__member_stow` where mid='$id' ");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_flink` where mid='$id' ");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_guestbook` where mid='$id' ");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_operation` where mid='$id' ");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_pms` where toid='$id' Or fromid='$id' ");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_friends` where mid='$id' Or fid='$id' ");
				$dsql->ExecuteNoneQuery("Delete From `#@__member_vhistory` where mid='$id' Or vid='$id' ");
				$dsql->ExecuteNoneQuery("Delete From `#@__feedback` where mid='$id' ");
				$dsql->ExecuteNoneQuery("update `#@__archives` set mid='0' where mid='$id'");
				#api{{
				if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')	{
	        $infofromuc=uc_get_user($row['userid']);
          uc_user_delete($infofromuc[0]);
        }
				#/aip}}
			}
			else
			{
				ShowMsg("�޷�ɾ���˻�Ա����������Ա��<b>[����Ա]</b>��<br />������ɾ�����<b>[����Ա]</b>����ɾ�����ʺţ�", $ENV_GOBACK_URL, 0, 5000);
				exit();
			}
		}
		ShowMsg("�ɹ�ɾ��һ����Ա��",$ENV_GOBACK_URL);
		exit();
	}
	$randcode = mt_rand(10000,99999);
	$safecode = substr(md5($cfg_cookie_encode.$randcode),0,24);
	$wintitle = "��Ա����-ɾ����Ա";
	$wecome_info = "<a href='".$ENV_GOBACK_URL."'>��Ա����</a>::ɾ����Ա";
	$win = new OxWindow();
	$win->Init("member_do.php","js/blank.js","POST");
	$win->AddHidden("fmdo","yes");
	$win->AddHidden("dopost",$dopost);
	$win->AddHidden("id",$id);
	$win->AddHidden("randcode",$randcode);
	$win->AddHidden("safecode",$safecode);
	$win->AddTitle("��ȷʵҪɾ��(ID:".$id.")�����Ա?");
	$win->AddMsgItem("��ȫ��֤����<input name='safecode' type='text' id='safecode' size='16' style='width:200px' />&nbsp;(���Ʊ����룺 <font color='red'>$safecode</font> )","30");
	$winform = $win->GetWindow("ok");
	$win->Display();
}else if($dopost=="delmembers"){
    CheckPurview('member_Del');
    if($fmdo=='yes')
    {
        $safecodeok = substr(md5($cfg_cookie_encode.$randcode),0,24);
        if($safecodeok!=$safecode)
        {
            ShowMsg("����д��ȷ�İ�ȫ��֤����","member_do.php?id={$id}&dopost=delmembers");
            exit();
        }
        if(!empty($id))
        {
            //ɾ���û���Ϣ
            
            $rs = $dsql->ExecuteNoneQuery2("Delete From `#@__member` where mid in (".str_replace("`",",",$id).") And matt<>10 ");    
            if($rs > 0)
            {
                $dsql->ExecuteNoneQuery("Delete From `#@__member_tj` where mid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_space` where mid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_company` where mid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_person` where mid in (".str_replace("`",",",$id).") ");

                //ɾ���û��������
                $dsql->ExecuteNoneQuery("Delete From `#@__member_stow` where mid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_flink` where mid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_guestbook` where mid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_operation` where mid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_pms` where toid in (".str_replace("`",",",$id).") Or fromid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_friends` where mid in (".str_replace("`",",",$id).") Or fid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__member_vhistory` where mid in (".str_replace("`",",",$id).") Or vid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("Delete From `#@__feedback` where mid in (".str_replace("`",",",$id).") ");
                $dsql->ExecuteNoneQuery("update `#@__archives` set mid='0' where mid in (".str_replace("`",",",$id).")");
            }
            else
            {
                ShowMsg("�޷�ɾ���˻�Ա����������Ա�ǹ���Ա������ID��<br />������ɾ���������Ա����ɾ�����ʺţ�",$ENV_GOBACK_URL,0,3000);
                exit();
            }
        }
        ShowMsg("�ɹ�ɾ����Щ��Ա��",$ENV_GOBACK_URL);
        exit();
    }
    $randcode = mt_rand(10000,99999);
    $safecode = substr(md5($cfg_cookie_encode.$randcode),0,24);
    $wintitle = "��Ա����-ɾ����Ա";
    $wecome_info = "<a href='".$ENV_GOBACK_URL."'>��Ա����</a>::ɾ����Ա";
    $win = new OxWindow();
    $win->Init("member_do.php","js/blank.js","POST");
    $win->AddHidden("fmdo","yes");
    $win->AddHidden("dopost",$dopost);
    $win->AddHidden("id",$id);
    $win->AddHidden("randcode",$randcode);
    $win->AddHidden("safecode",$safecode);
    $win->AddTitle("��ȷʵҪɾ��(ID:".$id.")�����Ա?");
    $win->AddMsgItem(" ��ȫ��֤����<input name='safecode' type='text' id='safecode' size='16' style='width:200px' /> (���Ʊ����룺 <font color='red'>$safecode</font>)","30");
    $winform = $win->GetWindow("ok");
    $win->Display();
}
/*----------------
function __Recommend()
�Ƽ���Ա
----------------*/
else if($dopost=="recommend")
{
	CheckPurview('member_Edit');
	$id = ereg_replace("[^0-9]","",$id);
	if($matt==0)
	{
		$dsql->ExecuteNoneQuery("update `#@__member` set matt=1 where mid='$id' And matt<>10 limit 1");
		ShowMsg("�ɹ�����һ����Ա�Ƽ���",$ENV_GOBACK_URL);
		exit();
	}
	else
	{
		$dsql->ExecuteNoneQuery("update `#@__member` set matt=0 where mid='$id' And matt<>10 limit 1");
		ShowMsg("�ɹ�ȡ��һ����Ա�Ƽ���",$ENV_GOBACK_URL);
		exit();
	}
}
/*----------------
function __EditUser()
���Ļ�Ա
----------------*/
else if($dopost=='edituser')
{
	CheckPurview('member_Edit');
	if(!isset($_POST['id'])) exit('Request Error!');
	$pwdsql = empty($pwd) ? '' : ",pwd='".md5($pwd)."'";
	if(empty($sex)) $sex = '��';
	$uptime=GetMkTime($uptime);
	
	if($matt==10 && $oldmatt!=10)
	{
		ShowMsg("�Բ���Ϊ��ȫ�������֧��ֱ�Ӱ�ǰ̨��ԱתΪ����Ĳ�����", "-1");
		exit();
	}	
	$query = "update `#@__member` set
			email = '$email',
			uname = '$uname',
			sex = '$sex',
			matt = '$matt',
			money = '$money',
			scores = '$scores',
			rank = '$rank',
			spacesta='$spacesta',
			uptime='$uptime',
			exptime='$exptime'
			$pwdsql
			where mid='$id' And matt<>10 ";
	$rs = $dsql->ExecuteNoneQuery2($query);
	if($rs==0)
	{
		$query = "update `#@__member` set
			email = '$email',
			uname = '$uname',
			sex = '$sex',
			money = '$money',
			scores = '$scores',
			rank = '$rank',
			spacesta='$spacesta',
			uptime='$uptime',
			exptime='$exptime'
			$pwdsql
			where mid='$id' ";
			$rs = $dsql->ExecuteNoneQuery2($query);
	}
	
	#api{{
	if(defined('UC_API') && @include_once DEDEROOT.'/api/uc.func.php')
	{
		$row = $dsql->GetOne("SELECT `scores`,`userid` FROM `#@__member` WHERE `mid`='$id' AND `matt`<>10");
		$amount = $scores-$row['scores'];
		uc_credit_note($row['userid'],$amount);
	}
	#/aip}}
	
	ShowMsg('�ɹ����Ļ�Ա���ϣ�', 'member_view.php?id='.$id);
	exit();
}
/*--------------
function __LoginCP()
��¼��Ա�Ŀ������
----------*/
else if($dopost=="memberlogin")
{
	CheckPurview('member_Edit');
	PutCookie('DedeUserID',$id,1800);
	PutCookie('DedeLoginTime',time(),1800);
	if(empty($jumpurl)) header("location:../member/index.php");
	else header("location:$jumpurl");
}
elseif($dopost == "deoperations")
{
	$nid = ereg_replace('[^0-9,]','',ereg_replace('`',',',$nid));
	$nid = explode(',',$nid);
	if(is_array($nid))
	{
		foreach ($nid as $var)
		{
			$query = "DELETE FROM `#@__member_operation` WHERE aid = '$var'";
			$dsql->ExecuteNoneQuery($query);
		}
		ShowMsg("ɾ���ɹ���","member_operations.php");
		exit();
	}
}
elseif($dopost == "upoperations")
{
	$nid = ereg_replace('[^0-9,]','',ereg_replace('`',',',$nid));
	$nid = explode(',',$nid);
	if(is_array($nid))
	{
		foreach ($nid as $var)
		{
			$query = "update `#@__member_operation` set sta = '1' where aid = '$var'";
			$dsql->ExecuteNoneQuery($query);
			ShowMsg("���óɹ���","member_operations.php");
			exit();
		}
	}
}
elseif($dopost == "okoperations")
{
	$nid = ereg_replace('[^0-9,]','',ereg_replace('`',',',$nid));
	$nid = explode(',',$nid);
	if(is_array($nid))
	{
		foreach ($nid as $var)
		{
			$query = "update `#@__member_operation` set sta = '2' where aid = '$var'";
			$dsql->ExecuteNoneQuery($query);
			ShowMsg("���óɹ���","member_operations.php");
			exit();
		}
	}
}
?>