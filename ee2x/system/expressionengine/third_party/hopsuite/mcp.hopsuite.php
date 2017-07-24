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
			lang('nav_settings')					=> BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.HOPSUITE_NAME.AMP.'method=settings',
		));
	}

	function index()
	{
		ee()->functions->redirect(BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module='.HOPSUITE_NAME.AMP.'method=settings');
	}

	function settings()
	{
		$this->build_nav();
		ee()->view->cp_page_title = lang('nav_settings');
		$vars = array();

		// Generate Instagram URL to get an access token
		// https://api.instagram.com/oauth/authorize/?client_id=xxxxxxxxxxxxxxxxxxxxxx&redirect_uri=http%3A%2F%2Fmysite.com&response_type=token&scope=public_content
		$site_url = ee()->config->item('site_url');
		$instagram_url = 'https://api.instagram.com/oauth/authorize/?client_id=CLIENTID&redirect_uri='.urlencode($site_url).'&response_type=token';
		$vars['site_url'] = $site_url;
		$vars['instagram_token_url'] = $instagram_url;

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
			$settings['instagram_access_token']	= ee()->input->post('instagram_access_token');

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
