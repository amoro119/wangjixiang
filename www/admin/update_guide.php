<?php
require(dirname(__FILE__).'/config.php');
CheckPurview('sys_Edit');
@set_time_limit(0);
require(DEDEINC.'/inc/inc_fun_funAdmin.php');
require(DEDEINC.'/dedehttpdown.class.php');

/**
 * ���ļ����ڴӾ����������ȡ������Ϣ���ļ�
 * �����û����п�������
 *
 */
 
function TestWriteAble($d)
{
	$tfile = '_dedet.txt';
	$fp = @fopen($d.$tfile,'w');
	if(!$fp) {
		return false;
	}
	else {
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		return true;
	}
}

function GetDirName($filename)
{
	$dirname = '../'.ereg_replace("[\\/]{1,}",'/',$filename);
	$dirname = ereg_replace("([^/]*)$",'',$dirname);
	return $dirname;
}

function TestIsFileDir($dirname)
{
	$dirs = array('name'=>'','isdir'=>false,'writeable'=>false);
	$dirs['name'] =  $dirname;
	if(is_dir($dirname))
	{
		$dirs['isdir'] = true;
		$dirs['writeable'] = TestWriteAble($dirname);
	}
	return $dirs;
}

function MkTmpDir($tmpdir,$filename)
{
	$basedir = DEDEROOT.'/data/'.$tmpdir;
	$dirname = trim(ereg_replace("[\\/]{1,}",'/',$filename));
	$dirname = ereg_replace("([^/]*)$","",$dirname);
	if(!is_dir($basedir)) {
		mkdir($basedir,0777);
	}
	if($dirname=='') {
		return true;
	}
	$dirs = explode('/',$dirname);
	$curdir = $basedir;
	foreach($dirs as $d)
	{
		$d = trim($d);
		if(empty($d)) continue;
		$curdir = $curdir.'/'.$d;
		if(!is_dir($curdir)) {
			mkdir($curdir,0777) or die($curdir);
		}
	}
	return true;
}

if(empty($dopost)) {
	$dopost = 'test';
}

//����������������б䶯���뵽 http://bbs.dedecms.com ��ѯ
$updateHost = 'http://updatenew.dedecms.com/base-v56/';

//��ǰ����汾�����ļ�
$verLockFile = DEDEROOT.'/data/admin/ver.txt';

$fp = fopen($verLockFile,'r');
$upTime = trim(fread($fp,64));
fclose($fp);
$oktime = substr($upTime,0,4).'-'.substr($upTime,4,2).'-'.substr($upTime,6,2);

/**
��AJAX��ȡ���°汾��Ϣ
function _Test() {  }
*/
if($dopost=='test')
{
	AjaxHead();
	//����Զ������
	$dhd = new DedeHttpDown();
	$dhd->OpenUrl($updateHost.'/verinfo.txt');
	$verlist = trim($dhd->GetHtml());
	$dhd->Close();
	if($cfg_soft_lang=='utf-8') {
			$verlist = gb2utf8($verlist);
	}
	$verlist = ereg_replace("[\r\n]{1,}","\n",$verlist);
	$verlists = explode("\n", $verlist);
	
	//��������
	$updateVers = array();
	$upitems = $lastTime = '';
	$n = 0;
	foreach($verlists as $verstr)
	{
			if( empty($verstr) || ereg("^//",$verstr) ) {
				continue ;
			}
			list($vtime,$vlang,$issafe,$vmsg) = explode(',',$verstr);
			$vtime = trim($vtime);
			$vlang = trim($vlang);
			$issafe = trim($issafe);
			$vmsg = trim($vmsg);
			if($vtime > $upTime && $vlang==$cfg_soft_lang)
			{
				$updateVers[$n]['issafe'] = $issafe;
				$updateVers[$n]['vmsg'] = $vmsg;
				$upitems .= ($upitems=='' ? $vtime : ','.$vtime);
				$lastTime = $vtime;
				$updateVers[$n]['vtime'] = substr($vtime,0,4).'-'.substr($vtime,4,2).'-'.substr($vtime,6,2);
				$n++;
			}
	}
		
	//echo "<xmp>";
	//�ж��Ƿ���Ҫ���£��������ʺϵĽ��
	if($n==0)
	{
			$offUrl = SpGetNewInfo();
			echo "<div class='updatedvt'><b>��ϵͳ�汾������ʱ��Ϊ��{$oktime}����ǰû�п��õĸ���</b></div>\r\n";
			echo "<iframe name='stafrm' src='{$offUrl}&uptime={$oktime}' frameborder='0' id='stafrm' width='100%' height='50'></iframe>";
	}
	else
	{
			echo "<div style='width:98%'><form name='fup' action='update_guide.php' method='post' onsubmit='ShowWaitDiv()'>\r\n";
			echo "<input type='hidden' name='dopost' value='getlist' />\r\n";
			echo "<input type='hidden' name='vtime' value='$lastTime' />\r\n";
			echo "<input type='hidden' name='upitems' value='$upitems' />\r\n";
			echo "<div class='upinfotitle'>��ϵͳ�汾������ʱ��Ϊ��{$oktime}����ǰ���õĸ����У�</div>\r\n";
			foreach($updateVers as $vers)
			{
				$style = '';
				if($vers['issafe']==1) {
					$style = "color:red;";
				}
				echo "<div style='{$style}' class='verline'>��".($vers['issafe']==1 ? "��ȫ����" : "��ͨ����")."��";
				echo $vers['vtime']."������˵����{$vers['vmsg']}</div>\r\n";
			}
			echo "<div style='line-height:32px'><input type='submit' name='sb1' value=' ����˻�ȡ���и����ļ���Ȼ��ѡ��װ ' class='np coolbg' style='cursor:pointer' />\r\n";
			echo " &nbsp; <input type='button' name='sb2' value=' ������Щ���� ' onclick='SkipReload({$lastTime})' class='np coolbg'  style='cursor:pointer' /></div>\r\n";
			echo "</form></div>";
	}
	//echo "</xmp>";
	exit();
	
}
/**
����ĳ������ǰ������
function _Skip() {  }
*/
else if($dopost=='skip')
{
	AjaxHead();
	$fp = fopen($verLockFile,'w');
	fwrite($fp,$vtime);
	fclose($fp);
	$offUrl = SpGetNewInfo();
	echo "<div class='updatedvt'><b>��ϵͳ�汾������ʱ��Ϊ��{$oktime}����ǰû�п��õĸ��¡�</b></div>\r\n";
	echo "<iframe name='stafrm' src='{$offUrl}&uptime={$oktime}' frameborder='0' id='stafrm' width='100%' height='60'></iframe>";
	exit();
}
else if($dopost=='skipback')
{
	$fp = fopen($verLockFile,'w');
	fwrite($fp,$vtime);
	fclose($fp);
	ShowMsg("�ɹ�������Щ���£�","index_body.php");
	exit();
}
/**
��ȡ�����ļ��б�
function _GetList() {  }
*/
else if($dopost=='getlist')
{
	$upitemsArr = explode(',',$upitems);
	rsort($upitemsArr);
	
	$tmpdir = substr(md5($cfg_cookie_encode),0,16);
	
	$dhd = new DedeHttpDown();
	$fileArr = array();
	$f = 0;
	foreach($upitemsArr as $upitem)
	{
		$durl = $updateHost.$cfg_soft_lang.'/'.$upitem.'.file.txt';
		$dhd->OpenUrl($durl);
		$filelist = $dhd->GetHtml();
		$filelist = trim( ereg_replace("[\r\n]{1,}","\n",$filelist) );
		if(!empty($filelist))
		{
			$filelists = explode("\n",$filelist);
			foreach($filelists as $filelist)
			{
				$filelist = trim($filelist);
				if(empty($filelist)) continue;
				$fs = explode(',',$filelist);
				if( empty($fs[1]) ) {
					$fs[1] = $upitem." ���湦�ܸ����ļ�";
				}
				if(!isset($fileArr[$fs[0]])) {
					$fileArr[$fs[0]] = $upitem." ".trim($fs[1]);
					$f++;
				}
			}
		}
	}
	$dhd->Close();
	
	$allFileList = '';
	if($f==0)
	{
		$allFileList = "<font color='green'><b>û���ֿ��õ��ļ��б���Ϣ�������ǹٷ��������������⣬���Ժ��ٳ��ԣ�</b></font>";
	}
	else
	{
		$allFileList .= "<div style='width:98%'><form name='fup' action='update_guide.php' method='post'>\r\n";
		$allFileList .= "<input type='hidden' name='vtime' value='$vtime' />\r\n";
		$allFileList .= "<input type='hidden' name='dopost' value='getfiles' />\r\n";
		$allFileList .= "<input type='hidden' name='upitems' value='$upitems' />\r\n";
		$allFileList .= "<div class='upinfotitle'>��������Ҫ���صĸ����ļ���·�������DedeCMS�ĸ�Ŀ¼����</div>\r\n";
		$filelists = explode("\n",$filelist);
		foreach($fileArr as $k=>$v) {
			$allFileList .= "<div class='verline'><input type='checkbox' name='files[]' value='{$k}'  checked='checked' /> $k({$v})</div>\r\n";
		}
		$allFileList .= "<div class='verline'>";
		$allFileList .= "�ļ���ʱ���Ŀ¼��../data/<input type='text' name='tmpdir' style='width:200px' value='$tmpdir' /><br />\r\n";
		$allFileList .= "<input type='checkbox' name='skipnodir' value='1'  checked='checked' /> ����ϵͳ��û�е��ļ���(ͨ���ǿ�ѡģ��Ĳ���)</div>\r\n";
		$allFileList .= "<div style='line-height:36px;background:#F8FEDA'>&nbsp;\r\n";
		$allFileList .= "<input type='submit' name='sb1' value=' ���ز�Ӧ����Щ���� ' class='np coolbg' style='cursor:pointer' />\r\n";
		$allFileList .="</form></div>";
	}
	
	include DedeInclude('templets/update_guide_getlist.htm');
	exit();
}
/**
�����ļ������������������б�
function _GetFiles() {  }
*/
else if($dopost=='getfilesstart')
{
	//update_guide.php?dopost=down&curfile=0
	$msg = "������ʱ������û��װģ����ļ����д��󣬿ɲ������<br />";
	$msg .= "<a href=update_guide.php?dopost=down&curfile=0>ȷ��Ŀ¼״̬��������������ʼ�����ļ�&gt;&gt;</a><br />";
	ShowMsg($msg,"javascript:;");
	exit();
}
else if($dopost=='getfiles')
{
	$cacheFiles = DEDEROOT.'/data/cache/updatetmp.inc';
	$skipnodir = (isset($skipnodir) ? 1 : 0);
	$adminDir = ereg_replace("(.*)[/\\]","",dirname(__FILE__));
	
	if(!isset($files))
	{
		$doneStr = "<p align='center' style='color:red'><br />��û��ָ���κ���Ҫ���ظ��µ��ļ����Ƿ�������Щ���£�<br /><br />";
		$doneStr .= "<a href='update_guide.php?dopost=skipback&vtime=$vtime' class='np coolbg'>[������Щ����]</a> &nbsp; ";
		$doneStr .= "<a href='index_body.php'  class='np coolbg'>[������ʾ�Ժ��ٽ��в���]</a></p>";
	}
	else
	{
		$fp = fopen($cacheFiles,'w');
		fwrite($fp,'<'.'?php'."\r\n");
		fwrite($fp,'$tmpdir = "'.$tmpdir.'";'."\r\n");
		fwrite($fp,'$vtime = '.$vtime.';'."\r\n");
		$dirs = array();
		$i = -1;
		foreach($files as $filename)
		{
			$tfilename = $filename;
			if( eregi("^dede/",$filename) ) {
				$tfilename = eregi_replace("^dede/",$adminDir.'/',$filename);
			}
			$curdir = GetDirName($tfilename);
			if( !isset($dirs[$curdir]) ) {
				$dirs[$curdir] = TestIsFileDir($curdir);
			}
			if($skipnodir==1 && $dirs[$curdir]['isdir']==false) {
				continue;
			}
			else {
				@mkdir($curdir,0777);
				$dirs[$curdir] = TestIsFileDir($curdir);
			}
			$i++;
			fwrite($fp,'$files['.$i.'] = "'.$filename.'";'."\r\n");
		}
		fwrite($fp,'$fileConut = '.$i.';'."\r\n");
		
		$items = explode(',',$upitems);
		foreach($items as $sqlfile)
		{
			fwrite($fp,'$sqls[] = "'.$sqlfile.'.sql";'."\r\n");
		}
		fwrite($fp,'?'.'>');
		fclose($fp);
		
		$dirinfos = '';
		if($i > -1)
		{
			$dirinfos = '<tr bgcolor="#ffffff"><td colspan="2">';
			$dirinfos .= "����������Ҫ�������ļ���д������ļ�����ע���ļ����Ƿ���д��Ȩ�ޣ�<br />\r\n";
			foreach($dirs as $curdir)
			{
				$dirinfos .= $curdir['name']." ״̬��".($curdir['writeable'] ? "[������]" : "<font color='red'>[������д]</font>")."<br />\r\n";
			}
			$dirinfos .= "</td></tr>\r\n";
		}
		
		$doneStr = "<iframe name='stafrm' src='update_guide.php?dopost=getfilesstart' frameborder='0' id='stafrm' width='100%' height='100%'></iframe>\r\n";
	}
	include DedeInclude('templets/update_guide_getfiles.htm');
	exit();
}
/**
�����ļ��������������
function _Down() {  }
*/
else if($dopost=='down')
{
	$cacheFiles = DEDEROOT.'/data/cache/updatetmp.inc';
	require_once($cacheFiles);
	
	if(empty($startup))
	{
		if($fileConut==-1 || $curfile > $fileConut)
		{
			ShowMsg("�����������ļ�����ʼ�������ݿ������ļ�...","update_guide.php?dopost=down&startup=1");
			exit();
		}
		
		//�����ʱ�ļ�����Ŀ¼�Ƿ����
		MkTmpDir($tmpdir, $files[$curfile]);
		
		$downfile = $updateHost.$cfg_soft_lang.'/source/'.$files[$curfile];
		
		$dhd = new DedeHttpDown();
		$dhd->OpenUrl($downfile);
		$dhd->SaveToBin(DEDEROOT.'/data/'.$tmpdir.'/'.$files[$curfile]);
		$dhd->Close();
		
		ShowMsg("�ɹ����ز������ļ���{$files[$curfile]}�� ����������һ���ļ���","update_guide.php?dopost=down&curfile=".($curfile+1));
		exit();
		
	}
	else
	{
		MkTmpDir($tmpdir, 'sql.txt');
		$dhd = new DedeHttpDown();
		$ct = '';
		foreach($sqls as $sql)
		{
			$downfile = $updateHost.$cfg_soft_lang.'/'.$sql;
			$dhd->OpenUrl($downfile);
			$ct .= $dhd->GetHtml();
		}
		$dhd->Close();
		$truefile = DEDEROOT.'/data/'.$tmpdir.'/sql.txt';
		$fp = fopen($truefile,'w');
		fwrite($fp,$ct);
		fclose($fp);
		
		ShowMsg("�������Զ���ļ���ȡ������<a href='update_guide.php?dopost=apply'>&lt;&lt;����˿�ʼֱ������&gt;&gt;</a><br />��Ҳ����ֱ��ʹ��[../data/{$tmpdir}]Ŀ¼���ļ��ֶ�������","javascript:;");
		
		exit();
		
	}
	exit();
}
/**
Ӧ������
function _ApplyUpdate() {  }
*/
else if($dopost=='apply')
{
	$cacheFiles = DEDEROOT.'/data/cache/updatetmp.inc';
	require_once($cacheFiles);
	
	if(empty($step))
	{
		$truefile = DEDEROOT.'/data/'.$tmpdir.'/sql.txt';
		$fp = fopen($truefile,'r');
		$sql = @fread($fp,filesize($truefile));
		fclose($fp);
		if(!empty($sql))
		{
			$mysql_version = $dsql->GetVersion(true);
			
			$sql = eregi_replace('ENGINE=MyISAM','TYPE=MyISAM',$sql);
			$sql41tmp = 'ENGINE=MyISAM DEFAULT CHARSET='.$cfg_db_language;
			if($mysql_version >= 4.1) {
				$sql = eregi_replace('TYPE=MyISAM',$sql41tmp,$sql);
			}
			
			$sqls = explode(";\r\n", $sql);
			foreach($sqls as $sql)
			{
				if(trim($sql)!='') {
					$dsql->ExecuteNoneQuery(trim($sql));
				}
			}
		}
		ShowMsg("������ݿ���£����ڿ�ʼ�����ļ���","update_guide.php?dopost=apply&step=1");
		exit();
	}
	else
	{
		$sDir = DEDEROOT."/data/$tmpdir";
		$tDir = DEDEROOT;
		
		$badcp = 0;
		$adminDir = ereg_replace("(.*)[/\\]","",dirname(__FILE__));
		
		if(isset($files) && is_array($files))
		{
			foreach($files as $f)
			{
				if(ereg('^dede',$f)) {
					$tf = ereg_replace('^dede',$adminDir,$f);
				}
				else {
					$tf = $f;
				}
				if(file_exists($sDir.'/'.$f))
				{
					$rs = @copy($sDir.'/'.$f, $tDir.'/'.$tf);
					if($rs) {
						unlink($sDir.'/'.$f);
					}
					else {
						$badcp++;
					}
				}
			}
		}
		
		$fp = fopen($verLockFile,'w');
		fwrite($fp,$vtime);
		fclose($fp);
		
		$badmsg = '��';
		if($badcp > 0)
		{
			$badmsg = "������ʧ�� {$badcp} ���ļ���<br />�����ʱĿ¼[../data/{$tmpdir}]��ȡ���⼸���ļ��ֶ�������";
		}
		
		ShowMsg("�ɹ��������{$badmsg}","javascript:;");
		exit();
	}
}
?>