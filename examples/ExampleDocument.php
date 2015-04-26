<?php
include_once(__DIR__ . '/../vendor/autoload.php');
include_once(__DIR__ . '/Head.php');
use Tokamak\Dom\HTMLDocument;

class ExampleDocument extends HTMLDocument
{

	protected function render()
	{
		$body = $this->appendElement('html')
			    ->appendComponent('Head', $this->data)
				->appendElement('body', null);
					$body->appendElement('h1', null, 'Test Document');

	}

}