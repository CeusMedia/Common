# User interface

The `UI` namespace builds output: HTML tags, form elements and pages, images (resize, filter,
watermark, captcha), SVG charts and readable dumps of variables for development.

Everything here returns strings or writes files – there is no rendering engine and no state
between requests. Most classes are small helpers; the ones used every day are
[`UI\HTML\Tag`](../api/UI/HTML/Tag.md), [`UI\HTML\Elements`](../api/UI/HTML/Elements.md),
[`UI\HTML\PageFrame`](../api/UI/HTML/PageFrame.md), [`UI\Image`](../api/UI/Image.md) with
[`UI\Image\Processing`](../api/UI/Image/Processing.md) and the development output `print_m()`.

!!! warning "What is escaped – and what is not"
    As a rule of thumb: **attribute values are escaped, content is not.**
    `Tag` encodes all attribute values; tag content, labels, titles and table cells are put
    into HTML as given, so they may contain markup. Escape user input yourself, e.g. with
    `htmlspecialchars()`, before passing it as content. Details are given per class below.
    Release 1.1.1 fixed many places where values were put unescaped into HTML, SVG or JavaScript,
    see the [changelog](../changelog.md).

## HTML tags

[`Tag`](../api/UI/HTML/Tag.md) builds a single element – statically by `create()` or as object:

```php
use CeusMedia\Common\UI\HTML\Tag;

echo Tag::create( 'a', 'Docs', ['href' => '/docs?a=1&b=2', 'class' => ['btn', 'btn-link']] );
// <a href="/docs?a=1&amp;b=2" class="btn btn-link">Docs</a>

echo Tag::create( 'p', '<b>bold</b>', ['style' => ['color' => 'red', 'margin' => 0]] );
// <p style="color: red; margin: 0"><b>bold</b></p>

echo Tag::create( 'ul', [Tag::create( 'li', 'a' ), Tag::create( 'li', 'b' )] );
// <ul><li>a</li><li>b</li></ul>

echo Tag::create( 'p', 'x', [], ['userId' => 12, 'mode' => 'edit'] );
// <p data-user-id="12" data-mode="edit">x</p>

echo Tag::create( 'br' );       // <br/>
echo Tag::create( 'div' );      // <div></div>
```

| Argument | Rules |
|----------|-------|
| `$name` | lowercased; required |
| `$content` | `NULL` (or `FALSE`) gives a short tag like `<br/>` – except for `style`, `script`, `div` and `textarea`; arrays are joined (nested arrays, too); numbers and objects with `__toString()` are cast; **not escaped** |
| `$attributes` | keys are lowercased and must match `[a-z][a-z0-9.:_-]*`; values are encoded by `htmlentities()` without double encoding (existing entities like `&amp;` are kept); arrays are joined by space, for `style` as `key: value` pairs; `NULL`, `FALSE` **and `TRUE`** are skipped – write `'disabled' => 'disabled'` |
| `$data` | rendered as `data-*` attributes, camel case keys become kebab case |

The object form allows building a tag step by step. Setting an attribute twice throws a
`RuntimeException`, unless `$strict` is `FALSE`:

```php
$button	= new Tag( 'button', 'Save', ['type' => 'submit'] );
$button->setAttribute( 'class', 'btn' );
$button->setAttribute( 'class', 'btn-primary', FALSE );     // override
echo $button;
// <button type="submit" class="btn-primary">Save</button>
```

`Tag` implements `Renderable`, so `render()`, `build()` and string casting give the same result.

!!! bug "Data attributes on tag objects"
    `setData()` – and therefore the `$data` argument of the **constructor** – stores the value
    as plain attribute: `$tag->setData( 'action', 'save' )` renders `action="save"`, not
    `data-action="save"`. Use the static `Tag::create()` for data attributes or set
    `data-action` by `setAttribute()`.

## Elements and forms

[`Elements`](../api/UI/HTML/Elements.md) extends [`FormElements`](../api/UI/HTML/FormElements.md)
and has static builders for links, images, lists and form fields. Most of them use `Tag`, so their
attribute values are escaped:

```php
use CeusMedia\Common\UI\HTML\Elements;

echo Elements::Link( './?page=2&sort=name', 'Next page', 'btn', NULL, 'Really?' );
// <a href="./?page=2&amp;sort=name" class="btn" onclick="return confirm(&quot;Really?&quot;)">Next page</a>

echo Elements::Image( 'logo.png', 'Logo', 'logo', 120, 40 );
// <img src="logo.png" class="logo" width="120" height="40" alt="Logo" title="Logo" hspace="0" vspace="0"/>

echo Elements::unorderedList( [Elements::ListItem( 'One' ), Elements::ListItem( 'Two' )] );
echo Elements::Heading( 'Title', 2 );          // <h2>Title</h2>
```

```php
echo Elements::Form( 'search', './search' );   // opening tag only: <form id="form_search" ... method="post">
echo Elements::Label( 'query', 'Search' );
echo Elements::Input( 'query', '"Tom & Jerry"', 'input-xl' );
// <input id="query" type="text" name="query" value="&quot;Tom &amp; Jerry&quot;" class="input-xl"/>
echo Elements::Select( 'country', ['de' => 'Germany', 'at' => 'Austria', '_selected' => 'at'] );
// <select id="country" name="country"><option value="de">Germany</option><option value="at" selected="selected">Austria</option></select>
echo Elements::Checkbox( 'newsletter', 1, TRUE );
echo Elements::HiddenField( 'id', 42 );
echo Elements::Button( 'save', 'Save', 'btn-primary' );
echo '</form>';
```

Good to know:

- Fields get the name as `id`, too. A select named `tags[]` becomes `multiple`.
- `Options()` and `Select()` take a map of value → label; the key `_selected` selects a value
  (or several, as array). Nested arrays become `<optgroup>`s. **Option labels are escaped.**
- The `$readOnly` argument of fields accepts `TRUE` or a message, which is shown by `alert()` on click.
- **Not escaped:** link texts, labels, button labels, `Textarea()` content and `MailLink()` labels.
  Confirmation texts of `Button()`, `LinkButton()` and `ResetButton()`, read-only messages and
  the URL of `LinkButton()` are put into JavaScript strings without JavaScript encoding – pass
  only fixed texts there. `Link()` encodes its confirmation text properly.
- Methods building old-style table layouts (`FieldCell()`, `LabelCell()`, `CheckTable()`,
  `FoldingArea()`, `ToolTip()`, …) concatenate HTML without any escaping.

## Pages

[`PageFrame`](../api/UI/HTML/PageFrame.md) builds a complete document with doctype, meta tags,
title, stylesheets and scripts. The default doctype is XHTML 1.0 Strict – pass `HTML_5` for HTML5:

```php
use CeusMedia\Common\UI\HTML\PageFrame;
use CeusMedia\Common\UI\HTML\Tag;

$page	= new PageFrame( 'HTML_5', 'en' );
$page->setTitle( 'Products' );
$page->setTitle( 'My Shop', 'append' );        // "Products | My Shop"
$page->addMetaTag( 'name', 'viewport', 'width=device-width, initial-scale=1' );
$page->addStylesheet( 'css/style.css' );
$page->addJavaScript( 'js/app.js' );
$page->addFavouriteIcon( 'favicon.png' );
$page->setCanonicalLink( 'https://shop.example.com/products' );
$page->addBody( Tag::create( 'h1', 'Products' ) );
$page->addBody( Tag::create( 'p', 'Welcome!' ) );
echo $page->build( ['class' => 'page-products'] );
```

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html"/>
    <meta http-equiv="Content-Script-Type" content="text/javascript"/>
    <meta http-equiv="Content-Style-Type" content="text/css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Products | My Shop</title>
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css"/>
    <link rel="icon" type="image/png" href="favicon.png"/>
    <link href="https://shop.example.com/products" rel="canonical"/>
    <script type="text/javascript" src="js/app.js"></script>
  </head>
  <body class="page-products">
    <h1>Products</h1>
    <p>Welcome!</p>
  </body>
</html>
```

Further: `addHead()` and `addScript()` add raw code to the head, `setBody()` replaces the body,
`setBaseHref()`, `addLink()`, `addPrefix()` (RDFa) and `setHeading()` (an `<h1>` before the body).
Body and head parts may be strings or `Renderable` objects. URLs may be given as
[`ADT\URL`](../api/ADT/URL.md). Other doctypes: `XHTML_11`, `XHTML_10_TRANSITIONAL`,
`XHTML_10_FRAMESET`, `HTML_401_STRICT`, `HTML_401_TRANSITIONAL`, `HTML_401_FRAMESET`.

!!! note
    URLs and meta values are attributes and therefore escaped. Title and heading are content
    and are **not** escaped.

## Pagination, tables and tabs

=== "Pagination"

    [`Pagination`](../api/UI/HTML/Pagination.md) renders a list of page links for `$amount` items,
    `$limit` per page, starting at `$offset`. It returns an empty string if everything fits on one page.

    ```php
    use CeusMedia\Common\UI\HTML\Pagination;

    $pagination	= new Pagination( [
    	'uri'		=> './products',
    	'param'		=> ['q' => 'red shoes'],
    	'coverage'	=> 2,
    ] );
    echo $pagination->build( 95, 10, 30 );
    ```

    ```html
    <ul class="pagination buttons">
      <li><a href="./products?q=red%20shoes&amp;offset=0" class="extreme">&laquo;</a></li>
      <li><a href="./products?q=red%20shoes&amp;offset=20" class="skip">&lsaquo;</a></li>
      <li><span class="more">&minus;</span></li>
      <li><a href="./products?q=red%20shoes&amp;offset=10">2</a></li>
      <li><a href="./products?q=red%20shoes&amp;offset=20">3</a></li>
      <li><span class="current">4</span></li>
      <li><a href="./products?q=red%20shoes&amp;offset=40">5</a></li>
      <li><a href="./products?q=red%20shoes&amp;offset=50">6</a></li>
      <li><span class="more">&minus;</span></li>
      <li><a href="./products?q=red%20shoes&amp;offset=40" class="skip">&rsaquo;</a></li>
      <li><a href="./products?q=red%20shoes&amp;offset=90" class="extreme">&raquo;</a></li>
    </ul>
    ```

    Parameters are URL encoded. Options: `keyOffset` (default `offset`), `keyRequest`, `keyParam`,
    `keyAssign`, `showFirstLast`, `showPrevNext`, `showMore`, CSS classes `class*` and labels `text*`
    (HTML, not escaped).

=== "Table"

    [`Table`](../api/UI/HTML/Table.md) collects heads, rows and cells. Labels are HTML – escape data:

    ```php
    use CeusMedia\Common\UI\HTML\Table;

    $table	= new Table( ['class' => 'table', 'caption' => 'Orders'] );
    $table->addHeads( ['ID', 'Customer', 'Total' => ['class' => 'num']] );
    foreach( $orders as $order ){
    	$table->addRow();
    	$table->addCell( (string) $order['id'] );
    	$table->addCell( htmlspecialchars( $order['customer'] ) );
    	$table->addCell( number_format( $order['total'], 2 ), ['class' => 'num'] );
    }
    echo $table->render( NULL );
    ```

    `addHeads()` and `addRow()` take a list of labels or label → attributes pairs; numeric labels
    like `'0'` become integer keys there and are rejected, so use `addCell()` for data.
    `render()` wraps the table in HTML comments with the given text (default `TEST`) – pass `NULL`
    to leave them out. An empty `<tfoot>` is always rendered.

=== "Tabs"

    [`Tabs`](../api/UI/HTML/Tabs.md) builds the markup for a jQuery tabs plugin and the script to apply it:

    ```php
    use CeusMedia\Common\UI\HTML\Tabs;

    $tabs	= new Tabs();
    $tabs->addTab( 'Details', '<p>Details …</p>', 'details' );
    $tabs->addTab( 'Reviews', '<p>Reviews …</p>', 'reviews' );
    echo $tabs->buildTabs( 'product-tabs' );
    echo $tabs->buildScript( '#product-tabs' );
    // jQuery(document).ready(function(){
    //   jQuery("#product-tabs").tabs({"navClass":"tabs-nav"}).show();
    // });
    ```

    Labels and contents are not escaped. `Tabs::$version` (or `setVersion()`) sets the plugin
    version (default `2`); below version 3, links are prefixed with the server variable `REDIRECT_URL`.

[`JQuery::buildPluginCall()`](../api/UI/HTML/JQuery.md) creates such ready-calls for any plugin.
Selector and options are JSON encoded for script blocks, so they cannot end the script.

## Exception views

For development and internal tools, [`Exception\View`](../api/UI/HTML/Exception/View.md) renders
an exception with message, code, type, file, line, trace and previous exceptions as `<dl>`;
[`Exception\Page`](../api/UI/HTML/Exception/Page.md) wraps it into a complete page:

```php
use CeusMedia\Common\UI\HTML\Exception\Page as ExceptionPage;
use CeusMedia\Common\UI\HTML\Exception\View as ExceptionView;

try{
	$app->run();
}
catch( Throwable $e ){
	echo ExceptionView::render( $e );            // HTML fragment
	ExceptionPage::display( $e, 1 );             // full page, then exit with code 1
}
```

Message, description, suggestion and additional properties are escaped. File paths are shortened
by the document root, if there is one. The page loads jQuery 1.4.2, a plugin and Bootstrap CSS from
`cdn.ceusmedia.de`.

!!! danger "Not for production"
    Traces show file paths and argument values. Show exception views to developers only.

## Images

Image classes need the PHP extension **GD** and support GIF, JPEG and PNG.
[`UI\Image`](../api/UI/Image.md) holds an image; [`Processing`](../api/UI/Image/Processing.md) and
[`Filter`](../api/UI/Image/Filter.md) change it in place:

```php
use CeusMedia\Common\UI\Image;
use CeusMedia\Common\UI\Image\Filter;
use CeusMedia\Common\UI\Image\Processing;

$image	= new Image( 'photo.jpg' );
echo $image->getWidth(), 'x', $image->getHeight();      // 440x440
echo $image->getMimeType();                              // image/jpeg

$processor	= new Processing( $image );
$processor->scaleDownToLimit( 100, 100 );                // 100x100, keeps ratio
$processor->crop( 10, 10, 50, 50 );                      // x, y, width, height
$processor->rotate( 90 );                                // clockwise
$processor->flip();                                      // vertically; flip( 1 ): horizontally
$processor->scale( 0.5 );                                // 25x25

$filter	= new Filter( $image );
$filter->grayscale();
$filter->contrast( -20 );
$filter->gamma( 1.2 );

$image->setQuality( 85 );                                // JPEG quality
$image->save( 'photo-small.jpg' );
$image->setType( IMAGETYPE_PNG );                        // file name becomes ./photo.png
$image->save();
```

| `Processing` method | Purpose |
|---------------------|---------|
| `resize( $width, $height )` | exact size |
| `scale( $factor, $factorY = NULL )` | by factor, e.g. `0.5` |
| `scaleDownToLimit( $width, $height )` | shrink to fit, keep ratio; `FALSE` if already small enough |
| `scaleUpToLimit( $width, $height )` | enlarge to fit, keep ratio |
| `scaleToRange( $minW, $minH, $maxW, $maxH )` | one of both above, if needed |
| `crop( $x, $y, $width, $height )` | cut out |
| `rotate( $angle, $bgColor = NULL )` | rotate clockwise; background defaults to the transparent color |
| `flip( $mode = 0 )` | `0`: vertically, `1`: horizontally |

All resizing methods return `FALSE` if nothing changed and take `$interpolate` (default `TRUE`).
Set `maxMegaPixels` (constructor or property) to refuse oversized targets.
`Filter` offers `blurGaussian()`, `blurSelective()`, `brightness()`, `colorize()`, `contrast()`,
`detectEdges()`, `emboss()`, `gamma()`, `grayscale()`, `negate()`, `pixelate()`, `removeMean()` and `smooth()`.

More on `Image`:

- `create( $width, $height )` starts a new true color image (PNG); `getResource()` gives the
  `GdImage` for own GD calls; `setResource()` replaces it.
- `render()` returns the binary image, `display()` sends it with `Content-type` header.
- Animated GIFs are rejected, unless `$tolerateAnimatedGif` is `TRUE` (only the first frame is kept).
- `getFileName()` is `NULL` for created images, so `save()` needs a file name then.

### Thumbnails, watermarks, rotation

These classes work on files directly – source in, target out:

```php
use CeusMedia\Common\UI\Image\Rotator;
use CeusMedia\Common\UI\Image\ThumbnailCreator;
use CeusMedia\Common\UI\Image\Watermark;

$creator	= new ThumbnailCreator( 'photo.jpg', 'thumb.jpg', 80 );   // JPEG quality 80
$creator->thumbizeByLimit( 160, 120 );     // fit into 160x120, keep ratio: 120x120
$creator->thumbize( 64, 64 );              // exact size

$watermark	= new Watermark( 'logo.png', 60 );                     // stamp image, opacity 60%
$watermark->setPosition( 'right', 'bottom' );                       // left|center|right, top|middle|bottom
$watermark->setMargin( 10, 10 );
$watermark->markImage( 'photo.jpg', 'photo-marked.jpg' );           // without target: overwrites source

$rotator	= new Rotator( 'photo.jpg', 'photo-rotated.jpg' );
$rotator->rotate( 90 );                    // counter-clockwise, keeps transparency
Rotator::rotateImage( 'photo.jpg', 90 );   // in place
```

!!! note "Rotation direction"
    `Processing::rotate()` turns **clockwise**, `Rotator::rotate()` passes the angle to GD's
    `imagerotate()` and turns **counter-clockwise**.

### Captcha

[`Captcha`](../api/UI/Image/Captcha.md) creates a random word and a JPEG with rotated letters.
A TrueType font file is required:

```php
use CeusMedia\Common\UI\Image\Captcha;

$captcha	= new Captcha();
$captcha->font		= __DIR__.'/fonts/font.ttf';
$captcha->length	= 5;
$captcha->width		= 120;
$captcha->useDigits	= TRUE;
$word	= $captcha->generate( 'captcha.jpg' );     // e.g. "raf5g", store it in the session
$jpeg	= $captcha->generateImage( $word );        // or get the JPEG as string
```

Options are public properties: `useSmalls` (default), `useLarges`, `useDigits`, `unique`, `length`,
`fontSize`, `width`, `height`, `angle`, `offsetX`, `offsetY`, `textColor`, `background`, `quality`.
Letters are placed 20 pixels apart, so the width should be at least `length * 20 + 20`.

### EXIF and histogram

```php
use CeusMedia\Common\UI\Image;
use CeusMedia\Common\UI\Image\Exif;
use CeusMedia\Common\UI\Image\Histogram;

$exif	= new Exif( 'photo.jpg' );                      // needs extension exif
echo $exif->get( 'COMPUTED.Width' );                    // 440 – nested values with dot
echo $exif->get( 'Model', 'unknown' );                  // camera model or default
$computed	= $exif->getAll( 'COMPUTED.' );             // ['html' => ..., 'Height' => 440, ...]
$thumbnail	= $exif->getThumbnailImage();               // <img> with data URI, exception if none

$data		= Histogram::getData( new Image( 'photo.jpg' ) );   // ['w' => [256 counts], 'r' => ..., 'g' => ..., 'b' => ...]
$histogram	= Histogram::drawChannels( $data );                 // Image, 256x100
$histogram->save( 'histogram.png' );
```

### Graphviz

[`Graphviz\Graph`](../api/UI/Image/Graphviz/Graph.md) builds a graph in DOT language;
[`Graphviz\Renderer`](../api/UI/Image/Graphviz/Renderer.md) calls the Graphviz binaries
(`dot`, `neato`, …), which must be installed:

```php
use CeusMedia\Common\UI\Image\Graphviz\Graph;
use CeusMedia\Common\UI\Image\Graphviz\Renderer;

$graph	= new Graph( 'deps', ['rankdir' => 'LR'] );     // always give an ID
$graph->addNode( 'app', ['shape' => 'box'] );
$graph->addNode( 'common lib', ['label' => 'Common\n1.1.1'] );
$graph->addEdge( 'app', 'common lib', ['label' => 'uses'] );
echo $graph->render();
// digraph deps {
// 	rankdir="LR"
// 	app [shape="box" label="app"];
// 	commonlib [label="Common\n1.1.1"];
// 	app -> commonlib [label="uses"]
// }

$renderer	= new Renderer( $graph, 'dot' );
$renderer->saveAsImage( 'deps.png', 'png' );
$map		= $renderer->getMap( 'cmapx' );            // client-side image map
```

Node names are reduced to word characters (`common lib` → `commonlib`), the original name
becomes the default label. `setType( 'graph' )` creates an undirected graph (edges `--`).

## SVG charts

[`SVG\Chart`](../api/UI/SVG/Chart.md) draws pie and bar charts from
[`SVG\ChartData`](../api/UI/SVG/ChartData.md) objects (value and label). Labels are escaped.

```php
use CeusMedia\Common\UI\SVG\Chart;
use CeusMedia\Common\UI\SVG\ChartData;

$chart	= new Chart( [
	new ChartData( 120, 'Books' ),
	new ChartData( 60, 'Music & Film' ),
	new ChartData( 20, 'Games' ),
], ['#4E79A7', '#F28E2B', '#59A14F'] );                 // colors, default: red, yellow, blue, orange
$chart->buildPieGraph( ['cx' => 150, 'cy' => 150, 'r' => 100, 'legend' => ['x' => 300, 'y' => 50]] );
$chart->save( 'chart.svg' );                            // complete SVG file
$svg	= $chart->encapsulate( $chart->getContent(), 450, 300 );    // SVG document as string
```

`buildBarAcross( ['x' => 50, 'y' => 80] )` draws horizontal bars instead (length in pixels = percentage).

!!! bug "Known issues"
    Each `build…()` call puts the drawn graph (and legend) into the content **twice**, and PHP 8.2+
    reports deprecations for dynamic properties. Bars of `buildBarAcross()` use SVG filters, whose
    names are lowercased by `Tag` and therefore ignored by browsers.

## Development output

The global functions from `src/global.php` (loaded by Composer) print variables readably –
as text on the console, as HTML otherwise. They use [`UI\DevOutput`](../api/UI/DevOutput.md):

```php
$data	= ['id' => 12, 'name' => 'Anna', 'active' => TRUE, 'tags' => ['a', 'b'], 'score' => 1.5, 'note' => NULL];
print_m( $data );
//   [I] id => 12
//   [S] name => Anna
//   [B] active => TRUE
//   [A] tags
//     [S] 0 => a
//     [S] 1 => b
//   [F] score => 1.5
//   [N] note => NULL

$html	= print_m( $data, NULL, NULL, TRUE, 'html' );   // return instead of print, force HTML
remark( 'Saved', ['id' => 12] );                       // Saved: id -> 12
print_j( ['a' => 1] );                                 // pretty printed JSON
pre( $text );                                          // escaped in <pre>
```

| Function | Purpose |
|----------|---------|
| `print_m( $mixed, $sign, $factor, $return, $channel )` | structured dump with types |
| `print_j( $mixed, $sign, $factor, $return )` | pretty JSON |
| `remark( $text, $parameters, $break )` | line with parameters |
| `pre( $string, $dump )` | escaped text in `<pre>` |
| `xmp( $string, $dump )` | text in `<xmp>` (not escaped) |
| `show()`, `print_globals()`, `showDOM()` | shortcuts |

The channel is detected by the environment variables `SHELL` or `PROMPT` (text, else HTML) and can be
set by `DevOutput::setDefaultChannel()`. In HTML, string values are escaped – keys, JSON and remarks are not.

[`VariableDumper::dump()`](../api/UI/VariableDumper.md) returns `var_dump()` output if Xdebug is
loaded and `print_m()` style otherwise. [`Tree\VariableDump`](../api/UI/HTML/Tree/VariableDump.md)
renders a variable as foldable HTML tree.

### Output buffer

[`OutputBuffer`](../api/UI/OutputBuffer.md) wraps `ob_start()` and friends:

```php
use CeusMedia\Common\UI\OutputBuffer;

$buffer	= new OutputBuffer();           // opened directly
include 'view.php';
$html	= $buffer->getAndClose();       // or get(), clear(), flush(), flushAndClose()
```

`has()` tells whether anything was captured; opening an open buffer or reading a closed one throws a
`RuntimeException`.

## Templates

[`UI\Template`](../api/UI/Template.md) is **deprecated** and only a stub: its constructor throws a
`Deprecation` exception. Use the separate package
[CeusMedia TemplateEngine](https://github.com/CeusMedia/TemplateEngine) instead.

## Further classes

??? info "HTML components (mostly for own jQuery plugins)"

    | Class | Purpose |
    |-------|---------|
    | [`Paging`](../api/UI/HTML/Paging.md) | older, option based variant of `Pagination` |
    | [`Panel`](../api/UI/HTML/Panel.md), [`CollapsePanel`](../api/UI/HTML/CollapsePanel.md) | box with header, abstract, content and footer |
    | [`Indicator`](../api/UI/HTML/Indicator.md) | progress bar with color by ratio |
    | [`Index`](../api/UI/HTML/Index.md) | table of contents from headings in HTML |
    | [`Options`](../api/UI/HTML/Options.md) | select with options loaded by AJAX (jQuery plugin `ajaxOptions`) |
    | [`Ladder`](../api/UI/HTML/Ladder.md), [`ContextMenu`](../api/UI/HTML/ContextMenu.md) | step list, context menu |
    | [`Tree\ArrayView`](../api/UI/HTML/Tree/ArrayView.md), [`Tree\FolderView`](../api/UI/HTML/Tree/FolderView.md) | nested lists for jQuery Treeview |
    | [`Tree\FolderCheckView`](../api/UI/HTML/Tree/FolderCheckView.md) | folder tree with checkboxes |
    | [`Tree\Menu`](../api/UI/HTML/Tree/Menu.md), [`Tree\LayerMenu`](../api/UI/HTML/Tree/LayerMenu.md) | menus from OPML or menu collections |
    | [`Exception\Trace`](../api/UI/HTML/Exception/Trace.md), [`Exception\TraceViewer`](../api/UI/HTML/Exception/TraceViewer.md) | stack trace as HTML |
    | [`JS\CodeMirror`](../api/UI/JS/CodeMirror.md) | script to turn textareas into CodeMirror editors |

??? info "More image classes"

    | Class | Purpose |
    |-------|---------|
    | [`Creator`](../api/UI/Image/Creator.md) | create or load a `GdImage` |
    | [`Drawer`](../api/UI/Image/Drawer.md) | lines, rectangles, strings on a `GdImage` |
    | [`Printer`](../api/UI/Image/Printer.md) | save or send a `GdImage` |
    | [`Modifier`](../api/UI/Image/Modifier.md) | base class of file based modifiers like `Rotator` |
    | [`TransparentWatermark`](../api/UI/Image/TransparentWatermark.md) | watermark with transparent, randomized effect |
    | [`Error`](../api/UI/Image/Error.md) | sends an image showing an error message |

[`UI\Text`](../api/UI/Text.md) has small text helpers: `line()`, `pad()`, `padLeft()`, `padRight()`,
`formatBytes()` and `char()`.
