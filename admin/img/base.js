	$(function(){
		//�ı���Style
		$(".txt").mouseover(function(){
			$(this).addClass("txt_o");
		}).mouseout(function(){
			$(this).removeClass("txt_o");
		}).focus(function(){
			$(this).addClass("txt_s");
		}).blur(function(){
			$(this).removeClass("txt_s");
		});
		
		//����۵�
		$(".tform").find("tbody tr th[_show]").each(function(i){
			//�����۵���ʾ
			if($(this).attr("_show")=="no"){
				 $(this).append(" <button type=\"button\" class=\"tbody_up\"></button>");
			}else{
				 $(this).append(" <button type=\"button\" class=\"tbody_down\"></button>");
			}
			//�۵�����
			$(this).click(function(){
				if($(this).find("button[class^='tbody_']").attr("class")=="tbody_up"){
					$(this).find("button[class^='tbody_']").attr("class","tbody_down");
					$(this).parent("tr").parent("tbody").find("tr").not($(this).parent("tr")).hide();
				}else if($(this).find("button[class^='tbody_']").attr("class")=="tbody_down"){
					$(this).find("button[class^='tbody_']").attr("class","tbody_up");
					$(this).parent("tr").parent("tbody").find("tr").not($(this).parent("tr")).show();
				}
			}).mouseover(function(){
				$(this).addClass("mouseon");
			}).mouseout(function(){
				$(this).removeClass("mouseon");
			}).click();			
		});

		//�б��и���
		$("table[_dlist*='light']").children("tbody").children("tr").mouseover(function(){
			if($(this).attr("_nolight")!="yes")$(this).addClass("t_on");
		}).mouseout(function(){
			$(this).removeClass("t_on");
		});
		
		//�б�������ѡ��
		$("table[_dlist*='check']").each(function(){
			//�����е��
			$(this).find("tbody tr").click(function(){
				checkbox = $(this).find("td input[type='checkbox']");
				tr = $(this);
				
				if(checkbox.attr("checked")===false){
					checkbox.attr("checked","checked");
					tr.addClass("t_sl");
				}else{
					checkbox.removeAttr("checked");
					tr.removeClass("t_sl");		
				}
				
			});
			
			//����checkbox���
			$(this).find("td input[type='checkbox']").click(function(){
				tr = $(this).parent("td").parent("tr");
				if($(this).attr("checked")===false){
					$(this).attr("checked","checked");
					tr.removeClass("t_sl");
				}else{
					$(this).removeAttr("checked");
					tr.addClass("t_sl");
				}
			});
			
			//�ų����Ӽ���ť���
			$(this).find("tbody tr td a,tbody tr td button,tbody tr td table").click(function(){
				tr = $(this).parent("td").parent("tr");
				checkbox = tr.find("td input[type='checkbox']");
				if(checkbox.attr("checked")===false){
					checkbox.attr("checked","checked");
					tr.removeClass("t_sl");
				}else{
					checkbox.removeAttr("checked");
					tr.addClass("t_sl");
				}
			});
			
		});
		
		
		
		//������ʼ��
		setChecklight();
		
		//ȫѡ��ť
		$("button[_click='allSelect']").click(function(){
			ckbox = $(this).parent("td").parent("tr").parent("tbody").find("td input[type='checkbox']");
			ckbox.attr("checked","checked");
			setChecklight();
		});
		
		//��ѡ��ť
		$("button[_click='unSelect']").click(function(){
			ckbox = $(this).parent("td").parent("tr").parent("tbody").find("td input[type='checkbox']");
			ckbox.each(function(){
				$(this).attr("checked") === false ? $(this).attr("checked","checked") : $(this).removeAttr("checked");
			});
			
			setChecklight();
		});
		
		//�Զ����ύ
		$("button[_submit]").click(function(){
			url = $(this).attr("_submit");
			if(/\[new\].*/.test(url)){
				url = url.replace(/\[new\]/,"");
			}else{
				url = $(this).parents("form").attr("action")+url;
			}
			$(this).parents("form").attr("action",url).submit();
		});
		
		
	});

	/*������ʼ��*/
	function setChecklight(){
		$(".tlist[_dlist*='check']").find("tbody tr td input[type='checkbox']").each(function(i){
			tr = $(this).parent("td").parent("tr");
			if($(this).attr("checked")){
				tr.addClass("t_sl");
			}else{
				tr.removeClass("t_sl");
			}
		});
	}
	
	/*��Ŀ��ת*/
	function AC(mid){
		f = $(window.parent.document);
		mlink = f.find("a[id='"+mid+"']");
		if(mlink.size()>0){
			box = mlink.parents("div[id^='menu_']");
			boxid = box.attr("id").substring(5,128);
			if($("body").attr("class")!="showmenu")$("#togglemenu").click();
			if(mlink.attr("_url")){
				$("#menu").find("div[id^=menu]").hide();
				box.show();
				mlink.addClass("thisclass").blur().parents("#menu").find("ul li a").not(mlink).removeClass("thisclass");
				if($("#mod_"+boxid).attr("class")==""){
					$("#nav").find("a").removeClass("thisclass");
					$("#nav").find("a[id='mod_"+boxid+"']").addClass("thisclass").blur();
				}
				window.location.href = mlink.attr("_url");
			}else if(mlink.attr("_open") && mlink.attr("_open")!=undefined){
				window.open(mlink.attr("_open"));
			}
		}
	
	}
	
