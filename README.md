# Tokamak

## Introduction

Tokamak is a PHP library for programmatic DOM templating. It provides an abstraction over PHP's DOMDocument API, presenting a simple DSL for defining both templates and reusable components. This interface emphasizes unidirectional, top-down data flow and is (loosely) inspired by React.js.

Note: Tokamak is in the early stages of development and is not yet suitable for production usage. However, if its approach interests you, please do try it out and provide feedback.

## Usage

### Documents
You can create an HTML template by extending Tokamak's HTMLClass and providing an implementation of the render method:

```PHP
<?php
use Tokamak\Dom\HTMLDocument;

class SimpleTemplate extends HTMLDocument
{

	protected function render()
	{
		$html = $this->appendElement('html');
	        $head = $html->appendElement('head');
				$head->appendElement('title', null, $this->data['title']); // data passed in via constructor
			$h1 = $html->appendElement('body')
                            ->appendElement('h1', null, 'Test Document'); // supports method chaining

	}

}
```

Then, you can pass in data/state when constructing an instance, and output it to an HTML string:

```PHP
<?php
$t = new SimpleTemplate(array('title' => 'Example Document');
echo $t->toString();
```

Which will result in the following HTML being output:

```HTML
<!DOCTYPE html>
<html>
<head>
<title>Example</title>
<meta charset="UTF-8">
</head>
<body><h1>Test Document</h1></body>
</html>
```

### Components

In addition to document templates, Tokamak lets you create custom components. Components are reusable pieces of
DOM that can be used to implement widgets and page partials. Like documents, their state/data is passed to them by
their parent elements.

Much like a Document, you create a component by extending Tokamak's Component class and implementing the internal render method:

```PHP
<?php
use Tokamak\Dom\Component

class Head extends Component {
	protected function render(){
		$head = $this->appendElement('head');
		$head->appendElement('title', null, $this->data['title']);
		$head->appendElement('meta', array('charset' => $this->dom->encoding));
	}
}
```

You can append a component class to a Document or to another Component via the `appendComponent` method:

```PHP
use Tokamak\Dom\HTMLDocument;

class DocumentWithComponent extends HTMLDocument
{

	protected function render()
	{
		$body = $this->appendElement('html')
			    ->appendComponent('Head', $this->data)
				->appendElement('body', null);
					$body->appendElement('h1', null, 'This one has a component.');

	}

}
```

Now, we've abstracted the `<head>` element as a reusable component. Rendering the document gives us the following:
 
```HTML
<!DOCTYPE html>
<html>
<head>
<title>Example</title>
<meta charset="UTF-8">
</head>
<body><h1>Test Document</h1></body>
</html>
```

### Limitations/Planned Changes

* The current method-chaining/fluent interface always returns the last element or component in the chain. This is somewhat inconvenient, as it requires the use of intermediate variables. A future revision may implement the DSL using closures, similar to how React does it.