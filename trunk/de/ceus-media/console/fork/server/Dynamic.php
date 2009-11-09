<?php
require_once( "Abstract.php5" );
class Fork_Server_Dynamic extends Fork_Server_Abstract
{
	protected $scriptFile;

	public function setScriptFile( $scriptFile )
	{
		$this->scriptFile	= $scriptFile;
	}

	protected function handleRequest( $request )
	{
		if( !$this->scriptFile )
			return "No Script for Dynamic Server set.";
		if( !file_exists( $this->scriptFile ) )
			return "Script for Dynamic Server is not existing.";
		
		return require_once( $this->scriptFile );
	}
}
?>
