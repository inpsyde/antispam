<?php
// set namespace
namespace Inpsyde\Antispam\Settings;

/**
 * Settings Page Class
 * 
 * @author et, fb
 * @since   2.0.0  03/26/2012
 */
class Inpsyde_Settings_Page {
	
	private $page_hook;
	
	/**
	 * Constructor
	 * 
	 * @author   et, fb
	 * @since    2.0.0  03/26/2012
	 * @return   void
	 */
	public function __construct() {
		
		add_action( 'admin_menu', array( $this, 'init_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );
	}
	
	/** 
	 * Init menu item
	 * 
	 * @author   et, fb
	 * @since    2.0.0  03/26/2012
	 * @return   void
	 */
	public function init_menu() {
		
		$this->page_hook = add_submenu_page(
			/* $parent_slug*/ 'options-general.php',
			/* $page_title */ 'Antispam',
			/* $menu_title */ 'Antispam',
			/* $capability */ 'administrator',
			/* $menu_slug  */ 'inpsyde-antispam-page',
			/* $function   */ array( $this, 'page' )
		);
	}
	
	/**
	 * Register and add settings fields to form on settings page
	 * 
	 * @author   et, fb
	 * @since    2.0.0  03/26/2012
	 * @return   void
	 */
	public function init_settings() {
		
		add_settings_section(
			/* $id 		 */ 'antispam_settings_section',
			/* $title 	 */ __( 'Antispam Settings', 'inps-antispam' ),	
			/* $callback */ function () { /* section head html */ }, 		
			/* $page	 */ $this->page_hook	
		);
		
		add_settings_field(
			/* $id       */ 'inpsas_jslib',
			/* $title    */ __( 'Javascript Library', 'inps-antispam' ),
			/* $callback */ function () {
				?>
				<fieldset>
					<legend class="screen-reader-text"><span>__( 'Javascript Library', 'inps-antispam' )</span></legend>
					<label>
						<input type="radio" name="inpsyde_antispam[jslib]" value="none" <?php
						if ( 'none' === \Inpsyde\Antispam\get_option( 'jslib', '' ) ) { // checked() uses "==" rather than "==="
							echo " checked='checked'";
						} ?> /> 
						<span><?php _e( 'none (default)', 'inps-antispam' ); ?></span>
					</label><br />
					<label>
						<input type="radio" name="inpsyde_antispam[jslib]" value="jquery" <?php
						if ( 'jquery' === \Inpsyde\Antispam\get_option( 'jslib', '' ) ) { // checked() uses "==" rather than "==="
							echo " checked='checked'";
						} ?> /> 
						<span><?php _e( 'jQuery', 'inps-antispam' ); ?></span>
					</label>
				</fieldset>
				<span class="description">
				<?php
				_e( 'Do you will use the solution with jQuery lib or without an lib? Decide which library you want to use the frontend.', 'inps-antispam' );
				?>
				</span>
				<?php
			},
			/* $page     */ $this->page_hook,  
			/* $section  */ 'antispam_settings_section'
		);
		
		add_settings_field(
			/* $id       */ 'inpsas_jsload',
			/* $title    */ __( 'Javascript Loading', 'inps-antispam' ),
			/* $callback */ function () {
				?>
				<fieldset>
					<legend class="screen-reader-text"><span>__( 'Javascript Loading', 'inps-antispam' )</span></legend>
					<label>
						<input type="radio" name="inpsyde_antispam[jsload]" value="singular" <?php
						if ( 'singular' === \Inpsyde\Antispam\get_option( 'jsload', '' ) ) { // checked() uses "==" rather than "==="
							echo " checked='checked'";
						} ?> /> 
						<span><?php _e( 'only singular pages (default)', 'inps-antispam' ); ?></span>
					</label><br />
					<label>
						<input type="radio" name="inpsyde_antispam[jsload]" value="all" <?php
						if ( 'all' === \Inpsyde\Antispam\get_option( 'jsload', '' ) ) { // checked() uses "==" rather than "==="
							echo " checked='checked'";
						} ?> /> 
						<span><?php _e( 'all pages', 'inps-antispam' ); ?></span>
					</label>
				</fieldset>
				<span class="description">
				<?php
				_e( 'Do you will load the script only on singular pages (post, page) or on all pages in frontend? The script is only necessary for the comment form.', 'inps-antispam' );
				?>
				</span>
				<?php
			},
			/* $page     */ $this->page_hook,  
			/* $section  */ 'antispam_settings_section'
		);
		
		add_settings_field(
			/* $id       */ 'inpsas_words',
			/* $title    */ sprintf(
				'<label for="inpsas_words">%s</label>',
				__( 'Selection of words users without JavaScript have to type. Separate with line breaks.', 'inps-antispam' )
			),
			/* $callback */ function () {
				?>
				<textarea name="inpsyde_antispam[words]" id="inpsas_words" rows="10" cols="50"><?php echo \Inpsyde\Antispam\get_option( 'words', '' ); ?></textarea>
				<?php
			},
			/* $page     */ $this->page_hook,  
			/* $section  */ 'antispam_settings_section'
		);
		
		add_settings_field(
			/* $id       */ 'inpsas_advice',
			/* $title    */ sprintf(
				'<label for="inpsas_advice">%s</label>',
				__( 'Please note; assign the password hint %word% to see it displayed when prompted.', 'inps-antispam' )
			),
			/* $callback */ function () {
				?>
				<textarea name="inpsyde_antispam[advice]" id="inpsas_advice" class="large-text" rows="10" cols="50"><?php echo \Inpsyde\Antispam\get_option( 'advice', '' ); ?></textarea>
				<?php
			},
			/* $page     */ $this->page_hook,  
			/* $section  */ 'antispam_settings_section'
		);
		
		add_settings_field(
			/* $id       */ 'inpsas_rejected',
			/* $title    */ sprintf(
				'<label for="inpsas_rejected">%s</label>',
				__( 'The warning you wish to give when the field is not or wrong filled out.', 'inps-antispam' )
			),
			/* $callback */ function () {
				?>
				<textarea name="inpsyde_antispam[rejected]" id="inpsas_rejected" class="large-text" rows="10" cols="50"><?php echo \Inpsyde\Antispam\get_option( 'rejected', '' ); ?></textarea>
				<?php
			},
			/* $page     */ $this->page_hook,  
			/* $section  */ 'antispam_settings_section'
		);
		
		register_setting( $this->page_hook, 'inpsyde_antispam' );
	}
	
	/**
	 * Get settings pages incl. markup
	 * 
	 * @author  et, fb
	 * @since   2.0.0  03/26/2012
	 * @return  void
	 */
	public function page() {
		?>
		<div class="wrap">
			<?php screen_icon( 'options-general' ); ?>
			<h2><?php _e( 'Antispam', 'inps-antispam' ); ?></h2>
			
			<form method="post" action="options.php">
				<?php settings_fields( $this->page_hook ); ?>
				<?php do_settings_sections( $this->page_hook ); ?>
				
				<?php submit_button( __( 'Save Changes' ), 'button-primary', 'submit', TRUE ); ?>
			</form>
			
		</div>
		<?php
	}
	
} // end class