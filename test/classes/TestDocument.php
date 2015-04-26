<?php
include_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/TestComponent.php');
use Tokamak\Dom\HTMLDocument;

class TestDocument extends HTMLDocument
{

	protected function render()
	{
		$body = $this->appendElement('html')
			    ->appendComponent('TestComponent', $this->data)
				->appendElement('body', null);
					$body->appendElement('h1', null, 'Test Document');
	}

}