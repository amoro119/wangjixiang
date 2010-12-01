<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_module');
require_once(dirname(__FILE__)."/../include/dedemodule.class.php");
require_once(dirname(__FILE__)."/../include/oxwindow.class.php");
if(empty($action)) $action = '';
$mdir = DEDEROOT.'/data/module';

function TestWriteAble($d)
{
	$tfile = '_dedet.txt';
	$d = ereg_replace('/$','',$d);
	$fp = @fopen($d.'/'.$tfile,'w');
	if(!$fp) return false;
	else
	{
		fclose($fp);
		$rs = @unlink($d.'/'.$tfile);
		if($rs) return true;
		else return false;
	}
}

function ReWriteConfigAuto()
{
	global $dsql;
	$configfile = DEDEROOT.'/data/config.cache.inc.php';
	if(!is_writeable($configfile))
	{
		echo "�����ļ�'{$configfile}'��֧��д�룬�޷��޸�ϵͳ���ò�����";
		//ClearAllLink();
		exit();
	}
	$fp = fopen($configfile,'w');
	flock($fp,3);
	fwrite($fp,"<"."?php\r\n");
	$dsql->SetQuery("Select `varname`,`type`,`value`,`groupid` From `#@__sysconfig` order by aid asc ");
	$dsql->Execute();
	while($row = $dsql->GetArray())
	{
		if($row['type']=='number') fwrite($fp,"\${$row['varname']} = ".$row['value'].";\r\n");
		else fwrite($fp,"\${$row['varname']} = '".str_replace("'",'',$row['value'])."';\r\n");
	}
	fwrite($fp,"?".">");
	fclose($fp);
}

/*--------------
function ShowAll();
--------------*/
if($action=='')
{
	$types = array('soft'=>'ģ��','templets'=>'ģ��','plus'=>'С���','patch'=>'����');
	$dm = new DedeModule($mdir);
	if(empty($moduletype)) $moduletype = '';
	$modules = $dm->GetModuleList($moduletype);
	require_once(dirname(__FILE__)."/templets/module_main.htm");
	$dm->Clear();
	exit();
}
/*--------------
function Setup();
--------------*/
else if($action=='setup')
{
	$dm = new DedeModule($mdir);
	$infos = $dm->GetModuleInfo($hash);

	if($infos['url']=='') $infos['url'] = '&nbsp;';
	$alertMsg = ($infos['lang']==$cfg_soft_lang ? '' : '<br /><font color="red">(���ģ������Ա�������ϵͳ�ı��벻һ�£����򿪷���ȷ�����ļ�����)</font>');

	$filelists = $dm->GetFileLists($hash);
	$filelist = '';
	$prvdirs = array();
	$incdir = array();
	foreach($filelists as $v)
	{
		if(empty($v['name'])) continue;
		if($v['type']=='dir')
		{
			$v['type'] = 'Ŀ¼';
			$incdir[] = $v['name'];
		}
		else
		{
			$v['type'] = '�ļ�';
		}
		$filelist .= "{$v['type']}|{$v['name']}\r\n";
	}
	//�����Ҫ��Ŀ¼Ȩ��
	foreach($filelists as $v)
	{
		$prvdir = ereg_replace('/([^/]*)$','/',$v['name']);
		if(!ereg('^\.',$prvdir)) $prvdir = './';
		$n = true;
		foreach($incdir as $k=>$v)
		{
			if(eregi("^".$v,$prvdir))
			{
				$n = false;
				break;
			}
		}
		if(!isset($prvdirs[$prvdir]) && $n && is_dir($prvdir))
		{
			$prvdirs[$prvdir][0] = 1;
			$prvdirs[$prvdir][1] = TestWriteAble($prvdir);
		}
	}
	$prvdir = "<table width='350'>\r\n";
	$prvdir .= "<tr style='background:#D1F5B6'><th width='270'>Ŀ¼</td><th align='center'>��д</td></tr>\r\n";
	foreach($prvdirs as $k=>$v)
	{
		if($v) $cw = '��';
		else $cw = '<font color="red">��</font>';
		$prvdir .= "<tr><td style='border-bottom:1px solid #D1F5B6'>$k</td>";
		$prvdir .= "<td align='center' style='border-bottom:1px solid #D1F5B6'>$cw</td></tr>\r\n";
	}
	$prvdir .= "</table>";

	$win = new OxWindow();
	$win->Init("module_main.php","js/blank.js","post");
	$win->mainTitle = "ģ�����";
	$win->AddTitle("<a href='module_main.php'>ģ�����</a> &gt;&gt; ��װģ�飺 {$infos['name']}");
	$win->AddHidden("hash",$hash);
	$win->AddHidden("action",'setupstart');
	if(trim($infos['url'])=='') $infos['url'] = '��';
	$msg = "<style>.dtb{border-bottom:1px dotted #cccccc}</style>
	<table width='98%' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='20%' height='28' class='dtb'>ģ�����ƣ�</td>
    <td width='80%' class='dtb'>{$infos['name']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>���ԣ�</td>
    <td class='dtb'>{$infos['lang']} {$alertMsg}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�ļ���С��</td>
    <td class='dtb'>{$infos['filesize']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�Ŷ����ƣ�</td>
    <td class='dtb'>{$infos['team']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>����ʱ�䣺</td>
    <td class='dtb'>{$infos['time']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�������䣺</td>
    <td class='dtb'>{$infos['email']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�ٷ���ַ��</td>
    <td class='dtb'>{$infos['url']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>ʹ��Э�飺</td>
    <td class='dtb'><a href='module_main.php?action=showreadme&hash={$hash}' target='_blank'>������...</a></td>
  </tr>
  <tr>
    <td height='30' class='dtb' bgcolor='#FCFE9C' colspan='2'>
    <b>ע�����</b>
    ��װʱ��ȷ���ļ��б����漰��Ŀ¼ǰ��д��Ȩ�ޣ����⡰��̨����Ŀ¼��������̨����Ŀ¼/templets��Ŀ¼Ҳ������ʱ���ÿ�д��Ȩ�ޡ�
    </td>
  </tr>
  <tr>
    <td height='30'><b>Ŀ¼Ȩ�޼�⣺</b><br /> ../ Ϊ��Ŀ¼ <br /> ./ ��ʾ��ǰĿ¼</td>
    <td>
    $prvdir
    </td>
  </tr>
  <tr>
    <td height='30'>ģ������������ļ��б�</td>
    <td></td>
  </tr>
  <tr>
    <td height='164' colspan='2'>
     <textarea name='filelists' id='filelists' style='width:90%;height:200px'>{$filelist}</textarea>
    </td>
  </tr>
  <tr>
    <td height='28'>�����Ѵ����ļ���������</td>
    <td>
   <input name='isreplace' type='radio' value='1' checked='checked' />
    ����
   <input name='isreplace' type='radio' value='3' />
   ���ǣ���������
   <input type='radio' name='isreplace' value='0' />
   �������ļ�
   </td>
  </tr>
</table>
	";
	$win->AddMsgItem("<div style='padding-left:10px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow("ok","");
	$win->Display();
	$dm->Clear();
	exit();
}
/*---------------
function SetupRun()
--------------*/
else if($action=='setupstart')
{
	if(!is_writeable($mdir))
	{
		ShowMsg("Ŀ¼ {$mdir} ��֧��д�룬�⽫���°�װ����û������������","-1");
		exit();
	}
	$dm = new DedeModule($mdir);

	$minfos = $dm->GetModuleInfo($hash);
	extract($minfos, EXTR_SKIP);

	$menustring = addslashes($dm->GetSystemFile($hash,'menustring'));

	$query = "INSERT INTO `#@__sys_module`(`hashcode` , `modname` , `indexname` , `indexurl` , `ismember` , `menustring` )
                                    VALUES ('$hash' , '$name' , '$indexname' , '$indexurl' , '$ismember' , '$menustring' ) ";

	$rs = $dsql->ExecuteNoneQuery("Delete From `#@__sys_module` where hashcode like '$hash' ");
	$rs = $dsql->ExecuteNoneQuery($query);
	if(!$rs)
	{
		ShowMsg('�������ݿ���Ϣʧ�ܣ��޷���ɰ�װ��'.$dsql->GetError(),'javascript:;');
		exit();
	}

	$dm->WriteFiles($hash,$isreplace);
	$filename = '';
	if(!isset($autosetup) || $autosetup==0) $filename = $dm->WriteSystemFile($hash,'setup');
	if(!isset($autodel) || $autodel==0) $dm->WriteSystemFile($hash,'uninstall');
	$dm->WriteSystemFile($hash,'readme');
	$dm->Clear();

	//��ģ��İ�װ����װ
	if(!isset($autosetup) || $autosetup==0)
	{
		include(DEDEROOT.'/data/module/'.$filename);
		exit();
	}
	//ϵͳ�Զ���װ
	else
	{
		$mysql_version = $dsql->GetVersion(true);
		//Ĭ��ʹ��MySQL 4.1 ���°汾��SQL��䣬�Դ���4.1�汾�����滻���� TYPE=MyISAM ==> ENGINE=MyISAM DEFAULT CHARSET=#~lang~#
		$setupsql = $dm->GetSystemFile($hash,'setupsql40');
		
		$setupsql = eregi_replace('ENGINE=MyISAM','TYPE=MyISAM',$setupsql);
		
		$sql41tmp = 'ENGINE=MyISAM DEFAULT CHARSET='.$cfg_db_language;
		
		if($mysql_version >= 4.1)
		{
			$setupsql = eregi_replace('TYPE=MyISAM',$sql41tmp,$setupsql);
		}
		
		
		//_ROOTURL_
		if($cfg_cmspath=='/') {
			$cfg_cmspath = '';
		}
		$rooturl = $cfg_basehost.$cfg_cmspath;
		
		$setupsql = eregi_replace('_ROOTURL_',$rooturl,$setupsql);
		$setupsql = ereg_replace("[\r\n]{1,}","\n",$setupsql);
		
		$sqls = split(";[ \t]{0,}\n", $setupsql);
		foreach($sqls as $sql)
		{
			if(trim($sql)!='') $dsql->executenonequery($sql);
		}
		
		ReWriteConfigAuto();
		
		$rflwft = "<script language='javascript' type='text/javascript'>\r\n";
		$rflwft .= "if(window.navigator.userAgent.indexOf('MSIE')>=1) top.document.frames.menu.location = 'index_menu_module.php';\r\n";
		$rflwft .= "else top.document.getElementById('menu').src = 'index_menu_module.php';\r\n";
		$rflwft .= "</script>";
		echo $rflwft;
		
		UpDateCatCache();
		ShowMsg('ģ�鰲װ���...','module_main.php');
		exit();
	}
}
/*--------------
function DelModule();
--------------*/
else if($action=='del')
{
	$dm = new DedeModule($mdir);
	$infos = $dm->GetModuleInfo($hash);

	if($infos['url']=='') $infos['url'] = '&nbsp;';
	$alertMsg = ($infos['lang']==$cfg_soft_lang ? '' : '<br /><font color="red">(���ģ������Ա�������ϵͳ�ı��벻һ�£����򿪷���ȷ�����ļ�����)</font>');

	$win = new OxWindow();
	$win->Init("module_main.php","js/blank.js","post");
	$win->mainTitle = "ģ�����";
	$win->AddTitle("<a href='module_main.php'>ģ�����</a> &gt;&gt; ɾ��ģ�飺 {$infos['name']}");
	$win->AddHidden('hash',$hash);
	$win->AddHidden('action','delok');
	$msg = "<style>.dtb{border-bottom:1px dotted #cccccc}</style>
	<table width='750' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='200' height='28' class='dtb'>ģ�����ƣ�</td>
    <td width='550' class='dtb'>{$infos['name']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>���ԣ�</td>
    <td class='dtb'>{$infos['lang']} {$alertMsg}</td>
  </tr>
  <tr>
    <td width='200' height='28' class='dtb'>�ļ���С��</td>
    <td width='550' class='dtb'>{$infos['filesize']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�Ŷ����ƣ�</td>
    <td class='dtb'>{$infos['team']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>����ʱ�䣺</td>
    <td class='dtb'>{$infos['time']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�������䣺</td>
    <td class='dtb'>{$infos['email']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�ٷ���ַ��</td>
    <td class='dtb'>{$infos['url']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>ʹ��Э�飺</td>
    <td class='dtb'><a href='module_main.php?action=showreadme&hash={$hash}' target='_blank'>������...</a></td>
  </tr>
  <tr>
    <td height='28' colspan='2'>
    ɾ��ģ���ɾ�����ģ��İ�װ���ļ���������Ѿ���װ����ִ��<a href='module_main.php?hash={$hash}&action=uninstall'><u>ж�س���</u></a>��ɾ����
   </td>
  </tr>
</table>
	";
	$win->AddMsgItem("<div style='padding-left:10px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow("ok","");
	$win->Display();
	$dm->Clear();
	exit();
}
else if($action=='delok')
{
	$dm = new DedeModule($mdir);
	$modfile = $mdir."/".$dm->GetHashFile($hash);
	unlink($modfile) or die("ɾ���ļ� {$modfile} ʧ�ܣ�");
	ShowMsg("�ɹ�ɾ��һ��ģ���ļ���","module_main.php");
	exit();
}
/*--------------
function UnInstall();
--------------*/
else if($action=='uninstall')
{
	$dm = new DedeModule($mdir);
	$infos = $dm->GetModuleInfo($hash);

	if($infos['url']=='') $infos['url'] = '&nbsp;';
	$alertMsg = ($infos['lang']==$cfg_soft_lang ? '' : '<br /><font color="red">(���ģ������Ա�������ϵͳ�ı��벻һ�£����򿪷���ȷ�����ļ�����)</font>');

	$filelists = $dm->GetFileLists($hash);
	$filelist = '';
	foreach($filelists as $v)
	{
		if(empty($v['name'])) continue;
		if($v['type']=='dir') $v['type'] = 'Ŀ¼';
		else $v['type'] = '�ļ�';
		$filelist .= "{$v['type']}|{$v['name']}\r\n";
	}
	$win = new OxWindow();
	$win->Init("module_main.php","js/blank.js","post");
	$win->mainTitle = "ģ�����";
	$win->AddTitle("<a href='module_main.php'>ģ�����</a> &gt;&gt; ж��ģ�飺 {$infos['name']}");
	$win->AddHidden("hash",$hash);
	$win->AddHidden("action",'uninstallok');
	$msg = "<style>.dtb{border-bottom:1px dotted #cccccc}</style>
	<table width='750' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='200' height='28' class='dtb'>ģ�����ƣ�</td>
    <td width='550' class='dtb'>{$infos['name']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>���ԣ�</td>
    <td class='dtb'>{$infos['lang']} {$alertMsg}</td>
  </tr>
  <tr>
    <td width='200' height='28' class='dtb'>�ļ���С��</td>
    <td width='550' class='dtb'>{$infos['filesize']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�Ŷ����ƣ�</td>
    <td class='dtb'>{$infos['team']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>����ʱ�䣺</td>
    <td class='dtb'>{$infos['time']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�������䣺</td>
    <td class='dtb'>{$infos['email']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�ٷ���ַ��</td>
    <td class='dtb'>{$infos['url']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>ʹ��Э�飺</td>
    <td class='dtb'><a href='module_main.php?action=showreadme&hash={$hash}' target='_blank'>������...</a></td>
  </tr>
  <tr>
    <td height='28'>ģ��������ļ���<br />(�ļ�·������ڵ�ǰĿ¼)</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td height='164' colspan='2'>
     <textarea name='filelists' id='filelists' style='width:90%;height:200px'>{$filelist}</textarea>
    </td>
  </tr>
  <tr>
    <td height='28'>����ģ����ļ���������</td>
    <td>
    <input type='radio' name='isreplace' value='0' checked='checked' />
    �ֹ�ɾ���ļ���������ж�س���
   <input name='isreplace' type='radio' value='2' />
    ɾ��ģ��������ļ�
   </td>
  </tr>
</table>
	";
	$win->AddMsgItem("<div style='padding-left:10px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow("ok","");
	$win->Display();
	$dm->Clear();
	exit();
}
/*--------------
function UnInstallRun();
--------------*/
else if($action=='uninstallok')
{
	$dsql->ExecuteNoneQuery("Delete From `#@__sys_module` where hashcode like '$hash' ");
	$dm = new DedeModule($mdir);

	$minfos = $dm->GetModuleInfo($hash);
	extract($minfos, EXTR_SKIP);

	if(!isset($moduletype) || $moduletype != 'patch' )
	{
		$dm->DeleteFiles($hash,$isreplace);
	}
	@$dm->DelSystemFile($hash,'readme');
	@$dm->DelSystemFile($hash,'setup');
	$dm->Clear();
	if(!isset($autodel) || $autodel==0)
	{
		include(DEDEROOT."/data/module/{$hash}-uninstall.php");
		@unlink(DEDEROOT."/data/module/{$hash}-uninstall.php");
		exit();
	}
	else
	{
		@$dm->DelSystemFile($hash,'uninstall');
		$delsql = $dm->GetSystemFile($hash,'delsql');
		if(trim($delsql)!='')
		{
			$sqls = explode(';', $delsql);
			foreach($sqls as $sql)
			{
				if(trim($sql)!='') $dsql->executenonequery($sql);
			}
		}
		
		ReWriteConfigAuto();
		
		$rflwft = "<script language='javascript' type='text/javascript'>\r\n";
		$rflwft .= "if(window.navigator.userAgent.indexOf('MSIE')>=1) top.document.frames.menu.location = 'index_menu_module.php';\r\n";
		$rflwft .= "else top.document.getElementById('menu').src = 'index_menu_module.php';\r\n";
		$rflwft .= "</script>";
		echo $rflwft;
		
		ShowMsg('ģ��ж�����...','module_main.php');
		exit();
	}
}
/*--------------
function ShowReadme();
--------------*/
else if($action=='showreadme')
{
	$dm = new DedeModule($mdir);
	$msg = $dm->GetSystemFile($hash,'readme');
	$msg = preg_replace("/(.*)<body/isU","",$msg);
	$msg = preg_replace("/<\/body>(.*)/isU","",$msg);
	$dm->Clear();
	$win = new OxWindow();
	$win->Init("module_main.php","js/blank.js","post");
	$win->mainTitle = "ģ�����";
	$win->AddTitle("<a href='module_main.php'>ģ�����</a> &gt;&gt; ʹ��˵����");
	$win->AddMsgItem("<div style='padding-left:10px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow("hand");
	$win->Display();
	exit();
}
/*--------------
function ViewOne();
--------------*/
else if($action=='view')
{
	$dm = new DedeModule($mdir);
	$infos = $dm->GetModuleInfo($hash);

	if($infos['url']=='') $infos['url'] = '&nbsp;';
	$alertMsg = ($infos['lang']==$cfg_soft_lang ? '' : '<br /><font color="red">(���ģ������Ա�������ϵͳ�ı��벻һ�£����򿪷���ȷ�����ļ�����)</font>');

	$filelists = $dm->GetFileLists($hash);
	$filelist = '';
	$setupinfo = '';
	foreach($filelists as $v)
	{
		if(empty($v['name'])) continue;
		if($v['type']=='dir') $v['type'] = 'Ŀ¼';
		else $v['type'] = '�ļ�';
		$filelist .= "{$v['type']}|{$v['name']}\r\n";
	}
	if(file_exists(DEDEROOT."/data/module/{$hash}-readme.php")) {
		$setupinfo = "�Ѱ�װ <a href='module_main.php?action=uninstall&hash={$hash}'>ж��</a>";
	} else {
		$setupinfo = "δ��װ <a href='module_main.php?action=setup&hash={$hash}'>��װ</a>";
	}
	$win = new OxWindow();
	$win->Init("","js/blank.js","");
	$win->mainTitle = "ģ�����";
	$win->AddTitle("<a href='module_main.php'>ģ�����</a> &gt;&gt; ģ�����飺 {$infos['name']}");
	$msg = "<style>.dtb{border-bottom:1px dotted #cccccc}</style>
	<table width='98%' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td width='20%' height='28' class='dtb'>ģ�����ƣ�</td>
    <td width='80%' class='dtb'>{$infos['name']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>���ԣ�</td>
    <td class='dtb'>{$infos['lang']} {$alertMsg}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�ļ���С��</td>
    <td class='dtb'>{$infos['filesize']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�Ƿ��Ѱ�װ��</td>
    <td class='dtb'>{$setupinfo}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�Ŷ����ƣ�</td>
    <td class='dtb'>{$infos['team']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>����ʱ�䣺</td>
    <td class='dtb'>{$infos['time']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�������䣺</td>
    <td class='dtb'>{$infos['email']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>�ٷ���ַ��</td>
    <td class='dtb'>{$infos['url']}</td>
  </tr>
  <tr>
    <td height='28' class='dtb'>ʹ��Э�飺</td>
    <td class='dtb'><a href='module_main.php?action=showreadme&hash={$hash}' target='_blank'>������...</a></td>
  </tr>
  <tr>
    <td height='28'>ģ��������ļ���<br />(�ļ�·������ڵ�ǰĿ¼)</td><td>&nbsp;</td>
  </tr>
  <tr>
    <td height='164' colspan='2'>
     <textarea name='filelists' id='filelists' style='width:90%;height:200px'>{$filelist}</textarea>
    </td>
  </tr>
</table>
	";
	$win->AddMsgItem("<div style='padding-left:10px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow('hand','');
	$win->Display();
	$dm->Clear();
	exit();
}
/*--------------
function Edit();
--------------*/
else if($action=='edit')
{
	$dm = new DedeModule($mdir);

	$minfos = $dm->GetModuleInfo($hash);
	extract($minfos, EXTR_SKIP);

	if(!isset($lang)) $lang = 'gb2312';
	if(!isset($moduletype)) $moduletype = 'soft';

	$menustring = $dm->GetSystemFile($hash,'menustring');

	$setupsql40 = $dm->GetSystemFile($hash,'setupsql40');
	
	$readmetxt = $dm->GetSystemFile($hash,'readme');
	
	$delsql = $dm->GetSystemFile($hash,'delsql');

	$filelist = $dm->GetSystemFile($hash,'oldfilelist',false);
	$dm->Clear();
	require_once(dirname(__FILE__).'/templets/module_edit.htm');
	exit();
}

?>