<!--

var fixupPos = false;
var canMove = false;
var leftLeaning = 0;
//�첽�ϴ�����ͼ��ر���
var nForm = null;
var nFrame = null;
var picnameObj = null;
var vImg = null;

function $Nav()
{
	if(window.navigator.userAgent.indexOf("MSIE")>=1) return 'IE';
	else if(window.navigator.userAgent.indexOf("Firefox")>=1) return 'FF';
	else return "OT";
}

function $Obj(objname)
{
	return document.getElementById(objname);
}

//�ɵ���ɫѡ����Ѿ����ڣ�
function ShowColor()
{
	var fcolor=showModalDialog("img/color.htm?ok",false,"dialogWidth:106px;dialogHeight:110px;status:0;dialogTop:"+(+120)+";dialogLeft:"+(+120));
	if(fcolor!=null && fcolor!="undefined") document.form1.color.value = fcolor;
}

function ColorSel(c, oname)
{
	var tobj = $Obj(oname);
	if( !tobj ) tobj = eval('document.form1.' + oname);
	if( !tobj )
	{
		$Obj('colordlg').style.display = 'none';
		return false;
	}
	else
	{
		tobj.value = c;
		$Obj('colordlg').style.display = 'none';
		return true;
	}
}

function ShowColor(e, o)
{
	LoadNewDiv(e, 'img/colornew.htm', 'colordlg');
}

function ShowHide(objname)
{
	var obj = $Obj(objname);
	if(obj.style.display != "none" ) obj.style.display = "none";
	else obj.style.display = "block";
}

function ShowHideT(objname)
{
	var obj = $Obj(objname);
	if(obj.style.display != "none" ) obj.style.display = "none";
	else obj.style.display = ($Nav()=="IE" ? "block" : "table");
}

function ShowObj(objname)
{
	var obj = $Obj(objname);
	obj.style.display = ($Nav()=="IE" ? "block" : "table");
}

function ShowObjRow(objname)
{
	var obj = $Obj(objname);
	obj.style.display = ($Nav()=="IE" ? "block" : "table-row");
}

function AddTypeid2()
{
	ShowObjRow('typeid2tr');
	//$Obj('typeid2ct').innerHTML = $Obj('typeidct').innerHTML.replace('typeid','typeid2');
}

function HideObj(objname)
{
	var obj = $Obj(objname);
	obj.style.display = "none";
}

function ShowItem1()
{
	ShowObj('head1'); ShowObj('needset'); HideObj('head2'); HideObj('adset');
}

function ShowItem2()
{
	ShowObj('head2'); ShowObj('adset'); HideObj('head1'); HideObj('needset');
}

function SeePic(img,f)
{
	if( f.value != '' ) 	img.src = f.value;
}

function SeePicNew(f, imgdid, frname, hpos, acname)
{
	var newobj = null;
	if(f.value=='') return ;
	vImg = $Obj(imgdid);
	picnameObj = document.getElementById('picname');
	nFrame = $Nav()=='IE' ? eval('document.frames.'+frname) : $Obj(frname);
	nForm = f.form;
	//�޸�form��action�Ȳ���
	if(nForm.detachEvent) nForm.detachEvent("onsubmit", checkSubmit);
  else nForm.removeEventListener("submit", checkSubmit, false);
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
		newobj.style.display = 'block';
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

function SelectFlash()
{
	if($Nav()=='IE'){ var posLeft = window.event.clientX-300; var posTop = window.event.clientY; }
	else{ var posLeft = 100; var posTop = 100; }
	window.open("../include/dialog/select_media.php?f=form1.flashurl", "popUpFlashWin", "scrollbars=yes,resizable=yes,statebar=no,width=500,height=350,left="+posLeft+", top="+posTop);
}

function SelectMedia(fname)
{
	if($Nav()=='IE'){ var posLeft = window.event.clientX-200; var posTop = window.event.clientY; }
	else{ var posLeft = 100;var posTop = 100; }
	window.open("../include/dialog/select_media.php?f="+fname, "popUpFlashWin", "scrollbars=yes,resizable=yes,statebar=no,width=500,height=350,left="+posLeft+", top="+posTop);
}

function SelectSoft(fname)
{
	if($Nav()=='IE'){ var posLeft = window.event.clientX-200; var posTop = window.event.clientY-50; }
	else{ var posLeft = 100; var posTop = 100; }
	window.open("../include/dialog/select_soft.php?f="+fname, "popUpImagesWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=400,left="+posLeft+", top="+posTop);
}

function SelectImage(fname,stype)
{
	if($Nav()=='IE'){ var posLeft = window.event.clientX-100; var posTop = window.event.clientY; }
	else{ var posLeft = 100; var posTop = 100; }
	if(!fname) fname = 'form1.picname';
	if(!stype) stype = '';
	window.open("../include/dialog/select_images.php?f="+fname+"&imgstick="+stype, "popUpImagesWin", "scrollbars=yes,resizable=yes,statebar=no,width=650,height=400,left="+posLeft+", top="+posTop);
}

function imageCut(fname)
{
	if($Nav()=='IE'){ var posLeft = window.event.clientX-100; var posTop = window.event.clientY; }
	else{ var posLeft = 100; var posTop = 100; }
	if(!fname) fname = 'picname';
	file = document.getElementById(fname).value;
	if(file == '') {
		alert('����ѡ����վ�����ϴ���ͼƬ');
		return false;
	}
	window.open("imagecut.php?f="+fname+"&file="+file, "popUpImagesWin", "scrollbars=yes,resizable=yes,statebar=no,width=800,height=600,left="+posLeft+", top="+posTop);
}

function SelectImageN(fname,stype,vname)
{
	if($Nav()=='IE'){ var posLeft = window.event.clientX-100; var posTop = window.event.clientY; }
	else{ var posLeft = 100; var posTop = 100; }
	if(!fname) fname = 'form1.picname';
	if(!stype) stype = '';
	window.open("../include/dialog/select_images.php?f="+fname+"&imgstick="+stype+"&v="+vname, "popUpImagesWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=400,left="+posLeft+", top="+posTop);
}

function SelectKeywords(f)
{
	if($Nav()=='IE'){ var posLeft = window.event.clientX-350; var posTop = window.event.clientY-200; }
	else{ var posLeft = 100; var posTop = 100; }
	window.open("article_keywords_select.php?f="+f, "popUpkwWin", "scrollbars=yes,resizable=yes,statebar=no,width=600,height=450,left="+posLeft+", top="+posTop);
}

function InitPage()
{
	var selsource = $Obj('selsource');
	var selwriter = $Obj('selwriter');
	var titlechange = $Obj('title');
	var colorbt = $Obj('color');
	if(selsource){ selsource.onmousedown=function(e){ SelectSource(e); } }
	if(selwriter){ selwriter.onmousedown=function(e){ SelectWriter(e); } }
	if(titlechange){ titlechange.onchange=function(e){ TestHasTitle(e); } }
	if(colorbt){ colorbt.onmousedown=function(e){ ShowColor2(e); } }
}

function OpenMyWin(surl)
{
	window.open(surl, "popUpMyWin", "scrollbars=yes,resizable=yes,statebar=no,width=500,height=350,left=200, top=100");
}

function OpenMyWinCoOne(surl)
{
	window.open(surl, "popUpMyWin2", "scrollbars=yes,resizable=yes,statebar=no,width=700,height=450,left=100,top=50");
}

function PutSource(str)
{
	var osource = $Obj('source');
	if(osource) osource.value = str;
	$Obj('mysource').style.display = 'none';
	ChangeFullDiv('hide');
}

function PutWriter(str)
{
	var owriter = $Obj('writer');
	if(owriter) owriter.value = str;
	$Obj('mywriter').style.display = 'none';
	ChangeFullDiv('hide');
}

function ClearDivCt(objname)
{
	if(!$Obj(objname)) return;
	$Obj(objname).innerHTML = '';
	$Obj(objname).style.display = 'none';
	ChangeFullDiv("hide");
}

function ChangeFullDiv(showhide)
{
	var newobj = $Obj('fullpagediv');
	if(showhide=='show')
	{
		if(!newobj)
		{
			newobj = document.createElement("DIV");
			newobj.id = 'fullpagediv';
			newobj.style.position='absolute';
			newobj.className = 'fullpagediv';
			document.body.appendChild(newobj);
		}
		else
		{
			newobj.style.display = 'block';
		}
	}
	else
	{
		if(newobj) newobj.style.display = 'none';
	}
}

function SelectSource(e)
{
	LoadNewDiv(e,'article_select_sw.php?t=source&k=8&rnd='+Math.random(), 'mysource');
	//ChangeFullDiv('show');
}

function SelectWriter(e)
{
	LoadNewDiv(e,'article_select_sw.php?t=writer&k=8&rnd='+Math.random(), 'mywriter');
	//ChangeFullDiv('show');
}

function LoadNewDiv(e,surl,oname)
{
	if($Nav()=='IE')
	{ 
		var posLeft = window.event.clientX-20;
		var posTop = window.event.clientY-30;
		posTop += document.body.scrollTop;
	}
	else
	{
		var posLeft = e.pageX-20;
		var posTop = e.pageY-30;
	}
	posLeft = posLeft - 100;
	var newobj = $Obj(oname);
	if(!newobj){
		newobj = document.createElement("DIV");
		newobj.id = oname;
		newobj.style.position = 'absolute';
		newobj.className = oname;
		newobj.className += ' dlgws';
		newobj.style.top = posTop;
		newobj.style.left = posLeft;
		document.body.appendChild(newobj);
	}
	else{
		newobj.style.display = "block";
	}
	if(newobj.innerHTML.length<10){
		var myajax = new DedeAjax(newobj);
		myajax.SendGet(surl);
	}
}

function TestHasTitle(e)
{
	LoadNewDiv2(e,'article_test_title.php?t='+$Obj('title').value,'mytitle',"dlgTesttitle");
}

function LoadNewDiv2(e,surl,oname,dlgcls)
{
	var posLeft = 300;
	var posTop = 50;
	var newobj = $Obj(oname);
	if(!newobj)
	{
		newobj = document.createElement("DIV");
		newobj.id = oname;
		newobj.style.position='absolute';
		newobj.className = dlgcls;
		newobj.style.top = posTop;
		newobj.style.left = posLeft;
		newobj.style.display = 'none';
		document.body.appendChild(newobj);
	}
	newobj.innerHTML = '';
	var myajax = new DedeAjax(newobj);
	myajax.SendGet2(surl);
	if(newobj.innerHTML=='') newobj.style.display = 'none';
	else newobj.style.display = 'block';
	DedeXHTTP = null;
}

function ShowUrlTr()
{
	var jumpTest = $Obj('flagsj');
	var jtr = $Obj('redirecturltr');
	var jf = $Obj('redirecturl');
	if(jumpTest.checked) jtr.style.display = "block";
	else{
		jf.value = '';
		jtr.style.display = "none";
	}
}

function ShowUrlTrEdit()
{
	ShowUrlTr();
	var jumpTest = $Obj('isjump');
	var rurl = $Obj('redirecturl');
	if(!jumpTest.checked) rurl.value="";
}

function CkRemote()
{
	document.getElementById('picname').value = '';
}

//����ָ����ߵ�AJAX����
function LoadQuickDiv(e, surl, oname, w, h)
{
	if($Nav()=='IE')
	{ 
		if(window.event)
		{
			var posLeft = window.event.clientX - 20;
			var posTop = window.event.clientY - 30;
		}
		else
		{
			var posLeft = e.clientX - 20;
			var posTop = e.clientY + 30;
		}
	}
	else
	{
		var posLeft = e.pageX - 20;
		var posTop = e.pageY - 30;
	}
	posTop += MyGetScrollTop();
	posLeft = posLeft - 400;
	//�̶�λ�õĸ߶�
	if(fixupPos)
	{
		posLeft = posTop = 50;
	}
	var newobj = $Obj(oname);
	if(!newobj)
	{
		newobj = document.createElement("DIV");
		newobj.id = oname;
		newobj.style.position = 'absolute';
		newobj.className = 'pubdlg';
		newobj.style.width = w;
		newobj.style.height = h;
		document.body.appendChild(newobj);
	}
  if(posTop > 500) posTop = 500;
  if(posLeft < 50) posLeft = 50;
  newobj.style.top = posTop;
	newobj.style.left = posLeft;
	newobj.innerHTML = '<div style="margin-top:10px;margin-left:10px;"><img src="img/loadinglit.gif" /> Loading...</div>';
	newobj.style.display = 'block';
	var myajax = new DedeAjax(newobj);
	myajax.SendGet(surl);
	fixupPos = false;
}

function MyGetScrollTop()
{
    return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
}

//ͨ���¼���ȡ�ӿ�
function getEvent()
{ 
	if($Nav()=='IE')  return window.event;
	func=getEvent.caller;       
	while(func!=null)
	{ 
		var arg0 = func.arguments[0];
		if(arg0)
		{
			if((arg0.constructor==Event || arg0.constructor ==MouseEvent) 
			|| (typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation))
			{ 
				return arg0;
			}
		}
		func=func.caller;
	}
	return null;
} 

//ģ��ondrop�¼���ش���
/*----------------------------
leftLeaning = 300;
����������ݹ̶�����onmousedown=DropStartȥ�����µ�DropStop
newobj.ondblclick =  DropStart;
newobj.onmousemove = DropMove;
newobj.onmousedown = DropStop;
----------------------------*/
function DropStart()
{
	this.style.cursor = 'move';
}

function DropStop()
{
	this.style.cursor = 'default';
}

function DropMove()
{
	if(this.style.cursor != 'move') return;
	var event = getEvent();
	if($Nav()=='IE')
	{ 
		var posLeft = event.clientX-20;
		var posTop = event.clientY-30;
		posTop += document.body.scrollTop;
	}
	else
	{
		var posLeft = event.pageX-20;
		var posTop = event.pageY-30;
	}
	this.style.top = posTop;
	this.style.left = posLeft-leftLeaning;
}

//��ָ����Ԫ�ذ�move�¼�
/*-----------------------------
onmousemove="DropMoveHand('divname', 225);"
onmousedown="DropStartHand();"
onmouseup="DropStopHand();"
-----------------------------*/
function DropStartHand()
{
	canMove = (canMove ? false : true);
}
function DropStopHand()
{
	canMove = false;
}
function DropMoveHand(objid, mleftLeaning)
{
	var event = getEvent();
	var obj = $Obj(objid);
	if(!canMove) return;
	
	if($Nav()=='IE')
	{ 
		var posLeft = event.clientX-20;
		var posTop = event.clientY-20;
		posTop += document.body.scrollTop;
	}
	else
	{
		var posLeft = event.pageX-20;
		var posTop = event.pageY-20;
	}
	obj.style.top = posTop;
	obj.style.left = posLeft - mleftLeaning;
}

//�������ݵ����а�
function copyToClipboard(txt)
{
 if(txt==null || txt=='') {
 		alert("û��ѡ���κ�����!");
 		return;
 }
 if(window.clipboardData)
 {
		window.clipboardData.clearData();
		window.clipboardData.setData("Text", txt);
 }
 else if(navigator.userAgent.indexOf('Opera') != -1)
 {
		window.location = txt;
 }
 else
 {
  	try {
   		netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
  	} catch (e) {
   		alert("��������ܾ���\n�����������ַ������'about:config'���س�\nȻ��'signed.applets.codebase_principal_support'����Ϊ'true'");
  	}
  	var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
 	  if (!clip) return;
  
  	var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
  	if (!trans) return;
  
  	trans.addDataFlavor('text/unicode');
  	var str = new Object();
  	var len = new Object();
  	var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
  	var copytext = txt;
  	str.data = copytext;
  	trans.setTransferData("text/unicode",str,copytext.length*2);
  	var clipid = Components.interfaces.nsIClipboard;
  	if (!clip) return false;
  	clip.setData(trans,null,clipid.kGlobalClipboard);
 }
}

function getSelTxt()
{
 var g, r;
 if(document.all){
		g = document.selection.createRange().text;
 }else{
		g = document.getSelection();
 }
 return g;
}

//��ʾ��ĿMap��ͼ
function ShowCatMap(e, obj, cid, targetId, oldvalue)
{
	fixupPos = true;
	LoadQuickDiv(e, 'archives_do.php?dopost=getCatMap&targetid='+targetId+'&channelid='+cid+'&oldvalue='+oldvalue+'&rnd='+Math.random(), 'getCatMap', '700px', '500px');
	ChangeFullDiv('show');
}

function getSelCat(targetId)
{
	var selBox = document.quicksel.seltypeid;
	var targetObj = $Obj(targetId);
	var selvalue = '';
	//����Ŀ����ѡ��
	if(targetId=='typeid2')
	{
		var j = 0;
		for(var i=0; i< selBox.length; i++)
		{
				if(selBox[i].checked) {
					j++;
					if(j==10) break;
					selvalue += (selvalue=='' ? selBox[i].value : ','+selBox[i].value);
				}
		}
		if(targetObj) targetObj.value = selvalue;
	}
	//����Ŀ����ѡ��
	else
	{
		if(selBox)
		{
			for(var i=0; i< selBox.length; i++)
			{
				if(selBox[i].checked) selvalue = selBox[i].value;
			}
		}
		if(selvalue=='')
		{
			alert('��û��ѡ���κ���Ŀ��');
			return ;
		}
		if(targetObj)
		{
			for(var j=0; j < targetObj.length; j++)
			{
				op=targetObj.options[j];
				if(op.value==selvalue) op.selected=true;
			}
		}
	}
	HideObj("getCatMap");
	ChangeFullDiv("hide");
}

-->