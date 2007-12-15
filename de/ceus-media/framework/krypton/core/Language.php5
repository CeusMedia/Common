<?php
import( 'de.ceus-media.framework.krypton.core.Registry' );
import( 'de.ceus-media.file.ini.IniReader' );
import( 'de.ceus-media.net.http.LanguageSniffer' );
import( 'de.ceus-media.validation.LanguageValidator' );
import( 'de.ceus-media.file.block.BlockFileReader' );
/**
 *	Language Support with sniffing of Browser Language and Language Validation.
 *	Loads Language Files direct or from Cache if enabled.
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			IniReader
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			LanguageValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.12.2006
 *	@version		0.2
 */
/**
 *	Language Support with sniffing of Browser Language and Language Validation.
 *	Loads Language Files direct or from Cache if enabled.
 *	@package		mv2.core
 *	@uses			Framework_Krypton_Core_Registry
 *	@uses			IniReader
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			LanguageValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.12.2006
 *	@version		0.2
 */
class Framework_Krypton_Core_Language
{
	protected $hovers		= array();
	protected $words		= array();
	
	protected $default;
	protected $allowed;
	
	protected $pathFiles;
	protected $pathCache;
	protected $loadedFiles	= array();

	public function getAllowedLanguages()
	{
		return $this->allowed;
	}
	
	public function getDefaultLanguage()
	{
		return $this->default;
	}
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->registry	= Framework_Krypton_Core_Registry::getInstance();
		$request	= $this->registry->get( 'request' );
		$session	= $this->registry->get( 'session' );
		$config		= $this->registry->get( 'config' );

		$this->default	= $config['languages']['default'];
		$this->allowed	= explode( ",", $config['languages']['allowed'] );
	}
	
	/**
	 *	Creates nested Folder recursive.
	 *	@access		protected
	 *	@param		string		$path		Folder to create
	 *	@return		void
	 */
	protected function createFolder( $path )
	{
		if( !file_exists( $path ) )
		{
			$parts	= explode( "/", $path );
			$folder	= array_pop( $parts );
			$path	= implode( "/", $parts );
			$this->createFolder( $path );
			mkDir( $path."/".$folder );
		}
	}
	
	/**
	 *	Returns ISO-Code of current Language.
	 *	@access		public
	 *	@return		string
	 */
	public function getLanguage()
	{
		$session	= $this->registry->get( 'session' );
		$language	= $session->get( 'language' );
		return $language;
	}
	
	public function getLoadedFiles()
	{
		return $this->loadedFiles;	
	}

	/**
	 *	Returns Array of Words, either of Section of File, of File or all.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Language File
	 *	@param		string		$section		Section in Language File
	 *	@return		array
	 */
	public function & getWords( $fileName = false, $section = false )
	{
		if( $fileName )
		{
			if( $section )
			{
				if( isset( $this->words[$fileName][$section] ) )
					return $this->words[$fileName][$section];
				else
					throw new Exception( "Section '".$section."' is not available in Language File '".$fileName."'" );
			}
			if( isset( $this->words[$fileName] ) )
				return $this->words[$fileName];
			else
				throw new Exception( "Language File '".$fileName."' is not available." );
		}
		return $this->words;
	}
	
	/**
	 *	Identifies Language from User Browser or User Request.
	 *	@access		public
	 *	@ver
	 */
	public function identifyLanguage()
	{
		$request	= $this->registry->get( 'request' );
		$session	= $this->registry->get( 'session' );

		//  --  LANGUAGE SELECT  --  //
		if( $language 	= $request->get( 'switchLanguageTo' ) )
		{
			$lv	= new LanguageValidator( $this->allowed, $this->default );
			$language	= $lv->getLanguage( $language );
			$this->setLanguage( $language );
		}
		//  --  LANGUAGE SNIFF  --  //
		if( !( $language = $session->get( 'language' ) ) )
		{
			$sniffer	= new Net_HTTP_LanguageSniffer;
			$language	= $sniffer->getLanguage( $this->allowed, $this->default );
		}
		$this->setLanguage( $language );
	}

	/**
	 *	Constructor.
	 *	@access		private
	 *	@param		string		$url		URL of Language Cache File
	 *	@return		string
	 */
	private function loadCache( $url )
	{
		$file	= new File( $url );
		return $file->readString();
			return implode( "", file( $url ) );
	}
	
	/**
	 *	Loads Hover Texts.
	 *	@access		private
	 *	@return		void
	 */
	protected function loadHovers()
	{
		$session	= $this->registry->get( 'session' );
		$uri	= $this->pathFiles.$session->get( 'language' )."/hovers.blocks";
		if( file_exists( $uri ) )
		{
			$bfr	= new BlockFileReader( $uri );
			$this->hovers	= $bfr->getBlocks();
		}
	}
	
	/**
	 *	Loads Language File.
	 *	@access		public
	 *	@param		string		$fileName		Name of Language File without Extension
	 *	@param		string		$section		Section Name in Words
	 *	@param		bool		$verbose		Flag: Note missing Language Files.
	 *	@return		bool
	 */
	public function loadLanguage( $fileName, $section = false, $verbose = false )
	{
		if( $verbose )
	 	   remark( "<b>Load Language: </b> File: ".$fileName." -> Section: ".$section );
		$config		= $this->registry->get( 'config' );
		$session	= $this->registry->get( 'session' );
		$messenger	= $this->registry->get( 'messenger' );
		$language	= $this->getLanguage();
		if( !$section )
			$section	= $fileName;

		if( !in_array( $fileName, array_keys( $this->loadedFiles ) ) )
		{
			if( in_array( $section, array_values( $this->words ) ) )
				throw new Exception( "Language File with Key '".$section."' is already loaded." );

			//  --  BASICS  --  //
			$basepath	= $config['paths']['languages'].$language."/";
			$basename	= str_replace( ".", "/", $fileName ).".lan";

			//  --  FILE URI CHECK  --  //
			$lanfile	= $basepath.$basename;		//  fallback: base path
			if( !file_exists( $lanfile ) )							//  check file
				throw new Framework_Krypton_Exception_IO( "Language File '".$fileName."' is not existing." );	

			//  CACHE CHECK  //
			$cachepath	= $config['paths']['cache'].basename( $config['paths']['languages'] ).'/';
			$cachefile	= $cachepath.$language.".".$fileName.".ser";
			if( file_exists( $cachefile ) && filemtime( $lanfile ) <= filemtime( $cachefile ) )
			{
				$this->words[$section]	= unserialize( $this->loadCache( $cachefile ) );
				$this->loadedFiles[$fileName]	= $section;
			}
			else if( file_exists( $lanfile ) )
			{
				$ir	= new IniReader( $lanfile, true );
				$this->words[$section]	= $ir->toArray( true );
				foreach( $this->words[$section] as $area => $pairs )
					foreach( array_keys( $pairs ) as $key )
						if( isset( $this->hovers[$basename."/".$area."/".$key] ) )
							$this->words[$section][$area][$key."_hover"] = $this->hovers[$fileName."/".$area."/".$key];
				$this->saveCache( $cachefile, serialize( $this->words[$section] ) );
				$this->loadedFiles[$fileName]	= $section;
				return true;
			}
			else if( $verbose )
				$messenger->noteFailure( "Language File '".$fileName."' is not existing in '".$uri."'" );
			return false;
		}
	}

	/**
	 *	Saves Language File Cache.
	 *	@access		private
	 *	@param		string		$url		URL of Language Cache File
	 *	@param		string		$content	Content of Language Cache File
	 *	@return		void
	 */
	private function saveCache( $url, $content )
	{
		$config	= $this->registry->get( 'config' );
		$this->createFolder( dirname( $url ) );		
		$file	= new File( $url, 0750 );
		$file->writeString( $content );
	}
	
	/**
	 *	Sets current Language, sets internal Paths and loads Hover Texts.
	 *	@access		public
	 *	@param		string		$language		ISO-Code of Language
	 *	@return		void
	 */
	public function setLanguage( $language )
	{
		$config		= $this->registry->get( 'config' );
		$session	= $this->registry->get( 'session' );
		if( !in_array( $language, $this->allowed ) )
			throw new Exception( "Language '".$language."' is not allowed." );
		$session->set( 'language', $language );

		$this->pathFiles	= $config['paths']['languages'];
		$this->pathCache	= $config['paths']['cache'].basename( $config['paths']['languages'] ).$language."/";
		$this->loadedFiles	= array();
		$this->registry->set( 'words', $this->words, true );
		$this->loadHovers();
	}
}
?>
