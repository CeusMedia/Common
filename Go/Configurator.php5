<?php
class Go_Configurator
{
	public function __construct( $arguments )
	{
		$force	= in_array( "-f", $arguments ) || in_array( "--force", $arguments );
		$pwd	= str_replace( "\\", "/", dirname( dirname( realpath( __FILE__ ) ) ) )."/";

		if( !defined( 'CM_CLASS_PATH' ) )
			define( 'CM_CLASS_PATH',	$pwd );
		ini_set( 'include_path', CM_CLASS_PATH.PATH_SEPARATOR.ini_get( "include_path" ) );
#		if( !@include_once( "autoload.php5" ) )
#			die( 'Installation of "cmClasses" seems to be corrupt: '.$pwd.'import.php5 is missing.' );
		require_once( dirname( dirname( __FILE__ ) ).'/src/UI/DevOutput.php5' );

		$files	= array(
			"cmClasses.ini.dist"	=> "cmClasses.ini",
			"doc.xml.dist"			=> "doc.xml",
			".htaccess.dist"		=> ".htaccess",
		);

		foreach( $files as $sourceFile => $targetFile )
		{
			if( !file_exists( $pwd.$sourceFile ) )
				throw new RuntimeException( 'Source file "'.$sourceFile.'" is not existing.' );
			
			remark( 'Setting up "'.$targetFile.'"... ' );
			$content	= file_get_contents( $pwd.$sourceFile );
			$content	= str_replace( "/path/to/cmClasses/version/", $pwd, $content );
			if( !$force && file_exists( $pwd.$targetFile ) )
				$status	= "already existing, use --force to overwrite";
			else
			{
				file_put_contents( $pwd.$targetFile, $content );
				$status	= "done.";
			}
			print( $status );
		}
		if( in_array( "--clean-up", $arguments ) )
		{
			remark( "Removing installer and distribution files..." );
			foreach( $files as $sourceFile => $targetFile )
				unlink( $pwd.$sourceFile );
		}
		remark( "\ncmClasses is now configured and usable.\n" );
	}
}
?>
