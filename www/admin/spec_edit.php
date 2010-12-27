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
	$channelid = -1;

	//��ȡ�鵵��Ϣ
	$arcQuery = "Select ch.typename as channelname,ar.membername as rankname,arc.*
    From `#@__archives` arc
    left join `#@__channeltype` ch on ch.id=arc.channel
    left join `#@__arcrank` ar on ar.rank=arc.arcrank where arc.id='$aid' ";
	$arcRow = $dsql->GetOne($arcQuery);
	if(!is_array($arcRow))
	{
		ShowMsg("��ȡ����������Ϣ����!","-1");
		exit();
	}
	$query = "Select * From `#@__channeltype` where id='-1'";
	$cInfos = $dsql->GetOne($query);
	if(!is_array($cInfos))
	{
		ShowMsg("��ȡƵ��������Ϣ����!","javascript:;");
		exit();
	}
	$addRow = $dsql->GetOne("Select * From `#@__addonspec` where aid='$aid'");
	$tags = GetTags($aid);
	include DedeInclude("templets/spec_edit.htm");
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
	
	if(!isset($tags)) $tags = '';
	$channelid= -1;

	//�����Զ����ֶλ��õ���Щ����
	if(!isset($autokey)) $autokey = 0;
	if(!isset($remote)) $remote = 0;
	if(!isset($dellink)) $dellink = 0;
	if(!isset($autolitpic)) $autolitpic = 0;

	//�Ա�������ݽ��д���
	$pubdate = GetMkTime($pubdate);
	$sortrank = AddDay($pubdate,$sortup);
	if($ishtml==0)
	{
		$ismake = -1;
	}
	else
	{
		$ismake = 0;
	}
	$title = cn_substrR($title,$cfg_title_maxlen);
	$shorttitle = cn_substrR($shorttitle,36);
	$color =  cn_substrR($color,7);
	$writer =  cn_substrR($writer,20);
	$source = cn_substrR($source,30);
	$description = cn_substrR($description,$cfg_auot_description);
	$keywords = trim(cn_substrR($keywords,60));
	$filename = trim(cn_substrR($filename,40));
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
				}else{
					if(!isset(${$vs[0]}))
					{
						${$vs[0]} = '';
					}
					${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$arcID);
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
	$inQuery = "update `#@__archives` set
		    typeid='$typeid',
		    sortrank='$sortrank',
		    flag='$flag',
		    ismake='$ismake',
		    arcrank='$arcrank',
		    click='$click',
		    title='$title',
		    color='$color',
		    writer='$writer',
		    source='$source',
		    litpic='$litpic',
		    pubdate='$pubdate',
		    notpost='$notpost',
		    description='$description',
		    keywords='$keywords',
		    shorttitle='$shorttitle',
		    filename='$filename'
		    where id='$id'; ";
	if(!$dsql->ExecuteNoneQuery($inQuery))
	{
		ShowMsg("�������ݿ�archives��ʱ�������飡","-1");
		exit();
	}

	//ר��ڵ��б�
	$arcids = '';
	$notelist = '';
	for($i=1;$i<=$cfg_specnote;$i++)
	{
		if(!empty(${'notename'.$i}))
		{
			$notename = str_replace("'","",trim(${'notename'.$i}));
			$arcid = trim(${'arcid'.$i});
			$col = trim(${'col'.$i});
			$imgwidth = trim(${'imgwidth'.$i});
			$imgheight = trim(${'imgheight'.$i});
			$titlelen = trim(${'titlelen'.$i});
			$infolen = trim(${'infolen'.$i});
			$listtmp = trim(${'listtmp'.$i});
			if(isset(${'noteid'.$i}))
			{
				$noteid = trim(${'noteid'.$i});
			}
			else
			{
				$noteid = $i;
			}
			if(isset(${'isauto'.$i}))
			{
				$isauto = trim(${'isauto'.$i});
			}
			else
			{
				$isauto = 0;
			}
			if(isset(${'keywords'.$i}))
			{
				$keywords = str_replace("'","",trim(${'keywords'.$i}));
			}
			else
			{
				$keywords = "";
			}
			if(!empty(${'typeid'.$i}))
			{
				$ttypeid = trim(${'typeid'.$i});
			}
			else
			{
				$ttypeid = 0;
			}
			if(!empty(${'rownum'.$i}))
			{
				$rownum = trim(${'rownum'.$i});
			}
			else
			{
				$rownum = 0;
			}
			$arcid = ereg_replace("[^0-9,]","",$arcid);
			$ids = explode(",",$arcid);
			$okids = "";
			if(is_array($ids))
			{
				foreach($ids as $mid)
				{
					$mid = trim($mid);
					if($mid=="")
					{
						continue;
					}
					if(!isset($arcids[$mid]))
					{
						if($okids=="")
						{
							$okids .= $mid;
						}
						else
						{
							$okids .= ",".$mid;
						}
						$arcids[$mid] = 1;
					}
				}
			}
			$notelist .= "{dede:specnote imgheight=\\'$imgheight\\' imgwidth=\\'$imgwidth\\'
				infolen=\\'$infolen\\' titlelen=\\'$titlelen\\' col=\\'$col\\' idlist=\\'$okids\\'
				name=\\'$notename\\' noteid=\\'$noteid\\' isauto=\'$isauto\' rownum=\\'$rownum\\'
				keywords=\\'$keywords\\' typeid=\\'$ttypeid\\'}
				$listtmp
				{/dede:specnote}\r\n";
		}
	}

	//���¸��ӱ�
	$inQuery = "update `#@__addonspec` set typeid ='$typeid',note='$notelist'{$inadd_f},templet='$templet' where aid='$id';";
	if(!$dsql->ExecuteNoneQuery($inQuery))
	{
		ShowMsg("�������ݿ⸽�ӱ� addonspec ʱ��������ԭ��","-1");
		exit();
	}

	//����HTML
	UpIndexKey($id,$arcrank,$typeid,$sortrank,$tags);
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
	$msg = "������ѡ����ĺ���������
    <a href='spec_add.php?cid=$typeid'><u>������ר��</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$id."&dopost=editArchives'><u>�鿴����</u></a>
    &nbsp;&nbsp;
    <a href='$artUrl' target='_blank'><u>�鿴ר��</u></a>
    &nbsp;&nbsp;
    <a href='content_s_list.php'><u>�ѷ���ר�����</u></a> ";
	$wintitle = "�ɹ�����һ��ר�⣡";
	$wecome_info = "ר�����::����ר��";
	$win = new OxWindow();
	$win->AddTitle("�ɹ�����ר�⣡");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();
}

?>