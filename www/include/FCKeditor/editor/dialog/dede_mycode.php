<?php
require_once(dirname(__FILE__)."/../../../common.inc.php");
?>
<HTML>
<HEAD>
<title>�����Զ�������</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<style>
.td{font-size:10pt;}
li,dd {
  list-style-type:none; margin:0px; padding:0px; 
}
ul,dl,ol,form,div {
  margin:0px; padding:0px;
}
</style>
<script src="common/fck_dialog_common.js" type="text/javascript"></script>
<script language=javascript>
var dialog = window.parent ;
var oEditor	= window.parent.InnerDialogLoaded() ;
var oDOM		= oEditor.FCK.EditorDocument ;
var FCK = oEditor.FCK;
window.onload = function()
{
	oEditor.FCKLanguageManager.TranslatePage(document) ;
	window.parent.SetOkButton( true ) ;
}
function Ok()
{
    var svalue = 0;
    if(!document.form1.selitems) {
    	return true;
    }
    if(document.form1.selitems.value) svalue = document.form1.selitems.value;
    else
    {
    	for(var i=0; i<document.form1.selitems.length; i++)
    	{
    		if(document.form1.selitems[i].checked) svalue = document.form1.selitems[i].value;
    	}
  	}
  	if(svalue > 0) oEditor.FCK.InsertHtml( document.getElementById('lab'+svalue).innerHTML );
    return true;
}

</script>
<link href="base.css" rel="stylesheet" type="text/css">
</HEAD>
<body bgcolor="#EBF6CD" topmargin="8">
  <form name="form1" id="form1">
  	<table border="0" width="98%" align="center">
    <tr> 
      <td>
      	��ѡ��Ҫ�������ݣ�
      	<br>
      	<font color='#999999'>�޸��ļ�data/admin/mycode.php������Щ����</font>
      </td>
    </tr>
    <tr height="50"> 
      <td style='line-height:160%' nowrap>
      	<ul>
      	<?php
      	$codefile = DEDEROOT."/data/admin/mycode.txt";
      	if(!file_exists($codefile))
      	{
      		 $testStr = "<"."?php
##����HTML����һ
//#<div align=\"center\">��������Ҫ�����HTML���ݣ�ǰ���б�ܱ��뱣����</div>
##����HTML���ݶ�
//#<div align=\"center\">��������Ҫ�����HTML���ݣ�ǰ���б�ܱ��뱣����</div>";
           $fp = fopen($codefile, 'w');
           fwrite($fp, $testStr);
           fclose($fp);
      	}
      	$fp = fopen($codefile, 'r');
      	$str = '';
      	while( !feof($fp) )
      	{
      		$str .= trim(fgets($fp, 1024));
      	}
      	fclose($fp);
      	$strs = explode("##", $str);
      	$i = 0;
      	foreach($strs as $str)
      	{
      		if(!ereg('//#',$str)) continue;
      		$i++;
      		$tmds = explode("//#", $str);
      		echo "<li><input type='radio' name='selitems' value='$i'><b>{$tmds[0]}</b>\r\n";
					echo "<br /><label id='lab{$i}'>{$tmds[1]}</label>";
      		echo "</li>\r\n";
      	}
      	?>
      </ul>
      </td>
    </tr>
  </table>
  </form>
</body>
</HTML>

