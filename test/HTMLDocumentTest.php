<?php
include_once(__DIR__ . '/../vendor/autoload.php');
include_once(__DIR__ . '/classes/TestDocument.php');
include_once(__DIR__ . '/classes/TestComponent.php');


/**
 * Class HTMLDocumentTest
 * Test cases for testing a normal use case incorporating documents, elements, components, etc.
 */
class HTMLDocumentTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test the output of an example Document class, including
	 * multiple elements and a custom component.
	 */
	public function testE2ERendering(){
		$template = new TestDocument(array("title" => "Example"));
		$template->setFormatOutput(true);
		$html =  $template->toString();
		$expected =
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

		$this->assertEquals($expected, $html, 'HTML output should match expected output.');
	}

}
