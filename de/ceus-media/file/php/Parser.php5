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
	protected $fileData	= array(
		'name'			=> "",
		'uri'			=> "",
		'uses'			=> array(),
		'description'	=> "",
		'package'		=> "",
		'subpackage'	=> "",
		'description'	=> array(),
		'license'		=> array(),
		'copyright'		=> array(),
		'version'		=> "",
		'since'			=> "",
		'author'		=> array(),
		'see'			=> array(),
		'link'			=> array(),
		'functions'		=> array(),
	);
	protected $classData		= array(
		'type'			=> "",
		'name'			=> "",
		'abstract'		=> FALSE,
		'final'			=> FALSE,
		'extends'		=> "",
		'implements'	=> array(),
		'uses'			=> array(),
		'description'	=> "",
		'package'		=> "",
		'subpackage'	=> "",
		'description'	=> array(),
		'license'		=> array(),
		'copyright'		=> array(),
		'version'		=> "",
		'since'			=> "",
		'author'		=> array(),
		'link'			=> array(),
		'see'			=> array(),
		'todo'			=> array(),
		'deprecated'	=> array(),
		'methods'		=> array(),
	);

	protected $methodData		= array(
		'name'			=> "",
		'description'	=> "",
		"abstract"		=> FALSE,
		"final"			=> FALSE,
		'static'		=> FALSE,
		'access'		=> "",
		'param'			=> array(),
		'return'		=> array(
			'type'			=> "void",
			'description' => NULL,
		),
		'throws'		=> array(),
		"version"		=> "",
		"since"			=> "",
		"author"		=> array(),
		"see"			=> array(),
		"link"			=> array(),
		"todo"			=> array(),
		"deprecated"	=> array(),
	);
	
	protected $regexClass		= '@^(abstract )?(final )?(interface |class )([\w]+)( extends ([\w]+))?( implements ([\w]+)(, ([\w]+))*)?$@i';
	protected $regexMethod	= '@^(abstract )?(final )?(protected |private |public )?(static )?function ([\w]+)\((.*)\)$@';
	protected $regexParam		= '@^(([\w]+) )?((&\s*)?\$([\w]+))( ?= ?([\S]+))?$@';
	protected $regexDocParam	= '@^\*\s+\@param\s+(([\w]+)\s+)?(\$?([\w]+))\s*(.+)?$@';

	/**
	 *	Parses a PHP File and returns nested Array of collected Information.
	 *	@access		public
	 *	@param		string		$fileName		File Name of PHP File to parse
	 *	@param		string		$innerPath		Base Path to File to be removed in Information
	 *	@return		array
	 */
	public function parseFile( $fileName, $innerPath )
	{
		$lines			= File_Reader::loadArray( $fileName );
		$openBlocks		= array();
		$fileBlock		= NULL;
		$openClass		= FALSE;
		$file			= $this->fileData;
		$file['name']	= substr( str_replace( "\\", "/", $fileName ), strlen( $innerPath ) );
		$file['uri']	= str_replace( "\\", "/", $fileName );
	
		$level	= 0;
		$class	= NULL;
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
				{
					$fileBlock	= array_shift( $openBlocks );
					$this->overwriteCodeDataWithDocData( $file, $fileBlock );
				}
			}
			if( !$openClass )
			{
				if( preg_match( $this->regexClass, $line, $matches ) )
				{
					$class	= $this->classData;
					$class	= $this->parseClass( $class, $matches, $openBlocks );
					if( $openBlocks )
						$this->overwriteCodeDataWithDocData( $class, array_pop( $openBlocks ) );
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

	/**
	 *	Appends all collected Documentation Information to already collected Code Information.
	 *	@access		private
	 *	@param		array		$codeData		Data collected by parsing Code
	 *	@param		string		$docData		Data collected by parsing Documentation
	 *	@return		void
	 */
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
				foreach( $value as $itemKey	=> $itemValue )
				{
					if( is_string( $itemValue ) )
					{
						if( is_string( $itemKey ) )
							$codeData[$key][$itemKey]	= $itemValue;
						else if( is_int( $itemKey ) && !in_array( $itemValue, $codeData[$key] ) )
							$codeData[$key][]	= $itemValue;
					}
					else if( is_string( $itemKey ) && isset( $codeData[$key][$itemKey] ) )
					{
						foreach( $itemValue as $itemItemKey => $itemItemValue )
							if( !isset( $codeData[$key][$itemKey][$itemItemKey] ) )
								$codeData[$key][$itemKey][$itemItemKey]	= $itemItemValue;
					}
					else if( $key != "param" )
					{
						foreach( $itemValue as $itemItemKey => $itemItemValue )
							if( !isset( $codeData[$key][$itemKey][$itemItemKey] ) )
								$codeData[$key][$itemKey][$itemItemKey]	= $itemItemValue;
					}
				}
			}
		}
	}
	
	/**
	 *	Parses a Class Signature and returns Array of collected Information.
	 *	@access		private
	 *	@param		array		$data			Class Data so far
	 *	@param		array		$matches		Matches of RegEx
	 *	@param		array		$openBlocks		Doc Blocks opened before
	 *	@return		array
	 */
	private function parseClass( $data, $matches, &$openBlocks )
	{
		$data['abstract']	= (bool) $matches[1];
		$data['final']		= (bool) $matches[2];
		$data['type']		= $matches[3];
		$data['name']		= $matches[4];
		$data['extends']	= isset( $matches[5] ) ? $matches[6] : NULL;
		if( isset( $matches[7] ) )
			foreach( array_slice( $matches, 8 ) as $match )
				if( !preg_match( "@^,@", $match ) )
					$data['implements'][]	= trim( $match );
		if( $openBlocks )
			$this->overwriteCodeDataWithDocData( $data, array_pop( $openBlocks ) );
		return $data;
	}

	/**
	 *	Parses a Doc Block and returns Array of collected Information.
	 *	@access		private
	 *	@param		array		$lines			Lines of Doc Block
	 *	@return		array
	 */
	private function parseDocBlock( $lines )
	{
#		remark( "Parsing DocBlock" );
		$data		= array();
		$descLines	= array();
		foreach( $lines as $line )
		{
			if( preg_match( $this->regexDocParam, $line, $matches ) )
			{
				$param	= array(
					'type'			=> $matches[2],
					'name'			=> $matches[4],
					'description'	=> isset( $matches[5] ) ? $matches[5] : NULL,
				);
				if( !isset( $data['param'] ) )
					$data['param']	= array();
				$data['param'][$matches[4]]	= $param;
			}
			else if( preg_match( "@\*\s+\@return\s+(\w+)\s*(.+)?$@i", $line, $matches ) )
			{
				$data['return']	= array(
					'type'			=> trim( $matches[1] ),
					'description'	=> isset( $matches[2] ) ? trim( $matches[2] ) : "",
				);
			}
			else if( preg_match( "@\*\s+\@author\s+(.+)\s*(<(.+)>)?$@iU", $line, $matches ) )
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
			else if( !$data && preg_match( "/^\*\s*([^@].+)?$/", $line, $matches ) )
				$descLines[]	= isset( $matches[1] ) ? trim( $matches[1] ) : "";
		}
		$data['description']	= trim( implode( "\n", $descLines ) );
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
		return $data;
	}

	/**
	 *	Parses a Method Signature and returns Array of collected Information.
	 *	@access		private
	 *	@param		array		$matches		Matches of RegEx
	 *	@param		array		$openBlocks		Doc Blocks opened before
	 *	@return		array
	 */
	private function parseMethod( $matches, &$openBlocks )
	{
		$method	= $this->methodData;
		$method['name']		= $matches[5];
		$method['access']	= trim( $matches[3] );
		$method['abstract']	= (bool) $matches[1];
		$method['final']	= (bool) $matches[2];
		$method['static']	= (bool) $matches[4];
		if( trim( $matches[6] ) )
		{
			$paramList	= array();
			foreach( explode( ",", $matches[6] ) as $param )
			{
				$param	 = trim( $param );
				if( !preg_match( $this->regexParam, $param, $matches ) )
					continue;
				$param	= array(
					'cast'		=> $matches[2],
					'reference'	=> $matches[4] ? TRUE : FALSE,
					'name'		=> $matches[5],
				);
				if( isset( $matches[6] ) )
					$param['default']	= $matches[7];
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