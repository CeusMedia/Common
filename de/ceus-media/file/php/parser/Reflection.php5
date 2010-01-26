<?php
class File_PHP_Parser_Reflection
{
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
		if( !Alg_StringUnicoder::isUnicode( $content ) )
			$content		= Alg_StringUnicoder::convertToUnicode( $content );

		$listClasses	= get_declared_classes();													//  list builtin Classes
		$listInterfaces	= get_declared_interfaces();												//  list builtin Interfaces
		require_once( $fileName );
		$listClasses	= array_diff( get_declared_classes(), $listClasses );						//  get only own Classes
		$listInterfaces	= array_diff( get_declared_interfaces(), $listInterfaces );					//  get only own Interfaces

		$file			= new ADT_PHP_File;
		$file->setBasename( basename( $fileName ) );
		$file->setPathname( substr( str_replace( "\\", "/", $fileName ), strlen( $innerPath ) ) );
		$file->setUri( str_replace( "\\", "/", $fileName ) );
		$file->setSourceCode( $content );

		//  --  READING CLASSES  --  //
		$countClasses	= count( $listClasses );													//  count own Classes
		if( $countClasses )
		{
			if( $this->verbose )
				remark( 'Parsing Classes ('.$countClasses.'):'.PHP_EOL );
			$listClasses	= $this->readFromClassList( $listClasses );
			$this->application->updateStatus( 'Done.', $countClasses, $countClasses );
		}
		foreach( $listClasses as $class )
			if( $class instanceof ADT_PHP_Class )
				$file->addClass( $class );

		//  --  READING INTERFACES  --  //
		$countInterfaces	= count( $listInterfaces );												//  count own Interfaces
		if( $countInterfaces )
		{
			if( $this->verbose )
				remark( 'Parsing Interfaces ('.$countInterfaces.'):'.PHP_EOL );
			$listInterfaces	= $this->readFromClassList( $listInterfaces );
			$this->application->updateStatus( 'Done.', $countInterfaces, $countInterfaces );
		}
		foreach( $listInterfaces as $interface )
			if( $interface instanceof ADT_PHP_Interface )
				$file->addInterface( $interface );

/*		$functionBody	= array();
		$lines			= explode( "\n", $content );
		$fileBlock		= NULL;
		$openClass		= FALSE;
		$function		= NULL;
	
		$level	= 0;
		$class	= NULL;
		if( $class )
		{
			foreach( $class->getMethods() as $methodName => $method )
				if( isset( $functionBody[$methodName] ) )
					$method->setSourceCode( $functionBody[$methodName] );
		}*/
		return $file;
	}


	public function readClass( ReflectionClass $class )
	{

		if( $class->isInterface() )
		{
			$object	= new ADT_PHP_Interface( $class->name );
			if( $class->getParentClass() )															//  NOT WORKING !!!
				$object->setExtendedInterfaceName( $class->getParentClass()->name );
		}
		else
		{
			$object	= new ADT_PHP_Class( $class->name );
			if( $class->getParentClass() )
				$object->setExtendedClassName( $class->getParentClass()->name );
			foreach( $class->getInterfaceNames() as $interfaceName )
				$object->setImplementedInterfaceName( $interfaceName );
			$object->setAbstract( $class->isAbstract() );

			foreach( $class->getProperties() as $property )
				$object->setMember( $this->readProperty( $property ) );
		}
		$object->setDescription( $class->getDocComment() );
		$object->setFinal( $class->isFinal() );
		$object->setLine( $class->getStartLine().'-'.$class->getEndLine() );
	
		foreach( $class->getMethods() as $method )
			$object->setMethod( $this->readMethod( $method ) );
			
			
		$parser		= new File_PHP_Parser_Doc_Regular;
		$docData	= $parser->parseDocBlock( $class->getDocComment() );
		$decorator	= new File_PHP_Parser_DocDecorator();
		$decorator->decorateCodeDataWithDocData( $object, $docData );
		return $object;
	}

	public function readProperty( ReflectionProperty $property )
	{
		$object	= new ADT_PHP_Member( $property->name );
		return $object;
	}

	public function readMethod( ReflectionMethod $method )
	{
		$object	= new ADT_PHP_Method( $method->name );
		$object->setDescription( $method->getDocComment() );
		foreach( $method->getParameters() as $parameter )
		{
			$parameter	= $this->readParameter( $parameter );
			$object->setParameter( $parameter );
		}
		$object->setLine( $method->getStartLine().'-'.$method->getEndLine() );
		$parser		= new Parser_DocComment;
		$docData	= $parser->parseDocBlock( $method->getDocComment() );
		$decorator	= new Parser_DocCommentDecorator();
		$decorator->decorateCodeDataWithDocData( $object, $docData );
		return $object;
	}

	public function readParameter( ReflectionParameter $parameter )
	{
		$object	= new ADT_PHP_Parameter( $parameter->name );
		$object->setReference( $parameter->isPassedByReference() );
		if( $parameter->getClass() )
			$object->setCast( $parameter->getClass()->name );
		if( $parameter->isDefaultValueAvailable() )
			$object->setDefault( $parameter->getDefaultValue() );
		return $object;
	}
}
?>