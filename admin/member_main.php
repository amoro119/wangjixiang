<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('member_List');
require_once(DEDEINC."/datalistcp.class.php");
setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");

if(!isset($sex)) $sex = '';
if(!isset($mtype)) $mtype = '';
if(!isset($spacesta)) $spacesta = -10;
if(!isset($matt)) $matt = 10;

if(!isset($keyword)) $keyword = '';
else $keyword = trim(FilterSearch($keyword));

$mtypeform = empty($mtype) ? "<option value=''>����</option>\r\n" : "<option value='$mtype'>$mtype</option>\r\n";
$sexform = empty($sex) ? "<option value=''>�Ա�</option>\r\n" : "<option value='$sex'>$sex</option>\r\n";
$sortkey = empty($sortkey) ? 'mid' : eregi_replace('[^a-z]','',$sortkey);

$staArr = array(-2=>'�����û�(����)', -1=>'δͨ�����', 0=>'���ͨ������ʾ��д������Ϣ', 1=>'û��д��ϸ����', 2=>'����ʹ��״̬');
$staArrmatt = array(1=>'���Ƽ�', 0=>'����ͨ ' );
$MemberTypes = array();
$dsql->SetQuery("Select rank,membername From `#@__arcrank` where rank>0 ");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$MemberTypes[$row->rank] = $row->membername;
}

if($sortkey=='mid')
{
	$sortform = "<option value='mid'>mid/ע��ʱ��</option>\r\n";
}
else if($sortkey=='rank')
{
	$sortform = "<option value='rank'>��Ա�ȼ�</option>\r\n";
}
else if($sortkey=='money')
{
	$sortform = "<option value='money'>��Ա���</option>\r\n";
}
else if($sortkey=='scores')
{
	$sortform = "<option value='scores'>��Ա����</option>\r\n";
}
else
{
	$sortform = "<option value='logintime'>��¼ʱ��</option>\r\n";
}

$wheres[] = " (userid like '%$keyword%' Or uname like '%$keyword%' Or email like '%$keyword%') ";

if($sex   != '')
{
	$wheres[] = " sex like '$sex' ";
}

if($mtype != '')
{
	$wheres[] = " mtype like '$mtype' ";
}

if($spacesta != -10)
{
	$wheres[] = " spacesta = '$spacesta' ";
}

if($matt != 10)
{
	$wheres[] = " matt= '$matt' ";
}

$whereSql = join(' And ',$wheres);
if($whereSql!='')
{
	$whereSql = ' where '.$whereSql;
}
$dsql->SetQuery("Select name From `#@__member_model`");
$dsql->Execute();
while($row = $dsql->GetArray())
{
	$MemberModels[] = $row;
}
$sql  = "select * From `#@__member` $whereSql order by $sortkey desc ";
$dlist = new DataListCP();
$dlist->SetParameter('sex',$sex);
$dlist->SetParameter('spacesta',$spacesta);
$dlist->SetParameter('matt',$matt);
$dlist->SetParameter('mtype',$mtype);
$dlist->SetParameter('sortkey',$sortkey);
$dlist->SetParameter('keyword',$keyword);
$dlist->SetTemplet(DEDEADMIN."/templets/member_main.htm");
$dlist->SetSource($sql);
$dlist->display();

function GetMemberName($rank,$mt)
{
	global $MemberTypes;
	if(isset($MemberTypes[$rank]))
	{
		if($mt=='ut') return " <font color='red'>��������".$MemberTypes[$rank]."</font>";
		else return $MemberTypes[$rank];
	}
	else
	{
		if($mt=='ut') return '';
		else return $mt;
	}
}

function GetMAtt($m)
{
	if($m<1) return '';
	else if($m==10) return "&nbsp;<font color='red'>[����Ա]</font>";
	else return "&nbsp;<img src='img/adminuserico.gif' wmidth='16' height='15'><font color='red'>[��]</font>";
}

?>