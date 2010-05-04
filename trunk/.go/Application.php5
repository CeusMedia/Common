<?php
class Go_Application
{
	private	$messages	= array(
		'title'						=> " > > GO > >   -  get & organize cmClasses\n",
		'install_invalid'			=> 'cmClasses is not installed properly',
		'config_missing'			=> "No Config File '%s' found.\n       cmClasses must be installed and configured.\n       GO must be within installation path.",
		'command_invalid'			=> "No valid command set (install,configure,update,create,test,run)",
		'subject_create_invalid'	=> "No valid creator subject set (doc,test).",
		'subject_test_invalid'		=> "No valid test subject set (self,units).",
		'tool_create_doc'			=> "No documentation tool set (creator,phpdoc).",
	);

	public function checkSetUp()
	{
		$pathLib	= dirname( dirname( __FILE__ ) ).'/';
		$hasScript	= file_exists( $pathLib.'useClasses.php5' );
		$hasConfig	= file_exists( $pathLib.'cmClasses.ini' );
		
		return $hasScript && $hasConfig;
	}

	public function __construct()
	{
		if( !empty( $_SERVER['SERVER_ADDR'] ) )
			die( "This tool is for console only." );
		isset( $_SERVER['SHELL'] ) ? passthru( "clear" ) : exec( "command /C cls" );					//  try to clear screen (not working on Windows!?)
		print( "\n".$this->messages['title'] );															//  print tool title
		$configFile	= dirname( dirname( __FILE__ ) )."/cmClasses.ini";									//  Configuration File

		$command	= NULL;
		$arguments	= array_slice( $_SERVER['argv'], 1 );												//  get given arguments
		if( !empty( $arguments[0] ) )
			$command	= strtolower( $arguments[0] );														//  extract command

		try
		{
			$isSetUp	= $this->checkSetUp();
			$cmdSetUp	= array( 'install', 'configure' );
			if( !( $isSetUp || in_array( $command, $cmdSetUp ) ) )
				$this->showError( $this->messages['install_invalid'] );

			if( !$arguments )																				//  no arguments given
				$this->showError( $this->messages['command_invalid'] );

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
								require_once( ".go/DocCreator.php5" );
								new Go_DocCreator( array_slice( $arguments, 3 ), $configFile, $config );
								break;
							case 'phpdoc':
								require_once( ".go/PhpDocumentor.php5" );
								new Go_PhpDocumentor( array_slice( $arguments, 3 ), $configFile, $config );
								break;
							default:
								throw new InvalidArgumentException( $this->messages['tool_create_doc'] );
						}
						break;
					case 'test':
						require_once( ".go/ClassUnitTestCreator.php5" );
						new Go_ClassUnitTestCreator( array_slice( $arguments, 2 ) );
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
					case 'classes':
						require_once( ".go/ClassSyntaxTester.php5" );
						new Go_ClassSyntaxTester( $arguments );
						break;						
					case 'self':
						require_once( ".go/SelfTester.php5" );
						new Go_SelfTester( $arguments );
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
						require_once( ".go/ClassUnitTester.php5" );
						new Go_ClassUnitTester( $arguments );
#						throw new InvalidArgumentException( $this->messages['subject_test_invalid'] );
				}
			case 'run':
				break;
			case 'install':
				require_once( ".go/Installer.php5" );
				new Go_Installer( array_slice( $arguments, 1 ) );
				break;
			case 'configure':
				require_once( ".go/Configurator.php5" );
				new Go_Configurator( array_slice( $arguments, 1 ) );
				break;
			case 'update':
				require_once( ".go/Updater.php5" );
				new Go_Updater( array_slice( $arguments, 1 ) );
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
				die( "-" );
				$this->showUsage( $this->messages['command_invalid'] );
		}
	}

	public function showUsage( $message = NULL )
	{
		if( $message )
			$message	= "\nERROR: ".$message."\n";
		$fileUri	= dirname( __FILE__ )."/usage.txt";
		$text		= file_get_contents( $fileUri );
		print( "\n".$text."\n".$message );
	}

	public function showError( $message )
	{
		$message	= "\nError: ".$message."\n";
		die( $message );
	}

	public function showException( Exception $exception )
	{
		$message	= "\nException: ".$exception->getMessage()."\n";
	}
}
?>