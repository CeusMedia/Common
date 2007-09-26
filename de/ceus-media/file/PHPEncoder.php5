<?php
import( 'de.ceus-media.file.File' );
/**
 *	Class for encoding PHP File.
 *	@package		file
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.10.2006
 *	@version 		0.1
 */
/**
 *	Class for encoding PHP File.
 *	@package		file
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.10.2006
 *	@version 		0.1
 */
class PHPEncoder
{
	/**	@var		string		$incode_prefix	Prefix of inner Code Wrapper */
	var $incode_prefix	= "";
	/**	@var		string		$incode_suffix		Suffix of inner Code Wrapper */
	var $incode_suffix	= "";
	/**	@var		string		$outcode_prefix	Prefix of outer Code Wrapper */
	var $outcode_prefix	= "";
	/**	@var		string		$outcode_suffix	Suffix of outer Code Wrapper */
	var $outcode_suffix	= "";
	/**	@var		string		$file_prefix		Prefix of compressed PHP File */
	var $file_prefix	= "code.";
	/**	@var		string		$file_suffix		Suffix of compressed PHP File */
	var $file_suffix	= "";

	/**
	 *	Constructor.
	 *	@access		public
	 * 	@return		void
	 */
	public function __construct()
	{
		$this->incode_prefix	= "?".">";
		$this->incode_suffix	= "<"."?";
		$this->outcode_prefix	= "<"."? print( '<xmp>'.gzinflate(base64_decode('";
		$this->outcode_prefix	= "<"."? eval( gzinflate(base64_decode('";
		$this->outcode_suffix	= "')));?".">";
	}
	
	/**
	 *	Returns decoded and stripped PHP Content.
	 *	@access		public
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	function decode( $php )
	{
		$code	= substr( $php, strlen( $this->outcode_prefix) , -strlen( $this->outcode_suffix ) );
		$php 	= $this->decodeHash( $code );
		return $php;
	}
	
	/**
	 *	Decodes an encoded PHP File.
	 *	@access		public
	 * 	@return		void
	 */
	function decodeFile( $filename, $overwrite = false )
	{
		if( file_exists( $filename ) )
		{
			if( $this->isEncoded( $filename ) )
			{
				$file	= new File( $filename );
				$php	= $file->readString();
				$code	= $this->encode( $php );
				$dirname	= dirname( $filename );
				$basename	= basename( $filename );
				$target	= $dirname."/".substr( $basename, strlen( $this->file_prefix) , -strlen( $this->file_suffix ) );
				if( $filename == $target && !$overwrite )
					trigger_error( "File cannot be overwritten, use Parameter [overwrite]", E_USER_ERROR );
				$file	= new File( $target );
				$file->writeString( $code );
				return true;
			}
		}
		return false;	
	}
	
	/**
	 *	Returns Hash decoded PHP Content.
	 *	@access		public
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	function decodeHash( $code )
	{
		$php	= gzinflate( base64_decode( $code ) );
		$php	= substr( $php, strlen( $this->incode_prefix) , -strlen( $this->incode_suffix ) );
		return 	$php;
	}
	
	/**
	 *	Returns encoded and wrapped PHP Content.
	 *	@access		public
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	function encode( $php )
	{
		$code	= $this->encodeHash( $php );
		$php	= $this->outcode_prefix.$code.$this->outcode_suffix;
		return $php;
	}

	/**
	 *	Encodes a PHP File.
	 *	@access		public
	 * 	@return		void
	 */
	function encodeFile( $filename, $overwrite = false )
	{
		if( file_exists( $filename ) )
		{
			if( !$this->isEncoded( $filename ) )
			{
				$file	= new File( $filename );
				$php	= $file->readString();
				$code	= $this->encode( $php );
				$dirname	= dirname( $filename );
				$basename	= basename( $filename );
				$target	= $dirname."/".$this->file_prefix.$basename.$this->file_suffix;
				if( $filename == $target && !$overwrite )
					trigger_error( "File cannot be overwritten, use Parameter [overwrite]", E_USER_ERROR );
//				copy( $filename, "#".$filename );
				$file	= new File( $target );
				$file->writeString( $code );
				return true;
			}
		}
		return false;
	}
	
	/**
	 *	Returns encoded PHP Content.
	 *	@access		public
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	function encodeHash( $php )
	{
		return base64_encode( gzdeflate( $this->incode_prefix.$php.$this->incode_suffix ) );	
	}
	
	/**
	 *	Indicated whether a PHP File ist encoded.
	 *	@access		public
	 *	@param		string		$filename		File Name of PHP File to be checked
	 * 	@return		bool
	 */
	function isEncoded( $filename )
	{
		if( file_exists( $filename ) )
		{
			$fp	= fopen( $filename, "r" );
			$code	= fgets( $fp, strlen( $this->outcode_prefix ) );
			if( $code == $this->outcode_prefix )
				return true;
		}
		return false;
	}
}
?>