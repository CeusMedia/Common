<?php
/**
 *	@deorecated not needed once Go is gone
 */
class Go_Application
{
	private $basePath;
	private	$messages	= array(
		'title'						=> " > >  GO  > >   -  get & organize CeusMedia::Common\n",
		'config_missing'			=> "No Config File '%s' found.\nCeusMedia::Common must be installed and configured.\nGO must be within installation path.",
		'command_invalid'			=> "No valid command set.\nPlease append 'help' for further information!\n",
		'subject_create_invalid'	=> "No valid creator subject set (doc,test).",
		'subject_test_invalid'		=> "No valid test subject set (benchmark,syntax,self,units).",
		'tool_create_doc'			=> "No documentation tool set (creator,phpdoc).",
	);
	private $configFile				= 'Common.ini';

	public function autoload( $className )
	{
		if( preg_match( '/^Go_/', $className ) )													//  is it a GO class ?
			require_once $this->basePath.'Go/'.preg_replace( '/^Go_/', '', $className ).'.php';		//  then require it
	}

	public function __construct( $clearScreen = FALSE )
	{
		spl_autoload_register( array( $this, 'autoload' ) );
		if( !empty( $_SERVER['SERVER_ADDR'] ) )
			die( "This tool is for console use only." );
		if( $clearScreen )
			isset( $_SERVER['SHELL'] ) ? passthru( "clear" ) : exec( "command /C cls" );			//  try to clear screen (not working on Windows!?)
		print( "\n".$this->messages['title'] );														//  print tool title

#		Go_Library::$configFile	= $this->configFile;
		$this->basePath		= dirname( __DIR__ ).'/';
		$this->configFile	= Go_Library::getConfigFile();											//  point to Configuration File

		$arguments	= array_slice( $_SERVER['argv'], 1 );											//  get given arguments

		try
		{
			if( !$arguments )																		//  no arguments given
				throw new InvalidArgumentException( $this->messages['command_invalid'] );
			$command	= strtolower( $arguments[0] );												//  extract command
			if( file_exists( $this->configFile ) )													//  Common installed and configured
			{
				require_once( 'autoload.php' );														//  enable autoload of Common
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
				if( count( $arguments ) < 2 )
					throw new InvalidArgumentException( $this->messages['subject_create_invalid'] );
				$subject	= strtolower( $arguments[1] );
				switch( $subject )
				{
					case 'doc':
						new Go_DocCreator( array_slice( $arguments, 3 ) );
						break;
					case 'test':
						new Go_UnitTestCreator( array_slice( $arguments, 2 ) );
						break;
					default:
						throw new InvalidArgumentException( $this->messages['subject_create_invalid'] );
				}
				break;
			case 'test':
				if( count( $arguments ) < 2 )
					throw new InvalidArgumentException( $this->messages['subject_test_invalid'] );
				$subject	= strtolower( $arguments[1] );
				switch( $subject )
				{
					case 'benchmark':
						new Go_Benchmark();
						break;
					case 'syntax':
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
			default:
				throw new InvalidArgumentException( $this->messages['command_invalid'] );
		}
	}

	public function showUsage( $message = NULL )
	{
		if( $message )
			$message	= "\nERROR: ".$message."\n";
		$text	= file_get_contents( $this->basePath.'Go/usage.txt' );
		$make	= file_get_contents( $this->basePath.'Go/make.txt' );
		$text	= str_replace( '{{make.txt}}', $make, $text);
		print( "\n".$text."\n".$message );
	}
}
?>
