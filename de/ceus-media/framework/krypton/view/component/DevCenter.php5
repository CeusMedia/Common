<?php
import( 'de.ceus-media.framework.krypton.core.View' );
/**
 *	View Component for Development Information.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.04.2007
 *	@version		0.6
 */
define( 'DEV_CENTER_PRINT_M', 0 );
define( 'DEV_CENTER_VAR_DUMP', 1 );
/**
 *	View Component for Development Information.
 *	@package		framework.krypton.view.component
 *	@extends		Framework_Krypton_Core_View
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.04.2007
 *	@version		0.6
 */
class Framework_Krypton_View_Component_DevCenter extends Framework_Krypton_Core_View
{
	public $printMode	= DEV_CENTER_VAR_DUMP;
	protected $tabs		= array();
	protected $divs		= array();
	protected $topics	= array(
		'show_request'		=> 'showRequest',
		'show_session'		=> 'showSession',
		'show_cookie'		=> 'showCookie',
		'show_classes'		=> 'showClasses',
		'show_config'		=> 'showConfig',
		'show_queries'		=> 'showQueries',
		'show_languages'	=> 'showLanguages',
		'show_words'		=> 'showWords',
		'show_sources'		=> 'showSources',
	);
	public static $templateDevSources	= 'dev_sources';
	public static $templateDevTabs		= 'dev';
	
	public function buildContent( $content )
	{
		$config		= $this->registry->get( 'config' );
		if( $config['debug.show'] )
		{
			$showForAll	= $config['debug.show'] == "*";
			$showForIp	= in_array( getEnv( 'REMOTE_ADDR' ), explode( ",", $config['debug.show'] ) );
			if( $showForAll || $showForIp )
			{
				$this->buildTopics( $config, $content );
				return $this->buildTabs( $config );
			}
		}
	}

	protected function buildTabs( $config )
	{
		if( count( $this->tabs ) )
		{
			foreach( $this->tabs as $id => $label )
			{
				$listTabs[]	= UI_HTML_Elements::ListItem( UI_HTML_Elements::Link( "#".$id, "<span>".$label."</span>" ) );
				$listDivs[]	= '<div id="'.$id.'">'.$this->divs[$id].'</div>';
			}
			$ui		= array(
				'path_js'	=> $config['paths.javascripts'],
				'tabs'		=> UI_HTML_Elements::unorderedList( $listTabs ),
				'divs'		=> implode( "\n", $listDivs ),
			);
			return $this->loadTemplate( self::$templateDevTabs, $ui );
		}
	}

	protected function buildTopics( $config, $content )
	{
		if( $content )
			$this->showRemarks( $content );
		foreach( $this->topics as $option => $method )
			if( $config['debug.'.$option] )
				if( method_exists( $this, $method ) )
					$this->$method( $config['debug.'.$option] );
	}

	/**
	 *	Creates readable Dump of a Variable, either with print_m or var_dump, depending on printMode
	 *	@access		protected
	 *	@param		mixed		$element		Variable to be dumped
	 *	@return		string
	 */
	protected function dumpVar( $element )
	{
		ob_start();																	//  open Buffer
		if( $this->printMode )														//  Print Mode: var_dump
		{
			var_dump( $element );													//  print  Variable Dump
			$dump	= ob_get_clean();												//  get buffered Dump
			$dump	= preg_replace( "@=>\n +@", ": ", $dump );						//  remove Line Break on Relations
			$dump	= str_replace( "{\n", "\n", $dump );							//  remove Array Opener
			$dump	= str_replace( "}\n", "\n", $dump );							//  remove Array Closer
			$dump	= str_replace( ' ["', " ", $dump );								//  remove Variable Key Opener
			$dump	= str_replace( '"]:', ":", $dump );								//  remove Variable Key Closer
			$dump	= preg_replace( '@string\([0-9]+\)@', "", $dump );				//  remove Variable Type for Strings
			$dump	= preg_replace( '@array\([0-9]+\)@', "", $dump );				//  remove Variable Type for Arrays
			ob_start();																//  open Buffer
			xmp( $dump );															//  print Dump with XMP
		}
		else																		//  Print Mode: print_m
		{
			print_m( $element, ".", 2 );											//  print Dump with 2 Dots as Indent Space
		}
		return ob_get_clean();														//  return buffered Dump
	}

	/**
	 *	Returns Array of set Topics for Tabs.
	 *	@access		public
	 *	@return		array
	 */
	public function getTopics()
	{
		return $this->topics;
	}
	
	/**
	 *	Adds another Topic or overwrites set Topic.
	 *	@access		public
	 *	@param		string		$key		Topic Key in Configuration File 'config.ini[debug]'
	 *	@param		string		$method		Method in DevCenter to build Topic Tab.
	 *	@return		void
	 */
	public function setTopic( $key, $method )
	{
		$this->topics[$key]	= $method;
	}

	/**
	 *	Sets Topics for Tabs.
	 *	@access		public
	 *	@return		array
	 */
	public function setTopics( $topics )
	{
		$this->topics	= $topics;
	}
	
	protected function showClasses()
	{
		if( isset( $GLOBALS['imported'] ) )
		{
			$imports	= $GLOBALS['imported'];
			if( count( $imports ) )
			{
				$list	= implode( "<br/>", array_keys( $imports ) ) ;
				natcasesort( $imports );
				$sorted	= implode( "<br/>", array_keys( $imports ) );

				$table	= "<table><tr><th>Classes</th><th>Classes sorted</th></tr><tr><td>".$list."</td><td>".$sorted."</td></tr></table>";
				$this->tabs['devTabClasses']	= "Classes <small>(".count($imports).")</small>";
				$this->divs['devTabClasses']	= $table;
			}
		}
	}

	protected function showConfig()
	{
		$config	= $this->registry->get( 'config' );
		if( count( $config ) )
		{
			$this->tabs['devTabConfig']	= "Config <small>(".count( $config ).")</small>";
			$this->divs['devTabConfig']	= $this->dumpVar( $config->getAll() );
		}
	}

	/**
	 *	Shows Cookie Information with PartitionCookie/Cake Support.
	 *	@access		protected
	 *	@param		bool		$supportPartitionCookie		Flag: support PartitionCookie/Cake
	 *	@return		void
	 */
	protected function showCookie( $supportPartitionCookie = true )
	{
		if( count( $_COOKIE ) )
		{
			if( !$supportPartitionCookie )
				$cookies	= $_COOKIE;
			else
			{
				$cookies	= array();
				foreach( $_COOKIE as $key => $value )
				{
					if( !preg_match( "/@[^:]+:.+/", $value ) )
					{
						$cookies[$key]	= $value;
						continue;
					}
					$subcookies	= explode( "@", $value );
					foreach( $subcookies as $subcookie )
					{
						if( !preg_match( "/.+:.+/", $subcookie ) )
							continue;
						list( $subcookieKey, $subcookieValue ) = explode( ":", $subcookie );
						if( !isset( $cookies[$key] ) )
							$cookies[$key]	= array();
						$cookies[$key][$subcookieKey] = $subcookieValue;
					}
				}
			}
			$this->tabs['devTabCookie']	= "Cookie <small>(".count( $_COOKIE ).")</small>";
			$this->divs['devTabCookie']	= $this->dumpVar( $cookies );
		}
	}

	protected function showLanguages()
	{
		$language	= $this->registry->get( 'language' );
		$files	= $language->getLoadedFiles();
		if( count( $files ) )
		{
			$list	= array();
			natcasesort( $files );
			foreach( $files as $file => $key )
				$list[]	= $file." (".$key.")";
			$this->tabs['devTabLanguages']	= "Languages <small>(".count( $list ).")</small>";
			$this->divs['devTabLanguages']	= "<xmp>".implode( "\n", $list )."</xmp>";
		}
	}

	protected function showQueries()
	{
		$logFile	= "logs/database/queries_".getEnv( 'REMOTE_ADDR' ).".log";
		if( file_exists( $logFile ) )
		{
			$content	= file_get_contents( $logFile );
			$count		= substr_count( $content, str_repeat( "-", 80 ) );
			$this->tabs['devTabQueries']	= "Queries <small>(".$count.")</small>";
			$this->divs['devTabQueries']	= "<xmp>".$content."</xmp>";
		}		
	}

	protected function showRemarks( $content )
	{
		$content	= trim( $content );
		$content	= preg_replace( "@^<br ?/>@", "", $content );
		$content	= preg_replace( "@<br ?/>$@", "", $content );
		$content	= preg_replace( "@<br />@", "<br/>", $content );
		$count	= substr_count( $content, "<br/>" ) + 1;
		$this->tabs['devTabRemarks']	= "Remarks <small>(".$count.")</small>";
		$this->divs['devTabRemarks']	= $content;
	}

	protected function showRequest()
	{
		$request	= $this->registry->get( 'request' );
		$all	= $request->getAll(); 
		if( count( $all ) )
		{
			$content	= $this->dumpVar( $all );
			$content	= str_replace( array( "<%", "%>" ), array( "[[%", "%]]" ), $content );
			$this->tabs['devTabRequest']	= "Request <small>(".count( $all ).")</small>";
			$this->divs['devTabRequest']	= $content;
		}
	}

	protected function showSession()
	{
		$session	= $this->registry->get( 'session' );
		$all		= $session->getAll(); 
		if( count( $all ) )
		{
			$this->tabs['devTabSession']	= "Session <small>(".count( $all ).")</small>";
			$this->divs['devTabSession']	= $this->dumpVar( $all );
		}
	}

	protected function showSources()
	{
		$this->tabs['devTabSources']	= "Sources ";
		$this->divs['devTabSources']	= $this->loadTemplate( self::$templateDevSources, array() );
	}

	protected function showWords()
	{
		$language	= $this->registry->get( 'language' );
		$words	= $language->getWords();
		if( count( $words ) )
		{
			$this->tabs['devTabWords']	= "Words";
			$this->divs['devTabWords']	= $this->dumpVar( $words );
		}
	}
}
?>