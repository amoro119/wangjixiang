<?php
if(!defined('DEDEINC')) exit('Request Error!');
/**
 * ���л��/ת�ʽӿ�
 */

/**
 * ��
 */
class bank
{
	/**
   * ���캯��
   *
   * @access  public
   * @param
   *
   * @return void
   */
  function bank()
  {
  }

  function __construct()
  {
      $this->bank();
  }

  /**
   * �ύ����
   */
  function GetCode($order,$payment)
  {
  	require_once DEDEINC.'/shopcar.class.php';
  	$cart 	= new MemberShops();
  	$cart->clearItem();
		$cart->MakeOrders();
	  if($payment=="member") $button="������ <a href='/'>������ҳ</a> ��ȥ <a href='/member/operation.php'>��Ա����</a>";
    else $button="������ <a href='/'>������ҳ</a> ��ȥ <a href='../member/shops_products.php?oid=".$order."'>�鿴����</a>";
    return $button;
  }
}

?>