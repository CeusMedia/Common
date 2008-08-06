<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.ui.html.WikiParser' );
/**
 *	File Reader and Writer for Wiki Pages.
 *	@package		ui.html
 *	@extends		UI_HTML_WikiParser
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.04.2006
 *	@version		0.6
 */
/**
 *	File Reader and Writer for Wiki Pages.
 *	@package		ui.html
 *	@extends		UI_HTML_WikiParser
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.04.2006
 *	@version		0.6
 */
class File_Wiki extends UI_HTML_WikiParser
{
	/**
	 *	Construcor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->setOption( 'use_cache', false );
		$this->setOption( 'cache_path', '' );
		$this->setOption( 'compress_cache', false );
	}

	/**
	 *	Returns Filename of Cache File from a Page ID.
	 *	@access		protected
	 *	@param		string		$id			Page ID
	 *	@return		string
	 */
	protected function getCacheFilenameFromPage( $id )
	{
		$filename	= $this->getOption( 'cache_path' ).md5( $id );
		return $filename;
	}
	
	/**
	 *	Returns Name of File from a Page ID.
	 *	@access		public
	 *	@param		string		$id			Page ID
	 *	@return		string
	 */
	public function getFilenameFromPage( $id )
	{
		$filename	= $this->getOption( 'path' ).str_replace( $this->getOption( 'namespace_separator' ), '/', $id ).$this->getOption( 'extension' );
		return $filename;
	}
	
	/**
	 *	Returns URL for a Page ID.
	 *	@access		public
	 *	@param		string		$id			Page ID
	 *	@return		string
	 */
	public function getUrlFromPage( $id )
	{
		$url	= $this->getOption( 'url' ).$this->getOption( 'carrier' ).$id;
		return $url;
	}
	
	/**
	 *	Indicates whether a File for a Page ID is existing.
	 *	@access		public
	 *	@param		string		$id			Page ID
	 *	@return		bool
	 */
	public function hasPage( $id )
	{
		$filename	= $this->getFilenameFromPage( $id );
		return file_exists( $filename );
	}

	/**
	 *	Returns parsed Content of File from a Page ID.
	 *	@access		public
	 *	@param		string		$id			Page ID
	 *	@return		string
	 */
	public function loadPage( $id )
	{
		if( $this->getOption( 'use_cache' ) )
		{
			$cachefile	= $this->_getCacheFilenameFromPage( $id );
			if( file_exists( $cachefile ) )
			{
				$file	= new File_Reader( $cachefile );
				$text	= $file->readString();
				if( $this->getOption( 'compress_cache' ) )
					$text	= gzuncompress( $text );
				return $text;
			}
			else
			{
				$filename	= $this->getFilenameFromPage( $id );
				$file	= new File_Reader( $filename );
				$text	= $file->readString();
				$text	= $this->parse( $text );
				$file	= new File_Writer( $cachefile, 0755 );
				if( $this->getOption( 'compress_cache' ) )
					$file->writeString( gzcompress( $text ) );
				else
					$file->writeString( $text );
				return $text;
			}
		}
		else
		{
			$filename	= $this->getFilenameFromPage( $id );
			if( file_exists( $filename ) )
			{
				$file	= new File_Reader( $filename );
				$text	= $file->readString();
				return $this->parse( $text );
			}
			else
			{
				return "File '".$filename."' is not existing.";
			}
		}
	}
	
	/**
	 *	Writes unparsed Content to File for a Page ID.
	 *	@access		public
	 *	@param		string		$id			Page ID
	 *	@param		string		$content		Content (unparsed) to write to File
	 *	@return		void
	 */
	public function writePage( $id, $content )
	{
		$filename	= $this->getFilenameFromPage( $id );
		if( file_exists( $filename ) )
		{
			$file	= new File_Writer( $filename );
			$file->writeString( $content );
		}
		else
		{
			$file	= new File_Writer( $filename, 0755 );
			$file->writeString( $content );
		}
		if( $this->getOption( 'use_cache' ) )
		{
			$cachefile	= $this->_getCacheFilenameFromPage( $id );
			if( file_exists( $cachefile ) )
				unlink( $cachefile );
		}
	}
}
?>