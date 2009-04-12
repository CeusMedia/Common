<?php
/**
 *	Executes Error Page Handler in Container secured by Exception Handler.
 *	@package		mv2
 *	@extends		ApplicationRunner
 *	@uses			Framework_Krypton_ErrorPageHandler
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.04.2009
 *	@version		0.1
 */
import( 'de.ceus-media.framework.krypton.ErrorPageHandler' );
import( 'de.ceus-media.framework.krypton.ApplicationRunner' );
/**
 *	Executes Error Page Handler in Container secured by Exception Handler.
 *	@package		mv2
 *	@extends		ApplicationRunner
 *	@uses			Framework_Krypton_ErrorPageHandler
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.04.2009
 *	@version		0.1
 */
class Framework_Krypton_ErrorPageRunner extends Framework_Krypton_ApplicationRunner
{
	protected function run()
	{
		Framework_Krypton_ErrorPageHandler::loadConstants();
		Framework_Krypton_ErrorPageHandler::$configFile	= CMC_KRYPTON_CONFIG_FILE;
		new Framework_Krypton_ErrorPageHandler();
	}
}
?>