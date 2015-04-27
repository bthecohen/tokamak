<?php
include_once(__DIR__ . '/../vendor/autoload.php');
include_once(__DIR__ . '/classes/EmptyTestDocument.php');
include_once(__DIR__ . '/classes/TestDocument.php');
include_once(__DIR__ . '/classes/TestDocumentClosures.php');
include_once(__DIR__ . '/classes/TestComponent.php');


/**
 * Class HTMLDocumentTest
 * Test cases for testing a normal use case incorporating documents, elements, components, etc.
 */
class DocumentTest extends PHPUnit_Framework_TestCase {

	public static $expectedHTML =
<<<HTML
<!DOCTYPE html>
<html>
<head>
<title>Example</title>
<meta charset="UTF-8">
</head>
<body><h1>Test Document</h1></body>
</html>

HTML;

	/**
	 * Test that the constructor for HTMLDocument returns an object
	 */
	public function testConstructor(){
		$template = new EmptyTestDocument();
		$this->assertInstanceOf('Tokamak\Dom\HTMLDocument', $template);
	}

	/**
	 * Test that the HTMLDocument constructor correctly instantiates a DOMDocument
	 */
	public function testGetDom(){
		$template = new EmptyTestDocument();
		$dom = $template->getDom();
		$this->assertInstanceOf('DOMDocument', $dom, 'Document constructor should create a DOMDocument instance.');
	}

	/**
	 * Make sure that Document::append adds components and elements to the Dom
	 */
	public function testAppend(){
		$template = new EmptyTestDocument();
		$dom = $template->getDom();

		// Check appending Component
		$component = new TestComponent($dom, array("title" => "Example"));
		$template->append($component);
		$head = $dom->getElementsByTagName('head');
		$this->assertEquals(1, $head->length, 'An appended component should be added to the underlying DOM.');

		// Check appending Element
		$element = new Tokamak\Dom\Element($dom, 'h1', null, 'Test');
		$template->append($element);
		$h1s = $dom->getElementsByTagName('h1');
		$this->assertEquals(1, $h1s->length, 'an appended element should be added to the underlying DOM.');
	}

	/**
	 * Test the output of an example Document class, including
	 * multiple elements and a custom component.
	 */
	public function testE2ERendering(){
		$template = new TestDocument(array("title" => "Example"));
		$template->setFormatOutput(true);
		$html =  $template->toString();
		$this->assertEquals(self::$expectedHTML, $html, 'HTML output should match expected output.');
	}

	/**
	 * Test the output of an example Document class, including
	 * multiple elements and a custom component.
	 */
	public function testRenderingWithClosures(){
		$template = new TestDocumentClosures(array("title" => "Example"));
		$template->setFormatOutput(true);
		$html =  $template->toString();
		$this->assertEquals(self::$expectedHTML, $html, 'HTML output should match expected output when using closures in addition to method chaining.');
	}

}
