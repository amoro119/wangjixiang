<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/oxwindow.class.php");
if(!isset($nid))
{
	$nid=0;
}
$ENV_GOBACK_URL = empty($_COOKIE["ENV_GOBACK_URL"]) ? "co_url.php" : $_COOKIE["ENV_GOBACK_URL"];

//ɾ���ڵ�
//ɾ���ڵ㽫ɾ�����оɵ���ַ����
/*
function co_delete()
*/
if($dopost=="delete")
{
	CheckPurview('co_Del');
	$nid = intval($nid);
	$dsql->ExecuteNoneQuery("Delete From `#@__co_htmls` where nid='$nid'");
	$dsql->ExecuteNoneQuery("Delete From `#@__co_note` where nid='$nid'");
	$dsql->ExecuteNoneQuery("Delete From `#@__co_urls` where nid='$nid'");
	ShowMsg("�ɹ�ɾ��һ���ڵ�!","co_main.php");
	exit();
}

//��ղɼ�����
//��ղɼ�����ʱ�Իᱣ���ɵ���ַ�������ڼ��ģʽ��ʼ�ղɼ��µ�����
/*
function url_clear()
*/
else if($dopost=="clear")
{
	CheckPurview('co_Del');
	if(!isset($ids))
	{
		$ids='';
	}
	if(empty($ids))
	{
		if(!empty($nid))
		{
			$nid = intval($nid);
			$dsql->ExecuteNoneQuery("Delete From `#@__co_htmls` where nid='$nid'");
		}
		ShowMsg("�ɹ����һ���ڵ�ɼ�������!","co_main.php");
		exit();
	}
	else
	{
		if(!empty($clshash))
		{
			$dsql->SetQuery("Select nid,url From `#@__co_htmls` where aid in($ids) ");
			$dsql->Execute();
			while($arr = $dsql->GetArray())
			{
				$nhash = md5($arr['url']);
				$nid = $row['nid'];
				$dsql->ExecuteNoneQuery("Delete From `#@__co_urls ` where nid='$nid' And hash='$nhash' ");
			}
		}
		$dsql->ExecuteNoneQuery("Delete From `#@__co_htmls` where aid in($ids) ");
		ShowMsg("�ɹ�ɾ��ָ������ַ����!",$ENV_GOBACK_URL);
		exit();
	}
}
else if($dopost=="clearct")
{
	CheckPurview('co_Del');
	if(!empty($ids))
	{
		$dsql->ExecuteNoneQuery("Update `#@__co_htmls` set isdown=0,result='' where aid in($ids) ");
	}
	ShowMsg("�ɹ������������!",$ENV_GOBACK_URL);
	exit();
}

/*
function url_clearall()
*/
else if($dopost=="clearall")
{
	CheckPurview('co_Del');
	$dsql->ExecuteNoneQuery("Delete From `#@__co_htmls` ");
	ShowMsg("�ɹ�������вɼ�����ʱ����!","co_main.php");
	exit();
}

//�����滻
/*
function co_replace() { }
*/
else if($dopost=="replace")
{
	//if()
	//$nid $aid $regtype $fdstring $rpstring
	$rpstring = trim($rpstring);
	if($regtype=='string')
	{
		$dsql->ExecuteNoneQuery("Update `#@__co_htmls` set `result`=REPLACE(`result`,'$fdstring','$rpstring') where nid='$nid' ");
	}
	else
	{
		//����һ�����Խ������Ҫ���û�ȷ�ϲ���
		if(empty($rpok))
		{
			$fdstring = stripslashes($fdstring);
			$rpstring = stripslashes($rpstring);
			$hiddenrpvalue = "<textarea name='fdstring' style='display:none'>{$fdstring}</textarea>\r\n<textarea name='rpstring' style='display:none'>{$rpstring}</textarea>\r\n";
			$fdstring = str_replace("\\/","#ASZZ#",$fdstring);
			$fdstring = str_replace('/',"\\/",$fdstring);
			$fdstring = str_replace('#ASZZ#',"\\/",$fdstring);
			$result = $rs = stripslashes($rs);
			if($fdstring!='')
			{
				$result = trim(preg_replace("/$fdstring/isU",$rpstring,$rs));
			}
			$wintitle = "�ɼ�����-�����滻";
			$wecome_info = "<a href='co_main.php'>�ɼ�����</a>::�����滻";
			$win = new OxWindow();
			$win->Init("co_do.php","js/blank.js","POST");
			$win->AddHidden('dopost',$dopost);
			$win->AddHidden('nid',$nid);
			$win->AddHidden('regtype','regex');
			$win->AddHidden('aid',$aid);
			$win->AddHidden('rpok','ok');
			$win->AddTitle("�����滻����ȷ�ϣ������������ȷ�����ȷ�ϣ�ϵͳ���滻��ǰ�ڵ��������ݣ�{$hiddenrpvalue}");
			$win->AddItem("ԭ�������ݣ�","<textarea name='rs' style='width:90%;height:250px'>{$rs}</textarea>\r\n");
			$win->AddItem("�������滻������ݣ�","<textarea name='okrs' style='width:90%;height:250px'>{$result}</textarea>\r\n");
			$winform = $win->GetWindow("ok");
			$win->Display();
			exit();
		}
		else
		{
			if($fdstring!='')
			{
				$dsql->SetQuery("Select `aid`,`result` From `#@__co_htmls` where nid='$nid' ");
				$dsql->Execute();
				while($row = $dsql->GetArray())
				{
					$fdstring = stripslashes($fdstring);
					$rpstring = stripslashes($rpstring);
					$fdstring = str_replace("\\/","#ASZZ#",$fdstring);
					$fdstring = str_replace('/',"\\/",$fdstring);
					$fdstring = str_replace('#ASZZ#',"\\/",$fdstring);
					$result = trim(preg_replace("/$fdstring/isU",$rpstring,$row['result']));
					$result = addslashes($result);
					$dsql->ExecuteNoneQuery("Update `#@__co_htmls` set `result`='$result' where aid='{$row['aid']}' ");
				}
			}
		}
	}
	ShowMsg("�ɹ��滻��ǰ�ڵ��������ݣ�","co_view.php?aid=$aid");
	exit();
}

//���ƽڵ�
/*
function co_copy()
*/
else if($dopost=="copy")
{
	CheckPurview('co_AddNote');
	if(empty($mynotename))
	{
		$wintitle = "�ɼ�����-���ƽڵ�";
		$wecome_info = "<a href='co_main.php'>�ɼ�����</a>::���ƽڵ�";
		$win = new OxWindow();
		$win->Init("co_do.php","js/blank.js","POST");
		$win->AddHidden("dopost",$dopost);
		$win->AddHidden("nid",$nid);
		$win->AddTitle("�������½ڵ����ƣ�");
		$win->AddItem("�½ڵ����ƣ�","<input type='text' name='mynotename' value='' size='30' />");
		$winform = $win->GetWindow("ok");
		$win->Display();
		exit();
	}
	$row = $dsql->GetOne("Select * From `#@__co_note` where nid='$nid'");
	foreach($row as $k=>$v)
	{
		if(!isset($$k))
		{
			$$k = addslashes($v);
		}
	}
	$usemore = (empty($usemore) ? '0' : $usemore);
	$inQuery = " INSERT INTO `#@__co_note`(`channelid`,`notename`,`sourcelang`,`uptime`,`cotime`,`pnum`,`isok`,`listconfig`,`itemconfig`,`usemore`)
               VALUES ('$channelid','$mynotename','$sourcelang','".time()."','0','0','0','$listconfig','$itemconfig','$usemore'); ";
	$dsql->ExecuteNoneQuery($inQuery);
	ShowMsg("�ɹ�����һ���ڵ�!",$ENV_GOBACK_URL);
	exit();
}

//����RssԴ�Ƿ���ȷ
/*-----------------------
function co_testrss()
-------------------------*/
else if($dopost=="testrss")
{
	CheckPurview('co_AddNote');
	$msg = '';
	if($rssurl=='')
	{
		$msg = '��û��ָ��RSS��ַ��';
	}
	else
	{
		include(DEDEINC."/dedecollection.func.php");
		$arr = GetRssLinks($rssurl);
		$msg = "�� {$rssurl} ���ֵ���ַ��<br />";
		$i=1;
		if(is_array($arr))
		{
			foreach($arr as $ar)
			{
				$msg .= "<hr size='1' />\r\n";
				$msg .= "link: {$ar['link']}<br />title: {$ar['title']}<br />image: {$ar['image']}\r\n";
				$i++;
			}
		}
	}
	$wintitle = "�ɼ�����-����";
	$wecome_info = "<a href='co_main.php'>�ɼ�����</a>::RSS��ַ����";
	$win = new OxWindow();
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand");
	$win->Display();
	exit();
}

//����������ַ�Ƿ���ȷ
/*-----------------------
function co_testregx()
-------------------------*/
else if($dopost=="testregx")
{
	CheckPurview('co_AddNote');
	$msg = '';
	if($regxurl=='')
	{
		$msg = '��û��ָ��ƥ�����ַ��';
	}
	else
	{
		include(DEDEINC."/dedecollection.func.php");
		$msg = "ƥ�����ַ��<br />";
		$lists = GetUrlFromListRule($regxurl,'',$startid,$endid,$addv);
		foreach($lists as $surl)
		{
			$msg .= $surl[0]."<br />\r\n";
		}
	}
	$wintitle = "�ɼ�����-����ƥ�����";
	$wecome_info = "<a href='co_main.php'>�ɼ�����</a>::����ƥ���б���ַ����";
	$win = new OxWindow();
	$win->AddMsgItem($msg);
	$winform = $win->GetWindow("hand");
	$win->Display();
	exit();
}

//�ɼ�δ��������
/*--------------------
function co_all()
---------------------*/
else if($dopost=="coall")
{
	CheckPurview('co_PlayNote');
	$mrow = $dsql->GetOne("Select count(*) as dd From `#@__co_htmls` ");
	$totalnum = $mrow['dd'];
	if($totalnum==0)
	{
		ShowMsg("û���ֿ����ص����ݣ�","-1");
		exit();
	}
	$wintitle = "�ɼ�����-�ɼ�δ��������";
	$wecome_info = "<a href='co_main.php'>�ɼ�����</a>::�ɼ�δ��������";
	$win = new OxWindow();
	$win->Init("co_gather_start_action.php","js/blank.js","GET");
	$win->AddHidden('startdd','0');
	$win->AddHidden('pagesize','5');
	$win->AddHidden('sptime','0');
	$win->AddHidden('nid','0');
	$win->AddHidden('totalnum',$totalnum);
	$win->AddMsgItem("���������Ⲣ���ء�<a href='co_url.php'><u>��ʱ����</u></a>��������δ���ص����ݣ��Ƿ������");
	$winform = $win->GetWindow("ok");
	$win->Display();
	exit();
}

?>