<?php
include_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/TestComponent.php');
use Tokamak\Dom\HTMLDocument;

class TestDocumentClosures extends HTMLDocument
{

	protected function render($data)
	{
		$this->appendElement('html', null, '', $data, function($data){
			$this->appendComponent('TestComponent', $data);
			$this->appendElement('body', null, '', null, function(){
				$this->appendElement('h1', null, 'Test Document');
			});
		});
	}

}