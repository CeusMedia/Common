```php
use CeusMedia\Common\UI\HTML\Tag;

echo Tag::create( 'a', 'Docs', ['href' => '/docs?a=1&b=2', 'class' => ['btn', 'btn-link']] );
// <a href="/docs?a=1&amp;b=2" class="btn btn-link">Docs</a>

echo Tag::create( 'p', htmlspecialchars( $userInput ), ['style' => ['color' => 'red']] );   // content is not escaped
echo Tag::create( 'p', 'x', [], ['userId' => 12] );     // <p data-user-id="12">x</p>
echo Tag::create( 'br' );                                // <br/>

$button	= new Tag( 'button', 'Save', ['type' => 'submit'] );
$button->setAttribute( 'class', 'btn-primary' );
echo $button;                                            // <button type="submit" class="btn-primary">Save</button>
```

See guide: [User interface](../../../guide/ui.md)
