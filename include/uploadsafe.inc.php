<?php
if(!defined('DEDEINC')) exit('Request Error!');

if(isset($_FILES['GLOBALS'])) exit('Request not allow!');

//Ϊ�˷�ֹ�û�ͨ��ע��Ŀ����ԸĶ������ݿ�
//����ǿ���޶���ĳЩ�ļ����ͽ�ֹ�ϴ�
$cfg_not_allowall = "php|pl|cgi|asp|aspx|jsp|php3|shtm|shtml";
$keyarr = array('name','type','tmp_name','size');

foreach($_FILES as $_key=>$_value)
{
	foreach($keyarr as $k)
	{
		if(!isset($_FILES[$_key][$k]))
		{
			exit('Request Error!');
		}
	}
	if( eregi('^(cfg_|GLOBALS)',$_key) )
	{
		exit('Request var not allow for uploadsafe!');
	}
	$$_key = $_FILES[$_key]['tmp_name'] = str_replace("\\\\","\\",$_FILES[$_key]['tmp_name']);
	${$_key.'_name'} = $_FILES[$_key]['name'];
	${$_key.'_type'} = $_FILES[$_key]['type'] = eregi_replace('[^0-9a-z\./]','',$_FILES[$_key]['type']);
	${$_key.'_size'} = $_FILES[$_key]['size'] = ereg_replace('[^0-9]','',$_FILES[$_key]['size']);
	if(!empty(${$_key.'_name'}) && (eregi("\.(".$cfg_not_allowall.")$",${$_key.'_name'}) || !ereg("\.",${$_key.'_name'})) )
	{
		if(!defined('DEDEADMIN'))
		{
			exit('Upload filetype not allow !');
		}
	}
	if(empty(${$_key.'_size'}))
	{
		${$_key.'_size'} = @filesize($$_key);
	}
	
	$imtypes = array("image/pjpeg","image/jpeg","image/gif","image/png","image/xpng","image/wbmp","image/bmp");
    if(in_array(strtolower(trim(${$_key.'_type'})),$imtypes))
    {
        $image_dd = @getimagesize($$_key);
        if (!is_array($image_dd))
        {
            exit('Upload filetype not allow !');
        }
    }
}
?>