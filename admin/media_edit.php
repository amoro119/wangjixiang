<?php
require_once(dirname(__FILE__)."/config.php");

//Ȩ�޼��
CheckPurview('sys_Upload,sys_MyUpload');
if(empty($dopost))
{
	$dopost = "";
}
$backurl = isset($_COOKIE['ENV_GOBACK_URL']) ? $_COOKIE['ENV_GOBACK_URL'] : "javascript:history.go(-1);";

/*---------------------------
function __del_file() //ɾ������
-----------------------------*/
if($dopost=='del')
{
	CheckPurview('sys_DelUpload');
	if(empty($ids))
	{
		$ids="";
	}
	if($ids=="")
	{
		$myrow = $dsql->GetOne("Select url From #@__uploads where aid='".$aid."'");
		$truefile = $cfg_basedir.$myrow['url'];
		$rs = 0;
		if(!file_exists($truefile)||$myrow['url']=="")
		{
			$rs = 1;
		}
		else
		{
			$rs = @unlink($truefile);
			//�������Զ�̸�������Ҫͬ��ɾ���ļ�
      if($cfg_remote_site=='Y')
      {
      	if($ftp->connect($ftpconfig) && $remoteuploads == 1)
      	{
      		$remotefile = str_replace(DEDEROOT, '', $truefile);
      		$ftp->delete_file($remotefile);
      	}
      }
			
		}
		if($rs==1)
		{
			$msg = "�ɹ�ɾ��һ��������";
			$dsql->ExecuteNoneQuery("Delete From #@__uploads where aid='".$aid."'");
		}
		ShowMsg($msg,$backurl);
		exit();
	}
	else
	{
		$ids = explode(',',$ids);
		$idquery = "";
		foreach($ids as $aid)
		{
			if($idquery=="")
			{
				$idquery .= " where aid='$aid' ";
			}
			else
			{
				$idquery .= " Or aid='$aid' ";
			}
		}
		$dsql->SetQuery("Select aid,url From #@__uploads $idquery ");
		$dsql->Execute();
		
		//�������Զ�̸�������Ҫͬ��ɾ���ļ�
    if($cfg_remote_site=='Y' && $remoteuploads == 1)
    {
    	$ftp->connect($ftpconfig);
    }
		while($myrow=$dsql->GetArray())
		{
			$truefile = $cfg_basedir.$myrow['url'];
			$rs = 0;
			if(!file_exists($truefile)||$myrow['url']=="")
			{
				$rs = 1;
			}
			else
			{
				$rs = @unlink($truefile);
				if($cfg_remote_site=='Y' && $remoteuploads == 1)
        {
        	$remotefile = str_replace(DEDEROOT, '', $truefile);
        	$ftp->delete_file($remotefile);
        }
			}
			if($rs==1)
			{
				$dsql->ExecuteNoneQuery("Delete From #@__uploads where aid='".$myrow['aid']."'");
			}
		}
		ShowMsg('�ɹ�ɾ��ѡ�����ļ���',$backurl);
		exit();
	}
}
/*--------------------------------
function __save_edit() //�������
-----------------------------------*/
else if($dopost=='save')
{
	if($aid=="")
	{
		exit();
	}

	//����Ƿ����޸�Ȩ��
	$myrow = $dsql->GetOne("Select * From #@__uploads where aid='".$aid."'");
	if($myrow['mid']!=$cuserLogin->getUserID())
	{
		CheckPurview('sys_Upload');
	}

	//����ļ�����
	$addquery = "";
	if(is_uploaded_file($upfile))
	{
		if($mediatype==1)
		{
			$sparr = Array("image/pjpeg","image/jpeg","image/gif","image/png","image/xpng","image/wbmp");
			if(!in_array($upfile_type,$sparr))
			{
				ShowMsg("���ϴ��Ĳ���ͼƬ���͵��ļ���","javascript:history.go(-1);");
				exit();
			}
		}
		else if($mediatype==2)
		{
			$sparr = Array("application/x-shockwave-flash");
			if(!in_array($upfile_type,$sparr))
			{
				ShowMsg("���ϴ��Ĳ���Flash���͵��ļ���","javascript:history.go(-1);");
				exit();
			}
		}else if($mediatype==3)
		{
			if(!eregi('audio|media|video',$upfile_type))
			{
				ShowMsg("���ϴ���Ϊ����ȷ���͵�Ӱ���ļ���","javascript:history.go(-1);");
				exit();
			}
			if(!eregi("\.".$cfg_mediatype,$upfile_name))
			{
				ShowMsg("���ϴ���Ӱ���ļ���չ���޷���ʶ�������ϵͳ���õĲ�����","javascript:history.go(-1);");
				exit();
			}
		}else
		{
			if(!eregi("\.".$cfg_softtype,$upfile_name))
			{
				ShowMsg("���ϴ��ĸ�����չ���޷���ʶ�������ϵͳ���õĲ�����","javascript:history.go(-1);");
				exit();
			}
		}

		//�����ļ�
		$nowtime = time();
		$oldfile = $myrow['url'];
		$oldfiles = explode('/',$oldfile);
		$fullfilename = $cfg_basedir.$oldfile;
		$oldfile_path = ereg_replace($oldfiles[count($oldfiles)-1]."$","",$oldfile);
		if(!is_dir($cfg_basedir.$oldfile_path))
		{
			MkdirAll($cfg_basedir.$oldfile_path,777);
			CloseFtp();
		}
		@move_uploaded_file($upfile,$fullfilename);
		if($mediatype==1)
		{
			require_once(DEDEINC."/image.func.php");
			if(in_array($upfile_type,$cfg_photo_typenames))
			{
				WaterImg($fullfilename,'up');
			}
		}
		$filesize = $upfile_size;
		$imgw = 0;
		$imgh = 0;
		if($mediatype==1)
		{
			$info = "";
			$sizes[0] = 0; $sizes[1] = 0;
			$sizes = @getimagesize($fullfilename,$info);
			$imgw = $sizes[0];
			$imgh = $sizes[1];
		}
		if($imgw>0)
		{
			$addquery = ",width='$imgw',height='$imgh',filesize='$filesize' ";
		}
		else
		{
			$addquery = ",filesize='$filesize' ";
		}
	}
	else
	{
		$fileurl = $filename;
	}

	//д�����ݿ�
	$query = " update #@__uploads set title='$title',mediatype='$mediatype',playtime='$playtime'";
	$query .= "$addquery where aid='$aid' ";
	$dsql->ExecuteNoneQuery($query);
	ShowMsg('�ɹ�����һ�򸽼����ݣ�','media_edit.php?aid='.$aid);
	exit();
}

//��ȡ������Ϣ
$myrow = $dsql->GetOne("Select * From #@__uploads where aid='".$aid."'");
if(!is_array($myrow))
{
	ShowMsg('�����Ҳ����˱�ŵĵ�����','javascript:;');
	exit();
}
include DedeInclude('templets/media_edit.htm');

?>