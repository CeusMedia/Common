<?php
import( 'de.ceus-media.adt.Reference' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.validation.DefinitionValidator' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Generic Action Handler.
 *	@package		framework.helium
 *	@uses			ADT_Reference
 *	@uses			Alg_TimeConverter
 *	@uses			File_INI_Reader
 *	@uses			DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
/**
 *	Generic Action Handler.
 *	@package		framework.helium
 *	@uses			ADT_Reference
 *	@uses			Alg_TimeConverter
 *	@uses			File_INI_Reader
 *	@uses			DefinitionValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
class Framework_Helium_Action
{
	/**	@var	array			$_actions		Array of Action events and methods */
	var $_actions	= array();
	/**	@var	ADT_Reference	$ref			Reference */
	var $ref;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->ref			= new ADT_Reference();
		$this->tc			= new Alg_TimeConverter;
		$this->messenger	= $this->ref->get( 'messenger' );
		$this->lan			=& $this->ref->get( 'language' );
	}

	/**
	 *	Calls Actions by checking calls in Request.
	 *	@access		public
	 *	@return		void
	 */
	public function act()
	{
		$request	=& $this->ref->get( 'request' );
		foreach( $this->_actions as $event => $action )
		{
			if( NULL !== $request->get( $event ) )
				return $this->$action();
		}
	}

	/**
	 *	Adds an Action by an event name and a method name.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@param		string		$action			Method name of Action
	 *	@return		void
	 */
	public function add( $event, $action )
	{
		$this->_actions[$event]	= $action;
	}

	/**
	 *	Indicates whether an Action is registered.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@return		bool
	 */
	public function has( $event )
	{
		return isset( $this->_actions[$event]);
	}

	/**
	 *	Loads a Language File into Language Space, needs Session.
	 *	@access		public
	 *	@param		string		$section			Section Name in Language Space
	 *	@param		string		$filename			File Name of Language File
	 *	@return		void
	 */
	public function loadLanguage( $section, $filename = false, $verbose = true )
	{
		$session	= $this->ref->get( 'session' );
		if( !$filename )
			$filename	= $section;
		$uri	= "languages/".$session->get( 'language' )."/".$filename.".lan";
		if( file_exists( $uri ) )
		{
			$ir	= new File_INI_Reader( $uri, true );
			$this->lan[$section]	= $ir->toArray( true );
			return true;
		}
		else if( $verbose )
			$this->messenger->noteFailure( "Language File '".$filename."' is not existing in '".$uri."'" );
		return false;
	}

	/**
	 *	Removes a registered Action.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@return		void
	 */
	public function remove( $event )
	{
		if( $this->has( $event ) )
			unset( $this->_actions[$event] );
	}

	/**
	 *	Restart application after realizing an Action.
	 *	@access		public
	 *	@param		string		$request			Request URL with Query String
	 *	@return		void
	 */
	public function restart( $request )
	{
		header( "Location: ".$request );
		die;
	}
}
?>