<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_module');
require_once(dirname(__FILE__)."/../include/dedemodule.class.php");
require_once(dirname(__FILE__)."/../include/oxwindow.class.php");
if(empty($action)) $action = '';
$mdir = DEDEROOT.'/data/module';
if($action=='upload')
{
	if( !is_uploaded_file($upfile) )
	{
		ShowMsg("ò����ʲô��û���ϴ�Ŷ��","javascript:;");
		exit();
	}
	else
	{
		include_once(dirname(__FILE__)."/../include/zip.class.php");
		$tmpfilename = $mdir.'/'.ExecTime().mt_rand(10000,50000).'.tmp';
		move_uploaded_file($upfile,$tmpfilename) or die("���ϴ����ļ��ƶ���{$tmpfilename}ʱʧ�ܣ�����{$mdir}Ŀ¼�Ƿ���д��Ȩ�ޣ�");

		//ZIP��ʽ���ļ�
		if($filetype==1){
			$z = new zip();
			$files = $z->get_List($tmpfilename);
			$dedefileindex = -1;
			//Ϊ�˽�ʡ��Դ��ϵͳ����.xml��Ϊ��չ��ʶ��ZIP������dedeģ���ʽ�ļ�
			if(is_array($files)){
		  for($i=0;$i<count($files);$i++)
		  {
				if( eregi( "\.xml",$files[$i]['filename']) ){
						$dedefile = $files[$i]['filename'];
						$dedefileindex = $i;
						break;
				}
			}}
			if($dedefileindex==-1)
			{
	  	   unlink($tmpfilename);
	  	   ShowMsg("�Բ������ϴ���ѹ�����в�����dedeģ���ļ���<br /><br /><a href='javascript:history.go(-1);'>&gt;&gt;���������ϴ�&gt;&gt;</a>","javascript:;");
	  	   exit();
			}
			$ziptmp = $mdir.'/ziptmp';
			$z->Extract($tmpfilename,$ziptmp,$dedefileindex);
			unlink($tmpfilename);
			$tmpfilename = $mdir."/ziptmp/".$dedefile;
		}

		$dm = new DedeModule($mdir);
	  $infos = $dm->GetModuleInfo($tmpfilename,'file');
	  if(empty($infos['hash']))
	  {
	  	unlink($tmpfilename);
	  	$dm->Clear();
	  	ShowMsg("�Բ������ϴ����ļ����ܲ���֯��ģ��ı�׼��ʽ�ļ���<br /><br /><a href='javascript:history.go(-1);'>&gt;&gt;���������ϴ�&gt;&gt;</a>","javascript:;");
	  	exit();
	  }
	  $okfile = $mdir.'/'.$infos['hash'].'.xml';
	  if($dm->HasModule($infos['hash']) && empty($delhas))
	  {
	  	unlink($tmpfilename);
	  	$dm->Clear();
	    ShowMsg("�Բ������ϴ���ģ���Ѿ����ڣ�<br />���Ҫ��������ɾ��ԭ���汾��ѡ��ǿ��ɾ����ѡ�<br /><br /><a href='javascript:history.go(-1);'>&gt;&gt;���������ϴ�&gt;&gt;</a>","javascript:;");
	  	exit();
	  }
	  @unlink($okfile);
	  copy($tmpfilename,$okfile);
	  @unlink($tmpfilename);
	  $dm->Clear();
	  ShowMsg("�ɹ��ϴ�һ���µ�ģ�飡","module_main.php?action=view&hash={$infos['hash']}");
	  exit();
	}
}
else
{
	$win = new OxWindow();
	$win->Init("module_upload.php","js/blank.js","POST' enctype='multipart/form-data");
	$win->mainTitle = "ģ�����";
	$wecome_info = "<a href='module_main.php'>ģ�����</a> &gt;&gt; �ϴ�ģ��";
	$win->AddTitle('��ѡ��Ҫ�ϴ����ļ�:');
	$win->AddHidden("action",'upload');
	$msg = "
	<table width='600' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td height='30'>�ļ���ʽ��</td>
    <td>
    <input name='filetype' type='radio' value='0' checked='checked' />
      ������ģ���
      <input type='radio' name='filetype' value='1' />
    ���� zip ѹ����ģ��� </td>
  </tr>
  <tr>
    <td height='30'>����ģ�飺</td>
    <td>
	<input name='delhas' type='checkbox' id='delhas' value='1' /> ǿ��ɾ��ͬ��ģ��(����ܵ����Ѿ���װ��ģ���޷�ж��)
	 </td>
  </tr>
  <tr>
    <td width='96' height='60'>��ѡ���ļ���</td>
    <td width='504'>
	<input name='upfile' type='file' id='upfile' style='width:380px' />	</td>
  </tr>
 </table>
	";
	$win->AddMsgItem("<div style='padding-left:20px;line-height:150%'>$msg</div>");
	$winform = $win->GetWindow('ok','');
	$win->Display();
	exit();
}

//ClearAllLink();
?>