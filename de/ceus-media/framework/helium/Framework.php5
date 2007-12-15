<?php
import( 'de.ceus-media.Reference' );
import( 'de.ceus-media.StopWatch' );
import( 'de.ceus-media.net.http.request.Receiver' );
import( 'de.ceus-media.framework.helium.Messenger' );
/**
 *	Main Class of Framework.
 *	@package		framework
 *	@subpackage		helium
 *	@uses			Reference
 *	@uses			StopWatch
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Messenger
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
/**
 *	Main Class of Framework.
 *	@package		framework
 *	@subpackage		helium
 *	@uses			Reference
 *	@uses			StopWatch
 *	@uses			Net_HTTP_Request_Receiver
 *	@uses			Messenger
 *	@uses			InterfaceViews
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
class Framework
{
	/**	@var	Reference	$ref			Reference */
	var $ref;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->ref	= new Reference;
		$this->ref->add( "stopwatch",	new StopWatch );		
		$this->ref->add( "messenger",	new Messenger );
		$this->ref->add( "request",		new Net_HTTP_Request_Receiver );
		$this->init();
	}
	
	/**
	 *	Creates references Objects and loads Configuration, to be overwritten.
	 *	@access		protected
	 *	@return		void
	 */
	protected function init()
	{
	}

	/**
	 *	Runs called Actions, to be overwritten.
	 *	@access		public
	 *	@return		void
	 */
	function runActions()
	{
	}
	
	/**
	 *	Creates Views by called Link and Rights of current User and returns HTML, to be overwritten.
	 *	@access		public
	 *	@return		string
	 */
	function buildViews()
	{
	}

	//  --  PRIVATE METHODS  --  //
	/**
	 *	Transforms requested Link into linked Class Names usind Separators.
	 *	@access		private
	 *	@param		string		$link					Link to transform to Class Name File
	 *	@param		string		$separator_link		Separator in Link
	 *	@param		string		$separator_class		Separator for Classes
	 *	@return		string
	 */
	function _transformLink( $link, $separator_folder = "__", $separator_class = "/", $separator_case = "_" )
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