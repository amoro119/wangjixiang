<?php
//��ʾ���ﳵ����Ʒ
require_once (dirname(__FILE__) . "/../include/common.inc.php");
define('_PLUS_TPL_', DEDEROOT.'/templets/plus');
require_once(DEDEINC.'/dedetemplate.class.php');
require_once DEDEINC.'/shopcar.class.php';
require_once DEDEINC.'/memberlogin.class.php';
$cart = new MemberShops();

if(isset($dopost) && $dopost=='makeid')
{
	AjaxHead();
	$cart->MakeOrders();
	echo $cart->OrdersId;
	exit;
}
$cfg_ml = new MemberLogin();
//��ù��ﳵ����Ʒ,��������
$Items = $cart->getItems();
if($cart->cartCount() < 1)
{
	ShowMsg("���ﳵ�в������κ���Ʒ��", "javascript:window.close();", false, 5000);
	exit;
}
@sort($Items);

$carts = array(
	'orders_id' => $cart->OrdersId,
	'cart_count' => $cart->cartCount(),
	'price_count' => $cart->priceCount()
);

$dtp = new DedeTemplate();
$dtp->Assign('carts',$carts);
$dtp->LoadTemplate(_PLUS_TPL_.'/car.htm');
$dtp->Display();
exit;
?>