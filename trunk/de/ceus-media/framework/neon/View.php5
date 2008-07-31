<?php
import( 'de.ceus-media.framework.neon.Component' );
import( 'de.ceus-media.adt.Reference' );
import( 'de.ceus-media.ui.html.Elements' );
import( 'de.ceus-media.ui.html.Paging' );
import( 'de.ceus-media.alg.TimeConverter' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.ui.html.WikiParser' );
/**
 *	Generic View with Language Support.
 *	@package		framework
 *	@subpackage		neon
 *	@extends		Framework_Neon_Component
 *	@uses			ADT_Reference
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_Paging
 *	@uses			Alg_TimeConverter
 *	@uses			File_INI_Reader
 *	@uses			UI_HTML_WikiParser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.12.2005
 *	@version		0.3
 */
/**
 *	Generic View with Language Support.
 *	@package		framework
 *	@subpackage		neon
 *	@extends		Framework_Neon_Component
 *	@uses			ADT_Reference
 *	@uses			UI_HTML_Elements
 *	@uses			UI_HTML_Paging
 *	@uses			Alg_TimeConverter
 *	@uses			File_INI_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@uses			UI_HTML_WikiParser
 *	@since			01.12.2005
 *	@version		0.3
 *	@todo			Code Documentation
 */
class Framework_Neon_View extends Framework_Neon_Component
{
	/**	@var	array		$_paths			Array of possible Path Keys in Config for Content Loading */
	var $_paths	= array(
			'html'	=> 'html',
			'wiki'	=> 'wiki',
			'txt'	=> 'text',
			);
	/**	@var	UI_HTML_Elements			$html			HTML Elements */
	var $html;
	/**	@var	UI_HTML_WikiParser			$wiki			Wiki Parser */
	var $wiki;
	/**	@var	Framework_Neon_Language		$language		Language Support */
	var $language;

	/**
	 *	Constructor, references Output Objects.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $useWikiParser = FALSE )
	{
		parent::__construct();
		$this->language		= $this->ref->get( 'language' );
		$this->html			= new UI_HTML_Elements;
		if( $useWikiParser )
			$this->wiki			= new UI_HTML_WikiParser;
	}

	/**
	 *	Builds HTML for Paging of Lists.
	 *	@access		public
	 *	@param		int		$number			Total Number of Entries
	 *	@param		int		$limit			Maximal Number of Entries to display
	 *	@param		int		$offset			Currently offset entries
	 *	@param		array	$options		Array of Options to set
	 *	@return		string
	 */
	public function buildPaging( $number, $limit, $offset, $options = array())
	{
		$request	= $this->ref->get( "request" );
		$link		= $request->get( 'link');
		$words		= $this->words['main']['paging'];

		$p	= new UI_HTML_Paging;
		$p->setOption( 'uri',		"" );
		$p->setOption( 'param',		array( 'link'	=> $link ) );
		$p->setOption( 'indent',	"" );

		foreach( $options as $key => $value )
			$p->setOption( $key, $value );
		
		$p->setOption( 'text_first',	$words['first'] );
		$p->setOption( 'text_previous',	$words['previous'] );
		$p->setOption( 'text_next',		$words['next'] );
		$p->setOption( 'text_last',		$words['last'] );
		$p->setOption( 'text_more',		$words['more'] );
		
		$pages	= $p->build( $number, $limit, $offset );
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

	public function hasContent( $file )
	{
		$config		= $this->ref->get( "config" );
		$session	= $this->ref->get( "session" );

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
	 *	@param		string		$separator_link		Separator in Language Link
	 *	@param		string		$separator_class	Separator for Language File
	 *	@return		string
	 */
	public function loadContent( $_file, $data = array() )
	{
		$config		= $this->ref->get( "config" );
		$session	= $this->ref->get( "session" );

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

		$content	= "";
		
		if( $ext == "wiki" && $this->wiki )
		{
			$cachefile	= $config['paths']['cache'].$path."/".$session->get( 'language' )."/".$basename.".html";
			if( file_exists( $cachefile ) && filemtime( $filename ) <= filemtime( $cachefile ) )
			{
				$file		= new File_Reader( $cachefile );
				$content	= $file->readString();
			}
			else if( file_exists( $filename ) )
			{
				$file		= new File_Reader( $filename );
				$cache		= new File_Writer( $cachefile, 0755 );
				$content	= $this->wiki->parse( $file->readString() );
				$cache->writeString( $content );
				$content = "<div class='wiki'>".$content."</div>";
			}
			else
				$this->messenger->noteFailure( "Content File '".$filename."' is not existing." );
		}
		else if( $ext == "html" )
		{
			if( file_exists( $filename ) )
			{
				$file		= new File_Reader( $filename );
				$content	= $file->readString();
			}
			else
				$this->messenger->noteFailure( "Content File '".$filename."' is not existing." );
		}
		else
		{
			$this->messenger->noteFailure( "Content Type for File '".$filename."' is not implemented." );
			return "";
		}
		foreach( $data as $key => $value )
			$content	= str_replace( "[#".$key."#]", $value, $content );
		return $content;
	}

	/**
	 *	Indicates whether a Cache File is existing.
	 *	@access		public
	 *	@param		string		$filename		File Name of Cache File
	 *	@return		bool			
	 */
	public function hasCache( $filename )
	{
		$config	= $this->ref->get( 'config' );
		$url	= $config['paths']['cache'].$filename;
		return file_exists( $url );
	}
	
	/**
	 *	Loads Content from a Cache File.
	 *	@access		public
	 *	@param		string		$filename		File Name of Cache File
	 *	@param		string		$log			File Name of Cache Log
	 *	@return		int
	 */
	public function loadCache( $filename, $log = "cache.log" )
	{
		$config		= $this->ref->get( 'config' );
		$url		= $config['paths']['cache'].$filename;
		$file		= new File_Reader( $url );
		$content	= $file->readString();
		if( $log )
			error_log( "Loaded from Cache File '".$filename."' ".strlen( $content )." Bytes.\n", 3, $config['paths']['logs'].$log );
		return $content;
	}

	/**
	 *	Saves Content to Cache File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string		$filename		File Name of Cache File
	 *	@param		string		$content		Content to save to Cache File
	 *	@param		string		$log			File Name of Cache Log
	 *	@return		int			Number of written Bytes
	 */
	public function saveCache( $filename, $content, $log = "cache.log" )
	{
		$config	= $this->ref->get( 'config' );
		$url	= $config['paths']['cache'].$filename;
		$file	= new File_Writer( $url, 0750 );
		$result	= $file->writeString( $content );
		if( $log )
			error_log( "Saved Cache File '".$filename."' with ".strlen( $content )." Bytes.\n", 3, $config['paths']['logs'].$log );
		return $result;
	}

	/**
	 *	Set the Title of HTML Page.
	 *	@access		protected
	 *	@param		string		$title		Title to set or add
	 *	@param		string		$separator	Separator if a Title is added
	 *	@param		bool		$append		Flag: add Title 
	 *	@param		array		$list		List of Keywords for HTML Output
	 *	@return		void
	 */
	protected function setTitle( $title, $separator = " | ", $append = true )
	{
		$words		=& $this->words['main']['main'];
		$current	=& $words['title'];
		if( $append == "prefix" )
			$current	= $title.$separator.$current;
		else if( $append == "suffix" )
			$current	= $current.$separator.$title;
		else if( isset( $words['title_prefix'] ) && $words['title_prefix'] )
			$current	= $words['title_prefix'].$separator.$title;
		else if( isset( $words['title_suffix'] ) && $words['title_suffix'] )
			$current	= $title.$separator.$words['title_suffix'];
	}

	/**
	 *	Sets a List of Keywords to Configuration for HTML-Template.
	 *	@access		protected
	 *	@param		array		$list		List of Keywords for HTML Output
	 *	@return		void
	 */
	protected function setKeywords( $list )
	{
		$config		= $this->ref->get( 'config' );
			$kewords	= implode( ",", $list );
		$config['meta']['keywords']	.= ",".$kewords;
	}
}
?>