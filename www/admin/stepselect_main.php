<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('c_Stepselect');
require_once(DEDEINC."/datalistcp.class.php");
require_once(DEDEINC.'/enums.func.php');
/*-----------------
ǰ̨��ͼ
function __show() { }
------------------*/
$ENV_GOBACK_URL = (isset($ENV_GOBACK_URL) ? $ENV_GOBACK_URL : 'stepselect_main.php');
if(empty($action))
{
  setcookie("ENV_GOBACK_URL",$dedeNowurl,time()+3600,"/");
  if(!isset($egroup)) $egroup = '';
  if(!isset($topvalue)) $topvalue = 0;
  $etypes = array();
  $egroups = array();
  $dsql->Execute('me','Select * from `#@__stepselect` order by id desc');
  while($arr = $dsql->GetArray())
  {
     $etypes[] = $arr;
     $egroups[$arr['egroup']] = $arr['itemname'];
  }

  if($egroup!='')
  {
	  $orderby = 'order by disorder asc, evalue asc';
	  if(!empty($topvalue))
	  {
	  	$egroupsql = " where egroup like '$egroup' And evalue>=$topvalue And evalue < ".($topvalue + 500);
	  }
	  else
	  {
	  	$egroupsql = " where egroup like '$egroup' ";
	  }
	  $sql = "Select * From `#@__sys_enum` $egroupsql $orderby";
  }
  else
  {
	  $egroupsql = '';
	  $sql = "Select * from `#@__stepselect` order by id desc";
  }
  $dlist = new DataListCP();
  $dlist->SetParameter('egroup',$egroup);
  $dlist->SetParameter('topvalue',$topvalue);
  $dlist->SetTemplet(DEDEADMIN."/templets/stepselect_main.htm");
  $dlist->SetSource($sql);
  $dlist->display();
  exit();
}
else if($action=='edit' || $action=='addnew' || $action=='addenum' || $action=='view')
{
   AjaxHead();
   include('./templets/stepselect_showajax.htm');
   exit();
}
/*-----------------
ɾ�����ͻ�ö��ֵ
function __del() { }
------------------*/
else if($action=='del')
{
   $arr = $dsql->GetOne("Select * from `#@__stepselect` where id='$id' ");
   if(!is_array($arr))
   {
      ShowMsg("�޷���ȡ������Ϣ�����������������","stepselect_main.php?".ExecTime());
      exit();
   }
   if($arr['issystem']==1)
   {
      ShowMsg("ϵͳ���õ�ö�ٷ��಻��ɾ����","stepselect_main.php?".ExecTime());
      exit();
   }
   $dsql->ExecuteNoneQuery("Delete From `#@__stepselect` where id='$id'; ");
   $dsql->ExecuteNoneQuery("Delete From `#@__sys_enum` where egroup='{$arr['egroup']}'; ");
   ShowMsg("�ɹ�ɾ��һ�����࣡","stepselect_main.php?".ExecTime());
   exit();
}
else if($action=='delenumAllSel')
{
	if(isset($ids) && is_array($ids))
	{
		$id = join(',', $ids);
		
		$groups = array();
		$dsql->Execute('me', "Select egroup From `#@__sys_enum` where id in($id) group by egroup");
		while($row = $dsql->GetArray('me'))
		{
			$groups[] = $row['egroup'];
		}
		
		$dsql->ExecuteNoneQuery("Delete From `#@__sys_enum` where id in($id); ");
		
		//���»���
		foreach($groups as $egropu) {
			WriteEnumsCache($egroup);
		}
		
		ShowMsg("�ɹ�ɾ��ѡ�е�ö�ٷ��࣡",$ENV_GOBACK_URL);
	}
	else
	{
		ShowMsg("��ûѡ���κη��࣡", "-1");
	}
	exit();
}
else if($action=='delenum')
{
		$row = $dsql->GetOne("Select egroup From `#@__sys_enum` where id = '$id' ");
		$dsql->ExecuteNoneQuery("Delete From `#@__sys_enum` where id='{$id}'; ");
		WriteEnumsCache($row['egroup']);
		ShowMsg("�ɹ�ɾ��һ��ö�٣�",$ENV_GOBACK_URL);
		exit();
}
/*-----------------
���������޸�
function __edit_save() { }
------------------*/
else if($action=='edit_save')
{
   if(eregi("[^0-9a-z_-]",$egroup))
   {
			ShowMsg("�����Ʋ�����ȫ���ַ���������ţ�","-1");
			exit();
   }
   $dsql->ExecuteNoneQuery("update `#@__stepselect` set `itemname`='$itemname',`egroup`='$egroup' where id='$id'; ");
   ShowMsg("�ɹ��޸�һ�����࣡","stepselect_main.php?".ExecTime());
   exit();
}
/*-----------------
����������
function __addnew_save() { }
------------------*/
else if($action=='addnew_save')
{
		if(eregi("[^0-9a-z_-]",$egroup))
		{
			ShowMsg("�����Ʋ�����ȫ���ַ���������ţ�","-1");
			exit();
		}
		$arr = $dsql->GetOne("Select * from `#@__stepselect` where itemname like '$itemname' Or egroup like '$egroup' ");
		if(is_array($arr))
		{
			ShowMsg("��ָ����������ƻ��������Ѿ����ڣ�����ʹ�ã�","stepselect_main.php");
			exit();
		}
		$dsql->ExecuteNoneQuery("Insert into `#@__stepselect`(`itemname`,`egroup`,`issign`,`issystem`) values('$itemname','$egroup','0','0'); ");
		WriteEnumsCache($egroup);
		ShowMsg("�ɹ����һ�����࣡","stepselect_main.php?egroup=$egroup");
		exit();
}
/*---------
�Ѿɰ�ȫ��ʡ�б��滻��ǰ��������
function __exarea() { }
----------*/
else if($action=='exarea')
{
	$bigtypes = array();
	$dsql->ExecuteNoneQuery("Delete From `#@__sys_enum` where egroup='nativeplace'; ");
	$query = "SELECT * FROM `#@__area` WHERE reid =0 order by id asc";
	$dsql->Execute('me', $query);
	$n = 1;
	while($row = $dsql->GetArray())
	{
		$bigtypes[$row['id']] = $evalue = $disorder = $n * 500;
		$dsql->ExecuteNoneQuery("Insert into `#@__sys_enum`(`ename`,`evalue`,`egroup`,`disorder`,`issign`)
		                         values('{$row['name']}','$evalue','nativeplace','$disorder','0'); ");
    $n++;                                
	}
	$stypes = array();
	foreach($bigtypes as $k=>$v)
	{
		$query = "SELECT * FROM `#@__area` WHERE reid=$k order by id asc";
	  $dsql->Execute('me', $query);
	  $n = 1;
	  while($row = $dsql->GetArray())
	  {
	  	$stypes[$row['id']] = $evalue = $disorder = $v + $n;
	  	$dsql->ExecuteNoneQuery("Insert into `#@__sys_enum`(`ename`,`evalue`,`egroup`,`disorder`,`issign`)
	  	                         values('{$row['name']}','$evalue','nativeplace','$disorder','0'); ");
      $n++; 
	  }
	}
	WriteEnumsCache('nativeplace');
	ShowMsg("�ɹ��������оɵĵ������ݣ�", "stepselect_main.php?egroup=nativeplace");
	exit();
}
/*--------------------
function __addenum_save() { }
���ڶ���ö�ٵ�˵����Ϊ�˽�ʡ��ѯ�ٶȣ�����ö����ͨ�������㷨���ɵģ�ԭ��Ϊ
�����ܱ� 500 �����Ķ���һ��ö�٣�(500 * n) + 1 < em < 500 * (n+1) Ϊ�¼�ö��
�磺1000 ���¼�ö�ٶ�Ӧ��ֵΪ 1001,1002,1003...1499
���� issign=1 �ģ���ʾ������ֻ��һ��ö�٣�����������㷨����
---------------------*/
else if($action=='addenum_save')
{
   if(empty($ename) || empty($egroup)) {
   	  Showmsg("������ƻ������Ʋ���Ϊ�գ�","-1");
   	  exit();
   }
   if($issign==1 || $topvalue==0)
   {
      	$enames = explode(',', $ename);
      	foreach($enames as $ename)
      	{
      		$arr = $dsql->GetOne("Select * From `#@__sys_enum` where egroup='$egroup' And (evalue mod 500)=0 order by evalue desc ");
				
					if(!is_array($arr)) $disorder = $evalue = ($issign==1 ? 1 : 500);
					else $disorder = $evalue = $arr['disorder'] + ($issign==1 ? 1 : 500);
				
					$dsql->ExecuteNoneQuery("Insert into `#@__sys_enum`(`ename`,`evalue`,`egroup`,`disorder`,`issign`) 
                                    values('$ename','$evalue','$egroup','$disorder','$issign'); "); 
        }
        WriteEnumsCache($egroup);                                                          
				ShowMsg("�ɹ����ö�ٷ��࣡".$dsql->GetError(), $ENV_GOBACK_URL);
				exit();
		}
		else
		{
				$minid = $topvalue;
				$maxid = $topvalue + 500;
				$enames = explode(',', $ename);
      	foreach($enames as $ename)
      	{
					$arr = $dsql->GetOne("Select * From `#@__sys_enum` where egroup='$egroup' And evalue>$minid And evalue<$maxid order by evalue desc ");
					if(!is_array($arr))
					{
						$disorder = $evalue = $minid+1;
					}
					else
					{
						$disorder = $arr['disorder']+1;
						$evalue = $arr['evalue']+1;
					}
					$dsql->ExecuteNoneQuery("Insert into `#@__sys_enum`(`ename`,`evalue`,`egroup`,`disorder`,`issign`) 
                                  values('$ename','$evalue','$egroup','$disorder','$issign'); ");
        }
        WriteEnumsCache($egroup);
				ShowMsg("�ɹ����ö�ٷ��࣡", $ENV_GOBACK_URL);
				exit();
		}
}
/*-----------------
�޸�ö�����ƺ�����
function __upenum() { }
------------------*/
else if($action=='upenum')
{
	$ename = trim(str_replace(' ����','',$ename));
	$row = $dsql->GetOne("Select egroup From `#@__sys_enum` where id = '$id' ");
	WriteEnumsCache($row['egroup']);
	$dsql->ExecuteNoneQuery("Update `#@__sys_enum` set `ename`='$ename',`disorder`='$disorder' where id='$aid'; ");
	ShowMsg("�ɹ��޸�һ��ö�٣�", $ENV_GOBACK_URL);
	exit();
}
/*-----------------
����ö�ٻ���
function __upallcache() { }
------------------*/
else if($action=='upallcache')
{
	if(!isset($egroup)) $egroup = '';
	WriteEnumsCache($egroup);
	ShowMsg("�ɸ���ö�ٻ��棡", $ENV_GOBACK_URL);
	exit();
}
?>