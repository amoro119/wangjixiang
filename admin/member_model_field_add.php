<?php
require_once(dirname(__FILE__)."/config.php");

//����Ȩ�޼��

require_once(DEDEADMIN.'/inc/inc_admin_channel.php');
if(empty($action))
{
	$action = '';
}
$mysql_version = $dsql->GetVersion();
$mysql_versions = explode(".",trim($mysql_version));
$mysql_version = $mysql_versions[0].".".$mysql_versions[1];
/*----------------------
function Save()
---------------------*/
if($action=='save')
{
	//ģ����Ϣ
	$fieldname = strtolower($fieldname);
	$row = $dsql->GetOne("Select `table`,`info` From #@__member_model where id='$id'");
	$fieldset = $row['info'];
	require_once(DEDEINC."/dedetag.class.php");
	$dtp = new DedeTagParse();
	$dtp->SetNameSpace("field","<",">");
	$dtp->LoadSource($fieldset);
	$trueTable = $row['table'];

	//�޸��ֶ�������Ϣ
	$dfvalue = trim($vdefault);
	$isnull = ($isnull==1 ? "true" : "false");
	$mxlen = $maxlength;

	//��ⱻ�޸ĵ��ֶ�����
	$fieldinfos = GetFieldMake($dtype,$fieldname,$dfvalue,$mxlen);
	$ntabsql = $fieldinfos[0];
	$buideType = $fieldinfos[1];

	$rs = $dsql->ExecuteNoneQuery(" ALTER TABLE `$trueTable` ADD  $ntabsql ");

	if(!$rs)
	{
		$gerr = $dsql->GetError();
		ShowMsg("�����ֶ�ʧ�ܣ�������ʾΪ��".$gerr,"javascript:;");
		exit();
	}
	$ok = false;

	//����������Ϣ�����滻Ϊ������
	if(is_array($dtp->CTags))
	{
		//����������
		foreach($dtp->CTags as $tagid=>$ctag)
		{
			if($fieldname == strtolower($ctag->GetName()))
			{
				$dtp->Assign($tagid,stripslashes($fieldstring),false);
				$ok = true;
				break;
			}
		}
		$oksetting = $ok ? $dtp->GetResultNP() : $fieldset."\n".stripslashes($fieldstring);
	}
	else
	{
		//ԭ��������Ϊ��
		$oksetting = $fieldset."\n".stripslashes($fieldstring);
	}
	$addlist = GetAddFieldList($dtp,$oksetting);
	$oksetting = addslashes($oksetting);
	$rs = $dsql->ExecuteNoneQuery("Update #@__member_model set `info`='$oksetting' where id='$id' ");
	if(!$rs)
	{
		$grr = $dsql->GetError();
		ShowMsg("����ڵ����ó���".$grr,"javascript:;");
		exit();
	}
	ShowMsg("�ɹ�����һ���ֶΣ�","member_model_edit.php?id=$id");
	exit();
}

//���ģ�������Ϣ������ʼ���������
/*----------------------
function ShowPage()
---------------------*/
$row = $dsql->GetOne("Select `table` From #@__member_model where id='$id'");
$trueTable = $row['table'];
$tabsql = "CREATE TABLE IF NOT EXISTS  `$trueTable`(
`mid` int(10) unsigned NOT NULL auto_increment,
";
if($mysql_version < 4.1)
{
	$tabsql .= " PRIMARY KEY  (`mid`)\r\n) TYPE=MyISAM; ";
}
else
{
	$tabsql .= " PRIMARY KEY  (`mid`)\r\n) ENGINE=MyISAM DEFAULT CHARSET=".$cfg_db_language."; ";
}
$dsql->ExecuteNoneQuery($tabsql);

//��⸽�ӱ��ﺬ�е��ֶ�
$fields = array();
$rs = $dsql->SetQuery("show fields from `$trueTable`");
$dsql->Execute('a');
while($nrow = $dsql->GetArray('a',MYSQL_ASSOC))
{
	$fields[strtolower($nrow['Field'])] = 1;
}
$f = '';
foreach($fields as $k=>$v)
{
	$f .= ($f=='' ? $k : ' '.$k);
}
require_once(DEDEADMIN."/templets/member_model_field_add.htm");

?>