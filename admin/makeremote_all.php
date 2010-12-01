<?php
require_once(dirname(__FILE__)."/config.php");
CheckPurview('sys_MakeHtml');
require_once(DEDEINC."/arc.partview.class.php");

if($cfg_remote_site=='N') exit('Error:$cfg_remote_site is OFF,Check it!');

//���ɱ���ͬ�����������б�
if(file_exists(DEDEDATA.'/config.file.inc.php'))
{
	require_once(DEDEDATA.'/config.file.inc.php');
}
	
if(empty($dopost))
{
	$dopost = '';
}

$step = !isset($step)? 1 : $step;
$sta = !isset($sta)? 0 : $sta;
$totalnum = !isset($totalnum)? 0 : $totalnum;
$maketype = empty($maketype)? '' : $maketype;
//��ȡͬ��״̬
function GetState($val)
{
	$color = ($val == 0)? 'red' : 'green';
	$signer = ($val == 0)? 'δͬ��' : '��ͬ��';
	return '<font color="'.$color.'">'.$signer.'</font>';
}

//����Զ��Ŀ¼����
function addDir($filedir='', $description='', $dfserv=0, $state=0, $issystem=0)
{
	return array(
	    'filedir' => $filedir,
	    'description' => $description,
	    'dfserv' => $dfserv,
	    'state' => $state,
	    'issystem' => $issystem
	);
}

//����������������������
function makeConfig($dirarray=array())
{
	$config_str = '';
	foreach($dirarray as $k => $val)
	{
		$config_str .= '$remotefile['.$k.'] = array('."\n";
		$config_str .= '  \'filedir\'=>\''.$val['filedir']."',\n";
		$config_str .= '  \'description\'=>\''.$val['description']."',\n";
		$config_str .= '  \'dfserv\'=>'.$val['dfserv'].",\n";
		$config_str .= '  \'state\'=>'.$val['state'].",\n";
		$config_str .= '  \'issystem\'=>'.$val['issystem']."\n";
		$config_str .= ");\n";
		
	}
	return ($config_str == '')? '' : $config_str;
}

//�ݹ��ȡĿ¼ by:tianya
function getDirs($directory,$exempt = array('.','..','.ds_store','.svn'),&$files = array()) 
{ 
	//û���򴴽�
	if(is_dir($directory) && !opendir($directory)) mkdir($directory,0777,TRUE);
  $handle = opendir($directory); 
  
  while(false !== ($resource = readdir($handle))) { 
    if(!in_array(strtolower($resource),$exempt)) { 
  	  //�ų�Ŀ¼
      if(is_dir($directory.$resource.'/'))
      { 
        array_merge($files, 
            getDirs($directory.$resource.'/',$exempt,$files)); 
      } else {
      	//if(!is_file($directory.'/'.$resource))
      	//{
          $files[] = $directory.'/'.$resource; 
        //}
      }
    } 
  } 
  closedir($handle); 
  return $files; 
} 

function updateConfig($dirarray=array())
{
	//����ת��Ϊ����д���ļ�
	$configfile = DEDEDATA.'/config.file.inc.php';
	$old_config = @file_get_contents($configfile);
  $config_str = makeConfig($dirarray);
	//�滻������
	$new_config = preg_replace("/#<s_config>(.*)#<e_config>/s", "#<s_config>\n\n{$config_str}#<e_config>", $old_config);
	file_put_contents($configfile, $new_config);
}


if($dopost == '')
{

}
/*
function Updateremote()
*/
else if($dopost == 'updateremote')
{
	$dirbox = array(); // ����һ��Ŀ¼����,ȷ��Ŀ¼��Ψһ��
	//��ȡ����HTML�����ļ����б�
	$query = "SELECT id,typedir,ispart FROM #@__arctype WHERE ispart <> '3'";
	$dsql->SetQuery($query);
	$dsql->Execute('al');
	$dirarray = array();

  $i = 0;
	while ($row = $dsql->GetArray("al"))
	{
		$darray = explode('/',preg_replace('/{cmspath}/', '', $row['typedir']));
		//����ȡ����Ŀ¼��Ϊ�ļ�ͬ������Ŀ¼
    if(!in_array($darray[1],$dirbox))
    {
    	$dirarray[$i] = addDir('/'.$darray[1], '�ĵ�HTMLĬ�ϱ���·', 0, 0, 1);
			$dirbox[] = $darray[1];
			
			$i++;
	  }
	}
	//ϵͳ�������Ŀ¼
	$dirarray[$i++] = addDir($cfg_medias_dir, 'ͼƬ/�ϴ��ļ�Ĭ��·��', 0, 0, 1);
	//ר��Ŀ¼
	$dirarray[$i++] = addDir('/special', 'ר��Ŀ¼', 0, 0, 1);
	//data/jsĿ¼
	$dirarray[$i++] = addDir('/data/js', '����jsĿ¼', 0, 0, 1);
	
	//$remotefile = array();
	//�ѷ�ϵͳĿ¼��������ȡ����
	foreach ($remotefile as $key => $value)
	{
		//�����û��Զ�������
		if($value['issystem'] == 0)
		{
			$dirarray[$i++] = addDir($value['filedir'], $value['description'],
			                         $value['dfserv'], $value['state'], $value['issystem']);
		}
	}
	
	updateConfig($dirarray);
	
	ShowMsg("�ɹ�����ͬ��Ŀ¼,�����¶�Ŀ¼����ͬ������!","makeremote_all.php");
	exit;
}
/*
function Make()&MakeAll()
*/
else if($dopost == 'make')
{
	if($step == 1)
	{
		if($maketype == 'makeall')
		{
			//�����������,����Ҫ�������$Iterm
			foreach($remotefile as $key => $val)
			{
				$Iterm[] = $val['filedir'];
			}
		} else {
			//��ʼ������
			$Iterm = !isset($Iterm)? array(): $Iterm;
		}

		$serviterm = !isset($serviterm)? array(): $serviterm;
		$cacheMakeFile = DEDEDATA.'/cache/filelist.inc.php';
		$dirlist = $alllist = $updir = array();
		$dirindex = 0;//Ŀ¼ͳһ����
		
		//���ñȽ����Ի��ĸ��·�ʽ������ʾ����
		//��ʼ�������ļ��еײ���Ӽ�Ŀ¼
		
		if(count($Iterm) > 0)
		{
			//��ȡԶ���ļ�(��)�б�
			foreach($Iterm as $key => $val)
			{
				$config = $serviterm[$key];
				if(is_array($dirlist = getDirs(DEDEROOT.$val)))
				{
					foreach($dirlist as $k => $v)
					{
						$alllist[] = $v.'|'.$config;
						if(!in_array($val, array_values($updir))) $updir[] = $val;
					}
				}
			}
			//�����ļ����б�,��������Ӽ��ļ��д���3������Ҫ����ϸ��
			//���б�д�뻺��
			$cachestr = "<?php \n  global \$dirlist,\$upremote;\n  \$dirlist=array();\n";
			foreach($alllist as $key => $val)
			{
				list($filename,$fileconfig) = explode('|', $val); 
				if(is_dir($filename))
				{
					$deepDir = getDirs($filename);
					$dd = 0;
					//�ȱ���һ��ȷ���Ӽ��ļ�����Ŀ
					foreach($deepDir as $k => $v)
					{
						if(is_dir($v)) $dd++;
					}
					if($dd > 3)
					{
						//����Լ��ļ�����Ŀ����3��
						foreach($deepDir as $k => $v)
						{
							$v .= '|'.$fileconfig;
							$cachestr .= "  \$dirlist['$dirindex']='$v';\n";
							$dirindex++;
						}
					}else{
						$cachestr .= "  \$dirlist['$dirindex']='$val';\n";
						$dirindex++;
					}
				}
			}
			
			foreach($updir as $key => $val)
			{
				$cachestr .= "  \$upremote['$key']='$val';\n";
			}
			$cachestr .= "?>";
			file_put_contents($cacheMakeFile, $cachestr);
			$tnum = count($alllist);
			ShowMsg("�ɹ���ȡԶ���б�,��������ļ�Զ�̷���!","makeremote_all.php?dopost=make&step=2&sta=1&totalnum=$tnum");
			exit;
		} else {
			echo '��û��ѡ��,����ѡ���ٵ������!';
		}
		exit;	
	} elseif ($step == 2){
		if(file_exists(DEDEDATA.'/cache/filelist.inc.php'))
		{
			require_once(DEDEDATA.'/cache/filelist.inc.php');
		}
		if(is_array($dirlist))
		{
			if($sta > 0 && $sta < $totalnum)
			{
			  list($dirname,$ftpconfig) = explode('|', $dirlist[$sta-1]); 
			  list($servurl,$servuser,$servpwd) = explode(',',$ftpconfig);
				$config=array( 'hostname' => $servurl, 'username' => $servuser,
				               'password' => $servpwd,'debug' => 'TRUE');
				
				if($ftp->connect($config))
				{
					//var_dump(is_dir($dirname));exit;
					if(is_dir($dirname))
					{
						//������ļ�Ŀ¼
						$remotedir = str_replace(DEDEROOT, '', $dirname).'/';
						$localdir = '..'.$remotedir.'/';
						$ftp->rmkdir($remotedir);
						if( $ftp->mirror($localdir, $remotedir))
						{
							$sta++;
							ShowMsg("�ɹ�ͬ���ļ���$remotedir,������һ������","makeremote_all.php?dopost=make&step=2&sta={$sta}&totalnum=$totalnum");
							exit;
						}
					} else {
			  		$remotefile = str_replace(DEDEROOT, '', $dirname);
	          $localfile = '..'.$remotefile;
	          //����Զ���ļ���
	          $remotedir = preg_replace('/[^\/]*\.(\w){0,}/', '', $remotefile);
						//������ļ�����Ҫ���ܴ���
						$remotebox = array();
						$ftp->rmkdir($remotedir);
						foreach($dirlist as $key => $val)
						{
							list($filename,$fileconfig) = explode('|', $val); 
							if(preg_replace('/[^\/]*\.(\w){0,}/', '', str_replace(DEDEROOT, '', $filename)) == $remotedir)
							{
								//�����Щ�ļ�����ͬһĿ¼,��ͳ����Щ��¼��id��Ŀ
								$remotebox[] = $key;
							}
						}
						//print_r($remotebox);
						//if(count($remotebox) > 1 && count($remotebox) < 20)
	          if(count($remotebox) > 1)
	          {
	          	//�������1,��˵���ж�����¼��ͬһ�ļ�����
							$localdir = '..'.$remotedir;
							if( $ftp->mirror($localdir, $remotedir))
							{
								$sta = end($remotebox) + 1;
								ShowMsg("�ɹ�ͬ���ļ���$remotedir,������һ������","makeremote_all.php?dopost=make&step=2&sta={$sta}&totalnum=$totalnum");
								exit;
							}
	          } else {
		          if( $ftp->upload($localfile, $remotefile) )
		          {
								$sta++;
								ShowMsg("�ɹ�ͬ���ļ�$remotefile,������һ������","makeremote_all.php?dopost=make&step=2&sta={$sta}&totalnum=$totalnum");
								exit;
		          }
	          }
					}
			  }
			} else {
				//����ɹ��������
				foreach($remotefile as $key => $val)
				{
					if(in_array($val['filedir'],array_values($upremote)))
					{
						$remotefile[$key]['state'] = 1;
					}
				}
				updateConfig($remotefile);
				@unlink(DEDEDATA.'/cache/filelist.inc.php');
				echo 'ȫ��ͬ�����!';exit;
			}
		} else {
			exit('Error:None remote cache file exist!');
		}
		exit;
	}
}

include DedeInclude('templets/makeremote_all.htm');

?>