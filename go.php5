<?php
class Go
{
	private	$messages	= array(
		'title'						=> " > > GO > >   -  get & organize cmClasses\n",
		'config_missing'			=> "No Config File '%s' found.\n       cmClasses must be installed and configured.\n       GO must be within installation path.",
		'command_invalid'			=> "No valid command set (install,configure,update,create,test,run)",
		'subject_create_invalid'	=> "No valid creator subject set (doc,test).",
		'subject_test_invalid'		=> "No valid test subject set (self,units).",
		'tool_create_doc'			=> "No documentation tool set (creator,phpdoc).",
	);

	public function __construct()
	{
		exec( isset( $_SERVER['SHELL'] ) ? "clear" : "cls" );											//  try to clear screen (not working?)
		print( "\n".$this->messages['title'] );															//  print tool title
		$configFile	= "cmClasses.ini";																	//  Configuration File
		$arguments	= array_slice( $_SERVER['argv'], 1 );												//  get given arguments
		if( !$arguments )																				//  no arguments given
			die( $this->showUsage( $this->messages['command_invalid'] ) );
		$command	= strtolower( $arguments[0] );														//  extract command

		try
		{
			if( file_exists( $configFile ) )															//  cmClasses installed and configured
			{
				require_once( "useClasses.php5" );														//  enable cmClasses
				import( 'de.ceus-media.ui.DevOutput' );													//  load output methods
			}
			else if( !( $command == "install" || $command == "configure" ) )							//  anything else but installation is impossible
			{
				$message	= sprintf( $this->messages['config_missing'], $configFile );
				throw new RuntimeException( $message );
			}
			$this->run( $configFile, $arguments, $command );											//  run tool component switch
		}
		catch( InvalidArgumentException $e )															//  catch argument exception
		{
			$this->showUsage( $e->getMessage() );														//  show usage and message
		}
		catch( Exception $e )																			//  catch any other exception
		{
			print( "\nERROR: ".$e->getMessage()."\n" );													//  show message only
		}
	}
		
	private function run( $configFile, $arguments, $command )
	{
		switch( $command )
		{
			case '-h':
			case '--help':
			case '/?':
			case '-?':
			case 'help':
				$this->showUsage();
				break;
			case 'create':
				$config		= parse_ini_file( $configFile, TRUE );
				if( count( $arguments ) < 2 )
					throw new InvalidArgumentException( $this->messages['subject_create_invalid'] );
				$subject	= strtolower( $arguments[1] );
				switch( $subject )
				{
					case 'doc':
						if( count( $arguments ) < 3 )
							throw new InvalidArgumentException( $this->messages['tool_create_doc'] );
						$tool	= strtolower( $arguments[2] );
						switch( $tool )
						{
							case 'creator':
								new GoDocCreator( array_slice( $arguments, 3 ), $configFile, $config );
								break;
							case 'phpdoc':
								new GoPhpDocumentor( array_slice( $arguments, 3 ), $configFile, $config );
								break;
							default:
								throw new InvalidArgumentException( $this->messages['tool_create_doc'] );
						}
						break;
					case 'test':
						new GoTestCreator( array_slice( $arguments, 2 ) );
						break;
					default:
						throw new InvalidArgumentException( $this->messages['subject_create_invalid'] );
				}
				break;
			case 'test':
				$config		= parse_ini_file( $configFile, TRUE );
				if( count( $arguments ) < 2 )
					throw new InvalidArgumentException( $this->messages['subject_test_invalid'] );
				$subject	= strtolower( $arguments[1] );
				switch( $subject )
				{
					case 'self':
						new GoSelfTester( $arguments );
						break;						
					case 'units':
						$command	= "phpunit";
						foreach( $config['unitTestOptions'] as $key => $value )
							$command	.= " --".$key." ".$value;
						print( "\nRunning Unit Tests:\n" );
						$command	.= " Tests_AllTests";
						passthru( $command );
						break;
					default:
						new GoTestRunner( $arguments );
#						throw new InvalidArgumentException( $this->messages['subject_test_invalid'] );
				}
			case 'run':
				break;
			case 'install':
				new GoInstaller( array_slice( $arguments, 1 ) );
				break;
			case 'configure':
				new GoConfigurator( array_slice( $arguments, 1 ) );
				break;
			case 'update':
				new GoUpdater( array_slice( $arguments, 1 ) );
				break;
			case 'moo':
				print( '
         (__)
        ~(..)~
   ,----\(oo)
  /|____|,\'
 * /"\ /\
' );
				break;
			default:
				$this->showUsage( $this->messages['command_invalid'] );
		}
	}

	public function showUsage( $message = NULL )
	{
		if( $message )
			$message	= "\nERROR: ".$message."\n";
		print( "
Usage: go COMMAND [OPTION]...
  install                      install cmClasses via SVN
    VERSION                      version to install (trunk,branches/0.6.3,...)
  configure                    auto-configure paths
   -f, --force                   overwrite if existing (warning!)
  update                       update cmClasses via SVN
    [REVISION]                   revision to update to
  create                       create...
    test                         test class
      PACKAGE_CLASS                for class in cmClasses
      -f, --force                  force to write test class (warning!)
    doc                        documentations
      creator                    using DocCreator
        -sp, --skip-parser        skip to parse class files
        -sc, --skip-creator       skip to write doc file
        -sr, --skip-resources     skip to copy resource files
      phpdoc                     using PhpDocumentor
        --show-config-only         show settings and abort
  run                          (not implemented yet)
  test                         test...
    self                         very basic self test
    PACKAGE_CLASS                class in cmClasses
    units                        units = test classes
    
".$message );
	}
}


class GoConfigurator
{
	public function __construct( $arguments )
	{
		$force	= in_array( "-f", $arguments ) || in_array( "--force", $arguments );
		$pwd	= str_replace( "\\", "/", dirname( realpath( __FILE__ ) ) )."/";

		if( !defined( 'CM_CLASS_PATH' ) )
			define( 'CM_CLASS_PATH',	$pwd );
		ini_set( 'include_path', CM_CLASS_PATH.PATH_SEPARATOR.ini_get( "include_path" ) );
		if( !@include_once( "import.php5" ) )
			die( 'Installation of "cmClasses" seems to be corrupt: '.$pwd.'import.php5 is missing.' );
		import( 'de.ceus-media.ui.DevOutput' );

		$files	= array(
			"cmClasses.ini.dist"	=> "cmClasses.ini",
			"useClasses.php5.dist"	=> "useClasses.php5",
		);

		foreach( $files as $sourceFile => $targetFile )
		{
			if( !file_exists( $pwd.$sourceFile ) )
				throw new RuntimeException( 'Source file "'.$sourceFile.'" is not existing.' );
			
			remark( 'Setting up "'.$sourceFile.'"... ' );
			$content	= file_get_contents( $pwd.$sourceFile );
			$content	= str_replace( "/path/to/cmClasses/version/", $pwd, $content );
			if( !$force && file_exists( $pwd.$targetFile ) )
				$status	= "already existing, use --force to overwrite";
			else
				$status	= "done.";
			print( $status );
			file_put_contents( $pwd.$targetFile, $content );
		}
		if( in_array( "--clean-up", $arguments ) )
		{
			remark( "Removing installer and distribution files..." );
			foreach( $files as $sourceFile => $targetFile )
				unlink( $pwd.$sourceFile );
		}
		remark( "\ncmClasses is now configured and usable.\n" );
	}
}
class GoInstaller
{
	public function __construct( $arguments )
	{
		if( count( $arguments ) < 2 )
			throw new InvalidArgumentException( 'No install path set.' );
		if( count( $arguments ) < 1 )
			throw new InvalidArgumentException( 'No version to install set.' );

		exec( "svn co http://cmclasses.googlecode.com/svn/".$arguments[0]." ".$arguments[1], $return );
		if( !$return )
			throw new RuntimeException( "SVN seems to be not installed." );
		chDir( $arguments[1] );
		new GoConfigurator();
	}
}
class GoUpdater
{
	public function __construct( $arguments )
	{
		$revision	= $arguments ? $arguments[0] : "";
		$path		= dirname( realpath( __FILE__ ) );
		exec( "svn update ".$path." ".$revision, $return );
		if( !$return )
			throw new RuntimeException( "SVN seems to be not installed." );
		echo implode( "\n", $return );
	}
}
class GoDocCreator
{
	public function __construct( $arguments, $configFile, $config )
	{
		$pathWork	= dirname( realpath( __FILE__ ) )."/";	
		$pathTool	= $config['docCreator']['pathTool'];
		if( !file_exists( $pathTool ) )
			throw new RuntimeException( 'Tool DocCreator is not installed in "'.$pathTool.'".' );
		chDir( $pathTool );
		import( 'classes.DocCreator' );
		DocCreator::$defaultConfigFile	= $configFile;
		new DocCreator( $pathWork );
	}
}
class GoPhpDocumentor
{
	public function __construct( $arguments, $configFile, $config )
	{
		$reportFile	= $config['phpDocumentor']['outputLog'];							//  phpDocumentor Report File
		$command	= "phpdoc -c ".$configFile;											//  Shell Command to run phpDocumentor

		if( in_array( "--show-config-only", $arguments ) )
		{
			remark( "Settings:" );
			foreach( $config['phpDocumentor'] as $key => $value )
			{
				$key	.= str_repeat( " ", 20 - strlen( $key ) );
				remark( $key.$value );
			}
			return;
		}
		if( in_array( "-q", $arguments ) || in_array( "--quite", $arguments ) )			//  Quite Mode is activated
		{
			$command	.= " > ".$reportFile;											//  redirect Output into Report File
			@unlink( $reportFile );														//  remove old Report File
		}
		passthru( $command );															//  run phpDocumentor
	}
}
class GoTestRunner
{
	public function __construct( $arguments )
	{
		$classKey	= $arguments[1];
		$parts		= explode( "_", $classKey );
		$fileKey	= array_pop( $parts );
		$suffix		= $fileKey == "All" ? "Tests" : "Test";
		while( $parts )
			$fileKey	= strtolower( array_pop( $parts ) )."/".$fileKey;

		$testClass	= "Tests_".$arguments[1].$suffix;
		$testFile	= "Tests/".$fileKey.$suffix.".php";
		if( !file_exists( $testFile ) )
			throw new RuntimeException( 'Test Class File "'.$testFile.'" is not existing.' );
		echo "\nTesting Class: ".$classKey."\n\n";
		passthru( "phpunit ".$testClass, $return );
	}
}
class GoTestCreator
{
	public function __construct( $arguments )
	{
		require_once( "useClasses.php5" );
		import( 'de.ceus-media.alg.TestCaseCreator' );
		import( 'de.ceus-media.console.ArgumentParser' );
		import( 'de.ceus-media.ui.DevOutput' );

		$force	= in_array( "-f", $arguments ) || in_array( "--force", $arguments );
		if( in_array( "-f", $arguments ) )
			unset( $arguments[array_search( "-f", $arguments )] );
		if( in_array( "--force", $arguments ) )
			unset( $arguments[array_search( "--force", $arguments )] );
		if( !$arguments )
			throw new InvalidArgumentException( 'No class name given to create test class for.' );
		$class	= array_shift( $arguments );
		$creator	= new Alg_TestCaseCreator();
		$creator->createForFile( $class, $force );
		remark( 'Created test class "Tests_'.$class.'Test".' );
	}
}
class GoSelfTester
{
	public function __construct( $arguments )
	{
		remark( "testing GO itself" );
		remark( "1. random numbers with 3 digits: " );
		import( 'de.ceus-media.alg.Randomizer' );
		import( 'de.ceus-media.math.RomanNumbers' );

		$randomizer	= new Alg_Randomizer();
		$randomizer->useLarges	= FALSE;
		$randomizer->useSmalls	= FALSE;
		$randomizer->useSigns	= FALSE;
		$c	= $randomizer->get( 1 ) + 2;
		for( $i=0; $i<$c; $i++ )
			print( $randomizer->get( 3 )." " );

		remark( "2. roman date: " );
		$year	= date( "Y" );
		print( $year. " is ".Math_RomanNumbers::convertToRoman( $year ) );

		remark( "" );
	}
}
$cwd		= getCwd();											//  current working directory
$twd		= dirname( realpath( __FILE__ ) );					//  tool working directory
chDir( $twd ); 
new Go();
chDir( $cwd );
?>