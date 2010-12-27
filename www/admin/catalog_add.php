<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/typelink.class.php");

if(empty($listtype))
{
	$listtype='';
}
if(empty($dopost))
{
	$dopost = '';
}
if(empty($upinyin))
{
	$upinyin = 0;
}
if(empty($channelid))
{
	$channelid = 1;
}
if(isset($channeltype))
{
	$channelid = $channeltype;
}
$id = empty($id) ? 0 :intval($id);
$reid = empty($reid) ? 0 :intval($reid);
$nid = 'article';

if($id==0 && $reid==0)
{
	CheckPurview('t_New');
}
else
{
	$checkID = empty($id) ? $reid : $id;
	CheckPurview('t_AccNew');
	CheckCatalog($checkID, '����Ȩ�ڱ���Ŀ�´������࣡');
}

if(empty($myrow))
{
	$myrow = array();
}
$dsql->SetQuery("select id,typename,nid from `#@__channeltype` where id<>-1 And isshow=1 order by id");
$dsql->Execute();
while($row=$dsql->GetObject())
{
	$channelArray[$row->id]['typename'] = $row->typename;
	$channelArray[$row->id]['nid'] = $row->nid;
	if($row->id==$channelid)
	{
		$nid = $row->nid;
	}
}
if($dopost=='quick')
{
	include DedeInclude('templets/catalog_add_quick.htm');
	exit();
}
/*---------------------
function action_savequick(){ }
---------------------*/
else if($dopost=='savequick')
{
	$tempindex = "{style}/index_{$nid}.htm";
	$templist = "{style}/list_{$nid}.htm";
	$temparticle = "{style}/article_{$nid}.htm";
	$queryTemplate = "insert into `#@__arctype`(reid,topid,sortrank,typename,typedir,isdefault,defaultname,issend,channeltype,
    tempindex,templist,temparticle,modname,namerule,namerule2,ispart,corank,description,keywords,seotitle,moresite,siteurl,sitepath,ishidden,`cross`,`crossid`,`content`,`smalltypes`)
    Values('~reid~','~topid~','~rank~','~typename~','~typedir~','$isdefault','$defaultname','$issend','$channeltype',
    '$tempindex','$templist','$temparticle','default','$namerule','$namerule2','0','0','','','~typename~','0','','','0','0','0','','')";
	foreach($_POST as $k=>$v)
	{
		if(ereg('^posttype',$k))
		{
			$k = str_replace('posttype','',$k);
		}
		else
		{
			continue;
		}
		$rank = ${'rank'.$k};
		$toptypename = trim(${'toptype'.$k});
		$sontype = trim(${'sontype'.$k});
		$toptypedir = GetPinyin(stripslashes($toptypename));
		$toptypedir = $referpath=='parent' ? $nextdir.'/'.$toptypedir : '/'.$toptypedir;
		if(empty($toptypename))
		{
			continue;
		}
		$sql = str_replace('~reid~','0',$queryTemplate);
		$sql = str_replace('~topid~','0',$sql);
		$sql = str_replace('~rank~',$rank,$sql);
		$sql = str_replace('~typename~',$toptypename,$sql);
		$sql = str_replace('~typedir~',$toptypedir,$sql);
		$dsql->ExecuteNoneQuery($sql);
		$tid = $dsql->GetLastID();
		if($tid>0 && $sontype!='')
		{
			$sontypes = explode(',',$sontype);
			foreach($sontypes as $k=>$v)
			{
				$v = trim($v);
				if($v=='')
				{
					continue;
				}
				$typedir = $toptypedir.'/'.GetPinyin(stripslashes($v));
				$sql = str_replace('~reid~',$tid,$queryTemplate);
				$sql = str_replace('~topid~',$tid,$sql);
				$sql = str_replace('~rank~',$k,$sql);
				$sql = str_replace('~typename~',$v,$sql);
				$sql = str_replace('~typedir~',$typedir,$sql);
				$dsql->ExecuteNoneQuery($sql);
			}
		}
	}
	UpDateCatCache();
	ShowMsg('�ɹ�����ָ����Ŀ��','catalog_main.php');
	exit();
}
/*---------------------
function action_save(){ }
---------------------*/
else if($dopost=='save')
{
	$smalltypes = '';
	if(empty($smalltype)) $smalltype = '';
	if(is_array($smalltype)) $smalltypes = join(',',$smalltype);
	
	if(!isset($sitepath)) $sitepath = '';
	
	if($topid==0 && $reid>0) $topid = $reid;
	
	if($ispart!=0) $cross = 0;
	
	$description = Html2Text($description,1);
	$keywords = Html2Text($keywords,1);
	
	if($ispart != 2 )
	{
		//��Ŀ�Ĳ���Ŀ¼
		if($referpath=='cmspath') $nextdir = '{cmspath}';
		if($referpath=='basepath') $nextdir = '';
		//��ƴ������
		if($upinyin==1 || $typedir=='')
		{
			$typedir = GetPinyin(stripslashes($typename));
		}
		$typedir = $nextdir.'/'.$typedir;
		$typedir = ereg_replace("/{1,}", "/", $typedir);
	}

	//������վ��ʱ������(����Զ�����Ŀ)
	if($reid==0 && $moresite==1)
	{
		$sitepath = $typedir;

		//��������ַ
		if($siteurl!='')
		{
			$siteurl = ereg_replace("/$","",$siteurl);
			if(!eregi("http://",$siteurl))
			{
				ShowMsg("��󶨵Ķ���������Ч������(http://host)����ʽ��","-1");
				exit();
			}
			if(eregi($cfg_basehost,$siteurl))
			{
				ShowMsg("��󶨵Ķ��������뵱ǰվ����ͬһ���򣬲���Ҫ�󶨣�","-1");
				exit();
			}
		}
	}

	//����Ŀ¼
	if($ispart != 2)
	{
		$true_typedir = str_replace("{cmspath}",$cfg_cmspath,$typedir);
		$true_typedir = ereg_replace("/{1,}","/",$true_typedir);
		if(!CreateDir($true_typedir))
		{
			ShowMsg("����Ŀ¼ {$true_typedir} ʧ�ܣ��������·���Ƿ�������⣡","-1");
			exit();
		}
	}
	
	$in_query = "insert into `#@__arctype`(reid,topid,sortrank,typename,typedir,isdefault,defaultname,issend,channeltype,
    tempindex,templist,temparticle,modname,namerule,namerule2,
    ispart,corank,description,keywords,seotitle,moresite,siteurl,sitepath,ishidden,`cross`,`crossid`,`content`,`smalltypes`)
    Values('$reid','$topid','$sortrank','$typename','$typedir','$isdefault','$defaultname','$issend','$channeltype',
    '$tempindex','$templist','$temparticle','default','$namerule','$namerule2',
    '$ispart','$corank','$description','$keywords','$seotitle','$moresite','$siteurl','$sitepath','$ishidden','$cross','$crossid','$content','$smalltypes')";

	if(!$dsql->ExecuteNoneQuery($in_query))
	{
		ShowMsg("����Ŀ¼����ʱʧ�ܣ�����������������Ƿ�������⣡","-1");
		exit();
	}
	UpDateCatCache();
	if($reid>0)
	{
		PutCookie('lastCid',GetTopid($reid),3600*24,'/');
	}
	ShowMsg("�ɹ�����һ�����࣡","catalog_main.php");
	exit();

}//End dopost==save


//��ȡ�Ӹ�Ŀ¼�̳е�Ĭ�ϲ���
if($dopost=='')
{
	$channelid = 1;
	$issend = 1;
	$corank = 0;
	$reid = 0;
	$topid = 0;
	$typedir = '';
	$moresite = 0;
	if($id>0)
	{
		$myrow = $dsql->GetOne(" Select tp.*,ch.typename as ctypename From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id=$id ");
		$channelid = $myrow['channeltype'];
		$issennd = $myrow['issend'];
		$corank = $myrow['corank'];
		$topid = $myrow['topid'];
		$typedir = $myrow['typedir'];
	}

	//����Ŀ�Ƿ�Ϊ����վ��
	$moresite = empty($myrow['moresite']) ? 0 : $myrow['moresite'];
}

include DedeInclude('templets/catalog_add.htm');

?>