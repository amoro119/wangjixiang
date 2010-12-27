<?php
require_once (dirname(__FILE__) . "/include/common.inc.php");
require_once DEDEINC."/arc.partview.class.php";
$pv = new PartView();
$pv->SetTemplet($cfg_basedir . $cfg_templets_dir ."/" . $cfg_df_style . "/index.htm");
$pv->Display();
?>