<?php
//��ȫ��ʾ���⣬�����䲻Ҫ�޸�
$safequestions = array();
$safequestions[0] = 'û��ȫ��ʾ����';

//��������ÿ����ֹ��޸�
//start****************************

$safequestions[1] = '����ϲ���ĸ���ʲô��';
$safequestions[2] = '������������ʲô��';
$safequestions[3] = '�����Сѧ��ʲô��';
$safequestions[4] = '��ĸ��׽�ʲô���֣�';
$safequestions[5] = '���ĸ�׽�ʲô���֣�';
$safequestions[6] = '����ϲ����ż����˭��';
$safequestions[7] = '����ϲ���ĸ�����ʲô��';

//end****************************



//���²�Ҫ�޸�
function GetSafequestion($selid=0,$formname='safequestion')
{
	global $safequestions;
	$safequestions_form = "<select name='$formname' id='$formname'>";
	foreach($safequestions as $k=>$v)
	{
	 	if($k==$selid) $safequestions_form .= "<option value='$k' selected>$v</option>\r\n";
	 	else $safequestions_form .= "<option value='$k'>$v</option>\r\n";
	}
	$safequestions_form .= "</select>\r\n";
	return $safequestions_form;
}

?>