<?php
class File_PHP_Parser_Doc_Regular
{
	protected $regexDocParam	= '@^\*\s+\@param\s+(([\S]+)\s+)?(\$?([\S]+))\s*(.+)?$@';
	protected $regexDocVariable	= '@^/\*\*\s+\@var\s+(\w+)\s+\$(\w+)(\s(.+))?\*\/$@s';				//  not used

	/**
	 *	Parses a Doc Block and returns Array of collected Information.
	 *	@access		protected
	 *	@param		array		$lines			Lines of Doc Block
	 *	@return		array
	 */
	public function parseDocBlock( $docComment )
	{
		$lines		= explode( "\n", $docComment );
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
}
