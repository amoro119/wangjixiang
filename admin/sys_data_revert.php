<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Data');
$bkdir = DEDEDATA."/".$cfg_backup_dir;
$filelists = Array();
$dh = dir($bkdir);
$structfile = "û�ҵ����ݽṹ�ļ�";
while(($filename=$dh->read()) !== false)
{
	if(!ereg('txt$',$filename))
	{
		continue;
	}
	if(ereg('tables_struct',$filename))
	{
		$structfile = $filename;
	}
	else if( filesize("$bkdir/$filename") >0 )
	{
		$filelists[] = $filename;
	}
}
$dh->close();
include DedeInclude('templets/sys_data_revert.htm');

?>