<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */
require_once '../../vendor/autoload.php';

use CeusMedia\Common\CLI\Exception\View as ExceptionView;
use CeusMedia\Common\Env;
use CeusMedia\Common\Exception\FileNotExisting as FileNotExistingException;
use CeusMedia\Common\Exception\Runtime as RuntimeException;
use CeusMedia\Common\Exception\Traits\Renderable as RenderableTrait;
use CeusMedia\Common\Exception\Validation as ValidationException;
use CeusMedia\Common\UI\HTML\Exception\Page as HtmlExceptionPage;
use RuntimeException as BaseRuntimeException;

class TestClass
{
	public function throwTestException( array $numbers = [1, 2, 3] ): never
	{
		$previous	= new BaseRuntimeException( 'Level 1 Exception', 1 );
		$previous	= FileNotExistingException::create( 'Level 2 Exception', 2, $previous )
			->setResource( 'invalid_file_path' );
		throw ValidationException::create( '', 0, $previous )
			->setMessage( 'Level 3 Exception' )
			->setCode( 3 )
			->setForm( 'test' )
			->setDescription( 'Long text here' )
			->setSuggestion( 'Just do it right' )
		;
	}
}

try{
	$o = new TestClass();
	$o->throwTestException( [1, 2, 3] );
}
catch( Throwable $e ){
	if( in_array( RenderableTrait::class, class_uses( $e ), TRUE ) ){
		$view	= $e->render();
		if( Env::isCli() )
			print $view;
		else
			print HtmlExceptionPage::wrapExceptionViewWithHtmlPage( $view );
	}
	exit;

/*	$body1	= ExceptionView::getInstance( $e )->render().PHP_EOL;
	$e2		= unserialize(serialize($e));
	$body2	= ExceptionView::getInstance( $e2 )->render().PHP_EOL;
	print_m( $body1 === $body2 );die;*/

/*	print 'Message: '.$e->getMessage().PHP_EOL;
	print ExceptionView::getInstance( $e )->render().PHP_EOL;
	print 'Description: '.$e->getDescription().PHP_EOL;
	print $e->getJson().PHP_EOL;
die;*/

	$e2		= unserialize(serialize($e));

//	print 'Message: '.$e2->getMessage().PHP_EOL;
//	print 'Description: '.$e->getDescription().PHP_EOL;
	print $e2->getJson() . PHP_EOL;
	print ExceptionView::getInstance($e2)->render() . PHP_EOL;
}
