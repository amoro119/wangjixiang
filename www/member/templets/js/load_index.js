$(document).ready(function(){ 
	  //�����ż�в�ͬ��ʽ	
	  $(".list tbody tr:even").addClass("row0");//ż��
	  $(".list tbody tr:odd").addClass("row1");//����
  
	  $(".submit tbody tr:even").addClass("row0");//ż��
	  $(".submit tbody tr:odd").addClass("row1");//����
	  
	  //����IE6��hover Bug
	  if ( $.browser.msie ){
	  	if($.browser.version == '6.0'){
	  		$("#menuBody li").hover(
	  			function(){
	  				//�����ж�,�Ƿ����
	  				//����������.actΪ����
	  				$(".act").each(function(){this.style.visibility='hidden'});
	  				if($(this).find(".act").length != 0)
	  				{
	  					$(this).children(".act").css("visibility","visible");
	  				} else {
	  					$(".act").css("visibility","hidden");
	  				}
	  			}
	  		)
	  	}
	  }	  
  })