<!--
if(moz) {
	extendEventObject();
	extendElementModel();
	emulateAttachEvent();
}
function viewArc(aid){
	if(aid==0) aid = getOneItem();
	window.open("archives_do.php?aid="+aid+"&dopost=viewArchives");
}
function kwArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	if(qstr=='')
	{
		alert('����ѡ��һ�������ĵ���');
		return;
	}
	location="archives_do.php?aid="+aid+"&dopost=makekw&qstr="+qstr;
}
function editArc(aid){
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=editArchives";
}
function updateArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=makeArchives&qstr="+qstr;
}
function checkArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=checkArchives&qstr="+qstr;
}
function moveArc(e, obj, cid){
	var qstr=getCheckboxItem();
	if(qstr=='')
	{
		alert('����ѡ��һ�������ĵ���');
		return;
	}
	LoadQuickDiv(e, 'archives_do.php?dopost=moveArchives&qstr='+qstr+'&channelid='+cid+'&rnd='+Math.random(), 'moveArchives', '450px', '180px');
	ChangeFullDiv('show');
}
function adArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?aid="+aid+"&dopost=commendArchives&qstr="+qstr;
}

function cAtts(jname, e, obj)
{
	var qstr=getCheckboxItem();
	if(qstr=='')
	{
		alert('����ѡ��һ�������ĵ���');
		return;
	}
	LoadQuickDiv(e, 'archives_do.php?dopost=attsDlg&qstr='+qstr+'&dojob='+jname+'&rnd='+Math.random(), 'attsDlg', '450px', '160px');
	ChangeFullDiv('show');
}

function delArc(aid){
	var qstr=getCheckboxItem();
	if(aid==0) aid = getOneItem();
	location="archives_do.php?qstr="+qstr+"&aid="+aid+"&dopost=delArchives";
}

function QuickEdit(aid, e, obj)
{
	LoadQuickDiv(e, 'archives_do.php?dopost=quickEdit&aid='+aid+'&rnd='+Math.random(), 'quickEdit', '450px', '300px');
	ChangeFullDiv('show');
}
//�����Ĳ˵�
function ShowMenu(evt,obj,aid,atitle)
{
  var popupoptions
  popupoptions = [
    new ContextItem("����ĵ�",function(){ viewArc(aid); }),
		new ContextItem("�༭����",function(){ QuickEdit(aid, evt, obj); }),
    new ContextItem("�༭�ĵ�",function(){ editArc(aid); }),
    new ContextSeperator(),
    new ContextItem("����HTML",function(){ updateArc(aid); }),
    new ContextItem("����ĵ�",function(){ checkArc(aid); }),
    new ContextItem("�Ƽ��ĵ�",function(){ adArc(aid); }),
    new ContextItem("ɾ���ĵ�",function(){ delArc(aid); }),
    new ContextSeperator(),
    new ContextItem("����(<u>C</u>)",function(){ copyToClipboard(atitle); }),
    new ContextItem("����ҳ��",function(){ location.reload(); }),
    new ContextSeperator(),
    new ContextItem("ȫ��ѡ��",function(){ selAll(); }),
    new ContextItem("ȡ��ѡ��",function(){ noSelAll(); }),
    new ContextSeperator(),
    new ContextItem("�رղ˵�",function(){})
  ]
  ContextMenu.display(evt,popupoptions);
  //location="catalog_main.php";
}
//���ѡ���ļ����ļ���
function getCheckboxItem()
{
	var allSel="";
	if(document.form2.arcID.value) return document.form2.arcID.value;
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
			if(allSel=="")
				allSel=document.form2.arcID[i].value;
			else
				allSel=allSel+"`"+document.form2.arcID[i].value;
		}
	}
	return allSel;
}

//���ѡ������һ����id
function getOneItem()
{
	var allSel="";
	if(document.form2.arcID.value) return document.form2.arcID.value;
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
				allSel = document.form2.arcID[i].value;
				break;
		}
	}
	return allSel;
}

function selAll()
{
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(!document.form2.arcID[i].checked)
		{
			document.form2.arcID[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.arcID.length;i++)
	{
		if(document.form2.arcID[i].checked)
		{
			document.form2.arcID[i].checked=false;
		}
	}
}
-->