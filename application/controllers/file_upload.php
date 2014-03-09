<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class File_Upload extends Admin_Controller {

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
		$upload_path_url = base_url().'upload/';

        $config['upload_dir'] = FCPATH.'upload/files/';
        $config['allowed_types'] = 'jpg';
        $config['max_size'] = '30000';
        $config['image_versions'] = array();

        $this->load->library('uploadhandler', $config, 'upload');

        // if ( ! $this->upload->do_upload()) {
        //     $error = array('error' => $this->upload->display_errors());
        //     $this->load->view('upload', $error);

        // } else {
        //     $data = $this->upload->data();
        //     /*
        //             // to re-size for thumbnail images un-comment and set path here and in json array
        //     $config = array(
        //         'source_image' => $data['full_path'],
        //         'new_image' => $this->$upload_path_url '/thumbs',
        //         'maintain_ration' => true,
        //         'width' => 80,
        //         'height' => 80
        //     );

        //     $this->load->library('image_lib', $config);
        //     $this->image_lib->resize();
        //     */
        //     //set the data for the json array
        //     $info->name = $data['file_name'];
        //     $info->size = $data['file_size'];
        //     $info->type = $data['file_type'];
        //     $info->url = $upload_path_url .$data['file_name'];
        //     // I set this to original file since I did not create thumbs.  change to thumbnail directory if you do = $upload_path_url .'/thumbs' .$data['file_name']
        //     $info->thumbnail_url = $upload_path_url .$data['file_name'];
        //     $info->delete_url = base_url().'upload/deleteImage/'.$data['file_name'];
        //     $info->delete_type = 'DELETE';

        //     //this is why we put this in the constants to pass only json data
        //     if (IS_AJAX) {
        //         echo json_encode(array($info));
        //         //this has to be the only data returned or you will get an error.
        //         //if you don't give this a json array it will give you a Empty file upload result error
        //         //it you set this without the if(IS_AJAX)...else... you get ERROR:TRUE (my experience anyway)

        //     // so that this will still work if javascript is not enabled
        //     } else {
        //         $file_data['upload_data'] = $this->upload->data();

        //         // $this->load->view('admin/upload_success', $file_data);
        //     }
        // }
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */