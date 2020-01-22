<?php
/**
 *	...
 *
 *	Copyright (c) 2010-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_PHP_Parser_Doc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */
/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_PHP_Parser_Doc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.6.8
 */
class FS_File_PHP_Parser_Doc_Decorator
{
	/**
	 *	Appends all collected Documentation Information to already collected Code Information.
	 *	In general, found doc parser data are added to the php parser data.
	 *	Found doc data can contain strings, objects and lists of strings or objects.
	 *	Since parameters are defined in signature and doc block, they need to be merged.
	 *	Parameters are given with an associatove list indexed by parameter name.
	 *
	 *	@access		protected
	 *	@param		object		$codeData		Data collected by parsing Code
	 *	@param		string		$docData		Data collected by parsing Documentation
	 *	@return		void
	 *	@todo		fix merge problem -> seems to be fixed (what was the problem again?)
	 */
	public function decorateCodeDataWithDocData( &$codeData, $docData )
	{
		foreach( $docData as $key => $value )
		{
			if( !$value )
				continue;

			//  value is an object
			if( is_object( $value ) )
			{
				if( $codeData instanceof ADT_PHP_Function )
				{
					switch( $key )
					{
						case 'return':	$codeData->setReturn( $value ); break;
					}
				}
			}
			//  value is a simple string
			else if( is_string( $value ) )
			{
				switch( $key )
				{
					//  extend category
					case 'category':	$codeData->setCategory( $value ); break;
					//  extend package
					case 'package':		$codeData->setPackage( $value ); break;
					//  extend version
					case 'version':		$codeData->setVersion( $value ); break;
					//  extend since
					case 'since':		$codeData->setSince( $value ); break;
					//  extend description
					case 'description':	$codeData->setDescription( $value ); break;
					//  extend todos
					case 'todo':		$codeData->setTodo( $itemValue ); break;
				}
				if( $codeData instanceof ADT_PHP_Interface )
				{
					switch( $key )
					{
						case 'access':
							//  only if no access type given by signature
							if( !$codeData->getAccess() )
								//  extend access type
								$codeData->setAccess( $value );
							break;								
						//  extend extends
						case 'extends':		$codeData->setExtendedClassName( $value ); break;
					}
				}
				if( $codeData instanceof ADT_PHP_Method )
				{
					switch( $key )
					{
						case 'access':
							//  only if no access type given by signature
							if( !$codeData->getAccess() )
								//  extend access type
								$codeData->setAccess( $value );
							break;
					}
				}
			}
			//  value is a list of objects or strings
			else if( is_array( $value ) )
			{
				//  iterate list
				foreach( $value as $itemKey => $itemValue )
				{
					//  special case: value is associative array -> a parameter to merge
					if( is_string( $itemKey ) )
					{
						switch( $key )
						{
							case 'param':
								foreach( $codeData->getParameters() as $parameter )
									if( $parameter->getName() == $itemKey )
										$parameter->merge( $itemValue );
								break;
						}
					}
					//  value is normal list of objects or strings
					else
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
							}
						}
					}
				}
			}
		}
	}
}
