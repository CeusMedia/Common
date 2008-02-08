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
		$className	= $type."_".$className;
		if( $prefix )
			$className	= ucFirst( $prefix )."_".$className;
		return $className;
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
		return $this->default;
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
			throw new Exception( "Type '".$type."' is not in Factory." );
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
			{
				$types		= implode( ", ", $this->types );
				$message	= "Invalid Factory Type '".$type."' (available:".$types.".";
				throw new Exception( $message );
			}
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