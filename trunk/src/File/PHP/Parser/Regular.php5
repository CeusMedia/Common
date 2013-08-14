<?php
/**
 *	Parses PHP Files containing a Class or Methods using regular expressions (slow).
 *
 *	Copyright (c) 2008-2012 Christian Würker (ceusmedia.com)
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
 *	@package		File.PHP.Parser
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@since			04.08.08
 *	@todo			support multiple return types separated with |
 */
/**
 *	Parses PHP Files containing a Class or Methods using regular expressions (slow).
 *	@category		cmClasses
 *	@package		File.PHP.Parser
 *	@uses			File_Reader
 *	@uses			ADT_PHP_File
 *	@uses			ADT_PHP_Interface
 *	@uses			ADT_PHP_Class
 *	@uses			ADT_PHP_Variable
 *	@uses			ADT_PHP_Member
 *	@uses			ADT_PHP_Function
 *	@uses			ADT_PHP_Method
 *	@uses			ADT_PHP_Parameter
 *	@uses			ADT_PHP_Author
 *	@uses			ADT_PHP_License
 *	@uses			ADT_PHP_Return
 *	@uses			ADT_PHP_Throws
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@since			04.08.08
 *	@todo			Code Doc
 */
class File_PHP_Parser_Regular
{
	protected $regexClass		= '@^(abstract )?(final )?(interface |class )([\w]+)( extends ([\w]+))?( implements ([\w]+)(, ([\w]+))*)?(\s*{)?@i';
	protected $regexMethod		= '@^(abstract )?(final )?(static )?(protected |private |public )?(static )?function ([\w]+)\((.*)\)(\s*{\s*)?;?\s*$@';
	protected $regexParam		= '@^(([\w]+) )?((&\s*)?\$([\w]+))( ?= ?([\S]+))?$@';
	protected $regexDocParam	= '@^\*\s+\@param\s+(([\S]+)\s+)?(\$?([\S]+))\s*(.+)?$@';
	protected $regexDocVariable	= '@^/\*\*\s+\@var\s+(\w+)\s+\$(\w+)(\s(.+))?\*\/$@s';
	protected $regexVariable	= '@^(protected|private|public|var)\s+(static\s+)?\$(\w+)(\s+=\s+([^(]+))?.*$@';
	protected $varBlocks		= array();

	protected $openBlocks		= array();
	protected $lineNumber		= 0;


	/**
	 *	Appends all collected Documentation Information to already collected Code Information.
	 *	In general, found doc parser data are added to the php parser data.
	 *	Found doc data can contain strings, objects and lists of strings or objects.
	 *	Since parameters are defined in signature and doc block, they need to be merged.
	 *	Parameters are given with an associatove list indexed by parameter name.
	 *
	 *	@access		protected
	 *	@param		array		$codeData		Data collected by parsing Code
	 *	@param		string		$docData		Data collected by parsing Documentation
	 *	@return		void
	 *	@todo		fix merge problem -> seems to be fixed (what was the problem again?)
	 */
	protected function decorateCodeDataWithDocData( &$codeData, $docData )
	{
		foreach( $docData as $key => $value )
		{
			if( !$value )
				continue;

			if( is_object( $value ) )															//  value is an object
			{
				if( $codeData instanceof ADT_PHP_Function )
				{
					switch( $key )
					{
						case 'return':		$codeData->setReturn( $value ); break;
					}
				}
			}
			else if( is_string( $value ) )																//  value is a simple string
			{
				switch( $key )
				{
					case 'category':	$codeData->setCategory( $value ); break;					//  extend category
					case 'package':		$codeData->setPackage( $value ); break;						//  extend package
					case 'subpackage':	$codeData->setSubpackage( $value ); break;					//  extend subpackage
					case 'version':		$codeData->setVersion( $value ); break;						//  extend version
					case 'since':		$codeData->setSince( $value ); break;						//  extend since
					case 'description':	$codeData->setDescription( $value ); break;					//  extend description
					case 'todo':		$codeData->setTodo( $itemValue ); break;					//  extend todos
				}
				if( $codeData instanceof ADT_PHP_Interface )
				{
					switch( $key )
					{
						case 'access':
							if( !$codeData->getAccess() )											//  only if no access type given by signature
								$codeData->setAccess( $value );										//  extend access type
							break;
						case 'extends':		$codeData->setExtendedClassName( $value ); break;		//  extend extends
					}
				}
				if( $codeData instanceof ADT_PHP_Method )
				{
					switch( $key )
					{
						case 'access':
							if( !$codeData->getAccess() )											//  only if no access type given by signature
								$codeData->setAccess( $value );										//  extend access type
							break;
					}
				}
			}
			else if( is_array( $value ) )															//  value is a list of objects or strings
			{
				foreach( $value as $itemKey => $itemValue )											//  iterate list
				{
					if( is_string( $itemKey ) )														//  special case: value is associative array -> a parameter to merge
					{
						switch( $key )
						{
							case 'param':
								foreach( $codeData->getParameters() as $parameter )
									if( $parameter->getName() == $itemKey ){
										$parameter->merge( $itemValue );
									}
								break;
						}
					}
					else																			//  value is normal list of objects or strings
					{
						switch( $key )
						{
							case 'license':		$codeData->setLicense( $itemValue ); break;
							case 'copyright':	$codeData->setCopyright( $itemValue ); break;
							case 'author':		$codeData->setAuthor( $itemValue ); break;
							case 'link':		$codeData->setLink( $itemValue ); break;
							case 'see':			$codeData->setSee( $itemValue ); break;
							case 'deprecated':	$codeData->setDeprecation( $itemValue ); break;
							case 'todo':		$codeData->setTodo( $itemValue ); break;
						}
						if( $codeData instanceof ADT_PHP_Interface )
						{
							switch( $key )
							{
								case 'implements':	$codeData->setImplementedInterfaceName( $itemValue ); break;
								case 'uses':		$codeData->setUsedClassName( $itemValue ); break;
							}
						}
						else if( $codeData instanceof ADT_PHP_Function )
						{
							switch( $key )
							{
								case 'throws':		$codeData->setThrows( $itemValue ); break;
								case 'trigger':		$codeData->setTrigger( $itemValue ); break;
							}
						}
					}
				}
			}
		}
	}

	/**
	 *	Parses a Class Signature and returns collected Information.
	 *	@access		protected
	 *	@param		ADT_PHP_File		$parent			File Object of current Class
	 *	@param		array				$matches		Matches of RegEx
	 *	@return		ADT_PHP_Interface|ADT_PHP_Class
	 */
	protected function parseClassOrInterface( ADT_PHP_File $parent, $matches )
	{
		switch( strtolower( trim( $matches[3] ) ) )
		{
			case 'interface':
				$artefact	= new ADT_PHP_Interface( $matches[4] );
				if( isset( $matches[5] ) )
					$artefact->setExtendedInterfaceName( $matches[6] );
				$artefact->setFinal( (bool) $matches[2] );
				break;
			default:
				$artefact	= new ADT_PHP_Class( $matches[4] );
				if( isset( $matches[5] ) )
					$artefact->setExtendedClassName( $matches[6] );
				$artefact->setFinal( (bool) $matches[2] );
				$artefact->setAbstract( (bool) $matches[1] );
				if( isset( $matches[7] ) )
					foreach( array_slice( $matches, 8 ) as $match )
						if( trim( $match ) && !preg_match( "@^,|{@", trim( $match ) ) )
							$artefact->setImplementedInterfaceName( trim( $match ) );
				break;
		}
		$artefact->setParent( $parent );
		$artefact->setLine( $this->lineNumber );
		$artefact->type		= $matches[3];
		if( $this->openBlocks )
		{
			$this->decorateCodeDataWithDocData( $artefact, array_pop( $this->openBlocks ) );
			$this->openBlocks	= array();
		}
		if( !$artefact->getCategory() && $parent->getCategory() )
			$artefact->setCategory( $parent->getCategory() );
		if( !$artefact->getPackage() && $parent->getPackage() )
			$artefact->setPackage( $parent->getPackage() );
		return $artefact;
	}

	/**
	 *	Parses a Doc Block and returns Array of collected Information.
	 *	@access		protected
	 *	@param		array		$lines			Lines of Doc Block
	 *	@return		array
	 */
	protected function parseDocBlock( $lines )
	{
		$data		= array();
		$descLines	= array();
		foreach( $lines as $line )
		{
			if( preg_match( $this->regexDocParam, $line, $matches ) )
			{
				$data['param'][$matches[4]]	= $this->parseDocParameter( $matches );
			}
			else if( preg_match( "@\*\s+\@return\s+(\w+)\s*(.+)?$@i", $line, $matches ) )
			{
				$data['return']	= $this->parseDocReturn( $matches );
			}
			else if( preg_match( "@\*\s+\@throws\s+(\w+)\s*(.+)?$@i", $line, $matches ) )
			{
				$data['throws'][]	= $this->parseDocThrows( $matches );
			}
			else if( preg_match( "@\*\s+\@trigger\s+(\w+)\s*(.+)?$@i", $line, $matches ) )
			{
				$data['trigger'][]	= $this->parseDocTrigger( $matches );
			}
			else if( preg_match( "@\*\s+\@author\s+(.+)\s*(<(.+)>)?$@iU", $line, $matches ) )
			{
				$author	= new ADT_PHP_Author( trim( $matches[1] ) );
				if( isset( $matches[3] ) )
					$author->setEmail( trim( $matches[3] ) );
				$data['author'][]	= $author;
			}
			else if( preg_match( "@\*\s+\@license\s+(\S+)( .+)?$@i", $line, $matches ) )
			{
				$data['license'][]	= $this->parseDocLicense( $matches );
			}
			else if( preg_match( "/^\*\s+@(\w+)\s*(.*)$/", $line, $matches ) )
			{
				switch( $matches[1] )
				{
					case 'implements':
					case 'deprecated':
					case 'todo':
					case 'copyright':
					case 'see':
					case 'uses':
					case 'link':
						$data[$matches[1]][]	= $matches[2];			
						break;
					case 'since':
					case 'version':
					case 'access':
					case 'category':
					case 'package':
					case 'subpackage':
						$data[$matches[1]]	= $matches[2];			
						break;
					default:
						break;
				}
			}
			else if( !$data && preg_match( "/^\*\s*([^@].+)?$/", $line, $matches ) )
				$descLines[]	= isset( $matches[1] ) ? trim( $matches[1] ) : "";
		}
		$data['description']	= trim( implode( "\n", $descLines ) );

		if( !isset( $data['throws'] ) )
			$data['throws']	= array();
		return $data;
	}

	/**
	 *	Parses a File/Class License Doc Tag and returns collected Information.
	 *	@access		protected
	 *	@param		array		$matches		Matches of RegEx
	 *	@return		ADT_PHP_License
	 */
	protected function parseDocLicense( $matches )
	{
		$name	= NULL;
		$url	= NULL;
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
			$name	= trim( $matches[1] );
			if( preg_match( "@^http://@", $matches[1] ) )
				$url	= trim( $matches[1] );
		}
		$license	= new ADT_PHP_License( $name, $url );
		return $license;
	}

	/**
	 *	Parses a Class Member Doc Tag and returns collected Information.
	 *	@access		protected
	 *	@param		array		$matches		Matches of RegEx
	 *	@return		ADT_PHP_Member
	 */
	protected function parseDocMember( $matches )
	{
		$member	= new ADT_PHP_Member( $matches[2], $matches[1], trim( $matches[4] ) );
		return $member;
	}

	/**
	 *	Parses a Function/Method Parameter Doc Tag and returns collected Information.
	 *	@access		protected
	 *	@param		array		$matches		Matches of RegEx
	 *	@return		ADT_PHP_Parameter
	 */
	protected function parseDocParameter( $matches )
	{
		$parameter	= new ADT_PHP_Parameter( $matches[4], $matches[2] );
		if( isset( $matches[5] ) )
			$parameter->setDescription( $matches[5] );
		return $parameter;
	}

	/**
	 *	Parses a Function/Method Return Doc Tag and returns collected Information.
	 *	@access		protected
	 *	@param		array		$matches		Matches of RegEx
	 *	@return		ADT_PHP_Return
	 */
	protected function parseDocReturn( $matches )
	{
		$return	= new ADT_PHP_Return( trim( $matches[1] ) );
		if( isset( $matches[2] ) )
			$return->setDescription( trim( $matches[2] ) );
		return $return;
	}

	/**
	 *	Parses a Function/Method Throws Doc Tag and returns collected Information.
	 *	@access		protected
	 *	@param		array		$matches		Matches of RegEx
	 *	@return		ADT_PHP_Throws
	 */
	protected function parseDocThrows( $matches )
	{
		$throws	= new ADT_PHP_Throws( trim( $matches[1] ) );
		if( isset( $matches[2] ) )
			$throws->setReason( trim( $matches[2] ) );
		return $throws;
	}

	/**
	 *	Parses a Function/Method Trigger Doc Tag and returns collected Information.
	 *	@access		protected
	 *	@param		array		$matches		Matches of RegEx
	 *	@return		ADT_PHP_Trigger
	 */
	protected function parseDocTrigger( $matches )
	{
		$trigger	= new ADT_PHP_Trigger( trim( $matches[1] ) );
		if( isset( $matches[2] ) )
			$trigger->setCondition( trim( $matches[2] ) );
		return $trigger;
	}

	/**
	 *	Parses a Class Varible Doc Tag and returns collected Information.
	 *	@access		protected
	 *	@param		array		$matches		Matches of RegEx
	 *	@return		ADT_PHP_Variable
	 */
	protected function parseDocVariable( $matches )
	{
		$variable	= new ADT_PHP_Variable( $matches[2], $matches[1], trim( $matches[4] ) );
		return $variable;
	}

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
		if( !Alg_Text_Unicoder::isUnicode( $content ) )
			$content		= Alg_Text_Unicoder::convertToUnicode( $content );


		$lines			= explode( "\n", $content );
		$fileBlock		= NULL;
		$openClass		= FALSE;
		$function		= NULL;
		$functionBody	= array();
		$file			= new ADT_PHP_File;
		$file->setBasename( basename( $fileName ) );
		$file->setPathname( substr( str_replace( "\\", "/", $fileName ), strlen( $innerPath ) ) );
		$file->setUri( str_replace( "\\", "/", $fileName ) );
	
		$level	= 0;
		$class	= NULL;
		do
		{
			$line	= trim( array_shift( $lines ) );
			$this->lineNumber ++;
			if( preg_match( "@^(<\?(php)?)|((php)?\?>)$@", $line ) )
				continue;
			
//			if( preg_match( '@^\s*{ ?}?$@', $line ) )
//				$level++;
			if( preg_match( '@{$@', $line ) )
				$level++;
			if( preg_match( '@}$@', $line ) )
				$level--;

			if( $line == "/**" && $level < 2 )
			{
				$list	= array();
				while( !preg_match( "@\*?\*/\s*$@", $line ) )
				{
					$list[]	= $line;
					$line	= trim( array_shift( $lines ) );
					$this->lineNumber ++;
				}
				array_unshift( $lines, $line );
				$this->lineNumber --;
				if( $list )
				{
					$this->openBlocks[]	= $this->parseDocBlock( $list );
					if( !$fileBlock && !$class )
					{
						$fileBlock	= array_shift( $this->openBlocks );
						array_unshift( $this->openBlocks, $fileBlock );
						$this->decorateCodeDataWithDocData( $file, $fileBlock );
					}
				}
				continue;
			}
			if( !$openClass )
			{
				if( preg_match( $this->regexClass, $line, $matches ) )
				{
					if( trim( array_pop( array_slice( $matches, -1 ) ) ) == "{" )
					{
						array_pop( $matches );
						$level++;
					}
					while( !trim( array_pop( array_slice( $matches, -1 ) ) ) )
						array_pop( $matches );
					$class	= $this->parseClassOrInterface( $file, $matches );
					$openClass	= TRUE;
				}
				else if( preg_match( $this->regexMethod, $line, $matches ) )
				{
						$openClass	= FALSE;
					$function	= $this->parseFunction( $file, $matches );
					if( isset( $matches[8] ) )
						$level++;
					$file->setFunction( $function );
				}
			}
			else
			{
				if( $level == 0 )
					$openClass	= FALSE;
				if( preg_match( $this->regexClass, $line, $matches ) )
				{
					if( $class instanceof ADT_PHP_Class )
						$file->addClass( $class );
					else if( $class instanceof ADT_PHP_Interface )
						$file->addInterface( $class );
					array_unshift( $lines, $line );
					$this->lineNumber --;
					$openClass	= FALSE;
					$level		= 1;
					continue;
				}
				if( preg_match( $this->regexMethod, $line, $matches ) )
				{
					$method		= $this->parseMethod( $class, $matches );
					$function	= $matches[6];
					$class->setMethod( $method );
					if( isset( $matches[8] ) )
						$level++;
				}
				else if( $level <= 1 )
				{
					if( preg_match( $this->regexDocVariable, $line, $matches ) )
					{
						if( $openClass && $class )
							$this->varBlocks[$class->getName()."::".$matches[2]]	= $this->parseDocMember( $matches );
						else
							$this->varBlocks[$matches[2]]	= $this->parseDocVariable( $matches );
					}
					else if( preg_match( $this->regexVariable, $line, $matches ) )
					{
						$name		= $matches[3];
						if( $openClass && $class )
						{
							$key		= $class->getName()."::".$name;
							$varBlock	= isset( $this->varBlocks[$key] ) ? $this->varBlocks[$key] : NULL;
							$variable	= $this->parseMember( $class, $matches, $varBlock ); 
							$class->setMember( $variable );
						}
						else
						{
							remark( "Parser Error: found var after class -> not handled yet" );
/*							$key		= $name;
							$varBlock	= isset( $this->varBlocks[$key] ) ? $this->varBlocks[$key] : NULL;
							$variable	= $this->parseMember( $matches, $varBlock );*/
						}
					}
				}
				else if( $level > 1 && $function )
				{
					$functionBody[$function][]	= $line;
				}
			}
		}
		while( $lines );

		$file->setSourceCode( $content );
		if( $class )
		{
			foreach( $class->getMethods() as $methodName => $method )
				if( isset( $functionBody[$methodName] ) )
					$method->setSourceCode( $functionBody[$methodName] );
			if( $class instanceof ADT_PHP_Class )
				$file->addClass( $class );
			else if( $class instanceof ADT_PHP_Interface )
				$file->addInterface( $class );
		}
		return $file;
	}

	/**
	 *	Parses a Function Signature and returns collected Information.
	 *	@access		protected
	 *	@param		ADT_PHP_File		$parent			Parent File Data Object
	 *	@param		array				$matches		Matches of RegEx
	 *	@return		ADT_PHP_Function
	 */
	protected function parseFunction( ADT_PHP_File $parent, $matches )
	{
		$function	= new ADT_PHP_Function( $matches[6] );
		$function->setParent( $parent );
		$function->setLine( $this->lineNumber );

		if( trim( $matches[7] ) )
		{
			$paramList	= array();
			foreach( explode( ",", $matches[7] ) as $param )
			{
				$param	 = trim( $param );
				if( !preg_match( $this->regexParam, $param, $matches ) )
					continue;
				$function->setParameter( $this->parseParameter( $function, $matches ) );
			}
		}
		if( $this->openBlocks )
		{
			$methodBlock	= array_pop( $this->openBlocks );
			$this->decorateCodeDataWithDocData( $function, $methodBlock );
			$this->openBlocks	= array();
		}
		return $function;
	}

	/**
	 *	Parses a Class Member Signature and returns collected Information.
	 *	@access		protected
	 *	@param		ADT_PHP_Class		$parent			Parent Class Data Object
	 *	@param		array				$matches		Matches of RegEx
	 *	@param		array				$docBlock		Variable data object from Doc Parser
	 *	@return		ADT_PHP_Member
	 */
	protected function parseMember( $parent, $matches, $docBlock = NULL )
	{
		$variable			= new ADT_PHP_Member( $matches[3], NULL, NULL );
		$variable->setParent( $parent );
		$variable->setLine( $this->lineNumber );
		if( isset( $matches[4] ) )
			$variable->setDefault( preg_replace( "@;$@", "", $matches[5] ) );
		if( !empty( $matches[1] ) )
			$variable->setAccess( $matches[1] == "var" ? NULL : $matches[1] );
		$variable->setStatic( (bool) trim( $matches[2] ) );

		if( $docBlock )
			if( $docBlock instanceof ADT_PHP_Variable )
				if( $docBlock->getName() == $variable->getName() )
					$variable->merge( $docBlock );
		return $variable;
	}

	/**
	 *	Parses a Method Signature and returns collected Information.
	 *	@access		protected
	 *	@param		ADT_PHP_Interface	$parent			Parent Class Data Object
	 *	@param		array				$matches		Matches of RegEx
	 *	@return		ADT_PHP_Method
	 */
	protected function parseMethod( ADT_PHP_Interface $parent, $matches )
	{
		$method	= new ADT_PHP_Method( $matches[6] );
		$method->setParent( $parent );
		$method->setLine( $this->lineNumber );
		if( !empty( $matches[3] ) )
			$method->setAccess( trim( $matches[4] ) );
		$method->setAbstract( (bool) $matches[1] );
		$method->setFinal( (bool) $matches[3] );
		$method->setStatic( (bool) $matches[5] );

		$return		= new ADT_PHP_Return( "unknown" );
		$return->setParent( $method );
		$method->setReturn( $return );

		if( trim( $matches[7] ) )
		{
			$paramList	= array();
			foreach( explode( ",", $matches[7] ) as $param )
			{
				$param	 = trim( $param );
				if( !preg_match( $this->regexParam, $param, $matches ) )
					continue;
				$method->setParameter( $this->parseParameter( $method, $matches ) );
			}
		}
		if( $this->openBlocks )
		{
			$methodBlock	= array_pop( $this->openBlocks );
			$this->decorateCodeDataWithDocData( $method, $methodBlock );
			$this->openBlocks	= array();
		}
#		if( !$method->getAccess() )
#			$method->setAccess( 'public' );
		return $method;
	}

	/**
	 *	Parses a Function/Method Signature Parameters and returns collected Information.
	 *	@access		protected
	 *	@param		ADT_PHP_Function	$parent			Parent Function or Method Data Object
	 *	@param		array				$matches		Matches of RegEx
	 *	@return		ADT_PHP_Parameter
	 */
	protected function parseParameter( ADT_PHP_Function $parent, $matches )
	{
		$parameter	= new ADT_PHP_Parameter( $matches[5] );
		$parameter->setParent( $parent );
		$parameter->setLine( $this->lineNumber );
		$parameter->setCast( $matches[2] );
		$parameter->setReference( (bool) $matches[4] );

		if( isset( $matches[6] ) )
			$parameter->setDefault( $matches[7] );
		return $parameter;
	}
}
?>
