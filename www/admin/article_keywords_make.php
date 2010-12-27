<?php
@ob_start();
@set_time_limit(3600);
require_once(dirname(__FILE__).'/config.php');
CheckPurview('sys_Keyword');
if(empty($dopost))
{
	$dopost = '';
}

//�����Ѵ��ڵĹؼ��֣�������Ĭ�ϵ�����ģ�ͣ�
if($dopost=='analyse')
{
	echo "���ڶ�ȡ�ؼ������ݿ�...<br/>\r\n";
	flush();
	$ws = $wserr = $wsnew = "";
	$dsql->SetQuery("Select * from `#@__keywords`");
	$dsql->Execute();
	while($row = $dsql->GetObject())
	{
		if($row->sta==1)
		{
			$ws[$row->keyword] = 1;
		}
		else
		{
			$wserr[$row->keyword] = 1;
		}
	}
	echo "��ɹؼ������ݿ�����룡<br/>\r\n";
	flush();
	echo "��ȡ�������ݿ⣬���Խ��õĹؼ��ֺ����ֽ��д���...<br/>\r\n";
	flush();
	$dsql->SetQuery("Select id,keywords from `#@__archives`");
	$dsql->Execute();
	while($row = $dsql->GetObject())
	{
		$keywords = explode(',',trim($row->keywords));
		$nerr = false;
		$mykey = '';
		if(is_array($keywords))
		{
			foreach($keywords as $v)
			{
				$v = trim($v);
				if($v=='')
				{
					continue;
				}
				if(isset($ws[$v]))
				{
					$mykey .= $v." ";
				}
				else if(isset($wsnew[$v]))
				{
					$mykey .= $v.' ';
					$wsnew[$v]++;
				}
				else if(isset($wserr[$v]))
				{
					$nerr = true;
				}
				else
				{
					$mykey .= $v." ";
					$wsnew[$v] = 1;
				}
			}
		}
	}
	echo "��ɵ������ݿ�Ĵ���<br/>\r\n";
	flush();
	if(is_array($wsnew))
	{
		echo "�Թؼ��ֽ�������...<br/>\r\n";
		flush();
		arsort($wsnew);
		echo "�ѹؼ��ֱ��浽���ݿ�...<br/>\r\n";
		flush();
		foreach($wsnew as $k=>$v)
		{
			if(strlen($k)>20)
			{
				continue;
			}
			$dsql->SetQuery("Insert Into `#@__keywords`(keyword,rank,sta,rpurl) Values('".addslashes($k)."','$v','1','')");
			$dsql->Execute();
		}
		echo "��ɹؼ��ֵĵ��룡<br/>\r\n";
		flush();
		sleep(1);
	}
	else
	{
		echo "û�����κ��µĹؼ��֣�<br/>\r\n";
		flush();
		sleep(1);
	}
	ShowMsg('������в���������ת���ؼ����б�ҳ��','article_keywords_main.php');
	exit();
}
//�Զ���ȡ�ؼ��֣�������Ĭ�ϵ�����ģ�ͣ�
else if($dopost=='fetch')
{
	require_once(DEDEINC."/splitword.class.php");
	if(empty($startdd))
	{
		$startdd = 0;
	}
	if(empty($pagesize))
	{
		$pagesize = 20;
	}
	if(empty($totalnum))
	{
		$totalnum = 0;
	}

	//ͳ�Ƽ�¼����
	if($totalnum==0)
	{
		$row = $dsql->GetOne("Select count(*) as dd From `#@__archives` where channel='1' ");
		$totalnum = $row['dd'];
	}

	//��ȡ��¼���������ؼ���
	if($totalnum > $startdd+$pagesize)
	{
		$limitSql = " limit $startdd,$pagesize";
	}
	else if(($totalnum-$startdd)>0)
	{
		$limitSql = " limit $startdd,".($totalnum - $startdd);
	}
	else
	{
		$limitSql = '';
	}
	$tjnum = $startdd;
	if($limitSql!='')
	{
		$fquery = "Select arc.id,arc.title,arc.keywords,addon.body From `#@__archives` arc
	          left join `#@__addonarticle` addon on addon.aid=arc.id where arc.channel='1' $limitSql ";
		$dsql->SetQuery($fquery);
		$dsql->Execute();
		$sp = new SplitWord($cfg_soft_lang, $cfg_soft_lang);
		while($row=$dsql->GetObject())
		{
			if($row->keywords!='')
			{
				continue;
			}
			$tjnum++;
			$id = $row->id;
			$keywords = "";
			$sp->SetSource($row->title, $cfg_soft_lang, $cfg_soft_lang);
			$titleindexs = $sp->GetFinallyIndex();
			$sp->SetSource(Html2Text($row->body), $cfg_soft_lang, $cfg_soft_lang);
			$allindexs = $sp->GetFinallyIndex();
			if(is_array($allindexs) && is_array($titleindexs))
			{
				foreach($titleindexs as $k)
				{
					if(strlen($keywords)>=30)
					{
						break;
					}
					else
					{
						$keywords .= $k.",";
					}
				}
				foreach($allindexs as $k)
				{
					if(strlen($keywords)>=30)
					{
						break;
					}
					else if(!in_array($k,$titleindexs))
					{
						$keywords .= $k.",";
					}
				}
			}
			$keywords = addslashes($keywords);
			if($keywords=='')
			{
				$keywords = ',';
			}
			$dsql->ExecuteNoneQuery("update `#@__archives` set keywords='$keywords' where id='$id'");
		}
		unset($sp);
	}//end if limit

	//������ʾ��Ϣ
	if($totalnum>0)
	{
		$tjlen = ceil( ($tjnum/$totalnum) * 100 );
	}
	else
	{
		$tjlen=100;
	}

	$dvlen = $tjlen * 2;
	$tjsta = "<div style='width:200;height:15;border:1px solid #898989;text-align:left'><div style='width:$dvlen;height:15;background-color:#829D83'></div></div>";
	$tjsta .= "<br/>��ɴ����ĵ������ģ�$tjlen %��λ�ã�{$startdd}������ִ������...";

	if($tjnum < $totalnum)
	{
		$nurl = "article_keywords_make.php?dopost=fetch&totalnum=$totalnum&startdd=".($startdd+$pagesize)."&pagesize=$pagesize";
		ShowMsg($tjsta,$nurl,0,500);
	}
	else
	{
		ShowMsg("�����������","javascript:;");
	}
	exit();
}

include DedeInclude('templets/article_keywords_make.htm');

?>