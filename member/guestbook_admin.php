<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'mydede';
$menutype_son = 'gu';
$pagesize = isset($pagesize) && is_numeric($pagesize) ? $pagesize : 5;
$pageno = isset($pageno) && is_numeric($pageno) ? max(1,$pageno) : 1;
if(empty($dopost))
{
	$dopost = '';
}

//�����б�
if($dopost=='getlist')
{
	AjaxHead();
	GetList($dsql,$pageno,$pagesize);
	exit();
}

//ɾ������
if($dopost=='del')
{
	if(!empty($aid))
	{
		$aid = intval($aid);
		$dsql->ExecuteNoneQuery("Delete From `#@__member_guestbook` where aid='$aid' And mid='".$cfg_ml->M_ID."'; ");
	}
	else if(!empty($ids))
	{
		$ids = ereg_replace("[^0-9,]",'',$ids);
		if($ids!='')
		{
			$dsql->ExecuteNoneQuery("Delete From `#@__member_guestbook` where aid in($ids) And mid='".$cfg_ml->M_ID."'; ");
		}
	}
	AjaxHead();
	GetList($dsql,$pageno,$pagesize);
	exit();
}

//��һ�ν������ҳ��
if($dopost=='')
{
	$row = $dsql->GetOne("Select count(*) as dd From `#@__member_guestbook` where mid='".$cfg_ml->M_ID."'; ");
	$totalRow = $row['dd'];
	include(dirname(__FILE__)."/templets/guestbook_admin.htm");
}

//����ض��Ĺؼ����б�
function GetList($dsql,$pageno,$pagesize)
{
	global $cfg_phpurl,$cfg_ml;
	$pagesize = intval($pagesize);
	$pageno = intval($pageno);
	$start = ($pageno-1) * $pagesize;
	$dsql->SetQuery("Select * From `#@__member_guestbook` where mid='".$cfg_ml->M_ID."' order by aid desc limit $start,$pagesize ");
	$dsql->Execute();
	$line = '';
	while($row = $dsql->GetArray())
	{

		$line .= "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='list'>
          <thead>
            <tr>
              <th colspan='6'><strong class='fLeft' style='padding-left: 5px;'>���Ա��⣺".$row['title']."</strong><span class='fRight'>
              <input name=\"ids\" type=\"checkbox\" id=\"ids\" value=\"".$row['aid']."\" />
              <a href='#' onclick='DelNote(".$row['aid'].")' style='color:#29A5C9;'>ɾ��</a></span></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td width='18%' style='padding-left: 10px;'>�û��ƺ���".$row['uname']."</td>
              <td>ʱ�䣺".MyDate("Y-m-d H:i",$row['dtime'])."&nbsp;IP��ַ��".$row['ip']."&nbsp;";

		if(!empty($row['gid']))
		{
			$line .= " <a href='index.php?uid={$row['uname']}&action=infos' target='_blank'>����</a> <a href='index.php?uid={$row['uname']}' target='_blank'>�ռ�</a> <a href='index.php?uid={$row['uname']}&action=guestbook' target='_blank'>�ظ�</a> ";
		}
		$line .= "</td>
            </tr>
            <tr>
              <td colspan='2' style='padding-left: 10px;'>".Text2Html($row['msg'])."</td>
            </tr>
          </tbody>
        </table>";

	}
	$line = $line == '' ? '��������' : $line;
	echo $line;
}

?>