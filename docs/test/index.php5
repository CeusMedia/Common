<?php
class UnitTestViewer
{
	public function __construct( $fileName )
	{
		if( !file_exists( $fileName ) )
			die( "no test results available - run unit tests first" );
		$reader			= new XML_UnitTestResultReader( $fileName );
		$date			= date( "d.m.Y H:i:s", $reader->getDate() );
		$time			= Alg_UnitFormater::formatSeconds( $reader->getTime(), 1 );
		$countSuites	= $reader->getTestSuiteCount();
		$countCases		= $reader->getTestCaseCount();
		$countTests		= $reader->getTestCount();
		$countFailures	= $reader->getFailureCount();
		$countErrors	= $reader->getErrorCount();
		$countPassed	= $countTests - $countFailures - $countErrors;
		$ratioFailures	= round( $countFailures / $countTests * 100, 1 );
		$ratioErrors	= round( $countErrors / $countTests * 100, 1 );
		$ratioPassed	= round( $countPassed / $countTests * 100, 1 );
		$failures		= $countFailures ? $this->buildList( $reader->getFailures(), 'failure' ) : "none";
		$errors			= $countErrors ? $this->buildList( $reader->getErrors(), 'failure' ) : "none";
		echo require_once( "template.phpt" );
	}

	protected function buildList( $tests, $type )
	{
		$list	= array();
		foreach( $tests as $test )
		{
			$lines	= array();
			foreach( explode( "\n", $test['error'] ) as $line )
			{
				$class	= NULL;
				$first	= substr( $line, 0, 1 );
				if( $first == "+" )
					$class	= 'positive';
				else if( $first == "-" )
					$class	= 'negative';
				$lines[]	= UI_HTML_Elements::ListItem( $line, 0, array( 'class' => $class ) );
			}
			$message	= UI_HTML_Elements::unorderedList( $lines, 0, array( 'class' => 'message' ) );

			$testSuite	= $test['suite'];
			$testCase	= $test['case'];
			$hash		= md5( $testSuite.":".$testCase );
			$id			= $type.$hash;
			$heading	= '<b>'.$testSuite.'</b> :: <em>'.$testCase.'</em>';
			$heading	= UI_HTML_Tag::create(
				'div',
				$heading,
				array(
					'class'		=> 'heading',
					'id'		=> $hash,
					'onclick'	=> "$('#".$type.$hash."').toggle();"
				)
			);
			$content	= UI_HTML_Tag::create(
				'div',
				$message,
				array(
					'class'	=> 'message',
					'id'	=> $type.$hash
				)
			);
			$list[]		= $heading."\n".$content;
		}
		return implode( "\n", $list );
	}
}
require_once '../../autoload.php5';
new UnitTestViewer( '../../Test/results.xml' );
?>