<?php
import( 'de.ceus-media.adt.Reference' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Generic Action Handler.
 *	@package		framework.argon
 *	@uses			ADT_Reference
 *	@uses			File_INI_Reader
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
/**
 *	Generic Action Handler.
 *	@package		framework.argon
 *	@uses			ADT_Reference
 *	@uses			File_INI_Reader
 *	@uses			Alg_TimeConverter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.1
 */
class Framework_Argon_Action
{
	/**	@var	ADT_Reference			$ref			Reference */
	var $ref;
	/**	@var	Alg_TimeConverter	$tc				Time Converter */
	var $tc;
	/**	@var	Messenger			$messenger		Messenger */
	var $messenger;
	/**	@var	array				$words			Array of all Words */
	var $words;

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
		$this->words		=& $this->ref->get( 'words' );
	}

	/**
	 *	Calls Actions by checking calls in Request.
	 *	@access		public
	 *	@return		void
	 */
	public function act()
	{
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
		$language	=& $this->ref->get( 'language' );
		$language->loadLanguage( $section, $filename = false, $verbose );
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
		$_filename	= "templates/".$_template_theme.$_file.".phpt";
		if( file_exists( $_filename ) )
			$_content = include( $_filename );
		else
			$this->messenger->noteFailure( "Template '".$_filename."' for View '".$_template."' is not existing" );
		return $_content;
	}

	/**
	 *	Restart application after realizing an Action.
	 *	@access		public
	 *	@param		string		$request			Request URL with Query String
	 *	@return		void
	 */
	public function restart( $request )
	{
		$session	= $this->ref->get( 'session' );
		$session->close();
		header( "Location: ".$request );
		die;
	}
}
?>