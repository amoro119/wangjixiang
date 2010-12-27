<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_Edit,a_AccEdit,a_MyEdit');
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEADMIN."/inc/inc_archives_functions.php");

if(empty($dopost))
{
	$dopost = '';
}
if($dopost!='save')
{
	require_once(DEDEADMIN."/inc/inc_catalog_options.php");
	require_once(DEDEINC."/dedetag.class.php");
	ClearMyAddon();
	$aid = intval($aid);

	//��ȡ�鵵��Ϣ
	$arcQuery = "Select ch.*,arc.* From `#@__arctiny` arc
    left join `#@__channeltype` ch on ch.id=arc.channel where arc.id='$aid' ";

	$cInfos = $dsql->GetOne($arcQuery);
	if(!is_array($cInfos))
	{
		ShowMsg("��Ƶ��ģ����Ϣ����","-1");
		exit();
	}

	$addtable = $cInfos['addtable'];
	$addRow = $dsql->GetOne("Select arc.*,ar.membername as rankname From `$addtable` arc left join `#@__arcrank` ar on ar.rank=arc.arcrank where arc.aid='$aid'");
	$channelid = $cInfos['channel'];
	$tags = GetTags($aid);
	include DedeInclude('templets/archives_sg_edit.htm');
	exit();
}

/*--------------------------------
function __save(){  }
-------------------------------*/
else if($dopost=='save')
{
	require_once(DEDEINC.'/image.func.php');
	require_once(DEDEINC.'/oxwindow.class.php');
	if($typeid==0)
	{
		ShowMsg("��ָ���ĵ�����Ŀ��","-1");
		exit();
	}
	if(empty($channelid))
	{
		ShowMsg("�ĵ�Ϊ��ָ�������ͣ������㷢�����ݵı��Ƿ�Ϸ���","-1");
		exit();
	}
	if(!CheckChannel($typeid,$channelid))
	{
		ShowMsg("����ѡ�����Ŀ�뵱ǰģ�Ͳ��������ѡ���ɫ��ѡ�","-1");
		exit();
	}
	if(!TestPurview('a_Edit'))
	{
		if(TestPurview('a_AccEdit'))
		{
			CheckCatalog($typeid,"�Բ�����û�в�����Ŀ {$typeid} ���ĵ�Ȩ�ޣ�");
		}
		else
		{
			CheckArcAdmin($id,$cuserLogin->getUserID());
		}
	}
	//�Ա�������ݽ��д���
	if(empty($flags)) $flag = '';
	else $flag = join(',',$flags);
	$title = cn_substrR($title,$cfg_title_maxlen);
	$isremote  = (empty($isremote)? 0  : $isremote);
	$serviterm=empty($serviterm)? "" : $serviterm;
	if(!TestPurview('a_Check,a_AccCheck,a_MyCheck'))
	{
		$arcrank = -1;
	}
	$adminid = $cuserLogin->getUserID();
	
	//�����ϴ�������ͼ
	if(empty($ddisremote))
	{
		$ddisremote = 0;
	}
	$litpic = GetDDImage('none',$picname,$ddisremote);
	
	//���������ӱ�����
	$inadd_f = '';
	$inadd_v = '';
	if(!empty($dede_addonfields))
	{
		$addonfields = explode(';',$dede_addonfields);
		$inadd_f = '';
		$inadd_v = '';
		if(is_array($addonfields))
		{
			foreach($addonfields as $v)
			{
				if($v=='')
				{
					continue;
				}
				$vs = explode(',',$v);
				if($vs[1]=='htmltext'||$vs[1]=='textdata') //HTML�ı����⴦��
				{
					${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$litpic,$keywords,$vs[1]);
				}else
				{
					if(!isset(${$vs[0]}))
					{
						${$vs[0]} = '';
					}
					${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$id);
				}
				$inadd_f .= ",`{$vs[0]}` = '".${$vs[0]}."'";
			}
		}
	}
	
	//����ͼƬ�ĵ����Զ�������
	if($litpic!='' && !ereg('p',$flag))
	{
		$flag = ($flag=='' ? 'p' : $flag.',p');
	}

	$cts = $dsql->GetOne("Select addtable From `#@__channeltype` where id='$channelid' ");
	$addtable = trim($cts['addtable']);
	if($addtable!='')
	{
		$iquery = "update `$addtable` set typeid='$typeid',arcrank='$arcrank',title='$title',flag='$flag',litpic='$litpic'{$inadd_f} where aid='$id' ";
		if(!$dsql->ExecuteNoneQuery($iquery))
		{
			ShowMsg("���¸��ӱ� `$addtable`  ʱ��������ԭ��","javascript:;");
			exit();
		}
	}

	//����HTML
	UpIndexKey($id,$arcrank,$typeid,$sortrank,'');
	if($cfg_remote_site=='Y' && $isremote=="1")
	{	
		if($serviterm!=""){
			list($servurl,$servuser,$servpwd) = explode(',',$serviterm);
			$config=array( 'hostname' => $servurl, 'username' => $servuser, 'password' => $servpwd,'debug' => 'TRUE');
		}else{
			$config=array();
		}
		if(!$ftp->connect($config)) exit('Error:None FTP Connection!');
	}

	$artUrl = MakeArt($id,true,true,$isremote);
	if($artUrl=='')
	{
		$artUrl = $cfg_phpurl."/view.php?aid=$id";
	}
	ClearMyAddon($id, $title);
	//���سɹ���Ϣ
	$msg = "
    ������ѡ����ĺ���������
    <a href='archives_sg_add.php?cid=$typeid'><u>�������ĵ�</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$id."&dopost=editArchives'><u>�鿴����</u></a>
    &nbsp;&nbsp;
    <a href='$artUrl' target='_blank'><u>�鿴�ĵ�</u></a>
    &nbsp;&nbsp;
    <a href='catalog_do.php?cid=$typeid&channelid={$channelid}&dopost=listArchives'><u>�����ĵ�</u></a>
    &nbsp;&nbsp;
    <a href='catalog_main.php'><u>��վ��Ŀ����</u></a>
    ";

	$wintitle = "�ɹ������ĵ���";
	$wecome_info = "�ĵ�����::�����ĵ�";
	$win = new OxWindow();
	$win->AddTitle("�ɹ������ĵ���");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();
}

?>