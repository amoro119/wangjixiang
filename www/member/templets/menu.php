    <?php
        $add_channel_menu = array();
        //���Ϊ�οͷ��ʣ����������˵�
        if(!empty($cfg_ml->M_ID))
        {
            $channelInfos = array();
            $dsql->Execute('addmod',"SELECT id,nid,typename,useraddcon,usermancon,issend,issystem,usertype,isshow FROM `#@__channeltype` ");	
            while($menurow = $dsql->GetArray('addmod'))
            {
                $channelInfos[$menurow['nid']] = $menurow;
                //���õ�ģ��
                if($menurow['isshow']==0)
                {
                    continue;
                }
                //�������
                if($menurow['issend']!=1 || $menurow['issystem']==1 
                || ( !ereg($cfg_ml->M_MbType, $menurow['usertype']) && trim($menurow['usertype'])!='' ) )
                {
                    continue;
                }
                $menurow['ddcon'] = empty($menurow['useraddcon']) ? 'archives_add.php' : $menurow['useraddcon'];
                $menurow['list'] = empty($menurow['usermancon']) ? 'content_list.php' : $menurow['usermancon'];
                $add_channel_menu[] = $menurow;
            }
            unset($menurow);
		?>
    <div id="mcpsub">
      <div class="topGr"></div>
      <div id="menuBody">
      	<!-- �������Ĳ˵�-->
      	<?php
      	if($menutype == 'content')
      	{
      	?>
        <h2 class="menuTitle" onclick="menuShow('menuFirst')" id="menuFirst_t"><b></b>ϵͳģ������</h2>
        <ul id="menuFirst">
        <?php
        //�Ƿ���������Ͷ��
        if($channelInfos['article']['issend']==1 && $channelInfos['article']['isshow']==1)
        {
        ?>
          <li class="articles"><a href="../member/content_list.php?channelid=1" title="�ѷ���������"><b></b>����</a><a href="../member/article_add.php" class="act" title="����������">����</a></li>
        <?php
      	}
        //�Ƿ�����ͼ��Ͷ��
        if($channelInfos['image']['issend']==1 && $cfg_mb_album=='Y'  && $channelInfos['image']['isshow']==1 
        && ($channelInfos['image']['usertype']=='' || ereg($cfg_ml->fields['mtype'], $channelInfos['image']['usertype'])) )
        {
        ?>
          
          
          <li class="photo"><a href="../member/content_list.php?channelid=2" title="����ͼ��"><b></b>ͼ��</a><a href="../member/album_add.php" class="act" title="�½�ͼ��">�½�</a></li>
	        <?php
	      	}
	      	//�Ƿ��������Ͷ��
	        if($channelInfos['soft']['issend']==1 && $channelInfos['soft']['isshow']==1
	        && ($channelInfos['image']['usertype']=='' || ereg($cfg_ml->fields['mtype'], $channelInfos['image']['usertype']))
	        )
	        {
	        ?>
          <li class="soft"><a href="../member/content_list.php?channelid=3" title="�ѷ��������"><b></b>���</a><a href="../member/soft_add.php" title="�ϴ����"class="act">�ϴ�</a></li>
          <?php
           }
           ?>
        </ul>
				<?php
				//�Ƿ�������Զ���ģ��Ͷ��
				if($cfg_mb_sendall=='Y')
				{
				?>
        <h2 class="menuTitle" onclick="menuShow('menuSec')" id="menuSec_t"><b></b>�Զ�������</h2>
        <ul id="menuSec">
					<?php
					foreach($add_channel_menu as $nnarr) {
					?>
					<li class="<?php echo $nnarr['nid'];?>"><a href="../member/<?php echo $nnarr['list'];?>?channelid=<?php echo $nnarr['id'];?>" title="�ѷ�����<?php echo $nnarr['typename'];?>"><b></b><?php echo $nnarr['typename'];?></a><a href='archives_do.php?dopost=addArc&channelid=<?php echo $nnarr['id'];?>' class="act" title="����������">����</a></li>
					<?php
					} 
					}
					?>  
        </ul>
        <h2 class="menuTitle" onclick="menuShow('menuThird')" id="menuThird_t"><b></b>��������</h2>
        <ul id="menuThird">
        	<li class="icon attachment"><a href="../member/uploads.php"><b></b>��������</a></li>
        </ul>
        
        <?php
      }
      ?>
      	<!-- �ҵ�֯�β˵�-->
      	<?php
      	if($menutype == 'mydede')
      	{
      	?>
        <h2 class="menuTitle" onclick="menuShow('menuFirst')" id="menuFirst_t"><b></b>��Ա����</h2>
        <ul id="menuFirst">
        	<li class="icon mystow"><a href="../member/mystow.php"><b></b>�ҵ��ղؼ�</a></li>
        <?php
        if($cfg_feedback_forbid=='N')
        {
          //<li class="icon feedback"><a href='../member/myfeedback.php'>�ҵ�����</a></li>
        }
        $dsql->Execute('nn','Select indexname,indexurl From `#@__sys_module` where ismember=1 ');
        while($nnarr = $dsql->GetArray('nn'))
        {
        	@preg_match("/\/(.+?)\//is", $nnarr['indexurl'],$matches);
        	$nnarr['class'] = isset($matches[1]) ? $matches[1] : 'channel';
        ?>
        <li class="<?php echo $nnarr['class'];?>"><a href="<?php echo $nnarr['indexurl']; ?>"><b></b><?php echo $nnarr['indexname']; ?>ģ��</a></li>
        <?php
        }
        ?>
        </ul>
        <?php
      }
      ?>
      	<!-- ϵͳ���ò˵�-->
      	<?php
      	if($menutype == 'config')
      	{
      	?>
        <h2 class="menuTitle" onclick="menuShow('menuFirst')" id="menuFirst_t"><b></b><?php echo $cfg_ml->M_MbType; ?>����</a></h2>
        <ul id="menuFirst">
        	<li class="icon baseinfo"><a href="../member/edit_baseinfo.php"><b></b>��������</a></li>
	        <li class="icon myinfo"><a href="../member/edit_fullinfo.php"><b></b><?php echo $cfg_ml->M_MbType; ?>����</a></li>
	        <li class="icon face"><a href="../member/edit_face.php"><b></b>ͷ������</a></li>
        </ul>
        <h2 class="menuTitle" onclick="menuShow('menuSec')" id="menuSec_t"><b></b>�ռ����</h2>
        <ul id="menuSec">
        	<li class="icon mtypes"><a href="../member/mtypes.php"><b></b>�������</a></li>
        	<li class="icon flink"><a href="../member/flink_main.php"><b></b>��ǩ����</a></li>
        	<li class="icon info"><a href="../member/edit_space_info.php"><b></b>�ռ�����</a></li>
        	<li class="icon spaceskin"><a href="../member/spaceskin.php"><b></b>���ѡ��</a></li>
        </ul>
        
        <?php
      }
      ?>
        <!--<h2 class="menuTitle"><b class="showMenu"></b>�������˵���</h2> -->
      </div>
      <div class="buttomGr"></div>
    </div>
    <?php
    }
    ?>
    <!--�������˵��� -->