/**
 * Script for enhanced comment form to reduce spam without jquery lib
 * 
 * @author   fb
 * @since    2.1.0  05/02/2012
 */

if ( document.getElementById( 'expected_answer_0' ) ) {

	var answer = document.getElementById( 'expected_answer_0' ).value + 
		document.getElementById( 'expected_answer_1' ).value;
	// helps on dev mode
	console.log(answer);
	
	document.getElementById( 'inpsyde_antispam_answer' ).setAttribute( 'value', answer );
	document.getElementById( 'inpsyde_antispam' ).style.display = 'none';
}