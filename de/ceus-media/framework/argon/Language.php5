<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.net.http.LanguageSniffer' );
import( 'de.ceus-media.validation.LanguageValidator' );
import( 'de.ceus-media.file.block.BlockFileReader' );

/**
 *	Language Support with sniffing of Browser Language and Language Validation.
 *	Loads Language Files direct or from Cache if enabled.
 *	@package		framework
 *	@subpackage		neon
 *	@extends		ADT_OptionObject
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			LanguageValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.12.2006
 *	@version		0.1
 */
/**
 *	Language Support with sniffing of Browser Language and Language Validation.
 *	Loads Language Files direct or from Cache if enabled.
 *	@package		framework
 *	@subpackage		neon
 *	@extends		ADT_OptionObject
 *	@uses			Net_HTTP_LanguageSniffer
 *	@uses			LanguageValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.12.2006
 *	@version		0.1
 */
class Language extends ADT_OptionObject
{
	var $_loaded	= array();
	
	public function __construct()
	{
		$this->ref	= new ADT_Reference;
		$request	= $this->ref->get( 'request' );
		$session	= $this->ref->get( 'session' );
		$config		= $this->ref->get( 'config' );
		
		//  --  LANGUAGE SELECT  --  //
		$default	= $config['languages']['default'];
		$allowed	= explode( ",", $config['languages']['allowed'] );
		if( $language 	= $request->get( 'switchLanguageTo' ) )
		{
			$lv	= new LanguageValidator( $allowed, $default );
			$language	= $lv->getLanguage( $language );
			$session->set( 'language', $language );
		}
		if( !( $language = $session->get( 'language' ) ) )
		{
			$sniffer	= new Net_HTTP_LanguageSniffer;
			$language	= $sniffer->getLanguage( $allowed, $default );
			$session->set( 'language', $language );
		}
		$language = $session->get( 'language' );
		$lv	= new LanguageValidator( $allowed, $default );
		$language	= $lv->getLanguage( $language );
		$session->set( 'language', $language );

		$this->setOption( 'path_files', $config['paths']['languages'] );
		$this->setOption( 'path_cache', $config['paths']['cache'].$config['paths']['languages'].$language."/" );
		$this->setOption( 'loaded_file', array() );
		$this->ref->add( 'words', $this->words );
		$this->loadHovers();
	}
	
	function loadHovers()
	{
		$session	= $this->ref->get( 'session' );
		$uri	= $this->getOption( 'path_files' ).$session->get( 'language' )."/hovers.blocks";
		if( file_exists( $uri ) )
		{
			$bfr	= new BlockFileReader( $uri );
			$this->_hovers	= $bfr->getBlocks();
		}
	}
	
	function loadLanguage( $filename, $section = false, $verbose = true )
	{
		$session	= $this->ref->get( 'session' );
		$messenger	= $this->ref->get( 'messenger' );
		if( !$section )
			$section	= $filename;
		$uri	= $this->getOption( 'path_files' ).$session->get( 'language' )."/".$filename.".lan";
		$cache	= $this->getOption( 'path_cache' ).basename( $filename ).".cache";
		if( file_exists( $cache ) && filemtime( $uri ) <= filemtime( $cache ) )
		{
			$this->words[$section]	= unserialize( $this->_loadCache( $cache ) );
		}
		else if( file_exists( $uri ) )
		{
			$ir	= new File_INI_Reader( $uri, true );
			$this->words[$section]	= $ir->toArray( true );
			foreach( $this->words[$section] as $area => $pairs )
				foreach( array_keys( $pairs ) as $key )
					if( isset( $this->_hovers[$filename."/".$area."/".$key] ) )
						$this->words[$section][$area][$key."_hover"] = $this->_hovers[$filename."/".$area."/".$key];
			$this->_saveCache( $cache, serialize( $this->words[$section] ) );
			return true;
		}
		else if( $verbose )
			$messenger->noteFailure( "Language File '".$filename."' is not existing in '".$uri."'" );
		return false;
	}

	function _hasCache( $filename )
	{
		$config	= $this->ref->get( 'config' );
//		remark( $url );
		return file_exists( $url );
	}
	
	function _loadCache( $url )
	{
		$config	= $this->ref->get( 'config' );
		$file	= new File( $url );
		return $file->readString();
			return implode( "", file( $url ) );
	}
	
	function _saveCache( $url, $content )
	{
		$config	= $this->ref->get( 'config' );
		$file	= new File( $url, 0750 );
		$file->writeString( $content );
	}
}
?>