<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'hopsuite/helper.php';

class hopsuite_mcp
{
  
	function __construct()
	{
		ee()->load->library('logger');
	}
  
	/**
	 * Build the navigation menu for the module
	*/
	function build_nav()
	{
		ee()->cp->set_right_nav(array(
			lang('hopsuite_module_name')	=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.HOPSUITE_NAME,
			lang('nav_settings')					=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.HOPSUITE_NAME.AMP.'method=settings',
		));
	}

	function index()
	{
		$this->build_nav();
		ee()->view->cp_page_title = lang('hopsuite_module_name');

		$vars = array();

		return ee()->load->view('index', $vars, TRUE);
	}

	function settings()
	{
		$this->build_nav();
		ee()->view->cp_page_title = lang('nav_settings');
		$vars = array();
		if (ee()->input->post('action') == "save_settings")
		{
			$settings = array();
			$form_is_valid = TRUE;
			if (ee()->input->post('cache_ttl') != "" && is_numeric(ee()->input->post('cache_ttl')) && intval(ee()->input->post('cache_ttl')) > 0 )
			{
				$settings["cache_ttl"] = intval(ee()->input->post('cache_ttl'));
			}
			else
			{
				$settings["cache_ttl"] = ee()->input->post('cache_ttl');
				$form_is_valid = FALSE;
				$vars["form_error_cache"] = lang('settings_form_error_cache');
			}

			$settings['facebook_app_id']		= ee()->input->post('facebook_app_id');
			$settings['facebook_app_secret']	= ee()->input->post('facebook_app_secret');
			$settings['twitter_token']			= ee()->input->post('twitter_token');
			$settings['twitter_token_secret']	= ee()->input->post('twitter_token_secret');
			$settings['twitter_consumer_key']	= ee()->input->post('twitter_consumer_key');
			$settings['twitter_consumer_secret']= ee()->input->post('twitter_consumer_secret');

			if ($form_is_valid)
			{
				Hopsuite_helper::save_settings($settings);
				ee()->session->set_flashdata('message_success', lang('settings_saved_success'));
				ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.HOPSUITE_NAME.AMP.'method=settings');
			}
			else
			{
				$vars["settings"] = $settings;
			}

		}

		// No data received, means we'll load saved settings
		if (!isset($form_is_valid))
		{
			$vars["settings"] = Hopsuite_helper::get_settings();
		}

		$vars['action_url'] = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.HOPSUITE_NAME.AMP.'method=settings';
		$vars['form_hidden'] = array('action' => 'save_settings');

		return ee()->load->view('settings', $vars, TRUE);
	}
}
