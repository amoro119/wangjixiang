<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Edit');
@set_time_limit(0);
require(DEDEINC.'/dedehttpdown.class.php');

//���·�����������б䶯���뵽 http://bbs.dedecms.com ��ѯ
$officialUrl = 'http://service.dedecms.com';
$updateHost = 'http://updatenew.dedecms.com/base-v56/';

$action = isset($action) ? trim($action) : '';

//��ǰ����汾�����ļ�
$verLockFile = DEDEROOT.'/data/admin/ver.txt';
//��ǰ���ָ���������ļ�
$verifiesLockFile = DEDEROOT.'/data/admin/verifies.txt';
	
$fp = fopen($verLockFile,'r');
$upTime = trim(fread($fp,64));
fclose($fp);

$updateTime = substr($upTime,0,4).'-'.substr($upTime,4,2).'-'.substr($upTime,6,2);
$verifiesTime = "δͬ����ָ����";
if(file_exists($verifiesLockFile))
{
	$fp = fopen($verifiesLockFile,'r');
	$upTime = trim(fread($fp,64));
	fclose($fp);
	$verifiesTime = substr($upTime,0,4).'-'.substr($upTime,4,2).'-'.substr($upTime,6,2);
}

$tmpdir = substr(md5($cfg_cookie_encode),0,16);


//�ض���file_get_contents�����ݲ�֧�ִ˺�����PHP
//��Ϊ�и���ط���fgets���ļ�����У���벻����
if(!function_exists('file_get_contents'))
{
	function file_get_contents($fname)
	{
		if(!file_exists($fname) || is_dir($fname))
		{
			return '';
		}
		else
		{
			$fp = fopen($fname, 'r');
			$ct = fread($fp, filesize($fname));
			fclose($fp);
			return $ct;
		}
	}
}

if($action == '')
{
	include(DEDEADMIN.'/templets/sys_verifies.htm');
	exit();
}
/*----------------
У���ļ�
function _verify() { }
----------------*/
else if($action == 'verify')
{
	$dsql->SetQuery("Select * from `#@__verifies` ");
	$dsql->Execute();
	$filelist = array();
	while($row = $dsql->GetArray())
	{
		$turefile = str_replace('../dede', '.', $row['filename']);
		//���������ڵ��ļ�
		if(!file_exists($turefile)) {
			continue;
		}
		if( filesize($turefile)==0 ) {
			continue;
		}
		$ct = file_get_contents($turefile);
		$ct = preg_replace("/\/\*\*[\r\n]{1,}(.*)[\r\n]{1,} \*\//sU", '', $ct);
		$cthash = md5($ct);
		if($cthash != $row['cthash']) {
			$row['localhash'] = $cthash;
			$row['mtime'] = MyDate('Y-m-d H:i:s', filemtime($turefile));
			$row['turefile'] = $turefile;
			$filelist[] = $row;
		}
	}
	if(!isset($filelist[0]))
	{
		ShowMsg("�����ļ���ͨ��Ч��֤�������ļ�û�б��Ķ�����","sys_verifies.php");
	}
	else
	{
		include(DEDEADMIN.'/templets/sys_verifies_verify.htm');
	}
	exit();
}
/*--------------------
�鿴���������ļ�
function _view() { }
----------------------*/
else if ($action == 'view')
{
	require_once(DEDEINC."/oxwindow.class.php");
	
	$filetxt = '';
	if( !eregi('data(.*)common.inc.php',$filename) )
	{
		$fp = fopen($filename,'r');
		$filetxt = fread($fp, filesize($filename));
		fclose($fp);
	}
	
	$filetxt = str_replace('textarea','!textarea',$filetxt);
	
	$wintitle = "�ļ�Ч��::�鿴�ļ�";
	$wecome_info = "<a href='sys_verifies.php'><u>�ļ�Ч��</u></a>::�鿴�ļ�";
	$win = new OxWindow();
	$win->Init();
	$win->AddTitle("����Ϊ�ļ� $filename �����ݣ������Ƿ���ɣ�");
	$winform = $win->GetWindow("hand","<textarea name='filetxt' style='width:100%;height:450px;word-wrap: break-word;word-break:break-all;'>".$filetxt."</textarea>");
	$win->Display();
	exit();
}
/*-----------------
����ָ����
function _manage() { }
-------------------*/
else if ($action == 'manage')
{
	$dsql->SetQuery("Select * from `#@__verifies` ");
	$dsql->Execute();
	$filelist = array();
	while($row = $dsql->GetArray())
	{
		$filelist[] = $row;
	}
	include(DEDEADMIN.'/templets/sys_verifies_manage.htm');
	exit();
}
/*-----------------------
�����ļ�
function _getfiles()
------------------------*/
else if ($action == 'getfiles')
{
	if(!isset($refiles))
	{
		ShowMsg("��û�����κβ�����","sys_verifies.php");
		exit();
	}
	$cacheFiles = DEDEROOT.'/data/modifytmp.inc';
	$fp = fopen($cacheFiles,'w');
	fwrite($fp,'<'.'?php'."\r\n");
	fwrite($fp,'$tmpdir = "'.$tmpdir.'";'."\r\n");
	$dirs = array();
	$i = -1;
	$adminDir = ereg_replace("(.*)[/\\]","",dirname(__FILE__));
	foreach($refiles as $filename)
	{
			$filename = substr($filename,3,strlen($filename)-3);
			if(eregi("^dede/",$filename)) {
				$curdir = GetDirName( eregi_replace("^dede/",$adminDir.'/',$filename) );
			}
			else {
				$curdir = GetDirName($filename);
			}
			if( !isset($dirs[$curdir]) ) {
				$dirs[$curdir] = TestIsFileDir($curdir);
			}
			$i++;
			fwrite($fp,'$files['.$i.'] = "'.$filename.'";'."\r\n");
	}
	fwrite($fp,'$fileConut = '.$i.';'."\r\n");
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
		
	$doneStr = "<iframe name='stafrm' src='sys_verifies.php?action=down&curfile=0' frameborder='0' id='stafrm' width='100%' height='100%'></iframe>\r\n";
	
	include(DEDEADMIN.'/templets/sys_verifies_getfiles.htm');
	
	exit();
}
/*-----------------------
�����ļ�
function _down()
------------------------*/
else if($action=='down')
{
	$cacheFiles = DEDEROOT.'/data/modifytmp.inc';
	require_once($cacheFiles);
	
	if($fileConut==-1 || $curfile > $fileConut)
	{
		ShowMsg("�����������ļ�<br /><a href='sys_verifies.php?action=apply'>[ֱ���滻�ļ�]</a> &nbsp; <a href='#'>[���Լ��ֶ��滻�ļ�]</a>","javascript:;");
		exit();
	}
	
	//�����ʱ�ļ�����Ŀ¼�Ƿ����
	MkTmpDir($tmpdir, $files[$curfile]);
		
	$downfile = $updateHost.$cfg_soft_lang.'/source/'.$files[$curfile];
		
	$dhd = new DedeHttpDown();
	$dhd->OpenUrl($downfile);
	$dhd->SaveToBin(DEDEROOT.'/data/'.$tmpdir.'/'.$files[$curfile]);
	$dhd->Close();
		
	ShowMsg("�ɹ������ļ���{$files[$curfile]}�� ����������һ���ļ���","sys_verifies.php?action=down&curfile=".($curfile+1));
	exit();
}
/*-----------------------
�޸�Ч�鷽ʽ
function _modify()
------------------------*/
else if($action=='modify')
{
	if(!isset($modifys))
	{
		ShowMsg("ûѡ��Ҫ�޸ĵ��ļ���","-1");
		exit();
	}
	else
	{
		foreach($modifys as $fname)
		{
			if($method=='local')
			{
				$tureFilename = str_replace('../dede','./',$fname);
				if(file_exists($tureFilename))
				{
					$fp = fopen($tureFilename,'r');
					$ct = fread($fp,filesize($tureFilename));
					fclose($fp);
					$cthash = md5($ct);
					$dsql->ExecuteNoneQuery("update `#@__verifies` set `method`='local',cthash='$cthash' where filename='$fname' ");
				}
			}
			else
			{
				$dsql->ExecuteNoneQuery("update `#@__verifies` set `method`='offical' where filename='$fname' ");
			}
		}
	}
	if($method=='local')
	{
		ShowMsg("�ɹ��޸�ָ���ļ�����֤��ʽ��","sys_verifies.php?action=manage");
	}
	else
	{
		ShowMsg("�ɹ��޸�ָ���ļ�����֤��ʽ��<br /> �������޸����ļ�ΪԶ����֤��ʽ���������и��²���<br /> <a href='sys_verifies.php?action=update'>[����]</a> &nbsp; <a href='sys_verifies.php?action=manage'>[����]</a>","javascript:;");
	}
	exit();
}
/*-----------------------
��ԭ�ļ�
function _applyRecover()
------------------------*/
else if ($action == 'apply')
{
	$cacheFiles = DEDEROOT.'/data/modifytmp.inc';
	require_once($cacheFiles);
	$sDir = DEDEROOT."/data/$tmpdir";
	$tDir = DEDEROOT;
		
	$badcp = 0;
	$adminDir = ereg_replace("(.*)[/\\]","",dirname(__FILE__));
		
	if(isset($files) && is_array($files))
	{
			foreach($files as $f)
			{
				if(ereg('^dede',$f)) $tf = ereg_replace('^dede',$adminDir,$f);
				else $tf = $f;
				
				if(file_exists($sDir.'/'.$f))
				{
					//��ԭ�ļ�ǰ�Ƚ����ļ�Ч��
					$ct = file_get_contents($sDir.'/'.$f);
					$ct = preg_replace("/\/\*\*[\r\n]{1,}(.*)[\r\n]{1,} \*\//sU", '', $ct);
					$newhash = md5($ct);
					$row = $dsql->GetOne("Select * From `#@__verifies` where filename='../{$f}' ");
					if(is_array($row) && $row['cthash'] != $newhash)
					{
						$badcp++;
					}
					else
					{
						$rs = @copy($sDir.'/'.$f, $tDir.'/'.$tf);
						if($rs) unlink($sDir.'/'.$f);
						else $badcp++;
					}
				}
			}
	}
		
	$badmsg = '��';
	if($badcp > 0)
	{
		$badmsg = "���� {$badcp} ���ļ�Ч���벻��ȷ����ʧ�ܣ�<br />�����ʱĿ¼[../data/{$tmpdir}]��ȡ���⼸���ļ��ֶ���ԭ��";
	}
		
	ShowMsg("�ɹ���ɻ�ԭָ���ļ�{$badmsg}","javascript:;");
	exit();
}
/*---------------
���߸���ָ����
function _update()
-----------------*/
else if($action == 'update')
{
	$rmFile = $updateHost.$cfg_soft_lang.'/verifys.txt';
	$dhd = new DedeHttpDown();
	$dhd->OpenUrl($rmFile);
	$ct = $dhd->GetHtml();
	$dhd->Close();
	$cts = split("[\r\n]{1,}",$ct);
	foreach($cts as $ct)
	{
		$ct = trim($ct);
		if(empty($ct)) continue;
		list($nameid,$cthash,$fname) = explode("\t",$ct);
		$row = $dsql->GetOne("Select * From `#@__verifies` where nameid='$nameid' ");
		if(!is_array($row) || ($row['method']=='official' && $row['cthash']!=$cthash ))
		{
			$dsql->ExecuteNoneQuery("Replace Into `#@__verifies`(nameid,cthash,method,filename) values ('$nameid','$cthash','official','$fname'); ");
		}
	}
	$fp = fopen($verifiesLockFile,'w');
	fwrite($fp, MyDate('Ymd',time()));
	fclose($fp);
	ShowMsg("���Ч������£��Ƿ����Ͻ���Ч�������<br /> <a href='sys_verifies.php?action=verify'>[��ʼЧ��]</a> &nbsp; <a href='sys_verifies.php?action=manage'>[����]</a> &nbsp; <a href='sys_verifies.php'>[����]</a>","javascript:;");
	exit();
}
/*-----------------
����ָ����
function _make() { }
-------------------*/
else if ($action == 'make')
{
	$fp = fopen(DEDEROOT.'/../verifys.txt','w');
	foreach (preg_ls ('../', true, "/.*\.(php|htm|html|js)$/i", 'CVS,data,html,uploads,templets,special') as $onefile)
	{
		$nameid = md5($onefile);
		$ctbody = file_get_contents(DEDEADMIN.'/'.$onefile);
		$ctbody = preg_replace("/\/\*\*[\r\n]{1,}(.*)[\r\n]{1,} \*\//sU", '', $ctbody);
		$cthash = md5($ctbody);
		fwrite($fp,"{$nameid}\t{$cthash}\t{$onefile}\r\n");
	}
	fclose($fp);
	ShowMsg("�����ɹ���","sys_verifies.php");
	exit();
}
//��ȡ�����ļ��б�
function preg_ls($path=".", $rec=false, $pat="/.*/", $ignoredir='')
{
	while (substr ($path,-1,1) =="/")
	{
		$path=substr ($path,0,-1);
	}
	if (!is_dir ($path) )
	{
		$path=dirname ($path);
	}
	if ($rec!==true)
	{
		$rec=false;
	}
	$d=dir ($path);
	$ret=Array ();
	while (false!== ($e=$d->read () ) )
	{
		if ( ($e==".") || ($e=="..") )
		{
			continue;
		}
		if ($rec && is_dir ($path."/".$e) && ($ignoredir == '' || strpos($ignoredir,$e ) === false))
		{
			$ret=array_merge ($ret,preg_ls($path."/".$e,$rec,$pat,$ignoredir));
			continue;
		}
		if (!preg_match ($pat,$e) )
		{
			continue;
		}
		$ret[]=$path."/".$e;
	}
	return (empty ($ret) && preg_match ($pat,basename($path))) ? Array ($path."/") : $ret;
}
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
?>