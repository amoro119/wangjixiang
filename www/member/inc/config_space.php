<?php
if(!defined('DEDEMEMBER')) exit('dedecms');

//����Ƿ񿪷Ż�Ա����
if($cfg_mb_open=='N')
{
	ShowMsg("ϵͳ�ر��˻�Ա���ܣ�������޷����ʴ�ҳ�棡","javascript:;");
	exit();
}

//��uid���й���
if(preg_match("/'/",$uid)){
   ShowMsg("�����û����к��зǷ��ַ���", "-1");
   exit();
}else{
   $uid=RemoveXSS($uid);
}


$_vars = GetUserSpaceInfos();
$_vars['bloglinks'] = $_vars['curtitle'] = '';

//---------------------------
//�û�Ȩ�޼��
//�������û�
if($_vars['spacesta'] == -2)
{
	ShowMsg("�û���{$_vars['userid']} �����ԣ���˸��˿ռ��ֹ���ʣ�", "-1");
	exit();
}
//δ����û�
if($_vars['spacesta'] < 0)
{
	ShowMsg("�û���{$_vars['userid']} ��������δͨ����ˣ���˿ռ��ֹ���ʣ�", "-1");
	exit();
}
//�Ƿ��ֹ�˹���Ա�ռ�ķ���
if( !isset($_vars['matt']) ) $_vars['matt'] = 0;
if($_vars['matt'] == 10 && $cfg_mb_adminlock=='Y' 
&& !(isset($cfg_ml->fields) && $cfg_ml->fields['matt']==10))
{
	ShowMsg('ϵͳ�����˽�ֹ���ʹ���Ա�ĸ��˿ռ䣡', '-1');
	exit();
}

//---------------------------
//Ĭ�Ϸ��
if($_vars['spacestyle']=='')
{
	if($_vars['mtype']=='����') {
		$_vars['spacestyle'] = 'person';
	}
	else if($_vars['mtype']=='��ҵ') {
		$_vars['spacestyle'] = 'company';
	}
	else {
		$_vars['spacestyle'] = 'person';
	}
}
//�Ҳ���ָ����ʽ�ļ��е�ʱ��ʹ��personΪĬ��
if(!is_dir(DEDEMEMBER.'/space/'.$_vars['spacestyle']))
{
	$_vars['spacestyle'] = 'person';
}

//��ȡ��������
$mtypearr = array();
$dsql->Execute('mty', "select * from `#@__mtypes` where mid='".$_vars['mid']."'");
while($row = $dsql->GetArray('mty'))
{
	$mtypearr[] = $row;
}

//��ȡ��Ŀ��������
$_vars['bloglinks'] = array();
$query = "select tp.channeltype,ch.typename from `#@__arctype` tp 
      left join `#@__channeltype` ch on ch.id=tp.channeltype 
      where (ch.usertype='' OR ch.usertype like '{$_vars['mtype']}') And tp.channeltype<>1 And tp.issend=1 And tp.ishidden=0 group by tp.channeltype order by ABS(tp.channeltype) asc";
$dsql->Execute('ctc', $query);
while( $row = $dsql->GetArray('ctc') )
{
	$_vars['bloglinks'][$row['channeltype']] = $row['typename'];
}


//��ȡ��ҵ�û�˽������
if($_vars['mtype']=='��ҵ')
{
	require_once(DEDEINC.'/enums.func.php');
	$query = "select * from `#@__member_company` where mid='".$_vars['mid']."'";
	$company = $db->GetOne($query);
	$company['vocation'] = GetEnumsValue('vocation', $company['vocation']);
	$company['cosize'] = GetEnumsValue('cosize', $company['cosize']);
	$tmpplace = GetEnumsTypes($company['place']);
	$provinceid = $tmpplace['top'];
	$provincename = (isset($em_nativeplaces[$provinceid]) ?  $em_nativeplaces[$provinceid] : '');
	$cityname = (isset($em_nativeplaces[$tmpplace['son']]) ? $em_nativeplaces[$tmpplace['son']] : '');
	$company['place'] = $provincename.' - '.$cityname;
	$_vars = array_merge($company, $_vars);
	if($action == 'infos') $action = 'introduce';
	$_vars['comface'] = empty($_vars['comface']) ? 'images/comface.png' : $_vars['comface'];
}

/**
 * ��ȡ�ռ������Ϣ
 *
 * @return unknown
 */
function GetUserSpaceInfos()
{
	global $dsql,$uid,$cfg_memberurl;
	$_vars = array();
	$userid = ereg_replace("[\r\n\t \*%]",'',$uid);
	$query = "Select m.mid,m.mtype,m.userid,m.uname,m.sex,m.rank,m.email,m.scores,
							m.spacesta,m.face,m.logintime,
							s.*,t.*,m.matt,r.membername,g.msg
		          From `#@__member` m
		          left join `#@__member_space` s on s.mid=m.mid
		          left join `#@__member_tj` t on t.mid=m.mid
		          left join `#@__arcrank` r on r.rank=m.rank
				  left join `#@__member_msg` g on g.mid=m.mid
		          where m.userid like '$uid' ORDER BY g.dtime desc ";
	$_vars = $dsql->GetOne($query);
	if(!is_array($_vars))
	{
		ShowMsg("����ʵ��û������Ѿ���ɾ����","javascript:;");
		exit();
	}
	if($_vars['face']=='')
	{
		$_vars['face']=($_vars['sex']=='Ů')? 'templets/images/dfgirl.png' : 'templets/images/dfboy.png';
	}
	$_vars['userid_e'] = urlencode($_vars['userid']);
	$_vars['userurl'] = $cfg_memberurl."/index.php?uid=".$_vars['userid_e'];
	if($_vars['membername']=='�������')
	{
		$_vars['membername'] = '���ƻ�Ա';
	}
	return $_vars;
}

?>