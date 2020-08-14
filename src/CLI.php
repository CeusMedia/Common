<?php
class CLI
{
	protected $logger;
	protected $size;

	static protected $mimeTypeLabels	= array(
		'application/xml'		=> 'XML',
		'text/plain'			=> 'Text',
		'text/x-php'			=> 'PHP',
		'text/x-makefile'		=> 'MAKE',
		'text/x-shellscript'	=> 'Shell',
	);

	public function __construct()
	{
		$this->base	= getCwd();
		$this->size	= \CLI_Dimensions::getSize();
		\UI_Text::$defaultLineLength	= $this->size->width - 1;
	}

	/**
	 *	Ensures that runtime environment is headless, like crontab execution.
	 *	@access		public
	 *	@static
	 *	@param		boolean		$strict			Flag: throw exception if not headless (default: yes)
	 *	@return		boolean
	 */
	static public function checkIsHeadless( bool $strict = TRUE ): bool
	{
		if( getEnv( 'TERM' ) === FALSE )
			return TRUE;
		if( $strict )
			throw new \RuntimeException( 'Available in headless environment, only' );
		return FALSE;
	}

	static public function checkIsCLi( bool $strict = TRUE ): bool
	{
		if( php_sapi_name() === 'cli' )
			return TRUE;
		if( $strict )
			throw new \RuntimeException( 'Available in CLI environment, only' );
		return FALSE;
	}

	static public function charTable( int $from = 2500, int $to = 2600 )
	{
		print PHP_EOL;
		for($i=$from/10; $i<$to/10; $i++){
			print 'x'.$i.'0  ';
			for($j=0; $j<16; $j++){
				$number	= $i.dechex( $j);
				print ' '.UI_Text::char( 'x'.$number );
			}
			print PHP_EOL;
		}
	}

	static public function error( $messages = NULL )
	{
		$isCli	= self::checkIsCLi( FALSE );
		if( !is_array( $messages ) )
			$messages	= array( $messages );
		foreach( $messages as $message ){
			if( is_null( $message ) )
				continue;
			$type		= gettype( $message );
			if( in_array( $type, array( 'string', 'integer' ) ) ){
				if( strlen( trim( $message ) ) ){
					$message	= trim( $message );
					$isCli ? fwrite( STDERR, $message ) : print( $message );
				}
			}
			else{
				$message	= UI_DevOutput::print_m( $message, NULL, NULL, TRUE );
				$isCli ? fwrite( STDERR, $message ) : print( $message );
			}
		}
		$isCli ? fwrite( STDERR, PHP_EOL ) : print( PHP_EOL );
	}

	public function getColors(): int
	{
		return $this->size->colors;
	}

	public function getHeight(): int
	{
		return $this->size->getHeight();
	}

	public function getWidth(): int
	{
		return $this->size->getWidth();
	}

	public function log( $message )
	{
		if( is_object( $this->log ) )
			return $this->logger->log( $message );
		$logFile	= $this->log ? $this->log : 'cli.log';
		error_log( date( 'Y-m-d H:i:s' ).': '.$message.PHP_EOL, $logFile );
	}

	static public function out( $messages = NULL, $newLine = TRUE )
	{
		$isCli	= self::checkIsCLi( FALSE );
		if( !is_array( $messages ) )
			$messages	= array( $messages );
		foreach( $messages as $message ){
			if( is_null( $message ) )
				continue;
			$type		= gettype( $message );
			if( in_array( $type, array( 'string', 'integer' ) ) ){
				if( strlen( trim( $message ) ) ){
					$isCli ? fwrite( STDOUT, $message ) : print( $message );
				}
			}
			else{
				$message	= UI_DevOutput::print_m( $message, NULL, NULL, TRUE );
				$isCli ? fwrite( STDOUT, $message ) : print( $message );
			}
		}
		if( $newLine )
			$isCli ? fwrite( STDOUT, PHP_EOL ) : print( PHP_EOL );
	}

	protected function realizePath( string $path ): string
	{
//		if( !strlen( trim( $path ) )
//			$path	= '.';
		$first	= substr( $path, 0, 1 );
		switch( $first ){
			case '/':
				break;
			case '~':
			//	@todo		implement!
				break;
			default:
				$path	= $this->base.'/'.$path;
		}
		if( file_exists( $path ) )
			return realpath( $path );
		throw new Exception_IO( 'Path is not existing', 0, $path );
	}

	function ls( $path = NULL, $mimeType = TRUE )
	{
		$path	= $this->realizePath( $path );
		$f		= new FS_Folder( $path );
		Alg_UnitFormater::$unitBytes[0]	= ' B';
		$folders	= $f->index( FS::TYPE_FOLDER, SORT_NATURAL | SORT_FLAG_CASE );
		$files		= $f->index( FS::TYPE_FILE | FS::TYPE_LINK, SORT_NATURAL | SORT_FLAG_CASE );
		$freeSize	= UI_Text::$defaultLineLength - 40;
		$mimeType	= $mimeType && UI_Text::$defaultLineLength > 80;
		if( $mimeType )
			$freeSize	-= 20;
		if( $folders->count() || $files->count() ){
			$headType	= $mimeType ? UI_Text::padLeft( 'Type', 20 ) : '';
			$headSize	= UI_Text::padLeft( 'Size', 12 );
			$headPerm	= UI_Text::padLeft( 'Rights   ', 12 );
			$headDate	= UI_Text::padRight( '  Date', 16 );
			if( $folders->count() && $files->count() )
				$heading	= '%1$d Folders and %2$d Files or Links:';
			else if( $folders->count() )
				$heading	= '%1$d Folders:';
			else if( $files->count() )
				$heading	= '%2$d Files or Links:';
			$heading	= sprintf( $heading, $folders->count(), $files->count() );
			$heading	= UI_Text::padRight( $heading, $freeSize );
			CLI::out( $heading.$headType.$headSize.$headPerm.$headDate );
			CLI::out( UI_Text::line( UI_Text::char( 'x2550' ) ) );
		}

		if( $folders ){
			foreach( $folders as $item ){
				$name	= $item->getName();
				$sfo	= $item->count( FS::TYPE_FOLDER, TRUE );
				$sfi	= $item->count( FS::TYPE_FILE | FS::TYPE_LINK, TRUE );
				$perm	= 'd'.FS_File_Permissions::getStringFromFile( $item->getPathName() );
				$date	= date( 'y-m-d H:i', $item->getTime() );
				CLI::out( join( array(
					UI_Text::padRight( $name, $freeSize ),
					$mimeType ? UI_Text::padLeft( '[folder]', 20 ) : '',
					UI_Text::padLeft( $sfo.'/'.$sfi, 12 ),
					UI_Text::padLeft( $perm, 12 ),
					UI_Text::padLeft( $date, 16 )
				) ) );
			}
		}
		if( $files ){
			if( $folders->count() )
				CLI::out( UI_Text::line( UI_Text::char( 'x2500' ) ) );
			foreach( $files as $item ){
				$name	= $item->getName();
				$mime	= self::shortenMimeType( $item->getMimeType() );
				$size	= UI_Text::formatBytes( $item->getSize() );
				$perm	= FS_File_Permissions::getStringFromFile( $item->getPathName() );
				$date	= date( 'y-m-d H:i', $item->getTime() );
				CLI::out( join( array(
					UI_Text::padRight( $name, $freeSize ),
					$mimeType ? UI_Text::padLeft( $mime, 20 ) : '',
					UI_Text::padLeft( $size, 12 ),
					UI_Text::padLeft( $perm, 12 ),
					UI_Text::padLeft( $date, 16 )
				) ) );
			}
		}
	}

	static protected function shortenMimeType( string $mimeType ): string
	{
		$mimeType	= trim( $mimeType );
		if( !strlen( $mimeType ) )
			return '';
		if( !preg_match( '/.+\/.+/', $mimeType ) )
			return $mimeType;
		if( array_key_exists( $mimeType, self::$mimeTypeLabels ) )
			return self::$mimeTypeLabels[$mimeType];
		list( $topic, $type )	= explode( '/', $mimeType, 2 );
		$short	= NULL;
		$type	= preg_replace( '/^x-/', '', $type );
		if( $topic === 'text' )
			$short = 'T';
		else if( $topic === 'application' )
			$short = 'A';
		$type	= Alg_Text_CamelCase::encode( str_replace( '-', ' ', $type ) );
		return $short ? $short.':'.$type : $topic.'/'.$type;
	}
}
