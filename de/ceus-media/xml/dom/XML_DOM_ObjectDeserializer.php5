<?php
import( 'de.ceus-media.xml.dom.XML_DOM_Parser' );
/**
 *	Deserializer for XML into a Data Object.
 *	@package		xml
 *	@subpackage		dom
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
/**
 *	Deserializer for XML into a Data Object.
 *	@package		xml
 *	@subpackage		dom
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
class XML_DOM_ObjectDeserializer
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Builds Object from XML of a serialized Object.
	 *	@access		public
	 *	@param		string		xml		XML String of a serialized Object
	 *	@return		mixed
	 */
	function deserialize( $xml )
	{
		$xp		= new XML_DOM_Parser( $xml );
		$tree	= $xp->parse();
		$class	= $tree->getAttribute( 'class' );
		$object	= new $class();
		$this->_deserializeVarsRec( $tree->getChildren(), $object );
		return $object;
	}
	
	/**
	 *	Adds nested Vars to an Element by their Type while supporting nested Arrays.
	 *	@access		private
	 *	@param		array		children		Array of Vars to add
	 *	@param		mixed		element		current Position in Object
	 *	@param		bool			first_level	Flag: Member Var or Array Var
	 *	@return		string
	 */
	function _deserializeVarsRec( $children, &$element, $first_level = true )
	{
		foreach( $children as $child )
		{
			$name	= $child->getAttribute( 'name' );
			$vartype	= $child->getNodeName();
			if( $first_level )
				$pointer	=& $element->$name;
			else
				$pointer	=& $element[$name];
			
			switch( $vartype )
			{
				case 'NULL':
					$pointer	= NULL;
					break;
				case 'boolean':
					$pointer	= (bool) $child->getContent();
					break;
				case 'string':
					$pointer	= utf8_decode( $child->getContent() );
					break;
				case 'integer':
					$pointer	= (int) $child->getContent();
					break;
				case 'double':
					$pointer	= (double) $child->getContent();
					break;
				case 'float':
					$pointer	= (float) $child->getContent();
					break;
				case 'array':
					$pointer	= array();
					$this->_deserializeVarsRec( $child->getChildren(), $pointer, false );
					break;
			}
		}
	}
}
?>