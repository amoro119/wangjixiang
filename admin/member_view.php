<?php
require(dirname(__FILE__)."/config.php");
CheckPurview('member_Edit');
$ENV_GOBACK_URL = isset($_COOKIE['ENV_GOBACK_URL']) ? "member_main.php" : '';
$id = ereg_replace("[^0-9]","",$id);
$row = $dsql->GetOne("select  * from #@__member where mid='$id'");

$staArr = array(
          	-10=>'�ȴ���֤�ʼ�',
          	-2=>'�����û�(����)',
          	-1=>'δͨ�����',
           	0=>'���ͨ������ʾ��д������Ϣ',
           	1=>'û��д��ϸ����',
           	2=>'����ʹ��״̬'
         );

//�������û��ǹ���Ա�ʺţ��������㹻Ȩ�޵��û����ܲ���
if($row['matt']==10)
{
	CheckPurview('sys_User');
}
if($row['uptime']>0 && $row['exptime']>0){
	$mhasDay = $row['exptime'] - ceil((time() - $row['uptime'])/3600/24)+1;
}else{
	$mhasDay = 0;
}
include DedeInclude('templets/member_view.htm');

?>