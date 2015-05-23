<?php
class Alg_ID{

	static public function uuid(){
	    if( function_exists( 'com_create_guid' ) === TRUE )
	        return trim( com_create_guid(), '{}' );
	    return sprintf(
			'%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
			mt_rand( 0, 65535 ),
			mt_rand( 0, 65535 ),
			mt_rand( 0, 65535 ),
			mt_rand( 16384, 20479 ),
			mt_rand( 32768, 49151 ),
			mt_rand( 0, 65535 ),
			mt_rand( 0, 65535 ),
			mt_rand( 0, 65535 )
		);
	}
}
?>
