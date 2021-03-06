<?php 
/**
 * 
 */

// Constants
define('LAYOUT_PLUGIN_DIR', dirname(__file__).DIRECTORY_SEPARATOR);
define('LAYOUT_PLUGIN_URL', str_replace(ABSPATH, site_url('/'), LAYOUT_PLUGIN_DIR));

// Resources
require_once LAYOUT_PLUGIN_DIR.'metabox.class.php';

// Declaring a new metabox
redrokk_metabox_class::getInstance("layout_details", array(
		'title'			=> "Rokkin Layouts",
		'priority'		=> 'high',
		'context'		=> 'side',
		'callback'		=> 'layout_details_metabox',
	)
);

/**
 * Method handles the layouts from the admin area.
 * 
 * @param unknown_type $post
 * @param unknown_type $metabox
 */
function layout_details_metabox( $post, $metabox )
{
	wp_register_script('redlayouts', LAYOUT_PLUGIN_URL.'layouts.js', array(), time(), true);
	wp_enqueue_script('redlayouts');
	
	wp_deregister_script('jquery.ui');
	wp_register_script('jquery.ui', LAYOUT_PLUGIN_URL.'jquery-ui-1.8.21.custom.min.js', array('jquery'), '1.8.82');
	wp_enqueue_script('jquery.ui');
	
	
	$value = get_post_meta($post->ID, 'layouts_design_items', true);
	$value = $value ?json_encode($value) :'false';
	?>
	<style type="text/css">
	/* Custom Scrollbar Styles */
	.rokkin_scroll::-webkit-scrollbar{width:9px;height:9px;}
	.rokkin_scroll::-webkit-scrollbar-button:start:decrement,#doc ::-webkit-scrollbar-button:end:increment{display:block;height:0;background-color:transparent;}
	.rokkin_scroll::-webkit-scrollbar-track-piece{background-color:#FAFAFA;-webkit-border-radius:0;-webkit-border-bottom-right-radius:8px;-webkit-border-bottom-left-radius:8px;}
	.rokkin_scroll::-webkit-scrollbar-thumb:vertical{height:50px;background-color:#999;-webkit-border-radius:8px;}
	.rokkin_scroll::-webkit-scrollbar-thumb:horizontal{width:50px;background-color:#999;-webkit-border-radius:8px;}
	
	.rokkin_scroll {display:none;width:258px;height:300px;overflow-y:scroll;margin-top:6px;}
	.layouts_design_item {width: 220px;text-align: center;font-size: 20px!important;height: 20px;margin:6px 0;}
	.layouts_content_item {width: 220px;margin:6px 0;}
	.layout_widget_cell {background-color:#e7ecf2;min-height:120px;line-height:120px;text-size:20px;font-weight:bold;text-align:center;
		border:4px solid #627DB4;margin-bottom:20px;
		background-repeat: no-repeat;background-position: center center;
		background-image: url(data:image/png;base64,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);
	}
	.rokkout-handle {border-bottom-left-radius: 3px;background-repeat : no-repeat;background-position : center center;background-color: #627DB4;position : absolute;width: 18px;height: 18px;border: 1px solid #627DB4;top: 0;right: 0;z-index: 1000;visibility : hidden;background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAALGPC/xhBQAAAAZiS0dEAP8A/wD/oL2nkwAAAAlwSFlzAAALEwAACxMBAJqcGAAAAAd0SU1FB9wGGxcBHcIEu6cAAANiSURBVDjLXZNdbFN1GMafc05b6TkdtCVW26002ZbVzCF6BxEZCI5kxpuJI95gVD5GVOZiROetidlVIzcQRRlZr5YlRHAzmRCYxnajumya6D7sh7O2ZaEfp1tPe9qe///1wg0Hv6v3TZ73zfs8yStgg5aWFsRiMQSDQfh8vm3ZbFYOh8OFjo4Owe12OxOJRLGvr68OAF1Hj+L7yUk8YHFxEVtJpVJnl5eXfw8Gg0+HQqFXotHo8sTE+MtbNR8PDv5XeDweAMDc3FxbOp0eCgQCB1ZXVy8SESWTyXAun0+UNY3Gx8c/mZ2dPf5nLPYpABseJZVKDTHGapqm5arVatEwDF6r1Vi1ViO9qrO1tfV8qVRaV1VVm5y8+faDwZGREQBAIBA4oGladmOQ12o1put6vaLr9XJZZ5VKhZcrFZ5Op/8C4AaA4ZGrMHm93m3JZPJNk8m0W5QkM+dcMAyDZzKZecMwfiTAbFNsLzkc9jYQSBTFx/5OJvsL+UIkEolcF7PZrFwqld51uVxnJVFs4JxTOpOZHx0dPeP3+wee8vvPLSz80V8qafcNzgSrrDypyMpHhULh9UQibpE8jY0653zK4XA8pyhKIxFRPpcb6+npGd5wScFgMNrV1aW5HncdhABTLBb75sKFzwdkWc6Je57ZLTQ3tzTLsuLmRAJnnIhgBiBu5vTOe+esTqfTTyATZxyyLLsPHzmys1LWBOn0qTM7m7xNYw6HoxkQQEQi48ze3d29ZLdvv3/ixBvKa8dePeZ2ewZFUbRyzgVJMnktFssTqlr81hRPxIt1Zry/tLS0Z//+Fz4wm81O+44dbe3t7Ze9TU2/SJJktTVs3wtRaCACFQqFf2amZz4TRGGxoKq6sHnm3UjkeGtr61eSJNlAAGMMnDgngkCcA4BARChppczpk6cO375ze+H5vfv+96nYlA6jbgiqqq4U14r3CABnnBgziBNHLp9Prq2vp4kg9w8M7AOA0Mw00NnZubnDNv7dxEkA7mg0NpTO3KNQOHxt/tffZuLxBH195UrfoRcPtV+/ceOtrQEDAD48f/6h/m7k556bt26N9fb2ei9euvTs1NQP14aHrx7cqvHt2gUAEB79iS++vAzG6tLKyoqlqusVTSsLPp/PqqpFPRye5uHwTw/p/wXQV8s8KO6lLgAAAABJRU5ErkJggg==);}
	#titlediv {padding-bottom:8px;background:#fff;}
	</style>
	
	<?php wp_nonce_field( 'layouts_nounce', 'layouts_nounce' ) ?>
	<a id="layouts_design_mode" class="button rokkin_button" style="float:left;" href="#" data="#designmode">Design Mode</a>
	<a id="layouts_content_mode" class="button rokkin_button" style="float:right;" href="#" data="#contentmode">Content Mode</a>
	<div class="clear"></div>
	
	<div id="designmode" class="rokkin_scroll rokkin200">
		<?php do_action('layouts_design_mode', $post, $metabox); ?>
		<div class="clear"></div>
	</div>
	
	<div id="contentmode" class="rokkin_scroll rokkin100">
		<?php do_action('layouts_content_mode', $post, $metabox); ?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<script type="text/javascript">
	var layouts_design_mode = <?php echo $value ?>;
	</script>
	<?php 
}

/**
 * Method saves the layout rows
 * 
 * @param string $post_id
 * @return null
 */
function layouts_save_post( $post_id )
{
	if ( wp_is_post_revision( $post_id ) ) return false;
	if ( empty($_POST) ) return false;
	if ( !wp_verify_nonce($_POST['layouts_nounce'],'layouts_nounce') ) return false;
	
	if (array_key_exists('layouts_design_items', $_POST)) {
		
		delete_post_meta($post_id, 'layouts_design_items');
		if (!empty($_POST['layouts_design_items'])) {
			add_post_meta($post_id, 'layouts_design_items', $_POST['layouts_design_items'], true);
		}
	}
}
add_action('save_post','layouts_save_post');


add_action('layouts_content_mode','layouts_content_mode');
function layouts_content_mode( $post, $metabox )
{
	?>
	<div class="layouts_content_item button">1</div>
	<?php 
}

