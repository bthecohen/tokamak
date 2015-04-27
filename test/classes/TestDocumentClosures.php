<?php
include_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/TestComponent.php');
use Tokamak\Dom\HTMLDocument;

class TestDocumentClosures extends HTMLDocument
{

	protected function render()
	{
		$data = $this->data;
		$this->appendElement('html', null, '', function() use ($data){
			$this->appendComponent('TestComponent', $data);
		})
		->appendElement('body', null, '', function(){
			$this->appendElement('h1', null, 'Test Document');
		});;
	}

}