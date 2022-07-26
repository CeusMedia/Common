<?php
namespace CeusMedia\Common\Tool\Compatibility;

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
