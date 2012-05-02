<?php 
// set namespace
namespace Inpsyde\Antispam;

// include settings page
if ( is_admin() ) {
	require_once 'settings.php';
	new Settings\Inpsyde_Settings_Page;
}

// only on frontend
// include the new fields in comment form on frontend
if ( ! is_admin() ) {
	add_action( 'comment_form', '\Inpsyde\Antispam\enhance_comment_form' );
	add_action( 'comment_post', '\Inpsyde\Antispam\comment_post' );
}

/**
 * uninstall options item, if the plugin deinstall via backend
 * 
 * @author  et, fb
 * @since   2.0.0  03/26/2012
 * @uses    delete_option
 * @return  void
 */
register_uninstall_hook( __FILE__, '\Inpsyde\Antispam\delete_options' );
function delete_options() {
	
	delete_option( 'inpsyde_antispam' );
}


/**
 * Init the plugin in WordPress
 * Enqueue the script and ready for multilanguage support
 * 
 * @since   2.0.0  03/26/2012
 * @return  void
 */
add_action( 'init', '\Inpsyde\Antispam\init' );
function init() {
	
	load_plugin_textdomain( 'inps-antispam', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	if ( ! is_admin() )
		wp_enqueue_script( 'jquery' );
	
}

/**
 * Convenience wrapper to access plugin options.
 * 
 * @param  string $name    option name
 * @param  mixed  $default fallback value if option does not exist
 * @return mixed
 */
function get_option( $name, $default = NULL ) {
	
	$options = \get_option( 'inpsyde_antispam' );

	return ( isset( $options[ $name ] ) ) ? $options[ $name ] : $default;
}

/**
 * Get array of possible words.
 * 
 * @return array
 */
function get_possible_words() {
	
	$words_string = trim( get_option( 'words', '' ) );
	$raw_words    = explode( "\r\n", $words_string );
	
	// filter empty strings
	return array_filter( $raw_words, function ( $w ) { return strlen( trim( $w ) ) > 0; } );
}

/**
 * Randomly select a word from our dictionary.
 * 
 * If no words exist, use a fallback string.
 * 
 * @return string
 */
function get_random_word() {

	$words = get_possible_words();

	if ( 0 === count( $words ) )
		return 'I, for one, welcome our new overlords.';
	else
		return $words[ array_rand( $words ) ];
}

/**
 * Hook: Add spam detection fields to comment form.
 * 
 * @param  mixed $form The comment form
 * @return void
 */
function enhance_comment_form( $form ) {
	
	// generate/get expected answer
	$answer = get_random_word();
	
	// split answer
	$parts = array();
	$answer_len = strlen( $answer );
	$answer_splitpoint = rand( 1, $answer_len - 2 ); // -2 => part 1 cannot be empty
	$parts[0] = substr( $answer, 0, $answer_splitpoint );
	$parts[1] = substr( $answer, $answer_splitpoint );
	// custom strings from settings
	$advice_string = get_option( 'advice', '' );
	if ( empty( $advice_string ) )
		$advice_string = __( 'Please type the following phrase to confirm you are a human: %word%', 'inps-antispam' );
	// replace words in string
	$advice_string = str_replace( '%word%', "%s%s%s", $advice_string );
	
	$advice = sprintf(
	 	$advice_string,
	 	$parts[0],
	 	'<span style="display:none;">+</span>',
	 	$parts[1]
	);

	?>
	<div class="hide-if-js-enabled">
		<label for="inpsyde_antispam_answer"><?php echo $advice; ?></label>
		<input type="text" name="inpsyde_antispam_answer" id="inpsyde_antispam_answer">
		<input type="hidden" name="expected_answer[0]" id="expected_answer_0" value="<?php echo $parts[ 0 ]; ?>">
		<input type="hidden" name="expected_answer[1]" id="expected_answer_1" value="<?php echo $parts[ 1 ]; ?>">
		<script type="text/javascript">
		jQuery( function($) {
			var answer = $( "#expected_answer_0" ).val() + $( "#expected_answer_1" ).val();
			$( "#inpsyde_antispam_answer" ).val(answer);
			$( ".hide-if-js-enabled" ).hide();
		} );
		</script>
	</div>
	<?php
}

/**
 * Hook: Process a posted comment.
 * 
 * @param  int $comment_id
 * @return void
 */
function comment_post( $comment_id ) {
	global $comment_content, $comment_type;

	if ( ! is_current_comment_valid() ) {
		delete_comment( $comment_id );
		$rejected_string = get_option( 'rejected', '' );

		if ( empty( $rejected_string ) )
			$rejected_string = __( 'Sorry, we think you are not a human :/', 'inps-antispam' );

		wp_die( $rejected_string );
	}
}

/**
 * Check if the submitted comment is valid.
 * 
 * @return boolean
 */
function is_current_comment_valid() {

	if ( ! isset( $_POST[ 'inpsyde_antispam_answer' ] ) || ! isset( $_POST[ 'expected_answer' ] ) )
		return false;

	if ( ! is_array( $_POST[ 'expected_answer' ] ) )
		return false;

	// TODO: check if the answer is in the list of allowed words
	$answer   = $_POST[ 'inpsyde_antispam_answer' ];
	$expected = implode( '', $_POST[ 'expected_answer' ] );

	return $answer === $expected;
}

/**
 * Delete comment by comment id.
 * 
 * @param  int $comment_id
 * @return void
 */
function delete_comment( $comment_id ) {
	global $wpdb;

	$wpdb->query( "
		DELETE FROM
			{$wpdb->comments}
		WHERE
			comment_ID = {$comment_id}
	" );

	recount_comments_for_post( (int) $_POST[ 'comment_post_ID' ] );
}

/**
 * Recount comment number for given post id.
 * 
 * @param  int $post_id
 * @return void
 */
function recount_comments_for_post( $post_id ) {
	global $wpdb;

	$wpdb->query( "
		UPDATE
			{$wpdb->posts}
		SET
			comment_count = (SELECT COUNT(*) from {$wpdb->comments} 
		WHERE
			comment_post_id = {$post_id}
		AND
			comment_approved = '1')
		WHERE
			ID = {$post_id}"
	);
}