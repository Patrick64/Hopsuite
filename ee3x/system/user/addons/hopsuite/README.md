# Hop Social Merge


## Usage

### Find the facebook numeric id of a page or a person

You can use external websites for that, like [http://findmyfbid.com](http://findmyfbid.com/).

### `{exp:hopsuite:simple}` simple tag

Displays a simple list of social posts (`<ul>` -> `<li>` list).

#### Example usage

`{exp:hopsuite:simple twitter_username="hopstudios" facebook_feed_id="6308437182"}`

`{exp:hopsuite:simple twitter_search_query="#eecms" facebook_feed_id="6308437182"}`

#### Parameters

- `twitter_username` Retrieve tweets from given username
- `twitter_search_query` Retrieve tweets from given search query. Take a look at [Twitter Search Page](https://twitter.com/search-home) to have a deeper look on how it works.
- `facebook_feed_id` Retrieve Facebook posts of the given feed id
- `total_count="10"` Specify how much posts in total will be displayed. If `facebook_count` and `twitter_count` are specified, this will not be taken into account.
- `facebook_count="5"` Specify how much Facebook posts will be displayed.
- `twitter_count="5"` Specify how much tweets will be displayed.

### `{exp:hopsuite:timeline}` tag pair

#### Example usage

	`{exp:hopsuite:timeline twitter_username="hopstudios" facebook_feed_id="6308437182"}
	    <p>{text_url}</p>
	    <p>{date format="%Y-%m-%d %H:%i:%s"}</p>
	    <p>{if social_network == "Facebook"}
	        F A C E B O O K {facebook_count}| {comments_count} comments | {shares_count} shares | {likes_count} likes
	    {if:else}
	        T W I T T E R {twitter_count}| {retweets_count} retweets | {favorites_count} favorites
	    {/if}</p>
	    {if picture != ""}<p><img src="{picture}"/></p>{/if}
	    <hr>
	{/exp:hopsuite:timeline}`

#### Parameters

- `twitter_username` Retrieve tweets from given username
- `twitter_search_query` Retrieve tweets from given search query. Take a look at [Twitter Search Page](https://twitter.com/search-home) to have a deeper look on how it works.
- `facebook_feed_id` Retrieve Facebook posts of the given feed id
- `total_count="10"` Specify how much posts in total will be displayed. If `facebook_count` and `twitter_count` are specified, this will not be taken into account.
- `facebook_count="5"` Specify how much Facebook posts will be displayed.
- `twitter_count="5"` Specify how much tweets will be displayed.

#### Inner tags

- `{count}` Display count of the current post
- Counts : `{facebook_count}` and `{twitter_count}` Those display the count of Facebook post and Twitter post separately.
- `{comments_count}` *Facebook only* This will display the number of comments of that post.
- `{date format="%Y-%m-%d"}` Date of the social post. You can use format="%Y-%m-%d" parameter to specify the date format (just as data tag in exp:channel:entries)
- `{favorites_count}` *Twitter only* This will display the number of time the tweet has been saved as favorite
- `{favorite_url}` *Twitter only* This will output an intent url to favourite the tweet (see [https://dev.twitter.com/web/intents](https://dev.twitter.com/web/intents))
- `{from}` This will display the twitter username or the person/page name
- `{likes_count}` *Facebook only* This will display the number of likes of the Facebook post
- `{picture}` This is a url to an image if any is provided in the post.
- `{profile_picture}` *Twitter only* This is a url of the Twitter avatar of the person who posted the tweet.
- `{profile_url}` This will display a URL to the Twitter account or Facebook person or page that posted the social post
- `{reply_url}` *Twitter only* This will output an intent url to reply to the tweet (see [https://dev.twitter.com/web/intents](https://dev.twitter.com/web/intents))
- `{retweets_count}` *Twitter only* This will display the number of times the tweet has been retweeted
- `{retweet_url}` *Twitter only* This will output an intent url to retweet the tweet (see [https://dev.twitter.com/web/intents](https://dev.twitter.com/web/intents))
- `{shares_count}` *Facebook only* This will display the number of times the Facebook post has been shared
- `{social_network}` This will display "Facebook" or "Twitter", depending on the source of the social post.
- `{text}` This will display the raw text of the social post. No url will be parsed as url.
- `{text_url}` This will display the post with the url parsed (meaning urls will be clickable)
- `{total_results}` This will display the total number of social posts.
- `{screen_name}` *Twitter only* This will display the Twitter screen name


## Support

Having issues ? Found a bug ? Suggestions ? Contact us at [tech@hopstudios.com](mailto:tech@hopstudios.com)


## Changelog 

### 1.0

First release

##License
Updated: Jan. 6, 2009

####Permitted Use

One license grants the right to perform one installation of the Software. Each additional installation of the Software requires an additional purchased license. For free Software, no purchase is necessary, but this license still applies.

####Restrictions

Unless you have been granted prior, written consent from Hop Studios, you may not:

* Reproduce, distribute, or transfer the Software, or portions thereof, to any third party.
* Sell, rent, lease, assign, or sublet the Software or portions thereof.
* Grant rights to any other person.
* Use the Software in violation of any U.S. or international law or regulation.

####Display of Copyright Notices

All copyright and proprietary notices and logos in the Control Panel and within the Software files must remain intact.
Making Copies

You may make copies of the Software for back-up purposes, provided that you reproduce the Software in its original form and with all proprietary notices on the back-up copy.

####Software Modification

You may alter, modify, or extend the Software for your own use, or commission a third-party to perform modifications for you, but you may not resell, redistribute or transfer the modified or derivative version without prior written consent from Hop Studios. Components from the Software may not be extracted and used in other programs without prior written consent from Hop Studios.

####Technical Support

Technical support is available through e-mail, at sales@hopstudios.com. Hop Studios does not provide direct phone support. No representations or guarantees are made regarding the response time in which support questions are answered.
Refunds

Hop Studios offers refunds on software within 30 days of purchase. Contact sales@hopstudios.com for assistance. This does not apply if the Software is free.
Indemnity

You agree to indemnify and hold harmless Hop Studios for any third-party claims, actions or suits, as well as any related expenses, liabilities, damages, settlements or fees arising from your use or misuse of the Software, or a violation of any terms of this license.

####Disclaimer Of Warranty

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, WARRANTIES OF QUALITY, PERFORMANCE, NON-INFRINGEMENT, MERCHANTABILITY, OR FITNESS FOR A PARTICULAR PURPOSE. FURTHER, HOP STUDIOS DOES NOT WARRANT THAT THE SOFTWARE OR ANY RELATED SERVICE WILL ALWAYS BE AVAILABLE.
Limitations Of Liability

YOU ASSUME ALL RISK ASSOCIATED WITH THE INSTALLATION AND USE OF THE SOFTWARE. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS OF THE SOFTWARE BE LIABLE FOR CLAIMS, DAMAGES OR OTHER LIABILITY ARISING FROM, OUT OF, OR IN CONNECTION WITH THE SOFTWARE. LICENSE HOLDERS ARE SOLELY RESPONSIBLE FOR DETERMINING THE APPROPRIATENESS OF USE AND ASSUME ALL RISKS ASSOCIATED WITH ITS USE, INCLUDING BUT NOT LIMITED TO THE RISKS OF PROGRAM ERRORS, DAMAGE TO EQUIPMENT, LOSS OF DATA OR SOFTWARE PROGRAMS, OR UNAVAILABILITY OR INTERRUPTION OF OPERATIONS.
