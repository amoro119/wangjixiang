<?php
if(!defined('DEDEINC')) exit('dedecms');

function multipage($allItemTotal, $currPageNum, $pageSize, $tagid=''){
if ($allItemTotal == 0) return "";

//������ҳ��
$pagesNum = ceil($allItemTotal/$pageSize);

//��һҳ��ʾ
$firstPage = ($currPageNum <= 1) ? $currPageNum ."</b>&lt;&lt;" : "<a href='javascript:multi(1,\"{$tagid}\")' title='��1ҳ'>1&lt;&lt;</a>";

//���һҳ��ʾ
$lastPage = ($currPageNum >= $pagesNum)? "&gt;&gt;". $currPageNum : "<a href='javascript:multi(". $pagesNum . ",\"{$tagid}\")' title='��". $pagesNum ."ҳ'>&gt;&gt;". $pagesNum ."</a>";

//��һҳ��ʾ
$prePage  = ($currPageNum <= 1) ? "��ҳ" : "<a href='javascript:multi(". ($currPageNum-1) . ",\"{$tagid}\")'  accesskey='p'  title='��һҳ'>[��һҳ]</a>";

//��һҳ��ʾ
$nextPage = ($currPageNum >= $pagesNum) ? "��ҳ" : "<a href='javascript:multi(". ($currPageNum+1) .",\"{$tagid}\")' title='��һҳ'>[��һҳ]</a>";

//��ҳ��ʾ
$listNums = "";
for ($i=($currPageNum-4); $i<($currPageNum+9); $i++) {
	if ($i < 1 || $i > $pagesNum) continue;
	if ($i == $currPageNum) $listNums.= "<a href='javascript:void(0)' class='thislink'>".$i."</a>";
	else $listNums.= " <a href='javascript:multi(". $i .",\"{$tagid}\")' title='". $i ."'>". $i ."</a> ";
}

$returnUrl = $listNums;

return $returnUrl;
}
?>