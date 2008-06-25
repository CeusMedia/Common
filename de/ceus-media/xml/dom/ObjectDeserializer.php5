<?php
import( 'de.ceus-media.xml.dom.Parser' );
/**
 *	Deserializer for XML into a Data Object.
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.6
 */
/**
 *	Deserializer for XML into a Data Object.
 *	@package		xml.dom
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.6
 */
class XML_DOM_ObjectDeserializer
{
	/**
	 *	Builds Object from XML of a serialized Object.
	 *	@access		public
	 *	@param		string		$xml			XML String of a serialized Object
	 *	@return		mixed
	 */
	public function deserialize( $xml, $strict = true )
	{
		$parser	= new XML_DOM_Parser();
		$tree	= $parser->parse( $xml );
		$class	= $tree->getAttribute( 'class' );
		if( !class_exists( $class ) )
			throw new Exception( 'Class "'.$class.'" has not been loaded, yet.' );
		$object	= new $class();
		$this->deserializeVarsRec( $tree->getChildren(), $object );
		return $object;
	}
	
	/**
	 *	Adds nested Vars to an Element by their Type while supporting nested Arrays.
	 *	@access		protected
	 *	@param		array		$children		Array of Vars to add
	 *	@param		mixed		$element		current Position in Object
	 *	@return		string
	 */
	protected function deserializeVarsRec( $children, &$element )
	{
		foreach( $children as $child )
		{
			$name		= $child->getAttribute( 'name' );
			$vartype	= $child->getNodeName();
			if( is_object( $element ) )
			{
				if( !isset( $element->$name ) )
					$element->$name	= NULL;
				$pointer	=& $element->$name;
			}
			else
			{
				if( !isset( $element->$name ) )
					$element[$name]	= NULL;
				$pointer	=& $element[$name];
			}
			
			switch( $vartype )
			{
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
				case 'array':
					$pointer	= array();
					$this->deserializeVarsRec( $child->getChildren(), $pointer );
					break;
				case 'object':
					$class		= $child->getAttribute( 'class' );
					$pointer	= new $class();
					$this->deserializeVarsRec( $child->getChildren(), $pointer );
					break;
				default:
					$pointer	= NULL;
					break;
			}
		}
	}
}
?>