<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('a_New,a_AccNew');
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEADMIN."/inc/inc_archives_functions.php");

if(empty($dopost))
{
	$dopost = '';
}
if($dopost != 'save')
{

	require_once(DEDEINC."/dedetag.class.php");
	require_once(DEDEADMIN."/inc/inc_catalog_options.php");
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
			$channelid = 2;
		}
	}

	//���Ƶ��ģ����Ϣ
	$cInfos = $dsql->GetOne(" Select * From  `#@__channeltype` where id='$channelid' ");
	$channelid = $cInfos['id'];
	//��ȡ�������id��ȷ����ǰȨ��
	$maxWright = $dsql->GetOne("SELECT COUNT(*) AS cc FROM #@__archives");
	include DedeInclude("templets/album_add.htm");
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
	if(!isset($formhtml)) $formhtml = 0;
	if(!isset($formzip)) $formzip = 0;
	if(!isset($ddisfirst)) $ddisfirst = 0;
	if(!isset($delzip)) $delzip = 0;
	if(empty($click)) $click = ($cfg_arc_click=='-1' ? mt_rand(50, 200) : $cfg_arc_click);

	if($typeid==0)
	{
		ShowMsg("��ָ���ĵ�����Ŀ��", "-1");
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
	$ismake = $ishtml==0 ? -1 : 0;
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
	if(empty($ddisremote))
	{
		$ddisremote = 0;
	}
	$litpic = GetDDImage('none',$picname,$ddisremote);

	//ʹ�õ�һ��ͼ��Ϊ����ͼ
	if($ddisfirst==1 && $litpic=='')
	{
		if(isset($imgurl1))
		{
			$litpic = GetDDImage('ddfirst', $imgurl1, $isrm);
		}
	}
	

	//�����ĵ�ID
	$arcID = GetIndexKey($arcrank,$typeid,$sortrank,$channelid,$senddate,$adminid);
	if(empty($arcID))
	{
		ShowMsg("�޷��������������޷����к���������","-1");
		exit();
	}

	$imgurls = "{dede:pagestyle maxwidth='$maxwidth' pagepicnum='$pagepicnum' ddmaxwidth='$ddmaxwidth' row='$row' col='$col' value='$pagestyle'/}\r\n";
	$hasone = false;

	//������������ϸ��Ƶ�ͼƬ
	/*---------------------
	function _getformhtml()
	------------------*/
	if($formhtml==1)
	{
		$imagebody = stripslashes($imagebody);
		$imgurls .= GetCurContentAlbum($imagebody,$copysource,$litpicname);
		if($ddisfirst==1 && $litpic=='' && !empty($litpicname))
		{
			$litpic = $litpicname;
			$hasone = true;
		}
	}
	/*---------------------
	function _getformzip()
	�����ZIP�н�ѹ��ͼƬ
	---------------------*/
	if($formzip==1)
	{
		include_once(DEDEINC."/zip.class.php");
		include_once(DEDEADMIN."/file_class.php");
		$zipfile = $cfg_basedir.str_replace($cfg_mainsite,'',$zipfile);
		$tmpzipdir = DEDEDATA.'/ziptmp/'.cn_substr(md5(ExecTime()),16);
		$ntime = time();
		if(file_exists($zipfile))
		{
			@mkdir($tmpzipdir,$GLOBALS['cfg_dir_purview']);
			@chmod($tmpzipdir,$GLOBALS['cfg_dir_purview']);
			$z = new zip();
			$z->ExtractAll($zipfile,$tmpzipdir);
			$fm = new FileManagement();
			$imgs = array();
			$fm->GetMatchFiles($tmpzipdir,"jpg|png|gif",$imgs);
			$i = 0;
			foreach($imgs as $imgold)
			{
				$i++;
				$savepath = $cfg_image_dir."/".MyDate("Y-m",$ntime);
				CreateDir($savepath);
				$iurl = $savepath."/".MyDate("d",$ntime).dd2char(MyDate("His",$ntime).'-'.$adminid."-{$i}".mt_rand(1000,9999));
				$iurl = $iurl.substr($imgold,-4,4);
				$imgfile = $cfg_basedir.$iurl;
				copy($imgold,$imgfile);
				unlink($imgold);

				if(is_file($imgfile))
				{
					$litpicname = $pagestyle > 2 ? GetImageMapDD($iurl,$cfg_ddimg_width) : $iurl;
					//ָ������ȡ��һ��Ϊ����ͼ�����ǿ��ʹ�õ�һ������ͼ
					if($i=='1')
					{
						if(!$hasone && $ddisfirst==1 && $litpic=='' && empty($litpicname))
						{
							$litpicname = GetImageMapDD($iurl,$cfg_ddimg_width);
						}
					}
					$info = '';
					$imginfos = GetImageSize($imgfile,$info);
					$imgurls .= "{dede:img ddimg='$litpicname' text='' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";

					//��ͼƬ��Ϣ���浽ý���ĵ���������
					$inquery = "
                   INSERT INTO #@__uploads(title,url,mediatype,width,height,playtime,filesize,uptime,mid)
                    VALUES ('{$title}','{$iurl}','1','".$imginfos[0]."','".$imginfos[1]."','0','".filesize($imgfile)."','".$ntime."','$adminid');
                 ";
					$dsql->ExecuteNoneQuery($inquery);
					$fid = $dsql->GetLastID();
					AddMyAddon($fid, $iurl);
					
					WaterImg($imgfile, 'up');

					if(!$hasone && $ddisfirst==1 && $litpic=='')
					{
						if(empty($litpicname))
						{
							$litpicname = $iurl;
							$litpicname = GetImageMapDD($iurl, $cfg_ddimg_width);
						}
						$litpic = $litpicname;
						$hasone = true;
					}
				}
			}
			if($delzip==1)
			{
				unlink($zipfile);
			}
			$fm->RmDirFiles($tmpzipdir);
		}
	}
	/*---------------------
	function _getformupload()
	ͨ��swfupload�����ϴ���ͼƬ
	---------------------*/
	if(is_array($_SESSION['bigfile_info']))
	{
		foreach($_SESSION['bigfile_info'] as $k=>$v)
		{
			$truefile = $cfg_basedir.$v;
			if(strlen($v)<2 || !file_exists($truefile)) continue;
			$info = '';
			$imginfos = GetImageSize($truefile, $info);
			$litpicname = $pagestyle > 2 ? GetImageMapDD($v, $cfg_ddimg_width) : '';
			if(!$hasone && $ddisfirst==1 && $litpic=='')
			{
				 $litpic = empty($litpicname) ? GetImageMapDD($v, $cfg_ddimg_width) : $litpicname;
				 $hasone = true;
			}
			$imginfo =  !empty(${'picinfook'.$k}) ? ${'picinfook'.$k} : '';
			$imgurls .= "{dede:img ddimg='$v' text='$imginfo' width='".$imginfos[0]."' height='".$imginfos[1]."'} $v {/dede:img}\r\n";
		}
	}

	$imgurls = addslashes($imgurls);
	
	//����body�ֶ��Զ�ժҪ���Զ���ȡ����ͼ��
	$body = AnalyseHtmlBody($body,$description,$litpic,$keywords,'htmltext');

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
	//������������
	$query = "INSERT INTO `#@__archives`(id,typeid,typeid2,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,
     color,writer,source,litpic,pubdate,senddate,mid,notpost,description,keywords,filename,dutyadmin,weight)
    VALUES ('$arcID','$typeid','$typeid2','$sortrank','$flag','$ismake','$channelid','$arcrank','$click','$money','$title','$shorttitle',
    '$color','$writer','$source','$litpic','$pubdate','$senddate','$adminid','$notpost','$description','$keywords','$filename','$adminid','$weight'); ";
	if(!$dsql->ExecuteNoneQuery($query))
	{
		$gerr = $dsql->GetError();
		$dsql->ExecuteNoneQuery(" Delete From `#@__arctiny` where id='$arcID' ");
		ShowMsg("�����ݱ��浽���ݿ����� `#@__archives` ʱ������������Ϣ�ύ��DedeCms�ٷ���".str_replace('"','',$gerr),"javascript:;");
		exit();
	}

	//���븽�ӱ�
	$cts = $dsql->GetOne("Select addtable From `#@__channeltype` where id='$channelid' ");
	$addtable = trim($cts['addtable']);
	if(empty($addtable))
	{
		$dsql->ExecuteNoneQuery("Delete From `#@__archives` where id='$arcID'");
		$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where id='$arcID'");
		ShowMsg("û�ҵ���ǰģ��[{$channelid}]��������Ϣ���޷���ɲ�������","javascript:;");
		exit();
	}
	$useip = GetIP();
	$query = "INSERT INTO `$addtable`(aid,typeid,redirecturl,userip,pagestyle,maxwidth,imgurls,row,col,isrm,ddmaxwidth,pagepicnum,body{$inadd_f})
         Values('$arcID','$typeid','$redirecturl','$useip','$pagestyle','$maxwidth','$imgurls','$row','$col','$isrm','$ddmaxwidth','$pagepicnum','$body'{$inadd_v}); ";
	if(!$dsql->ExecuteNoneQuery($query))
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
	$artUrl = MakeArt($arcID,true,true,$isremote);
	if($artUrl=='')
	{
		$artUrl = $cfg_phpurl."/view.php?aid=$arcID";
	}
	ClearMyAddon($arcID, $title);
	//���سɹ���Ϣ
	$msg = "
    ������ѡ����ĺ���������
    <a href='album_add.php?cid=$typeid'><u>��������ͼƬ</u></a>
    &nbsp;&nbsp;
    <a href='archives_do.php?aid=".$arcID."&dopost=editArchives'><u>����ͼ��</u></a>
    &nbsp;&nbsp;
    <a href='$artUrl' target='_blank'><u>Ԥ���ĵ�</u></a>
    &nbsp;&nbsp;
    <a href='catalog_do.php?cid=$typeid&dopost=listArchives'><u>�ѷ���ͼƬ����</u></a>
    &nbsp;&nbsp;
    $backurl
   ";
  $msg = "<div style=\"line-height:36px;height:36px\">{$msg}</div>".GetUpdateTest();
    
	$wintitle = "�ɹ�����һ��ͼ����";
	$wecome_info = "���¹���::����ͼ��";
	$win = new OxWindow();
	$win->AddTitle("�ɹ�����һ��ͼ����");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();
}
?>