<?php
require_once(dirname(__FILE__).'/config.php');
require_once(DEDEINC.'/oxwindow.class.php');
CheckPurview('sys_StringMix');
if(empty($dopost)) $dopost = '';
$templates = empty($templates) ? '' : stripslashes($templates);
$m_file = DEDEDATA.'/template.rand.php';

//----------------------action
$okmsg = '';
//��������
if($dopost=='save')
{
	$fp = fopen($m_file,'w');
	flock($fp,3);
	fwrite($fp,$templates);
	fclose($fp);
	$okmsg = '�ɹ�����������Ϣ AT:('.MyDate('H:i:s', time()).')';
}
//�Ծ��ĵ��������ģ�崦��
else if($dopost=='makeold')
{
	set_time_limit(3600);
	if(!file_exists($m_file))
	{
		AjaxHead();
		echo "�����ļ������ڣ�";
		exit();
	}
	require_once($m_file);
	if($cfg_tamplate_rand==0)
	{
		AjaxHead();
		echo "ϵͳû�����������ģ���ѡ�";
		exit();
	}
	$totalTmp = count($cfg_tamplate_arr) - 1;
	if( $totalTmp < 1 )
	{
		AjaxHead();
		echo "���ģ�����������Ϊ2�������ϣ�";
		exit();
	}
	for($i=0; $i < 10; $i++)
	{
		$temp = $cfg_tamplate_arr[mt_rand(0, $totalTmp)];
		$dsql->ExecuteNoneQuery(" Update `#@__addonarticle` set templet='$temp' where RIGHT(aid, 1)='$i' ");
	}
	AjaxHead();
	echo "ȫ����������ɹ���";
	exit();
}
//���ȫ����ָ��ģ��
else if($dopost=='clearold')
{
	$dsql->ExecuteNoneQuery(" Update `#@__addonarticle` set templet='' ");
	$dsql->ExecuteNoneQuery(" OPTIMIZE TABLE `#@__addonarticle` ");
	AjaxHead();
	echo "ȫ����������ɹ���";
	exit();
}

//-------------------------read
//����
if(empty($templates) && filesize($m_file)>0)
{
	$fp = fopen($m_file,'r');
	$templates = fread($fp,filesize($m_file));
	fclose($fp);
}
$wintitle = "���ģ����ɼ�����";
$wecome_info = "���ģ����ɼ�����";

$msg = "
<link href='img/base.css' rel='stylesheet' type='text/css' />
<script language='javascript' src='js/main.js'></script>
<script language='javascript' src='../include/dedeajax2.js'></script>
<script language='javascript'>
function DoRand(jobname)
{
	ChangeFullDiv('show');
	\$DE('loading').style.display = 'block';
	var myajax = new DedeAjax(\$DE('tmpct'));
	myajax.SendGet2('article_template_rand.php?dopost='+jobname);
	\$DE('loading').style.display = 'none';
	ChangeFullDiv('hide');
}
</script>
<div id='loading' style='z-index:3000;top:160;left:300;position:absolute;display:none;'>
	<img src='img/loadinglit.gif' /> ���Ժ����ڲ�����...
</div>
<table width='98%' align='center'>
<tr>
	<td height='28'>
	�������Ծɵ�����Ӧ�����ģ�����ã������˶Ծ����½��д���(�������ú�ģ����)��
	&nbsp; <a href='#' onclick='DoRand(\"makeold\")'>[<u>����ȫ��</u>]</a>
	&nbsp; <a href='#' onclick='DoRand(\"clearold\")'>[<u>ȡ��ȫ��</u>]</a>
	&nbsp; <span id='tmpct' style='color:red;font-weight:bold'>$okmsg</span>
	</td>
</tr>
<tr>
	<td bgcolor='#F1F9D7'><b>�밴˵���޸����ã�</b></td>
</tr>
<tr>
	<td><textarea name='templates' id='templates' style='width:100%;height:250px'>$templates</textarea></td>
</tr>
</table>";

$win = new OxWindow();
$win->Init('article_template_rand.php','js/blank.js','POST');
$win->AddHidden('dopost','save');
$win->AddTitle("�����ý�������ϵͳĬ�ϵ�����ģ�ͣ����ú󷢲�����ʱ���Զ���ָ����ģ�������ȡһ�����������ʹ�ô˹��ܣ���������Ϊ�ռ��ɣ�");
$win->AddMsgItem($msg);
$winform = $win->GetWindow('ok');
$win->Display();

?>