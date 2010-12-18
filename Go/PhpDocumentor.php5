<?php
class Go_PhpDocumentor
{
	public function __construct( $arguments )
	{
		$config		= Go_Library::getConfigData();
		$configFile	= Go_Library::getConfigFile();
		$reportFile	= $config['phpDocumentor']['outputLog'];							//  phpDocumentor Report File
		$command	= "phpdoc -c ".$configFile;											//  Shell Command to run phpDocumentor

		if( in_array( "--show-config-only", $arguments ) )
		{
			remark( "Settings:" );
			foreach( $config['phpDocumentor'] as $key => $value )
			{
				$key	.= str_repeat( " ", 20 - strlen( $key ) );
				remark( $key.$value );
			}
			return;
		}
		if( in_array( "-q", $arguments ) || in_array( "--quite", $arguments ) )			//  Quite Mode is activated
		{
			$command	.= " > ".$reportFile;											//  redirect Output into Report File
			@unlink( $reportFile );														//  remove old Report File
		}
		die( $command );
		passthru( $command );															//  run phpDocumentor
	}
}
?>