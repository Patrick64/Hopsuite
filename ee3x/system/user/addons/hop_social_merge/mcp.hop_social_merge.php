<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'hop_social_merge/helper.php';

class hop_social_merge_mcp
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
		$sidebar = ee('CP/Sidebar')->make();
		
		$sidebar->addHeader(lang('nav_how_to'), ee('CP/URL', 'addons/settings/'.HOP_SOCIAL_MERGE_NAME));
		$sidebar->addHeader(lang('nav_settings'), ee('CP/URL', 'addons/settings/'.HOP_SOCIAL_MERGE_NAME.'/settings'));
    }

    function index()
    {
        $this->build_nav();
        ee()->view->cp_page_title = lang('hop_social_merge_module_name');

        $vars = array();

        // return ee()->load->view('index', $vars, TRUE);
		return array(
			'heading'		=> lang('nav_how_to'),
			'body'			=> ee('View')->make(HOP_SOCIAL_MERGE_NAME.':index')->render($vars),
			'breadcrumb'	=> array(
			  ee('CP/URL', 'addons/settings/'.HOP_SOCIAL_MERGE_NAME)->compile() => lang('hop_social_merge_module_name')
			),
		);
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

            $settings['facebook_app_token'] = ee()->input->post('facebook_app_token');
            $settings['twitter_token'] = ee()->input->post('twitter_token');
            $settings['twitter_token_secret'] = ee()->input->post('twitter_token_secret');
            $settings['twitter_consumer_key'] = ee()->input->post('twitter_consumer_key');
            $settings['twitter_consumer_secret'] = ee()->input->post('twitter_consumer_secret');

            if ($form_is_valid)
            {
                Hop_social_merge_helper::save_settings($settings);
                ee()->session->set_flashdata('message_success', lang('settings_saved_success'));
                ee()->functions->redirect(ee('CP/URL')->make('addons/settings/'.HOP_SOCIAL_MERGE_NAME.'/settings'));
            }
            else
            {
                $vars["settings"] = $settings;
            }

        }

        // No data received, means we'll load saved settings
        if (!isset($form_is_valid))
        {
            $vars["settings"] = Hop_social_merge_helper::get_settings();
        }

        $vars['action_url'] = ee('CP/URL')->make('addons/settings/'.HOP_SOCIAL_MERGE_NAME.'/settings');
        $vars['form_hidden'] = array('action' => 'save_settings');

        // return ee()->load->view('settings', $vars, TRUE);
		return array(
			'heading'			=> lang('nav_settings'),
			'body'				=> ee('View')->make(HOP_SOCIAL_MERGE_NAME.':settings')->render($vars),
			'breadcrumb'	=> array(
			  ee('CP/URL', 'addons/settings/'.HOP_SOCIAL_MERGE_NAME)->compile() => lang('hop_social_merge_module_name')
			),
		);
    }
}
