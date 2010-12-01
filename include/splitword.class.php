<?php
/*
 * Unicode����ʵ��php�ִ���
 *
 */
define('_SP_', chr(0xFF).chr(0xFE)); 
define('_SP2_', ',');
//�����Щϵͳ�ڴ��������
ini_set('memory_limit', '64M');
class SplitWord
{
    
    //�����������ַ����루ֻ���� utf-8��gbk/gb2312/gb18030��big5 �������ͣ�  
    public $sourceCharSet = 'utf-8';
    public $targetCharSet = 'utf-8';
	
    //���ɵķִʽ���������� 1 Ϊȫ���� 2Ϊ �ʵ�ʻ㼰�������պ����ַ���Ӣ�ģ� 3 Ϊ�ʵ�ʻ㼰Ӣ��
    public $resultType = 2;
    
    //���ӳ���С�������ֵʱ����֣�notSplitLen = n(������) * 2 + 1
    public $notSplitLen = 5;
    
    //��Ӣ�ĵ���ȫ��תСд
    public $toLower = false;
    
    //ʹ������з�ģʽ�Զ�Ԫ�ʽ������
    public $differMax = false;
    
    //���Ժϲ�����
    public $unitWord = true;
    
    //ʹ�����Ŵ�����ģʽ�������
    public $differFreq = false;
	
    //��ת��Ϊunicode��Դ�ַ���
    private $sourceString = '';
    
    //���Ӵʵ�
    public $addonDic = array();
    public $addonDicFile = 'data/words_addons.dic';
    
    //���ʵ� 
    public $dicStr = '';
    public $mainDic = array();
    public $mainDicInfos = array();
    public $mainDicFile = 'data/base_dic_full.dic';
    //�Ƿ�ֱ������ʵ䣨ѡ�������ٶȽ������������Ͽ죻ѡ������Ͽ죬��������������Ҫʱ�Ż������ض��Ĵ�����
    private $isLoadAll = false;
    
    //���ʵ������󳤶�(ʵ�ʼ��ϴ�ĩΪ12+2)
    private $dicWordMax = 12;
    //�ַֺ�����飨ͨ���ǽ�ȡ���ӵ���;��
    private $simpleResult = array();
    //���ս��(�ÿո�ֿ��Ĵʻ��б�)
    private $finallyResult = '';
    
    //�Ƿ��Ѿ�����ʵ�
    public $isLoadDic = false;
    //ϵͳʶ���ϲ����´�
    public $newWords = array();
    public $foundWordStr = '';
    //�ʿ�����ʱ��
    public $loadTime = 0;
    
    //php4���캯��
	  function SplitWord($source_charset='utf-8', $target_charset='utf-8', $load_all=true, $source=''){
	  	$this->__construct($source_charset, $target_charset, $load_all, $source);
	  }	
	
    function __construct($source_charset='utf-8', $target_charset='utf-8', $load_all=true, $source='')
    {
        $this->SetSource( $source, $source_charset, $target_charset );
        $this->isLoadAll = $load_all;
        $this->LoadDict();
    }
    
    function SetSource( $source, $source_charset='utf-8', $target_charset='utf-8' )
    {
        $this->sourceCharSet = strtolower($source_charset);
        $this->targetCharSet = strtolower($target_charset);
        $this->simpleResult = array();
        $this->finallyResult = array();
        $this->finallyIndex = array();
        if( $source != '' )
        {
            $rs = true;
            if( preg_match("/^utf/", $source_charset) ) {
                $this->sourceString = iconv('utf-8//ignore', 'ucs-2', $source);
            }
            else if( preg_match("/^gb/", $source_charset) ) {
                $this->sourceString = iconv('utf-8', 'ucs-2', iconv('gb18030', 'utf-8//ignore', $source));
            }
            else if( preg_match("/^big/", $source_charset) ) {
                $this->sourceString = iconv('utf-8', 'ucs-2', iconv('big5', 'utf-8', $source));
            }
            else {
                $rs = false;
            }
        }
        else
        {
           $rs = false;
        }
        return $rs;
    }
    
    /**
     * ���ý������(ֻ�ڻ�ȡfinallyResult����Ч)
     * @param $rstype 1 Ϊȫ���� 2ȥ���������
     *
     * @return void
     */
    function SetResultType( $rstype )
    {
        $this->resultType = $rstype;
    }
    
    /**
     * ����ʵ�
     *
     * @return void
     */
    function LoadDict( $maindic='' )
    {
        $dicAddon = dirname(__FILE__).'/'.$this->addonDicFile;
        if($maindic=='' || !file_exists(dirname(__FILE__).'/'.$maindic) )
        {
            $dicWords = dirname(__FILE__).'/'.$this->mainDicFile ;
        }
        else
        {
            $dicWords = dirname(__FILE__).'/'.$maindic;
            $this->mainDicFile = $maindic;
        }
        //�������ʵ�
        $startt = microtime(true);
        $aslen = filesize($dicWords);
        $fp = fopen($dicWords, 'rb');
        $this->dicStr = fread($fp, $aslen);
        fclose($fp);
        $ishead = 1;
        $nc = '';
        $i = 0;
        while( $i < $aslen )
        {
            $nc = substr($this->dicStr, $i, 2);
            $i = $i+2;
            $slen = intval(hexdec(bin2hex( substr($this->dicStr, $i, 2) )));
            $i = $i+2;
            $this->mainDic[$nc][1] = '';
            $this->mainDic[$nc][2][0] = $i;
            $this->mainDic[$nc][2][1] = $slen;
            if( $this->isLoadAll)
            {
                $strs = explode(_SP_, substr($this->dicStr, $i, $slen) );
                $klen = count($strs);
                for($k=0; $k < $klen; $k++)
                {
                    //�Ȳ��Դ�Ƶ�ʹ��Խ��н��ͣ������������ٶȣ�������($this->GetWordInfos($word)��ôʵĸ�����Ϣ)
                    $this->mainDic[$nc][1][$strs[$k]] = $strs[$k+1];
                    //$this->mainDic[$nc][1][$strs[$k]] = explode(',', $strs[$k+1]); //ֱ�ӽ�������໨��0.1��ʱ�䣩
                    $k = $k+1;
                }
            }
            $i = $i + $slen;
        }
        //���ر����ʵ��ļ��ַ���
        if( $this->isLoadAll)
        {
            $this->dicStr = '';
        }
        //���븱�ʵ�
        $ds = file($dicAddon);
        foreach($ds as $d)
        {
            $d = trim($d);
            if($d=='') continue;
            $estr = substr($d, 1, strlen($d)-1);
            $estr = iconv('utf-8', 'ucs-2', $estr);
            $this->addonDic[substr($d, 0, 1)][$estr] = strlen($estr);
        }
        $this->loadTime = microtime(true) - $startt;
        $this->isLoadDic = true;
    }
    
   /**
    * ���ĳ��β���Ƿ����
    */
    function IsWordEnd($nc)
    {
         if( !isset( $this->mainDic[$nc] ) )
         {
            return false;
         }
         if( !is_array($this->mainDic[$nc][1]) )
         {       
              $strs = explode(_SP_, substr($this->dicStr, $this->mainDic[$nc][2][0], $this->mainDic[$nc][2][1]) );
              $klen = count($strs);
              for($k=0; $k < $klen; $k++)
              {
                  $this->mainDic[$nc][1][$strs[$k]] = $strs[$k+1];
                  //$this->mainDic[$nc][1][$strs[$k]] = explode(',', $strs[$k+1]);
                  $k = $k+1;
              }
         }
         return true;
    }
    
    /**
     * ���ĳ���ʵĴ��Լ���Ƶ��Ϣ
     * @parem $word unicode����Ĵ�
     * @return void
     */
     function GetWordProperty($word)
     {
        if( strlen($word)<4 )
        {
            return '/s';
        }
        $infos = $this->GetWordInfos($word);
        return isset($infos['m']) ? "/{$infos['m']}{$infos['c']}" : "/s";
     }
    
    /**
     * ָ��ĳ�ʵĴ�����Ϣ��ͨ�����´ʣ�
     * @parem $word unicode����Ĵ�
     * @parem $infos array('c' => ��Ƶ, 'm' => ����);
     * @return void;
     */
    function SetWordInfos($word, $infos)
    {
        if( strlen($word)<4 )
        {
            return ;
        }
        if( isset($this->mainDicInfos[$word]) )
        {
            $this->newWords[$word]++;
            $this->mainDicInfos[$word]['c']++;
        }
        else
        {
            $this->newWords[$word] = 1;
            $this->mainDicInfos[$word] = $infos;
        }
    }
    
    /**
     * �Ӵʵ��ļ���ָ���ʵ���Ϣ
     * @parem $word unicode����Ĵ�
     * @return array('c' => ��Ƶ, 'm' => ����);
     */
    function GetWordInfos($word)
    {
        $rearr = '';
        if( strlen($word) < 4 )
        {
            return $rearr;
        }
        //��黺������
        if( isset($this->mainDicInfos[$word]) )
        {
            return $this->mainDicInfos[$word];
        }
        //����
        $wfoot = $this->GetWord($word);
        $whead = substr($word, strlen($word)-2, 2);
        if( !$this->IsWordEnd($whead) || !isset($this->mainDic[$whead][1][$wfoot]) )
        {
            return $rearr;
        }
        if( is_array($this->mainDic[$whead][1][$wfoot]) )
        {
            $rearr['c'] = $this->mainDic[$whead][1][$wfoot][0];
            $rearr['m'] = $this->mainDic[$whead][1][$wfoot][1];
        }
        else
        {
            $strs = explode(_SP2_, $this->mainDic[$whead][1][$wfoot]);
            $rearr['c'] = $strs[0];
            $rearr['m'] = $strs[1];
        }
        //���浽��������
        $this->mainDicInfos[$word] = $rearr;
        return $rearr;
    }
    
    /**
     * ��ʼִ�з���
     * @parem bool optimize �Ƿ�Խ�������Ż�
     * @return bool
     */
    function StartAnalysis($optimize=true)
    {
        if( !$this->isLoadDic )
        {
            $this->LoadDict();
        }
        $this->simpleResult = $this->finallyResult = array();
        $this->sourceString .= chr(0).chr(32);
        $slen = strlen($this->sourceString);
        $sbcArr = array();
        $j = 0;
        //ȫ�������ַ����ձ�
        for($i=0xFF00; $i < 0xFF5F; $i++)
        {
            $scb = 0x20 + $j;
            $j++;
            $sbcArr[$i] = $scb;
        }
        //���ַ������дַ�
        $onstr = '';
        $lastc = 1; //1 ��/��/����, 2 Ӣ��/����/����('.', '@', '#', '+'), 3 ANSI���� 4 ������ 5 ��ANSI���Ż�֧���ַ�
        $s = 0;
        $ansiWordMatch = "[0-9a-z@#%\+\.-]";
        $notNumberMatch = "[a-z@#%\+]";
        $nameLink = 0xB7;
        for($i=0; $i < $slen; $i++)
        {
            $c = $this->sourceString[$i].$this->sourceString[++$i];
            $cn = hexdec(bin2hex($c));
            $cn = isset($sbcArr[$cn]) ? $sbcArr[$cn] : $cn;
            //ANSI�ַ�
            if($cn < 0x80)
            {
                if( preg_match('/'.$ansiWordMatch.'/i', chr($cn)) )
                {
                    if( $lastc != 2 && $onstr != '') {
                        $this->simpleResult[$s]['w'] = $onstr;
                        $this->simpleResult[$s]['t'] = $lastc;
                        $this->DeepAnalysis($onstr, $lastc, $s, $optimize);
                        $s++;
                        $onstr = '';
                    }
                    $lastc = 2;
                    $onstr .= chr(0).chr($cn);
                }
                else
                {
                    if( $onstr != '' )
                    {
                        $this->simpleResult[$s]['w'] = $onstr;
                        if( $lastc==2 )
                        {
                            if( !preg_match('/'.$notNumberMatch.'/i', iconv('ucs-2', 'utf-8', $onstr)) ) $lastc = 4;
                        }
                        $this->simpleResult[$s]['t'] = $lastc;
                        if( $lastc != 4 ) $this->DeepAnalysis($onstr, $lastc, $s, $optimize);
                        $s++;
                    }
                    $onstr = '';
                    $lastc = 3;
                    if($cn < 31)
                    {
                        continue;
                    }
                    else
                    {
                        $this->simpleResult[$s]['w'] = chr(0).chr($cn);
                        $this->simpleResult[$s]['t'] = 3;
                        $s++;
                    }
                }
            }
            //��ͨ�ַ�
            else
            {
                //�������� $cn==$nameLink || 
                if( ($cn>0x3FFF && $cn < 0x9FA6) || ($cn>0xF8FF && $cn < 0xFA2D)
                    || ($cn>0xABFF && $cn < 0xD7A4) || ($cn>0x3040 && $cn < 0x312B) )
                {
                    if( $lastc != 1 && $onstr != '')
                    {
                        $this->simpleResult[$s]['w'] = $onstr;
                        if( $lastc==2 )
                        {
                            if( !preg_match('/'.$notNumberMatch.'/i', iconv('ucs-2', 'utf-8', $onstr)) ) $lastc = 4;
                        }
                        $this->simpleResult[$s]['t'] = $lastc;
                        if( $lastc != 4 ) $this->DeepAnalysis($onstr, $lastc, $s, $optimize);
                        $s++;
                        $onstr = '';
                    }
                    $lastc = 1;
                    $onstr .= $c;
                }
                //�������
                else
                {
                    if( $onstr != '' )
                    {
                        $this->simpleResult[$s]['w'] = $onstr;
                        if( $lastc==2 )
                        {
                            if( !preg_match('/'.$notNumberMatch.'/i', iconv('ucs-2', 'utf-8', $onstr)) ) $lastc = 4;
                        }
                        $this->simpleResult[$s]['t'] = $lastc;
                        if( $lastc != 4 ) $this->DeepAnalysis($onstr, $lastc, $s, $optimize);
                        $s++;
                    }
                    
                    //�������
                    if( $cn == 0x300A )
                    {
                        $tmpw = '';
                        $n = 1;
                        $isok = false;
                        $ew = chr(0x30).chr(0x0B);
                        while(true)
                        {
                            $w = $this->sourceString[$i+$n].$this->sourceString[$i+$n+1];
                            if( $w == $ew )
                            {
                                $this->simpleResult[$s]['w'] = $c;
                                $this->simpleResult[$s]['t'] = 5;
                                $s++;
                        
                                $this->simpleResult[$s]['w'] = $tmpw;
                                $this->newWords[$tmpw] = 1;
                                if( !isset($this->newWords[$tmpw]) )
                                {
                                    $this->foundWordStr .= $this->OutStringEncoding($tmpw).'/nb, ';
                                    $this->SetWordInfos($tmpw, array('c'=>1, 'm'=>'nb'));
                                }
                                $this->simpleResult[$s]['t'] = 13;
                                
                                $s++;

                                //����з�ģʽ�����������ִ�
                                if( $this->differMax )
                                {
                                    $this->simpleResult[$s]['w'] = $tmpw;
                                    $this->simpleResult[$s]['t'] = 21;
                                    $this->DeepAnalysis($tmpw, $lastc, $s, $optimize);
                                    $s++;
                                }
                                
                                $this->simpleResult[$s]['w'] = $ew;
                                $this->simpleResult[$s]['t'] =  5;
                                $s++;
                        
                                $i = $i + $n + 1;
                                $isok = true;
                                $onstr = '';
                                $lastc = 5;
                                break;
                            }
                            else
                            {
                                $n = $n+2;
                                $tmpw .= $w;
                                if( strlen($tmpw) > 60 )
                                {
                                    break;
                                }
                            }
                        }//while
                        if( !$isok )
                        {
                            $this->simpleResult[$s]['w'] = $c;
              	            $this->simpleResult[$s]['t'] = 5;
              	            $s++;
              	            $onstr = '';
                            $lastc = 5;
                        }
                        continue;
                    }
                    
                    $onstr = '';
                    $lastc = 5;
                    if( $cn==0x3000 )
                    {
                        continue;
                    }
                    else
                    {
                        $this->simpleResult[$s]['w'] = $c;
                        $this->simpleResult[$s]['t'] = 5;
                        $s++;
                    }
                }//2byte symbol
                
            }//end 2byte char
        
        }//end for
        
        //����ִʺ�Ľ��
        $this->SortFinallyResult();
    }
    
    /**
     * ����ִ�
     * @parem $str
     * @parem $ctype (2 Ӣ���࣬ 3 ��/��/������)
     * @parem $spos   ��ǰ�ַֽ���α�
     * @return bool
     */
    function DeepAnalysis( &$str, $ctype, $spos, $optimize=true )
    {

        //���ľ���
        if( $ctype==1 )
        {
            $slen = strlen($str);
            //С��ϵͳ���÷ִ�Ҫ�󳤶ȵľ���
            if( $slen < $this->notSplitLen )
            {
                $tmpstr = '';
                $lastType = 0;
                if( $spos > 0 ) $lastType = $this->simpleResult[$spos-1]['t'];
                if($slen < 5)
                {
                	  //echo iconv('ucs-2', 'utf-8', $str).'<br/>';
                	  if( $lastType==4 && ( isset($this->addonDic['u'][$str]) || isset($this->addonDic['u'][substr($str, 0, 2)]) ) )
        						{
             					 $str2 = '';
             					 if( !isset($this->addonDic['u'][$str]) && isset($this->addonDic['s'][substr($str, 2, 2)]) )
             					 {
             					    $str2 = substr($str, 2, 2);
             					    $str  = substr($str, 0, 2);
             					 }
             					 $ww = $this->simpleResult[$spos - 1]['w'].$str;
             					 $this->simpleResult[$spos - 1]['w'] = $ww;
             					 $this->simpleResult[$spos - 1]['t'] = 4;
             					 if( !isset($this->newWords[$this->simpleResult[$spos - 1]['w']]) )
                       {
             					    $this->foundWordStr .= $this->OutStringEncoding( $ww ).'/mu, ';
             					    $this->SetWordInfos($ww, array('c'=>1, 'm'=>'mu'));
             					 }
             					 $this->simpleResult[$spos]['w'] = '';
             					 if( $str2 != '' )
             					 {
             					    $this->finallyResult[$spos-1][] = $ww;
             					    $this->finallyResult[$spos-1][] = $str2;
             					 }
       							}
       							else {
       								 $this->finallyResult[$spos][] = $str;
       							}
                }
                else
                {
                	  $this->DeepAnalysisChinese( $str, $ctype, $spos, $slen, $optimize );
                }
            }
            //�������ȵľ��ӣ�ѭ�����зִʴ���
            else
            {
                $this->DeepAnalysisChinese( $str, $ctype, $spos, $slen, $optimize );
            }
        }
        //Ӣ�ľ��ӣ�תΪСд
        else
        {
            if( $this->toLower ) {
                $this->finallyResult[$spos][] = strtolower($str);
            }
            else {
                $this->finallyResult[$spos][] = $str;
            }
        }
    }
    
    /**
     * ���ĵ�����ִ�
     * @parem $str
     * @return void
     */
    function DeepAnalysisChinese( &$str, $lastec, $spos, $slen, $optimize=true )
    {
        $quote1 = chr(0x20).chr(0x1C);
        $tmparr = array();
        $hasw = 0;
        //���ǰһ����Ϊ �� �� �����ַ���С��3���ַ�����һ���ʴ���
        if( $spos > 0 && $slen < 11 && $this->simpleResult[$spos-1]['w']==$quote1 )
        {
            $tmparr[] = $str;
            if( !isset($this->newWords[$str]) )
            {
                $this->foundWordStr .= $this->OutStringEncoding($str).'/nq, ';
                $this->SetWordInfos($str, array('c'=>1, 'm'=>'nq'));
            }
            if( !$this->differMax )
            {
                $this->finallyResult[$spos][] = $str;
                return ;
            }
        }
        //�����з�
        for($i=$slen-1; $i>0; $i--)
        {
            $nc = $str[$i-1].$str[$i];
            if($i<2)
            {
                $tmparr[] = $nc;
                $i = 0;
                break;
            }
            if( $this->IsWordEnd($nc) )
            {
                $i = $i - 1;
                $isok = false;
                for($k=12; $k>1; $k=$k-2)
                {
                    //if($i < $k || $this->mainDic[$nc][0][$k]==0) continue;
                    if($i < $k) continue;
                    $w = substr($str, $i-$k, $k);
                    if( isset($this->mainDic[$nc][1][$w]) )
                    {
                        $tmparr[] = $w.$nc;
                        $i = $i - $k;
                        $isok = true;
                        break;
                    }
                }
                if(!$isok)
                {
                   $tmparr[] = $nc;
                }
            }
            else
            {
               $tmparr[] = $nc;
               $i = $i - 1;
            }
        }
        if(count($tmparr)==0) return ;
        for($i=count($tmparr)-1; $i>=0; $i--)
        {
            $this->finallyResult[$spos][] = $tmparr[$i];
        }
        //�Ż����(��崦���´ʡ����ʡ�����ʶ���)
        if( $optimize )
        {
            $this->OptimizeResult( $this->finallyResult[$spos], $spos );
        }
    }
    
    /**
    * �����շִʽ�������Ż�����simpleresult����ϲ����������´�ʶ�����ʺϲ��ȣ�
    * @parem $optimize �Ƿ��Ż��ϲ��Ľ��
    * @return bool
    */
    //t = 1 ��/��/����, 2 Ӣ��/����/����('.', '@', '#', '+'), 3 ANSI���� 4 ������ 5 ��ANSI���Ż�֧���ַ�
    function OptimizeResult( &$smarr, $spos )
    {
        $newarr = array();
        $prePos = $spos - 1;
        $arlen = count($smarr);
        $i = $j = 0;
        //���������
        if( $prePos > -1 && !isset($this->finallyResult[$prePos]) )
        {
            $lastw = $this->simpleResult[$prePos]['w'];
            $lastt = $this->simpleResult[$prePos]['t'];
        	  if( ($lastt==4 || isset( $this->addonDic['c'][$lastw] )) && isset( $this->addonDic['u'][$smarr[0]] ) )
        	  {
               $this->simpleResult[$prePos]['w'] = $lastw.$smarr[0];
               $this->simpleResult[$prePos]['t'] = 4;
               if( !isset($this->newWords[ $this->simpleResult[$prePos]['w'] ]) )
               {
                    $this->foundWordStr .= $this->OutStringEncoding( $this->simpleResult[$prePos]['w'] ).'/mu, ';
                    $this->SetWordInfos($this->simpleResult[$prePos]['w'], array('c'=>1, 'm'=>'mu'));
               }
               $smarr[0] = '';
               $i++;
       		  }
       }
       for(; $i < $arlen; $i++)
       {
            
            if( !isset( $smarr[$i+1] ) )
            {
                $newarr[$j] = $smarr[$i];
                break;
            }
            $cw = $smarr[$i];
            $nw = $smarr[$i+1];
            $ischeck = false;
            //���������
            if( isset( $this->addonDic['c'][$cw] ) && isset( $this->addonDic['u'][$nw] ) )
            {
                //����з�ʱ�����ϲ�ǰ�Ĵ�
                if($this->differMax)
                {
                        $newarr[$j] = chr(0).chr(0x28);
                        $j++;
                        $newarr[$j] = $cw;
                        $j++;
                        $newarr[$j] = $nw;
                        $j++;
                        $newarr[$j] = chr(0).chr(0x29);
                        $j++;
                }
                $newarr[$j] = $cw.$nw;
                if( !isset($this->newWords[$newarr[$j]]) )
                {
                    $this->foundWordStr .= $this->OutStringEncoding( $newarr[$j] ).'/mu, ';
                    $this->SetWordInfos($newarr[$j], array('c'=>1, 'm'=>'mu'));
                }
                $j++; $i++; $ischeck = true;
            }
            //���ǰ����(ͨ������)
            else if( isset( $this->addonDic['n'][ $smarr[$i] ] ) )
            {
                $is_rs = false;
                //�����Ǹ��ʻ��ʻ�Ƶ�ʺܸߵĴʲ���Ϊ����
                if( strlen($nw)==4 )
                {
                    $winfos = $this->GetWordInfos($nw);
                    if(isset($winfos['m']) && ($winfos['m']=='r' || $winfos['m']=='c' || $winfos['c']>500) )
                    {
                    	 $is_rs = true;
                    }
                }
                if( !isset($this->addonDic['s'][$nw]) && strlen($nw)<5 && !$is_rs )
                {
                    //����з�ʱ�����ϲ�ǰ������
                    if($this->differMax)
                    {
                            $newarr[$j] = chr(0).chr(0x28);
                            $j++;
                            $newarr[$j] = $cw;
                            $j++;
                            $nj = $j;
                            $newarr[$j] = $nw;
                            $j++;
                            $newarr[$j] = chr(0).chr(0x29);
                            $j++;
                    }
                    $newarr[$j] = $cw.$nw;
                    //echo iconv('ucs-2', 'utf-8', $newarr[$j])."<br />";
                    //���Լ���������
                    if( strlen($nw)==2 && isset($smarr[$i+2]) && strlen($smarr[$i+2])==2 && !isset( $this->addonDic['s'][$smarr[$i+2]] ) )
                    {
                        $newarr[$j] .= $smarr[$i+2];
                        if($this->differMax)
                        {
                            $newarr[$nj] .= $smarr[$i+2];
                        }
                        $i++;
                    }
                    if( !isset($this->newWords[$newarr[$j]]) )
                    {
                        $this->SetWordInfos($newarr[$j], array('c'=>1, 'm'=>'nr'));
                        $this->foundWordStr .= $this->OutStringEncoding($newarr[$j]).'/nr, ';
                    }
                    $j++; $i++; $ischeck = true;
                }
            }
            //����׺��(������)
            else if( isset($this->addonDic['a'][$nw]) )
            {
                $is_rs = false;
                //�����Ǹ��ʻ��ʲ���Ϊǰ׺
                if( strlen($cw)>2 )
                {
                    $winfos = $this->GetWordInfos($cw);
                    if(isset($winfos['m']) && ($winfos['m']=='a' || $winfos['m']=='r' || $winfos['m']=='c' || $winfos['c']>500) )
                    {
                    	 $is_rs = true;
                    }
                }
                if( !isset($this->addonDic['s'][$cw]) && !$is_rs )
                {
                    //����з�ʱ�����ϲ�ǰ�Ĵ�
                    if($this->differMax)
                    {
                        $newarr[$j] = chr(0).chr(0x28);
                        $j++;
                        $newarr[$j] = $cw;
                        $j++;
                        $newarr[$j] = $nw;
                        $j++;
                        $newarr[$j] = chr(0).chr(0x29);
                        $j++;
                    }
                    $newarr[$j] = $cw.$nw;
                    if( !isset($this->newWords[$newarr[$j]]) )
                    {
                        $this->foundWordStr .= $this->OutStringEncoding($newarr[$j]).'/na, ';
                        $this->SetWordInfos($newarr[$j], array('c'=>1, 'm'=>'na'));
                    }
                    $i++; $j++; $ischeck = true;
                }
            }
            //�´�ʶ�����޹���
            else if($this->unitWord)
            {
                if(strlen($cw)==2 && strlen($nw)==2 
                && !isset($this->addonDic['s'][$cw]) && !isset($this->addonDic['t'][$cw]) && !isset($this->addonDic['a'][$cw]) 
                && !isset($this->addonDic['s'][$nw]) && !isset($this->addonDic['c'][$nw]))
                {
                    //����з�ʱ�����ϲ�ǰ�Ĵ�
                    if($this->differMax)
                    {
                        $newarr[$j] = chr(0).chr(0x28);
                        $j++;
                        $newarr[$j] = $cw;
                        $j++;
                        $nj = $j;
                        $newarr[$j] = $nw;
                        $j++;
                        $newarr[$j] = chr(0).chr(0x29);
                        $j++;
                    }
                    $newarr[$j] = $cw.$nw;
                    $wf = $nw;
                    //���Լ���������
                    if( isset($smarr[$i+2]) && strlen($smarr[$i+2])==2 && (isset( $this->addonDic['a'][$smarr[$i+2]] ) || isset( $this->addonDic['u'][$smarr[$i+2]] )) )
                    {
                        $newarr[$j] .= $smarr[$i+2];
                        $newarr[$nj] .= $smarr[$i+2];
                        $i++;
                    }
                    if( !isset($this->newWords[$newarr[$j]]) )
                    {
                        $this->foundWordStr .= $this->OutStringEncoding($newarr[$j]).'/ms, ';
                        $this->SetWordInfos($newarr[$j], array('c'=>1, 'm'=>'ms'));
                    }
                    $i++; $j++; $ischeck = true;
                }
            }
            
            //�����Ϲ���
            if( !$ischeck )
            {
                $newarr[$j] = $cw;
              	//��Ԫ��᪴���������з�ģʽ
                if( $this->differMax && !isset($this->addonDic['s'][$cw]) && strlen($cw) < 5 && strlen($nw) < 7)
                {
                    $slen = strlen($nw);
                    $hasDiff = false;
                    for($y=2; $y <= $slen-2; $y=$y+2)
                    {
                        $nhead = substr($nw, $y-2, 2);
                        $nfont = $cw.substr($nw, 0, $y-2);
                        if( $this->IsWordEnd($nhead) && isset( $this->mainDic[$nhead][1][$nfont] ) )
                        {
                            if( strlen($cw) > 2 ) $j++;
                            $hasDiff = true;
                            $newarr[$j] = $nfont.$nhead;
                        }
                    }
                }
                $j++;
            }
            
       }//end for
       $smarr =  $newarr;
    }
    
    /**
    * ת�����շִʽ���� finallyResult ����
    * @return void
    */
    function SortFinallyResult()
    {
    	  $newarr = array();
        $i = 0;
        foreach($this->simpleResult as $k=>$v)
        {
            if( empty($v['w']) ) continue;
            if( isset($this->finallyResult[$k]) && count($this->finallyResult[$k]) > 0 )
            {
                foreach($this->finallyResult[$k] as $w)
                {
                    if(!empty($w))
                    {
                    	$newarr[$i]['w'] = $w;
                    	$newarr[$i]['t'] = 20;
                    	$i++;
                    }
                }
            }
            else if($v['t'] != 21)
            {
                $newarr[$i]['w'] = $v['w'];
                $newarr[$i]['t'] = $v['t'];
                $i++;
            }
        }
        $this->finallyResult = $newarr;
        $newarr = '';
  	}
    
    /**
     * ��uncode�ַ���ת��Ϊ����ַ���
     * @parem str
     * return string
     */
     function OutStringEncoding( &$str )
     {
        $rsc = $this->SourceResultCharset();
        if( $rsc==1 ) {
            $rsstr = iconv('ucs-2', 'utf-8', $str);
        }
        else if( $rsc==2 ) {
            $rsstr = iconv('utf-8', 'gb18030', iconv('ucs-2', 'utf-8', $str) );
        }
        else{
            $rsstr = iconv('utf-8', 'big5', iconv('ucs-2', 'utf-8', $str) );
        }
        return $rsstr;
     }
    
    /**
     * ��ȡ���ս���ַ������ÿո�ֿ���ķִʽ����
     * @return string
     */
     function GetFinallyResult($spword=' ', $word_meanings=false)
     {
        $rsstr = '';
        foreach($this->finallyResult as $v)
        {
            if( $this->resultType==2 && ($v['t']==3 || $v['t']==5) )
            {
            	continue;
            }
            $m = '';
            if( $word_meanings )
            {
                $m = $this->GetWordProperty($v['w']);
            }
            $w = $this->OutStringEncoding($v['w']);
            if( $w != ' ' )
            {
                if($word_meanings) {
                    $rsstr .= $spword.$w.$m;
                }
                else {
                    $rsstr .= $spword.$w;
                }
            }
        }
        return $rsstr;
     }
     
    /**
     * ��ȡ�ַֽ�����������ַ�����
     * @return array()
     */
     function GetSimpleResult()
     {
        $rearr = array();
        foreach($this->simpleResult as $k=>$v)
        {
            if( empty($v['w']) ) continue;
            $w = $this->OutStringEncoding($v['w']);
            if( $w != ' ' ) $rearr[] = $w;
        }
        return $rearr;
     }
     
    /**
     * ��ȡ�ַֽ���������ַ����ԣ�1���Ĵʾ䡢2 ANSI�ʻ㣨����ȫ�ǣ���3 ANSI�����ţ�����ȫ�ǣ���4���֣�����ȫ�ǣ���5 ���ı����޷�ʶ���ַ���
     * @return array()
     */
     function GetSimpleResultAll()
     {
        $rearr = array();
        foreach($this->simpleResult as $k=>$v)
        {
            $w = $this->OutStringEncoding($v['w']);
            if( $w != ' ' )
            {
                $rearr[$k]['w'] = $w;
                $rearr[$k]['t'] = $v['t'];
            }
        }
        return $rearr;
     }
     
    /**
     * ��ȡ����hash����
     * @return array('word'=>count,...)
     */
     function GetFinallyIndex()
     {
        $rearr = array();
        foreach($this->finallyResult as $v)
        {
            if( $this->resultType==2 && ($v['t']==3 || $v['t']==5 || isset($this->addonDic['s'][$v['w']]) ) )
            {
            	continue;
            }
            $w = $this->OutStringEncoding($v['w']);
            if( $w == ' ' || $w == '(' || $w == ')' )
            {
                continue;
            }
            if( isset($rearr[$w]) )
            {
            	 $rearr[$w]++;
            }
            else
            {
            	 $rearr[$w] = 1;
            }
        }
        arsort($rearr);
        return $rearr;
     }
     
    /**
     * ��ñ���Ŀ�����
     * @return int
     */
     function SourceResultCharset()
     {
        if( preg_match("/^utf/", $this->targetCharSet) ) {
           $rs = 1;
        }
        else if( preg_match("/^gb/", $this->targetCharSet) ) {
           $rs = 2;
        }
        else if( preg_match("/^big/", $this->targetCharSet) ) {
           $rs = 3;
        }
        else {
            $rs = 4;
        }
        return $rs;
     }
     
     /**
     * �����ʵ�Ĵ���
     * @parem $targetfile ����λ��
     * @return void
     */
     function ExportDict( $targetfile )
     {
        $fp = fopen($targetfile, 'w');
        foreach($this->mainDic as $k=>$v)
        {
            $ik = iconv('ucs-2', 'utf-8', $k);
            foreach( $v[1] as $wk => $wv)
            {
                $arr = $this->GetWordInfos($wk.$k);
                $wd = iconv('ucs-2', 'utf-8', $wk).$ik;
                if($arr != '')
                {
                    fwrite($fp, $wd.','.$arr['c'].','.$arr['m']."\n");
                }
                else
                {
                    continue;
                }
            }
        }
        fclose($fp);
     }
     
     /**
     * ׷���´ʵ��ڴ���Ĵʵ�
     * @parem $word unicode����Ĵ�
     * @return void
     */
     function AddNewWord( $word )
     {
        
     }
     
     /**
     * ����ʵ�
     * @parem $sourcefile utf-8������ı��ʵ������ļ�<�μ�����dict/not-build/base_dic_full.txt>
     * @return void
     */
     function MakeDict( $sourcefile, $maxWordLen=16, $target='' )
     {
        if( $target=='' )
        {
            $dicWords = dirname(__FILE__).'/'.$this->mainDicFile;
        }
        else
        {
            $dicWords = dirname(__FILE__).'/'.$target;
        }
        $narr = $earr = array();
        if( !file_exists($sourcefile) )
        {
            echo 'File: '.$sourcefile.' not found!';
            return ;
        }
        $ds = file($sourcefile);
        $i = 0;
        $maxlen = 0;
        foreach($ds as $d)
        {
            $d = trim($d);
            if($d=='') continue;
            list($d, $hot, $mtype) = explode(',', $d);
            if( empty($hot) ) $hot = 0;
            if( empty($mtype) ) $mtype = 'x';
            if( $mtype=='@' ) continue;
            $d = iconv('utf-8', 'ucs-2', $d);
            /*������ANSI���
            if( strlen($mtype)==1 )
            {
                $mtype = chr(0).$mtype;
            }*/
            $nlength = strlen($d)-2;
            if( $nlength >= $maxWordLen ) continue;
            $maxlen = $nlength > $maxlen ? $nlength : $maxlen;
            $endc = substr($d, $nlength, 2);
            $n = hexdec(bin2hex($endc));
            if( isset($narr[$endc]) )
            {
                $narr[$endc]['w'][$narr[$endc]['c']] = $this->GetWord($d);
                $narr[$endc]['n'][$narr[$endc]['c']] = $hot;
                $narr[$endc]['m'][$narr[$endc]['c']] = $mtype;
                $narr[$endc]['l'] += $nlength;
                $narr[$endc]['c']++;
                $narr[$endc]['h'][$nlength] = isset($narr[$endc]['h'][$nlength]) ? $narr[$endc]['h'][$nlength]+1 : 1;
            }
            else
            {
                $narr[$endc]['w'][0] = $this->GetWord($d);
                $narr[$endc]['n'][0] = $hot;
                $narr[$endc]['m'][0] = $mtype;
                $narr[$endc]['l'] = $nlength;
                $narr[$endc]['c'] = 1;
                $narr[$endc]['h'][$nlength] = 1;
            }
        }
        $alllen = $n = $max = $bigw = $bigc = 0;
        $fp = fopen($dicWords, 'wb');
        foreach($narr as $k=>$v)
        {
            fwrite($fp, $k);
            /* ����ָ�����ȵĴ���������Ϣ������������ִ��ٶȲ����ԣ�����Ӱ������ʱ�䣩
            for($i=2; $i <= 12; $i = $i+2)
            {
                if( empty($v['h'][$i]) ) {
                    fwrite($fp, chr(0).chr(0));
                }
                else {
                    fwrite($fp, pack('n', $v['h'][$i]));
                }
            }*/
            $allstr = '';
            foreach($v['w']  as $n=>$w)
            {
                //$hot = pack('n', $narr[$k]['n'][$n]);
                $hot = $narr[$k]['n'][$n];
                $mtype = $narr[$k]['m'][$n];
                $allstr .= ($allstr=='' ? $w._SP_.$hot._SP2_.$mtype : _SP_.$w._SP_.$hot._SP2_.$mtype);
            }
            $alLen = strlen($allstr);
            $max = $alLen > $max ? $alLen : $max;
            fwrite($fp, pack('n', $alLen) );
            fwrite($fp, $allstr);
        }
        fclose($fp);
     }
     
     /**
     * ��ôʵ�ǰ����
     * @parem $str ����
     * @return void
     */
     function GetWord($str)
     {
        $newstr = '';
        for($i=0; $i < strlen($str)-3; $i++)
        {
            $newstr .= $str[$i];
            $newstr .= $str[$i+1];
            $i++;
        }
        return $newstr;
     }
    
}

?>