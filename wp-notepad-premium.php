<?php 
namespace wp\Notepad\premium\reminding ; 

/**
* Plugin Name: Wp-Notepad premium  
* Plugin URI: https://notepad.titanicuk.org/
* Description:  A Plugin  for reminding and note of users on the client side
* Version: 1.0.0
* Author: Amir Reza Khanjan
 *
 * @package Note
 * @category Wordpress
 * @author Mandegarweb
*
*/

/*
*
*check for correct directory
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Amir_Note' ) ) :


class Amir_Note 
{
/**
 *
 * @var string
*/
   public $version = '1.0.0';

	function __construct()
	{
     add_action('init',array(&$this, 'Amir_Note_localization_init'));
	 register_activation_hook( __FILE__,array(__CLASS__, 'Amir_Note_activate_pliugin' ));
     include_once('inc/shortcode.php');
	}
	function Amir_Note_localization_init()
	{

	 $path = dirname(plugin_basename( __FILE__ )) . '/lang/';
	 $loaded = load_plugin_textdomain( 'note', false, $path);
	}
	function Amir_Note_activate_pliugin()
	{
		global $wpdb;
		$table=$wpdb->prefix."note";
		if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
		 $databaser=$wpdb->query("CREATE TABLE IF NOT EXISTS `$table` (
		  `id` int(255) NOT NULL AUTO_INCREMENT,
		  `note` longtext CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
		  `do` tinyint(1) NOT NULL DEFAULT '0',
		  `date` varchar(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL,
		  `id_user` int(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
	   }
	    	
	}
}
$load=new Amir_Note();
endif;?>