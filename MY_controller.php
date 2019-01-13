<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class MY_controller extends CI_Controller {

	//  Protected property
	protected $is_login;
	protected $data = [];
	protected $headerView = [];
	protected $footerView = [];
	protected $breadcrumbs = '';
	protected $css,$js,$msg;
	// public property
	public $status = [
		1 => "Aktif",
		2 => "Tidak Aktif"
	];
	public $level = [
		1 => "Administrator",
		2 => "Dokter",
		3 => "Karyawan"
	];
	public $title,
		   $perm;

	// method
	public function __construct ($view) {
		parent::__construct();
		$this->is_login = (!$this->session->userdata('loged')) ? FALSE : $this->session->userdata('loged');
		if($view=='admin'){
			self::setViewAdmin();
		} elseif($view='front') {
			self::setViewFront();
		}
	}
	protected function setViewAdmin() {
		$this->headerView = [
			"base_templates/v_header",
			"base_templates/v_nav",
			"base_templates/v_aside"
		];
		$this->footerView = [
			"base_templates/v_footer"
		];
	}
	protected function setViewFront() {
		$this->headerView = [
			""
		];
		$this->footerView = [
			""
		];
	}
	protected function set_css($css) {
		if(is_array($css)){
			foreach($css as $item){
				$this->css .= '<link rel="stylesheet" href="' . $item . '" />';
			}
		} else {
			$this->css .= '<link rel="stylesheet" href="' . $css . '" />';
		}
	}
	protected function set_js($js) {
		if(is_array($js)){
			foreach($js as $item){
				$this->js .= '<script src="' . $item . '"></script>';
			}
		} else {
			$this->js .= '<script src="' . $js . '"></script>';
		}
	}
	protected function base_template($insertedView=null) {
		foreach($this->headerView as $view) {
			$this->load->view($view,$this->data);
		}
		if(is_array($insertedView)) {
			foreach ($insertedTemplate as $view) {
				$this->load->view($view,$this->data);
			}
		} else {
			$this->load->view($insertedView,$this->data);
		}
		foreach($this->footerView as $view) {
			$this->load->view($view,$this->data);
		}
	}
	protected function v($view) {
		$this->load->view($view,$this->data);
	}
	// Config Menu
	protected function get_menus($level) {
		$this->config->load('menus');
		$menus = $this->config->item('menus');
		return $menus[$level];
	}
	// Breadcrumbs
	protected function get_breadcrumbs() {
		$base = self::base();
		$base_link = '#';
		$this->breadcrumbs = '<li class="active">'.$base.'</li>';
		if($this->uri->segment(2)){
			$base_link = base_url($base);
			$this->breadcrumbs = '<li><a href="'.$base_link.'">'.$base.'</a></li><li class="active">'.$this->uri->segment(2).'</li>';
		}
		if($this->uri->segment(3)){
			$base_link = base_url($base);
			$this->breadcrumbs = '<li><a href="'.$base_link.'">'.$base.'</a></li><li><a href="'.$base_link.$this->uri->segment(2).'">'.$this->uri->segment(2).'</a></li>';
			$breadcrumb = explode('/',uri_string());
			$endkey = end(array_keys($breadcrumb));
			$linkbread = '';
			for($i=2; $i <= (count($breadcrumb)-2); $i++) {
				if($i==$endkey){
					$this->breadcrumbs .= '<li class="active">'.$breadcrumb[$i].'</li>';
				} else {
					$linkbread .= $breadcrumb[$i].'/';
					$this->breadcrumbs .= '<li><a href="'.$base_link.$this->uri->segment(2).'/'.$linkbread.'">'.$base.'</a></li>';
				}
			}
		}
		return $this->breadcrumbs;
	}
	protected function base() {
		if ($this->uri->segment(1)!==null) {
			return $this->uri->segment(1);
		} elseif($this->uri->segment(1)=='') {
			return 'Home';
		}

	}
}
