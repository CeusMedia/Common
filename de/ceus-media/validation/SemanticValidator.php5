<?php
/**
 *	Semantic Validator.
 *	@package		validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Semantic Validator.
 *	@package		validation
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo			Code Documentation
 */
class SemanticValidator
{
	/**
	 *	Indicated wheter the parameter has a value.
	 *	@access		public
	 *	@param		string		$param		Parameter to be proved
	 *	@return		bool
	 */
	public function hasValue( $param )
	{
		return $param != "";
	}

	/**
	 *	Indicated wheter the parameter is larger than a limit.
	 *	@access		public
	 *	@param		string		$param		Parameter to be proved
	 *	@param		string		$limit		Parameter to be messed with
	 *	@return		bool
	 */
	public function isGreater( $param, $limit )
	{
		return $param > $limit;
	}

	/**
	 *	Indicated wheter the parameter is smaller than a limit.
	 *	@access		public
	 *	@param		string		$param		Parameter to be proved
	 *	@param		string		$limit		Parameter to be messed with
	 *	@return		bool
	 */
	public function isLess( $param, $limit )
	{
		return $param < $limit;
	}

	/**
	 *	Indicated wheter the parameter is time formated and is in past.
	 *	@access		public
	 *	@param		string		$param		Parameter to be proved
	 *	@return		bool
	 */
	public function isPast( $param )
	{
		if( !$param )
			return true;
		else if( ereg( "[0-9]{1,2}.[0-9]{1,2}.[0-9]{4}", $param ) )
		{
			$parts	= explode( ".", $param );
			$time	= $parts[1] / 365 + $parts[1] / 12 + $parts[2];
			$now	= date( "d" ) / 365 + date( "m" ) / 12 + date( "Y" );
			if ($time <= $now) return true;
		}
		else if( ereg( "[0-9]{1,2}.[0-9]{4}", $param ) )
		{
			$parts	= explode( ".", $param );
			$time	= $parts[0] / 12 + $parts[1];
			$now	= date( "m" ) / 12 + date( "Y" );
			if( $time <= $now )
				return true;
		}
		return false;
	}

	/**
	 *	Indicated wheter the parameter is time formated and is in future.
	 *	@access		public
	 *	@param		string		$param		Parameter to be proved
	 *	@return		bool
	 */
	public function isFuture( $param )
	{
		if( !$param )
			return true;
		else if( ereg( "[0-9]{1,2}.[0-9]{1,2}.[0-9]{4}", $param ) )
		{
			$parts	= explode( ".", $param );
			$time	= $parts[0] / 365 + $parts[1] / 12 + $parts[2];
			$now	= date( "d" ) / 365 + date( "m" ) / 12 + date( "Y" );
			if( $time >= $now )
				return true;
		}
		else if( ereg( "[0-9]{1,2}.[0-9]{4}", $param ) )
		{
			$parts	= explode( ".", $param );
			$time	= $parts[0] / 12 + $parts[1];
			$now	= date( "m" ) / 12 + date( "Y" );
			if( $time >= $now )
				return true;
		}
		return false;
	}

	/**
	 *	Indicated wheter the parameter is time formated and is after another point in time.
	 *	@access		public
	 *	@param		string		$param		Parameter to be proved
	 *	@param		string		$point		Time point to be after
	 *	@return		bool
	 */
	public function isAfter( $param, $point )
	{
		if( !$param )
			return true;
		else if( ereg( "[0-9]{1,2}.[0-9]{1,2}.[0-9]{4}", $point ) )
		{
			$parts	= explode( ".", $param );
			$time	= $parts[0] / 365 + $parts[1] / 12 + $parts[2];
			$parts	= explode( ".", $point );
			$point	= $parts[0] / 365 + $parts[1] / 12 + $parts[2];
			if( $time > $point )
				return true;
		}
		else if( ereg( "[0-9]{1,2}.[0-9]{4}", $point ) )
		{
			$parts	= explode( ".", $param );
			$time	= $parts[0] / 12 + $parts[1];
			$parts	= explode( ".", $point );
			$point	= $parts[0] / 12 + $parts[1];
			if( $time > $point )
				return true;
		}
		return false;
	}

	/**
	 *	Indicated wheter the parameter is time formated and is before another point in time.
	 *	@access		public
	 *	@param		string		$param		Parameter to be proved
	 *	@param		string		$point		Time point to be before
	 *	@return		bool
	 */
	public function isBefore( $param, $point )
	{
		if( !$param )
			return true;
		else if( ereg( "[0-9]{1,2}.[0-9]{1,2}.[0-9]{4}", $point ) )
		{
			$parts	= explode( ".", $param );
			$time	= $parts[0] / 365 + $parts[1] / 12 + $parts[2];
			$parts	= explode (".", $point);
			$point	= $parts[0] / 365 + $parts[1] / 12 + $parts[2];
			if( $time < $point )
				return true;
		}
		else if( ereg( "[0-9]{1,2}.[0-9]{4}", $point ) )
		{
			$parts	= explode( ".", $param );
			$time	= $parts[0] / 12 + $parts[1];
			$parts	= explode( ".", $point );
			$point	= $parts[0] / 12 + $parts[1];
			if( $time < $point )
				return true;
		}
		return false;
	}

	/**
	 *	Indicated wheter the parameter an valid URL.
	 *	@access		public
	 *	@param		string		$param		Parameter to be proved
	 *	@return		bool
	 */
	public function isURL( $param )
	{
		if( $param )
			return $this->isPreg( $param, "@^([a-z]+)://([a-z0-9-_\.]+)/?([\w$-\.+!*'\(\)\@:?#=&/;]+)$@si" );
		return true;
	}

	/**
	 *	Indicated wheter the parameter an valid eMail address.
	 *	@access		public
	 *	@param		string		$$param		Parameter to be proved
	 *	@return		bool
	 */
	public function isEmail( $param )
	{
		if( $param )
			return $this->isPreg( $param, "#^([a-z0-9äöü_.-]+)@([a-z0-9äöü_.-]+)\.([a-z]{2,4})$#si" );
		return true;
	}
	
	public function isPreg( $param, $pattern )
	{
		return preg_match( $pattern, $param );
	}
	
	public function isEreg( $param, $pattern )
	{
		return ereg( $pattern, $param );
	}
	
	public function isEregi( $param, $pattern )
	{
		return eregi( $pattern, $param );
	}
	
	public function validate( $string, $feature, $value )
	{
		$valid = true;
		$class_method = "is".ucfirst( $feature );
		if( method_exists( $this, $class_method ) )
			$valid = $this->$class_method( $string, $value );
		return $valid;
	}
}
?>