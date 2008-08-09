<?php
/**
 *	Parses PHP Files containing a Class or Methods.
 *	@package		file.php
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.08.08
 *	@version		0.3
 */
import( 'de.ceus-media.file.Reader' );
/**
 *	Parses PHP Files containing a Class or Methods.
 *	@package		file.php
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.08.08
 *	@version		0.3
 *	@todo			Code Doc
 */
class File_PHP_Parser
{
	protected $classData		= array(
		'type'			=> "",
		'name'			=> "",
		"abstract"		=> FALSE,
		"final"			=> FALSE,
		"extends"		=> "",
		"implements"	=> array(),
		"uses"			=> array(),
		'description'	=> "",
		"package"		=> "",
		"subpackage"	=> "",
		"description"	=> array(),
		"todo"			=> array(),
		"see"			=> array(),
		"link"			=> array(),
		"license"		=> array(),
		"copyright"		=> array(),
		"link"			=> array(),
		"version"		=> array(),
		"since"			=> array(),
		"author"		=> array(),
		"methods"		=> array(),
		);

	protected $methodData		= array(
		'name'			=> "",
		'description'	=> "",
		"abstract"		=> FALSE,
		"final"			=> FALSE,
		'static'		=> FALSE,
		'access'		=> "",
		'param'			=> array(),
		'return'		=> array(),
		'throws'		=> array(),
		"version"		=> "",
		"since"			=> "",
		"author"		=> array(),
		"todo"			=> array(),
		"see"			=> array(),
		"link"			=> array(),
	);
	
	var $regexClass		= '@^(abstract )?(final )?(interface |class )([\w]+)( extends ([\w]+))?( implements ([\w]+)(, ([\w]+))*)?$@i';
	var $regexMethod	= '@^(abstract )?(final )?(protected |private |public )?(static )?function ([\w]+)\((.*)\)$@';
	var $regexParam		= '@^(([\w]+) )?((&\s*)?\$([\w]+))( ?= ?([\S]+))?$@';
	var $regexDocParam	= '@^\*\s+\@param\s+(([\w]+)\s+)?(\$([\w]+))\s*(.+)?$@';

	public function parseFile( $fileName, $innerPath )
	{
		$lines			= File_Reader::loadArray( $fileName );
		$openBlocks		= array();
		$fileBlock		= NULL;
		$openClass		= FALSE;
		$file			= $this->classData;
		$file['uri']	= substr( str_replace( "\\", "/", $fileName ), strlen( $innerPath ) );
		unset( $file['methods'] );
		$file['functions']	= array();
	
		$level	= 0;
		$class	= $this->classData;
		do
		{
			$line	= trim( array_shift( $lines ) );
			if( preg_match( "@^(<\?(php)?)|((php)?\?>)$@", $line ) )
				continue;

			if( preg_match( '@{ ?}?$@', $line ) )
				$level++;
			else if( preg_match( '@}$@', $line ) )
				$level--;

			if( $line == "/**" )
			{
				$list	= array();
				do
				{
					$line	= trim( array_shift( $lines ) );
					$list[]	= $line;
				}
				while( !preg_match( "@^\*?\*/$@", $line ) );
				$openBlocks[]	= $this->parseDocBlock( $list );
				if( !$fileBlock )
					$fileBlock	= array_shift( $openBlocks );
			}
			if( !$openClass )
			{
				if( preg_match( $this->regexClass, $line, $matches ) )
				{
					$class	= $this->parseClass( $class, $matches, $openBlocks );
					if( $fileBlock )
						$this->overwriteCodeDataWithDocData( $file, $fileBlock );
					$openClass	= TRUE;
				}
				else if( preg_match( $this->regexMethod, $line, $matches ) )
				{
					$function	= $this->parseMethod( $matches, $openBlocks );
					unset( $function['access'] );
					$file['functions'][$function['name']]	= $function;
				}
			}
			else
			{
				if( $level == 0 && $openClass )
					$openClass	= FALSE;
				if( preg_match( $this->regexMethod, $line, $matches ) )
				{
					$method	= $this->parseMethod( $matches, $openBlocks );
					$class['methods'][$method['name']]	= $method;
				}
			}
		}
		while( $lines );
		$data	= array(
			'file'		=> $file,
			'class'		=> $class,
		);
		return $data;
	}

	private function overwriteCodeDataWithDocData( &$codeData, $docData )
	{
		foreach( $docData as $key => $value )
		{
			if( !$value )
				continue;
			if( is_string( $value ) )
			{
				if( isset( $codeData[$key] ) && !$codeData[$key] )
					$codeData[$key]	= $value;
			}
			else if( isset( $codeData[$key] ) )
			{
#				remark( "Key: ".$key );
				foreach( $value as $itemKey	=> $itemValue )
				{
#					remark( "ItemKey: ".$itemKey );
					if( is_string( $itemValue ) )
					{
						$codeData[$key][]	= $itemValue;
					}
					else if( isset( $codeData[$key][$itemKey] ) )
					{
						foreach( $itemValue as $itemItemKey => $itemItemValue )
						{
#							remark( "ItemItemKey: ".$itemItemKey );
							if( isset( $codeData[$key][$itemKey][$itemItemKey] ) )
								continue;
							$codeData[$key][$itemKey][$itemItemKey]	= $itemItemValue;
						}
					}
				}
			}
		}
	}
	
	private function parseClass( $data, $matches, &$openBlocks )
	{
	#	print_m( $matches );
		if( $matches[1] )
			$data['abstract']	= TRUE;
		if( $matches[2] )
			$data['final']		= TRUE;
		$data['type']	= $matches[3];
		$data['name']	= $matches[4];
		if( isset( $matches[5] ) )
			$data['extends']	= $matches[6];
		if( isset( $matches[7] ) )
		{
			$matches	= array_slice( $matches, 8 );
			foreach( $matches as $match )
			{
				if( preg_match( "@^, @", $match ) )
					continue;
				$data['implements'][]	= $match;
			}
		}
		if( $openBlocks )
		{
			$classBlock	= array_pop( $openBlocks );
			$this->overwriteCodeDataWithDocData( $data, $classBlock );
		}
		return $data;
	}

	private function parseDocBlock( $lines )
	{
#		remark( "Parsing DocBlock" );
		$data		= array();
		$descLines	= array();
		foreach( $lines as $line )
		{
			if( preg_match( $this->regexDocParam, $line, $matches ) )
			{
#				print_m( $matches );
				$param	= array(
					'type'			=> $matches[2],
					'name'			=> $matches[4],
					'description'	=> "",
				);
				if( isset( $matches[5] ) )
					$param['description']		= $matches[5];
				if( !isset( $data['param'] ) )
					$data['param']	= array();
				
				$data['param'][$matches[4]]	= $param;
			}
			else if( preg_match( "@\*\s+\@author(.+)( <(.+)>)?$@iU", $line, $matches ) )
			{
				$data['author'][]	= array(
					'name'	=> trim( $matches[1] ),
					'mail'	=> isset( $matches[3] ) ? trim( $matches[3] ) : "",
				);
			}
			else if( preg_match( "@\*\s+\@license\s+(\S+)( .+)?$@i", $line, $matches ) )
			{
				$data['license'][]	= array(
					'url'	=> trim( $matches[1] ),
					'name'	=> isset( $matches[2] ) ? trim( $matches[2] ) : "",
				);
			}
			else if( preg_match( "/^\*\s+@(\w+)\s+(.+)$/", $line, $matches ) )
			{
				switch( $matches[1] )
				{
					case 'implements':
					case 'todo':
					case 'see':
					case 'link':
					case 'copyright':
					case 'license':
					case 'author':
					case 'see':
					case 'uses':
					case 'throws':
					case 'link':
						$data[$matches[1]][]	= $matches[2];			
						break;
					default:
						$data[$matches[1]]	= $matches[2];			
						break;
				}
			}
			else if( !$data && preg_match( "/^\*\s+([^@].+)$/", $line, $matches ) )
			{
				$descLines[]	= $matches[1];
			}
			
		}
		$data['description']	= implode( "\n", $descLines );
		if( !isset( $data['throws'] ) )
			$data['throws']	= array();
		foreach( $data['throws'] as $throws )
		{
			$list	= array();
			$parts	= explode( ",", $throws );
			foreach( $parts as $part )
				$list[]	= trim( $part );
			$data['throws']	= $list;
		}
#		print_m( $data );
		return $data;
	}

	private function parseMethod( $matches, &$openBlocks )
	{
#		print_m( $matches );
		$method	= $this->methodData;
		$method['name']		= $matches[5];
		$method['access']	= trim( $matches[3] );
		if( $matches[1] )
			$method['abstract']	= TRUE;
		if( $matches[2] )
			$method['final']	= TRUE;
		if( $matches[4] )
			$method['static']	= TRUE;
		if( trim( $matches[6] ) )
		{
			$params		= explode( ",", $matches[6] );
			$paramList	= array();
			foreach( $params as $param )
			{
				$param	 = trim( $param );
#				remark( $param );
				if( !preg_match( $this->regexParam, $param, $matches ) )
					continue;
#				print_m( $matches );
				$param	= array(
					'cast'		=> $matches[2],
					'reference'	=> $matches[4] ? TRUE : FALSE,
					'name'		=> $matches[5],
				);
				if( isset( $matches[6] ) )
					$param['default']	= $matches[6];
				$method['param'][$matches[5]] = $param; 
			}
		}
		if( $openBlocks )
		{
			$methodBlock	= array_pop( $openBlocks );
			$this->overwriteCodeDataWithDocData( $method, $methodBlock );
			$openBlocks	= array();
		}
		return $method;
	}
}
?>