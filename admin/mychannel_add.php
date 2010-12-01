<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_New');
require_once(DEDEINC."/dedetag.class.php");
if(empty($ismake))
{
	$ismake = 0;
}
if(empty($isdel))
{
	$isdel = 0;
}
if(empty($action))
{
	$action = '';
}

if($action=='add')
{
	//�������
	if(empty($id) || ereg("[^0-9-]",$id))
	{
		ShowMsg("<font color=red>'Ƶ��id'</font>����Ϊ���֣�","-1");
		exit();
	}
	if(eregi("[^a-z0-9]",$nid)||$nid=="")
	{
		ShowMsg("<font color=red>'Ƶ�����ֱ�ʶ'</font>����ΪӢ����ĸ�������ֻ���ַ�����","-1");
		exit();
	}
	if($addtable=="")
	{
		ShowMsg("���ӱ���Ϊ�գ�","-1");
		exit();
	}
	$trueTable2 = str_replace("#@__",$cfg_dbprefix,$addtable);

	if($issystem == -1 && $id>0) $id = $id * -1;

	//���id�Ƿ��ظ�
	$row = $dsql->GetOne("Select * from #@__channeltype where id='$id' Or nid like '$nid' Or addtable like '$addtable'");
	if(is_array($row))
	{
		ShowMsg("���ܡ�Ƶ��id������Ƶ�����Ʊ�ʶ���������ӱ����ơ������ݿ��Ѵ��ڣ������ظ�ʹ�ã�","-1");
		exit();
	}
	$mysql_version = $dsql->GetVersion();

	//�������ӱ�
	if($trueTable2!='')
	{
		$istb = $dsql->IsTable($trueTable2);
		if(!$istb || $isdel==1)
		{
			//�Ƿ���ҪժҪ�ֶ�
			$dsql->ExecuteNoneQuery("DROP TABLE IF EXISTS `{$trueTable2}`;");
			if($issystem != -1)
			{
			    $tabsql = "CREATE TABLE `$trueTable2`(
	                  `aid` int(11) NOT NULL default '0',
                    `typeid` int(11) NOT NULL default '0',
                    `redirecturl` varchar(255) NOT NULL default '',
                    `templet` varchar(30) NOT NULL default '',
                    `userip` char(15) NOT NULL default '',
           ";
			}
			else
			{
			     $tabsql = "CREATE TABLE `$trueTable2`(
	                  `aid` int(11) NOT NULL default '0',
                    `typeid` int(11) NOT NULL default '0',
                    `channel` SMALLINT NOT NULL DEFAULT '0',
                    `arcrank` SMALLINT NOT NULL DEFAULT '0',
                    `mid` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
                    `click` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
                    `title` varchar(60) NOT NULL default '',
                    `senddate` int(11) NOT NULL default '0',
                    `flag` set('c','h','p','f','s','j','a','b') default NULL,
                    `litpic` varchar(60) NOT NULL default '',
                    `userip` char(15) NOT NULL default '',
                    `lastpost` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
                    `scores` MEDIUMINT( 8 ) NOT NULL DEFAULT '0',
                    `goodpost` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
                    `badpost` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
            ";
			}
			if($mysql_version < 4.1)
			{
				$tabsql .= "    PRIMARY KEY  (`aid`), KEY `typeid` (`typeid`)\r\n) TYPE=MyISAM; ";
			}
			else
			{
				$tabsql .= "    PRIMARY KEY  (`aid`), KEY `typeid` (`typeid`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
			}
			$rs = $dsql->ExecuteNoneQuery($tabsql);
			if(!$rs)
			{
				ShowMsg("�������ӱ�ʧ��!".$dsql->GetError(),"javascript:;");
				exit();
			}
		}
	}

	$listfields = $fieldset = '';
	if($issystem == -1)
	{
		$fieldset = "<field:channel itemname=\"Ƶ��id\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:channel>
<field:arcrank itemname=\"���Ȩ��\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"5\" page=\"\"></field:arcrank>
<field:mid itemname=\"��Աid\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"8\" page=\"\"></field:mid>
<field:click itemname=\"���\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:click>
<field:title itemname=\"����\" autofield=\"0\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"60\" page=\"\"></field:title>
<field:senddate itemname=\"����ʱ��\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:senddate>
<field:flag itemname=\"�Ƽ�����\" autofield=\"0\" notsend=\"0\" type=\"checkbox\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:flag>
<field:litpic itemname=\"����ͼ\" autofield=\"0\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"0\" default=\"\"  maxlength=\"60\" page=\"\"></field:litpic>
<field:userip itemname=\"��ԱIP\" autofield=\"0\" notsend=\"0\" type=\"text\" isnull=\"true\" islist=\"0\" default=\"0\"  maxlength=\"15\" page=\"\"></field:userip>
<field:lastpost itemname=\"�������ʱ��\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"10\" page=\"\"></field:lastpost>
<field:scores itemname=\"���ۻ���\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"8\" page=\"\"></field:scores>
<field:goodpost itemname=\"������\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"8\" page=\"\"></field:goodpost>
<field:badpost itemname=\"������\" autofield=\"0\" notsend=\"0\" type=\"int\" isnull=\"true\" islist=\"1\" default=\"0\"  maxlength=\"8\" page=\"\"></field:badpost>\r\n";
		$listfields = 'channel,arcrank,mid,click,title,senddate,flag,listpic,lastpost,scores,goodpost,badpost';
	}

	$inQuery = "INSERT INTO `#@__channeltype`(id,nid,typename,addtable,addcon,mancon,editcon,useraddcon,usermancon,usereditcon,fieldset,listfields,issystem,issend,arcsta,usertype,sendrank,needdes,needpic,titlename,onlyone,dfcid)
    VALUES ('$id','$nid','$typename','$addtable','$addcon','$mancon','$editcon','$useraddcon','$usermancon','$usereditcon','$fieldset','$listfields','$issystem','$issend','$arcsta','$usertype','$sendrank','$needdes','$needpic','$titlename','$onlyone','$dfcid');";
	$dsql->ExecuteNoneQuery($inQuery);
	ShowMsg("�ɹ�����һ��Ƶ��ģ�ͣ�","mychannel_edit.php?id=".$id);
	exit();
}
$row = $dsql->GetOne("Select id From `#@__channeltype` order by id desc limit 0,1 ");
$newid = $row['id']+1;
if($newid<10)
{
	$newid = $newid+10;
}
require_once(DEDEADMIN."/templets/mychannel_add.htm");

?>