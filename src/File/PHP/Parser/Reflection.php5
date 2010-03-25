<?php
/**
 *	...
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		file.php.parser
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class File_PHP_Parser_Reflection
{
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