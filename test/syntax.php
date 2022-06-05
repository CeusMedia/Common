<?php
class LibraryClassSyntaxTester
{
	public function __construct( array $arguments )
	{
		$path	= dirname( __DIR__ ).'/src/';
		require_once( $path.'Alg/Text/Trimmer.php' );
		require_once( $path.'CLI.php' );
		require_once( $path.'CLI/Dimensions.php' );
		require_once( $path.'CLI/Output.php' );
		require_once( $path.'CLI/Output/Progress.php' );
		require_once( $path.'UI/DevOutput.php' );

		$data	= (object) self::listClasses( $path );

		print( 'Found '.$data->count.' class files'.PHP_EOL.PHP_EOL );
		self::testSyntax( $data->files );
	}

	protected static function listClasses( string $path ): array
	{
		$count	= 0;
		$size	= 0;
		$list	= [];
		self::listClassesRecursive( $path, $list, $count, $size );
		return [
			'path'	=> $path,
			'count'	=> $count,
			'size'	=> $size,
			'files'	=> $list
		];
	}

	protected static function listClassesRecursive( string $path, array &$list, int &$count , int &$size )
	{
		$index	= new DirectoryIterator( $path );
		foreach( $index as $entry ){
			if( $entry->isDot() || $entry->getFilename() === ".git" )
				continue;
			$pathName	= $entry->getPathname();
			if( $entry->isDir() )
				self::listClassesRecursive( $pathName, $list, $count, $size );
			else if( $entry->isFile() ){
				$info	= pathinfo( $pathName );
				if( $info['extension'] !== 'php' )
					continue;
				if( !preg_match( '/^[A-Z]/', $info['basename'] ) )
					continue;
				$list[] = $pathName;
				$size	+= filesize( $pathName );
				$count++;
			}
		}
	}

	protected static function testSyntax( array $files )
	{
		print( 'Checking syntax of class files ...'.PHP_EOL );
		$count	= 0;
		$line	= str_repeat( '-', 79 );
		$list	= [];
		$progress	= new CeusMedia\Common\CLI\Output\Progress();
		$progress->setTotal( count( $files ) );
		$progress->start();
		foreach( $files as $file ){
			$code	= 0;
			$output	= [];
			exec( 'php -l "'.$file.'" 2>&1', $output, $code );
			if( !preg_match( '/^No syntax errors detected/', join( PHP_EOL, $output ) ) )
				$list[$file]	= join( PHP_EOL, $output );
			$count++;
			$progress->update( $count );
		}
		$progress->finish();
		if( $list ){
			print( PHP_EOL.'! Invalid files:'.PHP_EOL );
			$path	= dirname( __FILE__ ).'/';
			foreach( $list as $file => $message ){
				$relative	= str_replace( $path, '', $file );
				print( 'File:  '.$relative.PHP_EOL );
				print( 'Error: '.$message.PHP_EOL.PHP_EOL );
			}
		}
	}
}
new LibraryClassSyntaxTester( array_slice( $_SERVER['argv'], 1 ) );
