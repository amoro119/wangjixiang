<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'mydede';
$menutype_son = 'mf';
if($cfg_mb_lit=='Y')
{
	ShowMsg("����ϵͳ�����˾�����Ա�ռ䣬����ʵĹ��ܲ����ã�","-1");
	exit();
}
require_once(DEDEINC."/datalistcp.class.php");

if(!isset($ftype))
{
	$ftype = 0;
}
if(!isset($dopost))
{
	$dopost = '';
}

//���ĺ���״̬
if($dopost=='upsta')
{
	$ids = ereg_replace("[^0-9,]","",$ids);
	if($sta=='good')
	{
		$upsta = " ftype=1 ";
	}
	else if($sta=='bad')
	{
		$upsta = " ftype=-1 ";
	}
	else
	{
		$upsta = " ftype=0 ";
	}
	$dsql->ExecuteNoneQuery("Update `#@__member_friends` set $upsta where id in($ids) And mid='{$cfg_ml->M_ID}' ");
	
	#api{{
	if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php' && $sta!='bad')
	{
		if($data = uc_get_user($cfg_ml->M_LoginID)) uc_friend_add($uid, $data[0]);
	}
	#/aip}}
	
	if($sta=='good')
	{
		ShowMsg("�ɹ���ָ��������Ϊ��ע���ѣ�","myfriend.php?ftype=1");
	}
	else if($sta=='bad')
	{
		ShowMsg("�ɹ���ָ�����ѷ����������","myfriend.php?ftype=-1");
	}
	else
	{
		ShowMsg("�ɹ���ָ������תΪ��ͨ���ѣ�","myfriend.php");
	}
	exit();
}

//ɾ������
else if($dopost=='del')
{
	$ids = ereg_replace("[^0-9,]","",$ids);
	#api{{
	if(defined('UC_API') && @include_once DEDEROOT.'/uc_client/client.php')
	{
		if($data = uc_get_user($cfg_ml->M_LoginID))
  		{
			list($uid, $username, $email) = $data;  		
			$friendids = @explode(",", $ids);
			if(!empty($friendids)) uc_friend_delete($uid , $friendids);
		}
	}
	#/aip}}
	$dsql->ExecuteNoneQuery("Delete From `#@__member_friends` where id in($ids) And mid='{$cfg_ml->M_ID}' ");
	ShowMsg("�ɹ�ɾ����ѡ�ĺ��ѣ�","myfriend.php?ftype=".$ftype);
	exit();
}

//���
else{
	$wsql = '';
	if(empty($ftype))
	{
		$wsql = " F.mid='{$cfg_ml->M_ID}' And F.ftype <>  '-1' ";
		$tname = "���к���";
	}
	else if($ftype==1)
	{
		$wsql = " F.mid='{$cfg_ml->M_ID}' And F.ftype =  '1' ";
		$tname = "�ر��ע";
	}
	else if($ftype==-1)
	{
		$wsql = " F.mid='{$cfg_ml->M_ID}' And F.ftype =  '-1' ";
		$tname = "������";
	}
	$query = "Select F.*,G.groupname From `#@__member_group` AS G  LEFT JOIN #@__member_friends AS F ON F.groupid=G.id where $wsql order by F.id desc";
	$dlist = new DataListCP();
	$dlist->pageSize = 20;
	$dlist->SetParameter("ftype",$ftype);
	$dlist->SetTemplate(dirname(__FILE__).'/templets/myfriend.htm');
	$dlist->SetSource($query);
	$dlist->Display();
}

function getUserInfo($uid,$_field = 'uname')
{
	global $dsql;
	$row = $dsql->GetOne("SELECT M.*,YEAR(CURDATE())-YEAR(P.birthday) as age,DATE_FORMAT(P.birthday,'%e��%d�ճ���') as birthday,S.spacename,S.sign FROM #@__member AS M 
						   LEFT JOIN #@__member_person AS P ON P.mid=M.mid
						   LEFT JOIN #@__member_space AS S ON M.mid=M.mid WHERE M.mid='$uid'");
	if(isset($row[$_field]))
	{
		if($_field == 'face')
		{
			if(empty($row[$_field])){
				$row[$_field]=($row['sex']=='Ů')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
		    }
		}
		return $row[$_field];
	}
	else return '';
}

?>