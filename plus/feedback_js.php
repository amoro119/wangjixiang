<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
if($cfg_feedback_forbid=='Y') exit("document.write('ϵͳ�Ѿ���ֹ���۹��ܣ�');\r\n");
require_once(DEDEINC."/datalistcp.class.php");
if(isset($arcID))
{
	$aid = $arcID;
}
$arcID = $aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
if($aid==0)
{
	exit(" Request Error! ");
}

$querystring = "select fb.*,mb.userid,mb.face as mface,mb.spacesta,mb.scores from `#@__feedback` fb
                 left join `#@__member` mb on mb.mid = fb.mid
                 where fb.aid='$aid' and fb.ischeck='1' order by fb.id desc";
$dlist = new DataListCP();
$dlist->pageSize = 6;
$dlist->SetTemplet(DEDETEMPLATE.'/plus/feedback_templet_js.htm');
$dlist->SetSource($querystring);
$dlist->display();

?>