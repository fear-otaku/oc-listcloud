<?php

/*
Plugin Name: Listing Tag Cloud
Plugin URI: https://github.com/fear-otaku/oc-listcloud
Description: This Plugin Shows a Tag Cloud based on your listing descriptions.
Version: 1.0
Author: Fear-Otaku Software
Author URI: http://www.fear-otaku.com/
Short Name: Listcloud
*/

require_once "cloud.php";

function listcloud_CloudDump() {
    require_once LIB_PATH . 'osclass/classes/Cache.php';

    $cache = new Cache('listcloud_feeds', 900);
    if ($cache->check()) {
        return $cache->retrieve();
    } else {
        $list = array();

        $content = osc_file_get_contents(osc_base_url().'index.php?page=search&sFeed=rss');
        if ($content) {
            $xml = simplexml_load_string($content);
            foreach ($xml->channel->item as $item) {
                $list[] = array(
				'title' => strval($item->title));
            }
        }

        $cache->store($list);
    }

    return $list;
}

function listcloud_ShowCloud(){

$text_content = file_get_contents(osc_base_url().'/oc-content/uploads/listcloud_feeds.cache');
$cloud = new PTagCloud(50);
$cloud->setUTF8(true);
$cloud->addTagsFromText($text_content);
$cloud->setWidth("300px");
echo $cloud->listcloud_Show();

}	
	function listcloud_admin_menu() {
   	 echo '<h3><a href="#">Tag Cloud</a></h3>
    	<ul>   	  <li><a href="'.osc_admin_render_plugin_url("listcloud/help.php").'?section=types">&raquo; ' . __('F.A.Q. / Help', 'listcloud') . '</a></li>
    	</ul>';
	}
	
	
	
	
	function listcloud_help() {
        osc_admin_render_plugin(osc_plugin_path(dirname(__FILE__)) . '/help.php') ;
   }

   
// This is needed in order to be able to activate the plugin
osc_register_plugin(osc_plugin_path(__FILE__), '');

// This is a hack to show a Uninstall link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__)."_uninstall", '');

// Admin menu
osc_add_hook('admin_menu', 'listcloud_admin_menu');
// This is a hack to show a Configure link at plugins table (you could also use some other hook to show a custom option panel)
osc_add_hook(osc_plugin_path(__FILE__)."_configure", 'listcloud_help');

?>
