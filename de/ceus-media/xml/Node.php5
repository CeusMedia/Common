<?php
class XML_Node extends SimpleXMLElement
{
    
	/**
	 *	Returns count of Attributes.
	 *	@access		public
	 *	@return		int
	 */
    public function countAttributes()
    {
    	return count( $this->getAttributeKeys() );
    }
   
	/**
	 *	Returns count of Children.
	 *	@access		public
	 *	@return		int
	 */
    public function countChildren()
    {
        $i = 0;
        foreach( $this->children() as $node )
        	$i++;
        return $i;
    }
   
	/**
	 *	Returns the Value of an Attribute from its' Key.
	 *	@access		public
	 *	@param		string		$key		Key of Attribute
	 *	@return		bool
	 */
    public function getAttribute( $key )
	{
		$data	= $this->attributes();
		if( !isset( $data[$key] ) )
			throw new Exception( 'Attribute "'.$key.'" is not set.' );
		return (string) $data[$key];
    }
   
	/**
	 *	Returns List of Attribute Keys.
	 *	@access		public
	 *	@return		array
	 */
    public function getAttributeKeys()
	{
    	$list	= array();
        foreach( $this->attributes() as $key => $value )
            $list[] = $key;
        return $list;
    }

	/**
	 *	Indicates whether an Attribute is existing by it's Key.
	 *	@access		public
	 *	@param		string		$key		Key of Attribute
	 *	@return		bool
	 */
	public function hasAttribute( $key )
	{
		$keys	= $this->getAttributeKeys();
		return in_array( $key, $keys );
	}
      
	/**
	 *	Returns Array of Attributes.
	 *	@access		public
	 *	@return		array
	 */
    public function getAttributes()
    {
    	$list	= array();
    	foreach( $this->attributes() as $key => $value )
    		$list[$key]	= (string) $value;
    	return $list;
    }
    
	/**
	 *	Writes current XML Node as XML File.
	 *	@access		public
	 *	@param		string		$fileName		File Name for XML File
	 *	@return		int
	 */
    public function asFile( $fileName )
    {
    	import( 'de.ceus-media.file.Writer' );
    	$xml	= $this->asXml();
    	return File_Writer::save( $fileName, $xml );
    }
}
?>