<?php 
class Newsletter_Block extends Core_Block {
	protected $view = 'blocks/newsletter';	
	public function __construct() {
		parent::__construct();
	}
	public function drawData() {
		$data = $this->getBlockData();
		
		return $data;
	}
}
