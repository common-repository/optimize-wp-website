<div class="wrap">
    <h2>Optimize WP Website Settings</h2>
	<div id="owpw-tab-menu"><a id="owpw-general" class="owpw-tab-links active" >General</a> <a  id="owpw-support" class="owpw-tab-links">Support</a> <a  id="owpw-other" class="owpw-tab-links">Our Other Plugins</a></div>
    <form method="post" action="options.php" id="owpw-option-form"> 
      <?php settings_fields('owpw'); ?>
        <div class="owpw-setting">
			<!-- General Setting -->	
			<div class="first owpw-tab" id="div-owpw-general">
				<table class="form-table">  
					<tr valign="top">
						<th width="10"><input type="checkbox" value="1" name="owpw_remove_wp_embed" id="owpw_remove_wp_embed" <?php checked(get_option('owpw_remove_wp_embed'),1); ?> /> <label for="owpw_remove_wp_embed">Remove wp-embed.min.js</label></th>
					</tr>
					<tr valign="top">
						<th><input type="checkbox" value="1" name="owpw_remove_jquery_migrate" id="owpw_remove_jquery_migrate" <?php checked(get_option('owpw_remove_jquery_migrate'),1); ?> />	<label for="owpw_remove_jquery_migrate">Remove jquery-migrate.min.js</label></th>
					</tr>
					<tr valign="top">
						<th><input type="checkbox" value="1" name="owpw_remove_commnet_reply" id="owpw_remove_commnet_reply" <?php checked(get_option('owpw_remove_commnet_reply'),1); ?> /> <label for="owpw_remove_commnet_reply">Remove comment-reply.min.js </label></th>
					</tr>
					<tr valign="top">
						<th><input type="checkbox" value="1" name="owpw_remove_generator" id="owpw_remove_generator" <?php checked(get_option('owpw_remove_generator'),1); ?> /> <label for="owpw_remove_generator">Remove generator tag </label></th>
					</tr>
					<tr valign="top">
						<th><input type="checkbox" value="1" name="owpw_page_specific_block" id="owpw_page_specific_block" <?php checked(get_option('owpw_page_specific_block'),1); ?> /> <label for="owpw_page_specific_block">Enable Page Specific Section </label></th>
					</tr>
					<tr><td><?php @submit_button(); ?></td></tr>
				</table>
			</div>
			<div class="owpw-tab" id="div-owpw-support"> <h2>Support</h2> 
				<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZEMSYQUZRUK6A" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="<?php echo  plugins_url( '../images/btn_donate_LG.gif' , __FILE__ );?>" title="Donate for this plugin"></a></p>
				<p><strong>Plugin Author:</strong><br><a href="https://www.wp-experts.in/contact-us" target="_blank">WP Experts Team</a></p>
				<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p>
			</div>
			<div class="last owpw-tab" id="div-owpw-other">
				<h2>Our Other plugins</h2>
				<p>
				  <ol>
					<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons With Floating Sidebar</a></li>
					<li><a href="https://wordpress.org/plugins/seo-manager/" target="_blank">SEO Manager</a></li>
							<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP-Admin</a></li>
							<li><a href="https://wordpress.org/plugins/wp-sales-notifier/" target="_blank">WP Sales Notifier</a></li>
							<li><a href="https://wordpress.org/plugins/wp-tracking-manager/" target="_blank">WP Tracking Manager</a></li>
							<li><a href="https://wordpress.org/plugins/wp-categories-widget/" target="_blank">WP Categories Widget</a></li>
							<li><a href="https://wordpress.org/plugins/wp-protect-content/" target="_blank">WP Protect Content</a></li>
							<li><a href="https://wordpress.org/plugins/wp-version-remover/" target="_blank">WP Version Remover</a></li>
							<li><a href="https://wordpress.org/plugins/wp-posts-widget/" target="_blank">WP Post Widget</a></li>
							<li><a href="https://wordpress.org/plugins/wp-importer" target="_blank">WP Importer</a></li>
							<li><a href="https://wordpress.org/plugins/wp-csv-importer/" target="_blank">WP CSV Importer</a></li>
							<li><a href="https://wordpress.org/plugins/wp-testimonial/" target="_blank">WP Testimonial</a></li>
							<li><a href="https://wordpress.org/plugins/wc-sales-count-manager/" target="_blank">WooCommerce Sales Count Manager</a></li>
							<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
							<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
							<li><a href="https://wordpress.org/plugins/tweets-slider/" target="_blank">Tweets Slider</a></li>
							<li><a href="https://wordpress.org/plugins/rg-responsive-gallery/" target="_blank">RG Responsive Slider</a></li>
							<li><a href="https://wordpress.org/plugins/cf7-advance-security" target="_blank">Contact Form 7 Advance Security WP-Admin</a></li>
							<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
					</ol>
				</p>
			</div>
		</div>
    </form>
</div>
