<?php
/**
 * Adds support for EE's Filemanager from within a module
 *
 * Requires ExpressionEngine 2.0 or newer. To use, simply include within your
 * module and invoke ModuleFilemanager::output() within your views. This will
 * include all of the necessary javascript to allow you to bind events to and
 * trigger the file manager.
 *
 * @copyright Copyright (c) 2011, Cody Phillips
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @version 1.0.0
 */
class ModuleFilemanager {

	/**
	 * @var array An array containing upload directories and available files
	 */
	private $file_manager = array();
	/**
	 * @var object The EE instance
	 */
	private $EE;

	/**
	 * Initalizes the filemanager
	 */
	public function __construct() {
		$this->EE =& get_instance();
		
		// All of the language for the filemanager is in EE's content language file, so load it up!
		$this->EE->lang->load('content');
		
		$this->EE->load->library('javascript');
		$this->EE->load->library('filemanager');
		
		$this->EE->filemanager->filebrowser('C=content_publish&M=filemanager_actions');
		
		// Set the file list
		$this->setup_file_list();
		
		$this->EE->javascript->set_global(array(
			'filebrowser.image_tag'	=> "<img src=\"[![Link:!:http://]!]\" alt=\"[![Alternative text]!]\" />",
			'upload_directories' => $this->file_manager['file_list']
		));
	}
	
	/**
	 * Set javascript output to bind events to the filemanager, for example:
	 * 	$(document).ready(function() {
	 *  	$.ee_filebrowser(); // initialize the filebrowser
	 *  	$.ee_filebrowser.add_trigger($(".some_click_area"), function(a){
	 *			// Handle upload (variables available include a.thumb, a.name, a.directory, a.dimensions, a.is_image)
	 *
	 *			// Reset the filebrowser
	 *			$.ee_filebrowser.reset();
	 *  	}
	 *  });
	 *
	 * @param string Javascript string data used to handle the ee_filebrowser jquery library (see above)
	 */
	public function output($str) {
		$this->EE->javascript->output($str);
	}
	
	/**
	 * Sets files and upload directories to be set globablly as javascript variables
	 */
	private function setup_file_list() {
		$this->EE->load->model('tools_model');
		
		$upload_directories = $this->EE->tools_model->get_upload_preferences($this->EE->session->userdata('group_id'));
	
		$this->file_manager = array(
			'file_list'	=> array(),
			'upload_directories' => array(),
		);
	
		$fm_opts = array(
			'id', 'name', 'url', 'pre_format', 'post_format', 
			'file_pre_format', 'file_post_format', 'properties', 
			'file_properties'
		);
	
		foreach($upload_directories->result() as $row) {
			$this->file_manager['upload_directories'][$row->id] = $row->name;

			foreach($fm_opts as $prop)
				$this->file_manager['file_list'][$row->id][$prop] = $row->$prop;
		}
	}	
}
?>