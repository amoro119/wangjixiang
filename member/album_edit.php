<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
if($cfg_mb_lit=='Y')
{
	ShowMsg("����ϵͳ�����˾�����Ա�ռ䣬����ʵĹ��ܲ����ã�","-1");
	exit();
}
if($cfg_mb_album=='N')
{
	ShowMsg("�Բ�������ϵͳ�ر���ͼ�����ܣ�����ʵĹ��ܲ����ã�","-1");
	exit();
}
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/customfields.func.php");
require_once(DEDEMEMBER."/inc/inc_catalog_options.php");
require_once(DEDEMEMBER."/inc/inc_archives_functions.php");
$channelid = isset($channelid) && is_numeric($channelid) ? $channelid : 2;
$aid = isset($aid) && is_numeric($aid) ? $aid : 0;
$menutype = 'content';
if(empty($formhtml))
{
	$formhtml = 0;
}

/*-------------
function _ShowForm(){  }
--------------*/
if(empty($dopost))
{
	//��ȡ�鵵��Ϣ
	$arcQuery = "Select arc.*,ch.addtable,ch.fieldset,ch.arcsta
       From `#@__archives` arc left join `#@__channeltype` ch on ch.id=arc.channel
       where arc.id='$aid' And arc.mid='".$cfg_ml->M_ID."'; ";
	$row = $dsql->GetOne($arcQuery);
	if(!is_array($row))
	{
		ShowMsg("��ȡ�ĵ���Ϣ����!","-1");
		exit();
	}
	else if($row['arcrank']>=0)
	{
		$dtime = time();
		$maxtime = $cfg_mb_editday * 24 *3600;
		if($dtime - $row['senddate'] > $maxtime)
		{
			ShowMsg("��ƪ�ĵ��Ѿ��������㲻�����޸�����","-1");
			exit();
		}
	}
	$addRow = $dsql->GetOne("Select * From `{$row['addtable']}` where aid='$aid'; ");
	$dtp = new DedeTagParse();
	$dtp->LoadSource($addRow['imgurls']);
	$abinfo = $dtp->GetTagByName('pagestyle');
	include(DEDEMEMBER."/templets/album_edit.htm");
	exit();
}

/*------------------------------
function _Save(){  }
------------------------------*/
else if($dopost=='save')
{
	$svali = GetCkVdValue();
	if(preg_match("/1/",$safe_gdopen)){
		if(strtolower($vdcode)!=$svali || $svali=='')
		{
			ResetVdValue();
			ShowMsg('��֤�����', '-1');
			exit();
		}
		
	}
	$maxwidth = isset($maxwidth) && is_numeric($maxwidth) ? $maxwidth : 800;
	$pagepicnum = isset($pagepicnum) && is_numeric($pagepicnum) ? $pagepicnum : 12;
	$ddmaxwidth = isset($ddmaxwidth) && is_numeric($ddmaxwidth) ? $ddmaxwidth : 200;
	$prow = isset($prow) && is_numeric($prow) ? $prow : 3;
	$pcol = isset($pcol) && is_numeric($pcol) ? $pcol : 3;
	$pagestyle = in_array($pagestyle,array('1','2','3')) ? $pagestyle : 2;

	include(DEDEMEMBER.'/inc/archives_check_edit.php');
	$imgurls = "{dede:pagestyle maxwidth='$maxwidth' pagepicnum='$pagepicnum'
	ddmaxwidth='$ddmaxwidth' row='$prow' col='$pcol' value='$pagestyle'/}\r\n";
	$hasone = false;
	$ddisfirst=1;

	//����������ָ����ͼƬ�����ϸ���
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
	$info = '';

	//������ϴ���ֱ���ϴ���ͼƬ
	for($i=1;$i<=120;$i++)
	{

		//����ͼƬ������
		if(isset(${'imgurl'.$i}) || (isset($_FILES['imgfile'.$i]['tmp_name']) && is_uploaded_file($_FILES['imgfile'.$i]['tmp_name'])))
		{
			$iinfo = str_replace("'","`",stripslashes(${'imgmsg'.$i}));
			if(!is_uploaded_file($_FILES['imgfile'.$i]['tmp_name']))
			{
				$iurl = stripslashes(${'imgurl'.$i});

				//����о�ͼ
				if(isset(${'imgurl'.$i}))
				{
					$litpicname = $iurl;
					$filename = $iurl;

					//��ͼ
					if($pagestyle > 2)
					{
						$litpicname = GetImageMapDD($filename,$ddmaxwidth);
						if($litpicname != '')
						{
							SaveUploadInfo($title.' Сͼ',$litpicname,1);
						}
					}
				}
				else
				{
					continue;
				}
			}
			else
			{
				$sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png","image/xpng","image/wbmp");
				if(!in_array($_FILES['imgfile'.$i]['type'],$sparr))
				{
					continue;
				}
				if(isset(${'imgurl'.$i}))
				{
					$filename = ${'imgurl'.$i};
				}
				else
				{
					$filename = '';
				}
				$filename = MemberUploads('imgfile'.$i,$filename,$cfg_ml->M_ID,'image','',0,0,false);
				if($filename!='')
				{
					SaveUploadInfo($title,$filename,1);
				}
				$litpicname = $filename;

				//��ͼ
				if($pagestyle > 2)
				{
					$litpicname = GetImageMapDD($filename,$ddmaxwidth);
					if($litpicname != '')
					{
						SaveUploadInfo($title.' Сͼ',$litpicname,1);
					}
				}
			}
			$imgfile = $cfg_basedir.$filename;
			if(is_file($imgfile))
			{
				$iurl = $filename;
				$info = '';
				$imginfos = @getimagesize($imgfile,$info);
				$imgurls .= "{dede:img ddimg='$litpicname' text='$iinfo' width='".$imginfos[0]."' height='".$imginfos[1]."'} $iurl {/dede:img}\r\n";
			}
			if(!$hasone && $litpic=='' && !empty($litpicname))
			{
				$litpic = $litpicname;
				$hasone = true;
			}
		}
	}//ѭ������
	$imgurls = addslashes($imgurls);

	//���������ӱ�����
	$inadd_f = '';
	if(!empty($dede_addonfields))
	{
		$addonfields = explode(';',$dede_addonfields);
		if(is_array($addonfields))
		{
			foreach($addonfields as $v)
			{
				if($v=='')
				{
					continue;
				}else if($v == 'templet')
				{
					ShowMsg("�㱣����ֶ�����,���飡","-1");
					exit();	
				}
				$vs = explode(',',$v);
				if(!isset(${$vs[0]}))
				{
					${$vs[0]} = '';
				}
				${$vs[0]} = GetFieldValueA(${$vs[0]},$vs[1],$aid);
				$inadd_f .= ','.$vs[0]." ='".${$vs[0]}."' ";
			}
		}
	}

	//����ͼƬ�ĵ����Զ�������
	if($litpic!='')
	{
		$flag = 'p';
	}

	//�������ݿ��SQL���
	//�������ݿ��SQL���
	$upQuery = "Update `#@__archives` set
             ismake='$ismake',
             arcrank='$arcrank',
             typeid='$typeid',
             title='$title',
             litpic='$litpic',
             description='$description',
             keywords='$keywords',
             mtype='$mtypesid',            
             flag='$flag'
        where id='$aid' And mid='$mid'; ";
	if(!$dsql->ExecuteNoneQuery($upQuery))
	{
		ShowMsg("�����ݱ��浽���ݿ�����ʱ��������ϵ����Ա��".$dsql->GetError(),"-1");
		exit();
	}

	$isrm = 0;

	if($addtable!='')
	{
		$query = "Update `$addtable`
  	set typeid='$typeid',
  	pagestyle='$pagestyle',
  	maxwidth = '$maxwidth',
  	ddmaxwidth = '$ddmaxwidth',
  	pagepicnum = '$pagepicnum',
  	imgurls='$imgurls',
  	row='$prow',
  	col='$pcol',
  	 userip='$userip',
  	isrm='$isrm'{$inadd_f}
    where aid='$aid'; ";
		if(!$dsql->ExecuteNoneQuery($query))
		{
			ShowMsg("���¸��ӱ� `$addtable`  ʱ��������ϵ����Ա��".$dsql->GetError(),"javascript:;");
			exit();
		}
	}

	UpIndexKey($aid,$arcrank,$typeid,$sortrank,$tags);
	$artUrl = MakeArt($aid,true);
	if($artUrl=='')
	{
		$artUrl = $cfg_phpurl."/view.php?aid=$aid";
	}

	//---------------------------------
	//���سɹ���Ϣ
	//----------------------------------
	$msg = "������ѡ����ĺ���������
<a href='album_add.php?cid=$typeid'><u>������ͼ��</u></a>
&nbsp;&nbsp;
<a href='archives_do.php?channelid=$channelid&aid=".$aid."&dopost=edit'><u>�鿴����</u></a>
&nbsp;&nbsp;
<a href='$artUrl' target='_blank'><u>�鿴ͼ��</u></a>
&nbsp;&nbsp;
<a href='content_list.php?channelid=$channelid'><u>����ͼ��</u></a> ";

	$wintitle = "�ɹ�����ͼ����";
	$wecome_info = "ͼ������::����ͼ��";
	$win = new OxWindow();
	$win->AddTitle("�ɹ�����ͼ����");
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand","&nbsp;",false);
	$win->Display();

}
?>