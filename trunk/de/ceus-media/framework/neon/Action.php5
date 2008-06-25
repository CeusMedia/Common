<?php
import( 'de.ceus-media.adt.Reference' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Generic Action Handler.
 *	@package		framework.neon
 *	@uses			ADT_Reference
 *	@uses			File_INI_Reader
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
/**
 *	Generic Action Handler.
 *	@package		framework.neon
 *	@uses			ADT_Reference
 *	@uses			File_INI_Reader
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
class Framework_Neon_Action
{
	/**	@var	array			$actions	Array of Action events and methods */
	protected $actions	= array();
	/**	@var	ADT_Reference	$ref		Object Reference */
	protected $ref;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public public function __construct()
	{
		$this->ref			= new ADT_Reference();
		$this->tc			= new Alg_TimeConverter;
		$this->messenger	= $this->ref->get( 'messenger' );
		$this->words		=& $this->ref->get( 'words' );
	}

	/**
	 *	Calls Actions by checking calls in Request.
	 *	@access		public
	 *	@return		void
	 */
	public function act()
	{
		$request	= $this->ref->get( 'request' );
		foreach( $this->actions as $event => $action )
			if( $request->has( $event ) )
				$this->$action();
	}

	/**
	 *	Adds an Action by an event name and a method name.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@param		string		$action			Method name of Action
	 *	@return		void
	 */
	public function add( $event, $action = false )
	{
		if( !$action )
			$action = $event;
		$this->actions[$event]	= $action;
	}

	/**
	 *	Returns a float formated as Currency.
	 *	@access		public
	 *	@param		mixed		$price			Price to be formated
	 *	@param		string		$separator		Separator
	 *	@return		string
	 */
	public function formatPrice( $price, $separator = "." )
	{
		$price	= (float)$price;
		ob_start();
		printf( "%01".$separator."2f", $price );
		return ob_get_clean();
	}

	/**
	 *	Indicates whether an Action is registered.
	 *	@access		public
	 *	@param		string		$event			Event name of Action
	 *	@return		bool
	 */
	public function has( $event )
	{
		return isset( $this->actions[$event]);
	}

	/**
	 *	Loads a Language File into Language Space, needs Session.
	 *	@access		public
	 *	@param		string		$filename			File Name of Language File
	 *	@param		string		$section			Section Name in Language Space
	 *	@return		void
	 */
	public function loadLanguage( $filename, $section = false, $verbose = true )
	{
		$language	=& $this->ref->get( 'language' );
		$language->loadLanguage( $filename, $section, $verbose );
	}

	/**
	 *	Loads Template File and returns Content.
	 *	@access		public
	 *	@param		string		$_template			Template Name (namespace(.class).view, i.E. example.add)
	 *	@param		array		$data				Data for Insertion in Template
	 *	@param		string		$separator_link		Separator in Language Link
	 *	@param		string		$separator_class		Separator for Language File
	 *	@return		string
	 */
	public function loadTemplate( $_template, $data = array(), $separator_link = ".", $separator_file = "/" )
	{
		$config	=& $this->ref->get( "config" );
		$_file	= str_replace( $separator_link, $separator_file, $_template );

		$_template_theme	= "";		
		if( isset( $config['layout']['template_theme'] ) )
			if( $config['layout']['template_theme'] )
				$_template_theme	= $config['layout']['template_theme']."/";
			
		extract( $data );
		$_content	= "";
		$_filename	= $config['paths']['templates'].$_template_theme.$_file.".phpt";
		if( file_exists( $_filename ) )
			$_content = include( $_filename );
		else
			$this->messenger->noteFailure( "Template '".$_filename."' for View '".$_template."' is not existing" );
		return $_content;
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
			unset( $this->actions[$event] );
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