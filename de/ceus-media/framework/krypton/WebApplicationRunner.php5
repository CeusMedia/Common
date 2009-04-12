<?php
/**
 *	Executes Web Application in Container secured by Exception Handler.
 *	@package		demos.krypton
 *	@extends		Framework_Krypton_ApplicationRunner
 *	@uses			Framework_Krypton_WebApplication 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			29.03.2009
 *	@version		0.1
 */
import( 'de.ceus-media.framework.krypton.WebApplication' );
import( 'de.ceus-media.framework.krypton.ApplicationRunner' );
/**
 *	Executes Web Application in Container secured by Exception Handler.
 *	@package		demos.krypton
 *	@extends		Framework_Krypton_ApplicationRunner
 *	@uses			Framework_Krypton_WebApplication 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			29.03.2009
 *	@version		0.1
 */
class Framework_Krypton_WebApplicationRunner extends Framework_Krypton_ApplicationRunner
{
	/**
	 *	Runs Web Application, called by Index Script.
	 *	@access		protected
	 *	@return		void
	 */
	protected function run()
	{
		Framework_Krypton_WebApplication::loadConstants();
		Framework_Krypton_WebApplication::$configFile	= CMC_KRYPTON_CONFIG_FILE;
		if( CMC_KRYPTON_MODE !== CMC_KRYPTON_MODE_PRODUCTION )
			$this->initErrorHandler();
#		$testError++;												//  Test Error
#		throw new Exception( "This is a Test Exception." );			//  Test Exception
		$application	= new Framework_Krypton_WebApplication();
		$application->act();
		$application->respond();
	}
}
?>