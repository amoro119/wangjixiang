<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/arc.searchview.class.php");

$pagesize = (isset($pagesize) && is_numeric($pagesize)) ? $pagesize : 10;
$typeid = (isset($typeid) && is_numeric($typeid)) ? $typeid : 0;
$channeltype = (isset($channeltype) && is_numeric($channeltype)) ? $channeltype : 0;
$kwtype = (isset($kwtype) && is_numeric($kwtype)) ? $kwtype : 1;
$mid = (isset($mid) && is_numeric($mid)) ? $mid : 0;

if(!isset($orderby)) $orderby='';
else $orderby = eregi_replace('[^a-z]','',$orderby);

if(!isset($searchtype)) $searchtype = 'titlekeyword';
else $searchtype = eregi_replace('[^a-z]','',$searchtype);

if(!isset($keyword)){
	if(!isset($q)) $q = '';
	$keyword=$q;
}

$oldkeyword = $keyword = FilterSearch(stripslashes($keyword));


//������Ŀ��Ϣ
if(empty($typeid))
{
	$typenameCacheFile = DEDEROOT.'/data/cache/typename.inc';
	if(!file_exists($typenameCacheFile) || filemtime($typenameCacheFile) < time()-(3600*24) )
	{
		$fp = fopen(DEDEROOT.'/data/cache/typename.inc', 'w');
		fwrite($fp, "<"."?php\r\n");
		$dsql->SetQuery("Select id,typename,channeltype From `#@__arctype`");
		$dsql->Execute();
		while($row = $dsql->GetArray())
		{
			fwrite($fp, "\$typeArr[{$row['id']}] = '{$row['typename']}';\r\n");
		}
		fwrite($fp, '?'.'>');
		fclose($fp);
	}
	//������Ŀ���沢���ؼ����Ƿ��������Ŀ����
	require_once($typenameCacheFile);
	if(isset($typeArr) && is_array($typeArr))
	{
		foreach($typeArr as $id=>$typename)
		{
			$keywordn = str_replace($typename, ' ', $keyword);
			if($keyword != $keywordn)
			{
				$keyword = $keywordn;
				$typeid = $id;
				break;
			}
		}
	}
}

$keyword = addslashes(cn_substr($keyword,30));

if($cfg_notallowstr !='' && eregi($cfg_notallowstr,$keyword))
{
	ShowMsg("��������ؼ����д��ڷǷ����ݣ���ϵͳ��ֹ��","-1");
	exit();
}

if(($keyword=='' || strlen($keyword)<2) && empty($typeid))
{
	ShowMsg('�ؼ��ֲ���С��2���ֽڣ�','-1');
	exit();
}

//����������ʱ��
$lockfile = DEDEROOT.'/data/time.lock.inc';
if(!file_exists($lockfile)) {
	$fp = fopen($lockfile,'w');
	flock($fp,1);
	fwrite($fp,time());
	fclose($fp);
}

//��ʼʱ��
if(empty($starttime)) $starttime = -1;
else
{
	$starttime = (is_numeric($starttime) ? $starttime : -1);
	if($starttime>0)
	{
	   $dayst = GetMkTime("2008-1-2 0:0:0") - GetMkTime("2008-1-1 0:0:0");
	   $starttime = time() - ($starttime * $dayst);
  }
}

$t1 = ExecTime();

$sp = new SearchView($typeid,$keyword,$orderby,$channeltype,$searchtype,$starttime,$pagesize,$kwtype,$mid);
$keyword = $oldkeyword;
$sp->Display();

//echo ExecTime() - $t1;
?>