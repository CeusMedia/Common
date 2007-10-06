<?php
import( 'de.ceus-media.framework.krypton.core.Registry' );
import( 'de.ceus-media.framework.krypton.core.Template' );
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.adt.TimeConverter' );
/**
 *	Abstract Basic Component for Actions and Views.
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_Template
 *	@uses			View_Component_Elements
 *	@uses			TimeConverter
 *	@uses			WikiParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.5
 */
/**
 *	Generic View with Language Support.
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_Template
 *	@uses			View_Component_Elements
 *	@uses			TimeConverter
 *	@uses			WikiParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.5
 */
/**
	T	S	J	C	E
	0	0	0	0	0	0			NONE
	0	0	0	0	1	1			EVENTS
	0	0	0	1	0	2			COMMENTS
	0	0	0	1	1	3			COMMENTS_AND_EVENTS
	0	0	1	0	0	4			SCRIPTS
	0	0	1	0	1	5			SCRIPTS_AND_EVENTS
	0	0	1	1	0	6			SCRIPTS_AND_COMMENTS
	0	0	1	1	1	7			SCRIPTS_AND_COMMENTS_AND_EVENTS
	0	1	0	0	0	8			STYLES
	0	1	0	0	1	9			STYLES_AND_EVENTS
	0	1	0	1	0	10			STYLES_AND_COMMENTS
	0	1	0	1	1	11			STYLES_AND_COMMENTS_AND_EVENTS
	0	1	1	0	0	12			STYLES_AND_SCRIPTS
	0	1	1	0	1	13			STYLES_AND_SCRIPTS_AND_EVENTS
	0	1	1	1	0	14			STYLES_AND_SCRIPTS_AND_COMMENTS
	0	1	1	1	1	15			STYLES_AND_SCRIPTS_AND_COMMENTS_AND_EVENTS
	1	0	0	0	0	16			ALL
*/
define( 'KRYPTON_CLEANSE_NONE',											0 );
define( 'KRYPTON_CLEANSE_EVENTS',										1 );
define( 'KRYPTON_CLEANSE_COMMENTS',										2 );
define( 'KRYPTON_CLEANSE_COMMENTS_AND_EVENTS',							3 );
define( 'KRYPTON_CLEANSE_SCRIPTS',										4 );
define( 'KRYPTON_CLEANSE_SCRIPTS_AND_EVENTS',							5 );
define( 'KRYPTON_CLEANSE_SCRIPTS_AND_COMMENTS',							6 );
define( 'KRYPTON_CLEANSE_SCRIPTS_AND_COMMENTS_AND_EVENTS',				7 );
define( 'KRYPTON_CLEANSE_STYLES',										8 );
define( 'KRYPTON_CLEANSE_STYLES_AND_EVENTS',							9 );
define( 'KRYPTON_CLEANSE_STYLES_AND_COMMENTS',							10 );
define( 'KRYPTON_CLEANSE_STYLES_AND_COMMENTS_AND_EVENTS',				11 );
define( 'KRYPTON_CLEANSE_STYLES_AND_SCRIPTS',							12 );
define( 'KRYPTON_CLEANSE_STYLES_AND_SCRIPTS_AND_EVENTS',				13 );
define( 'KRYPTON_CLEANSE_STYLES_AND_SCRIPTS_AND_COMMENTS',				14 );
define( 'KRYPTON_CLEANSE_STYLES_AND_SCRIPTS_AND_COMMENTS_AND_EVENTS',	15 );
define( 'KRYPTON_CLEANSE_ALL',											16 );
abstract class Framework_Krypton_Core_Component
{
	/**	@var		Framework_Krypton_Core_Registry		$registry		Registry of Objects */
	var $registry	= null;
	/**	@var		Elements		$html			HTML Elements */
	var $html		= null;
	/**	@var		Language		$language		Language Support */
	var $language	= null;
	/**	@var		Messenger		$messenger		Messenger Object */
	var $messenger	= null;
	/**	@var		TimeConverter	$tc				Time Converter Object */
	var $tc			= null;
	/**	@var		array			$words			Array of defined Words */
	var $words		= array();
	/**	@var		WikiParser		$wiki			Wiki Partser Object */
	var $wiki		= null;
	/**	@var		array			$_paths			Array of possible Path Keys in Config for Content Loading */
	var $_paths	= array(
			'html'	=> 'html',
/*			'wiki'	=> 'wiki',*/
			'txt'	=> 'text',
			);

	/**
	 *	Constructor, references Output Objects.
	 *	@access		public
	 *	@param		bool		$useWikiParser		Flag: make WikiParser a Member Object
	 *	@return		void
	 */
	public function __construct( $useWikiParser = false )
	{
		$this->registry		= Framework_Krypton_Core_Registry::getInstance();
		$this->html			= new Elements;
		$this->tc			= new TimeConverter;
		if( $useWikiParser )
		{
			import( 'de.ceus-media.ui.html.WikiParser' );
			$this->wiki			= new WikiParser;
		}
		$this->messenger	= $this->registry->get( 'messenger' );
		$this->language		= $this->registry->get( 'language' );
		$this->words		=& $this->language->getWords();
	}
	
	//  --  STRING MANIPULATION  --  //
	/**
	 *	Cleanse String by removing all HTML Tags or Scripts, Style, Comments or Event Attributes.
	 *	@todo		implement Events
	 */
	static function cleanseString( $string, $flag = 16, $verbose = false )
	{
		if( !is_int( $flag ) )
			$flag	= 16;

		if( $verbose )
		{
			xmp( "A: ".	( ( $flag >> 4 ) % 2 ) );				//  strip all Tags
			xmp( "S: ".	( ( $flag >> 3 ) % 2 ) );				//  strip Styles
			xmp( "J: ".	( ( $flag >> 2 ) % 2 ) );				//  strip Scripts
			xmp( "C: ".	( ( $flag >> 1 ) % 2 ) );				//  strip Comments
			xmp( "E: ".	( ( $flag >> 0 ) % 2 ) );				//  strip Event Attributes
		}

		if( ( $flag >> 4 ) % 2 )
			$string	= preg_replace( "@<[\/\!]*?[^<>]*?>@si", "", $string );
		if( ( $flag >> 3 ) % 2 )
			$string	= preg_replace( "@<style[^>]*?>.*?</style>@siU", "", $string );
		if( ( $flag >> 2 ) % 2 )
			$string	= preg_replace( "@<script[^>]*?>.*?</script>@si", "", $string );
		if( ( $flag >> 1 ) % 2 )
			$string	= preg_replace( "@<![\s\S]*?--[ \t\n\r]*>@", "", $string );
		if( ( $flag >> 0 ) % 2 )
			$string	= $string;
		return $string;
	}

	/**
	 *	Shortens a string by a maximum length with a mask.
	 *	@access		protected
	 *	@param		string		$string		String to be shortened
	 *	@param		int			$length		Maximum length to cut at
	 *	@param		string		$mask		Mask to append to shortened string
	 *	@return		string
	 */
	static protected function str_shorten( $string, $length = 20, $mask = "..." )
	{
		if( $length )
		{
			$inner_length	= $length - strlen( $mask );
			$sting_length	= strlen( $string );
			if( $sting_length > $inner_length )
				$string	= substr( $string, 0, $inner_length ).$mask;
		}
		return $string;
	}
	
	/**
	 *	Returns a float formated as Currency.
	 *	@access		protected
	 *	@param		mixed		$price			Price to be formated
	 *	@param		string		$separator		Separator
	 *	@return		string
	 */
	static protected function formatPrice( $price, $separator = "." )
	{
		$price	= (float)$price;
		ob_start();
		printf( "%01".$separator."2f", $price );
		return ob_get_clean();
	}


	//  --  FILE URI GETTERS  --  //
	protected function getContentUri( $fileKey, $verbose = false )
	{
		$config		= $this->registry->get( "config" );
		$session	= $this->registry->get( "session" );

		$parts		= explode( ".", $fileKey );
		$ext		= array_pop( $parts );
		$file		= array_pop( $parts );
		$path		= implode( "/", $parts )."/";
		$baseFile	= $path.$file.".".$ext;
		
		$pathType	= $this->_paths[$ext];
		$basePath	= $config['paths'][$pathType];
		$language	= $session->get( 'language' )."/";
		$fileName	= $basePath.$language.$baseFile;

		return $fileName;
	}

	protected function getTemplateUri( $fileKey, $verbose = false )
	{
		$config		= $this->registry->get( "config" );

		$basePath	= $config['paths']['templates'];
		$baseName	= str_replace( ".", "/", $fileKey ).".html";

		$fileName = $basePath.$baseName;
		return $fileName;
	}

	//  --  EXCEPTION HANDLING  --  //
	/**
	 *	Handles different Exceptions by calling special Exception Handlers.
	 *	@access		public
	 *	@param	 	Exception	$e		Exception to handle
	 *	@return		void
	 */
	public function handleException( $e, $lanfile, $section )
	{
		switch( get_class( $e ) )
		{
			case 'Framework_Krypton_Exception_Validation':
				$this->handleValidationException( $e, $lanfile, $section );
				break;
			case 'Framework_Krypton_Exception_Logic':
				$this->handleLogicException( $e, $lanfile );
				break;
			case 'Framework_Krypton_Exception_SQL':
				$this->handleSqlException( $e );
				break;
			case 'Framework_Krypton_Exception_Template':
				$this->handleTemplateException( $e );
				break;
			case 'Exception':
				throw new Exception( $e->getMessage() );

/*				$break	= ( !getEnv( 'PROMPT' ) && !getEnv( 'SHELL' ) ) ? "<br/>" : "\n";
				$code	= $e->getCode();
				$trace	= $e->getTrace();
				print( "Error: ".$e->getMessage().$break );
				print( "File: ".$e->getFile().$break );
				print( "Line: ".$e->getLine().$break );
				if( $code )
					print( "Code: ".$code.$break );
				foreach( $trace as $data )
				{
					extract( $data );
					$class	= isset( $class ) ? $class : "";
					$type	= isset( $type ) ? $type : "";
					print( str_repeat( "-", 70 ).$break );
					print( $class.$type.$function.$break );
					print( $file." [".$line."]".$break );
				}
				break;
*/			
			default:
				$this->messenger->noteFailure( $e->getMessage() );
		}
	}

	/**
	 *	Interprets Logic Errors and builds Error Message.
	 *	@access		protected
	 *	@param		array		$errors			Array of Errorsets of Errors Objects built be Logic.
	 *	@param		string		$filename		File Name of Language File
	 *	@param		string		$section		Section Name in Language Space
	 *	@return		void
	 */
	protected function handleLogicException( Framework_Krypton_Exception_Logic $e, $filename, $section = "msg" )
	{
		$words	= $this->words[$filename][$section];
		if( isset( $words[$e->key] ) )
			$msg	= $words[$e->key];
		else
			$msg	= $e->key;
		$this->messenger->noteError( $msg, $e->subject );
	}

	/**
	 *	Interprets Validation Errors and sets built Error Messages.
	 *	@access		protected
	 *	@param		array		$errors			Array of Errorsets of Errors Objects built be Logic.
	 *	@param		string		$filename		File Name of Language File
	 *	@param		string		$section		Section Name in Language Space
	 *	@return		void
	 */
	protected function handleValidationException( Framework_Krypton_Exception_Validation $e, $filename, $section )
	{
		$labels		= $this->words[$filename][$section];
		$validator	= $this->words['validator'];
		foreach( $e->getErrors() as $error )
		{
			if( $error instanceOf Framework_Krypton_Logic_ValidationError )
			{
				$msg	= $validator[$error->type][$error->key];
				$msg	= preg_replace( "@%label%@", $labels[$error->field], $msg );
				$msg	= preg_replace( "@%edge%@", $error->edge, $msg );
				$msg	= preg_replace( "@%field%@", $error->field, $msg );
				$msg	= preg_replace( "@%prefix%@", $error->prefix, $msg );
				$this->messenger->noteError( $msg );
			}
		}
	}
	
	protected function handleSqlException( Framework_Krypton_Exception_SQL $e )
	{
		$message	= $e->getMessage();
		if( is_string( $e->error ) )
			$message	.= "<br/>".$e->error;
		else if( is_array( $e->error ) )
			$message	.= "<br/>".$e->error[2];
		$this->messenger->noteFailure( $message );
	}
	
	protected function handleTemplateException( Framework_Krypton_Exception_Template $e )
	{
		$labels	= implode( ",", $e->getNotUsedLabels() );
		$labels	= htmlentities( $labels );
		$this->messenger->noteFailure( $e->getMessage()."<br/><small>".$labels."</small>" );
	}

	//  --  FILE MANAGEMENT  --  //
	/**
	 *	@access		public
	 */
	public function hasContent( $fileKey )
	{
		$fileName	= $this->getContentUri( $fileKey );
		return file_exists( $fileName );
	}
	
	/**
	 *	Loads Content File in HTML or DokuWiki-Format returns Content.
	 *	@access		public
	 *	@param		string		$fileKey			File Name (with Extension) of Content File (HTML|Wiki|Text), i.E. home.html leads to {CONTENT}/{LANGUAGE}/home.html
	 *	@param		array		$data				Data for Insertion in Template
	 *	@param		bool		$verbose			Flag: remark File Name
	 *	@return		string
	 */
	public function loadContent( $fileKey, $data = array(), $verbose = false )
	{
		$fileName	= $this->getContentUri( $fileKey, $verbose );
		if( !file_exists( $fileName ) )							//  check file
		{
			$this->messenger->noteFailure( "Content File '".$fileKey."' is not existing in '".$fileName."'." );
			return "";
		}

		//  --  FILE INTERPRETATION  --  //
		$file	= new File( $fileName );
		$content	= $file->readString();
		foreach( $data as $key => $value )
			$content	= str_replace( "[#".$key."#]", $value, $content );
		if( preg_match( "@\.wiki$@i", $fileName ) )
		{
			$content = "<div class='wiki'>".$this->wiki->parse( $content )."</div>";
		}
		if( preg_match( "@\.html$@i", $fileName ) )
		{
		}
		else
			$this->messenger->noteFailure( "Content Type for File '".$fileKey."' is not implemented." );
		return $content;
	}

	/**
	 *	Loads Template File and returns Content.
	 *	@access		public
	 *	@param		string		$fileKey			Template Name (namespace(.class).view, i.E. example.add)
	 *	@param		array		$data				Data for Insertion in Template
	 *	@param		bool		$verbose			Flag: remark File Name
	 *	@return		string
	 */
	public function loadTemplate( $fileKey, $data = array(), $verbose = false )
	{
		try
		{
			$fileName	= $this->getTemplateUri( $fileKey, $verbose );
			if( !file_exists( $fileName ) )
				throw new Framework_Krypton_Exception_IO( "Template '".$fileKey."' is not existing in '".$fileName."'." );

			$template	= new Framework_Krypton_Core_Template( $fileName, $data );
			return $template->create();
		}
		catch( Framework_Krypton_Exception_Template $e )
		{
			$labels	= implode( ", ", $e->getNotUsedLabels() );
			$labels	= htmlentities( $labels );
			throw new Framework_Krypton_Exception_IO( $e->getMessage()."<br/><small>".$labels."</small>" );
		}
		catch( Exception $e )
		{
			throw new Framework_Krypton_Exception_IO( $e->getMessage()."<br/><small>".$labels."</small>" );
		}
		return;
	}

	/**
	 *	Loads a Language File into Language Space, needs Session.
	 *	@access		protected
	 *	@param		string		$fileName			File Name of Language File
	 *	@param		string		$section			Section Name in Language Space
	 *	@return		void
	 */
	protected function loadLanguage( $fileName, $section = false, $verbose = true )
	{
		$language	= $this->registry->get( 'language' );
		$language->loadLanguage( $fileName, $section, $verbose );
	}

	/**
	 *	@access		protected
	 */
	protected function loadCache( $fileName )
	{
		$config	= $this->registry->get( 'config' );
		$url	= $config['paths']['cache'].$fileName;
		$file	= new File( $url );
		return $file->readString();
	//	!( file_exists( $uri ) && filemtime( $uri ) + 3600 > time() )
		return implode( "", file( $url ) );
	}
	
	/**
	 *	Overwrites Values from Database by Request Values.
	 *	@access		protected
	 *	@param		array		$data		Array of Values from Database
	 *	@param		string		$prefix		Prefix of Request Values
	 *	@return 	array
	 */
	protected function saveCache( $fileName, $content )
	{
		$config	= $this->registry->get( 'config' );
		$url	= $config['paths']['cache'].$fileName;
		$file	= new File( $url, 0750 );
		$file->writeString( $content );
	}
}
?>