<?php
/*---------------------------------------------------------------------------------------------
   �ļ�Զ�̷���Ŀ¼��������(config.file.inc.php)
   ˵��:���ļ���Ҫ����ϵͳ��̨[����]-[�Զ�����]-[Զ�̷�����ͬ��]
   �е��ļ����Զ��������������,ʹ��ʱ��������ض�Ŀ¼��Ҫ����ͬ
   ��,����Ҫ������ļ��н�������,���÷�ʽ����:
   $remotefile[] = array(
       'filedir' => '/yourdir', //ͬ���ļ���Ŀ¼
       'description' => '�������ļ���˵��', 
       'dfserv' => '0', //Ĭ�Ϸ�����ѡ��,������ϵͳ��̨[ϵͳ����]-[�������ֲ�/Զ�� ]������
       'state' => '0', //ͬ��״̬,0:δͬ�� 1:��ͬ��
   );
-----------------------------------------------------------------------------------------------*/   
global $remotefile;
$remotefile = array();

//�����Ǳر�ͬ��������
//@start_config ��Ҫ�Ķ�����<>�ṹ
#<s_config>

$remotefile[0] = array(
  'filedir'=>'/a',
  'description'=>'�ĵ�HTMLĬ�ϱ���·',
  'dfserv'=>0,
  'state'=>1,
  'issystem'=>1
);
$remotefile[1] = array(
  'filedir'=>'/longhoo',
  'description'=>'�ĵ�HTMLĬ�ϱ���·',
  'dfserv'=>0,
  'state'=>1,
  'issystem'=>1
);
$remotefile[2] = array(
  'filedir'=>'/uploads',
  'description'=>'ͼƬ/�ϴ��ļ�Ĭ��·��',
  'dfserv'=>0,
  'state'=>1,
  'issystem'=>1
);
$remotefile[3] = array(
  'filedir'=>'/special',
  'description'=>'ר��Ŀ¼',
  'dfserv'=>0,
  'state'=>1,
  'issystem'=>1
);
$remotefile[4] = array(
  'filedir'=>'/data/js',
  'description'=>'����jsĿ¼',
  'dfserv'=>0,
  'state'=>1,
  'issystem'=>1
);
$remotefile[5] = array(
  'filedir'=>'/images',
  'description'=>'ͼƬ�زĴ���ļ�',
  'dfserv'=>0,
  'state'=>1,
  'issystem'=>0
);
$remotefile[6] = array(
  'filedir'=>'/templets/images',
  'description'=>'ģ���ļ�ͼƬ���Ŀ¼',
  'dfserv'=>0,
  'state'=>1,
  'issystem'=>0
);
$remotefile[7] = array(
  'filedir'=>'/templets/style',
  'description'=>'ģ���ļ�CSS��ʽ���Ŀ¼',
  'dfserv'=>0,
  'state'=>1,
  'issystem'=>0
);
#<e_config>

?>