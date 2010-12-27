<?php
require_once(dirname(__FILE__)."/../include/common.inc.php");
require_once(DEDEINC."/channelunit.class.php");
if(!isset($open)) $open = 0;
//��ȡ�����б�
if($open==0)
{
	$aid = (isset($aid) && is_numeric($aid)) ? $aid : 0;
	if($aid==0) exit(' Request Error! ');

	$arcRow = GetOneArchive($aid);
	if($arcRow['aid']=='')
	{
		ShowMsg('�޷���ȡδ֪�ĵ�����Ϣ!','-1');
		exit();
	}
	extract($arcRow, EXTR_SKIP);

	$cu = new ChannelUnit($arcRow['channel'],$aid);
	if(!is_array($cu->ChannelFields))
	{
		ShowMsg('��ȡ�ĵ���Ϣʧ�ܣ�','-1');
		exit();
	}

	$vname = '';
	foreach($cu->ChannelFields as $k=>$v)
	{
		if($v['type']=='softlinks'){ $vname=$k; break; }
	}
	$row = $dsql->GetOne("Select $vname From `".$cu->ChannelInfos['addtable']."` where aid='$aid'");

	include_once(DEDEINC.'/taglib/channel/softlinks.lib.php');
	$ctag = '';
	$downlinks = ch_softlinks($row[$vname], $ctag, $cu, '', true);

	require_once(DEDETEMPLATE.'/plus/download_links_templet.htm');
	exit();
}
/*------------------------
//�ṩ������û�����(��ģʽ)
function getSoft_old()
------------------------*/
else if($open==1)
{
	//�������ش���
	$id = isset($id) && is_numeric($id) ? $id : 0;
	$link = base64_decode(urldecode($link));
	$hash = md5($link);
	$rs = $dsql->ExecuteNoneQuery2("Update `#@__downloads` set downloads = downloads+1 where hash='$hash' ");
	if($rs <= 0)
	{
		$query = " Insert into `#@__downloads`(`hash`,`id`,`downloads`) values('$hash','$id',1); ";
		$dsql->ExecNoneQuery($query);
	}
	header("location:$link");
	exit();
}
/*------------------------
//�ṩ������û�����(��ģʽ)
function getSoft_new()
------------------------*/
else if($open==2)
{
	$id = intval($id);
	//��ø��ӱ���Ϣ
	$row = $dsql->GetOne("Select ch.addtable,arc.mid From `#@__arctiny` arc left join `#@__channeltype` ch on ch.id=arc.channel where arc.id='$id' ");
	if(empty($row['addtable']))
	{
		ShowMsg('�Ҳ�������Ҫ�������Դ��', 'javascript:;');
		exit();
	}
	$mid = $row['mid'];
	
	//��ȡ�����б�����Ȩ����Ϣ
	$row = $dsql->GetOne("Select softlinks,daccess,needmoney From `{$row['addtable']}` where aid='$id' ");
	if(empty($row['softlinks']))
	{
		ShowMsg('�Ҳ�������Ҫ�������Դ��', 'javascript:;');
		exit();
	}
	$softconfig = $dsql->GetOne("Select * From `#@__softconfig` ");
	$needRank = $softconfig['dfrank'];
	$needMoney = $softconfig['dfywboy'];
	if($softconfig['argrange']==0)
	{
		$needRank = $row['daccess'];
	  $needMoney = $row['needmoney'];
	}
	
	//���������б�
	require_once(DEDEINC.'/dedetag.class.php');
	$softUrl = '';
	$islocal = 0;
	$dtp = new DedeTagParse();
	$dtp->LoadSource($row['softlinks']);
	if( !is_array($dtp->CTags) )
	{
		$dtp->Clear();
		ShowMsg('�Ҳ�������Ҫ�������Դ��', 'javascript:;');
		exit();
	}
	foreach($dtp->CTags as $ctag)
	{
		if($ctag->GetName()=='link')
		{
			$link = trim($ctag->GetInnerText());
			$islocal = $ctag->GetAtt('islocal');
			//������������
			if(!isset($firstLink) && $islocal==1) $firstLink = $link;
			if($islocal==1 && $softconfig['islocal'] != 1) continue;
			//֧��http,Ѹ������,ftp,flashget
			if(!eregi('^http://|^thunder://|^ftp://|^flashget://', $link))
			{
				 $link = $cfg_mainsite.$link;
			}
			$dbhash = substr(md5($link), 0, 24);
			if($uhash==$dbhash) $softUrl = $link;
		}
	}
	$dtp->Clear();
	if($softUrl=='' && $softconfig['ismoresite']==1 
	&& $softconfig['moresitedo']==1 && trim($softconfig['sites'])!='' && isset($firstLink))
	{
		$firstLink = eregi_replace("http://([^/]*)/", '/', $firstLink);
		$softconfig['sites'] = ereg_replace("[\r\n]{1,}", "\n", $softconfig['sites']);
		$sites = explode("\n", trim($softconfig['sites']));
		foreach($sites as $site)
		{
			if(trim($site)=='') continue;
			list($link, $serverName) = explode('|', $site);
			$link = trim( ereg_replace("/$", "", $link) ).$firstLink;
			$dbhash = substr(md5($link), 0, 24);
			if($uhash == $dbhash) $softUrl = $link;
		}
	}
	if( $softUrl == '' )
	{
		ShowMsg('�Ҳ�������Ҫ�������Դ��', 'javascript:;');
		exit();
	}
	//-------------------------
	// ��ȡ�ĵ���Ϣ���ж�Ȩ��
	//-------------------------
	$arcRow = GetOneArchive($id);
	if($arcRow['aid']=='')
	{
		ShowMsg('�޷���ȡδ֪�ĵ�����Ϣ!','-1');
		exit();
	}
	extract($arcRow, EXTR_SKIP);

	//������Ҫ����Ȩ�޵����
	if($needRank>0 || $needMoney>0)
	{
		require_once(DEDEINC.'/memberlogin.class.php');
		$cfg_ml = new MemberLogin();
		$arclink = $arcurl;
		$arctitle = $title;
		$arcLinktitle = "<a href=\"{$arcurl}\"><u>".$arctitle."</u></a>";
		$pubdate = GetDateTimeMk($pubdate);
	
		//��Ա������
		if(($needRank>1 && $cfg_ml->M_Rank < $needRank && $mid != $cfg_ml->M_ID))
		{
			$dsql->Execute('me' , "Select * From `#@__arcrank` ");
			while($row = $dsql->GetObject('me'))
			{
				$memberTypes[$row->rank] = $row->membername;
			}
			$memberTypes[0] = "�ο�";
			$msgtitle = "��û��Ȩ�����������{$arctitle}��";
			$moremsg = "��������Ҫ <font color='red'>".$memberTypes[$needRank]."</font> �������أ���Ŀǰ�ǣ�<font color='red'>".$memberTypes[$cfg_ml->M_Rank]."</font> ��";
			include_once(DEDETEMPLATE.'/plus/view_msg.htm');
			exit();
		}

		//����Ϊ����������Զ��۵���
		//���������Ҫ��ң�����û��Ƿ���������ĵ�
		if($needMoney > 0  && $mid != $cfg_ml->M_ID)
		{
			$sql = "Select aid,money From `#@__member_operation` where buyid='ARCHIVE".$id."' And mid='".$cfg_ml->M_ID."'";
			$row = $dsql->GetOne($sql);
			//δ�����������
			if( !is_array($row) )
			{
		 	 		//û���㹻�Ľ��
					if( $needMoney > $cfg_ml->M_Money || $cfg_ml->M_Money=='')
	 				{
							$msgtitle = "��û��Ȩ�����������{$arctitle}��";
							$moremsg = "��������Ҫ <font color='red'>".$needMoney." ���</font> �������أ���Ŀǰӵ�н�ң�<font color='red'>".$cfg_ml->M_Money." ��</font> ��";
							include_once(DEDETEMPLATE.'/plus/view_msg.htm');
							exit(0);
					}
					//���㹻��ң���¼�û���Ϣ
		 	 		$inquery = "INSERT INTO `#@__member_operation`(mid,oldinfo,money,mtime,buyid,product,pname,sta)
		              VALUES ('".$cfg_ml->M_ID."','$arctitle','$needMoney','".time()."', 'ARCHIVE".$id."', 'archive','�������', 2); ";
		 	 		//��¼����
		 	 		if( !$dsql->ExecuteNoneQuery($inquery) )
		 	 		{
		 	 			ShowMsg('��¼����ʧ��, �뷵��', '-1');
						exit(0);
		 	 		}
		    	//�۳����
		    	$dsql->ExecuteNoneQuery("Update `#@__member` set money = money - $needMoney where mid='".$cfg_ml->M_ID."'");
			}
		}
	}
	//�������ش���
	$hash = md5($softUrl);
	$rs = $dsql->ExecuteNoneQuery2("Update `#@__downloads` set downloads = downloads+1 where hash='$hash' ");
	if($rs <= 0)
	{
		$query = " Insert into `#@__downloads`(`hash`, `id`, `downloads`) values('$hash', '$id', 1); ";
		$dsql->ExecNoneQuery($query);
	}
	header("location:{$softUrl}");
	exit();
}//opentype=2
?>