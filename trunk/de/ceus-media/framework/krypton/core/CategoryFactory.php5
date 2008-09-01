<?php
/**
 *	Factory to produce Class Names for Types.
 *	@package		framework.krypton.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.03.2007
 *	@version		0.1
 */
/**
 *	Factory to produce Class Names for Types.
 *	@package		framework.krypton.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.03.2007
 *	@version		0.1
 */
class Framework_Krypton_Core_CategoryFactory
{
	/**	@var	string		$default	Default Type Selection */
	protected $default	= "";
	/**	@var	string		$type		Selected Type */
	protected $type		= "";
	/**	@var	string		$types		Available Types */
	protected $types	= array();
	
	/**
	 *	Returns typed Class Name.
	 *	@access		public
	 *	@param		string		$className		Class Name to type
	 *	@param		string		$prefix			Class Prefix (view,action,...)
	 *	@param		string		$category		Category to force
	 *	@return		string
	 */
	public function getClassName( $className, $prefix = "", $category = "" )
	{
		$type	= $category ? $category : $this->getType();
		$name	= $prefix." Category ".$type." ".$className;
		$name	= ucWords( trim( $name ) );
		$name	= str_replace( " ", "_", $name );
		return $name;
	}

	
	/**
	 *	Returns typed Class File Name.
	 *	@access		public
	 *	@param		string		$className		Class Name to type
	 *	@param		string		$prefix			Class Prefix (view,action,...)
	 *	@param		string		$category		Category to force
	 *	@return		string
	 */
	public function getClassFileName( $className, $prefix = "", $category = "" )
	{
		$type		= $category ? $category : $this->getType();
		$className	= ucFirst( $className );
		$className	= strtolower( $type ).".".$className;
		if( $prefix )
			$className	= strtolower( $prefix ).".category.".$className;
		return $className;
	}
	
	/**
	 *	Imports and returns Object.
	 *	@access		public
	 *	@param		string		$className		Class Name to type
	 *	@param		string		$prefix			Class Prefix (view,action,...)
	 *	@param		string		$category		Category to force
	 *	@return		object
	 */
	public function getObject( $className, $prefix = "", $category = "" )
	{
		$fileName	= "classes.".$this->getClassFileName( $className, $prefix, $category );
		$className	= $this->getClassName( $className, $prefix, $category );
		if( !class_exists( $className ) )
			import( $fileName );
		return new $className();
	}
	
	/**
	 *	Returns selected Typed or default Type if not Type is selected.
	 *	@access		public
	 *	@return		string
	 *	@throws		Exception
	 */
	public function getType()
	{
		if( $this->type )
			return $this->type;
		if( $this->default )
			return $this->default;
		if( $this->types )
			return $this->types[0];
		throw new Exception( 'No Types set.' );
	}


	/**
	 *	Returns List of set Types.
	 *	@access		public
	 *	@return		array
	 */
	public function getTypes()
	{
		return $this->types;
	}
	
	/**
	 *	Sets default Type.
	 *	@access		public
	 *	@param		string		$types		Default Type
	 *	@return		void
	 */
	public function setDefault( $type )
	{
		$type	= trim( $type );
		if( !in_array( $type, $this->types ) )
			throw new InvalidArgumentException( "Type '".$type."' is not available." );
		$this->default	= $type;
	}
	
	/**
	 *	Set selected Type.
	 *	@access		public
	 *	@param		string		$type		Type to select
	 *	@return		void
	 */
	public function setType( $type )
	{
		$type	= trim( $type );
		if( $type )
		{
			if( !in_array( $type, $this->types ) )
				throw new InvalidArgumentException( 'Type "'.$type.'" is not available.' );
			$this->type	= $type;
		}
	}
	
	/**
	 *	Sets available Types.
	 *	@access		public
	 *	@param		array		$types		Available Types
	 *	@return		void
	 */
	public function setTypes( $types )
	{
		$this->types	= array();
		foreach( $types as $type )
			$this->types[]	= trim( $type );
	}
}
?>