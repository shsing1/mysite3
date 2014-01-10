<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Admin_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        // 設定後台首頁layout
        $this->template->set_layout('admin/layout');

		// 設定後台需載入的js
        $this->template->add_js('/assets/js/jquery.address-1.6.min.js', TRUE);
        // $this->template->add_js('/assets/js/i18n/grid.locale-'.$this->session->userdata('current_language')->jqgrid.'.js', TRUE);
        $this->template->add_js('/assets/js/i18n/grid.locale-tw.js', TRUE);
        $this->template->add_js('/assets/js/jquery.jqGrid.min.js', TRUE);
        $this->template->add_js('/assets/js/admin.init.js', TRUE);

        // 設定後台需載入的css
        $this->template->add_css('/assets/css/ui.jqgrid.css');
        $this->template->add_css('/assets/css/admin.css');

		$this->template->render('admin/index');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */