<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_New,a_AccNew');
require_once(DEDEINC.'/customfields.func.php');
require_once(DEDEADMIN.'/inc/inc_archives_functions.php');
if(empty($dopost)) $dopost = '';

if($dopost != 'save')
{
	require_once(DEDEINC.'/dedetag.class.php');
	require_once(DEDEADMIN.'/inc/inc_catalog_options.php');
	ClearMyAddon();
	$channelid = empty($channelid) ? 0 : intval($channelid);
	$cid = empty($cid) ? 0 : intval($cid);

	//���Ƶ��ģ��ID
	if($cid>0 && $channelid==0)
	{
		$row = $dsql->GetOne("Select channeltype From `#@__arctype` where id='$cid'; ");
		$channelid = $row['channeltype'];
	}
	else
	{
		if($channelid==0)
		{
			$channelid = 1;
		}
	}

	$softconfig = $dsql->GetOne("select * From `#@__softconfig` ");
	//���Ƶ��ģ����Ϣ
	$cInfos = $dsql->GetOne(" Select * From  `#@__channeltype` where id='$channelid' ");
	$channelid = $cInfos['id'];
	//��ȡ�������id��ȷ����ǰȨ��
	$maxWright = $dsql->GetOne("SELECT COUNT(*) AS cc FROM #@__archives");
	include DedeInclude("templets/soft_add.htm");
	exit();
}
/*--------------------------------
function __save(){  }
-------------------------------*/
else if($dopost=='save')
{
	require_once(DEDEINC.'/image.func.php');
	require_once(DEDEINC.'/oxwindow.class.php');
	
	$flag = isset($flags) ? join(',',$flags) : '';
	$notpost = isset($notpost) && $notpost == 1 ? 1: 0;
	if(empty($click)) $click = ($cfg_arc_click=='-1' ? mt_rand(50, 200) : $cfg_arc_click);
	
	if(!isset($typeid2)) $typeid2 = 0;
	if(!isset($autokey)) $autokey = 0;
	if(!isset($remote)) $remote = 0;
	if(!isset($dellink)) $dellink = 0;
	if(!isset($autolitpic)) $autolitpic = 0;

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
	if(!CheckChannel($typeid,$channelid) )
	{
		ShowMsg("����ѡ�����Ŀ�뵱ǰģ�Ͳ��������ѡ���ɫ��ѡ�","-1");
		exit();
	}
	if(!TestPurview('a_New'))
	{
		CheckCatalog($typeid,"�Բ�����û�в�����Ŀ {$typeid} ��Ȩ�ޣ�");
	}

	//�Ա�������ݽ��д���
	if(empty($writer))$writer=$cuserLogin->getUserName();
	if(empty($source))$source='δ֪';
	$pubdate = GetMkTime($pubdate);
	$senddate = time();
	$sortrank = AddDay($pubdate,$sortup);
	
	if($ishtml==0) $ismake = -1;
	else $ismake = 0;
	
	if(empty($click)) $click = ($cfg_arc_click=='-1' ? mt_rand(50, 200) : $cfg_arc_click);

	$title = ereg_replace('"', '��', $title);
	$title = cn_substrR($title,$cfg_title_maxlen);
	$shorttitle = cn_substrR($shorttitle,36);
	$color =  cn_substrR($color,7);
	$writer =  cn_substrR($writer,20);
	$source = cn_substrR($source,30);
	$description = cn_substrR($description,$cfg_auot_description);
	$keywords = cn_substrR($keywords,60);
	$filename = trim(cn_substrR($filename,40));
	$userip = GetIP();
  $isremote  = (empty($isremote)? 0  : $isremote);
	$serviterm=empty($serviterm)? "" : $serviterm;
	if(!TestPurview('a_Check,a_AccCheck,a_MyCheck'))
	{
		$arcrank = -1;
	}
	$adminid = $cuserLogin->getUserID();

	//�����ϴ�������ͼ
	if(empty($ddisremote)) $ddisremote = 0;

	$litpic = GetDDImage('none',$picname,$ddisremote);

	//�����ĵ�ID
	$arcID = GetIndexKey($arcrank,$typeid,$sortrank,$channelid,$senddate,$adminid);
	if(empty($arcID))
	{
		ShowMsg("�޷��������������޷����к���������","-1");
		exit();
	}

	//����body�ֶ��Զ�ժҪ���Զ���ȡ����ͼ��
	$body = AnalyseHtmlBody($body, $description, $litpic, $keywords, 'htmltext');

	//���������ӱ�����
	$inadd_f = '';
	$inadd_v = '';
	if(!empty($dede_addonfields))
	{
		$addonfields = explode(';', $dede_addonfields);
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
				if(!isset(${$vs[0]}))
				{
					${$vs[0]} = '';
				}
				else if($vs[1]=='htmltext'||$vs[1]=='textdata') //HTML�ı����⴦��
				{
					${$vs[0]} = AnalyseHtmlBody(${$vs[0]},$description,$litpic,$keywords,$vs[1]);
				}
				else
				{
					if(!isset(${$vs[0]}))
					{
						${$vs[0]} = '';
					}
					${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$arcID);
				}
				$inadd_f .= ','.$vs[0];
				$inadd_v .= " ,'".${$vs[0]}."' ";
			}
		}
	}

	//����ͼƬ�ĵ����Զ�������
	if($litpic!='' && !ereg('p',$flag))
	{
		$flag = ($flag=='' ? 'p' : $flag.',p');
	}
	if($redirecturl!='' && !ereg('j',$flag))
	{
		$flag = ($flag=='' ? 'j' : $flag.',j');
	}

	//��ת��ַ���ĵ�ǿ��Ϊ��̬
	if(ereg('j', $flag)) $ismake = -1;
	//���浽����
	$inQuery = "INSERT INTO `#@__archives`(id,typeid,typeid2,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,
    color,writer,source,litpic,pubdate,senddate,mid,notpost,description,keywords,filename,dutyadmin,weight)
    VALUES ('$arcID','$typeid','$typeid2','$sortrank','$flag','$ismake','$channelid','$arcrank','$click','$money','$title','$shorttitle',
    '$color','$writer','$source','$litpic','$pubdate','$senddate','$adminid','$notpost','$description','$keywords','$filename','$adminid','$weight');";
	if(!$dsql->ExecuteNoneQuery($inQuery))
	{
		$gerr = $dsql->GetError();
		$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
		ShowMsg("�����ݱ��浽���ݿ����� `#@__archives` ʱ������������Ϣ�ύ��DedeCms�ٷ���".str_replace('"','',$gerr),"javascript:;");
		exit();
	}

	//��������б�
	$urls = '';
	
	//�������Ӵ���
	$softurl1 = stripslashes($softurl1);
	$nsoftsize = '';
	if($softurl1 != '')
	{
		$urls .= "{dede:link islocal='1' text='{$servermsg1}'} $softurl1 {/dede:link}\r\n";
		$autosize = empty($autosize) ? false : true;
		if($autosize && empty($softsize))
		{
			$nsoftsize = @filesize($cfg_basedir.$softurl1);
			if(empty($nsoftsize)) $nsoftsize = 'δ֪';
			else
			{
				$nsoftsize = trim(sprintf("%0.2f", $nsoftsize / 1024 / 1024));
				$nsoftsize = $nsoftsize." MB";
			}
		}
	}
	
	//�����С
	if(!empty($nsoftsize)) $softsize = $nsoftsize;
	else if(empty($softsize)) $softsize = 'δ֪';
	else $softsize = $softsize.' '.$unit;
	
	//�������Ӵ���
	for($i=2; $i<=30; $i++)
	{
		if(!empty(${'softurl'.$i}))
		{
			$forconfig = empty(${'forconfig'.$i}) ? false : true;
			if($forconfig)
			{
				if(empty(${'need'.$i})) continue;
				$serverUrl = stripslashes(${'softurlfirst'.$i});
				$serverUrl = ereg_replace("/$", "", $serverUrl);
				$softurl = stripslashes(${'softurl'.$i});
				if( cn_substr($softurl, 1) != '/' ) $softurl = '/'.$softurl;
				$softurl = $serverUrl.$softurl;
			}
			else
			{
				$softurl = stripslashes(${'softurl'.$i});
			}
			$servermsg = str_replace("'", "", stripslashes(${'servermsg'.$i}));
			if($servermsg=='') $servermsg = '���ص�ַ'.$i;
			if($softurl != 'http://')
			{
				$urls .= "{dede:link text='$servermsg'} $softurl {/dede:link}\r\n";
			}
		}
	}
	$urls = addslashes($urls);

	//���浽���ӱ�
	$cts = $dsql->GetOne("Select addtable From `#@__channeltype` where id='$channelid' ");
	$addtable = trim($cts['addtable']);
	if(empty($addtable))
	{
		$dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$arcID'");
		$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
		ShowMsg("û�ҵ���ǰģ��[{$channelid}]��������Ϣ���޷���ɲ�������","javascript:;");
		exit();
	}
	$daccess = isset($daccess) && is_numeric($daccess) ? $daccess : 0;
	$useip = GetIP();
	$inQuery = "INSERT INTO `$addtable`(aid,typeid,redirecturl,userip,filetype,language,softtype,accredit,
    os,softrank,officialUrl,officialDemo,softsize,softlinks,introduce,daccess,needmoney{$inadd_f})
    VALUES ('$arcID','$typeid','$redirecturl','$useip','$filetype','$language','$softtype','$accredit',
    '$os','$softrank','$officialUrl','$officialDemo','$softsize','$urls','$body','$daccess','$needmoney'{$inadd_v});";
	if(!$dsql->ExecuteNoneQuery($inQuery))
	{
		$gerr = $dsql->GetError();
		$dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$arcID'");
		$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
		ShowMsg("�����ݱ��浽���ݿ⸽�ӱ� `{$addtable}` ʱ������������Ϣ�ύ��DedeCms�ٷ���".str_replace('"','',$gerr),"javascript:;");
		exit();
	}

	//����HTML
	InsertTags($tags,$arcID);
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

	$arcUrl = MakeArt($arcID,true,true,$isremote);
	if($arcUrl=='')
	{
		$arcUrl = $cfg_phpurl."/view.php?aid=$arcID";
	}
	ClearMyAddon($arcID, $title);
	//���سɹ���Ϣ
	$msg = "
    ������ѡ����ĺ���������
    <a href='soft_add.php?cid=$typeid'><u>�����������</u></a>
    &nbsp;&nbsp;
    <a href='$arcUrl' target='_blank'><u>�鿴���</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$arcID."&dopost=editArchives'><u>�������</u></a>
    &nbsp;&nbsp;
    <a href='catalog_do.php?cid=$typeid&dopost=listArchives'><u>�ѷ����������</u></a>
    &nbsp;&nbsp;
    <a href='catalog_main.php'><u>��վ��Ŀ����</u></a>
   ";
  $msg = "<div style=\"line-height:36px;height:36px\">{$msg}</div>".GetUpdateTest();

	$wintitle = "�ɹ�����һ�������";
	$wecome_info = "���¹���::�������";
	$win = new OxWindow();
	$win->AddTitle("�ɹ����������");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();
}

?>