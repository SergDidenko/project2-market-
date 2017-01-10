<?php


class Pager{

	private $page; //number of page
	private $max;  //number of items on the page
	private $start;//start for each of pages
	private $link; //total number of links
	private $total;//total number of items in DB

	public function __construct($total,$page=1, $max=3, $link=5){
		$this->max=$max;
		$this->link=$link;
		$this->page=$page;
		$this->total=$total;
	}

	public function getLinks(){
		if ($this->total==0) {
			return null;
		}
		$this->start=$this->getStart();
		for ($i=0; $i < ceil($this->total/$this->max); $i++) { 
			$pagesArr[$i+1]=$i*($this->max);
		}
		$allPages=array_chunk($pagesArr,$this->link,true);
		$needChunk=$this->searchPage($allPages,$this->start);
		foreach ($allPages[$needChunk] as $pages => $offset) {
			$links[]=$pages;
		}
		return $links;
	}
	private function searchPage($arr,$start){
		foreach ($arr as $chunk => $pages) {
			if (in_array($start,$pages)) {
				return $chunk;
			}
		}
		return 0;
	}
	public function getMaxPage(){
		return ceil($this->total/$this->max);
	}
	public function getMax(){
		return $this->max;
	}
	public function getStart(){
		return ($this->page-1)*$this->max;
	}
}
