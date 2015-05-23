<?php
class Deprecation{
	static public function isAtleastPhpVersion( $version, $ignoreBuild = TRUE ){
		$installed	= phpversion();
		if( $ignoreBuild )
			$installed	= substr( $version, 0, strpos( $installed, '-' ) );
		return version_compare( $installed, $version ) >= 0;
		
	}
	
	public function notify( $message ){
		if( self::isAtleastPhpVersion( "5.3.0" ) )
			trigger_error( $message, E_USER_DEPRECATED );
		else
			trigger_error( 'Deprecated: '.$message, E_USER_NOTICE );
		return FALSE;
	}
}
?>