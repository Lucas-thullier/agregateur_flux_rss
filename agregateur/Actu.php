<?php
class Actu 
{
	private $_url;
	private $_source;
	private $_content;
	private $_postHour;
	private $_title;

	public function addUrl($newUrl)
	{
		$this-> _url = $newUrl;
	}
	public function addSource($newSource)
	{
		$this-> _source = $newSource;
	}
	public function addContent($newContent)
	{
		$this -> _content = $newContent;
	}
	public function addHour($newHour)
	{
		$this -> _postHour = $newHour;
	}
	public function addNewsTitle($newTitle)
	{
		$this -> _title = $newTitle;
	}
	
	// Accesseurs
	public function url()
	{
		return $this-> _url;
	}
	public function source()
	{
		return $this-> _source;
	}
	public function content()
	{
		return $this -> _content;
	}
	public function postHour()
	{
		return $this -> _postHour;
	}
	public function title()
	{
		return $this -> _title;
	}
}
?>