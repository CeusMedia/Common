<?php
import( 'de.ceus-media.console.command.ArgumentParser' );
abstract class Console_Command_Program
{
	public function __construct( $options, $shortcuts )
	{
		$arguments	= $_SERVER['argv'];
		array_shift( $arguments );
		$string		= implode( " ", $arguments );

		$parser	= new Console_Command_ArgumentParser();
		$parser->setNumberOfArguments( 2 );
		$parser->setShortcuts( $shortcuts );
		$parser->setPossibleOptions( $options );
		try
		{
			$parser->parse( $string );
			$this->arguments	= $parser->getArguments();
			$this->options		= $parser->getOptions();
			$this->main();
		}
		catch( Exception $e )
		{
			$this->showError( $e->getMessage() );
		}
	}

	abstract protected function main();
	
	/**
	 *	Prints Error Message to Console, to be overwritten.
	 *	@access		protected
	 *	@param		string		$message		Error Message to print to Console
	 *	@return		void
	 */
	protected function showError( $message, $abort = TRUE )
	{
		$message	= "\n".$message."\n";
		if( $abort )
			die( $message );
		echo $message;
	}
}
?>