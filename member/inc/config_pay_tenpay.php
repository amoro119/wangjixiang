<?php 
//�Ƹ�ͨ ����֧���ӿ�
/*�����滻Ϊ����ʵ���̻���*/
$strSpid    = $payment_userid[0];
/*strSpkey��32λ�̻���Կ, ���滻Ϊ����ʵ����Կ*/
$strSpkey   = $payment_key[0];
/*��������:	
        0		�Ƹ�ͨ
  		1001	��������   
  		1002	�й���������  
  		1003	�й���������  
  		1004	�Ϻ��ֶ���չ����   
  		1005	�й�ũҵ����  
  		1006	�й���������  
  		1008	���ڷ�չ����   
  		1009	��ҵ����   */
if(!isset($BankType)) $BankType = 0;
$BankType = ereg_replace("[^0-9]","",$BankType);
if($BankType < 1) $BankType = 0;
$strBankType= $BankType;
$strCmdNo   = "1";
$strBillDate= date('Ymd');
/*��Ʒ����*/
if(!isset($pname)) $pname = '������';
$strDesc    = $pname;
/*�û�QQ����, ������Ϊ�մ�*/
$strBuyerId = "";
/*�̻���*/
$strSaler   = $payment_userid[0];
//֧��������
if($payment_exp[0] < 0) $payment_exp[0] = 0;
$piice_ex = $price*$payment_exp[0];
$price 		= $price+$piice_ex;
//֧�����
$strTotalFee = $price*100;
if( $strTotalFee < 1){
	$dsql->Close();
	exit('����');
}
$strSpBillNo = $buyid;;
/*��Ҫ: ���׵���
	  ���׵���(28λ): �̻���(10λ) + ����(8λ) + ��ˮ��(10λ), ���밴�˸�ʽ����, �Ҳ����ظ�
	  ���sp_billno����10λ, ���ȡ���е���ˮ�Ų��ּӵ�transaction_id��(����10λ��0)
	  ���sp_billno����10λ, ����0, �ӵ�transaction_id��*/
$strTransactionId = $strSpid . $strBillDate . time();
/*��������: 1 �C RMB(�����) 2 - USD(��Ԫ) 3 - HKD(�۱�)*/
$strFeeType  = "1";
/*�Ƹ�ͨ�ص�ҳ���ַ, �Ƽ�ʹ��ip��ַ�ķ�ʽ(�255���ַ�)*/
$strRetUrl  = $cfg_basehost."/member/paycenter/tenpay/notify_handler.php";
/*�̻�˽������, ����ص�ҳ��ʱԭ������*/
$strAttach  = "my_magic_string";
/*����MD5ǩ��*/
$strSignText = "cmdno=" . $strCmdNo . "&date=" . $strBillDate . "&bargainor_id=" . $strSaler .
	      "&transaction_id=" . $strTransactionId . "&sp_billno=" . $strSpBillNo .        
	      "&total_fee=" . $strTotalFee . "&fee_type=" . $strFeeType . "&return_url=" . $strRetUrl .
	      "&attach=" . $strAttach . "&key=" . $strSpkey;
$strSign = strtoupper(md5($strSignText));

/*����֧����*/
$strRequest = "cmdno=" . $strCmdNo . "&date=" . $strBillDate . "&bargainor_id=" . $strSaler .        
"&transaction_id=" . $strTransactionId . "&sp_billno=" . $strSpBillNo .        
"&total_fee=" . $strTotalFee . "&fee_type=" . $strFeeType . "&return_url=" . $strRetUrl .        
"&attach=" . $strAttach . "&bank_type=" . $strBankType . "&desc=" . $strDesc .        
"&purchaser_id=" . $strBuyerId .        
"&sign=" . $strSign ;
$strRequestUrl = "https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi?".$strRequest;


if($cfg_soft_lang == 'utf-8')
{
	$strRequestUrl = utf82gb($strRequestUrl);	
	echo '<html>
	<head>
		<title>ת���Ƹ�֧ͨ��ҳ��</title>
	</head>
	<body onload="document.tenpay.submit();">
		<form name="tenpay" action="paycenter/tenpay/tenpay_gbk_page.php?strReUrl='.urlencode($strRequestUrl).'" method="post">
		</form>
	</body>
	</html>';	
}else{
	echo '<html>
	<head>
		<title>ת���Ƹ�֧ͨ��ҳ��</title>
	</head>
	<body onload="document.tenpay.submit();">
		<form name="tenpay" action="'.$strRequestUrl.'" method="post">
		</form>
	</body>
	</html>';
}
exit;