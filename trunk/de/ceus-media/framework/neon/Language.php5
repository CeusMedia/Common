<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.net.http.LanguageSniffer' );
import( 'de.ceus-media.alg.validation.LanguageValidator' );
import( 'de.ceus-media.file.block.Reader' );

/**
 *	Language Support with sniffing of Browser Language and Language Validation.
 *	Loads Language Files direct or from Cache if enabled.
 *	@package		framework.neon
 *	@extends		ADT_OptionObject
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			Alg_Validation_LanguageValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.12.2006
 *	@version		0.6
 */
/**
 *	Language Support with sniffing of Browser Language and Language Validation.
 *	Loads Language Files direct or from Cache if enabled.
 *	@package		framework.neon
 *	@extends		ADT_OptionObject
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			Alg_Validation_LanguageValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.12.2006
 *	@version		0.6
 */
class Framework_Neon_Language extends ADT_OptionObject
{
	protected	$loaded			= array();
	protected	$multilingual	= TRUE;
	public		$words			= array();
	
	public function __construct( $encoding = NULL )
	{
		parent::__construct();

		$this->ref	= new ADT_Reference;
		$request	= $this->ref->get( 'request' );
		$session	= $this->ref->get( 'session' );
		$config		= $this->ref->get( 'config' );

		$pathFiles	= $config['paths']['languages'];
		$pathCache	= $config['paths']['cache'].basename( $config['paths']['languages'] )."/";

		if( isset( $config['languages'] ) )
		{
			//  --  LANGUAGE SELECT  --  //
			$default	= $config['languages']['default'];
			$allowed	= explode( ",", $config['languages']['allowed'] );
			if( $language 	= $request->get( 'switchLanguageTo' ) )
			{
				$lv	= new Alg_Validation_LanguageValidator( $allowed, $default );
				$language	= $lv->getLanguage( $language );
				$session->set( 'language', $language );
				$request->remove( 'switchLanguageTo' );
			}
			if( !( $language = $session->get( 'language' ) ) )
			{
				$sniffer	= new Net_HTTP_LanguageSniffer;
				$language	= $sniffer->getLanguage( $allowed, $default );
				$session->set( 'language', $language );
			}

			$pathFiles	.= $language."/";
			$pathCache	.= $language."/";
		}

		$this->setOption( 'path_files', $pathFiles );
		$this->setOption( 'path_cache', $pathCache );
		$this->setOption( 'encoding', $encoding );
		$this->setOption( 'loaded_file', array() );
		$this->ref->add( 'words', $this->words );
		$this->loadHovers();
	}

	public function loadCache( $url )
	{
		$file	= new File_Reader( $url );
		return $file->readString();
			return implode( "", file( $url ) );
	}
	
	public function loadHovers()
	{
		$uri	= $this->getOption( 'path_files' )."/hovers.blocks";
		if( file_exists( $uri ) )
		{
			$bfr	= new File_Block_Reader( $uri );
			$this->_hovers	= $bfr->getBlocks();
		}
	}
	
	public function loadLanguage( $filename, $section = FALSE, $verbose = TRUE )
	{
		$messenger	= $this->ref->get( 'messenger' );
		if( !$section )
			$section	= $filename;
		$uri	= $this->getOption( 'path_files' ).$filename.".lan";
		$cache	= $this->getOption( 'path_cache' ).basename( $filename ).".cache";
		if( file_exists( $cache ) && filemtime( $uri ) <= filemtime( $cache ) )
		{
			$this->words[$section]	= unserialize( $this->loadCache( $cache ) );
		}
		else if( file_exists( $uri ) )
		{
			$ir	= new File_INI_Reader( $uri, true );
			$this->words[$section]	= $ir->toArray( true );
			foreach( $this->words[$section] as $area => $pairs )
			{
				foreach( $pairs as $key => $value )
					if( isset( $this->_hovers[$filename."/".$area."/".$key] ) )
						$this->words[$section][$area][$key."_hover"] = $this->_hovers[$filename."/".$area."/".$key];
			}
#			if( $this->getOption( 'encoding' ) == "utf-8" )			
#			{
				foreach( $this->words[$section] as $area => $pairs )
					foreach( $pairs as $key => $value )
						$this->words[$section][$area][$key]	= $value;
#			}
			$this->saveCache( $cache, serialize( $this->words[$section] ) );
			return true;
		}
		else if( $verbose )
			$messenger->noteFailure( "Language File '".$filename."' is not existing in '".$uri."'" );
		return false;
	}
	
	public function saveCache( $url, $content )
	{
		$file	= new File_Writer( $url, 0750 );
		$file->writeString( $content );
	}
}
?>