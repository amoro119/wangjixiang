<?php
require_once(dirname(__FILE__)."/config.php");
AjaxHead();
if($myurl == '')
{
	exit('');
}
$uid  = $cfg_ml->M_LoginID;

!$cfg_ml->fields['face'] && $face = ($cfg_ml->fields['sex'] == 'Ů')? 'dfgirl' : 'dfboy';
$facepic = empty($face)? $cfg_ml->fields['face'] : $GLOBALS['cfg_memberurl'].'/templets/images/'.$face.'.png';

?>
<div class="userinfo">
    <div class="welcome">��ã�<strong><?php echo $cfg_ml->M_UserName; ?></strong>����ӭ��¼ </div>
    <div class="userface">
        <a href="<?php echo $cfg_memberurl; ?>/index.php"><img src="<?php echo $facepic;?>" width="52" height="52" /></a>
    </div>
    <div class="mylink">
        <ul>
            <li><a href="<?php echo $cfg_memberurl; ?>/guestbook_admin.php">�ҵ�����</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/mystow.php">�ҵ��ղ�</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/article_add.php">��������</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/myfriend.php">���ѹ���</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/visit-history.php">�ÿͼ�¼</a></li>
            <li><a href="<?php echo $cfg_memberurl; ?>/search.php">���Һ���</a></li>
        </ul>
    </div>
    <div class="uclink">
        <a href="<?php echo $cfg_memberurl; ?>/index.php">��Ա����</a> | 
        <a href="<?php echo $cfg_memberurl; ?>/edit_fullinfo.php">����</a> | 
        <a href="<?php echo $myurl;?>">�ռ�</a> | 
        <a href="<?php echo $cfg_memberurl; ?>/index_do.php?fmdo=login&dopost=exit">�˳���¼</a> 
    </div>
</div><!-- /userinfo -->