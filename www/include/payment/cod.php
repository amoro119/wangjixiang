<?php
if(!defined('DEDEINC')) exit('Request Error!');
/**
 * ��������֧���ӿ�
 */

/**
 * �����ӿ���
 */
class Cod
{
  /**
   * ���캯��
   *
   * @access  public
   * @param
   *
   * @return void
   */
  function Cod()
  {
  }

  function __construct()
  {
      $this->Cod();
  }

  /**
   * ��ȡ����
   */
  function GetCode($order)
  {
  	require_once DEDEINC.'/shopcar.class.php';
  	$cart 	= new MemberShops();
  	$cart->clearItem();
		$cart->MakeOrders();
    $button="������ <a href='/'>������ҳ</a> ��ȥ <a href='../member/shops_products.php?oid=".$order."'>�鿴����</a>";
    return $button;
  }

}

?>