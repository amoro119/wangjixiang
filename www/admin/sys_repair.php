<?php
require_once(dirname(__FILE__).'/config.php');
CheckPurview('sys_ArcBatch');
require_once(dirname(__FILE__).'/../include/oxwindow.class.php');
//ShowMsg("Ŀǰ�ݲ���Ҫ�˹��ߣ��Ժ�����Ҫϵͳ������Զ������������<br /><a href='index_body.php'>&lt;&lt;����˷���&gt;&gt;</a>", "javascript:;");
//exit();
if(empty($dopost))
{
	$win = new OxWindow();
	$win->Init("sys_repair.php","js/blank.js","POST' enctype='multipart/form-data' ");
	$win->mainTitle = "ϵͳ�޸�����";
	$wecome_info = "<a href='index_body.php'>ϵͳ��ҳ</a> &gt;&gt; ϵͳ�����޸�����";
	$win->AddTitle('���������ڼ����޸����ϵͳ���ܴ��ڵĴ���');
	$msg = "
	<table width='98%' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td height='250' valign='top'>
    <br />
    �����ֶ�����ʱ�û�û����ָ����SQL��䣬���Զ���������©�������������ܻᵼ��һЩ����ʹ�ñ����߻��Զ���Ⲣ����<br /><br />
    <b>������Ŀǰ��Ҫִ�����涯����</b><br />
    1���޸�/�Ż����ݱ�<br />
    2������ϵͳ���棻<br />
    3�����ϵͳ����һ���ԡ�<br />
    4�����΢������������һ���ԡ�<br />
    <br />
    <br />
    <a href='sys_repair.php?dopost=1' style='font-size:18px;color:red'><b>����˿�ʼ���г�����&gt;&gt;</b></a>
    <br /><br /><br />
    </td>
  </tr>
 </table>
	";
	$win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow('hand','');
	$win->Display();
	exit();
}
/*-------------------
���ݽṹ������
function 1_test_db() {  }
--------------------*/
else if($dopost==1)
{
	$win = new OxWindow();
	$win->Init("sys_repair.php","js/blank.js","POST' enctype='multipart/form-data' ");
	$win->mainTitle = "ϵͳ�޸�����";
	$wecome_info = "<a href='sys_repair.php'>ϵͳ�����޸�����</a> &gt;&gt; ������ݽṹ";
	$win->AddTitle('���������ڼ����޸����ϵͳ���ܴ��ڵĴ���');
	$msg = "
	<table width='98%' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td height='250' valign='top'>
    {$msg}
    <b><font color='green'>��������ݽṹ�����Լ�⣡</font></b>
    <hr size='1'/>
    <br />
    <b>�����ϵͳ�����漸������֮һ������΢����ȷ�ԣ�</b><br />
    1���޷��������������޷����к�������<br />
    2���������ݿ�archives��ʱ����<br />
    3���б���ʾ����Ŀ��ʵ���ĵ�����һ��<br />
    <br />
    <a href='sys_repair.php?dopost=2' style='font-size:18px;'><b>����˼��΢����ȷ��&gt;&gt;</b></a>
    <br /><br /><br />
    </td>
  </tr>
 </table>
	";
	$win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow('hand','');
	$win->Display();
	exit();
}
/*-------------------
���΢����ȷ�Բ������޸�
function 2_test_arctiny() {  }
--------------------*/
else if($dopost==2)
{
  $msg = '';
  
  $allarcnum = 0;
  $row = $dsql->GetOne("Select count(*) as dd From `#@__archives` ");
  $allarcnum = $arcnum = $row['dd'];
  $msg .= "��#@__archives ���ܼ�¼���� {$arcnum} <br />";
  
  $shtables = array();
  $dsql->Execute('me', " Select addtable From `#@__channeltype` where id < -1 ");
  while($row = $dsql->GetArray('me') )
  {
  	$addtable = strtolower(trim(str_replace('#@__', $cfg_dbprefix, $row['addtable'])));
  	if(empty($addtable)) {
  		continue;
  	}
  	else
  	{
  		if( !isset($shtables[$addtable]) )
  		{
  			$shtables[$addtable] = 1;
  			$row = $dsql->GetOne("Select count(aid) as dd From `$addtable` ");
  			$msg .= "��{$addtable} ���ܼ�¼���� {$row['dd']} <br />";
  			$allarcnum += $row['dd'];
  		}
  	}
  }
  $msg .= "������Ч��¼���� {$allarcnum} <br /> ";
  $errall = "<a href='index_body.php' style='font-size:14px;'><b>����������޴��󷵻�&gt;&gt;</b></a>";
  $row = $dsql->GetOne("Select count(*) as dd From `#@__arctiny` ");
  $msg .= "��΢ͳ�Ʊ��¼���� {$row['dd']}<br />";
  if($row['dd']==$allarcnum)
  {
  	$msg .= "<p style='color:green;font-size:16px'><b>���߼�¼һ�£�����������</b></p><br />";
  }
  else
  {
  	$sql = " TRUNCATE TABLE `#@__arctiny`";
		$dsql->executenonequery($sql);
	  $msg .= "<font color='red'>���߼�¼��һ�£����Խ��м�����...</font><br />";
		//������ͨģ��΢����
		$sql = "insert into `#@__arctiny`(id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid)  
	        Select id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid from `#@__archives` ";
		$dsql->executenonequery($sql);
		//���뵥��ģ��΢����
		foreach($shtables as $tb=>$v)
		{
			$sql = "insert into `#@__arctiny`(id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid)  
			        Select aid, typeid, 0, arcrank, channel, senddate, 0, mid from `$tb` ";
			$rs = $dsql->executenonequery($sql); 
			$doarray[$tb]  = 1;
		}
		$row = $dsql->GetOne("Select count(*) as dd From `#@__arctiny` ");
		if($row['dd']==$allarcnum)
    {
    	$msg .= "<p style='color:green;font-size:16px'><b>������¼�ɹ���</b></p><br />";
    }
    else
    {
    	$msg .= "<p style='color:red;font-size:16px'><b>������¼ʧ�ܣ�������и߼��ۺϼ�⣡</b></p><br />";
    	$errall = " <a href='sys_repair.php?dopost=3' style='font-size:14px;'><b>���и߼�����Լ��&gt;&gt;</b></a> ";
    }
  }
  UpDateCatCache();
  $win = new OxWindow();
	$win->Init("sys_repair.php","js/blank.js","POST' enctype='multipart/form-data' ");
	$win->mainTitle = "ϵͳ�޸�����";
	$wecome_info = "<a href='sys_repair.php'>ϵͳ�����޸�����</a> &gt;&gt; ���΢����ȷ��";
	$win->AddTitle('���������ڼ����޸����ϵͳ���ܴ��ڵĴ���');
	$msg = "
	<table width='98%' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td height='250' valign='top'>
    {$msg}
    <hr />
    <br />
    {$errall}
    </td>
  </tr>
 </table>
	";
	$win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow('hand','');
	$win->Display();
	exit();
}
/*-------------------
�߼���ʽ�޸�΢��(��ɾ�����Ϸ�����������)
function 3_re_arctiny() {  }
--------------------*/
else if($dopost==3)
{
  $errnum = 0;
  $sql = " TRUNCATE TABLE `#@__arctiny`";
	$dsql->executenonequery($sql);
	
	$sql = "Select arc.id, arc.typeid, arc.typeid2, arc.arcrank, arc.channel, arc.senddate, arc.sortrank,
	        arc.mid, ch.addtable FROM `#@__archives` arc left join `#@__channeltype` ch on ch.id=arc.channel ";
  $dsql->Execute('me', $sql);
  while($row = $dsql->GetArray('me') )
  {
      $sql = "Insert Into `#@__arctiny`(id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid)
              Values('{$row['id']}','{$row['typeid']}','{$row['typeid2']}','{$row['arcrank']}',
             '{$row['channel']}','{$row['senddate']}','{$row['sortrank']}','{$row['mid']}');  ";
      $rs = $dsql->executenonequery($sql);
      if(!$rs)
      {
      	$addtable = trim($addtable);
      	$errnum ++;
      	$dsql->executenonequery("Delete From `#@__archives` where id='{$row['id']}' ");
      	if(!empty($addtable)) $dsql->executenonequery("Delete From `$addtable` where id='{$row['id']}' ");
      }
  }
  //���뵥��ģ��΢����
	$dsql->SetQuery("Select id,addtable From `#@__channeltype` where id < -1 ");
	$dsql->Execute();
	$doarray = array();
	while($row = $dsql->GetArray())
	{
		$tb = str_replace('#@__', $cfg_dbprefix, $row['addtable']);
		if(empty($tb) || isset($doarray[$tb]) )
		{
			continue;
		}
		else
		{
			$sql = "insert into `#@__arctiny`(id, typeid, typeid2, arcrank, channel, senddate, sortrank, mid)  
			        Select aid, typeid, 0, arcrank, channel, senddate, 0, mid from `$tb` ";
			$rs = $dsql->executenonequery($sql); 
			$doarray[$tb]  = 1;
		}
	}
	$win = new OxWindow();
	$win->Init("sys_repair.php","js/blank.js","POST' enctype='multipart/form-data' ");
	$win->mainTitle = "ϵͳ�޸�����";
	$wecome_info = "<a href='sys_repair.php'>ϵͳ�����޸�����</a> &gt;&gt; �߼��ۺϼ���޸�";
	$win->AddTitle('���������ڼ����޸����ϵͳ���ܴ��ڵĴ���');
	$msg = "
	<table width='98%' border='0' cellspacing='0' cellpadding='0' align='center'>
  <tr>
    <td height='250' valign='top'>
    ��������޸��������Ƴ������¼ {$errnum} ����
    <hr />
    <br />
    <a href='index_body.php' style='font-size:14px;'><b>����������޴��󷵻�&gt;&gt;</b></a>
    </td>
  </tr>
 </table>
	";
	$win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow('hand','');
	$win->Display();
	exit();
}
?>