<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_Edit');
require_once(DEDEINC."/dedetag.class.php");
require_once(DEDEINC."/oxwindow.class.php");
if(empty($dopost))
{
	$dopost="";
}
$id = isset($id) && is_numeric($id) ? $id : 0;

/*----------------
function __ShowHide()
-----------------*/
if($dopost=="show")
{
	$dsql->ExecuteNoneQuery("update `#@__channeltype` set isshow=1 where id='$id' ");
	ShowMsg("�����ɹ���","mychannel_main.php");
	exit();
}
else if($dopost=="hide")
{
	$dsql->ExecuteNoneQuery("update `#@__channeltype` set isshow=0 where id='$id'");
	ShowMsg("�����ɹ���","mychannel_main.php");
	exit();
}
/*----------------
function __CopyStart()
-----------------*/
else if($dopost=="copystart")
{
	if($id==-1)
	{
		ShowMsg("ר��ģ�Ͳ�֧�ָ��ƣ�","-1");
		exit();
	}
	$row = $dsql->GetOne("Select * From `#@__channeltype` where id='$id'");
	if($row['id']>-1)
	{
		$nrow = $dsql->GetOne("Select max(id) as id From `#@__channeltype` limit 0,1 ");
		$newid = $nrow['id']+1;
		if($newid<10)
		{
			$newid = $newid+10;
		}
		$idname = $newid;
	}
	else
	{
		$nrow = $dsql->GetOne("Select min(id) as id From `#@__channeltype` limit 0,1 ");
		$newid = $nrow['id']-1;
		if($newid<-10)
		{
			$newid = $newid-10;
		}
		$idname = 'w'.($newid * -1);
	}
	$row = $dsql->GetOne("Select * From `#@__channeltype` where id='$id'");
	$wintitle = "Ƶ������-ģ�͸���";
	$wecome_info = "<a href='mychannel_main.php'>Ƶ������</a> - ģ�͸���";
	$win = new OxWindow();
	$win->Init("mychannel_edit.php","js/blank.js","post");
	$win->AddTitle("������Ƶ���� [<font color='red'>".$row['typename']."</font>]");
	$win->AddHidden("cid",$id);
	$win->AddHidden("id",$id);
	$win->AddHidden("dopost",'copysave');
	$msg = "
		<table width='460' border='0' cellspacing='0' cellpadding='0'>
		<tr>
		<td width='170' height='24' align='center'>��Ƶ��id��</td>
		<td width='230'><input name='newid' type='text' id='newid' size='6' value='{$newid}' /></td>
		</tr>
		<tr>
		<td height='24' align='center'>��Ƶ�����ƣ�</td>
		<td><input name='newtypename' type='text' id='newtypename' value='{$row['typename']}{$idname}' style='width:250px' /></td>
		</tr>
		<tr>
		<td height='24' align='center'>��Ƶ����ʶ��</td>
		<td><input name='newnid' type='text' id='newnid' value='{$row['nid']}{$idname}' style='width:250px' /></td>
		</tr>
		<tr>
		<td height='24' align='center'>�¸��ӱ�</td>
		<td><input name='newaddtable' type='text' id='newaddtable' value='{$row['addtable']}{$idname}' style='width:250px' /></td>
		</tr>
		<tr>
		<td height='24' align='center'>����ģ�壺</td>
		<td>
		<input name='copytemplet' type='radio' id='copytemplet' value='1' class='np' checked='checked' /> ����
		&nbsp;
		<input name='copytemplet' type='radio' id='copytemplet' class='np' value='0' /> ������
		</td>
		</tr>
		</table>
		";
	$win->AddMsgItem("<div style='padding:20px;line-height:300%'>$msg</div>");
	$winform = $win->GetWindow("ok","");
	$win->Display();
	exit();
}
/*----------------
function __Export()
-----------------*/
else if($dopost=="export")
{
	if($id==-1)
	{
		ShowMsg("ר��ģ�Ͳ�֧�ֵ�����","-1");
		exit();
	}
	$row = $dsql->GetOne("Select * From `#@__channeltype` where id='$id' ");
	$channelconfig = '';
	$row['maintable'] = ereg_replace('dede_','#@__',$row['maintable']);
	$row['addtable'] = ereg_replace('dede_','#@__',$row['addtable']);
	foreach($row as $k=>$v)
	{
		if($k=='fieldset') $v = "\r\n$v\r\n";
		$channelconfig .= "<channel:{$k}>$v</channel:{$k}>\r\n";
	}
	$wintitle = "��������ģ�͹���";
	$wecome_info = "<a href='mychannel_main.php'><u>����ģ�͹���</u></a>::��������ģ�͹���";
	$win = new OxWindow();
	$win->Init();
	$win->AddTitle("����Ϊ���� [{$row['typename']}] ��ģ�͹�������Թ����������ѣ�");
	$winform = $win->GetWindow("hand","<textarea name='config' style='width:100%;height:450px;word-wrap: break-word;word-break:break-all;'>".$channelconfig."</textarea>");
	$win->Display();
	exit();
}
/*----------------
function __ExportIn()
-----------------*/
else if($dopost=="exportin")
{
	$wintitle = "��������ģ�͹���";
	$wecome_info = "<a href='mychannel_main.php'>����ģ�͹���</a>::��������ģ�͹���";
	$win = new OxWindow();
	$win->Init("mychannel_edit.php","js/blank.js","post");
	$win->AddHidden("dopost","exportinok");
	$win->AddTitle("����������ݣ�(����ģ�ͻ��ԭ��ģ�ͳ�ͻ�����������ڵ�����޸�)");
	$win->AddMsgItem("<textarea name='exconfig' style='width:100%;height:450px;word-wrap: break-word;word-break:break-all;'></textarea>");
	$winform = $win->GetWindow("ok");
	$win->Display();
	exit();
}
/*----------------
function __ExportInOk()
-----------------*/
else if($dopost=="exportinok")
{
	require_once(DEDEADMIN."/inc/inc_admin_channel.php");
	function GotoStaMsg($msg)
	{
		global $wintitle,$wecome_info,$winform;
		$wintitle = "��������ģ�͹���";
		$wecome_info = "<a href='mychannel_main.php'>����ģ�͹���</a>::��������ģ�͹���";
		$win = new OxWindow();
		$win->Init();
		$win->AddTitle("����״̬��ʾ��");
		$win->AddMsgItem($msg);
		$winform = $win->GetWindow("hand");
		$win->Display();
		exit();
	}

	$msg = "����Ϣ";
	$exconfig = stripslashes($exconfig);

	$dtp = new DedeTagParse();
	$dtp->SetNameSpace('channel','<','>');
	$dtp->LoadSource($exconfig);

	if(!is_array($dtp->CTags))
	{
		GotoStaMsg("ģ�͹����ǺϷ���Dedeģ�͹���");
	}

	$fields = array();
	foreach($dtp->CTags as $ctag)
	{
		$fname = $ctag->GetName('name');
		$fields[$fname] = trim($ctag->GetInnerText());
	}

	if(!isset($fields['nid']) || !isset($fields['fieldset']))
	{
		GotoStaMsg("ģ�͹����ǺϷ���Dedeģ�͹���");
	}

	//�����ĵ������

	$mysql_version = $dsql->GetVersion(true);

	$row = $dsql->GetOne("Select * From `#@__channeltype` where nid='{$fields['nid']}' ");
	if(is_array($row))
	{
		GotoStaMsg("ϵͳ���Ѿ�������ͬ��ʶ {$fields['nid']} �Ĺ���");
	}

	//������
	if($fields['issystem'] != -1)
	{
			$tabsql = "CREATE TABLE IF NOT EXISTS `{$fields['addtable']}`(
	                  `aid` int(11) NOT NULL default '0',
                    `typeid` int(11) NOT NULL default '0',
                    `redirecturl` varchar(255) NOT NULL default '',
                    `templet` varchar(30) NOT NULL default '',
                    `userip` char(15) NOT NULL default '',
      ";
	}
	else
	{
			 $tabsql = "CREATE TABLE IF NOT EXISTS `{$fields['addtable']}`(
	                  `aid` int(11) NOT NULL default '0',
                    `typeid` int(11) NOT NULL default '0',
                    `channel` SMALLINT NOT NULL DEFAULT '0',
                    `arcrank` SMALLINT NOT NULL DEFAULT '0',
                    `mid` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0',
                    `click` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
                    `title` varchar(60) NOT NULL default '',
                    `senddate` int(11) NOT NULL default '0',
                    `flag` set('c','h','p','f','s','j','a','b') default NULL,
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
		GotoStaMsg("������ʧ��!".$dsql->GetError());
		exit();
	}

	if($fields['issystem']==1)
	{
		$fields['issystem'] = 0;
	}

	if($fields['issystem'] == 0)
	{
		$row = $dsql->GetOne("Select id From `#@__channeltype` order by id desc ");
		$fields['newid'] = $row['id'] + 1;
	}
	else
	{
		$row = $dsql->GetOne("Select id From `#@__channeltype` order by id asc ");
		$fields['newid'] = $row['id'] - 1;
	}

	$fieldset = $fields['fieldset'];
	$fields['fieldset'] = addslashes($fields['fieldset']);

	$inquery = " INSERT INTO `#@__channeltype`(`id` , `nid` , `typename` , `addtable` , `addcon` ,
	 `mancon` , `editcon` , `useraddcon` , `usermancon` , `usereditcon` ,
	  `fieldset` , `listfields` , `issystem` , `isshow` , `issend` ,
	   `arcsta`,`usertype` , `sendrank` )
    VALUES('{$fields['newid']}' , '{$fields['nid']}' , '{$fields['typename']}' , '{$fields['addtable']}' , '{$fields['addcon']}' ,
     '{$fields['mancon']}' , '{$fields['editcon']}' , '{$fields['useraddcon']}' , '{$fields['usermancon']}' , '{$fields['usereditcon']}' ,
      '{$fields['fieldset']}' , '{$fields['listfields']}' , '{$fields['issystem']}' , '{$fields['isshow']}' , '{$fields['issend']}' ,
       '{$fields['arcsta']}' , '{$fields['usertype']}' , '{$fields['sendrank']}' ); ";

	$rs = $dsql->ExecuteNoneQuery($inquery);

	if(!$rs)
	{
		GotoStaMsg("����ģ��ʱ��������".$dsql->GetError());
	}

	$dtp = new DedeTagParse();
	$dtp->SetNameSpace("field","<",">");
	$dtp->LoadSource($fieldset);
	$allfields = '';
	if(is_array($dtp->CTags))
	{
		foreach($dtp->CTags as $ctag)
		{
			//��ⱻ�޸ĵ��ֶ�����
			$dtype = $ctag->GetAtt('type');
			$fieldname = $ctag->GetName();
			$dfvalue = $ctag->GetAtt('default');
			$islist = $ctag->GetAtt('islist');
			$mxlen = $ctag->GetAtt('maxlength');
			$fieldinfos = GetFieldMake($dtype,$fieldname,$dfvalue,$mxlen);
			$ntabsql = $fieldinfos[0];
			$buideType = $fieldinfos[1];
			if($islist!='')
			{
				$allfields .= ($allfields=='' ? $fieldname : ','.$fieldname);
			}
			$dsql->ExecuteNoneQuery(" ALTER TABLE `{$fields['addtable']}` ADD  $ntabsql ");
		}
	}

	if($allfields!='')
	{
		$dsql->ExecuteNoneQuery("Update `#@__channeltype` set listfields='$allfields' where id='{$fields['newid']}' ");
	}

	GotoStaMsg("�ɹ�����һ��ģ�ͣ�");

}
/*----------------
function __SaveCopy()
-----------------*/
else if($dopost=="copysave")
{
	$cid = intval($cid);
	$row = $dsql->GetOne("Select * From `#@__channeltype` where id='$cid' ", MYSQL_ASSOC);
	foreach($row as $k=>$v)
	{
		${strtolower($k)} = addslashes($v);
	}
	$inquery = " INSERT INTO `#@__channeltype`(`id` , `nid` , `typename` , `addtable` , `addcon` ,
                `mancon` , `editcon` , `useraddcon` , `usermancon` , `usereditcon` , `fieldset` , `listfields` ,
                 `issystem` , `isshow` , `issend` , `arcsta`,`usertype` , `sendrank` )
              VALUES('$newid' , '$newnid' , '$newtypename' , '$newaddtable' , '$addcon' ,
               '$mancon' , '$editcon' , '$useraddcon' , '$usermancon' , '$usereditcon' , '$fieldset' , '$listfields' ,
               '$issystem' , '$isshow' , '$issend' , '$arcsta','$usertype' , '$sendrank' );
  ";
	$mysql_version = $dsql->GetVersion(true);
	if(!$dsql->IsTable($newaddtable))
	{
		$dsql->Execute('me',"SHOW CREATE TABLE {$dsql->dbName}.{$addtable}");
		$row = $dsql->GetArray('me', MYSQL_BOTH);
		$tableStruct = $row[1];
		$tb = str_replace('#@__',$cfg_dbprefix,$addtable);
		$tableStruct = preg_replace("/CREATE TABLE `$addtable`/iU","CREATE TABLE `$newaddtable`",$tableStruct);
		$dsql->ExecuteNoneQuery($tableStruct);
	}
	if($copytemplet==1)
	{
		$tmpletdir = $cfg_basedir.$cfg_templets_dir.'/'.$cfg_df_style;
		copy("{$tmpletdir}/article_{$nid}.htm","{$tmpletdir}/{$newnid}_article.htm");
		copy("{$tmpletdir}/list_{$nid}.htm","{$tmpletdir}/{$newnid}_list.htm");
		copy("{$tmpletdir}/index_{$nid}.htm","{$tmpletdir}/{$newnid}_index.htm");
	}
	$rs = $dsql->ExecuteNoneQuery($inquery);
	if($rs)
	{
		ShowMsg("�ɹ�����ģ�ͣ���ת����ϸ����ҳ... ","mychannel_edit.php?id={$newid}&dopost=edit");
		exit();
	}
	else
	{
		$errv = $dsql->GetError();
		ShowMsg("ϵͳ������Ѵ�����뷢�͵��ٷ���̳���Լ��ԭ��<br /> ������룺mychannel_edit.php?dopost=savecopy $errv","javascript:;");
		exit();
	}
}
/*------------
function __SaveEdit()
------------*/
else if($dopost=="save")
{

	$fieldset = ereg_replace("[\r\n]{1,}","\r\n",$fieldset);

	$query = "Update `#@__channeltype` set
	typename = '$typename',
	addtable = '$addtable',
	addcon = '$addcon',
	mancon = '$mancon',
	editcon = '$editcon',
	useraddcon = '$useraddcon',
	usermancon = '$usermancon',
	usereditcon = '$usereditcon',
	fieldset = '$fieldset',
	listfields = '$listfields',
	issend = '$issend',
	arcsta = '$arcsta',
	usertype = '$usertype',
	sendrank = '$sendrank',
	needdes = '$needdes',
	needpic = '$needpic',
	titlename = '$titlename',
	onlyone = '$onlyone',
	dfcid = '$dfcid'
	where id='$id' ";
	if(trim($fieldset)!='')
	{
		$dtp = new DedeTagParse();
		$dtp->SetNameSpace("field","<",">");
		$dtp->LoadSource(stripslashes($fieldset));
		if(!is_array($dtp->CTags))
		{
			ShowMsg("�ı����ò�����Ч���޷����н�����","-1");
			exit();
		}
	}
	$trueTable = str_replace("#@__",$cfg_dbprefix,$addtable);
	if(!$dsql->IsTable($trueTable))
	{
		ShowMsg("ϵͳ�Ҳ�������ָ���ı� $trueTable �����ֹ����������","-1");
		exit();
	}
	$dsql->ExecuteNoneQuery($query);
	ShowMsg("�ɹ�����һ��ģ�ͣ�","mychannel_main.php");
	exit();
}
/*--------------------
function __GetTemplate()
--------------------*/
else if($dopost=="gettemplets")
{
	require_once(DEDEINC."/oxwindow.class.php");
	$row = $dsql->GetOne("Select * From `#@__channeltype` where id='$id'");
	$wintitle = "Ƶ������-�鿴ģ��";
	$wecome_info = "<a href='mychannel_main.php'>Ƶ������</a>::�鿴ģ��";
	$win = new OxWindow();
	$win->Init("","js/blank.js","");
	$win->AddTitle("Ƶ������".$row['typename']."��Ĭ��ģ���ļ�˵����");
	$defaulttemplate = $cfg_templets_dir.'/'.$cfg_df_style;
	$msg = "
		�ĵ�ģ�壺{$defaulttemplate}/article_{$row['nid']}.htm
		<a href='tpl.php?acdir={$cfg_df_style}&action=edit&filename=article_{$row['nid']}.htm'>[�޸�]</a><br/>
		�б�ģ�壺{$defaulttemplate}/list_{$row['nid']}.htm
		<a href='tpl.php?acdir={$cfg_df_style}&action=edit&filename=list_{$row['nid']}.htm'>[�޸�]</a>
		<br/>
		Ƶ������ģ�壺{$defaulttemplate}/index_{$row['nid']}.htm
		<a href='tpl.php?acdir={$cfg_df_style}&action=edit&filename=index_{$row['nid']}.htm'>[�޸�]</a>
	";
	$win->AddMsgItem("<div style='padding:20px;line-height:300%'>$msg</div>");
	$winform = $win->GetWindow("hand","");
	$win->Display();
	exit();
}
/*--------------------
function __Delete()
--------------------*/
else if($dopost=="delete")
{
	CheckPurview('c_Del');
	$row = $dsql->GetOne("Select * From `#@__channeltype` where id='$id'");
	if($row['issystem'] == 1)
	{
		ShowMsg("ϵͳģ�Ͳ�����ɾ����","mychannel_main.php");
		exit();
	}
	if(empty($job))
	{
		$job="";
	}
	if($job=="") //ȷ����ʾ
	{
		require_once(DEDEINC."/oxwindow.class.php");
		$wintitle = "Ƶ������-ɾ��ģ��";
		$wecome_info = "<a href='mychannel_main.php'>Ƶ������</a>::ɾ��ģ��";
		$win = new OxWindow();
		$win->Init("mychannel_edit.php","js/blank.js","POST");
		$win->AddHidden("job","yes");
		$win->AddHidden("dopost",$dopost);
		$win->AddHidden("id",$id);
		$win->AddTitle("��ȷʵҪɾ�� (".$row['typename'].") ���Ƶ����");
		$winform = $win->GetWindow("ok");
		$win->Display();
		exit();
	}
	else if($job=="yes") //����
	{
		require_once(DEDEINC."/typeunit.class.admin.php");
		$myrow = $dsql->GetOne("Select addtable From `#@__channeltype` where id='$id'",MYSQL_ASSOC);
		if(!is_array($myrow))
		{
			ShowMsg('����ָ����Ƶ����Ϣ������!','-1');
			exit();
		}

		//���Ƶ���ı��Ƿ��ռ���ݱ�
		$addtable = str_replace($cfg_dbprefix,'',str_replace('#@__',$cfg_dbprefix,$myrow['addtable']));
		$row = $dsql->GetOne("Select count(id) as dd From `#@__channeltype` where  addtable like '{$cfg_dbprefix}{$addtable}' Or addtable like CONCAT('#','@','__','$addtable') ; ");
		$isExclusive2 = ($row['dd']>1 ? 0 : 1 );

		//��ȡ��Ƶ��������������Ŀid
		$tids = '';
		$dsql->Execute('qm',"Select id From `#@__arctype` where channeltype='$id'");
		while($row = $dsql->GetArray('qm'))
		{
			$tids .= ($tids=='' ? $row['id'] : ','.$row['id']);
		}

		//ɾ�������Ϣ
		if($tids!='')
		{
			$dsql->ExecuteNoneQuery("Delete From `#@__arctiny` where typeid in($tids); ");
			$dsql->ExecuteNoneQuery("Delete From `{$myrow['maintable']}` where typeid in($tids); ");
			$dsql->ExecuteNoneQuery("Delete From `#@__spec` where typeid in ($tids); ");
			$dsql->ExecuteNoneQuery("Delete From `#@__feedback` where typeid in ($tids); ");
			$dsql->ExecuteNoneQuery("Delete From `#@__arctype` where id in ($tids); ");
		}

		//ɾ�����ӱ�򸽼ӱ��ڵ���Ϣ
		if($isExclusive2==1)
		{
			$dsql->ExecuteNoneQuery("DROP TABLE IF EXISTS `{$cfg_dbprefix}{$addtable}`;");
		}
		else
		{
			if($tids!='' && $myrow['addtable']!='')
			{
				$dsql->ExecuteNoneQuery("Delete From `{$myrow['addtable']}` where typeid in ($tids); ");
			}
		}

		//ɾ��Ƶ��������Ϣ
		$dsql->ExecuteNoneQuery("Delete From `#@__channeltype` where id='$id' ");

		//������Ŀ����
		UpDateCatCache($dsql);
		ShowMsg("�ɹ�ɾ��һ��ģ�ͣ�","mychannel_main.php");
		exit();
	}
}//del

/*----------------
function __modifysearch()
-----------------*/
else if($dopost == 'modifysearch'){
	if(!isset($step)) $step=0;
	if(empty($step)){
		$step = 1;
		$mid = intval($mid);
		$query = "select mainfields, addonfields, template from #@__advancedsearch where mid='$mid'";
		$searchinfo = $dsql->GetOne($query);
		if(!is_array($searchinfo)){
			$searchinfo['mainfields'] = $searchinfo['addonfields'] = $searchinfo['template'] = '';
		}
		$searchinfo['mainfields'] = explode(',', $searchinfo['mainfields']);
		$searchinfo['addonfields'] = explode(',', $searchinfo['addonfields']);
		$addonfieldsarr = array();
		foreach($searchinfo['addonfields'] as $k){
			$karr = explode(':', $k);
			$addonfieldsarr[] = $karr[0];
		}
		$template = $searchinfo['template'] == '' ? 'advancedsearch.htm' : $searchinfo['template'];
		$c1 = in_array('iscommend', $searchinfo['mainfields']) ? 'checked' : '';
		$c2 = in_array('typeid', $searchinfo['mainfields']) ? 'checked' : '';
		$c3 = in_array('writer', $searchinfo['mainfields']) ? 'checked' : '';
		$c4 = in_array('source', $searchinfo['mainfields']) ? 'checked' : '';
		$c5 = in_array('senddate', $searchinfo['mainfields']) ? 'checked' : '';


		$mainfields = '<label><input type="checkbox" name="mainfields[]" '.$c1.' value="iscommend" class="np" />�Ƿ��Ƽ�</label>';
		$mainfields .= '<label><input type="checkbox" name="mainfields[]" '.$c2.' value="typeid" class="np" />��Ŀ</label>';

		$mainfields .= '<label><input type="checkbox" name="mainfields[]" '.$c3.' value="writer" class="np" />����</label>';
		$mainfields .= '<label><input type="checkbox" name="mainfields[]" '.$c4.' value="source" class="np" />��Դ</label>';
		$mainfields .= '<label><input type="checkbox" name="mainfields[]" '.$c5.' value="senddate" class="np" />����ʱ��</label>';
		/*
		$mainfields .= '<label><input type="checkbox" name="mainfields[]" value="description" />ժҪ</label>';
		$mainfields .= '<label><input type="checkbox" name="mainfields[]" value="keywords" />�ؼ���</label>';
		$mainfields .= '<label><input type="checkbox" name="mainfields[]" value="smalltypeid" />С����</label>';
		$mainfields .= '<label><input type="checkbox" name="mainfields[]" value="area" />����</label>';
		$mainfields .= '<label><input type="checkbox" name="mainfields[]" value="sector" />��ҵ</label>';
		*/
		$query = "select * from `#@__channeltype` where id='$mid'";
		$channel = $dsql->GetOne($query);

		$searchtype = array('int', 'datetime', 'float', 'textdata', 'textchar', 'text', 'htmltext', 'multitext', 'select', 'radio', 'checkbox');
		$addonfields = '';
		$dtp = new DedeTagParse();
		$dtp->SetNameSpace("field","<",">");
		$dtp->LoadSource($channel['fieldset']);
		if($channel['issystem'] < 0)
		{
			$checked = in_array('typeid', $addonfieldsarr) ? 'checked' : '';
			$addonfields .= '<label><input type="checkbox" name="addonfields[]" '.$checked.' value="typeid" class="np" />��Ŀ</label>';
			$checked = in_array('senddate', $addonfieldsarr) ? 'checked' : '';
			$addonfields .= '<label><input type="checkbox" name="addonfields[]" '.$checked.' value="senddate" class="np" />����ʱ��</label>';

		}
		if(is_array($dtp->CTags) && !empty($dtp->CTags)){
			foreach($dtp->CTags as $ctag){
				$datatype = $ctag->GetAtt('type');
				$value = $ctag->GetName();
				if($channel['issystem'] < 0)
				{
					$_oo = array('channel','arcrank', 'title', 'senddate', 'mid', 'click', 'flag', 'litpic', 'userip', 'lastpost', 'scores', 'goodpost', 'badpost', 'endtime');
					if(in_array($value, $_oo))
					{
						continue;
					}
				}

				$label = $ctag->GetAtt('itemname');
				if(in_array($datatype, $searchtype)){
					$checked = in_array($value, $addonfieldsarr) ? 'checked' : '';
					$addonfields .= "<label><input type=\"checkbox\" name=\"addonfields[]\" $checked value=\"$value\" class='np' />$label</label>";
				}
			}
		}
		require_once(dirname(__FILE__)."/templets/mychannel_modifysearch.htm");
	}elseif($step == 1){
		$query = "select * from `#@__channeltype` where id='$mid'";
		$channel = $dsql->GetOne($query);
		if(empty($addonfields))
		{
			$addonfields = '';
		}
		$template = trim($template);
		$forms = '<form action="'.$cfg_cmspath.'/plus/advancedsearch.php" method="post">';
		$forms .= "<input type=\"hidden\" name=\"mid\" value=\"$mid\" />";
		$forms .= "<input type=\"hidden\" name=\"dopost\" value=\"search\" />";
		$forms .= "�ؼ��ʣ�<input type=\"text\" name=\"q\" /><br />";
		$mainstring = '';
		if(!empty($mainfields) && is_array($mainfields)){
			$mainstring = implode(',', $mainfields);
			foreach($mainfields as $mainfield){
				if($mainfield == 'typeid'){
					require_once(dirname(__FILE__)."/../include/typelink.class.php");
					$tl = new TypeLink(0);
					$typeOptions = $tl->GetOptionArray(0,0,$mid);
					$forms .= "<br />��Ŀ��<select name='typeid' style='width:200'>\r\n";
					$forms .= "<option value='0' selected>--������Ŀ--</option>\r\n";
					$forms .= $typeOptions;
					$forms .= "</select>";
					$forms .= "<label><input type=\"checkbox\" name=\"includesons\" value=\"1\" />��������Ŀ</label><br />";
				}elseif($mainfield == 'iscommend'){
					$forms .= "<label><input type=\"checkbox\" name=\"iscommend\" value=\"1\" />�Ƽ�</label><br />";
				}elseif($mainfield == 'writer'){
					$forms .= "���ߣ� <input type=\"text\" name=\"writer\" value=\"\" /><br />";
				}elseif($mainfield == 'source'){
					$forms .= "��Դ�� <input type=\"text\" name=\"source\" value=\"\" /><br />";
				}elseif($mainfield == 'senddate'){
					$forms .= "��ʼʱ�䣺<input type=\"text\" name=\"startdate\" value=\"\" /><br />";
					$forms .= "����ʱ�䣺<input type=\"text\" name=\"enddate\" value=\"\" /><br />";
				}

			}
		}

		$addonstring = '';

		$intarr = array('int','float');
		$textarr = array('textdata','textchar','text','htmltext','multitext');

		if($channel['issystem'] < 0)
		{
			foreach($addonfields as $addonfield)
			{
				if($addonfield == 'typeid'){
					require_once(dirname(__FILE__)."/../include/typelink.class.php");
					$tl = new TypeLink(0);
					$typeOptions = $tl->GetOptionArray(0,0,$mid);
					$forms .= "<br />��Ŀ��<select name='typeid' style='width:200'>\r\n";
					$forms .= "<option value='0' selected>--������Ŀ--</option>\r\n";
					$forms .= $typeOptions;
					$forms .= "</select>";
					$forms .= "<label><input type=\"checkbox\" name=\"includesons\" value=\"1\" />��������Ŀ</label><br />";
					$addonstring .= 'typeid:int,';
				} elseif($addonfield == 'senddate') {
					$forms .= "��ʼʱ�䣺<input type=\"text\" name=\"startdate\" value=\"\" /><br />";
					$forms .= "����ʱ�䣺<input type=\"text\" name=\"enddate\" value=\"\" /><br />";
					$addonstring .= 'senddate:datetime,';
				}
			}
		}

		if(is_array($addonfields) && !empty($addonfields)){

			$query = "select * from #@__channeltype where id='$mid'";
			$channel = $dsql->GetOne($query);

			$dtp = new DedeTagParse();
			$dtp->SetNameSpace("field","<",">");
			$dtp->LoadSource($channel['fieldset']);
			$fieldarr = $itemarr = $typearr = array();
			foreach($dtp->CTags as $ctag){
				foreach($addonfields as $addonfield){

					if($ctag->GetName() == $addonfield){
						if($addonfield == 'typeid' || $addonfield == 'senddate')
						{
							continue;
						}
						$fieldarr[] = $addonfield;
						$itemarr[] = $ctag->GetAtt('itemname');
						$typearr[] = $ctag->GetAtt('type');
						$valuearr[] = $ctag->GetAtt('default');
					}
				}
			}

			foreach($fieldarr as $k=>$field){
				$itemname = $itemarr[$k];
				$name = $field;
				$type = $typearr[$k];
				$tmp = $name.':'.$type;
				if(in_array($type, $intarr)){
					$forms .= "<br />$itemname : <input type=\"text\" name=\"start".$name."\" value=\"\" /> �� <input type=\"text\" name=\"end".$name."\" value=\"\" /><br />";
				}elseif(in_array($type, $textarr)){
					$forms .= "$itemname : <input type=\"text\" name=\"$name\" value=\"\" /><br />";

				}elseif($type == 'select'){
					$values = explode(',', $valuearr[$k]);
					if(is_array($values) && !empty($values)){
						$forms .= "<br />$itemname : <select name=\"$name\" ><option value=\"\">����</option>";
						foreach($values as $value){
							$forms .= "<option value=\"$value\">$value</option>";
						}
						$forms .= "</select>";
					}
				}elseif($type == 'radio'){
					$values = explode(',', $valuearr[$k]);
					if(is_array($values) && !empty($values)){
						$forms .= "<br />$itemname : <label><input type=\"radio\" name=\"".$name."\" value=\"\" checked />����</label>";
						foreach($values as $value){
							$forms .= "<label><input type=\"radio\" name=\"".$name."\" value=\"$value\" />$value</label>";
						}
					}
				}elseif($type == 'checkbox'){
					$values = explode(',', $valuearr[$k]);
					if(is_array($values) && !empty($values)){
						$forms .= "<br />$itemname : ";
						foreach($values as $value){
							$forms .= "<label><input type=\"checkbox\" name=\"".$name."[]\" value=\"$value\" />$value</label>";
						}
					}

				}elseif($type == 'datetime'){
					$forms .= "<br />��ʼʱ�䣺<input type=\"text\" name=\"startdate\" value=\"\" /><br />";
					$forms .= "����ʱ�䣺<input type=\"text\" name=\"enddate\" value=\"\" /><br />";
				}else{
					$tmp = '';
				}
				$addonstring .= $tmp.',';

			}

		}
		$forms .= '<input type="submit" name="submit" value="��ʼ����" /></form>';
		$formssql = addslashes($forms);
		$query = "replace into #@__advancedsearch(mid, maintable, mainfields, addontable, addonfields, forms, template) values('$mid','$maintable','$mainstring','$addontable','$addonstring','$formssql', '$template')";
		$dsql->ExecuteNoneQuery($query);
		$formshtml = htmlspecialchars($forms);
		echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312">';
		echo "����Ϊ���ɵ�html���������и��ƣ������Լ������޸���ʽ��ճ������Ӧ��ģ����<br><br><textarea cols=\"100\"  rows=\"10\">".$forms."</textarea>";
		echo '<br />Ԥ����<br /><hr>';
		echo $forms;

	}

	exit;
}
//ɾ���Զ���������
else if($dopost == 'del')
{
	$mid = intval($mid);
	$dsql->ExecuteNoneQuery("Delete From `#@__advancedsearch` where mid = '$mid'; ");
	ShowMsg("�ɹ�ɾ��һ���Զ���������","mychannel_main.php");
	exit();
}
$row = $dsql->GetOne("Select * From `#@__channeltype` where id='$id' ");
require_once(DEDEADMIN."/templets/mychannel_edit.htm");

?>