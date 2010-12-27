<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
require_once(DEDEINC."/typelink.class.php");
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEMEMBER."/inc/inc_list_functions.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
$cid = isset($cid) && is_numeric($cid) ? $cid : 0;
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 0;
$mtypesid = isset($mtypesid) && is_numeric($mtypesid) ? $mtypesid : 0;
if(!isset($keyword))
{
	$keyword = '';
}
if(!isset($arcrank))
{
	$arcrank = '';
}
$positionname = '';
$menutype = 'content';
$mid = $cfg_ml->M_ID;
$tl = new TypeLink($cid);
$cInfos = $tl->dsql->GetOne("Select arcsta,issend,issystem,usertype From `#@__channeltype`  where id='$channelid'; ");
if(!is_array($cInfos))
{
	ShowMsg('ģ�Ͳ�����', '-1');
	exit();
}
$arcsta = $cInfos['arcsta'];
$dtime = time();
$maxtime = $cfg_mb_editday * 24 *3600;

//��ֹ������Ȩ�޵�ģ��
if($cInfos['usertype'] !='' && $cInfos['usertype']!=$cfg_ml->M_MbType)
{
	ShowMsg('����Ȩ�޷��ʸò���', '-1');
	exit();
}

if($cid==0)
{
	$row = $tl->dsql->GetOne("Select typename From #@__channeltype where id='$channelid'");
	if(is_array($row))
	{
		$positionname = $row['typename'];
	}
}
else
{
	$positionname = str_replace($cfg_list_symbol,"",$tl->GetPositionName())." ";
}
$whereSql = " where arc.channel = '$channelid' And arc.mid='$mid' ";
if($keyword!='')
{
	$keyword = cn_substr(trim(ereg_replace("[><\|\"\r\n\t%\*\.\?\(\)\$ ;,'%-]","",stripslashes($keyword))),30);
	$keyword = addslashes($keyword);
	$whereSql .= " And (arc.title like '%$keyword%') ";
}
if($cid!=0)
{
	$whereSql .= " And arc.typeid in (".GetSonIds($cid).")";
}

//���ӷ����ѯ
if($arcrank == '1'){
	$whereSql .= " And arc.arcrank >= 0";
}else if($arcrank == '-1'){
	$whereSql .= " And arc.arcrank = -1";
}else if($arcrank == '-2'){
	$whereSql .= " And arc.arcrank = -2";
}

$classlist = '';
$dsql->SetQuery("SELECT * FROM `#@__mtypes` WHERE `mid` = '$cfg_ml->M_ID';");
$dsql->Execute();
while ($row = $dsql->GetArray())
{
	$classlist .= "<option value='content_list.php?channelid=".$channelid."&mtypesid=".$row['mtypeid']."'>".$row['mtypename']."</option>\r\n";
}
if($mtypesid != 0 )
{
	$whereSql .= " And arc.mtype = '$mtypesid'";
}
$query = "select arc.id,arc.typeid,arc.senddate,arc.flag,arc.ismake,arc.channel,arc.arcrank,
        arc.click,arc.title,arc.color,arc.litpic,arc.pubdate,arc.mid,tp.typename,ch.typename as channelname
        from `#@__archives` arc
        left join `#@__arctype` tp on tp.id=arc.typeid
        left join `#@__channeltype` ch on ch.id=arc.channel
       $whereSql order by arc.senddate desc ";
$dlist = new DataListCP();
$dlist->pageSize = 20;
$dlist->SetParameter("dopost","listArchives");
$dlist->SetParameter("keyword",$keyword);
$dlist->SetParameter("cid",$cid);
$dlist->SetParameter("channelid",$channelid);
$dlist->SetTemplate(DEDEMEMBER."/templets/content_list.htm");
$dlist->SetSource($query);
$dlist->Display();
?>