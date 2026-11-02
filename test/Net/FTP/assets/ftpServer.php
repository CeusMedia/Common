<?php
/**
 *	Minimal FTP server for unit tests of Net\FTP, serving a local folder.
 *	Handles one control connection at a time, supports passive and active mode.
 *	Not meant for any other use: no security beyond staying within the root folder.
 *
 *	Usage: php ftpServer.php <port> <root folder> <username> <password>
 */

declare( strict_types = 1 );

[, $port, $root, $username, $password]	= $argv + [NULL, '0', '', 'test', 'test'];
$root	= rtrim( (string) realpath( $root ), '/' );
if( '' === $root )
	exit( 1 );

$server	= stream_socket_server( 'tcp://127.0.0.1:'.$port, $errorCode, $errorMessage );
if( FALSE === $server ){
	fwrite( STDERR, $errorMessage.PHP_EOL );
	exit( 1 );
}

//  serve each connection in a forked child (if possible), so connections left open do not block others
$canFork	= function_exists( 'pcntl_fork' );
if( $canFork )
	pcntl_signal( SIGCHLD, SIG_IGN );
while( TRUE ){
	$client	= @stream_socket_accept( $server, -1 );
	if( FALSE === $client )
		continue;
	$pid	= $canFork ? pcntl_fork() : -1;
	if( 0 === $pid ){
		fclose( $server );
		( new TestFtpSession( $client, $root, $username, $password ) )->run();
		@fclose( $client );
		exit( 0 );
	}
	if( -1 === $pid )
		( new TestFtpSession( $client, $root, $username, $password ) )->run();
	@fclose( $client );
}

class TestFtpSession
{
	/** @var resource */
	protected $control;
	/** @var resource|NULL */
	protected $passive			= NULL;
	protected ?string $active	= NULL;
	protected string $cwd		= '/';
	protected bool $loggedIn	= FALSE;
	protected ?string $user		= NULL;
	protected ?string $renameFrom	= NULL;

	/**
	 *	@param		resource	$control
	 */
	public function __construct( $control, protected string $root, protected string $username, protected string $password )
	{
		$this->control	= $control;
	}

	public function run(): void
	{
		$this->reply( 220, 'Test FTP server ready' );
		while( FALSE !== ( $line = fgets( $this->control ) ) ){
			$line		= rtrim( $line, "\r\n" );
			$parts		= explode( ' ', $line, 2 );
			$command	= strtoupper( $parts[0] );
			$argument	= $parts[1] ?? '';
			if( 'QUIT' === $command ){
				$this->reply( 221, 'Bye' );
				return;
			}
			if( !$this->loggedIn && !in_array( $command, ['USER', 'PASS', 'SYST', 'FEAT', 'NOOP', 'OPTS'], TRUE ) ){
				$this->reply( 530, 'Not logged in' );
				continue;
			}
			$this->handle( $command, $argument );
		}
	}

	protected function handle( string $command, string $argument ): void
	{
		switch( $command ){
			case 'USER':
				$this->user	= $argument;
				$this->reply( 331, 'Password required' );
				break;
			case 'PASS':
				$this->loggedIn	= $this->user === $this->username && $argument === $this->password;
				$this->loggedIn ? $this->reply( 230, 'Logged in' ) : $this->reply( 530, 'Login incorrect' );
				break;
			case 'SYST':
				$this->reply( 215, 'UNIX Type: L8' );
				break;
			case 'FEAT':
				fwrite( $this->control, "211-Features:\r\n SIZE\r\n MDTM\r\n211 End\r\n" );
				break;
			case 'NOOP':
			case 'OPTS':
			case 'TYPE':
			case 'MODE':
			case 'STRU':
				$this->reply( 200, 'OK' );
				break;
			case 'PWD':
			case 'XPWD':
				$this->reply( 257, '"'.$this->cwd.'" is current directory' );
				break;
			case 'CWD':
			case 'CDUP':
				$path	= $this->resolve( 'CDUP' === $command ? '..' : $argument );
				if( NULL !== $path && is_dir( $this->root.$path ) ){
					$this->cwd	= $path;
					$this->reply( 250, 'OK' );
				}
				else
					$this->reply( 550, 'No such directory' );
				break;
			case 'PASV':
				$this->closeDataChannels();
				$this->passive	= stream_socket_server( 'tcp://127.0.0.1:0' ) ?: NULL;
				$name	= (string) stream_socket_get_name( $this->passive, FALSE );
				$port	= (int) substr( $name, strrpos( $name, ':' ) + 1 );
				$this->reply( 227, 'Entering Passive Mode (127,0,0,1,'.intdiv( $port, 256 ).','.( $port % 256 ).')' );
				break;
			case 'EPSV':
				$this->closeDataChannels();
				$this->passive	= stream_socket_server( 'tcp://127.0.0.1:0' ) ?: NULL;
				$name	= (string) stream_socket_get_name( $this->passive, FALSE );
				$this->reply( 229, 'Entering Extended Passive Mode (|||'.substr( $name, strrpos( $name, ':' ) + 1 ).'|)' );
				break;
			case 'PORT':
				$this->closeDataChannels();
				$numbers	= array_map( 'intval', explode( ',', $argument ) );
				if( 6 !== count( $numbers ) ){
					$this->reply( 501, 'Syntax error' );
					break;
				}
				$this->active	= implode( '.', array_slice( $numbers, 0, 4 ) ).':'.( $numbers[4] * 256 + $numbers[5] );
				$this->reply( 200, 'PORT command successful' );
				break;
			case 'LIST':
			case 'NLST':
				$argument	= trim( (string) preg_replace( '/^(-[a-zA-Z]+\s*)+/', '', $argument ) );
				$path		= $this->resolve( $argument );
				$lines		= NULL === $path ? NULL : $this->listPath( $path, 'NLST' === $command );
				if( NULL === $lines ){
					$this->reply( 550, 'No such file or directory' );
					$this->closeDataChannels();
					break;
				}
				$this->sendData( implode( '', array_map( static fn( $line ) => $line."\r\n", $lines ) ) );
				break;
			case 'RETR':
				$path	= $this->resolve( $argument );
				if( NULL === $path || !is_file( $this->root.$path ) ){
					$this->reply( 550, 'No such file' );
					$this->closeDataChannels();
					break;
				}
				$this->sendData( (string) file_get_contents( $this->root.$path ) );
				break;
			case 'STOR':
				$path	= $this->resolve( $argument );
				if( NULL === $path || !is_dir( dirname( $this->root.$path ) ) ){
					$this->reply( 553, 'Cannot store file' );
					$this->closeDataChannels();
					break;
				}
				$content	= $this->receiveData();
				if( NULL === $content )
					break;
				file_put_contents( $this->root.$path, $content );
				$this->reply( 226, 'Transfer complete' );
				break;
			case 'DELE':
				$path	= $this->resolve( $argument );
				( NULL !== $path && is_file( $this->root.$path ) && unlink( $this->root.$path ) )
					? $this->reply( 250, 'Deleted' )
					: $this->reply( 550, 'Delete failed' );
				break;
			case 'MKD':
			case 'XMKD':
				$path	= $this->resolve( $argument );
				( NULL !== $path && !file_exists( $this->root.$path ) && @mkdir( $this->root.$path ) )
					? $this->reply( 257, '"'.$path.'" created' )
					: $this->reply( 550, 'Create directory failed' );
				break;
			case 'RMD':
			case 'XRMD':
				$path	= $this->resolve( $argument );
				( NULL !== $path && '/' !== $path && is_dir( $this->root.$path ) && @rmdir( $this->root.$path ) )
					? $this->reply( 250, 'Removed' )
					: $this->reply( 550, 'Remove directory failed' );
				break;
			case 'RNFR':
				$path	= $this->resolve( $argument );
				$this->renameFrom	= ( NULL !== $path && file_exists( $this->root.$path ) ) ? $path : NULL;
				NULL !== $this->renameFrom
					? $this->reply( 350, 'Ready for RNTO' )
					: $this->reply( 550, 'No such file or directory' );
				break;
			case 'RNTO':
				$path	= $this->resolve( $argument );
				$from	= $this->renameFrom;
				$this->renameFrom	= NULL;
				( NULL !== $from && NULL !== $path && @rename( $this->root.$from, $this->root.$path ) )
					? $this->reply( 250, 'Renamed' )
					: $this->reply( 550, 'Rename failed' );
				break;
			case 'SIZE':
				$path	= $this->resolve( $argument );
				( NULL !== $path && is_file( $this->root.$path ) )
					? $this->reply( 213, (string) filesize( $this->root.$path ) )
					: $this->reply( 550, 'No such file' );
				break;
			case 'MDTM':
				$path	= $this->resolve( $argument );
				( NULL !== $path && file_exists( $this->root.$path ) )
					? $this->reply( 213, gmdate( 'YmdHis', (int) filemtime( $this->root.$path ) ) )
					: $this->reply( 550, 'No such file' );
				break;
			case 'SITE':
				$parts	= preg_split( '/\s+/', trim( $argument ), 3 ) ?: [];
				if( 3 === count( $parts ) && 'CHMOD' === strtoupper( $parts[0] ) ){
					$path	= $this->resolve( $parts[2] );
					( NULL !== $path && file_exists( $this->root.$path ) && @chmod( $this->root.$path, (int) octdec( $parts[1] ) ) )
						? $this->reply( 200, 'Permissions changed' )
						: $this->reply( 550, 'CHMOD failed' );
				}
				else
					$this->reply( 502, 'Command not implemented' );
				break;
			default:
				$this->reply( 502, 'Command not implemented' );
		}
	}

	/**
	 *	Resolves a path argument to an absolute virtual path within root, or NULL if leaving root.
	 */
	protected function resolve( string $argument ): ?string
	{
		$path	= str_starts_with( $argument, '/' ) ? $argument : $this->cwd.'/'.$argument;
		$parts	= [];
		foreach( explode( '/', $path ) as $part ){
			if( '' === $part || '.' === $part )
				continue;
			if( '..' === $part ){
				if( [] === $parts )
					return NULL;
				array_pop( $parts );
				continue;
			}
			$parts[]	= $part;
		}
		return '/'.implode( '/', $parts );
	}

	/**
	 *	@return		string[]|NULL
	 */
	protected function listPath( string $path, bool $namesOnly ): ?array
	{
		$fullPath	= $this->root.$path;
		if( is_file( $fullPath ) )
			return [$namesOnly ? basename( $path ) : $this->buildListLine( $fullPath, basename( $path ) )];
		if( !is_dir( $fullPath ) )
			return NULL;
		$names	= array_values( array_diff( scandir( $fullPath ) ?: [], ['.', '..'] ) );
		sort( $names );
		$lines	= [];
		foreach( $names as $name )
			$lines[]	= $namesOnly ? $name : $this->buildListLine( $fullPath.'/'.$name, $name );
		return $lines;
	}

	protected function buildListLine( string $fullPath, string $name ): string
	{
		clearstatcache( TRUE, $fullPath );
		$perms	= fileperms( $fullPath );
		$mode	= is_dir( $fullPath ) ? 'd' : '-';
		foreach( [0400, 0200, 0100, 040, 020, 010, 04, 02, 01] as $nr => $bit )
			$mode	.= ( $perms & $bit ) ? 'rwx'[$nr % 3] : '-';
		$time	= (int) filemtime( $fullPath );
		$date	= date( 'Y' ) === date( 'Y', $time ) ? date( 'M d H:i', $time ) : date( 'M d  Y', $time );
		$size	= is_dir( $fullPath ) ? 4096 : (int) filesize( $fullPath );
		return sprintf( '%s 1 owner group %8d %s %s', $mode, $size, $date, $name );
	}

	/**
	 *	@return		resource|NULL
	 */
	protected function openDataChannel()
	{
		$channel	= NULL;
		if( NULL !== $this->passive )
			$channel	= @stream_socket_accept( $this->passive, 10 ) ?: NULL;
		else if( NULL !== $this->active )
			$channel	= @stream_socket_client( 'tcp://'.$this->active, $errorCode, $errorMessage, 10 ) ?: NULL;
		return $channel;
	}

	protected function sendData( string $data ): void
	{
		$this->reply( 150, 'Opening data connection' );
		$channel	= $this->openDataChannel();
		if( NULL === $channel ){
			$this->reply( 425, 'Cannot open data connection' );
			$this->closeDataChannels();
			return;
		}
		fwrite( $channel, $data );
		fclose( $channel );
		$this->closeDataChannels();
		$this->reply( 226, 'Transfer complete' );
	}

	protected function receiveData(): ?string
	{
		$this->reply( 150, 'Opening data connection' );
		$channel	= $this->openDataChannel();
		if( NULL === $channel ){
			$this->reply( 425, 'Cannot open data connection' );
			$this->closeDataChannels();
			return NULL;
		}
		$content	= (string) stream_get_contents( $channel );
		fclose( $channel );
		$this->closeDataChannels();
		return $content;
	}

	protected function closeDataChannels(): void
	{
		if( is_resource( $this->passive ) )
			fclose( $this->passive );
		$this->passive	= NULL;
		$this->active	= NULL;
	}

	protected function reply( int $code, string $message ): void
	{
		fwrite( $this->control, $code.' '.$message."\r\n" );
	}
}
