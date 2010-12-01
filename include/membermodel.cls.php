<?php
if(!defined('DEDEINC'))
{
	exit('forbidden');
}
require_once DEDEINC.'/dedetag.class.php';
require_once DEDEINC.'/customfields.func.php';
require_once DEDEINC.'/enums.func.php';

class membermodel
{
	var $modid;
	var $db;
	var $info;
	var $name;
	var $table;
	var $public;
	var $egroups;
	var $listTemplate;
	var $viewTemplate;
	var $postTemplate;

  //����PHP4�汾
	function membermodel($modtype){
		$this->__construct($modtype);
	}
	
	//��������
	function __construct($modtype){
		$this->name = $modtype;
		$this->db = $GLOBALS['dsql'];
		$query = "SELECT * FROM #@__member_model WHERE name='{$modtype}'";
		$diyinfo = $this->db->getone($query);
		if(!is_array($diyinfo))
		{
			showMsg('��������ȷ���û�Աģ�Ͳ�����','javascript:;');
			exit();
		}
    $etypes = array();
    $egroups = array();
		$this->db->Execute('me','SELECT * FROM `#@__stepselect` ORDER BY id desc');
    while($arr = $this->db->GetArray())
    {
       $etypes[] = $arr;
       $egroups[$arr['egroup']] = $arr['itemname'];
    }
    $this->egroups = $egroups;
		$this->modid = $diyinfo['id'];
		$this->table = $diyinfo['table'];
		$this->description = $diyinfo['description'];
		$this->state = $diyinfo['state'];
		$this->issystem = $diyinfo['issystem'];
		$this->info = $diyinfo['info'];
	}//end func __construct()

  //��ȡ�û����ݱ�
	function getForm($type = 'post', $value = '', $admintype='membermodel2')
	{
		global $cfg_cookie_encode;
		$dtp = new DedeTagParse();
		$dtp->SetNameSpace("field","<",">");
		$dtp->LoadSource($this->info);
		$formstring = '';
		$formfields = '';
		$func = $type == 'post' ? 'GetFormItem' : 'GetFormItemValue';

		if(is_array($dtp->CTags))
		{
			foreach($dtp->CTags as $tagid=>$tag)
			{
				if($tag->GetAtt('autofield'))
				{
	        if($tag->GetAtt('state') == 1)
	        {
	        //������ø��ֶ�
						if($type == 'post')
						{
							//��һЩ�ֶν������⴦��
	
								if($tag->GetName() == 'onlynet')
								{
									$formstring .= '<li><span>��ϵ��ʽ���ƣ�</span><div class="lform">
		            <input name="onlynet" type="radio" id="onlynet" value="2" checked="checked" />
		            ������������ϵ��ʽ
		            <input name="onlynet" type="radio" id="onlynet" value="1" />
		            �������绰����ϸ��ַ
		            <input name="onlynet" type="radio" id="onlynet" value="0"  />
		            ����������ϵ��ʽ</div></li>';
								} else if ($tag->GetName() == 'place' || $tag->GetName() == 'oldplace'){
									$formtitle = ($tag->GetName() == 'place')? 'Ŀǰ���ڵ�' : '�������ڵ�';
									$formstring .='<li><div class="lform">' . GetEnumsForm('nativeplace',0,$tag->GetName()).'</div><span>'.$formtitle.'��</span></li>';
								} else if (array_key_exists($tag->GetName(),$this->egroups)){
									//������ģ�ͽ������⴦��
									$formstring .='<li><div class="lform">'. GetEnumsForm($tag->GetName(),0,$tag->GetName()).'</div><span>'.$this->egroups[$tag->GetName()].'��</span></li>';
								} else if ($tag->GetAtt('type') == 'checkbox'){
									//��checkboxģ�ͽ������⴦��
									$formstring .=$func($tag,$admintype);
								} else {
								  $formstring .= $func($tag,$admintype);
							  }
						}else{
							 if($tag->GetName() == 'onlynet')
								{
									$formstring .= '<p style="display:none"><label>��ϵ��ʽ���ƣ�</label>
		            <input name="onlynet" type="radio" id="onlynet" value="2" checked="checked" />
		            ������������ϵ��ʽ
		            <input name="onlynet" type="radio" id="onlynet" value="1" />
		            �������绰����ϸ��ַ
		            <input name="onlynet" type="radio" id="onlynet" value="0"  />
		            ����������ϵ��ʽ</p>';
								} else if ($tag->GetName() == 'place' || $tag->GetName() == 'oldplace'){
									$formtitle = ($tag->GetName() == 'place')? 'Ŀǰ���ڵ�' : '�������ڵ�';
									$formstring .='<p><label>'.$formtitle.'��</label>' . GetEnumsForm('nativeplace',$value[$tag->GetName()],$tag->GetName()).'</p>';
								} else if ($tag->GetName() == 'birthday'){
									$formstring .='<p><label>'.$tag->GetAtt('itemname').'��</label><input type="text" class="intxt" style="width: 100px;" id="birthday" value="'.$value[$tag->GetName()].'" name="birthday"></p>';
								} else if (array_key_exists($tag->GetName(),$this->egroups)){
									//������ģ�ͽ������⴦��
									$formstring .='<p><label>'.$this->egroups[$tag->GetName()].'��</label> '. GetEnumsForm($tag->GetName(),$value[$tag->GetName()],$tag->GetName()).'</p>';
								} else if ($tag->GetAtt('type') == 'checkbox'){
									//��checkboxģ�ͽ������⴦��
									$formstring .=$func($tag,htmlspecialchars($value[$tag->GetName()],ENT_QUOTES),$admintype);
								} else {
								  $formstring .= $func($tag,htmlspecialchars($value[$tag->GetName()],ENT_QUOTES),$admintype);
								  //echo $formstring;
							  }						
						}
						$formfields .= $formfields == '' ? $tag->GetName().','.$tag->GetAtt('type') : ';'.$tag->GetName().','.$tag->GetAtt('type');
	        }
				}
			}
		}
		$formstring .= "<input type=\"hidden\" name=\"dede_fields\" value=\"".$formfields."\" />\n";
		$formstring .= "<input type=\"hidden\" name=\"dede_fieldshash\" value=\"".md5($formfields.$cfg_cookie_encode)."\" />";
		return $formstring;
	}//end func getForm

	function getFieldList()
	{
		$dtp = new DedeTagParse();
		$dtp->SetNameSpace("field","<",">");
		$dtp->LoadSource($this->info);
		$fields = array();
		if(is_array($dtp->CTags))
		{
			foreach($dtp->CTags as $tagid=>$tag)
			{
				$fields[$tag->GetName()] = array($tag->GetAtt('itemname'), $tag->GetAtt('type'));
			}
		}
		return $fields;
	}

}

?>