<?php
require_once(dirname(__FILE__).'/../../include/common.inc.php');
//�ɹ�������Ϣ
$dsql->ExecuteNoneQuery("Update `#@__sys_task` set sta='�ɹ�' where dourl='dede-upcache.php' ");
echo "Welcome to DedeCMS!";
exit();
?>