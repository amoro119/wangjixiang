<!--
function selAll()
{
	var celements = document.getElementsByName('aids[]');
	for(i=0;i<celements.length;i++)
	{
		if(!celements[i].checked) celements[i].checked = true;
		else celements[i].checked = false;
	}
}

function noselAll()
{
	var celements = document.getElementsByName('aids[]');
	for(i=0;i<celements.length;i++)
	{
		if(celements[i].checked = true) 
		{
			celements[i].checked = false;
		}
	}
}

function delkey()
	{
		if(window.confirm("��ȷʵҪɾ��ѡ���Ĺؼ���ô��"))
		{
			document.form3.dopost.value = 'del';
			document.form3.submit();
		}
	}
	
function diskey()
	{
		if(window.confirm("��ȷʵҪ����ѡ���Ĺؼ���ô��"))
		{
			document.form3.dopost.value = 'dis';
			document.form3.submit();
		}
	}
	
function enakey()
	{
		if(window.confirm("��ȷʵҪ����ѡ���Ĺؼ���ô��"))
		{
			document.form3.dopost.value = 'ena';
			document.form3.submit();
		}
	}
	
function urlkey()
	{
		if(window.confirm("��ȷʵҪ����ѡ���Ĺؼ��ֵ���ַô��"))
		{
			document.form3.dopost.value = 'url';
			document.form3.submit();
		}
	}
	
function rankey()
	{
		if(window.confirm("��ȷʵҪ�ı�ѡ���Ĺؼ��ֵ�Ƶ��ô��"))
		{
			document.form3.dopost.value = 'ran';
			document.form3.submit();
		}
	}
<!--����ɾ���Ѷ�ؼ���-->
function delall()
	{
		if(window.confirm("��ȷʵҪɾ��ѡ���Ĺؼ���ô��"))
		{
			document.form3.dopost.value = 'delall';
			document.form3.submit();
		}
	}
	

-->