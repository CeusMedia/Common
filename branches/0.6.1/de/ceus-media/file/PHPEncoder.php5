<?php
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Class for encoding PHP File.
 *	@package		file
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.10.2006
 *	@version 		0.1
 */
/**
 *	Class for encoding PHP File.
 *	@package		file
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			11.10.2006
 *	@version 		0.1
 */
class PHPEncoder
{
	/**	@var		string		$incodePrefix		Prefix of inner Code Wrapper */
	var $incodePrefix			= "";
	/**	@var		string		$incodeSuffix		Suffix of inner Code Wrapper */
	var $incodeSuffix			= "";
	/**	@var		string		$outcodePrefix		Prefix of outer Code Wrapper */
	var $outcodePrefix			= "";
	/**	@var		string		$outcodeSuffix		Suffix of outer Code Wrapper */
	var $outcodeSuffix			= "";
	/**	@var		string		$filePrefix			Prefix of compressed PHP File */
	var $filePrefix				= "code.";
	/**	@var		string		$fileSuffix			Suffix of compressed PHP File */
	var $fileSuffix					= "";

	/**
	 *	Constructor.
	 *	@access		public
	 * 	@return		void
	 */
	public function __construct()
	{
		$this->incodePrefix		= "?".">";
		$this->incodeSuffix		= "<"."?";
		$this->outcodePrefix	= "<"."? print( '<xmp>'.gzinflate(base64_decode('";
		$this->outcodePrefix	= "<"."? eval( gzinflate(base64_decode('";
		$this->outcodeSuffix	= "')));?".">";
	}
	
	/**
	 *	Returns decoded and stripped PHP Content.
	 *	@access		public
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	public function decode( $php )
	{
		$code	= substr( $php, strlen( $this->outcodePrefix) , -strlen( $this->outcodeSuffix ) );
		$php 	= $this->decodeHash( $code );
		return $php;
	}
	
	/**
	 *	Decodes an encoded PHP File.
	 *	@access		public
	 * 	@return		void
	 */
	public function decodeFile( $fileName, $overwrite = FALSE )
	{
		if( file_exists( $fileName ) )
		{
			if( $this->isEncoded( $fileName ) )
			{
				$file	= new File_Reader( $fileName );
				$php	= $file->readString();
				$code	= $this->encode( $php );
				$dirname	= dirname( $fileName );
				$basename	= basename( $fileName );
				$target	= $dirname."/".substr( $basename, strlen( $this->filePrefix) , -strlen( $this->fileSuffix ) );
				if( $fileName == $target && !$overwrite )
					trigger_error( "File cannot be overwritten, use Parameter [overwrite]", E_USER_ERROR );
				$file	= new File_Writer( $target );
				$file->writeString( $code );
				return TRUE;
			}
		}
		return FALSE;	
	}
	
	/**
	 *	Returns Hash decoded PHP Content.
	 *	@access		protected 
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	protected function decodeHash( $code )
	{
		$php	= gzinflate( base64_decode( $code ) );
		$php	= substr( $php, strlen( $this->incodePrefix) , -strlen( $this->incodeSuffix ) );
		return 	$php;
	}
	
	/**
	 *	Returns encoded and wrapped PHP Content.
	 *	@access		public
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	public function encode( $php )
	{
		$code	= $this->encodeHash( $php );
		$php	= $this->outcodePrefix.$code.$this->outcodeSuffix;
		return $php;
	}

	/**
	 *	Encodes a PHP File.
	 *	@access		public
	 * 	@return		void
	 */
	public function encodeFile( $fileName, $overwrite = FALSE )
	{
		if( !file_exists( $fileName ) )
			return FALSE;
		if( $this->isEncoded( $fileName ) )
			return TRUE;
		$php		= File_Reader::load( $fileName );
		$code		= $this->encode( $php );
		$dirname	= dirname( $fileName );
		$basename	= basename( $fileName );
		$target		= $dirname."/".$this->filePrefix.$basename.$this->fileSuffix;
		if( $fileName == $target && !$overwrite )
			throw new Exception( 'File cannot be overwritten, use Parameter "overwrite".' );
//		copy( $fileName, "#".$fileName );
		return (bool) File_Writer::save( $target, $code );
	}
	
	/**
	 *	Returns encoded PHP Content.
	 *	@access		protected
	 *	@param		string		$php		Encoded PHP Content
	 * 	@return		string
	 */
	protected function encodeHash( $php )
	{
		return base64_encode( gzdeflate( $this->incodePrefix.$php.$this->incodeSuffix ) );	
	}
	
	/**
	 *	Indicated whether a PHP File ist encoded.
	 *	@access		public
	 *	@param		string		$fileName		File Name of PHP File to be checked
	 * 	@return		bool
	 */
	public function isEncoded( $fileName )
	{
		if( file_exists( $fileName ) )
		{
			$fp	= fopen( $fileName, "r" );
			$code	= fgets( $fp, strlen( $this->outcodePrefix ) );
			if( $code == $this->outcodePrefix )
				return TRUE;
		}
		return FALSE;
	}
}
?>