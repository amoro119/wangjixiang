<?php
require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__)."/actionsearch_class.php");
//����Ȩ�޼��
if(empty($dopost))
{
	$dopost = "";
}
$keyword=empty($keyword)? "" : $keyword;

$actsearch = new ActionSearch($keyword);

$asresult = $actsearch->Search();

include DedeInclude('templets/action_search.htm');

?>