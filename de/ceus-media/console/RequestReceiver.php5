<?php
import( 'de.ceus-media.adt.list.Dictionary' );
/**
 *	Handler for Console Requests.
 *	@package		console
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.02.2007
 *	@version		0.1
 */
/**
 *	Handler for Console Requests.
 *	@package		console
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			12.02.2007
 *	@version		0.1
 */
class Console_RequestReceiver extends ADT_List_Dictionary
{
	/**
	 *	Constructor, receives Console Arguments.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$count	= 0;
		global $argv;
		//$argv = array("runJob.php5", "Job_SaleTermination" , "cmd=");	
		foreach( $argv as $argument )
		{
			if( substr_count( $argument, "=" ) )
			{
				$parts	= explode( "=", $argument );
				$key	= array_shift( $parts );
				$this->pairs[$key]	= (string)implode( "=", $parts );
			}
			else
				$this->pairs[$count++]	= $argument;
		}
	}
}
?>