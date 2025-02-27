<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Downloads a File from a URL while showing Progress in Console.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Keyvan Minoukadeh
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI;

use CeusMedia\Common\Alg\Time\Clock;
use CeusMedia\Common\Alg\UnitFormater;
use RuntimeException;

/**
 *	Downloads a File from a URL while showing Progress in Console.
 *	@category		Library
 *	@package		CeusMedia_Common_CLI
 *	@author			Keyvan Minoukadeh
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://curl.haxx.se/libcurl/c/curl_easy_setopt.html
 */
class Downloader
{
	/**	@var		bool				$showFileName		Flag: show File Name */
	public bool $redirected				= FALSE;

	/**	@var		bool				$showHeaders		Flag: show Headers */
	public bool $showFileName			= TRUE;

	/**	@var		bool				$showHeaders		Flag: show Headers */
	public bool $showHeaders			= FALSE;

	/**	@var		bool				$showProgress		Flag: show Progress */
	public bool $showProgress			= TRUE;

	/**	@var		string				$templateBodyDone	Template for Progress Line after having finished File Download */
	public string $templateBodyDone		= "\rLoaded %1\$s (%2\$s) with %3\$s.\n";

	/**	@var		string				$templateBodyRatio	Template for Progress Line with Ratio (File Size must be known) */
	public string $templateBodyRatio	= "\r[%3\$s%%] %1\$s loaded (%2\$s)   ";

	/**	@var		string				$templateBody		Template for Progress Line without Ratio */
	public string $templateBody			= "\r%1\$s loaded (%2\$s)   ";

	/**	@var		string				$templateFileName	Template for File Name Line */
	public string $templateFileName		= "Downloading File \"%s\":\n";

	/**	@var		string				$templateHeader		Template for Header Line */
	public string $templateHeader		= "%s: %s\n";

	/**	@var		string				$templateHeader		Template for Header Line */
	public string $templateRedirect		= "Redirected to \"%s\"\n";

	/**	@var		int					$fileSize			Length of File to download, extracted from Response Headers */
	protected int $fileSize				= 0;

	/**	@var		int					$loadSize			Length of current Load */
	protected int $loadSize				= 0;

	/**	@var		array				$headers			Collected Response Headers, already split */
	protected array $headers			= [];

	protected ?string $fileUri			= NULL;

	protected ?string $tempUri			= NULL;

	/**	@var		Clock			$clock				Clock Instance */
	private Clock $clock;

	/**
	 *	Loads a File from a URL, saves it using Callback Methods and returns Number of loaded Bytes.
	 *	@access		public
	 *	@param		string		$url				URL of File to download
	 *	@param		string		$savePath			Path to save File to
	 *	@param		bool		$force				Flag: overwrite File if already existing
	 *	@return		int
	 */
	public function downloadUrl( string $url, string $savePath = '', bool $force = FALSE ): int
	{
		//  called via Browser
		if( getenv( 'HTTP_HOST' ) )
			die( "Usage in Console, only." );

		//  clear Size of current Load
		$this->loadSize	= 0;
		//  clear Size og File to download
		$this->fileSize	= 0;
		$this->redirected	= FALSE;

		if( $savePath && !file_exists( $savePath ) )
			if( !@mkDir( $savePath, 0777, TRUE ) )
				throw new RuntimeException( 'Save path could not been created.' );

		//  correct Path
		$savePath	= $savePath ? preg_replace( "@([^/])$@", "\\1/", $savePath ) : "";
		//  parse URL
		$parts		= parse_url( $url );
		//  parse Path
		$info		= pathinfo( $parts['path'] );
		//  extract File Name
		$fileName	= $info['basename'];
		//  no File Name found in URL
		if( !$fileName )
			throw new RuntimeException( 'File Name could not be extracted.' );

		//  store full File Name
		$this->fileUri	= $savePath.$fileName;
		//  store Temp File Name
		$this->tempUri	= sys_get_temp_dir().$fileName.".part";
		//  File already exists
		if( file_exists( $this->fileUri ) ){
			//  force not set
			if( !$force )
				throw new RuntimeException( 'File "'.$this->fileUri.'" is already existing.' );
			//  remove File, because forced
			if( !@unlink( $this->fileUri ) )
				throw new RuntimeException( 'File "'.$this->fileUri.'" could not been cleared.' );
		}
		//  Temp File exists
		if( file_exists( $this->tempUri ) )
			//  remove Temp File
			if( !@unlink( $this->tempUri ) )
				throw new RuntimeException( 'Temp File "'.$this->tempUri.'" could not been cleared.' );

		//  show extracted File Name
		if( $this->showFileName && $this->templateFileName )
			//  use Template
			printf( $this->templateFileName, $fileName );

		//  start clock
		$this->clock	= new Clock;
		//  start cURL
		$ch = curl_init();
		//  set URL in cURL Handle
		curl_setopt( $ch, CURLOPT_URL, $url );
		//  set Callback Method for Headers
		curl_setopt( $ch, CURLOPT_HEADERFUNCTION, [$this, 'readHeader'] );
		//  set Callback Method for Body
		curl_setopt( $ch, CURLOPT_WRITEFUNCTION, [$this, 'readBody'] );
		//  execute cURL Request
		curl_exec( $ch );

		//  get cURL Error
		$error	= curl_error( $ch );
		//  an Error occurred
		if( $error )
			//  throw Exception with Error
			throw new RuntimeException( $error, curl_errno( $ch ) );

		//  return Number of loaded Bytes
		return $this->loadSize;
	}

	/**
	 *	Callback Method for reading Body Chunks.
	 *	@access		protected
	 *	@param		resource	$ch			cURL Handle
	 *	@param		string		$string		Body Chunk Content
	 *	@return		int
	 *	@noinspection PhpUnusedParameterInspection
	 *	@noinspection PhpRedundantOptionalArgumentInspection
	 */
	protected function readBody( $ch, string $string ): int
	{
		//  get Length of Body Chunk
		$length	= strlen( $string );
		//  add Length to current Load Length
		$this->loadSize	+= $length;

		if( $this->redirected )
			//  return Length of Header String
			return $length;

		//  show Progress
		if( $this->showProgress ){
			//  get current Duration
			$time	= $this->clock->stop( 6, 0 );
			//  calculate Rate of Bytes per Second
			$rate	= $this->loadSize / $time * 1_000_000;
			//  format Rate
			$rate	= UnitFormater::formatBytes( $rate, 1 )."/s";
			//  File Size is known
			if( $this->fileSize ){
				//  calculate Ratio in %
				$ratio	= $this->loadSize / $this->fileSize * 100;
				//  fill Ratio with Spaces
				$ratio	= str_pad( (string) round( $ratio ), 3, " ", STR_PAD_LEFT );
				//  format current Load Size
				$size	= UnitFormater::formatBytes( $this->loadSize, 1 );
				//  use Template
				printf( $this->templateBodyRatio, $size, $rate, $ratio );
			}
			else{
				//  format current Load Size
				$size	= UnitFormater::formatBytes( $this->loadSize, 1 );
				//  use Template
				printf( $this->templateBody, $size, $rate );
			}
		}

		//  File Size is known from Header
		if( $this->fileSize )
			//  save to Temp File
			$saveUri	= $this->tempUri;
		//  File Size is not known
		else
			//  save File directly to Save Path
			$saveUri	= $this->fileUri;

		//  open File for appending
		$fp	= fopen( $saveUri, "ab+" );
		//  append Chunk Content
		fputs( $fp, $string );
		//  close File
		fclose( $fp );

		//  File Size is known and File is complete
		if( $this->fileSize && $this->fileSize == $this->loadSize ){
			//  move Temp File to Save Path
			rename( $this->tempUri, $this->fileUri );
			//  show Progress
			if( $this->showProgress && $this->templateBodyDone ){
				//  get File Name from File URI
				$fileName	= basename( $this->fileUri );

				//  get current Duration
				$time	= $this->clock->stop( 6, 0 );
				//  format Load Size
				$size	= UnitFormater::formatBytes( $this->loadSize, 1 );
				//  calculate Rate of Bytes per Second
				$rate	= $this->loadSize / $time * 1_000_000;
				//  format Rate
				$rate	= UnitFormater::formatBytes( $rate, 1 )."/s";

				//  use Template
				printf( $this->templateBodyDone, $fileName, $size, $rate );
			}
		}
		//  return Length of Body Chunk
		return $length;
	}

	/**
	 *	Callback Method for reading Headers.
	 *	@access		protected
	 *	@param		resource	$ch			cURL Handle
	 *	@param		string		$string		Header String
	 *	@return		int
	 *	@noinspection PhpUnusedParameterInspection
	 */
	protected function readHeader( $ch, string $string ): int
	{
		//  get Length of Header String
		$length = strlen( $string );

		//  trimmed Header String is empty
		if( !trim( $string ) )
			//  return Length of Header String
			return $length;
		if( $this->redirected )
			//  return Length of Header String
			return $length;

		//  split Header on Colon
		/** @noinspection PhpPregSplitWithoutRegExpInspection */
		$parts			= preg_split( '/: /', $string );
		//  there has been at least one Colon
		if( count( $parts ) > 1 )
		{
			//  Header Key is first Part
			$header		= trim( array_shift( $parts ) );
			//  Header Content are all other parts
			$content	= trim( join( ": ", $parts ) );
			//  store split Header
			$this->headers[$header]	= $content;
			//  Header is Redirect
			if( preg_match( "@^Location$@i", $header ) )
			{
				$this->redirected	= TRUE;
				if( $this->templateRedirect )
					printf( $this->templateRedirect, $content );
				$loader	= new Downloader();
				$loader->downloadUrl( $content, dirname( $this->fileUri ) );
			}
			//  Header is Content-Length
			if( preg_match( "@^Content-Length$@i", $header ) )
				//  store Size of File to download
				$this->fileSize	= (int) $content;
			//  show Header
			if( $this->showHeaders && $this->templateHeader)
				//  use Template
				printf( $this->templateHeader, $header, $content );
		}
		//  return Length of Header String
		return $length;
	}
}
