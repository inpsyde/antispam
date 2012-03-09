<?php
namespace Inpsyde\Antispam\Settings;

class Page {

	private $page_hook;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'init_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );
	}

	
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

	public function init_settings() {
		add_settings_section(
			/* $id 		 */ 'antispam_settings_section',
			/* $title 	 */ __( 'Antispam Settings', 'inps-antispam' ),	
			/* $callback */ function () { /* section head html */ }, 		
			/* $page	 */ $this->page_hook	
		);

		add_settings_field(
			/* $id       */ 'inpsas_words',
			/* $title    */ sprintf(
				'<label for="inpsas_words">%s</label>',
				__( 'Selection of words users without JavaScript have to type. Separate with line breaks.', 'inps-antispam' )
			),   
			/* $callback */ function () {
				?>
				<textarea name="inpsas_words" id="inpsas_words" class="large-text code" rows="10" cols="50"><?php echo get_option( 'inpsas_words', '' ); ?></textarea>
				<?php
			},      
			/* $page     */ $this->page_hook,  
			/* $section  */ 'antispam_settings_section'
		);

		register_setting( $this->page_hook, 'inpsas_words' );
	}

	public function page() {
		?>
		<div class="wrap">
			<?php screen_icon( 'options-general' ); ?>
			<h2><?php echo __( 'Antispam', 'inps-antispam' ); ?></h2>

			<form method="post" action="options.php">
				<?php settings_fields( $this->page_hook ); ?>
				<?php do_settings_sections( $this->page_hook ); ?>

				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>

		</div>
		<?php
	}
}