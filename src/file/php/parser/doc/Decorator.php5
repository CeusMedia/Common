<?php
class File_PHP_Parser_Doc_Decorator
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

			if( is_object( $value ) )															//  value is an object
			{
				if( $codeData instanceof ADT_PHP_Function )
				{
					switch( $key )
					{
						case 'return':	$codeData->setReturn( $value ); break;
					}
				}
			}
			else if( is_string( $value ) )																//  value is a simple string
			{
				switch( $key )
				{
					case 'category':	$codeData->setCategory( $value ); break;					//  extend category
					case 'package':		$codeData->setPackage( $value ); break;						//  extend package
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
									if( $parameter->getName() == $itemKey )
										$parameter->merge( $itemValue );
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
							}
						}
					}
				}
			}
		}
	}
}
?>