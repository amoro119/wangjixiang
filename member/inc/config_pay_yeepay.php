<?php
include_once DEDEMEMBER.'/paycenter/yeepay/yeepay_config.php';

if($payment_exp[4] < 0) $payment_exp[4] = 0;
$piice_ex = $price*$payment_exp[4];

if($piice_ex > 0) $price = $price+$piice_ex;

#	�̼������û�������Ʒ��֧����Ϣ.
##�ױ�֧��ƽ̨ͳһʹ��GBK/GB2312���뷽ʽ,�������õ����ģ���ע��ת��

#	�̻�������,ѡ��.
##����Ϊ""���ύ�Ķ����ű����������˻�������Ψһ;Ϊ""ʱ���ױ�֧�����Զ�����������̻�������.
$p2_Order					= trim($buyid);

#	֧�����,����.
##��λ:Ԫ����ȷ����.
$p3_Amt						= $price;

#	���ױ���,�̶�ֵ"CNY".
$p4_Cur						= "CNY";

#	��Ʒ����
##����֧��ʱ��ʾ���ױ�֧���������Ķ�����Ʒ��Ϣ.
$p5_Pid						= trim($pname);

#	��Ʒ����
$p6_Pcat					= trim($ptype);

#	��Ʒ����
$p7_Pdesc					= '';

#	�̻�����֧���ɹ����ݵĵ�ַ,֧���ɹ����ױ�֧������õ�ַ�������γɹ�֪ͨ.
$p8_Url						= $cfg_basehost.'/member/paycenter/yeepay/callback.php';	

#	�̻���չ��Ϣ
##�̻�����������д1K ���ַ���,֧���ɹ�ʱ��ԭ������.												
$pa_MP						= 'member';

#	Ӧ�����
##Ϊ"1": ��ҪӦ�����;Ϊ"0": ����ҪӦ�����.
$pr_NeedResponse	= 1;

#	���б���
##Ĭ��Ϊ""�����ױ�֧������.��������ʾ�ױ�֧����ҳ�棬ֱ����ת�������С�������֧��������һ��ͨ��֧��ҳ�棬���ֶο����ո�¼:�����б����ò���ֵ.			
$pd_FrpId					= '';
#����ǩ����������ǩ����
$hmac = getReqHmacString($p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse);	
$reqURL_onLineCmd = 'paycenter/yeepay/yeepay_gbk.php';
echo '
<html>
<head>
<title>To YeePay Page</title>
<body onload="document.yeepay.submit();">
<form name="yeepay" action="'.$reqURL_onLineCmd.'" method="post">
<input type="hidden" name="p0_Cmd"					value="'.$p0_Cmd.'">
<input type="hidden" name="p1_MerId"				value="'.$p1_MerId.'">
<input type="hidden" name="p2_Order"				value="'.$p2_Order.'">
<input type="hidden" name="p3_Amt"					value="'.$p3_Amt.'">
<input type="hidden" name="p4_Cur"					value="'.$p4_Cur.'">
<input type="hidden" name="p5_Pid"					value="'.$p5_Pid.'">
<input type="hidden" name="p6_Pcat"					value="'.$p6_Pcat.'">
<input type="hidden" name="p7_Pdesc"				value="'.$p7_Pdesc.'">
<input type="hidden" name="p8_Url"					value="'.$p8_Url.'">
<input type="hidden" name="p9_SAF"					value="'.$p9_SAF.'">
<input type="hidden" name="pa_MP"					value="'.$pa_MP.'">
<input type="hidden" name="pd_FrpId"				value="'.$pd_FrpId.'">
<input type="hidden" name="pr_NeedResponse"	value="'.$pr_NeedResponse.'">
<input type="hidden" name="hmac"					value="'.$hmac.'">
<input type="hidden" name="lang"					value="'.$cfg_soft_lang.'">
<input type="hidden" name="reqURL_onLine"					value="'.$reqURL_onLine.'">
</form>
</body>
</html>';exit;
?>