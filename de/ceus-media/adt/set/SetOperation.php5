<?php
import( 'de.ceus-media.adt.set.Set' );
/**
 *	Base Operations for Sets.
 *	Call all function with two or more Set Objects as parameters.
 *	@package		adt.set
 *	@uses			Set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Base Operations for Sets.
 *	Call all function with two or more Set Objects as parameters.
 *	@package		adt.set
 *	@uses			Set
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class SetOperation
{
	/**
	 *	Calculates Intersection of two or more Sets.
	 *	@access		public
	 *	@return		void
	 */
	function intersect()
	{
		$args = func_get_args();
		if( is_array( $args[0] ) && !$args[1] )
			$args = $args[0];
		if( count( $args ) < 2 )
			throw new IOException( "Intersection of Sets needs at least two Sets" );
		$carrier	= new Set();
		$args		= func_get_args();
		$left		= array_shift( $args );
		if( $left && is_object( $left ) )
		{
			foreach( $args as $right )
			{
				if( $right && is_object( $right ) )
				{
					$right->rewind();
					while( $right->hasNext() )
					{
						$next = $right->getNext();
						if( $left->inSet( $next ) )
							$carrier->add( $next );
					}				
				}
			}
		}
		return $carrier;
	}
	
	/**
	 *	Calculates symmetric Difference of two or more Sets.
	 *	@return		Set
	 */
	function differentiateSymmetric()
	{
		$args = func_get_args();
		if( is_array ( $args[0] ) && !$args[1] )
			$args = $args[0];
		$left	= $this->differentiate( $args );
		$right	= $this->differentiate( array_reverse( $args ) );
		$diff	= $this->unite( $left, $right );
		return $diff;
	}

	/**
	 *	Calculates Difference of two or more Sets.
	 *	@return		Set
	 */
	function differentiate()
	{
		$args = func_get_args();
		if( is_array( $args[0] ) && !$args[1] )
			$args = $args[0];
		if( count( $args ) < 2 )
			throw new IOException( "Difference of Sets needs at least two Sets" );
		$left = array_shift( $args );
		if( $left && is_object( $left ) )
		{
			foreach( $args as $right )
			{
				$carrier = new Set();
				if( $right && is_object( $right ) )
				{
					$left->rewind();	
					while( $left->hasNext() )
					{
						$next = $left->getNext();
						if( !$right->inSet( $next ) )
							$carrier->add( $next );
					}
				}
				$left = $carrier;
			}
		}
		return $left;
	}

	/**
	 *	Calculates the Union of two or more Sets.
	 *	@access		public
	 *	@return		void
 	 */
	function unite()
	{
		$args = func_get_args();
		if( is_array( $args[0] ) && !$args[1] )
			$args = $args[0];
		if( count( $args ) < 2 )
			throw new IOException( "Union of Sets needs at least two Sets" );
		$args = func_get_args();
		$left = array_shift( $args );
		if( $left && is_object( $left ) )
		{
			foreach( $args as $right )
			{
				if( $right && is_object( $right ) )
				{
					$right->rewind();	
					while( $right->hasNext() )
						$left->add( $right->getNext() );
				}
			}
		}
		return $left;
	}

	/**
	 *	Calculates Cross Product of two or more Sets.
	 *	@access		public
	 *	@return		void
 	 */
	function produceCross()
	{
		if( func_num_args() < 2 )
			throw new IOException( "Cross Product of Sets needs at least two Sets" );
		$args = func_get_args();   
		$left = array_shift( $args );
		if( $left && is_object( $left ) )
		{
			foreach( $args as $right )
			{
				if( $right && is_object( $right ) )
				{
					$carrier = new Set();
					$left->rewind();
					while( $left->hasNext() )
					{
						$x = $left->getNext();
						$right->rewind();
						while( $right->hasNext() )
							$carrier->add( $x.$right->getNext() );
					}
					$left = $carrier;
				}
			}
		}
		return $left;
	}
}
?>