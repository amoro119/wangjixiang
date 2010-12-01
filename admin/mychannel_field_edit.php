<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_New');
require_once(DEDEINC."/dedetag.class.php");
require_once(dirname(__FILE__)."/inc/inc_admin_channel.php");

if(empty($action)) {
	$action = '';
}

$id = isset($id) && is_numeric($id) ? $id : 0;

$mysql_version = $dsql->GetVersion();

//��ȡģ����Ϣ
$row = $dsql->GetOne("Select fieldset,'' as maintable,addtable,issystem From `#@__channeltype` where id='$id'");
$fieldset = $row['fieldset'];
$trueTable = $row['addtable'];

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
$ds = file(dirname(__FILE__)."/inc/fieldtype.txt");
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
	
	$dfvalue = $vdefault;
	
	if(ereg('^(select|radio|checkbox)',$dtype))
	{
		if(!ereg(',',$dfvalue))
		{
			ShowMsg("���趨���ֶ�Ϊ {$dtype} ���ͣ�������Ĭ��ֵ��ָ��Ԫ���б��磺'a,b,c' ","-1");
			exit();
		}
	}
	
	if($dtype=='stepselect')
	{
		$arr = $dsql->GetOne("Select * From `#@__stepselect` where egroup='$fieldname' ");
		if(!is_array($arr))
		{
			ShowMsg("���趨���ֶ�Ϊ�������ͣ���ϵͳ��û�ҵ����㶨����ֶ�����ͬ����������!","-1");
			exit();
		}
	}

	//������ݿ��Ƿ���ڸ��ӱ����������½�һ��
	$tabsql = "CREATE TABLE IF NOT EXISTS  `{$row['addtable']}`( `aid` int(11) NOT NULL default '0',\r\n `typeid` int(11) NOT NULL default '0',\r\n ";
	if($mysql_version < 4.1)
	{
		$tabsql .= " PRIMARY KEY  (`aid`), KEY `".$trueTable."_index` (`typeid`)\r\n) TYPE=MyISAM; ";
	}
	else
	{
		$tabsql .= " PRIMARY KEY  (`aid`), KEY `".$trueTable."_index` (`typeid`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
	}
	$dsql->ExecuteNoneQuery($tabsql);

	//��⸽�ӱ��ﺬ�е��ֶ�
	$fields = array();
	$rs = $dsql->SetQuery("show fields from `{$row['addtable']}`");
	$dsql->Execute('a');
	while($nrow = $dsql->GetArray('a',MYSQL_ASSOC))
	{
		$fields[ strtolower($nrow['Field']) ] = $nrow['Type'];
	}

	//�޸��ֶ�������Ϣ
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
		if($fieldname==strtolower($ctag->GetName()))
		{
			if(isset($fields[$fieldname]) && $fields[$fieldname]!=$buideType)
			{
				$tabsql = "ALTER TABLE `$trueTable` CHANGE `$fieldname` ".$ntabsql;
				$dsql->ExecuteNoneQuery($tabsql);
			}else if(!isset($fields[$fieldname]))
			{
				$tabsql = "ALTER TABLE `$trueTable` ADD ".$ntabsql;
				$dsql->ExecuteNoneQuery($tabsql);
			}else
			{
				$tabsql = '';
			}
			$dtp->Assign($tagid,stripslashes($fieldstring),false);
			break;
		}
	}
	$oksetting = $dtp->GetResultNP();
	
	$addlist = GetAddFieldList($dtp,$oksetting);
	$oksetting = addslashes($oksetting);
	$dsql->ExecuteNoneQuery("Update `#@__channeltype` set fieldset='$oksetting',listfields='$addlist' where id='$id' ");
	
	ShowMsg("�ɹ�����һ���ֶε����ã�","mychannel_edit.php?id={$id}&dopost=edit&openfield=1");
	
	exit();
}
/*------------------
ɾ���ֶ�
function _DELETE()
-------------------*/
else if($action=="delete")
{
	if($row['issystem']==1)
	{
		ShowMsg("�Բ���ϵͳģ�͵��ֶβ�����ɾ����","-1");
		exit();
	}

	//�����������ͣ����滻Ϊ������
	foreach($dtp->CTags as $tagid=>$ctag)
	{
		if(strtolower($ctag->GetName())==strtolower($fname))
		{
			$dtp->Assign($tagid,"#@Delete@#");
		}
	}
	$oksetting = addslashes($dtp->GetResultNP());
	
	$dsql->ExecuteNoneQuery("Update `#@__channeltype` set fieldset='$oksetting' where id='$id' ");
	
	$dsql->ExecuteNoneQuery("ALTER TABLE `$trueTable` DROP `$fname` ");
	
	ShowMsg("�ɹ�ɾ��һ���ֶΣ�","mychannel_edit.php?id={$id}&dopost=edit&openfield=1");
	exit();
}

require_once(DEDEADMIN."/templets/mychannel_field_edit.htm");

?>