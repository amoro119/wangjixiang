<!--

function ShowAddCatalog(){
	$Obj('addCatalog').style.display='block';
}

function CloseAddCatalog(){
	$Obj('addCatalog').style.display='none';
}

function CloseEditCatalog(){
	$Obj('editCatalog').style.display='none';
}

function DelCatalog(cid){
	if(window.confirm("��ȷʵҪɾ���������ô��"))
	{
		location.href='story_catalog.php?catid='+cid+'&action=del';
	}
}

function DelStory(bid){
	if(window.confirm("��ȷʵҪɾ���Ȿͼ��ô��")){
		location.href='story_do.php?bid='+bid+'&action=delbook';
	}
}

function DelStoryContent(cid){
	if(window.confirm("ɾ�����ݺ��½ڵ���������������Ų��ᷢ���仯��\r\n����ܵ��¹�����ң���ȷʵҪɾ����ƪ����ô��")){
		location.href='story_do.php?cid='+cid+'&action=delcontent';
	}
}

function CloseLayer(layerid){
	$Obj(layerid).style.display='none';
}

//Ԥ������
function PreViewCt(cid,booktype){
	if(booktype==0){
		window.open("../book/story.php?id="+cid);
	}else{
		window.open("../book/show-photo.php?id="+cid);
	}
}

//�༭��Ŀ
function EditCatalog(cid){
	$Obj('editCatalog').style.display='block';
	var myajax = new DedeAjax($Obj('editCatalogBody'),false,true,"","","���Ժ���������...");
	myajax.SendGet2('story_catalog.php?catid='+cid+'&action=editload');
	DedeXHTTP = null;
}

//ͼ���½ڣ�����ѡ��
function ReSelChapter(){
	var ems = document.getElementsByName('ids[]');
	for(var i=0;i<ems.length;i++){
		if(!ems[i].checked) ems[i].checked = true;
		else ems[i].checked = false;
	}
}

//ɾ�����½�ͼ������
function DelStoryChapter(cid){
	if(window.confirm("ɾ���½ڻ�ɾ���½��µ��������ݣ���ȷʵҪɾ��ô��")){
		location.href='story_do.php?cid='+cid+'&action=delChapter';
	}
}

//����ͼ��ļ��
function checkSubmitAdd()
{
	if(document.form1.catid.value==0){
		alert("��ѡ���������ݵ���Ŀ��");
		document.form1.bookname.focus();
		return false;
	}
	if(document.form1.bookname.value==""){
		alert("����ͼ�����Ʋ���Ϊ�գ�");
		document.form1.bookname.focus();
		return false;
	}
}

//����С˵���ݵļ��
function checkSubmitAddCt()
{
	if(document.form1.title.value==0){
		alert("���±��ⲻ��Ϊ�գ�");
		document.form1.title.focus();
		return false;
	}
	if(document.form1.chapterid.selectedIndex==-1 && document.form1.chapternew.value==''){
		alert("���������½ں����½����Ʋ���ͬʱΪ�գ�");
		return false;
	}
}

//�����������ݵļ��
function checkSubmitAddPhoto()
{
	if(document.form1.chapterid.selectedIndex==-1 && document.form1.chapternew.value==''){
		alert("���������½ں����½����Ʋ���ͬʱΪ�գ�");
		return false;
	}
	document.form1.photonum.value = endNum;
}

//��ʾѡ����������½�ѡ��
function ShowHideSelChapter(selfield,newfield)
{
	if(document.form1.addchapter.checked){
		$Obj(selfield).style.display = 'none';
		$Obj(newfield).style.display = 'block';
	}else{
		$Obj(selfield).style.display = 'block';
		$Obj(newfield).style.display = 'none';
	}
}

function selAll()
{
	for(i=0;i<document.form2.ids.length;i++)
	{
		if(!document.form2.ids[i].checked){
			document.form2.ids[i].checked=true;
		}
	}
}
function noSelAll()
{
	for(i=0;i<document.form2.ids.length;i++)
	{
		if(document.form2.ids[i].checked){
			document.form2.ids[i].checked=false;
		}
	}
}
//���ѡ���ļ����ļ���
function getCheckboxItem()
{
	var allSel="";

	if(document.form2.ids.value) return document.form2.ids.value;
	for(i=0;i<document.form2.ids.length;i++)
	{
		if(document.form2.ids[i].checked){
			allSel += (allSel=='' ? document.form2.ids[i].value : ","+document.form2.ids[i].value);
		}
	}
	return allSel;
}
//ɾ����ѡ
function DelAllBooks()
{
	if(window.confirm("��ȷʵҪɾ����Щͼ��ô��")){
		var selbook = getCheckboxItem();
		location.href='story_do.php?bid='+selbook+'&action=delbook';
	}
}

-->