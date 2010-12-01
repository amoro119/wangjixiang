<?php
@ob_start();
@set_time_limit(3600);
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_Keyword');
if(empty($dojob)) $dojob = '';
if($dojob=='')
{
	include DedeInclude("templets/article_description_main.htm");
	exit();
}
else
{
	if(empty($startdd))
	{
		$startdd = 0;
	}
	if(empty($pagesize))
	{
		$pagesize = 100;
	}
	if(empty($totalnum))
	{
		$totalnum = 0;
	}
	if(empty($sid))
	{
		$sid = 0;
	}
	if(empty($eid))
	{
		$eid = 0;
	}
	if(empty($dojob))
	{
		$dojob = 'des';
	}
	$table = ereg_replace("[^0-9a-zA-Z_#@]","",$table);
	$field = ereg_replace("[^0-9a-zA-Z_\[\]]","",$field);
	$channel = intval($channel);
	if($dsize>250)
	{
		$dsize = 250;
	}

	$tjnum = 0;

	//��ȡ�Զ�ժҪ
	if($dojob=='des')
	{

		if(empty($totalnum))
		{
			$addquery  = "";
			if($sid!=0)
			{
				$addquery  .= " And id>='$sid' ";
			}
			if($eid!=0)
			{
				$addquery  .= " And id<='$eid' ";
			}
			$tjQuery = "Select count(*) as dd From #@__archives where channel='{$channel}' $addquery";
			$row = $dsql->GetOne($tjQuery);
			$totalnum = $row['dd'];
		}
		if($totalnum > 0)
		{
			$addquery  = "";
			if($sid!=0)
			{
				$addquery  .= " And #@__archives.id>='$sid' ";
			}
			if($eid!=0)
			{
				$addquery  .= " And #@__archives.id<='$eid' ";
			}
			$fquery = "Select #@__archives.id,#@__archives.title,#@__archives.description,{$table}.{$field}
          From #@__archives left join {$table} on {$table}.aid=#@__archives.id
          where #@__archives.channel='{$channel}' $addquery limit $startdd,$pagesize ; ";
			$dsql->SetQuery($fquery);
			$dsql->Execute();
			while($row=$dsql->GetArray())
			{
				$body = $row[$field];
				$description = $row['description'];
				if(strlen($description)>10 || $description=='-')
				{
					continue;
				}
				$bodytext = preg_replace("/#p#|#e#|������|��ҳ����/isU","",Html2Text($body));
				if(strlen($bodytext) < $msize)
				{
					continue;
				}
				$des = trim(addslashes(cn_substr($bodytext,$dsize)));
				if(strlen($des)<3)
				{
					$des = "-";
				}
				$dsql->ExecuteNoneQuery("Update #@__archives set description='{$des}' where id='{$row['id']}';");
			}

			//���ؽ�����Ϣ
			$startdd = $startdd + $pagesize;
			if($totalnum > $startdd)
			{
				$tjlen = ceil( ($startdd/$totalnum) * 100 );
			}else
			{
				$tjlen=100;
				ShowMsg('�����������', 'javascript:;');
				exit();
			}
			$dvlen = $tjlen * 2;
			$tjsta = "<div style='width:200;height:15;border:1px solid #898989;text-align:left'><div style='width:$dvlen;height:15;background-color:#829D83'></div></div>";
			$tjsta .= "<br/>��ɴ����ĵ������ģ�$tjlen %������ִ������...";
			$nurl = "article_description_main.php?totalnum=$totalnum&startdd={$startdd}&pagesize=$pagesize&table={$table}&field={$field}&dsize={$dsize}&msize={$msize}&channel={$channel}&dojob={$dojob}";
			ShowMsg($tjsta,$nurl,0,500);
			exit();
		}
		else
		{
			ShowMsg('�����������', 'javascript:;');
			exit();
		}
	}//��ȡ�Զ�ժҪ�������

	//�����Զ���ҳ
	if($dojob=='page')
	{
		require_once(DEDEADMIN."/inc/inc_archives_functions.php");

		$addquery  = "";
		if($sid!=0)
		{
			$addquery  .= " and aid>='$sid' ";
		}
		if($eid!=0)
		{
			$addquery  .= " and aid<='$eid' ";
		}

		//ͳ�Ƽ�¼����
		if($totalnum==0)
		{
			$sql = "Select count(*) as dd From $table where 1 $addquery";
			$row = $dsql->GetOne($sql);
			$totalnum = $row['dd'];
		}

		//��ȡ��¼��������
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
			$limitSql = "";
		}
		$tjnum = $startdd;
		if($limitSql!="")
		{
			$fquery = "Select aid,$field From $table where 1 $addquery $limitSql ;";
			$dsql->SetQuery($fquery);
			$dsql->Execute();
			while($row=$dsql->GetArray())
			{
				$tjnum++;
				$body = $row[$field];
				$aid = $row['aid'];
				if(strlen($body) < $msize)
				{
					continue;
				}
				if(!preg_match("/#p#/iU",$body))
				{
					$body = SpLongBody($body,$cfg_arcautosp_size*1024,"#p#��ҳ����#e#");
					$body = addslashes($body);
					$dsql->ExecuteNoneQuery("Update $table set $field='$body' where aid='$aid' ; ");
				}
			}
		}//end if limit

		//���ؽ�����ʾ
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
		$tjsta .= "<br/>��ɴ����ĵ������ģ�$tjlen %������ִ������...";

		if($tjnum < $totalnum)
		{
			$nurl = "article_description_main.php?totalnum=$totalnum&startdd=".($startdd+$pagesize)."&pagesize=$pagesize&table={$table}&field={$field}&dsize={$dsize}&msize={$msize}&channel={$channel}&dojob={$dojob}";
			ShowMsg($tjsta,$nurl,0,500);
			exit();
		}
		else
		{
			ShowMsg('�����������', 'javascript:;');
			exit();
		}
	}//�����Զ���ҳ����������
}
?>