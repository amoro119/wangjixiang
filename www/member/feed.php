<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/json.class.php");
CheckRank(0,0);
$menutype = 'config';
//ักิ๑สýพÝฟโ
$feeds = array();
$type=(empty($type))? "" : $type;
if($type=="allfeed")
{	
	$sql="SELECT * FROM `#@__member_feed` ORDER BY dtime DESC limit 8";
	$dsql->SetQuery($sql);
	$dsql->Execute();
	while ($row = $dsql->GetArray()) {
		$row['uname'] = gb2utf8($row['uname']);
		$row['title'] = gb2utf8(htmlspecialchars_decode($row['title'],ENT_QUOTES));
		$row['note'] = gb2utf8($row['note']);
		$row['dtime']= gb2utf8(FloorTime(time()- $row['dtime']));
		$feeds[] = $row;
	}
}elseif($type=="myfeed"){	
	$sql="SELECT * FROM `#@__member_feed`  where mid='".$cfg_ml->M_ID."' ORDER BY dtime DESC limit 8";
	$dsql->SetQuery($sql);
	$dsql->Execute();
	while ($row = $dsql->GetArray()) {
		$row['uname'] = gb2utf8($row['uname']);
		$row['title'] = gb2utf8(htmlspecialchars_decode($row['title'],ENT_QUOTES));
		$row['note'] = gb2utf8($row['note']);
		$row['dtime']= gb2utf8(FloorTime(time()- $row['dtime']));
		$feeds[] = $row;
	}
}else{
	require_once(DEDEINC.'/channelunit.func.php');
    $sql = "select arc.id,arc.typeid,arc.senddate,arc.title,arc.ismake,arc.arcrank,arc.money,arc.filename,a.namerule,a.typedir,a.moresite,a.siteurl, a.sitepath,m.userid from #@__archives arc left join #@__arctype a on a.id=arc.typeid left join #@__member m on m.mid=arc.mid where arc.arcrank > -1 order by arc.sortrank desc limit 12";
	$dsql->SetQuery($sql);
	$dsql->Execute();
	while ($row = $dsql->GetArray()) {
		$row['htmlurl'] = GetFileUrl($row['id'], $row['typeid'], $row['senddate'], $row['title'], $row['ismake'], $row['arcrank'], $row['namerule'], $row['typedir'], $row['money'], $row['filename'], $row['moresite'], $row['siteurl'], $row['sitepath']);
		$row['userid'] = gb2utf8($row['userid']);
		$row['title'] = gb2utf8($row['title']);
		$row['senddate'] = gb2utf8(MyDate('m-d H:i',$row['senddate']));
		$feeds[] = $row;
	}	
}

$json = new Services_JSON();
$output = $json->encode($feeds);
print($output);
?>