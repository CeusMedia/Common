<?php
import( 'de.ceus-media.adt.ChainElement' );
import( 'de.ceus-media.adt.list.LinkList' );
/**
 *	Double linked List.
 *	@package		adt
 *	@subpackage		list
 *	@extends		LinkList
 *	@uses			ChainElement
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.1
 */
/**
 *	Double linked List.
 *	@package		adt
 *	@subpackage		list
 *	@extends		LinkList
 *	@uses			ChainElement
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.1
 */
class ChainList extends LinkList
{
	/**	@var		ChainElement	$_last	Last Element in this Chain */
	var $_last;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}

	/**
	 *	Adds a primitive data type or Object to the Chain.
	 *	@access		public
	 *	@param		mixed		$entry	Primitive data type  or Object	
	 *	@return		void
	 */
	function addEntry( $entry )
	{
		if( $this->getSize() )
		{
			$new =& new ChainElement( $entry );
			if( $this->_current->hasLink() )
			{
				$next =& $this->_current->getLink();
				$next->setOrigin( $new );
				$new->setLink( $next );
			}
			$this->_current->setLink( $new );
			$new->setOrigin( $this->_current );
			$this->_current =& $new;
		}
		else
		{
			$this->_first = new ChainElement( $entry );
			$this->_current =& $this->_first;
		}
		$this->_size ++;
	}
	
	/**
	 *	Adds an entire Array to the Chain.
	 *	@access		public
	 *	@param		array	$list		non-associative Array of entries of all types( Primitives or Objects)
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
	 *	Proves existence of a previous Element in the Chain.
	 *	@access		public
	 *	@return		bool
	 */
	function hasPrevious()
	{
		return  $this->_current->hasOrigin();
	}
	
	/**
	 *	Returns the Reference to the previous Element.
	 *	@access		public
	 *	@return		mixed
	 */
	function & getPrevious()
	{
		$this->_current =& $this->_current->getOrigin();
		return $this->_current;
	}
	
	/**
	 *	Steps up in the Chain and returns the achieved Element.
	 *	@access		public
	 *	@param		int		$steps		Amount of steps to step up in chain
	 *	@return		mixed
	 */
	function stepUp( $steps = 1 )
	{
		$current =& $this->_current;
		for( $i=0; $i<$steps; $i++ )
		{
			if( $this->hasPrevious() )
			{
				$element =& $this->getPrevious();
			}
			else break;
		}
		if( $current != $element ) return $element;
		return false;
	}	

	/**
	 *	Steps down in the Chain and returns the achieved Element.
	 *	@access		public
	 *	@param		int		$steps		Amount of steps to step down in chain
	 *	@return		mixed
	 */
	function stepDown( $steps = 1 )
	{
		$current =& $this->_current;
		for( $i=0; $i<$steps; $i++ )
		{
			if( $this->hasNext() )
			{
				$element =& $this->getNext();
			}
			else break;
		}
		if( $current != $element ) return $element;
		return false;
	}
	
	/**
	 *	Returns ChainList as LinkList.
	 *	@access		public
	 *	@return		LinkList
	 */
	function toLinkList()
	{
		$ll = new LinkList();
		$current =& $this->_first;
		while( $current->hasLink() )
		{
			$ll->addEntry( $current->getContent() );
			$current =& $current->getLink();
		}
		if( $this->getSize() )
			$ll->addEntry( $current->getContent() );
		return $ll;
	}
}
?>