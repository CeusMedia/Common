<?php
import( 'de.ceus-media.StopWatch' );
import( 'de.ceus-media.net.Reader' );
import( 'de.ceus-media.adt.list.Dictionary' );
import( 'de.ceus-media.adt.StringBuffer' );
import( 'de.ceus-media.alg.UnitFormater' );
/**
 *	Crawls and counts all internal Links of an URL.
 *	@package		net.site
 *	@uses			Net_Reader
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.12.2006
 *	@version		0.2
 */
/**
 *	Crawls and counts all internal Links of an URL.
 *	@package		net.site
 *	@uses			Net_Reader
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.12.2006
 *	@version		0.2
 *	@todo			finish Code Doc
 */
class Net_Site_Crawler
{
	protected $crawled		= FALSE;
	protected $depth		= 10;
	protected $errors		= array();
	protected $links		= array();

	protected $host;
	protected $pass;
	protected $path;
	protected $port;
	protected $scheme;
	protected $user;
	
	public $denied			= array(
		'pdf',
		'doc',
		'xls',
		'ppt',
		'mp3',
		'mp4',
		'mpeg',
		'mpg',
		'avi',
		'mov',
		'jpg',
		'jpeg',
		'gif',
		'png',
		'bmp',
	);
	
	public $deniedUrlParts	= array();


	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$depth			Number of Links followed in a Row
	 *	@return		void
	 */
	public function __construct( $depth = 10 )
	{
		if( $depth < 1 )
			throw new InvalidArgumentException( 'Depth must be at least 1.' );
		$this->depth	= $depth;
		$this->reader	= new Net_Reader( "empty" );
		$this->reader->setUserAgent( "SiteCrawler/0.1" );
	}

	/**
	 *	Builds URL from Parts.
	 *	@access		protected
	 *	@param		array		$parts			Parts of URL
	 *	@return		string
	 */
	protected function buildUrl( $parts )
	{
		$url	= new ADT_StringBuffer();
		if( isset( $parts['user'] ) && isset( $parts['pass'] ) && $parts['user'] )
			$url->append( $parts['user'].":".$parts['pass']."@" );
		if( substr( $parts['path'], 0, 1 ) != "/" )
			$parts['path']	= "/".$parts['path'];
		$url->append( $parts['host'].$parts['path'] );
		if( isset( $parts['query'] ) )
			$url->append( "?".$parts['query'] );
		$url	= str_replace( "//", "/", $url->toString() );
		$url	= $parts['scheme']."://".$url;
		return $url;
	}

	/**
	 *	Crawls a Web Site, collects Information and returns Number of visited Links.
	 *	@access		public
	 *	@param		string		$url					URL of Web Page to start at
	 *	@param		bool		$followExternalLinks	Flag: follow external Links (on another Domain)
	 *	@param		bool		$verbose				Flag: show Progression
	 *	@return		int
	 */
	public function crawl( $url, $followExternalLinks = FALSE, $verbose = FALSE )
	{
		if( $xdebug = ini_get( 'xdebug.profiler_enable' ) )							//  XDebug Profiler is enabled
			ini_set( 'xdebug.profiler_enable', "0" );								//  disable Profiler

		$this->crawled	= FALSE;
		$this->errors	= array();
		$this->links	= array();
		$parts			= parse_url( $url );
		$this->scheme	= isset( $parts['scheme'] ) ? $parts['scheme'] : "";
		$this->host		= isset( $parts['host'] ) ? $parts['host'] : "";
		$this->port		= isset( $parts['port'] ) ? $parts['port'] : "";
		$this->user		= isset( $parts['user'] ) ? $parts['user'] : "";
		$this->pass		= isset( $parts['pass'] ) ? $parts['pass'] : "";
		$this->path		= isset( $parts['path'] ) ? $parts['path'] : "";

		$number		= 0;
		$urlList	= array( $url );
		while( count( $urlList ) )
		{
			$number++;
			$url	= array_shift( $urlList );
			$parts	= new ADT_List_Dictionary( parse_url( $url ) );

			$parts['scheme']	= $this->scheme;
			$parts['host']		= isset( $parts['host'] ) ? $parts['host'] : $this->host;
			$parts['port']		= $this->port;
			$parts['user']		= $this->user;
			$parts['pass']		= $this->pass;
#			$parts['path']		= $this->path.$parts['path'];	
			
			if( $parts['host'] != $this->host )
				if( !$followExternalLinks )
					continue;	

			$url		= $this->buildUrl( $parts );
			if( array_key_exists( base64_encode( $url ), $this->links ) )
				continue;

			$denied				= FALSE;
			foreach( $this->deniedUrlParts as $part )
				if( preg_match( "@".$part."@", $url ) )
					$denied	= TRUE;

			if( $denied || substr_count( $parts['path'], ".." ) )
				continue;

			try
			{
				$content	= $this->getHTML( $url );
				$this->handleRecoveredLink( $url, $content );
				if( $verbose )
					$this->handleVerbose( $url, $number );
				$document	= $this->getDocument( $content, $url );

				$links		= $this->getLinksFromDocument( $document );
				foreach( $links as $url => $label )
				{
					$info	= pathinfo( $url );
					if( isset( $info['extension'] ) )
						if( in_array( $info['extension'], $this->denied ) )
							continue;
					$urlList[]	= $url;
				}
			}
			catch( Exception $e )
			{
				$this->errors[$url]	= $e->getMessage();
			}
		}
		$this->crawled	= TRUE;
		if( $xdebug )																//  XDebug Profiler was enabled
			ini_set( 'xdebug.profiler_enable', "1" );								//  enable Profiler
		return count( $this->links );
	}

	/**
	 *	Tries to get DOM Document from HTML Content or logs Errors and throws Exception.
	 *	@access		public
	 *	@param		string		$content		HTML Content
	 *	@param		string		$url			URL of HTML Page
	 *	@return		DOMDocument
	 */
	protected function getDocument( $content, $url )
	{
		$doc = new DOMDocument();
		ob_start();
		if( !$doc->loadHTML( $content ) )
		{
			$content	= ob_get_clean();
			if( $content )
				$this->errors[$url]	= $content;
			throw new RuntimeException( 'Error reading HTML.' );
		}
		ob_end_clean();
		return $doc;
	}
	
	/**
	 *	Returns List of Errors.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 *	Reads HTML Page and returns Content or logs Errors and throws Exception.
	 *	@access		public
	 *	@param		string		$url		URL to get Content for
	 *	@return		string
	 */
	protected function getHTML( $url )
	{
		$this->reader->setUrl( $url );
		try
		{
			return $this->reader->read( array( 'CURLOPT_FOLLOWLOCATION' => TRUE ) );		
		}
		catch( RuntimeException $e )
		{
			$this->errors[$url]	= $e->getMessage();
			throw $e;
		}
	}

	/**
	 *	Returns List of found URLs with Document Content.
	 *	@access		public
	 *	@return		array
	 */
	public function getLinks()
	{
		if( !$this->crawled )
			throw new RuntimeException( "First crawl an URL." );
		return $this->links;
	}

	/**
	 *	Parses a HTML Document and returns extracted Link URLs.
	 *	@access		protected
	 *	@param		DOMDocument	$document		DOM Document of HTML Content
	 *	@return		array
	 */
	protected function getLinksFromDocument( $document )
	{
		$links	= array();
		$nodes	= $document->getElementsByTagName( "a" );
		foreach( $nodes as $node )
		{
			$ref	= $node->getAttribute( 'href' );
			if( preg_match( "@^(#|mailto:|javascript:)@", $ref ) )
				continue;
			$links[$ref]	= $node->nodeValue;
		}
		return $links;
	}
	
	/**
	 *	Shows Information about downloaded Web Page. This Method is customizable (can be overwritten).
	 *	@access		protected
	 *	@param		string		$url			URL of Web Page
	 *	@param		int			$number			Number of followed Page
	 */
	protected function handleVerbose( $url, $number )
	{
		$speed	= Alg_UnitFormater::formatBytes( $this->reader->getStatus( 'speed_download' ) );
		remark( "[".$number."] ".str_replace( "http://".$this->host, "", $url )." | ".$speed."/s" );
#		print_m( $this->reader->getStatus() );
	}

	/**
	 *	Collects URL, Number of References and Content of Web Pages. This Method is customizable (can be overwritten).
	 *	@access		protected
	 *	@param		string		$url			URL of Web Page
	 *	@param		string		$content		HTML Content of Web Page
	 */
	protected function handleRecoveredLink( $url, $content = NULL )
	{
		if( array_key_exists( $url, $this->links ) )
			return $this->links[$url]['references']++;
		$this->links[base64_encode( $url )] = array(
			'url'			=> $url,
			'references'	=> 1,
			'content'		=> $content
		);
	}
}
?>