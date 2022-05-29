#!/usr/bin/php
<?php

new CompatCliApp( $argv );

class CompatCliApp
{
	protected $rootPath;

	public function __construct( $arguments )
	{
		$this->rootPath	= dirname( __DIR__ ).'/';
		$this->dispatch( $arguments[1] ?? '' );
	}

	protected function dispatch( string $action )
	{
		$compat	= new CompatWorker( $this->rootPath );
		switch( $action ){
			case 'compat8-show-missing':
				require_once $this->rootPath.'vendor/autoload.php';
				$count	= $compat->showMissing8();
				print( 'Classes not found: '.$count.PHP_EOL );
				break;
			case 'compat8-generate':
				$count	= $compat->generateCompat8();
				print( 'Wrote class map with '.$count.' entries.'.PHP_EOL );
				break;
			case 'compat9-show-missing':
				require_once $this->rootPath.'vendor/autoload.php';
				$count	= $compat->showMissing9();
				print( 'Classes not found: '.$count.PHP_EOL );
				break;
			case 'compat9-generate':
				$count	= $compat->generateCompat9();
				print( 'Wrote class map with '.$count.' entries.'.PHP_EOL );
				break;
			default:
				print( join( PHP_EOL, [
					'Error: No action given.'.PHP_EOL,
					'Usage: compat [ACTION]'.PHP_EOL,
					'Actions:',
					'  compat8-show-missing',
					'  compat8-generate',
					'  compat9-show-missing',
					'  compat9-generate',
				] ).PHP_EOL );
		};
	}
}

class CompatWorker
{
	protected $rootPath;

	public function __construct( string $rootPath )
	{
		$this->rootPath	= $rootPath;
	}

	public function generateCompat8(): int
	{
		$filePath	= $this->rootPath.'compat8.php';

		$count	= 0;
		$list	= [];
		$this->generateCompat8Recursive( $list, $count );

		@unlink( $filePath );
		$handle	= fopen( $filePath, 'w+' );
		fputs( $handle, '<?php'.PHP_EOL );
		foreach( $list as $item )
			fputs( $handle, $item );
		fclose( $handle );
		return $count;
	}

	public function generateCompat9()
	{
		$count		= 0;
		$filePath	= $this->rootPath.'compat9.php';

		$namespaces	= [];
		$this->generateCompat9Recursive( $namespaces, $count );

		@unlink( $filePath );
		$handle	= fopen( $filePath, 'w+' );
		fputs( $handle, '<?php'.PHP_EOL );
		ksort( $namespaces );
		foreach( $namespaces as $namespace => $lines ){
			if( count( $lines ) === 0 )
				continue;
			$lines	= PHP_EOL."\t".join( PHP_EOL."\t", $lines );
			$line	= sprintf( 'namespace %s{%s}'.PHP_EOL, $namespace, $lines );
			fputs( $handle, $line );
		}
		fclose( $handle );
		return $count;
	}

	public function showMissing8()
	{
		$count = 0;
		$this->showMissing8Recursive( $count );
		return $count;
	}

	protected function generateCompat8Recursive( &$list, &$count, $path = '' )
	{
		$index		= new DirectoryIterator( $this->rootPath.'src/'.$path );
		$template	= '%s %s extends %s{};'.PHP_EOL;
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
			$nsClass	= '\\'.$item->namespace.'\\'.$item->class9;
			$list[]		= sprintf( $template, $item->declaration, $item->class8, $nsClass );
			$count++;
		}
	}

	protected function generateCompat9Recursive( &$namespaces, &$count, $path = '' )
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
			if( !isset( $namespaces[$item->namespace] ) )
				$namespaces[$item->namespace] = [];
			$namespaces[$item->namespace][]	= sprintf( $template, $item->declaration, $item->class9, $item->class8 );
			$count++;
		}
	}

	protected function showMissing8Recursive( &$count, $path = '' )
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

class LibraryItem
{
	const TYPE_CLASS			= 0;
	const TYPE_ABSTRACT_CLASS	= 1;
	const TYPE_INTERFACE		= 2;
	const TYPE_TRAIT			= 3;
	public $class8;
	public $class9;
	public $path8;
	public $path9;
	public $namespace;
	public $type				= self::TYPE_CLASS;
	public $declaration			= 'class';

	public static function fromFile( $filePath )
	{
		$item			= new self();
		$parts			= explode( '/', $filePath );
		$fileName		= array_pop( $parts );
		$pathName		= $parts ? join( '/', $parts ).'/' : '';
		$item->class9	= preg_replace( '/\.php*$/', '', $fileName );
		$item->path9	= $parts ? '\\'.join( '\\', $parts ) : '';

		$item->path8	= strtr( $pathName, [
			'/Obj/'			=> '/Object/',
			'/Collection/'	=> '/List/',
		] );
		$item->class8	= str_replace( '/', '_', $item->path8 ).strtr( $item->class9, [
			'Interface_'	=> 'Interface',
			'Object_'		=> 'Object',
			'String_'		=> 'String',
			'Null_'			=> 'Null',
			'Reflect'		=> 'Reflection',
			'Abstraction'	=> 'Abstract',
			'Collection'	=> 'List',
			'UnorderedList'	=> 'List',
		] );
		$item->namespace	= 'CeusMedia\\Common'.$item->path9;

		if( in_array( $item->class9, ['Renderable', 'Interface_'] ) )
			$item->type		= self::TYPE_INTERFACE;
		else if( in_array( $item->class9, ['Abstraction', 'Program', 'Store', 'StaticStore', 'Singleton'] ) )
			$item->type		= self::TYPE_ABSTRACT_CLASS;
		else if( in_array( $item->class8, ['UI_Image_Graph_Generator'] ) )
			$item->type		= self::TYPE_ABSTRACT_CLASS;

		$item->declaration	= strtr( (string) $item->type, [
			(string) self::TYPE_CLASS			=> 'class',
			(string) self::TYPE_ABSTRACT_CLASS	=> 'abstract class',
			(string) self::TYPE_INTERFACE		=> 'interface',
			(string) self::TYPE_TRAIT			=> 'trait',
		] );

		return $item;
	}
}
