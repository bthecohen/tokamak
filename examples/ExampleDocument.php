<?php
include_once(__DIR__ . '/../vendor/autoload.php');
include_once(__DIR__ . '/Head.php');
use Tokamak\Dom\HTMLDocument;

class ExampleDocument extends HTMLDocument
{

	protected function render($data = null)
	{
		$this->appendElement('html', null, '', function($data){
			$this->appendComponent('Head', $data);
		})
		->appendElement('body', null, '', function(){
			$this->appendElement('h1', null, 'Test Document');
		});;

	}

}