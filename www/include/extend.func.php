<?php
function litimgurls($imgid=0){
   global $lit_imglist;
   $dsql = new DedeSql(false);
   //��ȡ���ӱ�
   $row = $dsql->GetOne("SELECT c.addtable FROM #@__archives AS a LEFT JOIN #@__channeltype AS c ON a.channel=c.id where a.id='$imgid'");
   $addtable = trim($row['addtable']);
   //��ȡͼƬ���ӱ�imgurls�ֶ����ݽ��д���
   $row = $dsql->GetOne("Select imgurls From `$addtable` where aid='$imgid'");
   //����inc_channel_unit.php��ChannelUnit��
   $ChannelUnit = new ChannelUnit(2,$imgid);
   //����ChannelUnit����GetlitImgLinks������������ͼ
   $lit_imglist = $ChannelUnit->GetlitImgLinks($row['imgurls']);
   //���ؽ��
   return $lit_imglist;
}

?>