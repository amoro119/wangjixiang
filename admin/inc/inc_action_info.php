<?php
require_once(dirname(__FILE__)."/../config.php");
$cuserLogin = new userLogin();
//��̨���ܲ���������
$actionSearch[0] =array(
    'toptitle' => '����',
    'title'  => '�������',
    'description' =>'վ�㵵�����湦�ܲ���',
    'soniterm' =>  array(
        0  =>  array(
            'title' =>'��վ��Ŀ����',
            'description' =>'վ��������Ŀ����',
            'purview' =>'t_List,t_AccList',
            'linkurl' =>'catalog_main.php'
        ),
        1  =>  array(
            'title' =>'����˵ĵ���',
            'description' =>'��������ģ�ͷ����δ����������б�',
            'purview' =>'a_Check,a_AccCheck',
            'linkurl' =>'content_list.php?arcrank=-1'
        ),
        2  =>  array(
            'title' =>'�ҷ������ĵ�',
            'description' =>'���ڵ�¼�Ĺ���Ա���������������ģ���е��ĵ�',
            'purview' =>'a_List,a_AccList,a_MyList',
            'linkurl' =>'content_list.php?mid='.$cuserLogin->userID
        ),
        3  =>  array(
            'title' =>'���۹���',
            'description' =>'��վ�������۹���',
            'purview' =>'sys_Feedback',
            'linkurl' =>'feedback_main.php'
        ),
        4  =>  array(
            'title' =>'���ݻ���վ',
            'description' =>'�����"ϵͳ��������"��"��������"�п�����"���»���վ(��/��)��������",��̨ɾ�����ĵ��������ڴ˴�',
            'purview' =>'a_List,a_AccList,a_MyList',
            'linkurl' =>'recycling.php'
        )
    )
);
$actionSearch[1] = array(
    'toptitle' => '����', 
    'title' => '���ݹ���',
    'description' => '��վ��Ӧ����ģ�͵��ĵ�����',
    'soniterm' => array(
        0  =>  array(
            'title' =>'ר�����',
            'description' =>'����ר�����ݵĹ���',
            'purview' =>'spec_New',
            'linkurl' =>'content_s_list.php'
        ),
    )
);
$actionSearch[2] = array(
    'toptitle' => '����', 
    'title' => '��������',
    'description' => '�����ϴ��ĸ�������',
    'soniterm' => array(
        0  =>  array(
            'title' =>'�ϴ����ļ� ',
            'description' =>'ͨ��������ϴ�ͼƬ��FLASH����Ƶ/��Ƶ������/�����ȸ��� ',
            'purview' =>'',
            'linkurl' =>'media_add.php'
        ),
        1  =>  array(
            'title' =>'�������ݹ��� ',
            'description' =>'�г������ϴ��ĸ���',
            'purview' =>'sys_Upload,sys_MyUpload',
            'linkurl' =>'media_main.php'
        ),
        2  =>  array(
            'title' =>'�ļ�ʽ������ ',
            'description' =>'Ӧ���ļ������ģʽ���и����Ĺ���',
            'purview' =>'plus_�ļ�������',
            'linkurl' =>'media_main.php?dopost=filemanager'
        ),
    )
);
$actionSearch[3] = array(
    'toptitle' => '����', 
    'title' => 'Ƶ��ģ��',
    'description' => '�����ϴ��ĸ�������',
    'soniterm' => array(
        0  =>  array(
            'title' =>'����ģ�͹��� ',
            'description' =>'���Զ�������Ʒ�������ͼƬ������ͨ���¡�ר�⡢������Ϣ��ģ�;��й���Ҳ���Դ����µ�����ģ��',
            'purview' =>'c_List',
            'linkurl' =>'mychannel_main.php'
        ),
        1  =>  array(
            'title' =>'��ҳ�ĵ����� ',
            'description' =>'�����͹���ҳ��',
            'purview' =>'temp_One',
            'linkurl' =>'templets_one.php'
        ),
        2  =>  array(
            'title' =>'���������� ',
            'description' =>'�����͹������е�����',
            'purview' =>'c_Stepseclect',
            'linkurl' =>'stepselect_main.php?dopost=filemanager'
        ),
        3  =>  array(
            'title' =>'�����б���� ',
            'description' =>'������ͬ���б���ʽ',
            'purview' =>'c_List',
            'linkurl' =>'freelist_main.php'
        ),
        4  =>  array(
            'title' =>'�Զ���� ',
            'description' =>'�����͹����Զ����',
            'purview' =>'c_List',
            'linkurl' =>'diy_main.php'
        ),
    )
);
$actionSearch[4] = array(
    'toptitle' => '����', 
    'title' => '����ά��',
    'description' => '��һЩ��������������ɾ������ӵȵ�',
    'soniterm' => array(
        0  =>  array(
            'title' =>'����ϵͳ���� ',
            'description' =>'������Ŀ���桢����ö�ٻ��� ������arclist���û��� ��������ڻ�Ա������ʷ ��ɾ�����ڶ���',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'sys_cache_up.php'
        ),
        1  =>  array(
            'title' =>'�ĵ�����ά�� ',
            'description' =>'�����Ķ�ĳ����Ŀ����ȫ����Ŀ�����ݽ�������ĵ�������HTML���ƶ��ĵ���ɾ���ĵ�',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'content_batch_up.php'
        ),
        2  =>  array(
            'title' =>'�����ؼ���ά�� ',
            'description' =>'���Ѿ����е��������ѵĹؼ��ʽ��й���',
            'purview' =>'sys_Keyword',
            'linkurl' =>'search_keywords_main.php?dopost=filemanager'
        ),
        3  =>  array(
            'title' =>'�ĵ��ؼ���ά�� ',
            'description' =>'���ĵ��еĹؼ��ʽ���������ά��',
            'purview' =>'sys_Keyword',
            'linkurl' =>'article_keywords_main.php'
        ),
        4  =>  array(
            'title' =>'�ظ��ĵ���� ',
            'description' =>'���Զ���վ�г��ֵ��ظ�������ĵ����д���',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'article_test_same.php'
        ),
        5  =>  array(
            'title' =>'�Զ�ժҪ|��ҳ ',
            'description' =>'�����Զ�������ϵͳû����дժҪ���ĵ���ժҪ��Ϣ�����û��ҳ���ĵ����Զ���ҳ��ʶ',
            'purview' =>'sys_Keyword',
            'linkurl' =>'article_description_main.php'
        ),
        6  =>  array(
            'title' =>'TAG��ǩ���� ',
            'description' =>'��������վ��tag����������ά��',
            'purview' =>'sys_Keyword',
            'linkurl' =>'tags_main.php'
        ),
        7  =>  array(
            'title' =>'���ݿ������滻 ',
            'description' =>'���Զ����ݿ��е�ĳ�ű��е��ֶν������ݵ������滻',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'sys_data_replace.php'
        ),
    )
);
$actionSearch[5] = array(
    'toptitle' => '��Ա', 
    'title' => '��Ա����',
    'description' => 'ע���Ա�����ֵ����ù���',
    'soniterm' => array(
        0  =>  array(
            'title' =>'ע���Ա�б�',
            'description' =>'����ע���Ա�Ĺ�����,���а����޸�,ɾ��,�鿴��Ա�ĵ��Լ���������Ա�Ȳ���',
            'purview' =>'member_List',
            'linkurl' =>'member_main.php'
        ),
        1  =>  array(
            'title' =>'��Ա��������',
            'description' =>'���û�Ա�ļ���,����ͨ����Ʋ�ͬ��Ա�ķ���Ȩ�����Ի�Ա�������һ����չ',
            'purview' =>'member_Type',
            'linkurl' =>'member_rank.php'
        ),
        2  =>  array(
            'title' =>'����ͷ������',
            'description' =>'��Ա���ֵȼ�����,���ݻ�Ա����ֶԻ�Ա����ͷ�λ���',
            'purview' =>'member_Type',
            'linkurl' =>'member_scores.php'
        ),
        3  =>  array(
            'title' =>'��Աģ�͹���',
            'description' =>'Ϊ��Ա�ƶ���ͬ�Ļ�Ա����,Ĭ���и���,��ҵ�����û�����,����ͬʱ����Ϊ�û�ģ����Ӳ�ͬ���ֶ�',
            'purview' =>'member_Type',
            'linkurl' =>'member_model_main.php'
        ),
        4  =>  array(
            'title' =>'��Ա���Ź���',
            'description' =>'��Ա֮�䷢�͵Ķ���Ϣ����,���а���Ⱥ������Ϣ�ͶԵ�����Ա���Ͷ���Ϣ����',
            'purview' =>'member_Type',
            'linkurl' =>'member_pm.php'
        ),
        5  =>  array(
            'title' =>'��Ա���Թ���',
            'description' =>'��Ա�ռ����ԵĹ�����Ŀ,���Զ����Խ�������ɾ���Ȳ���',
            'purview' =>'member_Type',
            'linkurl' =>'member_guestbook.php'
        ),
        6  =>  array(
            'title' =>'��Ա��̬����',
            'description' =>'��Ա�������,ɾ������,�������ݲ����Ļ�Ա��̬,�ڻ�Ա������ҳ������ʾ,������Խ��������������',
            'purview' =>'member_Type',
            'linkurl' =>'member_info_main.php?type=feed'
        ),
        7  =>  array(
            'title' =>'��Ա�������',
            'description' =>'��Ա���Ļ�Ա��ӵĻ�Ա����,������Խ�����������',
            'purview' =>'member_Type',
            'linkurl' =>'member_info_main.php?type=mood'
        ),
    )
);
$actionSearch[6] = array(
    'toptitle' => '��Ա', 
    'title' => '֧������',
    'description' => 'վ������������,�����㿨,�̵궩���Ȳ���',
    'soniterm' => array(
        0  =>  array(
            'title' =>'�㿨��Ʒ����',
            'description' =>'��վ�㿨��Ʒ����,������Ӳ�ͬ�����ĵ㿨��Ʒ����',
            'purview' =>'sys_Data',
            'linkurl' =>'cards_type.php'
        ),
        1  =>  array(
            'title' =>'�㿨��Ʒ����',
            'description' =>'������վ�㿨,�������������ɵ㿨�Լ��鿴�㿨�ĵ�ǰ״̬',
            'purview' =>'sys_Data',
            'linkurl' =>'cards_manage.php'
        ),
        2  =>  array(
            'title' =>'��Ա��Ʒ����',
            'description' =>'���Խ���Ա���ͽ��в�Ʒ����,������۸߼���Ա1������,��������ԶԻ�Ա��Ʒ���ж���',
            'purview' =>'sys_Data',
            'linkurl' =>'member_type.php'
        ),
        3  =>  array(
            'title' =>'��Ա���Ѽ�¼',
            'description' =>'��Ա��ǰ̨���в��������ѻ��ֵ����Ѽ�¼��ͬʱ���Բ鿴���ѳ�ֵ�����ĸ������',
            'purview' =>'sys_Data',
            'linkurl' =>'member_operations.php'
        ),
        4  =>  array(
            'title' =>'�̵궩����¼',
            'description' =>'ǰ̨��Ա�̵��ύ�Ķ�����¼��������Զ���Щ��������һ��ͳһ�Ĺ���',
            'purview' =>'sys_Data',
            'linkurl' =>'shops_operations.php'
        ),
        5  =>  array(
            'title' =>'֧���ӿ�����',
            'description' =>'�̵��Լ���Ա��Ʒ�����õ������߸��ʽ��Ҫ���õ�֧���ӿڣ����ﺬ�г��õĽӿڣ����磺֧�������ױ���',
            'purview' =>'sys_Data',
            'linkurl' =>'sys_info_pay.php'
        ),
        6  =>  array(
            'title' =>'�����ʽ����',
            'description' =>'��վ�����̳ǵ��ͻ���ʽ��������Զ�����б༭����',
            'purview' =>'sys_Data',
            'linkurl' =>'shops_delivery.php'
        ),
        7  =>  array(
            'title' =>'����˺�����',
            'description' =>'���и�����˺�����,�û����Բ鿴��������и����˺ŷ���֧��',
            'purview' =>'sys_Data',
            'linkurl' =>'shops_bank.php'
        ),
    )
);
$actionSearch[7] = array(
    'toptitle' => '����', 
    'title' => '�Զ�����',
    'description' => 'һ�����ɾ�̬����',
    'soniterm' => array(
        0  =>  array(
            'title' =>'һ��������վ',
            'description' =>'����һ���������о�̬ҳ��',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_all.php'
        ),
        1  =>  array(
            'title' =>'����ϵͳ����',
            'description' =>'������Ŀ���桢����ö�ٻ��桢����arclist���û��桢������ڻ�Ա������ʷ��ɾ�����ڶ��� ',
            'purview' =>'sys_ArcBatch',
            'linkurl' =>'sys_cache_up.php'
        ),
    )
);
$actionSearch[8] = array(
    'toptitle' => '����', 
    'title' => 'HTML����',
    'description' => '�����ҳ����Ŀ���ĵ���ר��ȵȽ��и���',
    'soniterm' => array(
        0  =>  array(
            'title' =>'������ҳHTML',
            'description' =>'������վ��ҳ���HTML',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_homepage.php'
        ),
        1  =>  array(
            'title' =>'������Ŀ HTML',
            'description' =>'��ÿ����Ŀ���о�̬HTMLҳ�������',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_list.php'
        ),
        2  =>  array(
            'title' =>'�����ĵ�HTML',
            'description' =>'��ÿ����Ŀ�µ��ĵ����о�̬HTMLҳ�������',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_archives.php'
        ),    
        3  =>  array(
            'title' =>'������վ��ͼ',
            'description' =>'������վ��ͼ�ľ�̬HTMLҳ��',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_map_guide.php'
        ),
        4  =>  array(
            'title' =>'����RSS�ļ� HTML',
            'description' =>'��ȫվ��RSS���и���',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_rss.php'
        ),
        5  =>  array(
            'title' =>'��ȡJS�ļ�',
            'description' =>'���Ի�ȡĳ����Ŀ��js����',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_js.php'
        ),
        6  =>  array(
            'title' =>'����ר�� HTML',
            'description' =>'��ר����о�̬HTMLҳ�������',
            'purview' =>'sys_MakeHtml',
            'linkurl' =>'makehtml_spec.php'
        ),
    )
);
$actionSearch[9] = array(
    'toptitle' => 'ģ��', 
    'title' => 'ģ�����',
    'description' => '�����ҳ����Ŀ���ĵ���ר��ȵȽ��и���',
    'soniterm' => array(
        0  =>  array(
            'title' =>'Ĭ��ģ����� ',
            'description' =>'����վ���ڲ��õ�ģ���ļ����й���',
            'purview' =>'temp_All',
            'linkurl' =>'templets_main.php'
        ),
        1  =>  array(
            'title' =>'��ǩԴ����� ',
            'description' =>'�����еı�ǩ�ļ������޸ġ����',
            'purview' =>'temp_All',
            'linkurl' =>'templets_tagsource.php'
        ),
        2  =>  array(
            'title' =>'�Զ������',
            'description' =>'�����Զ�����',
            'purview' =>'temp_MyTag',
            'linkurl' =>'mytag_main.php'
        ),    
        3  =>  array(
            'title' =>'���ܱ����',
            'description' =>'���Ը�����Ҫ������Ӧ�ĵ��ñ�ǩ',
            'purview' =>'temp_Other',
            'linkurl' =>'mytag_tag_guide.php'
        ),
        4  =>  array(
            'title' =>'ȫ�ֱ�ǲ��� ',
            'description' =>'���Զ�ȫ�ֵı�ǩ���ý��в���',
            'purview' =>'temp_Test',
            'linkurl' =>'tag_test.php'
        ),
    )
);
$actionSearch[10] = array(
    'toptitle' => 'ϵͳ', 
    'title' => 'ϵͳ����',
    'description' => '����վ��һЩ������Ϣ�����ý��й���',
    'soniterm' => array(
        0  =>  array(
            'title' =>'ϵͳ��������',
            'description' =>'����վ�����á��������� ���������á���Ա���á��������á�����ѡ�����ѡ�ģ�����á�����±����ȷ��࣬��������վ������Ϣ����վ�Ļ�������ѡ��',
            'purview' =>'sys_Edit',
            'linkurl' =>'sys_info.php'
        ),
        1  =>  array(
            'title' =>'ϵͳ�û�����',
            'description' =>'�����е���վ����Ա���й���',
            'purview' =>'sys_User',
            'linkurl' =>'sys_admin_user.php'
        ),
        2  =>  array(
            'title' =>'�û����趨',
            'description' =>'����վ����Ա�����û����Ļ���',
            'purview' =>'sys_Group',
            'linkurl' =>'sys_group.php'
        ),
        3  =>  array(
            'title' =>'ϵͳ��־����',
            'description' =>'��ÿ����½��̨�Ĺ���Ա���еĲ������м�¼',
            'purview' =>'sys_Log',
            'linkurl' =>'log_list.php'
        ),
        4  =>  array(
            'title' =>'��֤��ȫ����',
            'description' =>'����֤�����֤�����������',
            'purview' =>'sys_safe.php',
            'linkurl' =>'sys_verify'
        ),
        5  =>  array(
            'title' =>'ͼƬˮӡ����',
            'description' =>'�����ϴ���ͼƬ��ӵ�ˮӡ��������',
            'purview' =>'sys_Edit',
            'linkurl' =>'sys_info_mark.php'
        ),
        6  =>  array(
            'title' =>'�Զ����ĵ�����',
            'description' =>'�������İ汾�У���վ��ҳ��Ƶ���������ƣ���ֻ�ܵ������� arclist ��ǰ�ĳ��Ŀ���»��ض�����ʽ���ĵ���ѡ��Ķ������������������ںܴ�Ĳ��㣬�ڷ�����ʱ����ʺϵ��ĵ�ѡ��ר�ŵ����ԣ���ôʹ��arclist�ĵط��ͻᰴ������Ը��ʾָ�����ĵ���',
            'purview' =>'sys_Att',
            'linkurl' =>'content_att.php'
        ),
        7  =>  array(
            'title' =>'���Ƶ������',
            'description' =>'���Զ��������ʱ��������ʾ��ʽ�����ط�ʽ������������ȵȽ�������',
            'purview' =>'sys_SoftConfig',
            'linkurl' =>'soft_config.php'
        ),
        8  =>  array(
            'title' =>'���ɼ�������',
            'description' =>'���ɼ������ַ�������',
            'purview' =>'sys_StringMix',
            'linkurl' =>'article_string_mix.php'
        ),
        9  =>  array(
            'title' =>'���ģ������',
            'description' =>'�����ý�������ϵͳĬ�ϵ�����ģ�ͣ����ú󷢲�����ʱ���Զ���ָ����ģ�������ȡһ�����������ʹ�ô˹��ܣ���������Ϊ�ռ��ɣ�',
            'purview' =>'sys_StringMix',
            'linkurl' =>'article_template_rand.php'
        ),
        10  =>  array(
            'title' =>'�ƻ��������',
            'description' =>'�������һ��ָ��ʱ�����еĳ���',
            'purview' =>'sys_task',
            'linkurl' =>'sys_task.php'
        ),
        11  =>  array(
            'title' =>'���ݿⱸ��/��ԭ',
            'description' =>'�����ݿ���б��ݺͻ�ԭ',
            'purview' =>'sys_data',
            'linkurl' =>'sys_data.php'
        ),
        12  =>  array(
            'title' =>'SQL�����й���',
            'description' =>'���������ÿ�����ݱ�ִ�е��л��߶��е�SQL���',
            'purview' =>'sys_data',
            'linkurl' =>'sys_sql_query.php'
        ),
        13  =>  array(
            'title' =>'�ļ�У��[S]',
            'description' =>'�ļ�У�齫��鱾վ�ļ��Ƿ��dedecmsԭʼ�ļ��Ƿ���ȫһ��',
            'purview' =>'sys_verifies',
            'linkurl' =>'sys_verifies.php'
        ),
        14  =>  array(
            'title' =>'����ɨ��[S]',
            'description' =>'��DedeCms����ģʽΪ��׼�����е��ļ�����ɨ�貢�����ж�',
            'purview' =>'sys_verifies',
            'linkurl' =>'sys_safetest.php'
        ),
        15  =>  array(
            'title' =>'ϵͳ�����޸�[S]',
            'description' =>'�����ֶ�����ʱ�û�û����ָ����SQL��䣬���Զ���������©�������������ܻᵼ��һЩ����ʹ�ñ����߻��Զ���Ⲣ����',
            'purview' =>'sys_verifies',
            'linkurl' =>'sys_repair.php'
        ),
    )
);
$actionSearch[11] = array(
    'toptitle' => '�ɼ�', 
    'title' => '�ɼ�����',
    'description' => '���ݲɼ��������',
    'soniterm' => array(
        0  =>  array(
            'title' =>'�ɼ��ڵ���� ',
            'description' =>'�����ɼ��ڵ�Ĺ���ҳ��,������Ӳɼ�,����,�����ɼ��ڵ��',
            'purview' =>'co_ListNote',
            'linkurl' =>'co_main.php'
        ),
        1  =>  array(
            'title' =>'��ʱ���ݹ��� ',
            'description' =>'�ɼ�����ʱ���ݴ�Ŵ�',
            'purview' =>'co_ViewNote',
            'linkurl' =>'co_url.php'
        ),
        2  =>  array(
            'title' =>'����ɼ�����',
            'description' =>'����ɼ��Ĺ���',
            'purview' =>'co_GetOut',
            'linkurl' =>'co_get_corule.php'
        ),    
        3  =>  array(
            'title' =>'���ܱ����',
            'description' =>'���Ը�����Ҫ������Ӧ�ĵ��ñ�ǩ',
            'purview' =>'temp_Other',
            'linkurl' =>'mytag_tag_guide.php'
        ),
        4  =>  array(
            'title' =>'��زɼ�ģʽ ',
            'description' =>'��زɼ�ģʽ',
            'purview' =>'co_GetOut',
            'linkurl' =>'co_gather_start.php'
        ),
        5  =>  array(
            'title' =>'�ɼ�δ�������� ',
            'description' =>'�ɼ�û��������ɵ�����',
            'purview' =>'co_GetOut',
            'linkurl' =>'co_do.php?dopost=coall'
        ),
    )
);
?>