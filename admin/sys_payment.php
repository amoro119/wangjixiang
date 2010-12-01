<?php
require_once(dirname(__FILE__)."/config.php");
require_once(DEDEINC.'/datalistcp.class.php');
CheckPurview('sys_Data');

$dopost = (empty($dopost))? '' : $dopost;
$pid = (empty($pid))? 0 : preg_replace('/[^0-9]/','',$pid);
/*
�����������ʽ������:
*/
//һ���򵥵�[����<->��]������
/*����ṹӦ��Ϊ:
  array(
    [name]=>array(
    	[title]=>'��ǰ���������',
    	[type]=>'text|select',
    	[description]=>'�����ݵĽ���˵��'
    	[iterm]=>'1:ʹ�ñ�׼˫�ӿ�,ʹ�õ������׽ӿ�', //�������":",��ǰ��Ϊvalueֵ,����Ϊ��ʾ����
    	[value]=>'ʹ�õ������׽ӿ�',
    ),
    ...
  )
  ʹ�÷���:
  �������ĸ�ʽ���뵽������ȥ,Ȼ����н���:
  1.������,����������
  $af = new Array2form($config);
  
  2.����һ����ģ��(��ѡ,�������������Ĭ��)
  $af->SetDefaultTpl($templets); $templets:Ϊһ���ײ�ģ���ļ�
  ��ģ���ʽΪ:
  <p>~title~:~form~<small>~description~</small></p>
  
  3.��ȡ�ض���Ŀ��
  $af->GetIterm('alipay', 1) //1.��ʾ��ȡһ��Ĭ��ģ���µ�������,2.����ȡһ������
  
  4.��ȡ���б�����
  $af->GetAll() //��ȡ�����н����������
  
*/
class Array2form
{
	var $FormArray = array();
	var $ArrFromTPL = '';
	
	function __construct($formarray = array())
	{
		if(count($formarray) > 1)
		{
			$this->FormArray = $formarray;
		  //var_dump($this->FormArray);
			$this->SetDefaultTpl();
		}
	}
	
	//��������,����PHP4
	function Array2form($formarray = array())
	{
		$this->__construct($formarray);
	}
	
	//��ȡһ���ض���Ŀ�ı�
	function GetIterm($itermid = '', $itermtype = 1)
	{
		$reval = $reval_form =  $reval_title = $reval_des = $myformItem = '';
		if(is_array($this->FormArray))
		{
			foreach ($this->FormArray as $key => $val) {
				if($key == $itermid)
				{
					$reval_title = $val['title'];
					$reval_des = $val['description'];
					$reval_form = $this->GetForm($key,$val, $val['type']);
					//����ģ���ǩ�滻
					if($itermtype == 1)
							$reval = preg_replace(array("/~title~/","/~form~/","/~description~/"), 
																array($reval_title, $reval_form, $reval_des), $this->ArrFromTPL);
					else return $reval_form;	
				}
			}
		} else {
			return false;
		}
		return empty($reval)? '' : $reval;	
	}
	
	function GetForm($key,$formarry = array(),$formtype='text')
	{
		switch ($formtype) {
			case 'text':
				//�����ı��༭��
				$valstr=(empty($formarry['value']))? "value=''" : "value='{$formarry['value']}'";
				$reval_form = "<input type='text' name='{$key}' id='{$key}' style='width:300px' class='text'{$valstr}>";
				break;
			case 'select':
				//����ѡ���
				$reval_title = $formarry['title'];
				$items = explode(',',$formarry['iterm']);
				$reval_form = "<select name='{$key}' class='text'>";
						if(is_array($items))
						{
							foreach($items as $v)
							{
								$v = trim($v);
								if($v=='') continue;
								//ͳһ������ð��תΪӢ��
								$v = str_replace("��", ":", $v);
								if(preg_match("/[\:]/",$v)){	
									list($value,$name)=split(':',$v);
									$reval_form.=($formarry['value']==$value)? "<option value='$value' selected>$name</option>\r\n" : "<option value='$value'>$name</option>\r\n";
								}else{
								  $reval_form.=($formarry['value']==$v)? "<option value='$v' selected>$v</option>\r\n" : "<option value='$v'>$v</option>\r\n";
								}
							}
						}
						$reval_form .= "</select>\r\n";
				break;
		}
		return $reval_form;
	}
	
	
	//��ȡ���еı�����
	function GetAll()
	{
		  $reval=empty($reval)? '' : $reval;	
			if(is_array($this->FormArray)){
				foreach ($this->FormArray as $key => $val){
				 $reval .= $this->GetIterm($key);
				}
				return $reval;
			}else{
				return false;	
			}		
	}
	
	//��ȡһ���ض���Ŀ�ı�
	function SetDefaultTpl($tplname = '')
	{
		if(empty($tplname)) 
		{
			$this->ArrFromTPL = '<p>~title~��~form~<small>~description~</small></p>';
		} else {
			if(file_exists($tplname)) $this->ArrFromTPL = file_get_contents($tplname);
		  else $this->ArrFromTPL = $tplname;
		}
	}
	
}
$tplstring = "
            <tr>
              <td height='25' align='center'>~title~��</td>
              <td>~form~ <small>~description~</small></td>
            </tr>
";
//��װ֧���ӿ�
if($dopost=='install')
{
	$row = $dsql->GetOne("SELECT * FROM `#@__payment` WHERE id='$pid'");
	if(is_array($row))
	{
	  $af = new Array2form(unserialize($row['config']));
	  $af->SetDefaultTpl($tplstring);
	  $reval = $af->GetAll();
	};
	include DedeInclude('templets/sys_payment_install.htm');
	exit;
} 
//����֧���ӿ�
else if($dopost=='config')
{
    if($pay_name=="" || $pay_desc=="" || $pay_fee==""){
        ShowMsg("����δ��д����Ŀ��","-1");
		exit();
    }
    $row = $dsql->GetOne("SELECT * FROM `#@__payment` WHERE id='$pid'");
    $config=unserialize($row['config']);
    $payments="'code' => '".$row['code']."',";
    foreach ($config as $key => $v){
    	$config[$key]['value']=${$key};
    	$payments .= "'".$key."' => '".$config[$key]['value']."',";
    }
    $payments=substr($payments, 0, -1);
    $payment="\$payment=array(".$payments.")";
    $configstr = "<"."?php\r\n".$payment."\r\n?".">\r\n";
    if(!empty($payment)){
    	$m_file = DEDEDATA."/payment/".$row['code'].".php";
	   	$fp = fopen($m_file,"w") or die("д���ļ� $safeconfigfile ʧ�ܣ�����Ȩ�ޣ�");
			fwrite($fp,$configstr);
			fclose($fp);
		}
    $config=serialize($config);
    $query="UPDATE `#@__payment` SET name = '$pay_name',fee='$pay_fee',description='$pay_desc',config='$config',enabled='1' WHERE id='$pid'";
    $dsql->ExecuteNoneQuery($query);
    if($pm=='edit') $msg="�����޸ĳɹ�";
    else $msg="��װ�ɹ���";
    ShowMsg($msg,"sys_payment.php");
	  exit();
}

//ɾ��֧���ӿ�
else if($dopost=='uninstall')
{
	$row = $dsql->GetOne("SELECT * FROM `#@__payment` WHERE id='$pid'");
	$config=unserialize($row['config']);
  foreach ($config as $key => $v) $config[$key]['value']="";
  $config=serialize($config);
  $query="UPDATE `#@__payment` SET fee='',config='$config',enabled='0' WHERE id='$pid'";
  $dsql->ExecuteNoneQuery($query);
  //ͬʱ��Ҫɾ����Ӧ�Ļ���
  $m_file = DEDEDATA."/payment/".$row['code'].".php";
  @unlink($m_file);
  ShowMsg("ɾ���ɹ���","sys_payment.php");
  exit();

}

$sql = "SELECT * FROM `#@__payment` ORDER BY rank ASC";

$dlist = new DataListCP();
$dlist->SetTemplet(DEDEADMIN."/templets/sys_payment.htm");
$dlist->SetSource($sql);
$dlist->display();
?>