# Guide

CeusMedia Common is a collection of independent tools. The namespaces group them by task – pick the one
of your task and start there:

| Namespace | Content | Guide |
|-----------|---------|-------|
| `FS` | files and folders, INI, CSV, JSON, YAML, logs, archives, backups, locks, iCal and vCard files | [File system](filesystem.md) |
| `Net` | reading URLs, HTTP requests and responses, downloads, cookies, FTP, crawler and sitemaps, API clients | [Network](network.md) |
| `XML` | DOM nodes, parser and builder, XPath, RSS and Atom feeds, OPML, XML-RPC, XSLT | [XML](xml.md) |
| `UI` | HTML tags and elements, page frames, templates, images, SVG charts, debug output | [User interface](ui.md) |
| `CLI` | arguments, output, questions, forked workers, daemons and cron jobs | [CLI](cli.md) |
| `ADT` | collections, registries, bitmasks, trees, graphs, URLs, JSON, time, events | [Data types](data-types.md) |
| `Alg` | text tools, sorting and searching, colors, durations, units, validation | [Algorithms](algorithms.md) |
| root, `Exception` | environment, autoloader, deprecations, exceptions, global functions | [Core](core.md) |

All classes are listed with their methods in the [API reference](../api/index.md).

## Conventions

- **Namespaces** follow the folders in `src/`: `CeusMedia\Common\FS\File\INI\Reader` is
  `src/FS/File/INI/Reader.php`.
- **Strict types:** all classes declare `strict_types=1` and use native types. Pass values of the
  declared type – a numeric string where an `int` is expected raises a `TypeError`.
- **Reader, Writer, Editor:** many file formats come in three flavours – a reader for reading,
  a writer for writing and an editor for changing existing files.
- **Static shortcuts:** many classes offer static methods for one-off tasks besides their objects,
  e.g. `Net\Reader::readUrl()` or `FS\File\Reader::load()`.
- **Fluent setters:** setters usually return the object itself, so calls can be chained.
- **Reserved words:** classes named like PHP keywords get a trailing underscore, like `ADT\Null_`,
  `ADT\Object_` and `ADT\String_`.
