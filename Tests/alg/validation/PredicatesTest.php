<?php
/**
 *	TestUnit of Dictionay
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Dictionay
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.alg.validation.Predicates' );
/**
 *	TestUnit of Dictionay
 *	@package		Tests.adt.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Dictionay
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class Tests_Alg_Validation_PredicatesTest extends PHPUnit_Framework_TestCase
{
	public function testHasMaxLength()
	{
		$assertion	= true;
		$creation	= Alg_Validation_Predicates::hasMaxLength( "test1", 6 );
		$this->assertEquals( $assertion, $creation );
	
		$assertion	= false;
		$creation	= Alg_Validation_Predicates::hasMaxLength( "test1", 3 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>