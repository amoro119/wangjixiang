<?php
require_once(dirname(__FILE__).'/../include/common.inc.php');
require_once(DEDEINC.'/dedetag.class.php');
/*****************************************************
�ƻ��������ʹ��˵����
������������ִ����������ְ������ת��Ҫִ�е�������ַ��JS������ʽ������ֱ�ӷ���Ҫִ�е�������ַ���ͻ�����ʽ��
��Ϊ��ȷ��������ִ����ȫ������ʹ��Dede�Ŀͻ��˹��ߣ�����ֻ��ͨ��JS��������JS�����кܶ಻ȷ�����ػᵼ����������ɣ�
��JS������ʽ���������ĵ�ҳ������JS����/plus/task.php?client=js��������üƻ���������룬ϵͳ���ò���->����ѡ���
�����ж��ƿͻ��ˣ�ֱ�ӷ��ʡ�http://��ַ/plus/task.php?clientpwd=�������롱���᷵������һ����ִ���������ַ��û�п��������򷵻ش���notask)��Ȼ��ͻ������������ַ���ɡ� 
****************************************************/
if(empty($client)) $client = 'dede';
if(empty($clientpwd)) $clientpwd = '';
$cfg_task_pwd = trim($cfg_task_pwd);

//��֤�ͻ��˹�������
if(!empty($cfg_task_pwd) && $clientpwd != $cfg_task_pwd)
{
	echo ($client=='js' ? '' : 'notask');
	exit();
}

//ȡ�õ�ʱ��ĸ���ֵ
$ntime = time();
$nformattime = GetDateTimeMk($ntime);
list($ndate, $ntime) = explode(' ', $nformattime);
list($y, $m, $d) = explode('-', $ndate);
list($hh, $mm, $ss) = explode(':', $ntime);

$daylimit = 24 * 3600;

$dsql->Execute('me', 'Select * From `#@__sys_task` where islock=0 order by id asc ');
while($arr = $dsql->GetArray())
{
	$starttime = $arr['starttime'];
	$endtime = $arr['endtime'];
	//����һ�������У������Ѿ����е�����
	if($arr['lastrun'] > $starttime && $arr['runtype']==1) continue;
	//�������趨���������ʱ��
	if($endtime!=0 && $endtime < $ntime) continue;
	//δ�ﵽ����ʼʱ�������
	if($starttime!=0 && $ntime < $starttime) continue;
	
	$dotime = GetMkTime($ndate.' '.$arr['runtime'].':00');
	$limittime = $daylimit * $arr['freq'];
	
	$isplay = false;
	//�жϷ���ִ������������
	if($arr['freq'] > 1 && $ntime - $arr['lastrun'] > $limittime )
	{
		$isplay = true;
	}
	else
	{
		$ndateInt = intval( str_replace('-', '', $ndate) );
		$rdateInt = intval( str_replace('-', '', GetDateMk($arr['lastrun'])) );
		list($th, $tm) = explode(':', $arr['runtime']);
		if($ndateInt > $rdateInt 
		&& ($hh > $th || ($hh==$th && $mm >= $tm) ) )
		{
			$isplay = true;
		}
	}
	//������ִ������������
	if($isplay)
	{
		$dourl = trim($arr['dourl']);
		if(!file_exists("task/$dourl"))
		{
			echo ($client=='js' ? '' : 'notask');
			exit();
		}
		else
		{
			$getConfigStr = trim($arr['parameter']);
			$getString = '';
			if(ereg('t:', $getConfigStr))
			{
				$getStrings = array();
				$dtp = new DedeTagParse();
				$dtp->SetNameSpace('t', '<', '>');
				$dtp->LoadString($getConfigStr);
				if(is_array($dtp->CTags))
				{
					foreach($dtp->CTags as $k=>$ctag)
					{
						$getString .= ($getString=='' ? '' : '&').$ctag->GetAtt('key').'='.urlencode($ctag->GetAtt('value'));
					}
				}
			}
			$dsql->ExecuteNoneQuery("Update `#@__sys_task` set lastrun='".time()."', sta='����' where id='{$arr['id']}' ");
			if($getString !='' ) $dourl .= '?'.$getString; 
			if($client=='js') header("location:{$cfg_phpurl}/task/{$dourl}");
			else echo "{$cfg_phpurl}/task/{$dourl}";
			exit();
		}
	}
}
echo ($client=='js' ? '' : 'notask');
exit();
?>