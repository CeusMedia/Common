<?php
class Go_Application
{
	private $basePath;
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
		if( !empty( $_SERVER['SERVER_ADDR'] ) )
			die( "This tool is for console only." );
		isset( $_SERVER['SHELL'] ) ? passthru( "clear" ) : exec( "command /C cls" );					//  try to clear screen (not working on Windows!?)
		print( "\n".$this->messages['title'] );															//  print tool title
		$this->basePath	= dirname( __FILE__ ).'/';
		$configFile	= dirname( dirname( __FILE__ ) )."/cmClasses.ini";									//  Configuration File
		$arguments	= array_slice( $_SERVER['argv'], 1 );												//  get given arguments
		if( !$arguments )																				//  no arguments given
			die( $this->showUsage( $this->messages['command_invalid'] ) );
		$command	= strtolower( $arguments[0] );														//  extract command

		try
		{
			if( file_exists( $configFile ) )															//  cmClasses installed and configured
			{
				require_once( 'autoload.php5' );														//  enable cmClasses
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
								require_once( $this->basePath.'DocCreator.php5' );
								new Go_DocCreator( array_slice( $arguments, 3 ), $configFile, $config );
								break;
							case 'phpdoc':
								require_once( $this->basePath.'PhpDocumentor.php5' );
								new Go_PhpDocumentor( array_slice( $arguments, 3 ), $configFile, $config );
								break;
							default:
								throw new InvalidArgumentException( $this->messages['tool_create_doc'] );
						}
						break;
					case 'test':
						require_once( $this->basePath.'ClassUnitTestCreator.php5' );
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
						require_once( $this->basePath.'ClassSyntaxTester.php5' );
						new Go_ClassSyntaxTester( $arguments );
						break;						
					case 'self':
						require_once( $this->basePath.'SelfTester.php5' );
						new Go_SelfTester( $arguments );
						break;						
					case 'units':

						require_once( $this->basePath.'Library.php5' );
		
		remark( "Reading Class Files:\n" );
		$data	= Go_Library::listClasses( dirname( dirname ( __FILE__ ) ).'/src/' );
		foreach( $data['files'] as $file )
		{
			require_once( $file );
			echo '.';
		}
		remark( "\n" );

						$command	= "phpunit";
						foreach( $config['unitTestOptions'] as $key => $value )
							$command	.= " --".$key." ".$value;
						print( "\nRunning Unit Tests:\n\r" );
						$command	.= " Test_AllTests";
						passthru( $command );
						break;
					default:
						require_once( $this->basePath.'ClassUnitTester.php5' );
						new Go_ClassUnitTester( $arguments );
#						throw new InvalidArgumentException( $this->messages['subject_test_invalid'] );
				}
			case 'run':
				break;
			case 'install':
				require_once( $this->basePath.'Installer.php5' );
				new Go_Installer( array_slice( $arguments, 1 ) );
				break;
			case 'configure':
				require_once( $this->basePath.'Configurator.php5' );
				new Go_Configurator( array_slice( $arguments, 1 ) );
				break;
			case 'update':
				require_once( $this->basePath.'Updater.php5' );
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
				$this->showUsage( $this->messages['command_invalid'] );
		}
	}

	public function showUsage( $message = NULL )
	{
		if( $message )
			$message	= "\nERROR: ".$message."\n";
		$text	= file_get_contents( $this->basePath.'usage.txt' );
		print( "\n".$text."\n".$message );
	}
}
?>
