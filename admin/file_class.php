<?php
if(!defined('DEDEINC'))
{
	exit('');
}

// �ļ������߼���
class FileManagement
{
	var $baseDir="";
	var $activeDir="";

	//�Ƿ������ļ�������ɾ��Ŀ¼��
	//Ĭ��Ϊ������ 0 ,���ϣ�����ܹ�������Ŀ¼,���ֵ��Ϊ 1 ��
	var $allowDeleteDir=0;

	//��ʼ��ϵͳ
	function Init()
	{
		global $cfg_basedir, $activepath;
		$this->baseDir = $cfg_basedir;
		$this->activeDir = $activepath;
	}

	//�����ļ���
	function RenameFile($oldname,$newname)
	{
		$oldname = $this->baseDir.$this->activeDir."/".$oldname;
		$newname = $this->baseDir.$this->activeDir."/".$newname;
		if(($newname!=$oldname) && is_writable($oldname))
		{
			rename($oldname,$newname);
		}
		ShowMsg("�ɹ�����һ���ļ�����","file_manage_main.php?activepath=".$this->activeDir);
		return 0;
	}

	//������Ŀ¼
	function NewDir($dirname)
	{
		$newdir = $dirname;
		$dirname = $this->baseDir.$this->activeDir."/".$dirname;
		if(is_writable($this->baseDir.$this->activeDir))
		{
			MkdirAll($dirname,$GLOBALS['cfg_dir_purview']);
			CloseFtp();
			ShowMsg("�ɹ�����һ����Ŀ¼��","file_manage_main.php?activepath=".$this->activeDir."/".$newdir);
			return 1;
		}
		else
		{
			ShowMsg("������Ŀ¼ʧ�ܣ���Ϊ���λ�ò�����д�룡","file_manage_main.php?activepath=".$this->activeDir);
			return 0;
		}
	}

	//�ƶ��ļ�
	function MoveFile($mfile,$mpath)
	{
		if($mpath!="" && !ereg("\.\.",$mpath))
		{
			$oldfile = $this->baseDir.$this->activeDir."/$mfile";
			$mpath = str_replace("\\","/",$mpath);
			$mpath = ereg_replace("/{1,}","/",$mpath);
			if(!ereg("^/",$mpath))
			{
				$mpath = $this->activeDir."/".$mpath;
			}
			$truepath = $this->baseDir.$mpath;
			if(is_readable($oldfile) && is_readable($truepath) && is_writable($truepath))
			{
				if(is_dir($truepath))
				{
					copy($oldfile,$truepath."/$mfile");
				}
				else
				{
					MkdirAll($truepath,$GLOBALS['cfg_dir_purview']);
					CloseFtp();
					copy($oldfile,$truepath."/$mfile");
				}
				unlink($oldfile);
				ShowMsg("�ɹ��ƶ��ļ���","file_manage_main.php?activepath=$mpath",0,1000);
				return 1;
			}
			else
			{
				ShowMsg("�ƶ��ļ� $oldfile -&gt; $truepath/$mfile ʧ�ܣ�������ĳ��λ��Ȩ�޲��㣡","file_manage_main.php?activepath=$mpath",0,1000);
				return 0;
			}
		}
		else
		{
			ShowMsg("�Բ������ƶ���·�����Ϸ���","-1",0,5000);
			return 0;
		}
	}

	/**
	 * ɾ��Ŀ¼
	 *
	 * @param unknown_type $indir
	 */
	function RmDirFiles($indir)
	{
		if(!is_dir($indir))
		{
			return ;
		}
		$dh = dir($indir);
		while($filename = $dh->read())
		{
			if($filename == "." || $filename == "..")
			{
				continue;
			}
			else if(is_file("$indir/$filename"))
			{
				@unlink("$indir/$filename");
			}
			else
			{
				$this->RmDirFiles("$indir/$filename");
			}
		}
		$dh->close();
		@rmdir($indir);
	}

	/**
	 * ���ĳĿ¼�Ϸ�������ļ�
	 *
	 * @param unknown_type $indir
	 * @param unknown_type $fileexp
	 * @param unknown_type $filearr
	 */
	function GetMatchFiles($indir,$fileexp,&$filearr)
	{
		$dh = dir($indir);
		while($filename = $dh->read())
		{
			$truefile = $indir.'/'.$filename;
			if($filename == "." || $filename == "..")
			{
				continue;
			}
			else if(is_dir($truefile))
			{
				$this->GetMatchFiles($truefile,$fileexp,$filearr);
			}
			else if(preg_match("/\.(".$fileexp.")/i",$filename))
			{
				$filearr[] = $truefile;
			}
		}
		$dh->close();
	}

	/**
	 * ɾ���ļ�
	 *
	 * @param unknown_type $filename
	 * @return unknown
	 */
	function DeleteFile($filename)
	{
		$filename = $this->baseDir.$this->activeDir."/$filename";
		if(is_file($filename))
		{
			@unlink($filename); $t="�ļ�";
		}
		else
		{
			$t = "Ŀ¼";
			if($this->allowDeleteDir==1)
			{
				$this->RmDirFiles($filename);
			}
		}
		ShowMsg("�ɹ�ɾ��һ��".$t."��","file_manage_main.php?activepath=".$this->activeDir);
		return 0;
	}
}

//Ŀ¼�ļ���С�����
class SpaceUse
{
	var $totalsize=0;

	function checksize($indir)
	{
		$dh=dir($indir);
		while($filename=$dh->read())
		{
			if(!ereg("^\.",$filename))
			{
				if(is_dir("$indir/$filename"))
				{
					$this->checksize("$indir/$filename");
				}
				else
				{
					$this->totalsize=$this->totalsize + filesize("$indir/$filename");
				}
			}
		}
	}

	function setkb($size)
	{
		$size=$size/1024;

		if($size>0)
		{
			list($t1,$t2)=explode(".",$size);
			$size=$t1.".".substr($t2,0,1);
		}
		return $size;
	}

	function setmb($size)
	{
		$size=$size/1024/1024;
		if($size>0)
		{
			list($t1,$t2)=explode(".",$size);
			$size=$t1.".".substr($t2,0,2);
		}
		return $size;
	}
}

?>