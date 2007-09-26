<?php
import( 'de.ceus-media.adt.OptionObject' );
/**
 *	Randomizer supporting different sign types.
 *	@package		math
 *	@extends		OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.01.2006
 *	@version		0.1
 */
/**
 *	Randomizer supporting different sign types.
 *	@package		math
 *	@extends		OptionObject
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.01.2006
 *	@version		0.1
 */
class Randomizer extends OptionObject
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->options		= array(
			"useDigits"	=> "digits",
			"useSmalls"	=> "smalls",
			"useLarges"	=> "larges",
			"useSigns"	=> "signs",
			);

		$this->setOption( 'digits',	"0123456789" );
		$this->setOption( 'smalls',	"abcdefghijklmnopqrstuvwxyz" );
		$this->setOption( 'larges',	"ABCDEFGHIJKLMNOPQRSTUVWXYZ" );
		$this->setOption( 'signs',	".:~_-+*#&§%!()={[]}/" );

		$this->setOption( 'useDigits',		true );
		$this->setOption( 'useSmalls',	true );
		$this->setOption( 'useLarges',	true );
		$this->setOption( 'useSigns',		true );
		$this->setOption( 'length',		0 );
		$this->setOption( 'unique',		true );
	}

	/**
	 *	Builds and returns randomized string.
	 *	@access		public
	 *	@param		int		$length		Length of string to build
	 *	@return		string
	 */
	public function get( $length = 0 )
	{
		if( !$length )
		{
			if( $this->getOption( 'length' ) )
				$length = $this->getOption( 'length' );
			else
				trigger_error( "Randomizer: No Length given", E_USER_ERROR );
		}
		
		$pool	= "";
		foreach( $this->options as $key => $value )
			if( $this->getOption( $key ) )
				$pool	.= $this->getOption( $value );
		if( $this->getOption( 'unique' ) && $length > strlen( $pool ) )
			trigger_error( "Randomizer: Length is greater than amount of used signs ", E_USER_ERROR );

		$random	= array();
		$input	= array();
		for( $i=0; $i<strlen( $pool ); $i++ )
			$input[] = $pool[$i];
		for( $i=0; $i<$length; $i++ )
		{
			srand( ( float ) microtime() * 10000000 );
			$rand_key = array_rand( $input, 1 );
			if( $this->getOption( 'unique' ) )
			{
				if( !in_array( $input[$rand_key], $random ) )
					$random[] = $input[$rand_key];
				else
				{
					$i--;
					continue;
				}
			}
			else
				$random[] = $input[$rand_key];
		}
		$random	= join( $random );
		return $random;
	}
}
?>