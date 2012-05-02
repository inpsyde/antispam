/**
 * Script for enhanced comment form to reduce spam with jquery lib
 * 
 * @author   et, fb
 * @since    2.0.0  03/26/2012
 */

( function( $ ) {
	
	var answer = $( "#expected_answer_0" ).val() + $( "#expected_answer_1" ).val();
	
	$( "#inpsyde_antispam_answer" ).val(answer);
	$( ".hide-if-js-enabled" ).hide();

} )( jQuery );