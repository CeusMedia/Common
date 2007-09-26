<?php
import( 'de.ceus-media.adt.list.LinkElement' );
import( 'de.ceus-media.adt.list.IndexList' );
/**
 *	Simple linked List.
 *	@package		adt.list
 *	@uses			LinkElement
 *	@uses			IndexList
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.1
 */
/**
 *	Simple linked List.
 *	@package		adt.list
 *	@uses			LinkElement
 *	@uses			IndexList
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.1
 */
class LinkList
{
	/**	@var		mixed	$current	internal Pointer to the current Element in the List */
	var $current;
	/**	@var		mixed	$first		internal Pointer to the first Element in the List */
	var $first;
	/**	@var		int		$size		internal Counter of Elements in the List */
	var $size = 0;
	
	/**
	 *	Adds a primitive data type or Object to the List.
	 *	@access		public
	 *	@param		mixed	$entry		Primitive data type  or Object	
	 */
	function addEntry( $entry )
	{
		if( $this->getSize() )
		{
			$new =& new LinkElement( $entry );
			if( $this->current->hasLink() )
			{
				$next =& $this->current->getLink();
				$new->setLink( $next );
			}
			$this->current->setLink( $new );
			$this->current =& $new;
		}
		else
		{
			$this->first	= new LinkElement( $entry );
			$this->current	= $this->first;
		}
		$this->size ++;
	}
	
	/**
	 *	Adds an entire Array to the List.
	 *	@access		public
	 *	@param		array	$list		non-associative Array of entries of all types (Primitives or Objects)
	 *	@return		void
	 */
	function addArray( $array )
	{
		foreach( $array as $entry )
		{
			$this->addEntry( $entry );
		}
	}

	/**
	 *	Sets internal Pointer to the first Element in the List.
	 *	@access		public
	 *	@return		void
	 */
	function reset()
	{
		$this->current =& $this->first;
	}	

	/**
	 *	Proves existence of a next Element in the List.
	 *	@access		public
	 *	@return		bool
	 */
	function hasNext()
	{
		return  $this->current;
		
	}
	
	/**
	 *	Returns the Reference to the next Element.
	 *	@access		public
	 *	@return		mixed
	 */
	function & getNext()
	{
		$return	= $this->current;
		$this->current = $this->current->getLink();
		return $return;
	}
	
	/*	
	 *	Returns the Size of the List.
	 *	@access		public
	 *	@return		int
	 */
	function getSize()
	{
		return $this->size;
	}

	/**
	 *	Returns LinkList as primitive Array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray()
	{
		$a = array();
		$current =& $this->first;
		while( $current->hasLink() )
		{
			$a[] = $current->getContent();
			$current =& $current->getLink();
		}
		if( $this->getSize() )
			$a[] = $current->getContent();
		return $a;
	}

	/**
	 *	Returns LinkList as IndexList.
	 *	@access		public
	 *	@return		IndexList
	 */
	function toIndexList()
	{
		$list = new IndexList();
		$current =& $this->first;
		while( $current->hasLink() )
		{
			$list->addEntry( $current->getContent() );
			$current =& $current->getLink();
		}
		if( $this->getSize() )
			$list->addEntry( $current->getContent() );
		return $list;
	}

}
?>