<?php
/**
 *	Parses PHP Files containing a Class or Methods to Array using regular expressions (slow).
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		file.php.parser
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.08
 *	@version		$Id$
 */
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.alg.StringUnicoder' );
/**
 *	Parses PHP Files containing a Class or Methods to Array using regular expressions (slow).
 *	@category		cmClasses
 *	@package		file.php.parser
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			04.08.08
 *	@version		$Id$
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
		'description'	=> "",
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
		'category'		=> "",
		'package'		=> "",
		'subpackage'	=> "",
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
		'members'		=> array(),
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
	
	protected $regexClass		= '@^(abstract )?(final )?(interface |class )([\w]+)( extends ([\w]+))?( implements ([\w]+)(, ([\w]+))*)?(\s*{\s*)?$@i';
	protected $regexMethod		= '@^(abstract )?(final )?(protected |private |public )?(static )?function ([\w]+)\((.*)\)(\s*{\s*)?$@';
	protected $regexParam		= '@^(([\w]+) )?((&\s*)?\$([\w]+))( ?= ?([\S]+))?$@';
	protected $regexDocParam	= '@^\*\s+\@param\s+(([\w]+)\s+)?(\$?([\w]+))\s*(.+)?$@';
	protected $regexDocVariable	= '@^/\*\*\s+\@var\s+(\w+)\s+\$(\w+)(\s(.+))?\*\/$@s';
	protected $regexVariable	= '@^(protected|private|public|var)\s+(static\s+)?\$(\w+)(\s+=\s+([^(]+))?.*$@';

	/**
	 *	Parses a PHP File and returns nested Array of collected Information.
	 *	@access		public
	 *	@param		string		$fileName		File Name of PHP File to parse
	 *	@param		string		$innerPath		Base Path to File to be removed in Information
	 *	@return		array
	 */
	public function parseFile( $fileName, $innerPath )
	{
		$content		= File_Reader::load( $fileName );
		$lines			= explode( "\n", $content );
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
			$line	= Alg_StringUnicoder::convertToUnicode( $line );
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
					if( trim( array_pop( array_slice( $matches, -1 ) ) ) == "{" )
					{
						array_pop( $matches );
						$level++;
					}
					while( !trim( array_pop( array_slice( $matches, -1 ) ) ) )
						array_pop( $matches );
					$class	= $this->parseClass( $class, $matches, $openBlocks );
					if( $openBlocks )
						$this->overwriteCodeDataWithDocData( $class, array_pop( $openBlocks ) );
					$openClass	= TRUE;
				}
				else if( preg_match( $this->regexMethod, $line, $matches ) )
				{
					$function	= $this->parseMethod( $matches, $openBlocks );
					if( isset( $matches[8] ) )
						$level++;
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
					if( isset( $matches[8] ) )
						$level++;
				}
				else if( preg_match( $this->regexDocVariable, $line, $matches ) )
				{
					$this->varBlocks[$matches[2]]	= array(
						'type'			=> $matches[1],
						'name'			=> $matches[2],
						'description'	=> trim( $matches[4] ),
					);
				}
				else if( preg_match( $this->regexVariable, $line, $matches ) )
				{
					$name	= $matches[3];
					$default	= NULL;
					if( isset( $matches[4] ) )
						$default	= preg_replace( "@;$@", "", $matches[5] );
					$data	= array(
						'access'		=> $matches[1] == "var" ? "public" : $matches[1],
						'static'		=> (bool) trim( $matches[2] ),
						'type'			=> NULL,
						'name'			=> $name,
						'description'	=> NULL,
						'default'		=> $default,
					);
					if( isset( $this->varBlocks[$name] ) )
						$data	= array_merge( $data, $this->varBlocks[$name] );
					$class['members'][$name]	= $data;
				}
			}
		}
		while( $lines );
		$data	= array(
			'file'		=> $file,
			'class'		=> $class,
			'source'	=> $content,
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
				if( isset( $codeData[$key] ) && is_array( $codeData[$key] ) )
					$codeData[$key][]	= $value;
				else if( isset( $codeData[$key] ) && !$codeData[$key] )
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
				if( trim( $match ) && !preg_match( "@^,|{@", trim( $match ) ) )
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
				if( isset( $matches[2] ) )
				{
					$url	= trim( $matches[1] );
					$name	= trim( $matches[2] );
					if( preg_match( "@^http://@", $matches[2] ) )
					{
						$url	= trim( $matches[2] );
						$name	= trim( $matches[1] );
					}
				}
				else
				{
					$url	= "";
					$name	= trim( $matches[1] );
					if( preg_match( "@^http://@", $matches[1] ) )
						$url	= trim( $matches[1] );
				}
			
				$data['license'][]	= array(
					'url'	=> $url,
					'name'	=> $name,
				);
			}
			else if( preg_match( "/^\*\s+@(\w+)\s+(.+)$/", $line, $matches ) )
			{
				switch( $matches[1] )
				{
					case 'implements':
					case 'deprecated':
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
		if( !$method['access'] )
			$method['access']	= "public";
		return $method;
	}
}
?>