<?php
require_once(dirname(__FILE__)."/../config.php");


$maparray = array(1=>'�ĵ����',2=>'ϵͳ����',3=>'���븨������',4=>'��վ���²���',5=>'��Ա���',6=>'����ģ����');

//����ɷ���Ƶ��
$addset = '';
$dsql->SetQuery("Select id,typename,addcon,mancon From `#@__channeltype` where id<>-1 And isshow=1 order by id asc");
$dsql->Execute();
while($row = $dsql->GetObject())
{
	$addset .= "  <m:item name='{$row->typename}' ischannel='1' link='{$row->mancon}?channelid={$row->id}' linkadd='{$row->addcon}?channelid={$row->id}' channelid='{$row->id}' rank='' target='main' />\r\n";
}
//////////////////////////
$menusMain = "
-----------------------------------------------

<m:top mapitem='1' item='1_' name='���ò���' display='block'>
  <m:item name='��վ��Ŀ����' link='catalog_main.php' ischannel='1' addalt='������Ŀ' linkadd='catalog_add.php?listtype=all' rank='t_List,t_AccList' target='main' />
  <m:item name='���е����б�' link='content_list.php' rank='a_List,a_AccList' target='main' />
  <m:item name='����˵ĵ���' link='content_list.php?arcrank=-1' rank='a_Check,a_AccCheck' target='main' />
  <m:item name='�ҷ������ĵ�' link='content_list.php?mid=".$cuserLogin->getUserID()."' rank='a_List,a_AccList,a_MyList' target='main' />
  <m:item name='���۹���' link='feedback_main.php' rank='sys_Feedback' target='main' />
  <m:item name='���ݻ���վ' link='recycling.php' ischannel='1' addalt='��ջ���վ' addico='img/gtk-del.png' linkadd='archives_do.php?dopost=clear&aid=no' rank='a_List' target='main' />
</m:top>

<m:top mapitem='1' item='1_' name='���ݹ���' display='block'>
  $addset
  <m:item name='ר�����' ischannel='1' link='content_s_list.php' linkadd='spec_add.php' channelid='-1' rank='spec_New' target='main' />
</m:top>

<m:top mapitem='1' item='1_' name='Ƶ��ģ��' display='block' rank='t_List,t_AccList,c_List,temp_One'>
  <m:item name='����ģ�͹���' link='mychannel_main.php' rank='c_List' target='main' />
  <m:item name='��ҳ�ĵ�����' link='templets_one.php' rank='temp_One' target='main'/>
  <m:item name='����������' link='stepselect_main.php' rank='c_Stepseclect' target='main' />
  <m:item name='�����б����' link='freelist_main.php' rank='c_List' target='main' />
  <m:item name='�Զ����' link='diy_main.php' rank='c_List' target='main' />
</m:top>

<m:top mapitem='3' item='3_' name='�ɼ�����' display='none' rank='co_NewRule,co_ListNote,co_ViewNote,co_Switch,co_GetOut'>
  <m:item name='�ɼ��ڵ����' link='co_main.php' rank='co_ListNote' target='main' />
  <m:item name='��ʱ���ݹ���' link='co_url.php' rank='co_ViewNote' target='main' />
  <m:item name='����ɼ�����' link='co_get_corule.php' rank='co_GetOut' target='main'/>
  <m:item name='��زɼ�ģʽ' link='co_gather_start.php' rank='co_GetOut' target='main'/>
  <m:item name='�ɼ�δ��������' link='co_do.php?dopost=coall' rank='co_GetOut' target='main'/>
</m:top>

<m:top mapitem='3' item='1_3_3' name='����ά��' display='block'>
  <m:item name='����ϵͳ����' link='sys_cache_up.php' rank='sys_ArcBatch' target='main' />
  <m:item name='�ĵ�����ά��' link='content_batch_up.php' rank='sys_ArcBatch' target='main' />
  <m:item name='�����ؼ���ά��' link='search_keywords_main.php' rank='sys_Keyword' target='main' />
  <m:item name='�ĵ��ؼ���ά��' link='article_keywords_main.php' rank='sys_Keyword' target='main' />
  <m:item name='�Զ�ժҪ|��ҳ' link='article_description_main.php' rank='sys_Keyword' target='main' />
  <m:item name='TAG��ǩ����' link='tags_main.php' rank='sys_Keyword' target='main' />
  <m:item name='���ݿ������滻' link='sys_data_replace.php' rank='sys_ArcBatch' target='main' />
</m:top>

<m:top mapitem='4' item='5_' name='�Զ�����' notshowall='1'  display='block' rank='sys_MakeHtml'>
  <m:item name='һ��������վ' link='makehtml_all.php' rank='sys_MakeHtml' target='main' />
  <m:item name='����ϵͳ����' link='sys_cache_up.php' rank='sys_ArcBatch' target='main' />
</m:top>

<m:top mapitem='4' item='5_' name='HTML����' notshowall='1' display='none' rank='sys_MakeHtml'>
  <m:item name='������ҳHTML' link='makehtml_homepage.php' rank='sys_MakeHtml' target='main' />
  <m:item name='������ĿHTML' link='makehtml_list.php' rank='sys_MakeHtml' target='main' />
  <m:item name='�����ĵ�HTML' link='makehtml_archives.php' rank='sys_MakeHtml' target='main' />
  <m:item name='������վ��ͼ' link='makehtml_map_guide.php' rank='sys_MakeHtml' target='main' />
  <m:item name='����RSS�ļ�' link='makehtml_rss.php' rank='sys_MakeHtml' target='main' />
  <m:item name='��ȡJS�ļ�' link='makehtml_js.php' rank='sys_MakeHtml' target='main' />
  <m:item name='����ר��HTML' link='makehtml_spec.php' rank='sys_MakeHtml' target='main' />
</m:top>

<m:top mapitem='3' item='1_6_' name='��������' display='none' rank='sys_Upload,sys_MyUpload,plus_�ļ�������'>
  <m:item name='�ϴ����ļ�' link='media_add.php' rank='' target='main' />
  <m:item name='�������ݹ���' link='media_main.php' rank='sys_Upload,sys_MyUpload' target='main' />
  <m:item name='�ļ�ʽ������' link='media_main.php?dopost=filemanager' rank='plus_�ļ�������' target='main' />
</m:top>

<m:top mapitem='5' item='6_' name='��Ա����' display='none' rank='member_List,member_Type'>
<m:item name='��Ա������' link='member_mtype.php' rank='member_Type' target='main' />
  <m:item name='ע���Ա�б�' link='member_main.php' rank='member_List' target='main' />
  <m:item name='��Ա��������' link='member_rank.php' rank='member_Type' target='main' />
  <m:item name='����ͷ������' link='member_scores.php' rank='member_Type' target='main' />
  <m:item name='��Ա���Ź���' link='member_pm.php' rank='member_Type' target='main' />
</m:top>

<m:top mapitem='2' item='10_' name='ϵͳ����' display='none' rank='sys_User,sys_Group,sys_Edit,sys_Log,sys_Data'>
  <m:item name='ϵͳ��������' link='sys_info.php' rank='sys_Edit' target='main' />
  <m:item name='ϵͳ�û�����' link='sys_admin_user.php' rank='sys_User' target='main' />
  <m:item name='�û����趨' link='sys_group.php' rank='sys_Group' target='main' />
  <m:item name='ϵͳ��־����' link='log_list.php' rank='sys_Log' target='main' />
  <m:item name='ͼƬˮӡ����' link='sys_info_mark.php' rank='sys_Edit' target='main' />
  <m:item name='�Զ����ĵ�����' link='content_att.php' rank='sys_Att' target='main' />
  <m:item name='���Ƶ������' link='soft_config.php' rank='sys_SoftConfig' target='main' />
  <m:item name='���ɼ�������' link='article_string_mix.php' rank='sys_StringMix' target='main' />
  <m:item name='��Դ����' link='article_source_edit.php' rank='sys_Source' target='main' />
  <m:item name='���߹���' link='article_writer_edit.php' rank='sys_Writer' target='main' />
  <m:item name='���ģ������' link='article_template_rand.php' rank='sys_StringMix' target='main' />
  <m:item name='�ƻ��������' link='sys_task.php' rank='sys_Task' target='main' />
  <m:item name='���ݿⱸ��/��ԭ' link='sys_data.php' rank='sys_Data' target='main' />
  <m:item name='SQL�����й���' link='sys_sql_query.php' rank='sys_Data' target='main' />
  <m:item name='�ļ�У��[S]' link='sys_verifies.php' rank='sys_verify' target='main' />
  <m:item name='����ɨ��[S]' link='sys_safetest.php' rank='sys_verify' target='main' />
</m:top>

<m:top mapitem='5' item='10_6_' name='֧������' display='none' rank='sys_Data'>
  <m:item name='�㿨��Ʒ����' link='cards_type.php' rank='sys_Data' target='main' />
  <m:item name='�㿨��Ʒ����' link='cards_manage.php' rank='sys_Data' target='main' />
  <m:item name='��Ա��Ʒ����' link='member_type.php' rank='sys_Data' target='main' />
  <m:item name='��Ա���Ѽ�¼' link='member_operations.php' rank='sys_Data' target='main' />
  <m:item name='�̵궩����¼' link='shops_operations.php' rank='sys_Data' target='main' />
  <m:item name='֧���ӿ�����' link='sys_info_pay.php' rank='sys_Data' target='main' />
  <m:item name='�����ʽ����' link='shops_delivery.php' rank='sys_Data' target='main' />
  <m:item name='����˺�����' link='shops_bank.php' rank='sys_Data' target='main' />
</m:top>

<m:top mapitem='2' item='10_7_' name='ģ�����' display='none' rank='temp_One,temp_Other,temp_MyTag,temp_test,temp_All'>
  <m:item name='Ĭ��ģ�����' link='templets_main.php' rank='temp_All' target='main'/>
  <m:item name='��ǩԴ�����' link='templets_tagsource.php' rank='temp_All' target='main'/>
  <m:item name='�Զ������' link='mytag_main.php' rank='temp_MyTag' target='main'/>
  <m:item name='���ܱ����' link='mytag_tag_guide.php' rank='temp_Other' target='main'/>
  <m:item name='ȫ�ֱ�ǲ���' link='tag_test.php' rank='temp_Test' target='main'/>
</m:top>

";

//�������˵�
$plusset = '';
$dsql->SetQuery("Select * From `#@__plus` where isshow=1 order by aid asc");
$dsql->Execute();
while($row = $dsql->GetObject()) {
	$plusset .= $row->menustring."\r\n";
}

$menusMain .= "
<m:top mapitem='6' name='ģ�����' c='6,' display='block'>
  <m:item name='ģ�����' link='module_main.php' rank='sys_module' target='main' />
  <m:item name='�ϴ���ģ��' link='module_upload.php' rank='sys_module' target='main' />
  <m:item name='ģ��������' link='module_make.php' rank='sys_module' target='main' />
</m:top>

<m:top mapitem='6' item='7' name='�������' display='block'>
  <m:item name='���������' link='plus_main.php' rank='10' target='main' />
  $plusset
</m:top>
";

$mapstring = '';
$dtp = new DedeTagparse();
$dtp->SetNameSpace('m','<','>');
$dtp->LoadString($menusMain);
foreach($maparray as $k=>$bigname)
{
  $mapstring .= "<dl class='maptop'>\r\n";
  $mapstring .= "<dt class='bigitem'>$bigname</dt>\r\n";
  $mapstring .= "<dd>\r\n";
  foreach($dtp->CTags as $ctag)
  {
  	if($ctag->GetAtt('mapitem')==$k)
  	{
  		$mapstring .= "<dl class='mapitem'>\r\n";
  		$mapstring .= "<dt>".$ctag->GetAtt('name')."</dt>\r\n";
 			$mapstring .= "<dd>\r\n<ul class='item'>\r\n";
 			$dtp2 = new DedeTagParse();
			$dtp2->SetNameSpace('m','<','>');
			$dtp2->LoadSource($ctag->InnerText);
			foreach($dtp2->CTags as $j=>$ctag2)
			{
				$mapstring .= "<li><a href='".$ctag2->GetAtt('link')."' target='".$ctag2->GetAtt('target')."'>".$ctag2->GetAtt('name')."</a></li>\r\n";
			}
 			$mapstring .= "</ul>\r\n</dd>\r\n</dl>\r\n";
  	}
	}
	$mapstring .= "</dd>\r\n</dl>\r\n";
}

?>