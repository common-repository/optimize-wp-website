<?php
/*
Plugin Name: Optimize WP Website
Description: A very simple plugin to optimize your website by remove unused files and allow you to add page specific CSS code
Author: WP-EXPERTS.IN Team
Author URI: https://www.wp-experts.in
Version: 1.2
License GPL2
Copyright 2020-21  WP-Experts.IN  (email  developer.0087@gmail.com)

This program is free software; you can redistribute it andor modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('OptimizeWpWebsite'))
{
    class OptimizeWpWebsite
    {
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
			// Installation and uninstallation hooks
			register_activation_hook(__FILE__, array(&$this, 'owpw_activate'));
			register_deactivation_hook(__FILE__, array(&$this, 'owpw_deactivate'));
			//backend hooks action
			add_filter("plugin_action_links_".plugin_basename(__FILE__), array(&$this,'owpw_settings_link'));
			add_action('admin_init', array(&$this, 'owpw_admin_init'));
			add_action('admin_menu', array(&$this, 'owpw_add_menu'));
			add_action( 'admin_bar_menu', array(&$this,'toolbar_link_to_owpw'), 999 );
			//front-end hooks action
			add_action('init', array(&$this,'owpw_init_func') );
			add_action('wp_footer', array(&$this,'owpw_deregister_unused_scripts') );
			add_action('wp_default_scripts', array(&$this,'owpw_remove_unused_jquery_migrate') );
			// add page specific CSS meta boxes
			if(!empty(get_option('owpw_page_specific_block')))
			{
			//register meta box in backend
			add_action('add_meta_boxes',array(&$this,'oww_add_meta_box'));
			add_action( 'save_post', array(&$this,'oww_meta_box_save_post') );
			//publish meta box css in front-end
			add_action('wp_head',array(&$this,'oww_display_custom_meta'));
		    }
			
            
        } // END public function __construct
        
		/**
		 * @hooks wp_head or wp_footer
		 * hook to display custom meta box value in head/footer of the site
		 */
		 public function oww_display_custom_meta()
		 {
		    global $post;
			if(isset($post) && is_singular('page'))
			{
		     $position = !empty(get_post_meta($post->ID,'_oww_css_postion',true)) ? get_post_meta($post->ID,'_oww_css_postion',true) : 'footer';
			 if($position=='footer'){
			   add_action('wp_footer' ,array(&$this,'oww_display_custom_meta_val'));
		       }else
		       {
				  $csscode = !empty(get_post_meta($post->ID,'_oww_custom_css',true)) ? '<style type="text/css">'.get_post_meta($post->ID,'_oww_custom_css',true).'</style>' : '';
				  $csscode .= !empty(get_post_meta($post->ID,'_oww_css_file',true)) ? "<link rel='stylesheet' id='oww-css'  href='".get_post_meta($post->ID,'_oww_css_file',true)."' media='all' />" : '';
				  echo $csscode;
				 }
		    }
		}
		public function oww_display_custom_meta_val()
		{
			global $post;
			if(isset($post) && is_singular('page'))
			{
			  $csscode = !empty(get_post_meta($post->ID,'_oww_custom_css',true)) ? '<style type="text/css">'.get_post_meta($post->ID,'_oww_custom_css',true).'</style>' : '';
			  $csscode .= !empty(get_post_meta($post->ID,'_oww_css_file',true)) ? "<link rel='stylesheet' id='oww-css'  href='".get_post_meta($post->ID,'_oww_css_file',true)."' media='all' />" : '';
			  echo $csscode;
			  }
		}
		/**
		 * @hooks add_meta_box
		 * hook to add custom meta box fields
		 */
		public function oww_add_meta_box()
		{
			$screens = array( 'page');
			foreach ( $screens as $screen ) {

				add_meta_box(
					'oww-meta-box',
					__( 'Page Specific Meta Section By Optimize WP Website', 'wpexpertsin' ),
					array(&$this,'show_oww_meta_box'),
					$screen
				);
			}

		}
		//define custom meta fields
		public function oww_meta_fields()
		{
		  //Define meta box fields
			$oww_prefix = '_oww_';
			$oww_meta_fields = array(
			'id' => 'oww-meta-box',
			'title' => 'Page Specific Section By Optimize WP Website',
			'page' => '',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
							 array(
							'title' => 'Custom CSS',
							'desc' => 'Define page specific custom CSS without style tag',
							'id' => $oww_prefix . 'custom-css',
							'name' => $oww_prefix . 'custom_css',
							'type' => 'textarea',
							'std' => ''
							),
							 array(
							'title' => 'Custom File',
							'desc' => 'Define custom css file',
							'id' => $oww_prefix . 'css-file',
							'name' => $oww_prefix . 'css_file',
							'type' => 'text',
							'std' => ''
							),
							 array(
							'title' => 'Display CSS In ',
							'desc' => ' select option to show css in header/footer',
							'id' => $oww_prefix . 'css-postion',
							'name' => $oww_prefix . 'css_postion',
							'type' => 'select',
							'options' =>'footer,head',
							'std' => ''
							)
			   )
			);
		  return $oww_meta_fields;
		}

		/**
		 * @hooks add_meta_box
		 * hook to show meta box html
		 */	
		public function show_oww_meta_box()
		{
			global $post;
			$oww_meta_fields = $this->oww_meta_fields();
			wp_nonce_field( 'oww_box_field', 'oww_meta_box_once' );
			foreach ($oww_meta_fields['fields'] as $field) {
			// get current post meta data
		   
			$meta = get_post_meta($post->ID, $field['id'], true);
			echo '<p>',
			'<label for="', $field['id'], '"><strong>', $field['title'], '</strong></label>','';
			switch ($field['type']) {
			case 'text':
			echo '<input type="text" name="', $field['name'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
			break;
			case 'textarea':
			echo '<textarea name="', $field['name'], '" id="', $field['id'], '" cols="60" rows="20" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
			break;
			case 'select':
			echo '<select name="', $field['name'], '" id="', $field['id'], '" >';
			$optionVal=explode(',',$field['options']);
			foreach($optionVal as $optVal):
			if($meta==$optVal){
			$valseleted =' selected="selected"';}else {
				 $valseleted ='';
				}
			echo '<option value="', $optVal, '" ',$valseleted,' id="', $field['id'], '">', $optVal, '</option>';
			endforeach;
			echo '</select>',$field['desc'];
			break;
			'</p>';
			}

			}
		}
		//sanitize_fields
		public function oww_sanitize_fields($type='',$val='')
		{
			// Is this textarea
			if($type='textarea')
			{
			  $val = sanitize_textarea_field($val);
			}else
			{
				$val = sanitize_text_field($val);
			}
			return $val;
		}

	   /**
		 * @hooks save_post
		 * hook to save meta values
		 */	
		  public function oww_meta_box_save_post($post_id) {
			$oww_meta_box = $this->oww_meta_fields();
				// Check if our nonce is set.
				 if ( ! isset( $_POST['oww_meta_box_once'] ) ) {
						return;
					}
					
				// check autosave
				if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return $post_id;
				}

				// check permissions
				if ('page' == $_POST['post_type']) 
				{
					if (!current_user_can('edit_page', $post_id))
					return $post_id;
				} 
				elseif(!current_user_can('edit_post', $post_id)){
				return $post_id;
				}

				foreach ($oww_meta_box['fields'] as $field) 
				{
					$old = get_post_meta($post_id, $field['name'], true);
					$new = $this->oww_sanitize_fields($field['type'],$_POST[$field['name']]);
					if ($new && $new != $old){
					 update_post_meta($post_id, $field['name'], $new);
					} 
					elseif ('' == $new && $old) {
					delete_post_meta($post_id, $field['name'], $old);
					}
				}
			}
		/**
		 * @hooks init
		 * hook to remove unused files
		 */		
		 public function owpw_init_func(){
		    
		    $remove_generator = !empty(get_option('owpw_remove_generator')) ? get_option('owpw_remove_generator') : 0;
		 	if($remove_generator)
		 	remove_action('wp_head', 'wp_generator'); //removes //wp_generator
			
			remove_action('wp_head', 'rss_link'); //removes EditURI/RSD
			//remove_action( 'wp_head', 'wlwmanifest_link');//Remove wlwmanifest link
			//remove_action( 'wp_head', 'wp_shortlink_wp_head'); //Remove shortlink
		 
		 }
		/**
		 * @hooks wp_footer
		 * hook to remove unused files
		 */		
		 public function owpw_deregister_unused_scripts(){
			     global $post;
			    $removeembed = !empty(get_option('owpw_remove_wp_embed')) ? get_option('owpw_remove_wp_embed') : 0;
			    $removecommentjs= !empty(get_option('owpw_remove_commnet_reply')) ? get_option('owpw_remove_commnet_reply') : 0;
			    if($removeembed){
				wp_dequeue_script( 'wp-embed' ); // remove default wp-embed.js
			    }
			    
			    // dislable comment for all pages excpet where comment is open
			    if($removecommentjs)
			    {
			     wp_deregister_script( 'comment-reply' ); 
			     if (isset($post) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
					wp_enqueue_script( 'comment-reply' );
	             }
			   }
			    
			}
		/**
		 * @hooks wp_default_scripts
		 * hook to remove wp_default_scripts
		 */		
		 
		 public function owpw_remove_unused_jquery_migrate( $scripts ) {
			
			$removemigrate = !empty(get_option('owpw_remove_jquery_migrate')) ? get_option('owpw_remove_jquery_migrate') : 0;
			if($removemigrate){
			if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
				$script = $scripts->registered['jquery'];

				if ( $script->deps ) { // Check whether the script has any dependencies
					$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
				}
			}
		     }
		 }
		/**
		 * hook to add link under adminmenu bar
		 */		
		public function toolbar_link_to_owpw( $wp_admin_bar ) {
			$args = array(
				'id'    => 'owpw_menu_bar',
				'title' => 'WP Optimize Site',
				'href'  => admin_url('options-general.php?page=wp_optimize_site'),
				'meta'  => array( 'class' => 'owpw-toolbar-page' )
			);
			$wp_admin_bar->add_node( $args );
			//second lavel
			$wp_admin_bar->add_node( array(
				'id'    => 'owpw-second-sub-item',
				'parent' => 'owpw_menu_bar',
				'title' => 'Settings',
				'href'  => admin_url('options-general.php?page=wp_optimize_site'),
				'meta'  => array(
					'title' => __('Settings'),
					'target' => '_self',
					'class' => 'owpw_menu_item_class'
				),
			));
		}
		/**
		 * hook into WP's admin_init action hook
		 */
		public function owpw_admin_init()
		{
			// Set up the settings for this plugin
			$this->owpw_init_settings();
			// Possibly do additional admin_init tasks
		} // END public static function activate
		/**
		 * Initialize some custom settings
		 */     
		public function owpw_init_settings()
		{
			// register the settings for this plugin
			register_setting('owpw', 'owpw_remove_wp_embed');
			register_setting('owpw', 'owpw_remove_jquery_migrate');
			register_setting('owpw', 'owpw_remove_commnet_reply');
			register_setting('owpw', 'owpw_remove_generator');
			register_setting('owpw', 'owpw_remove_generator');
			register_setting('owpw', 'owpw_page_specific_block');
		} // END public function init_custom_settings()
		/**
		 * add a menu
		 */     
		public function owpw_add_menu()
		{
			add_options_page('WP Optimize Site Settings', 'WP Optimize Site', 'manage_options', 'wp_optimize_site', array(&$this, 'owpw_settings_page'));
		} // END public function add_menu()

		/**
		 * Menu Callback
		 */     
		public function owpw_settings_page()
		{
			if(!current_user_can('manage_options'))
			{
				wp_die(__('You do not have sufficient permissions to access this page.'));
			}

			// Render the settings template
			include(sprintf("%s/lib/settings.php", dirname(__FILE__)));
			//include(sprintf("%s/css/admin.css", dirname(__FILE__)));
			// Style Files
			wp_register_style( 'owpw_admin_style', plugins_url( 'css/owpw-admin.css',__FILE__ ) );
			wp_enqueue_style( 'owpw_admin_style' );
			// JS files
			wp_register_script('owpw_admin_script', plugins_url('/js/owpw-admin.js',__FILE__ ), array('jquery'));
            wp_enqueue_script('owpw_admin_script');
		} // END public function plugin_settings_page()
        /**
         * Activate the plugin
         */
        public function owpw_activate()
        {
            // Do nothing
        } // END public static function activate
    
        /**
         * Deactivate the plugin
         */     
        public function owpw_deactivate()
        {
            // Do nothing
        } // END public static function deactivate
        // Add the settings link to the plugins page
		public function owpw_settings_link($links)
		{ 
			$settings_link = '<a href="options-general.php?page=wp_optimize_site">Settings</a>'; 
			array_unshift($links, $settings_link); 
			return $links; 
		}
    } // END class wp_optimize_site
} // END if(!class_exists('OptimizeWpWebsite'))

if(class_exists('OptimizeWpWebsite'))
{
    // instantiate the plugin class
    $owpsite_plugin_template = new OptimizeWpWebsite();
}
