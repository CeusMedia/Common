<?php
namespace CeusMedia\CommonTool\Compatibility;

use DirectoryIterator;

class Worker
{
	protected string $rootPath;

	public function __construct( string $rootPath )
	{
		$this->rootPath	= $rootPath;
	}

	public function generateCompat8(): int
	{
		$filePath	= $this->rootPath.'src/compat8.php';
		$count	= 0;
		$list	= [];
		$this->generateCompat8Recursive( $list, $count );
        ksort( $list );

		@unlink( $filePath );
		$handle	= fopen( $filePath, 'w+' );
		fputs( $handle, '<?php'.PHP_EOL.'/** @noinspection PhpMultipleClassDeclarationsInspection */'.PHP_EOL.PHP_EOL );
		foreach( $list as $item )
			fputs( $handle, $item );
		fclose( $handle );
		return $count;
	}

	public function generateCompat9(): int
	{
		$count		= 0;
		$filePath	= $this->rootPath.'src/compat9.php';

		$namespaces	= [];
		$this->generateCompat9Recursive( $namespaces, $count );

		@unlink( $filePath );
		$handle	= fopen( $filePath, 'w+' );
		fputs( $handle, '<?php'.PHP_EOL.'/** @noinspection PhpMultipleClassDeclarationsInspection */'.PHP_EOL.PHP_EOL );
		ksort( $namespaces );
		foreach( $namespaces as $namespace => $lines ){
			asort($lines);
			if( count( $lines ) === 0 )
				continue;
			$lines	= PHP_EOL."\t".join( PHP_EOL."\t", $lines );
			$line	= sprintf( 'namespace %s{%s}'.PHP_EOL, $namespace, $lines );
			fputs( $handle, $line );
		}
		fclose( $handle );
		return $count;
	}

	public function showMissing8(): int
	{
		$count = 0;
		$this->showMissing8Recursive( $count );
		return $count;
	}

	protected function generateCompat8Recursive( &$list, int &$count, string $path = '' ): void
	{
		$index		= new DirectoryIterator( $this->rootPath.'src/'.$path );
		$template	= '%s %s extends %s{}'.PHP_EOL;
		foreach( $index as $entry ){
			if( $entry->isDot() )
				continue;
			$folder	= $path ? $path.'/' : '';
			if( $entry->isDir() ){
				$this->generateCompat8Recursive( $list, $count, $folder.$entry->getFilename() );
				continue;
			}
			if( !preg_match( '/^[A-Z].+\.php$/', $entry->getFilename() ) )
				continue;
			$item		= LibraryItem::fromFile( $folder.$entry->getFilename() );
			if( in_array( $item->class9, ['Collection2', 'Compatibility'] ) )
				continue;
			$nsClass	= $item->namespace.'\\'.$item->class9;
			$list[$item->class8]	= sprintf( $template, $item->declaration, $item->class8, $nsClass );
			$count++;
		}
	}

	protected function generateCompat9Recursive( &$namespaces, int &$count, string $path = '' ): void
	{
		$index		= new DirectoryIterator( $this->rootPath.'src/'.$path );
		$template	= '%s %s extends \\%s{}';
		foreach( $index as $entry ){
			if( $entry->isDot() )
				continue;
			$folder	= $path ? $path.'/' : '';
			if( $entry->isDir() ){
				$this->generateCompat9Recursive( $namespaces, $count, $folder.$entry->getFilename() );
				continue;
			}
			if( !preg_match( '/^[A-Z].+\.php$/', $entry->getFilename() ) )
				continue;
			$item	= LibraryItem::fromFile( $folder.$entry->getFilename() );
//			var_export( $item );die;
			if( !isset( $namespaces[$item->namespace] ) ){
				$namespaces[$item->namespace] = [];
				arsort($namespaces);
			}
			$namespaces[$item->namespace][]	= sprintf( $template, $item->declaration, $item->class9, $item->class8 );
			$count++;
		}
	}

	protected function showMissing8Recursive( int &$count, string $path = '' ): void
	{
		$index	= new DirectoryIterator( $this->rootPath.'src/'.$path );
		foreach( $index as $entry ){
			if( $entry->isDot() )
				continue;
			$folder	= $path ? $path.'/' : '';
			if( $entry->isDir() ){
				$this->showMissing8Recursive( $count, $folder.$entry->getFilename() );
				continue;
			}
			$item		= LibraryItem::fromFile( $folder.$entry->getFilename() );
			if( $item->type === LibraryItem::TYPE_INTERFACE ){
				if( !interface_exists( $item->class8 ) ){
					print( $item->class8.PHP_EOL );
					$count++;
				}
			}
			else {
				if( !class_exists( $item->class8 ) ){
					print( $item->class8.PHP_EOL );
					$count++;
				}
			}
		}
	}
}
