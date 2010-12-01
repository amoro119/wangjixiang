<?php
require_once(dirname(__FILE__).'/config.php');
if(empty($dopost))
{
	ShowMsg("�Բ�����ָ����Ŀ������","catalog_main.php");
	exit();
}
$cid = empty($cid) ? 0 : intval($cid);
$channelid = empty($channelid) ? 0 : intval($channelid);

/*--------------------------
//�����ĵ�
function addArchives();
---------------------------*/
if($dopost=="addArchives")
{
	//Ĭ�����µ��÷�����
	if(empty($cid) && empty($channelid))
	{
		header("location:article_add.php");
		exit();
	}
	if(!empty($channelid))
	{
		//����ģ�͵��÷�����
		$row = $dsql->GetOne("Select addcon from #@__channeltype where id='$channelid'");
	}
	else
	{
		//������Ŀ���÷�����
		$row = $dsql->GetOne("Select ch.addcon from `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id='$cid' ");
	}
	$gurl = $row["addcon"];
	if($gurl=="")
	{
		ShowMsg("�Բ�����ָ����Ŀ��������","catalog_main.php");
		exit();
	}

	//��ת�����ݲ���
	header("location:{$gurl}?channelid={$channelid}&cid={$cid}");
	exit();
}

/*--------------------------
//�����ĵ�
function listArchives();
---------------------------*/
else if($dopost=="listArchives")
{
	if(!empty($gurl))
	{
		if(empty($arcrank))
		{
			$arcrank = '';
		}
		$gurl = str_replace('..','',$gurl);
		header("location:{$gurl}?arcrank={$arcrank}&cid={$cid}");
		exit();
	}
	if($cid>0)
	{
		$row = $dsql->GetOne("Select #@__arctype.typename,#@__channeltype.typename as channelname,#@__channeltype.id,#@__channeltype.mancon from #@__arctype left join #@__channeltype on #@__channeltype.id=#@__arctype.channeltype where #@__arctype.id='$cid'");
		$gurl = $row["mancon"];
		$channelid = $row["id"];
		$typename = $row["typename"];
		$channelname = $row["channelname"];
		if($gurl=="")
		{
			ShowMsg("�Բ�����ָ����Ŀ��������","catalog_main.php");
			exit();
		}
	}
	else if($channelid>0)
	{

		$row = $dsql->GetOne("Select typename,id,mancon from #@__channeltype where id='$channelid'");
		$gurl = $row["mancon"];
		$channelid = $row["id"];
		$typename = "";
		$channelname = $row["typename"];
	}
	
	if(empty($gurl))
	{
		$gurl = 'content_list.php';
	}
	header("location:{$gurl}?channelid={$channelid}&cid={$cid}");
	exit();
}

/*--------------------------
//���ͨ��ģ��Ŀ¼
function viewTempletDir();
---------------------------*/
else if($dopost=="viewTemplet")
{
	header("location:tpl.php?path=/".$cfg_df_style);
	exit();
}

/*--------------------------
//���Բ�����
function GoGuestBook();
---------------------------*/
else if($dopost=="guestbook")
{
	ShowMsg("������ת�����Ա�&gt;&gt;", "{$cfg_phpurl}/guestbook.php?gotopagerank=admin");
	exit();
}

/*------------------------
�������ҳ�����Ŀ
function ViewSgPage()
------------------------*/
else if($dopost=="viewSgPage")
{
	require_once(DEDEINC."/arc.listview.class.php");
	$lv = new ListView($cid);
	$pageurl = $lv->MakeHtml();
	ShowMsg("���»��壬���Ժ�...",$pageurl);
	exit();
}

/*------------------------
������Ŀ����˳��
function upRank()
------------------------*/
else if($dopost=="upRank")
{
	//���Ȩ�����
	CheckPurview('t_Edit,t_AccEdit');

	//�����Ŀ�������
	CheckCatalog($cid,"����Ȩ���ı���Ŀ��");
	$row = $dsql->GetOne("Select reid,sortrank From #@__arctype where id='$cid'");
	$reid = $row['reid'];
	$sortrank = $row['sortrank'];
	$row = $dsql->GetOne("Select sortrank From #@__arctype where sortrank<=$sortrank And reid=$reid order by sortrank desc ");
	if(is_array($row))
	{
		$sortrank = $row['sortrank']-1;
		$dsql->ExecuteNoneQuery("update #@__arctype set sortrank='$sortrank' where id='$cid'");
	}
	UpDateCatCache();
	ShowMsg("�����ɹ�������Ŀ¼...","catalog_main.php");
	exit();
}
else if($dopost=="upRankAll")
{
	//���Ȩ�����
	CheckPurview('t_Edit');
	$row = $dsql->GetOne("Select id From #@__arctype order by id desc");
	if(is_array($row))
	{
		$maxID = $row['id'];
		for($i=1;$i<=$maxID;$i++)
		{
			if(isset(${'sortrank'.$i}))
			{
				$dsql->ExecuteNoneQuery("Update #@__arctype set sortrank='".(${'sortrank'.$i})."' where id='{$i}';");
			}
		}
	}
	UpDateCatCache();
	ShowMsg("�����ɹ������ڷ���...","catalog_main.php");
	exit();
}

/*--------------------------
//������Ŀ����
function UpCatlogCache();
---------------------------*/
else if($dopost=="upcatcache")
{
	UpDateCatCache();
	$sql = " TRUNCATE TABLE `#@__arctiny`";
	$dsql->executenonequery($sql);
	
	//������ͨģ��΢����
	$sql = "insert into `#@__arctiny`(id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid)  
	        Select id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid from `#@__archives` ";
	$dsql->executenonequery($sql);
	
	//���뵥��ģ��΢����
	$dsql->SetQuery("Select id,addtable From `#@__channeltype` where id < -1 ");
	$dsql->Execute();
	$doarray = array();
	while($row = $dsql->GetArray())
	{
		$tb = str_replace('#@__', $cfg_dbprefix, $row['addtable']);
		if(empty($tb) || isset($doarray[$tb]) )
		{
			continue;
		}
		else
		{
			$sql = "insert into `#@__arctiny`(id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid)  
			        Select aid, typeid, 0, arcrank, channel, senddate, 0, mid from `$tb` ";
			$rs = $dsql->executenonequery($sql); 
			$doarray[$tb]  = 1;
		}
	}
	
	ShowMsg("�����ɹ������ڷ���...","catalog_main.php");
	exit();
}

/*---------------------
��ȡJS�ļ�
function GetJs
----------------------*/
else if($dopost=="GetJs")
{
	header("location:makehtml_js.php");
	exit();
}

/*-----------
������������
function GetSunListsMenu();
-----------*/
else if($dopost=="GetSunListsMenu")
{
	$userChannel = $cuserLogin->getUserChannel();
	require_once(DEDEINC."/typeunit.class.menu.php");
	AjaxHead();
	PutCookie('lastCidMenu',$cid,3600*24,"/");
	$tu = new TypeUnit($userChannel);
	$tu->LogicListAllSunType($cid,"��");
}

/*-----------
������������
function GetSunLists();
-----------*/
else if($dopost=="GetSunLists")
{
	require_once(DEDEINC."/typeunit.class.admin.php");
	AjaxHead();
	PutCookie('lastCid',$cid,3600*24,"/");
	$tu = new TypeUnit();
	$tu->dsql = $dsql;
	echo "    <table width='100%' border='0' cellspacing='0' cellpadding='0'>\r\n";
	$tu->LogicListAllSunType($cid,"��");
	echo "    </table>\r\n";
	$tu->Close();
}
/*----------------
�ϲ���Ŀ
function unitCatalog() { }
-----------------*/
else if($dopost == 'unitCatalog')
{
	CheckPurview('t_Move');
	require_once(DEDEINC.'/oxwindow.class.php');
	require_once(DEDEINC.'/typelink.class.php');
	require_once(DEDEINC.'/channelunit.func.php');
	if(empty($nextjob))
	{
		$typeid = isset($typeid) ? intval($typeid) : 0;
		$row = $dsql->GetOne("Select count(*) as dd From `#@__arctype` where reid='$typeid' ");
		$tl = new TypeLink($typeid);
		$typename = $tl->TypeInfos['typename'];
		$reid = $tl->TypeInfos['reid'];
		$channelid = $tl->TypeInfos['channeltype'];
		if(!empty($row['dd']))
		{
			ShowMsg("��Ŀ�� $typename($typeid) ������Ŀ�����ܽ��кϲ�������", '-1');
			exit();
		}
		$typeOptions = $tl->GetOptionArray(0, 0, $channelid);
		$wintitle = '�ϲ���Ŀ';
		$wecome_info = "<a href='catalog_main.php'>��Ŀ����</a> &gt;&gt; �ϲ���Ŀ";
		$win = new OxWindow();
		$win->Init('catalog_do.php', 'js/blank.js', 'POST');
		$win->AddHidden('dopost', 'unitCatalog');
		$win->AddHidden('typeid', $typeid);
		$win->AddHidden('channelid', $channelid);
		$win->AddHidden('nextjob', 'unitok');
		$win->AddTitle("�ϲ�Ŀ¼ʱ����ɾ��ԭ������ĿĿ¼���ϲ������ֶ�����Ŀ����Ŀ���ĵ�HTML���б�HTML��");
		$win->AddItem('��ѡ�����Ŀ�ǣ�', "<font color='red'>$typename($typeid)</font>");
		$win->AddItem('��ϣ���ϲ����Ǹ���Ŀ��', "<select name='unittype'>\r\n{$typeOptions}\r\n</select>");
		$win->AddItem('ע�����', '��Ŀ�������¼�����Ŀ��ֻ�����Ӽ������߼���ͬ����ͬ�����������');
		$winform = $win->GetWindow('ok');
		$win->Display();
		exit();
	}
	else
	{
		if($typeid==$unittype)
		{
			ShowMsg("���ģ�ͬһ��Ŀ��κϲ��������۸����", '-1');
			exit();
		}
		if(IsParent($unittype, $typeid))
		{
			ShowMsg('���ܴӸ���ϲ������࣡', 'catalog_main.php');
			exit();
		}
		$row = $dsql->GetOne("Select addtable From `#@__channeltype` where id='$channelid' ");
		$addtable = (empty($row['addtable']) ? '#@__addonarticle' : $row['addtable'] );
		$dsql->ExecuteNoneQuery("Update `#@__arctiny` set typeid='$unittype' where typeid='$typeid' ");
		$dsql->ExecuteNoneQuery("Update `#@__feedback` set typeid='$unittype' where typeid='$typeid' ");
		$dsql->ExecuteNoneQuery("Update `#@__archives` set typeid='$unittype' where typeid='$typeid' ");
		$dsql->ExecuteNoneQuery("Update `#@__archives` set typeid2='$unittype' where typeid2='$typeid' ");
		$dsql->ExecuteNoneQuery("Update `#@__addonspec` set typeid='$unittype' where typeid='$typeid' ");
		$dsql->ExecuteNoneQuery("Update `$addtable` set typeid='$unittype' where typeid='$typeid' ");
		$dsql->ExecuteNoneQuery("Delete From `#@__arctype` where id='$typeid' ");
		UpDateCatCache();
		ShowMsg('�ɹ��ϲ�ָ����Ŀ��', 'catalog_main.php');
		exit();
	}
}
/*----------------
�ƶ���Ŀ
function moveCatalog() { }
-----------------*/
else if($dopost == 'moveCatalog')
{
	CheckPurview('t_Move');
	require_once(DEDEINC.'/oxwindow.class.php');
	require_once(DEDEINC.'/typelink.class.php');
	require_once(DEDEINC.'/channelunit.func.php');
	if(empty($nextjob))
	{
		$tl = new TypeLink($typeid);
		$typename = $tl->TypeInfos['typename'];
		$reid = $tl->TypeInfos['reid'];
		$channelid = $tl->TypeInfos['channeltype'];
		$typeOptions = $tl->GetOptionArray(0,0,$channelid);
		$wintitle = "�ƶ���Ŀ";
		$wecome_info = "<a href='catalog_main.php'>��Ŀ����</a> &gt;&gt; �ƶ���Ŀ";
		$win = new OxWindow();
		$win->Init('catalog_do.php', 'js/blank.js', 'POST');
		$win->AddHidden('dopost', 'moveCatalog');
		$win->AddHidden('typeid', $typeid);
		$win->AddHidden('channelid', $channelid);
		$win->AddHidden('nextjob', 'unitok');
		$win->AddTitle("�ƶ�Ŀ¼ʱ����ɾ��ԭ���Ѵ������б��ƶ��������¶���Ŀ����HTML��");
		$win->AddItem('��ѡ�����Ŀ�ǣ�',"$typename($typeid)");
		$win->AddItem('��ϣ���ƶ����Ǹ���Ŀ��',"<select name='movetype'>\r\n<option value='0'>�ƶ�Ϊ������Ŀ</option>\r\n$typeOptions\r\n</select>");
		$win->AddItem('ע�����','������Ӹ����ƶ����Ӽ�Ŀ¼��ֻ�����Ӽ������߼���ͬ����ͬ�����������');
		$winform = $win->GetWindow('ok');
		$win->Display();
		exit();
	}
	else
	{
		if($typeid==$movetype)
		{
			ShowMsg('�ƶԶ����Ŀ��λ����ͬ��', 'catalog_main.php');
			exit();
		}
		if(IsParent($movetype, $typeid))
		{
			ShowMsg('���ܴӸ����ƶ������࣡', 'catalog_main.php');
			exit();
		}
		$dsql->ExecuteNoneQuery(" Update `#@__arctype` set reid='$movetype' where id='$typeid' ");
		UpDateCatCache();
		ShowMsg('�ɹ��ƶ�Ŀ¼��', 'catalog_main.php');
		exit();
	}
}
?>