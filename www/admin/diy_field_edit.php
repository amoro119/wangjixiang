<?php
require_once(dirname(__FILE__)."/config.php");

//����Ȩ�޼��

require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEADMIN."/inc/inc_admin_channel.php");
if(empty($action))
{
	$action = '';
}

//��ȡģ����Ϣ
$mysql_version = $dsql->GetVersion();
$mysql_versions = explode(".",trim($mysql_version));
$mysql_version = $mysql_versions[0].".".$mysql_versions[1];
$row = $dsql->GetOne("Select `table`,`info` From #@__diyforms where diyid='$diyid'");
$fieldset = $row['info'];
$trueTable = $row['table'];
$dtp = new DedeTagParse();
$dtp->SetNameSpace("field","<",">");
$dtp->LoadSource($fieldset);
foreach($dtp->CTags as $ctag)
{
	if(strtolower($ctag->GetName())==strtolower($fname))
	{
		break;
	}
}

//�ֶ�������Ϣ
$ds = file(DEDEADMIN."/inc/fieldtype.txt");
foreach($ds as $d)
{
	$dds = explode(',',trim($d));
	$fieldtypes[$dds[0]] = $dds[1];
}

//�������
/*--------------------
function _SAVE()
----------------------*/
if($action=='save')
{

	if(!isset($fieldtypes[$dtype]))
	{
		ShowMsg("���޸ĵ���ϵͳר�����͵����ݣ���ֹ������","-1");
		exit();
	}

	//������ݿ��Ƿ���ڸ��ӱ����������½�һ��
	$tabsql = "CREATE TABLE IF NOT EXISTS  `$trueTable`(
	`id` int(10) unsigned NOT NULL auto_increment,
	`ifcheck` tinyint(1) NOT NULL default '0',
	";
	if($mysql_version < 4.1)
	{
		$tabsql .= " PRIMARY KEY  (`id`)\r\n) TYPE=MyISAM; ";
	}
	else
	{
		$tabsql .= " PRIMARY KEY  (`id`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
	}
	$dsql->ExecuteNoneQuery($tabsql);

	//�����ﺬ�е��ֶ�
	$fields = array();
	$rs = $dsql->SetQuery("show fields from `$trueTable`");
	$dsql->Execute('a');
	while($nrow = $dsql->GetArray('a',MYSQL_ASSOC))
	{
		$fields[ strtolower($nrow['Field']) ] = $nrow['Type'];
	}

	//�޸��ֶ�������Ϣ
	$dfvalue = $vdefault;
	$isnull = ($isnull==1 ? "true" : "false");
	$mxlen = $maxlength;
	$fieldname = strtolower($fname);

	//��ⱻ�޸ĵ��ֶ����ͣ����������ݱ�
	$fieldinfos = GetFieldMake($dtype,$fieldname,$dfvalue,$mxlen);
	$ntabsql = $fieldinfos[0];
	$buideType = $fieldinfos[1];
	$tabsql  = '';

	//�����������ͣ����滻Ϊ������
	foreach($dtp->CTags as $tagid=>$ctag)
	{
		if(trim($fieldname)==trim(strtolower($ctag->GetName())))
		{

			if(isset($fields[$fieldname]) && $fields[$fieldname]!=$buideType)
			{
				$tabsql = "ALTER TABLE `$trueTable` CHANGE `$fieldname` ".$ntabsql;
				$dsql->ExecuteNoneQuery($tabsql);
			}
			else if(!isset($fields[$fieldname]))
			{
				$tabsql = "ALTER TABLE `$trueTable` ADD ".$ntabsql;
				$dsql->ExecuteNoneQuery($tabsql);
			}
			else
			{
				$tabsql = '';
			}
			$dtp->Assign($tagid,stripslashes($fieldstring),false);
			break;
		}
	}
	$oksetting = $dtp->GetResultNP();

	$oksetting = addslashes($oksetting);
	$dsql->ExecuteNoneQuery("Update #@__diyforms set info='$oksetting' where diyid='$diyid' ");
	ShowMsg("�ɹ�����һ���ֶε����ã�","diy_edit.php?diyid={$diyid}");
	exit();
}

/*------------------
ɾ���ֶ�
function _DELETE()
-------------------*/
else if($action=="delete")
{
	//�����������ͣ����滻Ϊ������
	foreach($dtp->CTags as $tagid=>$ctag)
	{
		if(strtolower($ctag->GetName())==strtolower($fname))
		{
			$dtp->Assign($tagid,"#@Delete@#");
		}
	}
	$oksetting = addslashes($dtp->GetResultNP());
	$dsql->ExecuteNoneQuery("Update #@__diyforms set info='$oksetting' where diyid='$diyid' ");
	$dsql->ExecuteNoneQuery("ALTER TABLE `$trueTable` DROP `$fname` ");
	ShowMsg("�ɹ�ɾ��һ���ֶΣ�","diy_edit.php?diyid=$diyid");
	exit();
}
require_once(DEDEADMIN."/templets/diy_field_edit.htm");

?>