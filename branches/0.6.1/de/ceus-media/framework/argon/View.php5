<?php
import( 'de.ceus-media.adt.Reference' );
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.ui.html.Paging' );
import( 'de.ceus-media.alg.TimeConverter' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.ui.html.WikiParser' );
/**
 *	Generic View with Language Support.
 *	@package		framework.argon
 *	@uses			ADT_Reference
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_Paging
 *	@uses			Alg_TimeConverter
 *	@uses			File_INI_Reader
 *	@uses			WikiParser
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.3
 */
/**
 *	Generic View with Language Support.
 *	@package		framework.argon
 *	@uses			ADT_Reference
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_Paging
 *	@uses			Alg_TimeConverter
 *	@uses			File_INI_Reader
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@uses			WikiParser
 *	@since			01.12.2005
 *	@version		0.3
 */
class Framework_Argon_View
{
	/**	@var	array						$_paths		Array of possible Path Keys in Config for Content Loading */
	var $_paths	= array(
			'html'	=> 'html',
			'wiki'	=> 'wiki',
			'txt'	=> 'text',
			);
	/**	@var	ADT_Reference				$ref		Reference */
	var $ref;
	/**	@var	Alg_TimeConverter			$tc			Time Converter */
	var $tc;
	/**	@var	UI_HTML_Elements			$html		HTML Elements */
	var $html;
	/**	@var	WikiParser					$wiki		Wiki Parser */
	var $wiki;
	/**	@var	Framework_Argon_Language	$language	Language Support */
	var $language;
	/**	@var	array						$words		Array of all Words */
	var $words;
	/**	@var	Framework_Argon_Messenger	$messenger	Messenger */
	var $messenger;


	/**
	 *	Constructor, references Output Objects.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->ref			= new ADT_Reference();
		$this->tc			= new Alg_TimeConverter;
		$this->html			= new UI_HTML_Elements;
		$this->wiki			= new WikiParser;
		$this->language		= $this->ref->get( 'language' );
		$this->words		=& $this->language->words;
		$this->messenger	=& $this->ref->get( 'messenger' );
	}

	/**
	 *	Builds HTML for Paging of Lists.
	 *	@access		public
	 *	@param		int		$count_all		Total mount of total entries
	 *	@param		int		$limit			Maximal amount of displayed entries
	 *	@param		int		$offset			Currently offset entries
	 *	@param		array	$options		Array of Options to set
	 *	@return		string
	 */
	public function buildPaging( $count_all, $limit, $offset, $options = array())
	{
		$request	=& $this->ref->get( "request" );
		$link		= $request->get( 'link');
		$words		= $this->words['main']['paging'];

		$p	= new UI_HTML_Paging;
		$p->setOption( 'uri',		"index.php" );
		$p->setOption( 'param',		array( 'link'	=> $link ) );
		$p->setOption( 'indent',	"" );

		foreach( $options as $key => $value )
			$p->setOption( $key, $value );
		
		$p->setOption( 'text_first',	$words['first'] );
		$p->setOption( 'text_previous',	$words['previous'] );
		$p->setOption( 'text_next',		$words['next'] );
		$p->setOption( 'text_last',		$words['last'] );
		$p->setOption( 'text_more',		$words['more'] );
		
		$pages	= $p->build( $count_all, $limit, $offset );
		return $pages;
	}

	/**
	 *	Highlights a String within a String.
	 *	@access		public
	 *	@param		string		$text			String to highlight within
	 *	@param		string		$searches		Array of String to highlight
	 *	@return 	string
	 */
	public function hilight( $text, $searches )
	{
		if( is_array( $searches ) && count( $searches ) )
		{
			$list	= array();
			foreach( $searches as $search )
			{
				$length	= strlen( $search );
				if( !isset( $list[$length] ) )
					$list[$length]	= array();
				$list[$length][]	= $search;
			}
			krsort( $list );
			$searches	= array();
			$i=0;
			foreach( $list as $length )
				foreach( $length as $search )
				{
					$matches = array();
					preg_match_all( "/".$search."/si", $text, $matches );
					foreach( $matches[0] as $match)
					{
						$text	= preg_replace( "/".$match."/si", "[#".$i."#]", $text, 1 );
						$searches[$i++] = $match;
					}
				}
			foreach( $searches as $key => $search )
				$text	= preg_replace( "/\[#".$key."#\]/", "<span class='highlight'>".$search."</span>", $text, 1 );
		}
		return $text;
	}

	/**
	 *	Shortens a string by a maximum length with a mask.
	 *	@access		public
	 *	@param		string		$string		String to be shortened
	 *	@param		int			$length		Maximum length to cut at
	 *	@param		string		$mask		Mask to append to shortened string
	 *	@return		string
	 */
	public function str_shorten( $string, $length = 20, $mask = "..." )
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
	 *	Transforms a formated String to HTML.
	 *	@access		public
	 *	@param		string		$string		String to be transformed
	 *	@return		string
	 */
	public function transform( $string )
	{
		$string	= htmlspecialchars( $string );
		$pattern	= "@(\[d\])(.*)(\[/d\])@si";
		$string	= preg_replace_callback( $pattern, array( $this, "transform_callback" ), $string );
		$pattern	= "@(\[k\])(.*)(\[/k\])@si";
		$string	= preg_replace_callback( $pattern, array( $this, "transform_callback" ), $string );
		$pattern	= "@(\[g\])(.*)(\[/g\])@si";
		$string	= preg_replace_callback( $pattern, array( $this, "transform_callback" ), $string );
		$string	= nl2br( $string );
		return $string;
	}
	
	/**
	 *	Callback for String Transformation.
	 *	@access		public
	 *	@param		string		$string		String to be transformed
	 *	@return		string
	 */
	public function transform_callback( $matches )
	{
		if( $matches[0] )
		{
			if( substr( $matches[1], 1, 1 ) == "d" )
				$string	= "<b>".$matches[2]."</b>";
			else if( substr( $matches[1], 1, 1 ) == "k" )
				$string	= "<em>".$matches[2]."</em>";
			else if( substr( $matches[1], 1, 1 ) == "g" )
				$string	= "<font size='+1'>".$matches[2]."</font>";
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
	public function formatPrice( $price, $separator = "." )
	{
		$price	= (float)$price;
		ob_start();
		printf( "%01".$separator."2f", $price );
		return ob_get_clean();
	}

	public function hasContent( $file )
	{
		$config		=& $this->ref->get( "config" );
		$session	=& $this->ref->get( "session" );

		$parts		= explode( ".", $file );
		$ext		= array_pop( $parts );
		$file		= array_pop( $parts );
		$basename	= $file.".".$ext;
		
		$path		= $this->_paths[$ext];
		$uri			= $config['paths'][$path].$session->get( 'language' )."/".implode( "/", $parts )."/";
//		$theme		= $config['layout']['template_theme'] ? $config['layout']['template_theme']."/" : "";
		$theme		= "";
		$filename		= $uri.$theme.$basename;
		
		return file_exists( $filename );
	}

	/**
	 *	Loads Content File in HTML or DokuWiki-Format returns Content.
	 *	@access		public
	 *	@param		string		$_file				File Name (with Extension) of Content File (HTML|Wiki|Text), i.E. home.html leads to {CONTENT}/{LANGUAGE}/home.html
	 *	@param		array		$data				Data for Insertion in Template
	 *	@return		string
	 */
	public function loadContent( $_file, $data = array() )
	{
		$config		=& $this->ref->get( "config" );
		$session	=& $this->ref->get( "session" );

		$parts		= explode( ".", $_file );
		$ext		= array_pop( $parts );
		$file		= array_pop( $parts );
		$basename	= $file.".".$ext;
		
		$path		= $this->_paths[$ext];
		$uri			= $config['paths'][$path].$session->get( 'language' )."/";
		if( count( $parts ) )
			$uri	.= implode( "/", $parts )."/";
//		$theme		= $config['layout']['template_theme'] ? $config['layout']['template_theme']."/" : "";
		$theme		= "";
		$filename		= $uri.$theme.$basename;
		
		if( file_exists( $filename ) )
		{
			$file	= new File_Reader( $filename );
			$content	= $file->readString();
			foreach( $data as $key => $value )
				$content	= str_replace( "[#".$key."#]", $value, $content );
			if( $ext == "wiki" )
			{
				$content = "<div class='wiki'>".$this->wiki->parse( $content )."</div>";
			}
			else if( $ext == "html" )
			{
			}
			else
				$this->messenger->noteFailure( "Content Type for File '".$filename."' is not implemented." );
		}
		else
			$this->messenger->noteFailure( "Content File '".$filename."' is not existing." );
		return $content;
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
	 *	Loads a Language File into Language Space, needs Session.
	 *	@access		public
	 *	@param		string		$section		Section Name in Language Space
	 *	@param		string		$filename		File Name of Language File
	 *	@return		void
	 */
	public function loadLanguage( $section, $filename = false, $verbose = true )
	{
		$language	=& $this->ref->get( 'language' );
		$language->loadLanguage( $section, $filename = false, $verbose );
	}

	public function loadCache( $filename )
	{
		$config	= $this->ref->get( 'config' );
		$url	= $config['paths']['cache'].$filename;
		$file	= new File_Reader( $url );
		return $file->readString();
	//	!( file_exists( $uri ) && filemtime( $uri ) + 3600 > time() )
		return implode( "", file( $url ) );
	}
	
	public function saveCache( $filename, $content )
	{
		$config	= $this->ref->get( 'config' );
		$url	= $config['paths']['cache'].$filename;
		$file	= new File_Writer( $url, 0750 );
		$file->writeString( $content );
	}
}
?>