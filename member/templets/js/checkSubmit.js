function checkSubmit()
{

	if(document.addcontent.title.value==""){
		alert("���Ʋ���Ϊ�գ�");
		document.addcontent.title.focus();
		return false;
	}

	if(document.addcontent.typeid.value==0){
		alert("������Ŀ����ѡ��");
		return false;
	}

	if(document.addcontent.typeid.options && document.addcontent.typeid.options[document.addcontent.typeid.selectedIndex].className!='option3')
	{
		alert("������Ŀ����ѡ���ɫ��������Ŀ��");
		return false;
	}

	if(document.addcontent.vdcode.value==""){
		document.addcontent.vdcode.focus();
		alert("��֤�벻��Ϊ�գ�");
		return false;
	}

}