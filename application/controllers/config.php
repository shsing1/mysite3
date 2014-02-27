<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Config extends Visitor_Controller {

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
		$array1 = array('base_url');
		$array2 = $this->config->config;
		$result = array();

		foreach ($array1 as $v)
		{
			if (isset($array2[$v])) {
				$result[$v] = $array2[$v];
			}
		}

		if (count($result) > 0)
		{
			echo 'var config = ' . json_encode($result) . ';';
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */