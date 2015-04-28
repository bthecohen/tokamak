# Tokamak

## Introduction

Tokamak is a PHP library for programmatic DOM templating. It provides an abstraction over PHP's DOMDocument API, presenting a simple DSL for defining both templates and reusable components. This interface emphasizes unidirectional, top-down data flow and is (loosely) inspired by React.js. Unlike React, this library is used for stateless server-side rendering; therefore, elements in Tokamak are stateless, and are only rendered once.

Note: Tokamak is in the early stages of development and is not yet suitable for production usage. However, if its approach interests you, please do try it out and provide feedback.

## Usage

### Documents
You can create an HTML template by extending Tokamak's HTMLClass and providing an implementation of the render method:

```PHP
<?php
use Tokamak\Dom\HTMLDocument;

class SimpleTemplate extends HTMLDocument
{

	protected function render($data)
	{
		$html = $this->appendElement('html');
	        $head = $html->appendElement('head');
				$head->appendElement('title', null, $data['title']); // data passed in via constructor
			$h1 = $html->appendElement('body')
                            ->appendElement('h1', null, 'Test Document'); // supports method chaining

	}

}
```

Then, you can pass in data when constructing an instance, and output it to an HTML string:

```PHP
<?php
$t = new SimpleTemplate(array('title' => 'Example');
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
DOM that can be used to implement widgets and page partials. Like documents, their data is passed to them by
their parent elements.

Much like a Document, you create a component by extending Tokamak's Component class and implementing the internal render method:

```PHP
<?php
use Tokamak\Dom\Component

class Head extends Component {
	protected function render($data){
		$head = $this->appendElement('head');
		$head->appendElement('title', null, $data['title']);
		$head->appendElement('meta', array('charset' => $this->dom->encoding));
	}
}
```

You can append a component class to a Document or to another Component via the `appendComponent` method:

```PHP
use Tokamak\Dom\HTMLDocument;

class DocumentWithComponent extends HTMLDocument
{

	protected function render($data)
	{
		$body = $this->appendElement('html')
			    ->appendComponent('Head', $data)
				->appendElement('body', null);
					$body->appendElement('h1', null, 'Test Document');

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

### Closures

In addition to method chaining, Tokamak supports using closures as callbacks in order to append multiple levels of elements with one method call. This is useful for adding multiple nodes to a child node, and obviates the need for assigning nodes to intermediate variables. The following example produces the same output as the above template:

```PHP
<?php

use Tokamak\Dom\HTMLDocument;

class ExampleDocumentWithClosures extends HTMLDocument
{
	protected function render($data)
	{
		$data = $this->data;
		$this->appendElement('html', null, '', $data, function($data) {
			$this->appendComponent('Head', $data);
		})
		->appendElement('body', null, '', null, function(){
			$this->appendElement('h1', null, 'Test Document');
		});;

	}
}
```

Within the closure, `$this` will be correctly bound to the child element, allowing you to easily construct a DOM hierarchy. Any data to be passed into the closure is included in the fourth argument. You could also import a local variable into the closure's scope by using the syntax: `function() use ($foo){}`.

## Installation

Install via Composer, by adding a dependency on `tokamak/tokamak` to your composer.json file or by running

```
composer require tokamak/tokamak
```

on the command line in your project directory.

## Documentation

Read the API documentation [here.](http://bthecohen.github.io/tokamak/documentation/api/)

## Limitations/Planned Features

* I plan to implement selector methods to access descendant nodes.
