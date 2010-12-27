<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC."/typelink.class.php");
if(empty($dopost))
{
	$dopost = '';
}
$id = isset($id) ? intval($id) : 0;

//���Ȩ�����
CheckPurview('t_Edit,t_AccEdit');

//�����Ŀ�������
CheckCatalog($id, '����Ȩ���ı���Ŀ��');

/*-----------------------
function action_save()
----------------------*/
if($dopost=="save")
{

	$description = Html2Text($description,1);
	$keywords = Html2Text($keywords,1);
	$uptopsql = $smalltypes = '';
	if(isset($smalltype) && is_array($smalltype))
	{
		$smalltypes = join(',',$smalltype);
	}
	if($topid==0)
	{
		$sitepath = $typedir;
		$uptopsql = " ,siteurl='$siteurl',sitepath='$sitepath',ishidden='$ishidden' ";
	}
	if($ispart!=0)
	{
		$cross = 0;
	}
	$upquery = "Update `#@__arctype` set
     issend='$issend',
     sortrank='$sortrank',
     typename='$typename',
     typedir='$typedir',
     isdefault='$isdefault',
     defaultname='$defaultname',
     issend='$issend',
     ishidden='$ishidden',
     channeltype='$channeltype',
     tempindex='$tempindex',
     templist='$templist',
     temparticle='$temparticle',
     namerule='$namerule',
     namerule2='$namerule2',
     ispart='$ispart',
     corank='$corank',
     description='$description',
     keywords='$keywords',
     seotitle='$seotitle',
     moresite='$moresite',
     `cross`='$cross',
     `content`='$content',
     `crossid`='$crossid',
     `smalltypes`='$smalltypes'
     $uptopsql
	where id='$id' ";

	if(!$dsql->ExecuteNoneQuery($upquery))
	{
		ShowMsg("���浱ǰ��Ŀ����ʱʧ�ܣ�����������������Ƿ�������⣡","-1");
		exit();
	}

	//���ѡ������Ŀ��Ͷ�壬���¶�����ĿΪ��Ͷ��
	if($topid>0 && $issend==1)
	{
		$dsql->ExecuteNoneQuery("Update `#@__arctype` set issend='$issend' where id='$topid'; ");
	}
	$slinks = " id in (".GetSonIds($id).")";

	//�޸Ķ�����Ŀʱǿ���޸��¼��Ķ�վ��֧������
	if($topid==0 && ereg(',',$slinks))
	{
		$upquery = "Update `#@__arctype` set moresite='$moresite', siteurl='$siteurl',sitepath='$sitepath',ishidden='$ishidden' where 1=1 And $slinks";
		$dsql->ExecuteNoneQuery($upquery);
	}

	//��������Ŀ����
	if(!empty($upnext))
	{
		$upquery = "Update `#@__arctype` set
       issend='$issend',
       defaultname='$defaultname',
       channeltype='$channeltype',
       tempindex='$tempindex',
       templist='$templist',
       temparticle='$temparticle',
       namerule='$namerule',
       namerule2='$namerule2',
       ishidden='$ishidden'
     where 1=1 And $slinks";
		if(!$dsql->ExecuteNoneQuery($upquery))
		{
			ShowMsg("���ĵ�ǰ��Ŀ�ɹ����������¼���Ŀ����ʱʧ�ܣ�","-1");
			exit();
		}
	}
	UpDateCatCache();
	ShowMsg("�ɹ�����һ�����࣡","catalog_main.php");
	exit();
}//End Save Action
else if ($dopost=="savetime")
{
	$uptopsql = '';
	$slinks = " id in (".GetSonIds($id).")";
	
	//������Ŀ����������Ŀ¼����
	if($topid==0 && $moresite==1)
	{
		$sitepath = $typedir;
		$uptopsql = " ,sitepath='$sitepath' ";
		if(ereg(',',$slinks))
		{
			$upquery = "Update `#@__arctype` set sitepath='$sitepath' where $slinks";
			$dsql->ExecuteNoneQuery($upquery);
		}
	}
	//���ѡ������Ŀ��Ͷ�壬���¶�����ĿΪ��Ͷ��
	if($topid > 0 && $issend==1)
	{
		$dsql->ExecuteNoneQuery("Update `#@__arctype` set issend='$issend' where id='$topid'; ");
	}
	
	$upquery = "Update `#@__arctype` set
     issend='$issend',
     sortrank='$sortrank',
     typedir='$typedir',
     typename='$typename',
   	 isdefault='$isdefault',
     defaultname='$defaultname',
     ispart='$ispart',
     corank='$corank' $uptopsql
	where id='$id' ";
	
	if(!$dsql->ExecuteNoneQuery($upquery))
	{
		ShowMsg("���浱ǰ��Ŀ����ʱʧ�ܣ�����������������Ƿ�������⣡","-1");
		exit();
	}
	UpDateCatCache();
	ShowMsg("�ɹ�����һ�����࣡","catalog_main.php");
	exit();
}

//��ȡ��Ŀ��Ϣ
$dsql->SetQuery("Select tp.*,ch.typename as ctypename From `#@__arctype` tp left join `#@__channeltype` ch on ch.id=tp.channeltype where tp.id=$id");
$myrow = $dsql->GetOne();
$topid = $myrow['topid'];
if($topid>0)
{
	$toprow = $dsql->GetOne("Select moresite,siteurl,sitepath From `#@__arctype` where id=$topid");
	foreach($toprow as $k=>$v)
	{
		if(!ereg("[0-9]",$k))
		{
			$myrow[$k] = $v;
		}
	}
}
$myrow['content']=empty($myrow['content'])? "&nbsp;" : $myrow['content'];

//��ȡƵ��ģ����Ϣ
$channelid = $myrow['channeltype'];
$dsql->SetQuery("select id,typename,nid from `#@__channeltype` where id<>-1 And isshow=1 order by id");
$dsql->Execute();
while($row=$dsql->GetObject())
{
	$channelArray[$row->id]['typename'] = $row->typename;
	$channelArray[$row->id]['nid'] = $row->nid;
	if($row->id==$channelid)
	{
		$nid = $row->nid;
	}
}
PutCookie('lastCid',GetTopid($id),3600*24,"/");
if($dopost == 'time')
{
	?>
	  <form name="form1" action="catalog_edit.php" method="post" onSubmit="return checkSubmit();">
  <input type="hidden" name="dopost" value="savetime" />
  <input type="hidden" name="id" value="<?php echo $id; ?>" />
  <input type="hidden" name="topid" value="<?php echo $myrow['topid']; ?>" />
  <input type="hidden" name="moresite" value="<?php echo $myrow['moresite']; ?>" />
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
       <tr> 
            <td class='bline' height="26" align="center" colspan="2">
            <a href='catalog_edit.php?id=<?php echo $id; ?>'><u>��ǰ�ǿ�ݱ༭ģʽ�������Ҫ�޸ĸ���ϸ�Ĳ�������ʹ�ø߼�ģʽ&gt;&gt;</u></a>
            </td>
          </tr>
         <tr> 
            <td width="150" class='bline' height="26" align="center">�Ƿ�֧��Ͷ�壺</td>
            <td class='bline'> <input type='radio' name='issend' value='0' class='np' <?php if($myrow['issend']=="0") echo " checked='1' ";?> />
              ��֧��&nbsp; <input type='radio' name='issend' value='1' class='np' <?php if($myrow['issend']=="1") echo " checked='1' ";?> />
              ֧�� </td>
          </tr>
          <!-- �ڿ����޸ĸ�������ģ�ͺ���Ϊģ��û�ı䣬�ᵼ�´������ȥ��Щѡ��� -->
          <tr> 
            <td class='bline' height="26" align="center"><font color='red'>����ģ�ͣ�</font> </td>
            <td class='bline'>
            <?php    
            foreach($channelArray as $k=>$arr)
            {
            	if($k==$channelid) echo "{$arr['typename']} | {$arr['nid']}";
            }
            ?>
            <a href='catalog_edit.php?id=<?php echo $id; ?>'><u>[�޸�]</u></a>
            </td>
          </tr>
          <tr> 
            <td class='bline' height="26" align="center"><font color='red'>��Ŀ���ƣ�</font></td>
            <td class='bline'><input name="typename" type="text" id="typename" size="30" value="<?php echo $myrow['typename']?>" class="iptxt" /></td>
          </tr>
          <tr> 
            <td class='bline' height="26" align="center"> ����˳�� </td>
            <td class='bline'> <input name="sortrank" size="6" type="text" value="<?php echo $myrow['sortrank']?>" class="iptxt" />
              ���ɵ� -&gt; �ߣ� </td>
          </tr>
          <tr> 
            <td class='bline' height="26" align="center">���Ȩ�ޣ�</td>
            <td class='bline'> <select name="corank" id="corank" style="width:100">
                <?php
              $dsql->SetQuery("Select * from #@__arcrank where rank >= 0");
              $dsql->Execute();
              while($row = $dsql->GetObject())
              {
              	if($myrow['corank']==$row->rank)
              	  echo "<option value='".$row->rank."' selected>".$row->membername."</option>\r\n";
				        else
				          echo "<option value='".$row->rank."'>".$row->membername."</option>\r\n";
              }
              ?>
              </select>
              (��������Ŀ����ĵ����Ȩ��) </td>
          </tr>
          <tr>
              <td class='bline' height="26" align="center">�ļ�����Ŀ¼��</td>
              <td class='bline'><input name="typedir" type="text" id="typedir" value="<?php echo $myrow['typedir']?>" style="width:300px"  class="iptxt" /></td>
          </tr>
          <tr> 
            <td height="26" align="center" class='bline'>��Ŀ�б�ѡ�</td>
            <td class='bline'> <input type='radio' name='isdefault' value='1' class='np'<?php if($myrow['isdefault']==1) echo " checked='1' ";?>/>
              ���ӵ�Ĭ��ҳ 
              <input type='radio' name='isdefault' value='0' class='np'<?php if($myrow['isdefault']==0) echo " checked='1' ";?>/>
              ���ӵ��б��һҳ 
              <input type='radio' name='isdefault' value='-1' class='np'<?php if($myrow['isdefault']==-1) echo " checked='1' ";?>/>
              ʹ�ö�̬ҳ </td>
          </tr>
          <tr> 
            <td class='bline' height="26" align="center">Ĭ��ҳ�����ƣ� </td>
            <td class='bline'><input name="defaultname" type="text" value="<?php echo $myrow['defaultname']?>" class="iptxt" /></td>
          </tr>
          <tr> 
            <td height="26" class='bline' align="center">��Ŀ���ԣ�</td>
            <td class='bline'>
            	<input name="ispart" type="radio" id="radio" value="0" class='np'<?php if($myrow['ispart']==0) echo " checked='1' ";?>/>
              �����б���Ŀ�������ڱ���Ŀ�����ĵ����������ĵ��б�<br>
              <input name="ispart" type="radio" id="radio2" value="1" class='np'<?php if($myrow['ispart']==1) echo " checked='1' ";?>/>

              Ƶ�����棨��Ŀ�����������ĵ���<br>
              <input name="ispart" type="radio" id="radio3" value="2" class='np'<?php if($myrow['ispart']==2) echo " checked='1' ";?>/>
              �ⲿ���ӣ���"�ļ�����Ŀ¼"����д��ַ��              </td>
          </tr>
          <tr>          	
            <td align="center" colspan="2" height="54" bgcolor='#FAFEE0'>
            <input name="imageField" type="image" src="img/button_ok.gif" width="60" height="22" border="0" class="np"/>
            &nbsp;&nbsp;&nbsp;
            <a title='�ر�' onclick='CloseMsg()'><img src="img/button_back.gif" width="60" height="22" border="0"></a>
            </td>
          </tr>
      </table>
	  </form>
	<?php
	exit();
}
else 
{
	include DedeInclude('templets/catalog_edit.htm');
}
?>