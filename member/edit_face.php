<?php
require_once(dirname(__FILE__)."/config.php");
CheckRank(0,0);
$menutype = 'config';
if(!isset($dopost))
{
    $dopost = '';
}
if(!isset($backurl))
{
    $backurl = 'edit_face.php';
}
if($dopost=='save')
{
    $maxlength = $cfg_max_face * 1024;
    $userdir = $cfg_user_dir.'/'.$cfg_ml->M_ID;
    if(!preg_match("#^".$userdir."#", $oldface))
    {
        $oldface = '';
    }
    if(is_uploaded_file($face))
    {
        if(@filesize($_FILES['face']['tmp_name']) > $maxlength)
        {
            ShowMsg("���ϴ���ͷ���ļ�������ϵͳ���ƴ�С��{$cfg_max_face} K��", '-1');
            exit();
        }
        //ɾ����ͼƬ����ֹ�ļ���չ����ͬ���磺ԭ������gif����������jpg��
        if(preg_match("#\.(jpg|gif|png)$#i", $oldface) && file_exists($cfg_basedir.$oldface))
        {
            @unlink($cfg_basedir.$oldface);
        }
        //�ϴ��¹�ͼƬ
        $face = MemberUploads('face', $oldface, $cfg_ml->M_ID, 'image', 'myface', 180, 180);
    }
    else
    {
        $face = $oldface;
    }
    $query = "UPDATE `#@__member` SET `face` = '$face' WHERE mid='{$cfg_ml->M_ID}' ";
    $dsql->ExecuteNoneQuery($query);
    ShowMsg('�ɹ�����ͷ����Ϣ��', $backurl);
    exit();
}
else if($dopost=='delold')
{
    if(empty($oldface))
    {
        ShowMsg("û�п�ɾ����ͷ��", "-1");
        exit();
    }
    $userdir = $cfg_user_dir.'/'.$cfg_ml->M_ID;
    if(!preg_match("#^".$userdir."#", $oldface) || preg_match('#\.\.#', $oldface))
    {
        $oldface = '';
    }
    if(preg_match("#\.(jpg|gif|png)$#i", $oldface) && file_exists($cfg_basedir.$oldface))
    {
        @unlink($cfg_basedir.$oldface);
    }
    $query = "UPDATE `#@__member` SET `face` = '' WHERE mid='{$cfg_ml->M_ID}' ";
    $dsql->ExecuteNoneQuery($query);
    ShowMsg('�ɹ�ɾ��ԭ����ͷ��', $backurl);
    exit();
}
$face = $cfg_ml->fields['face'];
include(DEDEMEMBER."/templets/edit_face.htm");
exit();
?>