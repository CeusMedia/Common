<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\CLI\Exception;

use CeusMedia\Common\Exception\Runtime;
use CeusMedia\Common\Exception\Traits\Descriptive;
use Throwable;
use InvalidArgumentException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_CLI_Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class View
{
	protected ?Throwable $exception		= NULL;
	protected string $heading			= 'Exception:';

	/**
	 *	@param		Throwable|NULL		$exception
	 *	@return		self
	 */
	public static function getInstance( ?Throwable $exception = NULL ): self
	{
		return new self( $exception );
	}

	/**
	 *	@param		Throwable|NULL		$exception
	 */
	public function __construct( ?Throwable $exception = NULL )
	{
		if( !is_null( $exception ) )
			$this->setException( $exception );
	}

	/**
	 *	@return		string
	 */
	public function __toString(): string
	{
		return $this->render();
	}

	/**
	 *	@return		string
	 */
	public function render(): string
	{
		if( NULL === $this->exception )
			throw new InvalidArgumentException( 'No exception set' );

		$e	= $this->exception;
		$lines		= '' !== $this->heading ? [$this->heading] : [];
		$lines[]	= '- Message:     '.$e->getMessage();
		$lines[]	= '- Code:        '.$e->getCode();
		if( in_array( Descriptive::class, class_uses( $e ), TRUE ) ){
			/** @var Runtime $e */
			if( '' !== $e->getDescription() )
				$lines[]	= '- Description: '.$e->getDescription();
			if( '' !== $e->getSuggestion() )
				$lines[]	= '- Suggestion:  '.$e->getSuggestion();
			foreach( $e->getAdditionalProperties() as $key => $value ){
				if( in_array( $key, ['description', 'suggestion', 'traceAsString'], TRUE ) )
					continue;
				switch( gettype( $value ) ){
					case 'object':
					case 'array':
						$value	= json_encode( $value );
						break;
					default:
						$value ??= '-empty-';
				}
				$key		= str_pad( ucfirst( $key ).':', 13, ' ', STR_PAD_RIGHT );
				$lines[]	= '- '.$key.$value;
			}
		}

		$lines[]	= '';
		$lines[]	= $this->renderFactsBlock();

		$lines[]	= '';
		$lines[]	= $this->renderTraceBlock();

		if( NULL !== $e->getPrevious() )
			$lines[]	= PHP_EOL.self::getInstance( $e->getPrevious() )
				->setHeading( 'Previous Exception:' )
				->render();

		return join( PHP_EOL, $lines ).PHP_EOL;
	}

	/**
	 *	@param		Throwable		$exception
	 *	@return		self
	 */
	public function setException( Throwable $exception ): self
	{
		$this->exception	= $exception;
		return $this;
	}

	/**
	 *	@param		?string		$heading
	 *	@return		self
	 */
	public function setHeading( ?string $heading ): self
	{
		$this->heading	= $heading;
		return $this;
	}

	/**
	 *	@return		string
	 */
	protected function renderFactsBlock(): string
	{
		$fullClassName	= get_class( $this->exception );
		$spaceParts	= explode( '\\', preg_replace( '/^\\\\/', '', $fullClassName ) );
		$revCan		= join( '/', array_reverse( $spaceParts ) );
		$className	= array_pop( $spaceParts );
		$namespace	= join( '\\', $spaceParts );
		return join( PHP_EOL, [
			'Facts:',
			'- File:Line:   '.$this->exception->getFile().':'.$this->exception->getLine(),
//			'- Class Name:  '.$fullClassName,
			'- Class/Type:  '.$className.( '' !== $namespace ? ' (in '.$namespace.')' : '' ),
			'- rCanonical:  '.str_replace( '/Exception', 'Exception', $revCan ),
		] );
	}

	/**
	 *	@return		string
	 */
	protected function renderTraceBlock(): string
	{
		$lines	= [];
		foreach( $this->exception->getTrace() as $nr => $step ){
			$number			= str_pad( '#'.( $nr + 1 ), 4, ' ', STR_PAD_RIGHT );
			$classPrefix	= '' !== $step['class'] ? $step['class'].' '.$step['type'].' ' : '';
			$arguments		= implode( ', ', $step['args'] ?? [] );
			$lines[]		= vsprintf( '%s Call: %s%s(%s)'.PHP_EOL.'     File: %s @ %s', [
				$number,
				$classPrefix,
				$step['function'],
				$arguments,
				$step['file'],
				$step['line']
			] );
		}
		$lines[]	= 'Trace:';
		return join( PHP_EOL, array_reverse( $lines ) );
	}
}
