<?php

if(!defined('DEDEINC'))
{
	exit("Request Error!");
}

function GetIndexKey($arcrank,$typeid,$sortrank=0,$channelid=1,$senddate=0,$mid=1)
{
	global $dsql,$senddate,$typeid2;
	if(empty($typeid2)) $typeid2 = 0;
	if(empty($senddate)) $senddate = time();
	if(empty($sortrank)) $sortrank = $senddate;
	$iquery = "
	  INSERT INTO `#@__arctiny` (`arcrank`,`typeid`,`typeid2`,`channel`,`senddate`, `sortrank`, `mid`)
	  VALUES ('$arcrank','$typeid','$typeid2' , '$channelid','$senddate', '$sortrank', '$mid') ";
	$dsql->ExecuteNoneQuery($iquery);
	$aid = $dsql->GetLastID();
	return $aid;
}

// ����΢��key��Tag
function UpIndexKey($id,$arcrank,$typeid,$sortrank=0,$tags='')
{
	global $dsql,$typeid2;
	if(empty($typeid2)) $typeid2 = 0;
	$addtime = time();
	$query = " Update `#@__arctiny` set `arcrank`='$arcrank', `typeid`='$typeid', `typeid2`='$typeid2', `sortrank`='$sortrank' where id = '$id' ";
	$dsql->ExecuteNoneQuery($query);

	/*
	* �����޸ĺ��Tag
	*/
	if($tags!='')
	{
		$oldtag = GetTags($id);
		$oldtags = explode(',',$oldtag);
		$tagss = explode(',',$tags);
		foreach($tagss as $tag)
		{
			$tag = trim($tag);
			if(isset($tag[12]) || $tag!=stripslashes($tag))
			{
				continue;
			}
			if(!in_array($tag,$oldtags))
			{
				InsertOneTag($tag,$id);
			}
		}
		foreach($oldtags as $tag)
		{
			if(!in_array($tag,$tagss))
			{
				$dsql->ExecuteNoneQuery("Delete From `#@__taglist` where aid='$id' And tag like '$tag' ");
				$dsql->ExecuteNoneQuery("Update `#@__tagindex` set total=total-1 where tag like '$tag' ");
			}
			else
			{
				$dsql->ExecuteNoneQuery("Update `#@__taglist` set `arcrank` = '$arcrank', `typeid` = '$typeid' where tag like '$tag' ");
			}
		}
	}
}

/*
* ����Tags
*/
function InsertTags($tag,$aid)
{
	$tags = explode(',',$tag);
	foreach($tags as $tag)
	{
		$tag = trim($tag);
		if(isset($tag[20]) || $tag!=stripslashes($tag))
		{
			continue;
		}
		InsertOneTag($tag,$aid);
	}
}

/*
* ����һ��tag
*/
function InsertOneTag($tag,$aid)
{
	global $typeid,$arcrank,$dsql;
	$tag = trim($tag);
	if($tag == '')
	{
		return '';
	}
	if(empty($typeid))
	{
		$typeid = 0;
	}
	if(empty($arcrank))
	{
		$arcrank = 0;
	}
	$rs = false;
	$addtime = time();
	$row = $dsql->GetOne("Select * From `#@__tagindex` where tag like '$tag' ");
	if(!is_array($row))
	{
		$rs = $dsql->ExecuteNoneQuery(" Insert Into `#@__tagindex`(`tag`,`typeid`,`count`,`total`,`weekcc`,`monthcc`,`weekup`,`monthup`,`addtime`) values('$tag','$typeid','0','1','0','0','$addtime','$addtime','$addtime'); ");
		$tid = $dsql->GetLastID();
	}
	else
	{
		$rs = $dsql->ExecuteNoneQuery(" Update `#@__tagindex` set total=total+1,addtime=$addtime where tag like '$tag' ");
		$tid = $row['id'];
	}
	if($rs)
	{
		$dsql->ExecuteNoneQuery("Insert Into `#@__taglist`(`tid`,`aid`,`arcrank`,`typeid` , `tag`) values('$tid','$aid','$arcrank','$typeid' , '$tag'); ");
	}
}

?>