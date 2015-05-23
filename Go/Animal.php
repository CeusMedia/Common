<?php
class Go_Animal
{
	public function __construct( $sound )
	{
		switch( $sound )
		{
			case 'moo':
				print self::getCow();
				break;
			default:
				break;
		}
	}

	public static function getCow()
	{
		return '
         (__)
        ~(..)~
   ,----\(oo)
  /|____|,\'
 * /"\ /\
';
	}
}
