<?php
require(dirname(__FILE__)."/config.php");
header("Pragma:no-cache");
header("Cache-Control:no-cache");
header("Expires:0");

//��Դ�б�
if($t=='source')
{
	$m_file = DEDEDATA."/admin/source.txt";
	$allsources = file($m_file);
	echo "<div class='coolbg4'>[<a href=\"javascript:OpenMyWin('article_source_edit.php');ClearDivCt('mysource');\">����</a>]&nbsp;";
	echo "[<a href='#' onclick='javascript:HideObj(\"mysource\");ChangeFullDiv(\"hide\");'>�ر�</a>]</div>\r\n<div class='wsselect'>\r\n";
	foreach($allsources as $v)
	{
		$v = trim($v);
		if($v!="")
		{
			echo "<a href='#' onclick='javascript:PutSource(\"$v\")'>$v</a> | \r\n";
		}
	}
	echo "</div><div class='coolbg5'>&nbsp;</div>";
}
else
{
	//�����б�
	$m_file = DEDEDATA."/admin/writer.txt";
	echo "<div class='coolbg4'>[<a href=\"javascript:OpenMyWin('article_writer_edit.php');ClearDivCt('mywriter');\">����</a>]&nbsp;";
	echo "[<a href='#' onclick='javascript:HideObj(\"mywriter\");ChangeFullDiv(\"hide\");'>�ر�</a>]</div>\r\n<div class='wsselect'>\r\n";
	if(filesize($m_file)>0)
	{
		$fp = fopen($m_file,'r');
		$str = fread($fp,filesize($m_file));
		fclose($fp);
		$strs = explode(',',$str);
		foreach($strs as $str)
		{
			$str = trim($str);
			if($str!="")
			{
				echo "<a href='#' onclick='javascript:PutWriter(\"$str\")'>$str</a> | ";
			}
		}
	}
	echo "</div><div class='coolbg5'>&nbsp;</div>\r\n";
}

?>