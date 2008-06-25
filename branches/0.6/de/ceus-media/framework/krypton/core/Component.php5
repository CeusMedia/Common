<?php
import( 'de.ceus-media.framework.krypton.core.Registry' );
import( 'de.ceus-media.framework.krypton.core.Template' );
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Abstract Basic Component for Actions and Views.
 *	@package		framework.krypton.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_Template
 *	@uses			View_Component_Elements
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@uses			Alg_TimeConverter
 *	@uses			UI_HTML_WikiParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.6
 */
/**
 *	Generic View with Language Support.
 *	@package		framework.krypton.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			Framework_Krypton_Core_Template
 *	@uses			View_Component_Elements
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@uses			Alg_TimeConverter
 *	@uses			UI_HTML_WikiParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.6
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
	/**	@var		UI_HTML_Elements	$html			HTML Elements */
	var $html		= null;
	/**	@var		Language			$language		Language Support */
	var $language	= null;
	/**	@var		Messenger			$messenger		Messenger Object */
	var $messenger	= null;
	/**	@var		Alg_TimeConverter	$tc				Time Converter Object */
	var $tc			= null;
	/**	@var		array				$words			Array of defined Words */
	var $words		= array();
	/**	@var		UI_HTML_WikiParser	$wiki			Wiki Parser Object */
	var $wiki		= null;
	/**	@var		array				$paths			Array of possible Path Keys in Config for Content Loading */
	public $paths	= array(
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
		$this->html			= new UI_HTML_Elements;
		$this->tc			= new Alg_TimeConverter;
		if( $useWikiParser )
		{
			import( 'de.ceus-media.ui.html.WikiParser' );
			$this->wiki			= new UI_HTML_WikiParser;
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
	public function cleanseString( $string, $flag = 16, $verbose = false )
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
	 *	@access		public
	 *	@param		string		$string		String to be shortened
	 *	@param		int			$length		Maximum length to cut at
	 *	@param		string		$mask		Mask to append to shortened string
	 *	@return		string
	 */
	public static function shortenString( $string, $length = 20, $mask = "..." )
	{
		$length	= abs( $length );
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
	 *	@access		public
	 *	@param		mixed		$price			Price to be formated
	 *	@param		string		$separator		Separator
	 *	@return		string
	 */
	public static function formatPrice( $number, $decimals = 2, $separatorDecimals = NULL, $separatorThousands = NULL )
	{
		$sepDecimals	= $separatorDecimals ? $separatorDecimals : ",";
		$sepThousands	= $separatorThousands ? $separatorThousands : ".";
		return number_format( $number, $decimals, $sepDecimals, $sepThousands );
	}

	public static function getPriceFromString( $string )
	{
		$string = trim( $string );
		if( !preg_match( "~^(\+|-)?([0-9]+|(?:(?:[0-9]{1,3}([.,' ]))+[0-9]{3})+)(([.,])[0-9]{1,2})?$~", $string, $r ) )
			throw new InvalidArgumentException( "This String is not a formated Price." );
			
		$pre	= $r['1'].$r['2'];
		$post	= "";
		if( !empty( $r['3'] ) )
		{
			$pre = $r['1'].preg_replace( "~[".$r['3']."]~", "", $r['2'] );
		}
		if( !empty( $r['5'] ) )
		{
			$post = ".".preg_replace( "~[".$r['5']."]~", "", $r['4'] );
		}
		return (float) number_format( $pre.$post, 2, ".", "" );		
	}

	//  --  FILE URI GETTERS  --  //
	/**
	 *	Returns Cache File URI.
	 *	@access		public
	 *	@param		string		$fileKey		File Name of Cache File
	 *	@return		string
	 */
	public function getCacheUri( $fileKey )
	{
		$config		= $this->registry->get( "config" );
		$basePath	= $config['paths.cache'];
		$fileName	= $basePath.$fileKey;
		return $fileName;
	}

	/**
	 *	Returns Content File URI.
	 *	@access		public
	 *	@param		string		$fileKey		File Name of Content File
	 *	@return		string
	 */
	public function getContentUri( $fileKey )
	{
		$config		= $this->registry->get( "config" );
		$session	= $this->registry->get( "session" );
		$language	= $session->get( 'language' )."/";

		$info		= pathinfo( $fileKey );

		//  --  CONTENT TYPE  --  //
		$extension	= $info['extension'];
		if( !$extension )
			throw new InvalidArgumentException( 'A Content File Key must have an Extension.' );
		if( !isset( $this->paths[$extension] ) )
			throw new InvalidArgumentException( 'No Content Type for Extension "'.$extension.'" is not registered.' );
		$pathType	= $this->paths[$extension];
		if( !isset( $config['paths.'.$pathType] ) )
			throw new RuntimeException( 'No Path for Content Type "'.$pathType.'" set.' );
		$typePath	= $config['paths.'.$pathType];

		//  --  PARTS  --  //
		$ext		= $extension ? ".".$extension : "";
		$parts		= explode( ".", $info['filename'] );
		$file		= array_pop( $parts );
		$path		= $parts ? implode( "/", $parts )."/" : "";
		$dirName	= preg_replace( "@^\./$@", "", trim( $info['dirname'] )."/" );

		$fileName	= $typePath.$language.$dirName.$path.$file.$ext;
		return $fileName;

/*
		$parts		= explode( ".", $fileKey );
		$ext		= array_pop( $parts );
		$file		= array_pop( $parts );
		if( !$file )
			return
		$path		= $parts ? implode( "/", $parts )."/" : "";
		$baseFile	= $path.$file.".".$ext;

		$pathType	= $this->paths[$ext];
		
		$basePath	= $config['paths.'.$pathType];
		$language	= $session->get( 'language' )."/";
		$fileName	= $basePath.$language.$baseFile;
		return $fileName;
*/
	}

	/**
	 *	Retursn HTTP Query String build from basic Parameter Pairs and additional Pairs, where a Pair will Value NULL will remove the Pair.
	 *	@access		public
	 *	@param		array		$basePairs		Array of basic Parameter Pairs
	 *	@param		array		$otherPairs		Arrayo of Pairs to add or remove (on Value NULL)
	 *	@return		string
	 */
	public function getQueryString( $basePairs, $otherPairs = array() )
	{
		foreach( $otherPairs as $key => $value )
		{
			if( $value === NULL )
			{
				unset( $basePairs[$key] );
				continue;
			}
			$basePairs[$key]	= $value;
		}
		$query	= http_build_query( $basePairs, '', "&" );
		return $query;
	}

	/**
	 *	Returns Template File URI.
	 *	@access		public
	 *	@param		string		$fileKey		File Name of Template File
	 *	@return		string
	 */
	public function getTemplateUri( $fileKey )
	{
		$config		= $this->registry->get( "config" );

		$basePath	= $config['paths.templates'];
		$baseName	= str_replace( ".", "/", $fileKey ).".html";

		$fileName = $basePath.$baseName;
		return $fileName;
	}

	//  --  EXCEPTION HANDLING  --  //
	/**
	 *	Handles different Exceptions by calling special Exception Handlers.
	 *	@access		public
	 *	@param	 	Exception	$e			Exception to handle
	 *	@param	 	string		$lanfile	Language File with Error Messages and Form Fields
	 *	@param	 	mixed		$section	Section Name as String or associative Array in combination with Form Names.
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
				$this->handleLogicExceptionOld( $e, $lanfile );
				break;
			case 'Framework_Krypton_Exception_SQL':
				new UI_HTML_Exception_TraceViewer( $e );
				$this->handleSqlException( $e );
				break;
			case 'Framework_Krypton_Exception_Template':
				$this->handleTemplateException( $e );
				break;
			case 'LogicException':
				$this->handleLogicException( $e, $lanfile );
				break;
			case 'Exception':
				throw $e;

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
				import( 'de.ceus-media.ui.html.exception.TraceViewer' );
				new UI_HTML_Exception_TraceViewer( $e );
				$this->messenger->noteFailure( $e->getMessage() );
		}
	}

	/**
	 *	Interprets Logic Exception and builds Error Message.
	 *	@access		protected
	 *	@param		LogicException		$e				Exception to handle.
	 *	@param		string				$filename		File Name of Language File
	 *	@param		string				$section		Section Name in Language Space
	 *	@return		void
	 */
	protected function handleLogicExceptionOld( Exception $e, $filename, $section = "msg" )
	{
		$words	= $this->words[$filename][$section];
		if( isset( $words[$e->key] ) )
			$msg	= $words[$e->key];
		else
			$msg	= $e->key;
		$this->messenger->noteError( $msg, $e->subject );
	}

	/**
	 *	Interprets Logic Exception and builds Error Message.
	 *	@access		protected
	 *	@param		LogicException		$e				Exception to handle.
	 *	@param		string				$filename		File Name of Language File
	 *	@param		string				$section		Section Name in Language Space
	 *	@return		void
	 */
	protected function handleLogicException( LogicException $e, $filename, $section = "msg" )
	{
		$words	= $this->words[$filename][$section];
		if( isset( $words[$e->getMessage()] ) )
			$msg	= $words[$e->getMessage()];
		else
			$msg	= $e->getMessage();
		$this->messenger->noteError( $msg, $e->getCode() );
	}

	/**
	 *	Interprets Errors of Validation Exception and sets built Error Messages.
	 *	@access		protected
	 *	@param		Framework_Krypton_Exception_Logic	$e				Exception to handle.
	 *	@param		string								$filename		File Name of Language File
	 *	@param		string								$section		Section Name in Language Space
	 *	@return		void
	 */
	protected function handleValidationException( Framework_Krypton_Exception_Validation $e, $filename, $section )
	{
		if( is_array( $section ) )
		{
			$form	= $e->getForm();
			if( $form && in_array( $form, array_keys( $section ) ) )
				$section	= $section[$form];
			else
				$section	= array_shift( $section );
		}
		$labels		= $this->words[$filename][$section];
		$messages	= $this->words['validator']['messages'];
		foreach( $e->getErrors() as $error )
		{
			if( $error instanceOf Framework_Krypton_Logic_ValidationError )
			{
				$msg	= $messages[$error->key];
				if( $error->key == "isClass" )
					if( isset( $messages["is".ucfirst( $error->edge )] ) )
						$msg	= $messages["is".ucfirst( $error->edge )];
				$msg	= preg_replace( "@%label%@", $labels[$error->field], $msg );
				$msg	= preg_replace( "@%edge%@", $error->edge, $msg );
				$msg	= preg_replace( "@%field%@", $error->field, $msg );
				$msg	= preg_replace( "@%prefix%@", $error->prefix, $msg );
				$this->messenger->noteError( $msg );
			}
		}
	}
	
	/**
	 *	Interprets SQL Exception and sets built Error Messages.
	 *	@access		protected
	 *	@param		Framework_Krypton_Exception_SQL		$e				Exception to handle.
	 *	@return		void
	 */
	protected function handleSqlException( Framework_Krypton_Exception_SQL $e )
	{
		$message	= $e->getMessage();
		$message	.= "<br/>".$e->sqlMessage;
		$this->messenger->noteFailure( $message );
	}
	
	/**
	 *	Interprets Template Exception and sets built Error Messages.
	 *	@access		protected
	 *	@param		Framework_Krypton_Exception_Template	$e				Exception to handle.
	 *	@return		void
	 */
	protected function handleTemplateException( Framework_Krypton_Exception_Template $e )
	{
		$list	= array();
		foreach( $e->getNotUsedLabels() as $label )
			$list[]	= preg_replace( "@<%(.*)%>@", "\\1", $label );
		$labels	= implode( ",", $list );
		$labels	= htmlentities( $labels );
		$this->messenger->noteFailure( $e->getMessage()."<br/><small>".$labels."</small>" );
	}

	//  --  FILE MANAGEMENT  --  //
	/**
	 *	Indicates whether a Cache File is existing.
	 *	@access		public
	 *	@param		string		$fileKey		File Name of Cache File
	 *	@return		bool
	 */
	public function hasCache( $fileKey )
	{
		$fileName	= $this->getCacheUri( $fileKey );
		return file_exists( $fileName );
	}
	
	/**
	 *	Indicates whether a Content File is existing.
	 *	@access		public
	 *	@param		string		$fileKey		File Name of Content File
	 *	@return		bool
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
			throw new Framework_Krypton_Exception_IO( "Content File '".$fileKey."' is not existing in '".$fileName."'." );

		//  --  FILE INTERPRETATION  --  //
		$file	= new File_Reader( $fileName );
		$content	= $file->readString();
		foreach( $data as $key => $value )
			$content	= str_replace( "[#".$key."#]", $value, $content );
		if( $this->wiki && preg_match( "@\.wiki$@i", $fileName ) )
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
#		try
#		{
			$fileName	= $this->getTemplateUri( $fileKey, $verbose );
			if( !file_exists( $fileName ) )
				throw new Framework_Krypton_Exception_IO( "Template '".$fileKey."' is not existing in '".$fileName."'." );

			$template	= new Framework_Krypton_Core_Template( $fileName, $data );
			return $template->create();
#		}
#		catch( Exception $e )
#		{
			$this->handleException( $e, 'main', 'exceptions' );
#		}
	}

	/**
	 *	Loads a Language File into Language Space, needs Session.
	 *	@access		public
	 *	@param		string		$fileName			File Name of Language File
	 *	@param		string		$section			Section Name in Language Space
	 *	@return		bool
	 */
	public function loadLanguage( $fileName, $section = FALSE, $verbose = FALSE )
	{
		$language	= $this->registry->get( 'language' );
		return $language->loadLanguage( $fileName, $section, $verbose );
	}

	/**
	 *	Loads File from Cache.
	 *	@access		public
	 *	@param		string		$fileName 			File Name of Cache File.
	 *	@return		string
	 */
	public function loadCache( $fileName )
	{
		$config	= $this->registry->get( 'config' );
		$url	= $config['paths.cache'].$fileName;
		return File_Reader::load( $url );
	}
	
	/**
	 *	Saves Content to a Cache File.
	 *	@access		public
	 *	@param		string		$fileName 			File Name of Cache File.
	 *	@param		string		$content			Content to save to Cache File
	 *	@return 	int
	 */
	public function saveCache( $fileName, $content )
	{
		import( 'de.ceus-media.file.Writer' );
		$config	= $this->registry->get( 'config' );
		$url	= $config['paths.cache'].$fileName;
		$file	= new File_Writer( $url, 0750 );
		return $file->writeString( $content );
	}
}
?>