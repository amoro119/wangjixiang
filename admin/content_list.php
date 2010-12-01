<?php
/**
 *
 * content_s_list.php��content_i_list.php��content_select_list.php
 * ��ʹ�ñ��ļ���Ϊʵ�ʴ�����룬ֻ��ʹ�õ�ģ�岻ͬ��������ر䶯��ֻ��ı��ļ������ģ�弴��
 *
 */
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/typelink.class.php');
require_once(DEDEINC.'/datalistcp.class.php');
require_once(DEDEADMIN.'/inc/inc_list_functions.php');

$cid = isset($cid) ? intval($cid) : 0;

$channelid = isset($channelid) ? intval($channelid) : 0;

$mid = isset($mid) ? intval($mid) : 0;

if(!isset($keyword)) $keyword = '';

if(!isset($flag)) $flag = '';

if(!isset($arcrank)) $arcrank = '';

if(!isset($dopost)) $dopost = '';

//���Ȩ����ɣ���Ȩ��
CheckPurview('a_List,a_AccList,a_MyList');

//��Ŀ������
$userCatalogSql = '';
if(TestPurview('a_List'))
{
	;
}
else if(TestPurview('a_AccList'))
{
	if($cid==0 && $cfg_admin_channel == 'array')
	{
		$admin_catalog = join(',', $admin_catalogs);
		$userCatalogSql = " arc.typeid in($admin_catalog) ";
	}
	else
	{
		CheckCatalog($cid, '����Ȩ�����ָ����Ŀ�����ݣ�');
	}
     if(TestPurview('a_MyList'))
    {
        $mid =  $cuserLogin->getUserID();
    }
}

$adminid = $cuserLogin->getUserID();
$maintable = '#@__archives';
setcookie('ENV_GOBACK_URL', $dedeNowurl, time()+3600, '/');
$tl = new TypeLink($cid);

//----------------------------------------
//�ڲ�ָ�����������͹ؼ��ֵ������ֱ��ͳ��΢��
//----------------------------------------
if(empty($totalresult) && empty($keyword) && empty($orderby) && empty($flag))
{
	$tinyQuerys = array();
	
	if(!empty($userCatalogSql))
	{
		$tinyQuerys[] = str_replace('arc.', '', $userCatalogSql);
	}
	
	if(!empty($channelid) && empty($cid))
	{
		$tinyQuerys[] = " channel = '$channelid' ";
	}
	else
	{
		$tinyQuerys[] = " channel>0 ";
	}
	
	if(!empty($arcrank))
	{
		$tinyQuerys[] = " arcrank='$arcrank' ";
	}
	else
	{
		$tinyQuerys[] = " arcrank > -2 ";
	}
	
	if(!empty($mid))
	{
		$tinyQuerys[] = " mid='$mid' ";
	}
	
	if(!empty($cid))
	{
		$tinyQuerys[] = " typeid in(".GetSonIds($cid).") ";
	}
	
	if(count($tinyQuerys)>0)
	{
		$tinyQuery = "where ".join(' And ',$tinyQuerys);
	}
	
	$arr = $dsql->GetOne("Select count(*) as dd From `#@__arctiny` $tinyQuery ");

	$totalresult = $arr['dd'];
}

if($cid==0)
{
	if($channelid==0)
	{
		$positionname = '������Ŀ&gt;';
	}
	else
	{
		$row = $tl->dsql->GetOne("Select id,typename,maintable From `#@__channeltype` where id='$channelid'");
		$positionname = $row['typename']." &gt; ";
		$maintable = $row['maintable'];
		$channelid = $row['id'];
	}
}
else
{
	$positionname = str_replace($cfg_list_symbol," &gt; ",$tl->GetPositionName())." &gt; ";
}

//��ѡ����ǵ���ģ����Ŀʱ��ֱ����ת������ģ�͹�����
if(empty($channelid) 
  && isset($tl->TypeInfos['channeltype']))
{
	$channelid = $tl->TypeInfos['channeltype'];
}
if($channelid < -1 )
{
	header("location:content_sg_list.php?cid=$cid&channelid=$channelid&keyword=$keyword");
	exit();
}

$optionarr = $tl->GetOptionArray($cid, $admin_catalogs, $channelid);
$whereSql = empty($channelid) ? " where arc.channel > 0  And arc.arcrank > -2 " : " where arc.channel = '$channelid' And arc.arcrank > -2 ";

$flagsArr = '';
$dsql->Execute('f', 'Select * From `#@__arcatt` order by sortid asc');
while($frow = $dsql->GetArray('f'))
{
	$flagsArr .= ($frow['att']==$flag ? "<option value='{$frow['att']}' selected>{$frow['attname']}</option>\r\n" : "<option value='{$frow['att']}'>{$frow['attname']}</option>\r\n");
}


if(!empty($userCatalogSql))
{
		$whereSql .= " And ".$userCatalogSql;
}
if(!empty($mid))
{
	$whereSql .= " And arc.mid = '$mid' ";
}
if($keyword != '')
{
	$whereSql .= " And ( CONCAT(arc.title,arc.writer) like '%$keyword%') ";
}
if($flag != '')
{
	$whereSql .= " And FIND_IN_SET('$flag', arc.flag) ";
}
if($cid != 0)
{
	$whereSql .= ' And arc.typeid in ('.GetSonIds($cid).')';
}
if($arcrank != '')
{
	$whereSql .= " And arc.arcrank = '$arcrank' ";
	$CheckUserSend = "<input type='button' class='coolbg np' onClick=\"location='catalog_do.php?cid=".$cid."&dopost=listArchives&gurl=content_list.php';\" value='�����ĵ�' />";
}
else
{
	$CheckUserSend = "<input type='button' class='coolbg np' onClick=\"location='catalog_do.php?cid=".$cid."&dopost=listArchives&arcrank=-1&gurl=content_list.php';\" value='������' />";
}

$orderby = empty($orderby) ? 'id' : eregi_replace("[^a-z0-9]", "", $orderby);
$orderbyField = 'arc.'.$orderby;

$query = "Select arc.id,arc.typeid,arc.senddate,arc.flag,arc.ismake,
arc.channel,arc.arcrank,arc.click,arc.title,arc.color,arc.litpic,arc.pubdate,arc.mid,
tp.typename,ch.typename as channelname,mb.uname as adminname
from `$maintable` arc
left join `#@__arctype` tp on tp.id=arc.typeid
left join `#@__channeltype` ch on ch.id=arc.channel
left join `#@__member` mb on mb.mid=arc.mid
$whereSql
order by $orderbyField desc";

if(empty($f) || !ereg('form', $f)) $f = 'form1.arcid1';

//��ʼ��
$dlist = new DataListCP();
$dlist->pageSize = 30;

//GET����
$dlist->SetParameter('dopost', 'listArchives');
$dlist->SetParameter('keyword', $keyword);
if(!empty($mid)) $dlist->SetParameter('mid', $mid);
$dlist->SetParameter('cid', $cid);
$dlist->SetParameter('flag', $flag);
$dlist->SetParameter('orderby', $orderby);
$dlist->SetParameter('arcrank', $arcrank);
$dlist->SetParameter('channelid', $channelid);
$dlist->SetParameter('f', $f);

//ģ��
if(empty($s_tmplets)) $s_tmplets = 'templets/content_list.htm';
$dlist->SetTemplate(DEDEADMIN.'/'.$s_tmplets);

//��ѯ
$dlist->SetSource($query);

//��ʾ
$dlist->Display();
$dlist->Close();

?>