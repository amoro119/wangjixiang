<?php
require_once(DEDEMEMBER."/paycenter/cbpayment/cbpayment_config.php");
if($payment_exp[3] < 0) $payment_exp[3] = 0;
$piice_ex = $price*$payment_exp[3];

$v_oid = trim($buyid); //������
if($piice_ex > 0) $price = $price+$piice_ex;
$v_amount = sprintf("%01.2f", $price);                   //֧�����                 

$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;        //md5����ƴ�մ�,ע��˳���ܱ�
$v_md5info = strtoupper(md5($text));                             //md5�������ܲ�ת���ɴ�д��ĸ

$remark1 = trim($ptype);//��ע�ֶ�1
$remark2 = trim($pname);//��ע�ֶ�2

$v_rcvname   = 'վ��';		// �ջ���
$v_rcvaddr   = '����';		// �ջ���ַ
$v_rcvtel    = '0755-83791960';		// �ջ��˵绰
$v_rcvpost   = '100080';		// �ջ����ʱ�
$v_rcvmobile = '13838384381';		// �ջ����ֻ���

$v_ordername   = $cfg_ml->M_UserName;	// ����������
$v_orderaddr   = '����';	// �����˵�ַ
$v_ordertel    = '0755-83791960';	// �����˵绰
$v_orderpost   = 518000;	// �������ʱ�
$v_orderemail  = 'service@nps.cn';	// �������ʼ�
$v_ordermobile = 13838384581;	// �������ֻ���

$strRequestUrl = $v_post_url.'?v_mid='.$v_mid.'&v_oid='.$v_oid.'&v_amount='.$v_amount.'&v_moneytype='.$v_moneytype
	.'&v_url='.$v_url.'&v_md5info='.$v_md5info.'&remark1='.$remark1.'&remark2='.$remark2;

echo '<html>
<head>
	<title>ת����������֧��ҳ��</title>
</head>
<body onload="document.cbpayment.submit();">
	<form name="cbpayment" action="'.$strRequestUrl.'" method="post">
	</form>
</body>
</html>';
exit;