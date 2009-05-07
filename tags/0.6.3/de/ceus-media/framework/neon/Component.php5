<?php
import( 'de.ceus-media.adt.Reference' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Generic Action Handler.
 *	@package		framework.neon
 *	@uses			ADT_Reference
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
/**
 *	Generic Action Handler.
 *	@package		framework.neon
 *	@uses			ADT_Reference
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
abstract class Framework_Neon_Component
{
	/**	@var		ADT_Reference				$ref				Object Reference */
	protected $ref;
	/**	@var		Framework_Neon_Messenger	$messenger			Messenger */
	protected $messenger;
	/**	@var		Alg_TimeConverter			$actions			Array of Action events and methods */
	protected $tc								= array();
	/**	@var		array						$words				Array of all Words */
	protected $words;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public public function __construct()
	{
		$this->ref			= new ADT_Reference();
		$this->tc			= new Alg_TimeConverter;
		$this->config	 	= $this->ref->get( 'config' );
		$this->request	 	= $this->ref->get( 'request' );
		$this->session		= $this->ref->get( 'session' );
		$this->messenger	= $this->ref->get( 'messenger' );
		$this->words		=& $this->ref->get( 'words' );
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
	 *	Loads a Language File into Language Space, needs Session.
	 *	@access		public
	 *	@param		string		$filename			File Name of Language File
	 *	@param		string		$section			Section Name in Language Space
	 *	@return		void
	 */
	public function loadLanguage( $filename, $section = false, $verbose = true )
	{
		$language	= $this->ref->get( 'language' );
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
		$config	= $this->ref->get( "config" );
		$_file	= str_replace( $separator_link, $separator_file, $_template );

		$_template_theme	= "";		
		if( isset( $config['layout']['template_theme'] ) )
			if( $config['layout']['template_theme'] )
				$_template_theme	= $config['layout']['template_theme']."/";
		
		$_content	= "";
		$_path		= isset( $config['paths']['templates'] ) ? $config['paths']['templates'] : "templates/";
		$_filename	= $_path.$_template_theme.$_file.".phpt";
		extract( $data );
		if( file_exists( $_filename ) )
		{
			$_content = include( $_filename );
		}
		else
			$this->messenger->noteFailure( "Template '".$_filename."' for View '".$_template."' is not existing" );
		return $_content;
	}
}
?>