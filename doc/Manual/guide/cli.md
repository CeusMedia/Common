# CLI

The `CLI` namespace helps to write console scripts: reading arguments, printing text, colors,
tables and progress bars, asking questions, running processes in the background, forking workers
and running cron jobs.

## Arguments

Two parsers are available:

| Parser | Style | Validation |
|--------|-------|------------|
| [`CLI\ArgumentParser`](../api/CLI/ArgumentParser.md) | `command key=value` | none, shortcuts for keys |
| [`CLI\Command\ArgumentParser`](../api/CLI/Command/ArgumentParser.md) | `argument -v --key=value` | known options, value patterns, mandatory arguments |

### Commands and key-value pairs

`ArgumentParser::parse()` splits a string or a list into commands and parameters. Every argument
containing `=` becomes a parameter, all others are commands. Shortcuts rename parameter keys:

```php
use CeusMedia\Common\CLI\ArgumentParser;

[$commands, $parameters]	= ArgumentParser::parse( 'import users.csv v=1 limit=10', ['v' => 'verbose'] );
// $commands:   ['import', 'users.csv']
// $parameters: ['verbose' => '1', 'limit' => '10']
```

For the current call (`php tool.php import users.csv v=1`) use `parseFromCurrentCall()`.
It also returns the called script and the working directory:

```php
[$commands, $parameters, $script, $path]	= ArgumentParser::parseFromCurrentCall( ['v' => 'verbose'] );
// $commands: ['import', 'users.csv'], $parameters: ['verbose' => '1'], $script: 'tool.php'
```

!!! info "Flags without value"
    An argument like `--dry-run` has no `=` and is therefore a command. Set the last argument
    `$fallBackOnEmptyPair` to `TRUE` to treat every argument as parameter – arguments without `=`
    get the value `NULL` then.

[`CLI\RequestReceiver`](../api/CLI/RequestReceiver.md) is a dictionary of the current call:
commands are stored under numeric keys (the script name first), parameters under their names.

```php
use CeusMedia\Common\CLI\RequestReceiver;

$request	= new RequestReceiver();      // php tool.php import limit=10
$request->get( 1 );                       // 'import'
$request->get( 'limit' );                 // '10'
```

### Options with validation

[`CLI\Command\ArgumentParser`](../api/CLI/Command/ArgumentParser.md) knows the possible options.
Each option is mapped to `FALSE` (flag without value), `TRUE` (any value) or a regular expression
the value must match. Values follow after `=`, `:` or a space:

```php
use CeusMedia\Common\CLI\Command\ArgumentParser;

$parser	= new ArgumentParser();
$parser->setNumberOfMandatoryArguments( 1 );
$parser->setPossibleOptions( [
	'verbose'	=> FALSE,			//  flag without value
	'limit'		=> '/^\d+$/',		//  value must match pattern
	'format'	=> TRUE,			//  any value
] );
$parser->setShortcuts( ['v' => 'verbose', 'l' => 'limit'] );

$parser->parse( 'import users.csv -v --limit=10 --format csv' );
$parser->getArguments();    // ['import', 'users.csv']
$parser->getOptions();      // ['limit' => '10', 'format' => 'csv', 'verbose' => TRUE]
```

Unknown options and values not matching their pattern throw an `InvalidArgumentException`,
missing arguments or option values a `RuntimeException`.

### Console programs

[`CLI\Command\Program`](../api/CLI/Command/Program.md) wraps this parser: define the options in the
constructor and implement `main()`. `run()` parses the arguments of the current call, fills
`$this->arguments` and `$this->options` and returns the exit code of `main()`:

```php
use CeusMedia\Common\CLI\Command\Program;

class ImportProgram extends Program
{
	public function __construct()
	{
		$options	= ['verbose' => FALSE, 'limit' => '/^\d+$/'];
		$shortcuts	= ['v' => 'verbose', 'l' => 'limit'];
		parent::__construct( $options, $shortcuts, 1 );
	}

	protected function main(): int
	{
		$file	= $this->arguments[0];
		$limit	= (int) ( $this->options['limit'] ?? 100 );
		if( !empty( $this->options['verbose'] ) )
			echo 'Importing '.$limit.' rows from '.$file.PHP_EOL;
		return static::EXIT_OK;
	}
}

exit( ( new ImportProgram() )->run() );    // php import.php users.csv -v -l=20
```

If parsing fails – or `main()` throws an exception – the message is printed and the script exits
with `Program::EXIT_PARSE` (2). Override `handleParserException()` or `showError()` to change this.

The older [`CLI\Application`](../api/CLI/Application.md) uses `CLI\ArgumentParser` and calls `main()`
right from its constructor; the parsed values are found in `$this->arguments->get( 'commands' )`
and `$this->arguments->get( 'parameters' )`.

## Output

The root class [`CLI`](../api/CLI.md) has static shortcuts for printing. `out()` writes to STDOUT,
`error()` to STDERR (both print to the page outside the console). Lists print each item, other
values are dumped:

```php
use CeusMedia\Common\CLI;

CLI::out( 'Hello' );
CLI::out( ['first line', 'continued'] );    // items are printed without separator
CLI::out( 'no line break', FALSE );
CLI::error( 'Something failed' );
```

[`CLI\Output`](../api/CLI/Output.md) updates the current line, e.g. for status messages.
`newLine()` starts a new line *before* printing, `sameLine()` overwrites the current line and
`append()` adds to it:

```php
use CeusMedia\Common\CLI\Output;

$output	= new Output();
$output->newLine( 'Loading …' );
$output->sameLine( 'Loaded 12 files' );    // replaces "Loading …"
$output->append( ', done.' );
$output->setMaxLineLength( 60 );           // longer lines are shortened in the middle
```

!!! info "Headless environments"
    `sameLine()` (and so `append()` and the progress bar) prints nothing in a headless environment –
    neither `TERM` nor `DISPLAY` set, like in cron jobs. `newLine()` prints whenever PHP runs on the
    console.

### Colors and styles

[`CLI\Color`](../api/CLI/Color.md) wraps strings in ANSI escape sequences:

```php
use CeusMedia\Common\CLI\Color;

$color	= new Color();
echo $color->colorize( 'Warning', 'yellow', 'blue' ), PHP_EOL;
echo $color->asError( 'Failed' ), ' ', $color->asSuccess( 'OK' ), PHP_EOL;
echo $color->bold( 'bold' ), ' ', $color->italic( 'italic' ), ' ', $color->underscore( 'underlined' ), PHP_EOL;
echo $color->colorize256( 'orange', '208' ), PHP_EOL;     // color number of a 256-color terminal
```

`getForegroundColors()` and `getBackgroundColors()` list the color names. The classes of
`asError()`, `asWarning()`, `asInfo()` and `asSuccess()` are defined in the static array `Color::$classes`
and can be changed or extended for `applyClass()`.

### Tables

[`CLI\Output\Table`](../api/CLI/Output/Table.md) renders a list of rows. The keys of the first row
are the headline, string columns are aligned left, numbers right:

```php
use CeusMedia\Common\CLI\Output\Table;

$table	= new Table();
$table->setData( [
	['name' => 'Alice', 'city' => 'Leipzig', 'hits' => 1204],
	['name' => 'Bob', 'city' => 'Berlin', 'hits' => 7],
] );
echo $table->render();
```

```text
┌───────┬─────────┬──────┐
│ name  │ city    │ hits │
├───────┼─────────┼──────┤
│ Alice │ Leipzig │ 1204 │
├───────┼─────────┼──────┤
│ Bob   │ Berlin  │    7 │
└───────┴─────────┴──────┘
```

`setBorderStyle()` takes `Table::BORDER_STYLE_SINGLE` (default), `BORDER_STYLE_DOUBLE`,
`BORDER_STYLE_MIXED` or `BORDER_STYLE_NONE`. `setSizeMode( Table::SIZE_MODE_MAX )` stretches the
columns to 76 characters.

!!! warning "Column widths"
    The column widths are computed from the values only. A key longer than all values of its
    column breaks the borders of the headline.

### Progress bar

[`CLI\Output\Progress`](../api/CLI/Output/Progress.md) draws a bar with counter, ratio and the
estimated time left, using the full width of the terminal:

```php
use CeusMedia\Common\CLI\Output\Progress;

$progress	= new Progress();
$progress->setTotal( count( $items ) );
$progress->start();
foreach( $items as $nr => $item ){
	//  ... do the work
	$progress->update( $nr + 1 );
}
$progress->finish();
```

`update()` starts the bar if needed and ends the line once the total is reached.
`setBarBlocks()` sets the 4 characters for 0, 33, 66 and 100 % of a block, `setBarTemplate()` the
order of the parts (`%1$s` bar, `%2$s` numbers, `%3$s` ratio, `%4$s` time left):

```php
$progress	= ( new Progress() )
	->setBarBlocks( ['.', '-', '=', '#'] )
	->setBarTemplate( '%3$s %1$s %2$s' )
	->setTotal( 3 );
```

### Terminal size

[`CLI\Dimensions`](../api/CLI/Dimensions.md) asks `tput` for the size of the terminal. The values
are cached, `TRUE` as argument detects them again:

```php
use CeusMedia\Common\CLI\Dimensions;

$width	= Dimensions::getWidth();      // eg. 80
$height	= Dimensions::getHeight();     // eg. 24
$colors	= Dimensions::getColors();     // eg. 256
$size	= Dimensions::getSize();       // object with colors, height and width
```

Without a terminal (no `TERM`), `tput` fails and the methods return `0`.

## Input

[`CLI\Question`](../api/CLI/Question.md) asks until the answer is valid. The type decides the
validation, options and ranges are shown behind the question:

```php
use CeusMedia\Common\CLI\Question;

$name	= Question::askStatic( 'Your name', Question::TYPE_STRING, NULL, [], FALSE );
$format	= Question::askStatic( 'Format', Question::TYPE_STRING, 'csv', ['csv', 'json'], FALSE );
//  Format [csv] (csv|json):

$level	= ( new Question( 'Level', Question::TYPE_INTEGER, 3, [], FALSE ) )
	->setRange( 1, 5 )
	->ask();
//  Level [3] (1-5):

$confirm	= Question::askStatic( 'Continue?', Question::TYPE_BOOLEAN, 'y', [], FALSE );
//  Continue? [y] (y:yes|n:no):
```

The last argument `$break` (default: `TRUE`) puts the answer on a new line. `setStrictOptions( FALSE )`
allows answers which are not in the options.

!!! warning "Defaults and answers"
    An empty answer is accepted if a default is set – but `ask()` returns the empty string, not the
    default. Apply the default yourself: `$format = Question::askStatic( … ) ?: 'csv';`.
    Boolean questions return the answer as typed, so `y` or `yes`.

[`CLI\Prompt`](../api/CLI/Prompt.md) reads one line from the terminal (`/dev/tty`, else STDIN),
[`CLI\Password`](../api/CLI/Password.md) reads without echo, using bash (not on Windows):

```php
use CeusMedia\Common\CLI\Password;
use CeusMedia\Common\CLI\Prompt;

$name		= ( new Prompt() )->get( 'Name: ' );
$password	= Password::getInstance( 'Database password: ' )->ask();
```

!!! warning "Special characters in passwords"
    `Password::ask()` passes the input through the shell unquoted: backslashes and leading or
    repeated spaces get lost, and `*` or `?` are expanded to file names. Don't use it for passwords
    which may contain these characters.

## Processes

[`CLI\Command\BackgroundProcess`](../api/CLI/Command/BackgroundProcess.md) starts a shell command
with `nohup` in the background and keeps its process ID (Unix only):

```php
use CeusMedia\Common\CLI\Command\BackgroundProcess;

$process	= BackgroundProcess::newInstance()
	->setCommand( 'php worker.php --queue=mails' )
	->start();
$pid		= $process->getPid();
$running	= $process->getStatus();   // TRUE while the process runs
$process->stop();                      // kill the process
```

Output of the command is discarded. To watch a process started before, set its ID by `setPid()`.

### Forked workers

[`CLI\Fork\Worker\Abstraction`](../api/CLI/Fork/Worker/Abstraction.md) forks a number of child
processes (extension `pcntl`, Unix only). Each child runs `workChild()` and exits with its return
value, the parent runs `workParent()` once per child:

```php
use CeusMedia\Common\CLI\Fork\Worker\Abstraction as WorkerAbstraction;

class ImportWorker extends WorkerAbstraction
{
	protected function workChild( int $pid, int $workerNumber ): int|string
	{
		echo 'Worker '.$workerNumber.' (PID '.getmypid().') started'.PHP_EOL;
		//  ... do the work of this worker
		return 0;
	}

	protected function workParent( int $pid ): int|string
	{
		echo 'Forked child '.$pid.PHP_EOL;      //  PID of the new child
		return 0;
	}
}

( new ImportWorker() )->forkWorkers( 3 );
while( pcntl_wait( $status ) > 0 );            //  wait for all children
```

[`CLI\Fork\Abstraction`](../api/CLI/Fork/Abstraction.md) forks once per call of `fork()`, passing its
arguments to `runInChild()` and `runInParent()`. With `TRUE` as constructor argument the parent
waits for each child.

!!! warning "End the child"
    `CLI\Fork\Abstraction` does not end the child process. Call `exit()` at the end of
    `runInChild()`, otherwise the child continues running the code of the parent.

### Socket servers

[`CLI\Fork\Server\Abstraction`](../api/CLI/Fork/Server/Abstraction.md) is a TCP server forking a
child per connection (extensions `pcntl`, `posix`, `sockets`). The constructor starts the server
right away: it forks into the background, writes the process ID into the file `pid` in the working
directory and ends the calling script. Implement `handleRequest()`, which gets the received data and
returns the answer:

```php
use CeusMedia\Common\CLI\Fork\Server\Abstraction as ForkServer;

class EchoServer extends ForkServer
{
	protected function handleRequest( string $request ): string
	{
		return strtoupper( $request );
	}
}

new EchoServer( 8000 );         // TRUE as 2nd argument kills a running server first
```

The server stops on `SIGTERM` and reopens its socket on `SIGHUP`. Ready to use are
[`Fork\Server\Reflect`](../api/CLI/Fork/Server/Reflect.md) (answers with statistics and the request)
and [`Fork\Server\Dynamic`](../api/CLI/Fork/Server/Dynamic.md) (answers with the return value of a
script set by `setScriptFile()`). Clients extend
[`Fork\Server\Client\Abstraction`](../api/CLI/Fork/Server/Client/Abstraction.md), which sends
`getRequest()` to the port on `127.0.0.1` by its protected method `getResponse()`.

## Daemons and cron jobs

[`CLI\Server\Cron\Daemon`](../api/CLI/Server/Cron/Daemon.md) runs the jobs of a crontab file and
writes their output to a log file:

```text
# minute hour day month weekday command
0-59 * * * * php /path/to/queue.php
30 2 * * * php /path/to/backup.php
0 8 * * 1,3 php /path/to/report.php
```

```php
use CeusMedia\Common\CLI\Server\Cron\Daemon as CronDaemon;

$daemon	= new CronDaemon( 'jobs.crontab', 'cron.log' );
$daemon->serve();           // run jobs due now, once – eg. called by the system cron every minute
$daemon->serve( TRUE );     // run as service: check jobs every minute, endlessly
```

Fields may be `*`, a number, a list (`1,3`) or a range (`1-5`); weekdays count from `0` (Sunday).

!!! warning "Crontab syntax"
    Steps like `*/5` and combinations like `1,3-5` are not supported – such lines are skipped
    silently. The last line of the file must end with a line break, otherwise it is skipped, too.

[`Cron\Parser`](../api/CLI/Server/Cron/Parser.md) reads the file into
[`Cron\Job`](../api/CLI/Server/Cron/Job.md) objects. `Job::checkMaturity()` tells whether a job is
due in the current minute, `execute()` runs it and returns its output.

[`CLI\Server\Daemon`](../api/CLI/Server/Daemon.md) is a base for own daemons: `main()` calls
`serve()` and `sleep()` (1 second) until `$this->shallQuit` is set. The constructor sets a time
limit of 10 seconds by default – pass `0` for unlimited run time:

```php
use CeusMedia\Common\CLI\Server\Daemon;

class QueueDaemon extends Daemon
{
	public function serve(): void
	{
		//  ... handle the next entries of the queue
	}
}

new QueueDaemon( 0 );      // runs until the process is stopped
```

## Other classes

| Class | Purpose |
|-------|---------|
| [`CLI\Downloader`](../api/CLI/Downloader.md) | downloads a URL into a folder, showing the progress |
| [`CLI\Exception\View`](../api/CLI/Exception/View.md) | renders an exception with trace and previous exceptions as plain text, see [Core](core.md) |
| [`CLI\Shell`](../api/CLI/Shell.md) | interactive PHP shell: evaluates entered lines and prints the result |
| [`CLI\Output\TableBorderTheme`](../api/CLI/Output/TableBorderTheme.md) | border characters of a table style |
| [`CLI\Fork\Server\Exception`](../api/CLI/Fork/Server/Exception.md), [`SocketException`](../api/CLI/Fork/Server/SocketException.md) | errors of the socket server |

`CLI::ls()` lists a folder with sizes, rights and dates, `CLI::charTable()` prints a range of
Unicode characters.

!!! warning "Downloader in 1.1.1"
    If the server sends a `Content-Length`, `CLI\Downloader` writes into a temporary file with a
    wrong path and the file is not saved. Use [`Net\Reader`](../api/Net/Reader.md) or
    [`Net\CURL`](../api/Net/CURL.md) instead, see [Network](network.md).
