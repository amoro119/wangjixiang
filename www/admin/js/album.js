<!--

function checkSubmitAlb()
{
	if(document.form1.title.value==''){
		alert("�������ⲻ��Ϊ�գ�");
		return false;
	}
	if(document.form1.typeid.value==0){
		alert("��ѡ�񵵰��������");
		return false;
	}
	document.form1.imagebody.value = $Obj('copyhtml').innerHTML;
	return true;
}

function TestGet()
{
	LoadTestDiv();
}


function checkMuList(psid,cmid)
{
	if($Obj('pagestyle3').checked)
	{
		$Obj('cfgmulist').style.display = 'block';
		$Obj('spagelist').style.display = 'none';
	}
	else if($Obj('pagestyle1').checked)
	{
		$Obj('cfgmulist').style.display = 'none';
		$Obj('spagelist').style.display = 'block';
	}
	else
	{
		$Obj('cfgmulist').style.display = 'none';
		$Obj('spagelist').style.display = 'none';
	}
}

//ͼ������ʾ������zip�ļ�ѡ��
function ShowZipField(formitem,zipid,upid)
{
	if(formitem.checked){
		$Obj(zipid).style.display = 'block';
		$Obj(upid).style.display = 'none';
		//$Obj('handfield').style.display = 'none';
		$Obj('formhtml').checked = false;
		$Obj('copyhtml').innerHTML = '';
	}else
	{
		$Obj(zipid).style.display = 'none';
		//$Obj('handfield').style.display = 'block';
	}
}

//ͼ������ʾ������Html�༭��
function ShowHtmlField(formitem,htmlid,upid)
{
	if($Nav()!="IE"){
		alert("�÷����������ڷ�IE�������");
		return ;
	}
	if(formitem.checked){
		$Obj(htmlid).style.display = 'block';
		$Obj(upid).style.display = 'none';
		//$Obj('handfield').style.display = 'none';
		$Obj('formzip').checked = false;
	}else
	{
		$Obj(htmlid).style.display = 'none';
		//$Obj('handfield').style.display = 'block';
		$Obj('copyhtml').innerHTML = '';
	}
}

function SeePicNewAlb(f, imgdid, frname, hpos, acname)
{
	var newobj = null;
	if(f.value=='') return ;
	vImg = $Obj(imgdid);
	picnameObj = document.getElementById('picname');
	nFrame = $Nav()=='IE' ? eval('document.frames.'+frname) : $Obj(frname);
	nForm = f.form;
	//�޸�form��action�Ȳ���
	if(nForm.detachEvent) nForm.detachEvent("onsubmit", checkSubmitAlb);
  else nForm.removeEventListener("submit", checkSubmitAlb, false);
	nForm.action = 'archives_do.php';
	nForm.target = frname;
	nForm.dopost.value = 'uploadLitpic';
	nForm.submit();
	
	picnameObj.value = '';
	newobj = $Obj('uploadwait');
	if(!newobj)
	{
		newobj = document.createElement("DIV");
		newobj.id = 'uploadwait';
		newobj.style.position = 'absolute';
		newobj.className = 'uploadwait';
		newobj.style.width = 120;
		newobj.style.height = 20;
		newobj.style.top = hpos;
		newobj.style.left = 100;
		document.body.appendChild(newobj);
		newobj.innerHTML = '<img src="img/loadinglit.gif" width="16" height="16" alit="" />�ϴ���...';
	}
	newobj.style.display = 'block';
	//�ύ��ԭform��action�Ȳ���
	nForm.action = acname;
	nForm.dopost.value = 'save';
	nForm.target = '';
	nForm.litpic.disabled = true;
	//nForm.litpic = null;
	//if(nForm.attachEvent) nForm.attachEvent("onsubmit", checkSubmit);
  //else nForm.addEventListener("submit", checkSubmit, true);
}

//ɾ���Ѿ��ϴ���ͼƬ
function DelAlbPic(pid)
{
	var tgobj = $Obj('albCtok'+pid);
	var myajax = new DedeAjax(tgobj);
	myajax.SendGet2('swfupload.php?dopost=del&id='+pid);
	$Obj('thumbnails').removeChild(tgobj);
}

//ɾ���Ѿ��ϴ���ͼƬ(�༭ʱ��)
function DelAlbPicOld(picfile, pid)
{
	var tgobj = $Obj('albold'+pid);
	var myajax = new DedeAjax(tgobj);
	myajax.SendGet2('swfupload.php?dopost=delold&picfile='+picfile);
	$Obj('thumbnailsEdit').removeChild(tgobj);
}


-->