<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('temp_Other');

//�����������ɱ��
$attlist = "";
$attlist .= " row='".$row."'";
$attlist .= " titlelen='".$titlelen."'";
if($orderby!='senddate')
{
	$attlist .= " orderby='".$orderby."'";
}
if($order!='desc')
{
	$attlist .= " order='".$order."'";
}
if($typeid>0)
{
	$attlist .= " typeid='".$typeid."'";
}
if(isset($arcid))
{
	$attlist .= " idlist='".$arcid."'";
}
if($channel>0)
{
	$attlist .= " channelid='".$channel."'";
}
if($att>0)
{
	$attlist .= " att='".$att."'";
}
if($col>1)
{
	$attlist .= " col='".$col."'";
}
if($subday>0)
{
	$attlist .= " subday='".$subday."'";
}
if(!empty($types))
{
	$attlist .= " type='";
	foreach($types as $v)
	{
		$attlist .= $v.'.';
	}
	$attlist .= "'";
}
$innertext = stripslashes($innertext);
if($keyword!="")
{
	$attlist .= " keyword='$keyword'";
}
$fulltag = "{dede:arclist$attlist}
$innertext
{/dede:arclist}\r\n";
if($dopost=='savetag')
{
	$fulltag = addslashes($fulltag);
	$tagname = "auto";
	$inQuery = "
	 Insert Into #@__mytag(typeid,tagname,timeset,starttime,endtime,normbody,expbody)
	 Values('0','$tagname','0','0','0','$fulltag','');
	";
	$dsql->ExecuteNoneQuery($inQuery);
	$id = $dsql->GetLastID();
	$dsql->ExecuteNoneQuery("Update #@__mytag set tagname='{$tagname}_{$id}' where aid='$id'");
	$fulltag = "{dede:mytag name='{$tagname}_{$id}' ismake='yes'/}";
}
include DedeInclude('templets/mytag_tag_guide_ok.htm');

?>