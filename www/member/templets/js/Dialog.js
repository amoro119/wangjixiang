//��������Ի���
  function createDialog(options) {
	  options = $.extend({title: "�Ի���"}, options || {});
	  var dialog = new Boxy("<div><p>����һ���Ի��� <a href='#nogo' onclick='Boxy.get(this).hide(); return false'>�����ң�</a></p></div>", options);
	  return false;
  } 