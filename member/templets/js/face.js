  //��ʾ����
  function showFace() {
	  if($('#share_textarea').val() == '��,˵��ɶ��...'){
		  $('#share_textarea').val('');
	  }
	  //������ͨ��ʽ
	  //$('#mood_msg_menu').css('display', 'block');
	  var leftpos = $(".share02").position().left;
	  //��ȡλ�ò��Ҿ�������򵯳�λ��
	  $('#mood_msg_menu').css('left', leftpos+'px');
	  $('#mood_msg_menu').show('normal');
	  //$('#mood_add').
	  if($('#mood_face_bg')) {$('#mood_face_bg').remove();}
	  var modDiv = '<div id="mood_face_bg" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 788px; z-index: 10000; opacity: 0;" onclick="hideFace()"/>'
	  $('#baseParent').append(modDiv); 
  }
  
  //���ر���
  function hideFace() {
	  //alert($('#share_textarea').val());
	  if($('#share_textarea').val() == ''){
		  $('#share_textarea').val('��,˵��ɶ��...');
	  }
	  $('#mood_msg_menu').css('display', 'none');
	  if($('#mood_face_bg')) {$('#mood_face_bg').remove();}
  }
  
  //���ӱ���
  function addFace(faceid) {
	  //ͨ��faceid����Ϊ���������ӵ��༭��
	  var facecode;
	  facecode = '[face:' + faceid + ']';
	  $('#share_textarea').val($('#share_textarea').val() + facecode); 
  }