<?php

namespace CeusMedia\CommonTool\Go;

use CeusMedia\Common\CLI\Output\Progress;
use DirectoryIterator;
use Exception;

/**
 *	@deorecated not needed once Go is gone
 */
class Library
{
	public static string $configFile	= 'Common.ini';

	public static function getConfigData(): array
	{
		return parse_ini_file( self::getConfigFile(), TRUE ) ?: [];
	}

	public static function getConfigFile(): string
	{
		return dirname( __FILE__, 3 ).'/'.self::$configFile;
	}

	public static function listClasses( string $path ): array
	{
		$count	= 0;
		$size	= 0;
		$list	= [];
		self::listClassesRecursive( $path, $list, $count, $size );
		return array(
			'path'	=> $path,
			'count'	=> $count,
			'size'	=> $size,
			'files'	=> $list
		);
	}

	public static function getGoPath(): string
	{
		return dirname( __FILE__ ).'/';
	}

	public static function getLibraryPath(): string
	{
		return dirname( __DIR__ ).'/';
	}

	public static function getSourcePath(): string
	{
		return self::getLibraryPath().'/src/';
	}

	protected static function listClassesRecursive( string $path, array &$list, int &$count , int &$size ): void
	{
		$index	= new DirectoryIterator( $path );
		foreach( $index as $entry ){
			$pathName	= $entry->getPathname();
			if( $entry->isDot() )
				continue;
			if( $entry->getFilename() == ".git" )
				continue;
			if( $entry->isDir() ){
		#		echo "Path: ".$entry->getPath()."\n";
				self::listClassesRecursive( $pathName, $list, $count, $size );
			}
			else if( $entry->isFile() ){
				$info	= pathinfo( $pathName );
				if( $info['extension'] !== "php" )
					continue;
				if( !preg_match( '/^[A-Z]/', $info['basename'] ) )
					continue;
				$list[] = $pathName;
				$size	+= filesize( $pathName );
				$count++;
			}
		}
	}

	public static function showMemoryUsage(): void
	{
		$number	= ceil( memory_get_usage() / 1024 );
		print( "\nmemory: ".$number."KB" );
	}

	/**
	 *	@deprecated	since CMC_Loader
	 */
	public static function testImports( array $files ): void
	{
		remark( "Checking nested imports\n" );
		$count	= 0;
		$path	= dirname( __FILE__ )."/";
		$line	= str_repeat( "-", 79 );
		$list	= [];
		foreach( $files as $file ){
			$relative	= str_replace( $path, "", $file );
			if( $count && !( $count % 60 ) )
				echo " ".$count."/".count( $files )."\n";
			try{
				@require_once( $relative );
				echo ".";
			}
			catch( Exception $e ){
				$list[$file]	= $e;
				echo "E";
			}
			$count++;
		}
		echo "  ".$count."/".count( $files )."\n";
		if( $list ){
			remark( "\n! Invalid files:" );
			foreach( $list as $file => $exception ){
				$relative	= str_replace( $path, "", $file );
				remark( "File: ".$relative );
				remark( $exception->getMessage() );
				remark( $line );
			}
		}
	}

	public static function testSyntax( array $files ): void
	{
		remark( "Checking class file syntax\n" );
		$count	= 0;
		$path	= dirname( __FILE__ )."/";
		$line	= str_repeat( "-", 79 );
		$list	= [];
		$progress	= new Progress();
		$progress->setTotal( count( $files ) );
		$progress->start();
		foreach( $files as $file ){
			$code	= 0;
			$output	= [];
			exec( 'php -l "'.$file.'" 2>&1', $output, $code );
			if( !str_starts_with(join( PHP_EOL, $output), 'No syntax errors detected' ) )
				$list[$file]	= join( PHP_EOL, $output );
			$count++;
			$progress->update( $count );
		}
		$progress->finish();
		if( $list ){
			remark( "\n! Invalid files:" );
			foreach( $list as $file => $message ){
				$relative	= str_replace( $path, "", $file );
				remark( "File:  ".$relative );
				remark( "Error: ".$message.PHP_EOL );
			}
		}
	}
}
