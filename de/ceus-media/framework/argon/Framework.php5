<?php
import( 'de.ceus-media.adt.Reference' );
import( 'de.ceus-media.StopWatch' );
import( 'de.ceus-media.net.http.request.Receiver' );
import( 'de.ceus-media.framework.argon.Messenger' );
/**
 *	Main Class of Framework.
 *	@package		framework
 *	@subpackage		argon
 *	@uses			ADT_Reference
 *	@uses			StopWatch
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Framework_Argon_Messenger
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
/**
 *	Main Class of Framework.
 *	@package		framework
 *	@subpackage		argon
 *	@uses			ADT_Reference
 *	@uses			StopWatch
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Framework_Argon_Messenger
 *	@uses			InterfaceViews
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
class Framework
{
	/**	@var	ADT_Reference	$ref			Reference */
	var $ref;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->ref	= new ADT_Reference;
		$this->ref->add( "stopwatch",	new StopWatch );		
		$this->ref->add( "messenger",	new Framework_Argon_Messenger );
		$this->ref->add( "request",		new Net_HTTP_Request_Receiver );
		$this->_init();
	}
	
	/**
	 *	Creates references Objects and loads Configuration, to be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	protected function _init()
	{
	}

	/**
	 *	Runs called Actions, to be overwritten.
	 *	@access		public
	 *	@return		void
	 */
	public function runActions()
	{
	}
	
	/**
	 *	Creates Views by called Link and Rights of current User and returns HTML, to be overwritten.
	 *	@access		public
	 *	@return		string
	 */
	public function buildViews()
	{
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Transforms requested Link into linked Class Names usind Separators.
	 *	@access		protected
	 *	@param		string		$link				Link to transform to Class Name File
	 *	@param		string		$separator_link		Separator in Link
	 *	@param		string		$separator_class	Separator for Classes
	 *	@return		string
	 */
	protected function _transformLink( $link, $separator_folder = "__", $separator_class = "/", $separator_case = "_" )
	{
		$words	= explode( $separator_folder, $link );
		$count	= count( $words );
		for( $i=0; $i<$count; $i++ )
		{
			if( $separator_class && $i == ( $count - 1 ) )
				$class	= ucfirst( strtolower( $words[$i] ) );
			else
				$class	= ucfirst( strtolower( $words[$i] ) );
			$words[$i] = $class;
		}
		$link		= implode( $separator_class, $words );

		$words	= explode( $separator_case, $link );
		$count	= count( $words );
		for( $i=0; $i<$count; $i++ )
		{
			$class	= ucfirst( ucfirst( $words[$i] ) );
			$words[$i] = $class;
		}
		$link		= implode( "", $words );

		return $link;
	}
}
?>