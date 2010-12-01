<?php
if(!defined('DEDEINC')) exit('Request Error!');
/**
 * ֧�����ӿ���
 */
class Alipay
{
  var $dsql;
  /**
   * ���캯��
   *
   * @access  public
   * @param
   *
   * @return void
   */
  function Alipay()
  {
  	global $dsql;
		$this->dsql = $dsql;
  }

  function __construct()
  {
      $this->Alipay();
  }

  /**
   * ����֧������
   * @param   array   $order      ������Ϣ
   * @param   array   $payment    ֧����ʽ��Ϣ
   */
  function GetCode($order, $payment)
  {
  	global $cfg_basehost,$cfg_soft_lang;
    $charset = $cfg_soft_lang;

    $real_method = $payment['alipay_pay_method'];

    switch ($real_method){
      case '0':
        $service = 'trade_create_by_buyer';
        break;
      case '1':
        $service = 'create_partner_trade_by_buyer';
        break;
      case '2':
        $service = 'create_direct_pay_by_user';
        break;
    }

    $agent = 'C4335994340215837114';

    $parameter = array(
      'agent'             => $agent,
      'service'           => $service,
      'partner'           => $payment['alipay_partner'],
      //'partner'           => ALIPAY_ID,
      '_input_charset'    => $charset,
      'notify_url'        => $cfg_basehost."/plus/carbuyaction.php?dopost=return&code=".$payment['code'],
      'return_url'        => $cfg_basehost."/plus/carbuyaction.php?dopost=return&code=".$payment['code'],
      /* ҵ����� */
      'subject'           => "֧��������:".$order['out_trade_no'],
      'out_trade_no'      => $order['out_trade_no'],
      'price'             => $order['price'],
      'quantity'          => 1,
      'payment_type'      => 1,
      /* �������� */
      'logistics_type'    => 'EXPRESS',
      'logistics_fee'     => 0,
      'logistics_payment' => 'BUYER_PAY_AFTER_RECEIVE',
      /* ����˫����Ϣ */
      'seller_email'      => $payment['alipay_account']
    );

    ksort($parameter);
    reset($parameter);
    
    $param = '';
    $sign  = '';

    foreach ($parameter AS $key => $val)
    {
      $param .= "$key=" .urlencode($val). "&";
      $sign  .= "$key=$val&";
    }

    $param = substr($param, 0, -1);
    $sign  = substr($sign, 0, -1). $payment['alipay_key'];
  
    $button = '<div style="text-align:center"><input type="button" onclick="window.open(\'https://www.alipay.com/cooperate/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5\')" value="����ʹ��alipay֧����֧��"/></div>';

    /* ��չ��ﳵ */
		require_once DEDEINC.'/shopcar.class.php';
  	$cart 	= new MemberShops();
    $cart->clearItem();
		$cart->MakeOrders();
    return $button;
  }

  /**
   * ��Ӧ����
   */
  function respond()
  {

    if (!empty($_POST))
    {
      foreach($_POST as $key => $data)
      {
          $_GET[$key] = $data;
      }
    }
    
    /* ���������ļ� */
    require_once DEDEDATA.'/payment/'.$_GET['code'].'.php';
     
    /* ȡ�ö����� */
    $order_sn = trim($_GET['out_trade_no']);
    
    /*�ж϶�������*/
    if(preg_match ("/S-P[0-9]+RN[0-9]/",$order_sn)) {
	    //���֧������Ƿ����
			$row = $this->dsql->GetOne("SELECT * FROM #@__shops_orders WHERE oid = '{$order_sn}'");
			if ($row['priceCount'] != $_GET['total_fee'])
			{
			    return $msg = "֧��ʧ�ܣ�֧���������Ʒ�ܼ۲����!";
			}
			$ordertype="goods";
		}elseif(preg_match ("/M[0-9]+T[0-9]+RN[0-9]/",$order_sn)){
			$row = $this->dsql->GetOne("SELECT * FROM #@__member_operation WHERE buyid = '{$order_sn}'");
			
			//��ȡ������Ϣ����鶩������Ч��
			if(!is_array($row)||$row['sta']==2) return $msg = "���Ķ����Ѿ������벻Ҫ�ظ��ύ!";
		  elseif($row['money'] != $_GET['total_fee']) return $msg = "֧��ʧ�ܣ�֧���������Ʒ�ܼ۲����!";
		  
			$ordertype = "member";
			$product =	$row['product'];
			$pname= $row['pname'];
			$pid=$row['pid'];
			
		}else{	
			return $msg = "֧��ʧ�ܣ����Ķ�����������!";
		}

    /* �������ǩ���Ƿ���ȷ */
    ksort($_GET);
    reset($_GET);
    
    $sign = '';
    foreach ($_GET AS $key=>$val)
    {
      if ($key != 'sign' && $key != 'sign_type' && $key != 'code' && $key != 'dopost')
      {
      	$sign .= "$key=$val&";
      }
    }
    
    $sign = substr($sign, 0, -1).$payment['alipay_key'];
    
    if (md5($sign) != $_GET['sign'])
    {
    	return 	$msg = "֧��ʧ��!";
    }
    
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS' || $_GET['trade_status'] == 'TRADE_SUCCESS')
    {
    	if($ordertype=="goods"){ 
    	  if($this->success_db($order_sn))  return $msg = "֧���ɹ�!<br> <a href='/'>������ҳ</a> <a href='/member'>��Ա����</a>";
     		else  return $msg = "֧��ʧ��!<br> <a href='/'>������ҳ</a> <a href='/member'>��Ա����</a>";
     	}elseif($ordertype=="member") {
      	if($this->success_mem($order_sn,$pname,$product,$pid))  return $msg = "֧���ɹ�!<br> <a href='/'>������ҳ</a> <a href='/member'>��Ա����</a>";
     		else  return $msg = "֧��ʧ��!<br> <a href='/'>������ҳ</a> <a href='/member'>��Ա����</a>";
     	}
    }else{
    	$this->log_result ("verify_failed");
      return 	$msg = "֧��ʧ��!<br> <a href='/'>������ҳ</a> <a href='/member'>��Ա����</a>";
    }
  }
  
  /*������Ʒ����*/
  function success_db($order_sn){
  	require_once DEDEINC.'/memberlogin.class.php';
  	$cfg_ml = new MemberLogin();
		$cfg_ml->PutLoginInfo($cfg_ml->M_ID);
		
		/* �ı䶩��״̬_֧���ɹ� */
		$sql = "UPDATE `#@__shops_orders` SET `state`='1' WHERE `oid`='$order_sn' AND `userid`='".$cfg_ml->M_ID."'";
		if($this->dsql->ExecuteNoneQuery($sql)){
			$this->dsql->Close();
			$this->log_result("verify_success,������:".$order_sn); //����֤��������ļ�
			return 	true;
		}else{
			$this->dsql->Close();
			$this->log_result ("verify_failed,������:".$order_sn);//����֤��������ļ�
		  return 	false;
		}
  }
  
   /*����㿨����Ա����*/
  function success_mem($order_sn,$pname,$product,$pid){
  	require_once DEDEINC.'/memberlogin.class.php';
  	$cfg_ml = new MemberLogin();
		$cfg_ml->PutLoginInfo($cfg_ml->M_ID);
    
    //���½���״̬Ϊ�Ѹ���
		$sql = "UPDATE `#@__member_operation` SET `sta`='1' WHERE `buyid`='$order_sn' AND `mid`='".$cfg_ml->M_ID."'";
		$this->dsql->ExecuteNoneQuery($sql);

		/* �ı�㿨����״̬_֧���ɹ� */
		if($product=="card"){
			$row = $this->dsql->GetOne("Select cardid From #@__moneycard_record where ctid='$pid' And isexp='0' ");
			
			//����Ҳ���ĳ�����͵Ŀ���ֱ��Ϊ�û����ӽ��
			if(!is_array($row))
			{
				$nrow = $this->dsql->GetOne("SELECT num FROM #@__moneycard_type WHERE pname = '{$pname}'");
				$dnum = $nrow['num'];
				$sql1 = "UPDATE `#@__member` SET `money`=money+'{$nrow['num']}' WHERE `mid`='".$cfg_ml->M_ID."'";
				$oldinf="ֱ�ӳ�ֵ��".$nrow['num']."��ҵ��ʺţ�";
			}else{
				$cardid = $row['cardid'];
				$sql1=" Update #@__moneycard_record set uid='".$cfg_ml->M_ID."',isexp='1',utime='".time()."' where cardid='$cardid' ";
				$oldinf="��ֵ���룺".$cardid;
			}
			//���½���״̬Ϊ�ѹر�
			$sql2=" Update #@__member_operation set sta=2,oldinfo='$oldinf' where buyid='$order_sn'";
			if($this->dsql->ExecuteNoneQuery($sql1) && $this->dsql->ExecuteNoneQuery($sql2)){
		    $this->dsql->Close();
		    $this->log_result("verify_success,������:".$order_sn); //����֤��������ļ�
			  return true;
			}else{
				$this->dsql->Close();
				$this->log_result ("verify_failed,������:".$order_sn);//����֤��������ļ�
			  return false;
			}
			
		/* �ı��Ա����״̬_֧���ɹ� */
		}elseif($product=="member"){
			$row = $this->dsql->GetOne("Select rank,exptime From #@__member_type where aid='$pid' ");
			$rank = $row['rank'];
			$exptime = $row['exptime'];
			/*����ԭ������ʣ�������*/
			$rs = $this->dsql->GetOne("Select uptime,exptime From #@__member where mid='".$cfg_ml->M_ID."'");
			if($rs['uptime']!=0 && $rs['exptime']!=0 ) {
				$nowtime = time();
  			$mhasDay = $rs['exptime'] - ceil(($nowtime - $rs['uptime'])/3600/24) + 1;
  			$mhasDay=($mhasDay>0)? $mhasDay : 0;
			}
			$sql1 =  " Update #@__member set rank='$rank',exptime='$exptime'+'$mhasDay',uptime='".time()."' where mid='".$cfg_ml->M_ID."'";
			//���½���״̬Ϊ�ѹر�
			$sql2=" Update #@__member_operation set sta='2',oldinfo='��Ա�����ɹ�!' where buyid='$order_sn' ";
			if($this->dsql->ExecuteNoneQuery($sql1) && $this->dsql->ExecuteNoneQuery($sql2)){
		    $this->dsql->Close();
		    $this->log_result("verify_success,������:".$order_sn); //����֤��������ļ�
			  return true;
			}else{
				$this->dsql->Close();
				$this->log_result ("verify_failed,������:".$order_sn);//����֤��������ļ�
			  return false;
			}
		}	
  }
  
  function  log_result($word) {
  	global $cfg_cmspath;
		$fp = fopen(dirname(__FILE__)."/../../data/payment/log.txt","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,$word.",ִ������:".strftime("%Y-%m-%d %H:%I:%S",time())."\r\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
}
?>