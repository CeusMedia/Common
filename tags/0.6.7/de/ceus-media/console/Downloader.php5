<?php
/**
 *	Downloads a File from an URL while showing Progress in Console.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		console
 *	@uses			Alg_UnitFormater
 *	@uses			Stopwatch
 *	@author			Keyvan Minoukadeh
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			05.05.2008
 *	@version		0.1
 */
import( 'de.ceus-media.alg.UnitFormater' );
import( 'de.ceus-media.Stopwatch' );
/**
 *	Downloads a File from an URL while showing Progress in Console.
 *	@package		console
 *	@uses			Alg_UnitFormater
 *	@uses			Stopwatch
 *	@author			Keyvan Minoukadeh
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			05.05.2008
 *	@version		0.1
 *	@see			http://curl.haxx.se/libcurl/c/curl_easy_setopt.html
 */
class Console_Downloader
{
	/**	@var		int			$fileSize			Length of File to download, extracted from Response Headers */	
	protected $fileSize			= 0;
	/**	@var		int			$loadSize			Length of current Load */	
	protected $loadSize			= 0;
	/**	@var		array		$headers			Collected Response Headers, already splitted */
	protected $headers			= array();
	/**	@var		bool		$showFileName		Flag: show File Name */
	public $redirected			= FALSE;
	/**	@var		bool		$showHeaders		Flag: show Headers */
	public $showFileName		= TRUE;
	/**	@var		bool		$showHeaders		Flag: show Headers */
	public $showHeaders			= FALSE;
	/**	@var		bool		$showProgress		Flag: show Progress */
	public $showProgress		= TRUE;
	/**	@var		string		$templateBodyDone	Template for Progress Line after having finished File Download */
	public $templateBodyDone	= "\rLoaded %1\$s (%2\$s) with %3\$s.\n";
	/**	@var		string		$templateBodyRatio	Template for Progress Line with Ratio (File Size muste be known) */
	public $templateBodyRatio	= "\r[%3\$s%%] %1\$s loaded (%2\$s)   ";
	/**	@var		string		$templateBody		Template for Progress Line without Ratio */
	public $templateBody		= "\r%1\$s loaded (%2\$s)   ";
	/**	@var		string		$templateFileName	Template for File Name Line */
	public $templateFileName	= "Downloading File \"%s\":\n";
	/**	@var		string		$templateHeader		Template for Header Line */
	public $templateHeader		= "%s: %s\n";
	/**	@var		string		$templateHeader		Template for Header Line */
	public $templateRedirect	= "Redirected to \"%s\"\n";
	/**	@var		Stopwatch	$watch				Stopwatch Instance */
	private $watch;

	/**
	 *	Loads a File from an URL, saves it using Callback Methods and returns Number of loaded Bytes.
	 *	@access		public
	 *	@param		string		$url				URL of File to download
	 *	@param		string		$savePath			Path to save File to
	 *	@param		bool		$force				Flag: overwrite File if already existing
	 *	@return		int
	 */
	public function downloadUrl( $url, $savePath = "", $force = FALSE )
	{
		if( getEnv( 'HTTP_HOST' ) )															//  called via Browser
			die( "Usage in Console, only." );

		$this->loadSize	= 0;																//  clear Size of current Load
		$this->fileSize	= 0;																//  clear Size og File to download
		$this->redirected	= FALSE;

		if( $savePath && !file_exists( $savePath ) )
			if( !@mkDir( $savePath, 0777, TRUE ) )
				throw new RuntimeException( 'Save path could not been created.' );

		$savePath	= $savePath ? preg_replace( "@([^/])$@", "\\1/", $savePath ) : "";		//  correct Path
		$parts		= parse_url( $url );													//  parse URL
		$info		= pathinfo( $parts['path'] );											//  parse Path
		$fileName	= $info['basename'];													//  extract File Name
		if( !$fileName )																	//  no File Name found in URL
			throw new RuntimeException( 'File Name could not be extracted.' );

		$this->fileUri	= $savePath.$fileName;												//  store full File Name
		$this->tempUri	= sys_get_temp_dir().$fileName.".part";								//  store Temp File Name
		if( file_exists( $this->fileUri ) )													//  File already exists
		{
			if( !$force )																	//  force not set
				throw new RuntimeException( 'File "'.$this->fileUri.'" is already existing.' );
			if( !@unlink( $this->fileUri ) )												//  remove File, because forced
				throw new RuntimeException( 'File "'.$this->fileUri.'" could not been cleared.' );
		}
		if( file_exists( $this->tempUri ) )													//  Temp File exists
			if( !@unlink( $this->tempUri ) )												//  remove Temp File
				throw new RuntimeException( 'Temp File "'.$this->tempUri.'" could not been cleared.' );

		if( $this->showFileName && $this->templateFileName )								//  show extraced File Name
			printf( $this->templateFileName, $fileName );									//  use Template
		
		$this->watch	= new StopWatch;													//  start Stopwatch
		$ch = curl_init();																	//  start cURL
		curl_setopt( $ch, CURLOPT_URL, $url );												//  set URL in cURL Handle
		curl_setopt( $ch, CURLOPT_HEADERFUNCTION, array( $this, 'readHeader' ) );			//  set Callback Method for Headers
		curl_setopt( $ch, CURLOPT_WRITEFUNCTION, array( $this, 'readBody' ) );				//  set Callback Method for Body
		curl_exec( $ch );																	//  execute cURL Request

		$error	= curl_error( $ch );														//  get cURL Error
		if( $error )																		//  an Error occured
			throw new RuntimeException( $error, curl_errno( $ch ) );						//  throw Exception with Error

		return $this->loadSize;																//  return Number of loaded Bytes
	}

	/**
	 *	Callback Method for reading Body Chunks.
	 *	@access		protected
	 *	@param		resource	$ch			cURL Handle
	 *	@param		string		$string		Body Chunk Content
	 *	@return		int
	 */
	protected function readBody( $ch, $string )
	{
		$length	= strlen( $string );														//  get Length of Body Chunk
		$this->loadSize	+= $length;															//  add Length to current Load Length

		if( $this->redirected )
			return $length;																	//  return Length of Header String

		if( $this->showProgress && $this->showProgress )									//  show Progress
		{
			$time	= $this->watch->stop( 6, 0 );											//  get current Duration
			$rate	= $this->loadSize / $time * 1000000;									//  calculate Rate of Bytes per Second
			$rate	= Alg_UnitFormater::formatBytes( $rate, 1 )."/s";						//  format Rate
			if( $this->fileSize )															//  File Size is known
			{
				$ratio	= $this->loadSize / $this->fileSize * 100;							//  calculate Ratio in %
				$ratio	= str_pad( round( $ratio, 0 ), 3, " ", STR_PAD_LEFT );				//  fill Ratio with Spaces
				$size	= Alg_UnitFormater::formatBytes( $this->loadSize, 1 );				//  format current Load Size
				printf( $this->templateBodyRatio, $size, $rate, $ratio );					//  use Template
			}
			else
			{
				$size	= Alg_UnitFormater::formatBytes( $this->loadSize, 1 );				//  format current Load Size
				printf( $this->templateBody, $size, $rate );								//  use Template
			}
		}

		if( $this->fileSize )																//  File Size is known from Header
			$saveUri	= $this->tempUri;													//  save to Temp File
		else																				//  File Size is not known
			$saveUri	= $this->fileUri;													//  save File directly to Save Path

		$fp	= fopen( $saveUri, "ab+" );														//  open File for appending
		fputs( $fp, $string );																//  append Chunk Content
		fclose( $fp );																		//  close File
		
		if( $this->fileSize && $this->fileSize == $this->loadSize )							//  File Size is known and File is complete
		{
			rename( $this->tempUri, $this->fileUri );										//  move Temp File to Save Path
			if( $this->showProgress && $this->templateBodyDone )							//  show Progress
			{
				$fileName	= basename( $this->fileUri );									//  get File Name from File URI
				printf( $this->templateBodyDone, $fileName, $size, $rate );					//  use Template
			}
		}
		return $length;																		//  return Length of Body Chunk
	}

	/**
	 *	Callback Method for reading Headers.
	 *	@access		protected
	 *	@param		resource	$ch			cURL Handle
	 *	@param		string		$string		Header String
	 *	@return		int
	 */
	protected function readHeader( $ch, $string )
	{
		$length = strlen( $string );														//  get Length of Header String

		if( !trim( $string ) )																//  trimmed Header String is empty
			return $length;																	//  return Length of Header String
		if( $this->redirected )
			return $length;																	//  return Length of Header String
	
		$parts			= split( ": ", $string );											//  split Header on Colon
		if( count( $parts ) > 1 )															//  there has been at least one Colon
		{
			$header		= trim( array_shift( $parts ) );									//  Header Key is first Part
			$content	= trim( join( ": ", $parts ) );										//  Header Content are all other Parts
			$this->headers[$header]	= $content;												//  store splitted Header
			if( preg_match( "@^Location$@i", $header ) )									//  Header is Redirect
			{
				$this->redirected	= TRUE;
				if( $this->templateRedirect )
					printf( $this->templateRedirect, $content );
				$loader	= new Console_Downloader();
				$loader->downloadUrl( $content, dirname( $this->fileUri ) );
			}
			if( preg_match( "@^Content-Length$@i", $header ) )								//  Header is Content-Length
				$this->fileSize	= (int) $content;											//  store Size of File to download
			if( $this->showHeaders && $this->templateHeader)								//  show Header
				printf( $this->templateHeader, $header, $content );							//  use Template
		}
		return $length;																		//  return Length of Header String
	}
}
?>