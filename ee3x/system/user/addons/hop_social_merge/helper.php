<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'hop_social_merge/config.php';
require PATH_THIRD."hop_social_merge/libraries/TwitterAPIWrapper.php";
require PATH_THIRD."hop_social_merge/libraries/FacebookAPIWrapper.php";

class Hop_social_merge_helper
{
    private static $_settings_table_name = "hop_social_merge_settings";
    private static $_settings;

    private static function _get_default_settings()
    {
        return array(
            'cache_ttl'                 => '5',
            'facebook_app_token'        => '',
            'twitter_token'             => '',
            'twitter_token_secret'      => '',
            'twitter_consumer_key'      => '',
            'twitter_consumer_secret'   => ''
        );
    }

    public static function get_settings()
    {
        if (! isset(self::$_settings))
        {
            $settings = array();

            //Get the actual saved settings
            $query = ee()->db->get(self::$_settings_table_name);

            foreach ($query->result_array() as $row)
            {
                $settings[$row["setting_name"]] = $row["value"];
            }

            self::$_settings = array_merge(self::_get_default_settings(), $settings);
        }

        return self::$_settings;
    }

    /**
     * Save Add-on settings into database
     * @param  array  $settings [description]
     * @return array            [description]
     */
    public static function save_settings($settings = array())
    {
        //be sure to save all settings possible
        $_tmp_settings = array_merge(self::_get_default_settings(), $settings);

        //No way to do INSERT IF NOT EXISTS so...
        foreach ($_tmp_settings as $setting_name => $setting_value)
        {
            $query = ee()->db->get_where(self::$_settings_table_name, array('setting_name'=>$setting_name), 1, 0);
            if ($query->num_rows() == 0) {
              // A record does not exist, insert one.
              $query = ee()->db->insert(self::$_settings_table_name, array('setting_name' => $setting_name, 'value' => $setting_value));
            } else {
              // A record does exist, update it.
              $query = ee()->db->update(self::$_settings_table_name, array('value' => $setting_value), array('setting_name'=>$setting_name));
            }
        }

        self::$_settings = $_tmp_settings;
    }

    /**
     * Get the social timeline with given parameters
     * Will load cache if exist, if not, load from social networks using APIs
     * @param  [type] $twitter_screen_name Twitter user account to get tweets from
     * @param  [type] $twitter_search_query Search query (if no screenname set, used for hashtags and whatnot...)
     * @param  [type] $twitter_count       Number of tweets to get
     * @param  [type] $facebook_page_id    Facebook page if to get posts from
     * @param  [type] $facebook_count      Number of posts to get
     * @return array                       An array containing all post/tweets, ordered by date, most recent first
     */
    public static function _get_timeline($twitter_screen_name, $twitter_search_query, $twitter_count, $facebook_page_id, $facebook_count)
    {
        $cache_key = "";

		//Api limit
		if ($facebook_count > 25)
        {
            $facebook_count = 25;
        }

		if ($twitter_count > 200)
		{
			$twitter_count = 200;
			//no kidding
		}

        //Parameters validation
        $get_twitter = FALSE;
        if (($twitter_screen_name != NULL && $twitter_screen_name != "") || ($twitter_search_query != NULL && $twitter_search_query != ""))
        {
            $get_twitter = TRUE;
        }
        $get_facebook = FALSE;
        if ($facebook_page_id != NULL && $facebook_page_id != "")
        {
            $get_facebook = TRUE;
        }
        
        if (!$get_facebook && !$get_twitter)
        {
            return "";
        }

        //Creating unique cache key for this configuration
        $cache_key = md5(serialize(func_get_args()));

        if ($timeline_cache = ee()->cache->get('/'.__CLASS__.'/'.$cache_key))
        {
            //Cache found, return it
            return $timeline_cache;
        }
        else
        {
            //No cache, let's use APIs !
            
            //Add-on settings
            $settings = self::get_settings();

            //Our posts will be stored in there
            $timeline = array();

            if ($get_facebook)
            {
                //Let's get those Facebook posts

                $facebook_token = $settings['facebook_app_token'];

                //Get Facebook page posts
                // Note: we specify the fields to have access to number of comments and likes (yes, if you don't do that, you don't have the counts...)
                $post_params = array(
                    "format"        => "json",
                    "limit"         => $facebook_count,
                    "fields"        => 'comments.limit(1).summary(true),likes.limit(1).summary(true),message,picture,link,from,shares',
                );

                $api_params = array("access_token" => $facebook_token);
                $facebook_api = new FacebookAPIWrapper($api_params);
                $result = $facebook_api->get($facebook_page_id."/posts", $post_params);

                $data = json_decode($result);
//var_dump($data);

                $timeline_facebook = array();
                foreach ($data->data as $post)
                {
                    $data_post = new DateTime($post->created_time);
                    $post_timeline = array(
                        'timestamp' => $data_post->getTimestamp(),
                        'facebook'  => $post
                        //'facebook'  => ''
                    );
                    $timeline_facebook[] = $post_timeline;
                }
//var_dump($timeline_facebook);
            }

            if ($get_twitter)
            {
                //Let's get those tweets

                $twit_token             = $settings['twitter_token'];
                $twit_token_secret      = $settings['twitter_token_secret'];
                $twit_consumer_key      = $settings['twitter_consumer_key'];
                $twit_consumer_secret   = $settings['twitter_consumer_secret'];

                //Get Twitter page posts
                $twit_settings = array (
                    'oauth_access_token'        => $twit_token,
                    'oauth_access_token_secret' => $twit_token_secret,
                    'consumer_key'              => $twit_consumer_key,
                    'consumer_secret'           => $twit_consumer_secret
                );
                
                // Query to get user timeline
                if ($twitter_screen_name != NULL && $twitter_screen_name != "")
                {
                    $params = array(
                        "screen_name"   => $twitter_screen_name,
                        "count"         => $twitter_count
                    );
                    
                    $twitter_api = new TwitterAPIWrapper($twit_settings);
                    $json = $twitter_api->get("statuses/user_timeline.json", $params );
                    // print_r($json);

                    // Data is an array of Tweets
                    $data = json_decode($json);
                    
                    if (isset($data->errors))
                    {
                      ee()->logger->developer('Hop Social Merge error when getting tweets : '. $data->errors[0]->code . ' - ' . $data->errors[0]->message);
                      $data = null;
                    }
                }
                //Query to search for tweets
                else
                {
                    $params = array(
                        "q"         => $twitter_search_query,
                        "count"     => $twitter_count,
                        "result_type" => 'recent'
                    );
                    
                    $twitter_api = new TwitterAPIWrapper($twit_settings);
                    $json = $twitter_api->get("search/tweets.json", $params );
                    // print_r($json);
                    
                    // Adjustement to get an array of tweets
                    $data = json_decode($json);
                    if (isset($data->errors))
                    {
                      ee()->logger->developer('Hop Social Merge error when getting tweets : '. $data->errors[0]->code . ' - ' . $data->errors[0]->message);
                      $data = null;
                    }
                    else
                    {
                      $data = $data->statuses;
                    }
                    
                }
                
// var_dump($data);

                $timeline_twitter = array();
                if ($data != null)
                {
                  foreach ($data as $tweet)
                  {
                      $date_tweet = new DateTime($tweet->created_at);
                      $tweet_timeline = array(
                          'timestamp' => $date_tweet->getTimestamp(),
                          'tweet'     => $tweet
                      );
                      $timeline_twitter[] = $tweet_timeline;
                  }
                }
            }

            if ($get_facebook && $get_twitter)
            {
                //If we need the two timelines, combine them
                while (count($timeline_facebook) != 0)
                {
                    while (count($timeline_twitter) != 0 && $timeline_facebook[0]['timestamp'] < $timeline_twitter[0]['timestamp'])
                    {
                        $timeline[] = $timeline_twitter[0];
                        array_shift($timeline_twitter);
                        if (count($timeline_twitter) == 0){ break; }
                    }
                    $timeline[] = $timeline_facebook[0];
                    array_shift($timeline_facebook);
                }

                if (count($timeline_twitter) != 0)
                {
                    //we got tweets remaining
                    foreach ($timeline_twitter as $tweet)
                    {
                        $timeline[] = $tweet;
                    }
                }
            }
            else if ($get_facebook)
            {
                $timeline = $timeline_facebook;
            }
            else if ($get_twitter)
            {
                $timeline = $timeline_twitter;
            }


// var_dump($timeline);

            //Our timeline is ready, save it in cache 
            if (isset(ee()->cache))
            {
                ee()->cache->save('/'.__CLASS__.'/'.$cache_key, $timeline, $settings['cache_ttl']*60);
            }

            return $timeline;
        }// endif no cache found
    }
}
