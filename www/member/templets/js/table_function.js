/*

֯�οƼ� ����Ա���ı����ء� ����

2008.10.14 10:48 for Fangyu12@gmail.com

Last modified 2008.10.14 17:30

Copyright (c) 2008, dedecms All rights reserved.

*/
$(document).ready(function(){
	//�����ż�в�ͬ��ʽ	
	$(".list tbody tr:even").addClass("row0");//ż��
	$(".list tbody tr:odd").addClass("row1");//����

	$(".submit tbody tr:even").addClass("row0");//ż��
	$(".submit tbody tr:odd").addClass("row1");//����
	
	$(".friend:odd").addClass("row1");
	
	//��ǩ
	$("#linkList .flink:odd").addClass("row1");
	
	//�����Ԫ��ʱ���и���
	$(".list tbody tr td").click(function(){$(this).parent("tr").toggleClass("click");$(this).parent("tr").toggleClass("hover");	});
	$(".list tbody tr td").hover(function(){$(this).parent("tr").addClass("hover"); },function(){$(this).parent("tr").removeClass("hover"); });
	//checked ȫѡ&��ѡ&��ѡ
	$("#checkedClick").click(function(){
		$(".list tbody [type='checkbox']").each(function(){
			if($(this).attr("checked")){
				$(this).removeAttr("checked");				
				$(".list tbody tr").removeClass("click");
				}
			else{
				$(this).attr("checked",'true');				
				$(".list tbody tr").addClass("click");
				}
		})
	});
	
	//checked ȫѡ&��ѡ&��ѡ
	$("#checkedClick").click(function(){
		$("form [type='checkbox']").each(function(){
			if($(this).attr("checked")){
				$(this).removeAttr("checked");
				}
			else{
				$(this).attr("checked",'true');
				}
		})
	});
	
	//��Ŀ-�ղ� δ���,��ͷ�Ի���ing ע:����
	$(".favorite #allDeploy").click(function(){$(".itemBody").toggleClass("invisible");});
	$(".favorite .itemHead").click(function(){$(this).next(".itemBody").toggleClass("invisible");});
	
	//��Ŀ-����friend
	$(".friend .itemHead").click(function(){$(this).next(".itemBody").toggleClass("invisible");});
	//��Ŀ-��������friend
	$(".search .itemHead").click(function(){$(this).next(".itemBody").toggleClass("invisible");});
	//��Ŀ-̽��visit
	$("#allDeploy").click(function(){$(".itemBody").toggleClass("invisible");});
	$(".visit .itemHead").click(function(){$(this).next(".itemBody").toggleClass("invisible");});
	//��Ŀ-��ϸ����info
	$(".info .itemHead").click(function(){
										$(this).next(".itemBody").toggleClass("invisible");
										$(this).children(".icon16").toggleClass("titHide");
										$(this).children(".icon16").toggleClass("titShow")});
	//������
	/*$(".card").load( function() {$(this).addClass("invisible")});
	$(".level").load( function() {$(this).addClass("invisible")});
	$(".rechargeable").load( function() {$(this).addClass("invisible")});
														  
	$("#buy").click(function(){$(".rechargeable").addClass("invisible");$(".card").removeClass("invisible");$(".level").removeClass("invisible");});
	$("#rechargeable").click(function(){$(".rechargeable").removeClass("invisible");$(".card").addClass("invisible");$(".level").addClass("invisible");});
	
	$(".buyCard").click(function(){$(".card").removeClass("invisible");$(".level").addClass("invisible");});
	$(".buyLevel").click(function(){$(".card").addClass("invisible");$(".level").removeClass("invisible");});*/
	
	
	
});