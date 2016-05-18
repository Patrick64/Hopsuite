<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once PATH_THIRD.'hop_social_merge/helper.php';

class Hop_social_merge
{
	var $return_data	= '';

	private $facebook_page_id;
	private $twitter_username;
	private $twitter_search_query;
	private $twitter_count;
	private $facebook_count;

	/**
	 * Displays a simple list of
	 * @return [type] [description]
	 */
	function simple()
	{
		ee()->load->library('logger');
		$this->_process_parameters();

		$timeline = Hop_social_merge_helper::_get_timeline($this->twitter_screen_name, $this->twitter_search_query, $this->twitter_count, $this->facebook_page_id, $this->facebook_count);

		$tag_data = '<li>{text_url} - <span class="post_date">{social_network} on {date format="%Y-%m-%d %H:%i:%s"}</span></li>';

		if ($timeline != null && count($timeline) != 0)
		{
			$this->return_data = '<ul>'.$this->_process_tag_pair($timeline, $tag_data).'</ul>';
		}
		else
		{
			$this->return_data = "";
		}

		return $this->return_data;
	}

	/**
	 * Tag pair with accessible tags for social posts data
	 * @return string [description]
	 */
	function timeline()
	{
		ee()->load->library('logger');
		$this->_process_parameters();

		$timeline = Hop_social_merge_helper::_get_timeline($this->twitter_screen_name, $this->twitter_search_query, $this->twitter_count, $this->facebook_page_id, $this->facebook_count);

		if ($timeline != null && count($timeline) != 0)
		{
			$this->return_data = $this->_process_tag_pair($timeline);
		}
		else
		{
			$this->return_data = "";
		}

		return $this->return_data;
	}

	/**
	 * Look at parameters set in template tag and check them/make them available
	 * @return [type] [description]
	 */
	private function _process_parameters()
	{
		$this->twitter_screen_name = ee()->TMPL->fetch_param('twitter_username');
		$this->twitter_search_query = ee()->TMPL->fetch_param('twitter_search_query');
		$this->facebook_page_id = ee()->TMPL->fetch_param('facebook_feed_id');
		$this->_set_counts();
	}

	private function _set_counts()
	{
		$total_count = 10;
		$total_count_str = ee()->TMPL->fetch_param('total_count');
		if ($total_count_str != "" && is_numeric($total_count_str))
		{
			$total_count = intval($total_count_str);
		}

		$twitter_count = -1;
		$twitter_count_str = ee()->TMPL->fetch_param('twitter_count');
		if ($twitter_count_str != "" && is_numeric($twitter_count_str))
		{
			$twitter_count = intval($twitter_count_str);
		}

		$facebook_count = -1;
		$facebook_count_str = ee()->TMPL->fetch_param('facebook_count');
		if ($facebook_count_str != "" && is_numeric($facebook_count_str))
		{
			$facebook_count = intval($facebook_count_str);
		}

		if ($facebook_count == -1 && $twitter_count == -1)
		{
			$facebook_count = floor($total_count/2);
			$twitter_count = floor($total_count/2);
			if ( ($facebook_count+$twitter_count) == ($total_count - 1))
			{
				$twitter_count++;
			}
		}
		else if ($facebook_count == -1)
		{
			$facebook_count = $total_count - $twitter_count;
			if ($facebook_count < 0)
			{
				$facebook_count = 0;
			}
		}
		else if ($twitter_count == -1)
		{
			$twitter_count = $total_count - $facebook_count;
			if ($twitter_count < 0)
			{
				$twitter_count = 0;
			}
		}

		$this->twitter_count = $twitter_count;
		$this->facebook_count = $facebook_count;
	}

	/**
	 * Process the tag or tag pair using the data we got
	 * @param  array $timeline Our timeline with twitter/facebook posts
	 * @return string		   processed template with social posts data
	 */
	private function _process_tag_pair($timeline, $tag_data = NULL)
	{
		if ($tag_data == NULL)
		{
			$tag_data = ee()->TMPL->tagdata;
		}
		$this->return_data = "";
		$timeline_tags = array();
		$facebook_count = 0;
		$twitter_count = 0;
		foreach($timeline as $post)
		{
			//Convert post to tag array
			$social_post = $this->_setup_tags($post);
			if ($social_post['social_network'] == "Facebook")
			{
				$facebook_count++;
				$social_post['facebook_count'] = $facebook_count;
			}
			else if ($social_post['social_network'] == "Twitter")
			{
				$twitter_count++;
				$social_post['twitter_count'] = $twitter_count;
			}

			$timeline_tags[] = $social_post;
		}
		//Let EE do the job
		return ee()->TMPL->parse_variables($tag_data, $timeline_tags);
	}

	/**
	 * This is getting specific data from social posts and set it up as an array for template tags
	 * @param  [type] $post [description]
	 * @return [type]	   [description]
	 */
	private function _setup_tags($post)
	{
		$tags = array(
			'text'				=> "",
			'text_url'			=> "",
			'date'				=> "",
			'social_network'	=> "",
			'likes_count'		=> 0,
			'shares_count'		=> 0,
			'comments_count'	=> 0,
			'retweets_count'	=> 0,
			'favorites_count'   => 0,
			'from'				=> "",
			'screen_name'		=> "",
			'profile_picture'   => "",
			'profile_url'		=> "",
			'picture'			=> "",
			// Specific Twitter variables
			'retweet_url'		=> "",
			'favorite_url'		=> "",
			'reply_url'			=> "",
		);
		if (array_key_exists("tweet", $post))
		{
			$tweet = $post["tweet"];
			// Avoid error if tweet data isn't correct
			if (isset($tweet->created_at) && isset($tweet->text))
			{
				$tweet_date = new DateTime($tweet->created_at);

				//Replace shortened urls to full ones
				$tweet_text = $tweet->text;
				$tweet_text_url = $tweet->text;
				foreach ($tweet->entities->urls as $tweet_url)
				{
					$tweet_text = str_replace($tweet_url->url, $tweet_url->expanded_url, $tweet_text);
					$tweet_text_url = str_replace($tweet_url->url, '<a href="'.$tweet_url->expanded_url.'">'.$tweet_url->display_url.'</a>', $tweet_text_url);
				}
				//Media are also shortened sometime so we'll change them too
				if (isset($tweet->entities->media) && is_array($tweet->entities->media))
				{
					//media is an array of medias, get the first picture of it
					foreach ($tweet->entities->media as $tweet_media)
					{
						$tweet_text = str_replace($tweet_media->url, $tweet_media->media_url_https, $tweet_text);
						$tweet_text_url = str_replace($tweet_media->url, '<a href="'.$tweet_media->expanded_url.'">'.$tweet_media->display_url.'</a>', $tweet_text_url);
					}
				}

				$tags['text']			= $tweet_text;
				//$tags['text_url']	 = preg_replace('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=\-&_/]+!', "<a href=\"\\0\">\\0</a>", $tweet_text);
				$tags['text_url']		= $tweet_text_url;
				$tags['date']			= $tweet_date->getTimestamp();
				$tags['social_network'] = "Twitter";
				$tags['retweets_count'] = $tweet->retweet_count;
				$tags['favorites_count']= $tweet->favorite_count;
				
				$tags['retweet_url']	= 'https://twitter.com/intent/retweet?tweet_id='.$tweet->id;
				$tags['favorite_url']   = 'https://twitter.com/intent/favorite?tweet_id='.$tweet->id;
				$tags['reply_url']		= 'https://twitter.com/intent/tweet?in_reply_to='.$tweet->id;

				//User data
				$tags['from']			= $tweet->user->screen_name;
				$tags['screen_name']	= $tweet->user->name;
				$tags['profile_picture']= $tweet->user->profile_image_url_https;
				$tags['profile_url']	= 'https://twitter.com/'.$tweet->user->screen_name;

				if (isset($tweet->entities->media) && is_array($tweet->entities->media))
				{
					//media is an array of medias, get the first picture of it
					foreach ($tweet->entities->media as $tweet_media)
					{
						if ($tweet_media->type = "photo")
						{
							$tags['picture'] = $tweet_media->media_url_https;
							break;
						}
					}
				}
			} //ENDIF isset($tweet->created_at) && isset($tweet->text)
		}
		else if (array_key_exists("facebook", $post))
		{
			$facebook = $post['facebook'];
			
			if (isset($facebook->created_time))
			{
				$facebook_date = new DateTime($facebook->created_time);
			}
			else
			{
				$facebook_date = new DateTime();
			}
			
			$facebook_text = "";
			if (isset($facebook->message))
			{
				$facebook_text = $facebook->message;
			}

			if (isset($facebook->link) && $facebook->link != "")
			{
				$facebook_text .= " ".$facebook->link;
			}
			$tags['text']				= $facebook_text;
			$tags['text_url']			= preg_replace('!(http|ftp|scp)(s)?:\/\/[a-zA-Z0-9.?%=\-&_/]+!', "<a href=\"\\0\">\\0</a>", $facebook_text);
			$tags['date']				= $facebook_date->getTimestamp();
			$tags['social_network']		= "Facebook";
			if (isset($facebook->shares))
			{
				$tags['shares_count']   = $facebook->shares->count;
			}

			$tags['likes_count']		= $facebook->likes->summary->total_count;
			$tags['comments_count']		= $facebook->comments->summary->total_count;

			//User data
			$tags['from']				= $facebook->from->name;
			//We don't have that in Facebook data...
			//$tags['profile_picture']  = $facebook->from->
			$tags['profile_picture']	= '';
			$tags['profile_url']		= 'https://www.facebook.com/'.$facebook->from->id;

			//Media attached
			if (isset($facebook->picture))
			{
				$tags['picture']		= $facebook->picture;
			}
		}

		return $tags;
	}
}
