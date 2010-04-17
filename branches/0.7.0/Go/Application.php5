<?php
class Go_Application
{
	private $basePath;
	private	$messages	= array(
		'title'						=> " > >  GO  > >   -  get & organize cmClasses\n",
		'config_missing'			=> "No Config File '%s' found.\ncmClasses must be installed and configured.\nGO must be within installation path.",
		'command_invalid'			=> "No valid command set.\nPlease append 'help' for further information!\n",
		'subject_create_invalid'	=> "No valid creator subject set (doc,test).",
		'subject_test_invalid'		=> "No valid test subject set (benchmark,classes,self,units).",
		'tool_create_doc'			=> "No documentation tool set (creator,phpdoc).",
	);
	private $configFile				= 'cmClasses.ini';

	public function autoload( $className )
	{
		if( preg_match( '/^Go_/', $className ) )													//  is it a GO class ?
			require_once $this->basePath . preg_replace( '/^Go_/', '', $className ).'.php5';		//  then require it
	}

	public function __construct( $clearScreen = FALSE )
	{
		spl_autoload_register( array( $this, 'autoload' ) );
		if( !empty( $_SERVER['SERVER_ADDR'] ) )
			die( "This tool is for console use only." );
		if( $clearScreen )
			isset( $_SERVER['SHELL'] ) ? passthru( "clear" ) : exec( "command /C cls" );			//  try to clear screen (not working on Windows!?)
		print( "\n".$this->messages['title'] );														//  print tool title

		$this->basePath		= dirname( __FILE__ ).'/';
		$this->configFile	= dirname( dirname( __FILE__ ) ).'/'.$this->configFile;					//  point to Configuration File

		$arguments	= array_slice( $_SERVER['argv'], 1 );											//  get given arguments

		try
		{
			if( !$arguments )																		//  no arguments given
				throw new InvalidArgumentException( $this->messages['command_invalid'] );
			$command	= strtolower( $arguments[0] );												//  extract command
			if( file_exists( $this->configFile ) )													//  cmClasses installed and configured
			{
				require_once( 'autoload.php5' );													//  enable cmClasses
				import( 'de.ceus-media.ui.DevOutput' );												//  load output methods
			}
			else if( !( $command == "install" || $command == "configure" ) )						//  anything else but installation is impossible
			{
				$message	= sprintf( $this->messages['config_missing'], $this->configFile );
				throw new RuntimeException( $message );
			}
			$this->handle( $this->configFile, $command, $arguments );								//  run tool component switch
		}
#		catch( InvalidArgumentException $e )														//  catch argument exception
#		{
#			$this->showUsage( $e->getMessage() );													//  show usage and message
#		}
		catch( Exception $e )																		//  catch any other exception
		{
			print( "\nERROR: ".$e->getMessage()."\n" );												//  show message only
		}
	}

	private function handle( $configFile, $command, $arguments )
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
				$config		= parse_ini_file( $this->configFile, TRUE );
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
								new Go_DocCreator( array_slice( $arguments, 3 ), $this->configFile, $config );
								break;
							case 'phpdoc':
								new Go_PhpDocumentor( array_slice( $arguments, 3 ), $this->configFile, $config );
								break;
							default:
								throw new InvalidArgumentException( $this->messages['tool_create_doc'] );
						}
						break;
					case 'test':
						new Go_UnitTestCreator( array_slice( $arguments, 2 ) );
						break;
					default:
						throw new InvalidArgumentException( $this->messages['subject_create_invalid'] );
				}
				break;
			case 'test':
				$config		= parse_ini_file( $this->configFile, TRUE );
				if( count( $arguments ) < 2 )
					throw new InvalidArgumentException( $this->messages['subject_test_invalid'] );
				$subject	= strtolower( $arguments[1] );
				switch( $subject )
				{
					case 'benchmark':
						new Go_Benchmark();
						break;						
					case 'classes':
						new Go_ClassSyntaxTester( $arguments );
						break;						
					case 'self':
						new Go_SelfTester( $arguments );
						break;						
					case 'units':
						$className	= empty( $arguments[2] ) ? NULL : $arguments[2];
						new Go_UnitTester( $className );
						break;
					default:
						throw new InvalidArgumentException( $this->messages['subject_test_invalid'] );
				}
				break;
			case 'install':
				new Go_Installer( array_slice( $arguments, 1 ) );
				break;
			case 'configure':
				new Go_Configurator( array_slice( $arguments, 1 ) );
				break;
			case 'update':
				new Go_Updater( array_slice( $arguments, 1 ) );
				break;
			case 'moo':
				print Go_Animal::getCow(); 
				break;
			default:
				throw new InvalidArgumentException( $this->messages['command_invalid'] );
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
