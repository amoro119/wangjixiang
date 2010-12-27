<?php
//error_reporting(E_ALL);
error_reporting(E_ALL || ~E_NOTICE);
define('DEDEINC', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) ) );
define('DEDEROOT', ereg_replace("[/\\]{1,}", '/', substr(DEDEINC,0,-8) ) );
define('DEDEDATA', DEDEROOT.'/data');
define('DEDEMEMBER', DEDEROOT.'/member');
define('DEDETEMPLATE', DEDEROOT.'/templets');

//����register_globals������಻��ȫ�����ԣ����ǿ��Ҫ��ر�register_globals
if ( ini_get('register_globals') )
{
    exit('<a href="http://docs.dedecms.com/doku.php?id=register_globals">php.ini register_globals must is Off! </a>');
}

//��ֹ session.auto_start
if ( ini_get('session.auto_start') != 0 )
{
    exit('<a href="http://docs.dedecms.com/doku.php?id=session_auto_start">php.ini session.auto_start must is 0 ! </a>');
}

//����ע���ⲿ�ύ�ı���
foreach($_REQUEST as $_k=>$_v)
{
	if( strlen($_k)>0 && eregi('^(cfg_|GLOBALS)',$_k) )
	{
		exit('Request var not allow!');
	}
}

//�Ƿ�����mb_substr�滻cn_substr�����Ч��
$cfg_is_mb = $cfg_is_iconv = false;
if(function_exists('mb_substr')) $cfg_is_mb = true;
if(function_exists('iconv_substr')) $cfg_is_iconv = true;

function _RunMagicQuotes(&$svar)
{
	if(!get_magic_quotes_gpc())
	{
		if( is_array($svar) )
		{
			foreach($svar as $_k => $_v) $svar[$_k] = _RunMagicQuotes($_v);
		}
		else
		{
			$svar = addslashes($svar);
		}
	}
	return $svar;
}

foreach(Array('_GET','_POST','_COOKIE') as $_request)
{
	foreach($$_request as $_k => $_v) ${$_k} = _RunMagicQuotes($_v);
}

//ϵͳ��ر������
if(!isset($needFilter))
{
	$needFilter = false;
}
$registerGlobals = @ini_get("register_globals");
$isUrlOpen = @ini_get("allow_url_fopen");
$isSafeMode = @ini_get("safe_mode");
if( eregi('windows', @getenv('OS')) )
{
	$isSafeMode = false;
}

//Session����·��
$sessSavePath = DEDEDATA."/sessions/";
if(is_writeable($sessSavePath) && is_readable($sessSavePath))
{
	session_save_path($sessSavePath);
}

//ϵͳ���ò���
require_once(DEDEDATA."/config.cache.inc.php");

//ת���ϴ����ļ���صı�������ȫ����������ǰ̨ͨ�õ��ϴ�����
if($_FILES)
{
	require_once(DEDEINC.'/uploadsafe.inc.php');
}

//���ݿ������ļ�
require_once(DEDEDATA.'/common.inc.php');

//����ϵͳ��֤��ȫ����
if(file_exists(DEDEDATA.'/safe/inc_safe_config.php'))
{
	require_once(DEDEDATA.'/safe/inc_safe_config.php');
	if(!empty($safe_faqs)) $safefaqs = unserialize($safe_faqs);
}


//php5.1�汾����ʱ������
//�����������������php5.1���°汾�������壬���ʵ���ϵ�ʱ����ã�Ӧ����MyDate��������
if(PHP_VERSION > '5.1')
{
	$time51 = $cfg_cli_time * -1;
	@date_default_timezone_set('Etc/GMT'.$time51);
}
$cfg_isUrlOpen = @ini_get("allow_url_fopen");

//�û����ʵ���վhost
$cfg_clihost = 'http://'.$_SERVER['HTTP_HOST'];

//վ���Ŀ¼
$cfg_basedir = eregi_replace($cfg_cmspath.'/include$','',DEDEINC);
if($cfg_multi_site == 'Y')
{
	$cfg_mainsite = $cfg_basehost;
}
else
{
	$cfg_mainsite = '';
}

//ģ��Ĵ��Ŀ¼
$cfg_templets_dir = $cfg_cmspath.'/templets';
$cfg_templeturl = $cfg_mainsite.$cfg_templets_dir;
$cfg_templets_skin = empty($cfg_df_style)? $cfg_mainsite.$cfg_templets_dir."/default" : $cfg_mainsite.$cfg_templets_dir."/$cfg_df_style";

//cms��װĿ¼����ַ
$cfg_cmsurl = $cfg_mainsite.$cfg_cmspath;

//���Ŀ¼�����Ŀ¼�����ڴ�ż�������ͶƱ�����۵ȳ���ı�Ҫ��̬����
$cfg_plus_dir = $cfg_cmspath.'/plus';
$cfg_phpurl = $cfg_mainsite.$cfg_plus_dir;

$cfg_data_dir = $cfg_cmspath.'/data';
$cfg_dataurl = $cfg_mainsite.$cfg_data_dir;

//��ԱĿ¼
$cfg_member_dir = $cfg_cmspath.'/member';
$cfg_memberurl = $cfg_mainsite.$cfg_member_dir;

//ר���б�Ĵ��·��
$cfg_special = $cfg_cmspath.'/special';
$cfg_specialurl = $cfg_mainsite.$cfg_special;

//����Ŀ¼
$cfg_medias_dir = $cfg_cmspath.$cfg_medias_dir;
$cfg_mediasurl = $cfg_mainsite.$cfg_medias_dir;

//�ϴ�����ͨͼƬ��·��,���鰴Ĭ��
$cfg_image_dir = $cfg_medias_dir.'/allimg';

//�ϴ�������ͼ
$ddcfg_image_dir = $cfg_medias_dir.'/litimg';

//�û�Ͷ��ͼƬ���Ŀ¼
$cfg_user_dir = $cfg_medias_dir.'/userup';

//�ϴ������Ŀ¼
$cfg_soft_dir = $cfg_medias_dir.'/soft';

//�ϴ��Ķ�ý���ļ�Ŀ¼
$cfg_other_medias = $cfg_medias_dir.'/media';

//���ժҪ��Ϣ��****�벻Ҫɾ������**** ����ϵͳ�޷���ȷ����ϵͳ©����������Ϣ
$cfg_version = 'V56_GBK';
$cfg_soft_lang = 'gb2312';
$cfg_soft_public = 'base';

$cfg_softname = '֯�����ݹ���ϵͳ';
$cfg_soft_enname = 'DedeCms';
$cfg_soft_devteam = 'Dedecms�ٷ��Ŷ�';

//�ĵ���Ĭ����������
$art_shortname = $cfg_df_ext = '.html';
$cfg_df_namerule = '{typedir}/{Y}/{M}{D}/{aid}'.$cfg_df_ext;

//�½�Ŀ¼��Ȩ�ޣ������ʹ�ñ�����ԣ����̲���֤������˳����Linux��Unixϵͳ����
if(isset($cfg_ftp_mkdir) && $cfg_ftp_mkdir=='Y')
{
	$cfg_dir_purview = '0755';
}
else
{
	$cfg_dir_purview = 0755;
}


//��Ա�Ƿ�ʹ�þ���ģʽ���ѽ��ã�
$cfg_mb_lit = 'N';

//����ȫ�ֱ���
$_sys_globals['curfile'] = '';
$_sys_globals['typeid'] = 0;
$_sys_globals['typename'] = '';
$_sys_globals['aid'] = 0;

if(empty($cfg_addon_savetype))
{
	$cfg_addon_savetype = 'Ymd';
}
if($cfg_sendmail_bysmtp=='Y' && !empty($cfg_smtp_usermail))
{
	$cfg_adminemail = $cfg_smtp_usermail;
}

if(!isset($cfg_NotPrintHead)) {
	header("Content-Type: text/html; charset={$cfg_soft_lang}");
}


//�������ݿ���
require_once(DEDEINC.'/dedesql.class.php');

//ȫ�ֳ��ú���
require_once(DEDEINC.'/common.func.php');

?>