<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('temp_Test');
require_once(DEDEINC."/arc.partview.class.php");
$partcode = stripslashes($partcode);
if(empty($typeid))
{
	$typeid = 0;
}
if(empty($showsource))
{
	$showsource = "";
}
if($typeid>0)
{
	$pv = new PartView($typeid);
}
else
{
	$pv = new PartView();
}
$pv->SetTemplet($partcode,"string");
if($showsource==""||$showsource=="yes")
{
	echo "ģ�����:";
	echo "<span style='color:red;'><pre>".htmlspecialchars($partcode)."</pre></span>";
	echo "���:<hr size='1' width='100%'>";
}
$pv->Display();
?>