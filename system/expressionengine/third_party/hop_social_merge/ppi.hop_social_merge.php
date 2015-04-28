<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require PATH_THIRD."hop_social_merge/libraries/TwitterAPIWrapper.php";
require PATH_THIRD."hop_social_merge/libraries/FacebookAPIWrapper.php";

class Hop_social_merge {

    public $return_data = "";

    private $fbk_app_token = "909957492357934|rLI-VuWCZOVUK9uPk3OFvSIM13c";
    private $page_id = "70363978942";

    private $twit_token = '108856923-4j0RbHbb5f6lGaMNq0qjk3sCDi00D4NgNKVcH915';
    private $twit_token_secret = 'rrZohEc6RZ439c8bKiJjo8gHOzLktHoMUwtZEfacRFM1P';
    private $twit_consumer_key = 'L4b9Hxv3Z5JFWBy7jJMEaLpI6';
    private $twit_consumer_secret = '5Yr5YQZIgfKtz0Pjl6WhSTZYWUuieUOlXkRfOpe87YZ8OzKFs2';
    //private $twit_user = "SSIReview";
    private $twit_user = "louwii59";

    /**
     * This is used for the main tag pair loop
     */
    public function ___construct()
    {
        $timeline = $this->_get_timeline();

        return $this->_process_tag_pair($timeline);
        //$this->return_data = json_decode($json);
    }

    /**
     * Displays a simple formatted list
     * @return [type] [description]
     */
    public function __simple()
    {
        $timeline = $this->_get_timeline();

        $this->return_data = "<ul>";
        foreach ($timeline as $post)
        {
            $this->return_data .= '<li>';
            if (array_key_exists('tweet', $post))
            {
                $tweet = $post['tweet'];
                $tweet_date = new DateTime($tweet->created_at);
// var_dump($tweet);
                $this->return_data .= $tweet->text;
                $this->return_data .= ' - <span class="post_date">Twitter on '.$tweet_date->format("Y-m-d H:i:s").'</span>';
            }
            else if (array_key_exists('facebook', $post))
            {
                $facebook = $post['facebook'];
                $facebook_date = new DateTime($facebook->created_time);
// var_dump($facebook);
                $this->return_data .= $facebook->message;
                if (isset($facebook->link) && $facebook->link != "")
                {
                    $this->return_data .= ' <a href="'.$facebook->link.'">'.$facebook->link.'</a>';
                }
                $this->return_data .= ' - <span class="post_date">Facebook on '.$facebook_date->format("Y-m-d H:i:s").'</span>';
            }
            $this->return_data .= '</li>';
        }
        $this->return_data .= "</ul>";

        return $this->return_data;
    }

    private function _get_timeline($get_twitter = true, $get_facebook = true)
    {
        $cache_key = "";
        if ($get_twitter && $get_facebook)
        {
            $cache_key = "twitter_facebook";
        }
        else if ($get_facebook)
        {
            $cache_key = "facebook";
        }
        else if ($get_twitter)
        {
            $cache_key = "twitter";
        }
        else
        {
            //twitter and facebook are false, exiting...
            return "";
        }

        if ($timeline_cache = ee()->cache->get('/'.get_class($this).'/'.$cache_key))
        {
            return $timeline_cache;
        }
        else
        {
            //Our posts will be stored in there
            $timeline = array();

            if ($get_facebook)
            {
                //Get Facebook page posts
                // Note: we specify the fields to have access to number of comments and likes (yes, if you don't do that, you don't have the counts...)
                $post_params = array(
                    "format"        => "json",
                    "limit"         => 5,
                    "fields"        => 'comments.limit(1).summary(true),likes.limit(1).summary(true),message,picture,link,from,shares',
                );

                $api_params = array("access_token" => $this->fbk_app_token);
                $facebook_api = new FacebookAPIWrapper($api_params);
                $result = $facebook_api->get($this->page_id."/posts", $post_params);

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
                //Get Twitter page posts
                $twit_settings = array (
                    'oauth_access_token' => $this->twit_token,
                    'oauth_access_token_secret' => $this->twit_token_secret,
                    'consumer_key' => $this->twit_consumer_key,
                    'consumer_secret' => $this->twit_consumer_secret
                );
                $params = array(
                    "screen_name"   => $this->twit_user,
                    "count"         => 5,
                    "max_id"        => "590962380651147264"
                );
                $twitter_api = new TwitterAPIWrapper($twit_settings);
                $json = $twitter_api->get("statuses/user_timeline.json", $params );

                $data = json_decode($json);
    // var_dump($data);

                $timeline_twitter = array();
                foreach ($data as $tweet)
                {
                    $date_tweet = new DateTime($tweet->created_at);
                    $tweet_timeline = array(
                        'timestamp' => $date_tweet->getTimestamp(),
                        'tweet'     => $tweet
                        //'tweet'     => ''
                    );
                    $timeline_twitter[] = $tweet_timeline;
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

            //Our timeline is ready
            if (isset(ee()->cache))
            {
                ee()->cache->save('/'.get_class($this).'/'.$cache_key, $timeline, 3600);
            }

            return $timeline;
        }
    }



}
