<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="box">
	<div class="tbl-ctrls">
		<h2>How to use ?</h2>
		<h3>Single tag</h3>
		<p>The simple tag will display a list of social posts.</p>
		<pre style="background-color: #fff; color: #111; padding: 10px;">{exp:hop_social_merge:simple twitter_username="hopstudios" facebook_feed_id="6308437182"}
		</pre>
		<p>Or</p>
		<pre style="background-color: #fff; color: #111; padding: 10px;">{exp:hop_social_merge:simple twitter_search_query="#eecms" facebook_feed_id="6308437182"}
		</pre>
		<p>In order to get facebook posts and tweets, the <u><i>twitter_username</i> or <i>twitter_search_query</i> parameter</u> and <u><i>facebook_feed_id</i></u> parameter are mandatory.</p>
		<h3>Tag Pair</h3>
		<p>The tag pair will iterate through each social post and display them using template tags. Here is a simple example :</p>
		<pre style="background-color: #fff; color: #111; padding: 10px; white-space:pre-wrap; word-wrap:break-word;">{exp:hop_social_merge:timeline twitter_username="hopstudios" facebook_feed_id="6308437182"}
    &lt;p&gt;{text_url}&lt;/p&gt;
    &lt;p&gt;{date format=&quot;%Y-%m-%d %H:%i:%s&quot;}&lt;/p&gt;
    &lt;p&gt;{if social_network == &quot;Facebook&quot;}
        F A C E B O O K {facebook_count}| {comments_count} comments | {shares_count} shares | {likes_count} likes
    {if:else}
        T W I T T E R {twitter_count}| {retweets_count} retweets | {favorites_count} favorites
    {/if}&lt;/p&gt;
    {if picture != &quot;&quot;}&lt;p&gt;&lt;img src=&quot;{picture}&quot;/&gt;&lt;/p&gt;{/if}
    &lt;hr&gt;
{/exp:hop_social_merge:timeline}
		</pre>

		<h3>Parameters</h3>
		<p>Those parameters are available for both single tag and tag pair</p>
		<p><strong>twitter_username</strong><br/>
		This will retrieve tweets from the given user timeline You can't use both twitter_username and twitter_search_query in the same tag.</p>
		<p><strong>twitter_search_query</strong><br/>
		This will search for tweets. Take a look at the <a href="https://twitter.com/search-home">official twitter search page</a> to have a deeper look on how it works (operators and advanced queries). You can't use both twitter_username and twitter_search_query in the same tag.</p>
		<p><strong>facebook_feed_id</strong><br/>
		Feed id of the person or page you want to retrieve posts from. To get a facebook feed id from a name, use the form at the bottom of that page.</p>
		<p><strong>total_count="10"</strong><br/>
		Specify how much posts in total will be displayed. If facebook_count and twitter_count are specified, this will not be taken into account.</p>
		<p><strong>facebook_count="5"</strong><br/>
		Specify how much Facebook posts will be displayed.</p>
		<p><strong>twitter_count="5"</strong><br/>
		Specify how much tweets will be displayed.</p>

		<h3>Available Tags</h3>
		<p>Only when using the tag pair, obviously.</p>

		<p><strong>{count}</strong><br/>
		Display the current count of the post (with no distinction between Twitter or Facebook)</p>

		<p><strong>Counts : {facebook_count} and {twitter_count}</strong><br/>
		Those display the count of Facebook post and Twitter post separately.</p>

		<p><strong>{comments_count}</strong><br/>
		<i>Facebook only</i> This will display the number of comments of that post.</p>

		<p><strong>{date format="%Y-%m-%d"}</strong><br/>
		Date of the social post. You can use <i>format="%Y-%m-%d"</i> parameter to specify the date format (just as data tag in exp:channel:entries)</p>

		<p><strong>{favorites_count}</strong><br/>
		<i>Twitter only</i> This will display the number of time the tweet has been saved as favorite</p>

		<p><strong>{favorite_url}</strong><br/>
		<i>Twitter only</i> This will output an intent url to favourite the tweet (see <a href="https://dev.twitter.com/web/intents">https://dev.twitter.com/web/intents</a>)</p>

		<p><strong>{from}</strong><br/>
		This will display the username of the person/page that sent the social post</p>

		<p><strong>{likes_count}</strong><br/>
		<i>Facebook only</i> This will display the number of likes of the Facebook post</p>

		<p><strong>{picture}</strong><br/>
		This is a url to an image if any is provided in the post.<p>

		<p><strong>{profile_picture}</strong><br/>
		<i>Twitter only</i> This is a url of the Twitter avatar of the person who posted the tweet.</p>

		<p><strong>{reply_url}</strong><br/>
		<i>Twitter only</i> This will output an intent url to reply to the tweet (see <a href="https://dev.twitter.com/web/intents">https://dev.twitter.com/web/intents</a>)</p>

		<p><strong>{retweets_count}</strong><br/>
		<i>Twitter only</i> This will display the number of times the tweet has been retweeted</p>

		<p><strong>{retweet_url}</strong><br/>
		<i>Twitter only</i> This will output an intent url to retweet the tweet (see <a href="https://dev.twitter.com/web/intents">https://dev.twitter.com/web/intents</a>)</p>

		<p><strong>{shares_count}</strong><br/>
		<i>Facebook only</i> This will display the number of times the Facebook post has been shared</p>

		<p><strong>{social_network}</strong><br/>
		This will display "Facebook" or "Twitter", depending on the source of the social post.</p>

		<p><strong>{switch="one|two|three"}</strong><br/>
		    This variable permits you to rotate through any number of values as the results are displayed. The first result will use “one”, the second will use “two”, the third “three”, the fourth “one”, and so on..</p>

		<p><strong>{text}</strong><br/>
		This will display the raw text of the social post. No url will be parsed as url</p>

		<p><strong>{text_url}</strong><br/>
		This will display the post with the url parsed</p>

		<p><strong>{total_results}</strong><br/>
		Display the total number of social posts.</p>

		<h2>Get Facebook feed id</h2>
		<p>To get your feed id, enter the short name of the page or username of a user and submit. The short name can be found in the page/user profile URL (https://www.facebook.com/hopstudios, short name is "hopstudios").</p>
		<form id="facebook_page_name_get" action="#" method="get">
		<input type="text" class="field shun" placeholder="Facebook page short name" /><br/>
		<input name="submit" value="Submit" class="submit" type="submit">
		</form>
		<p id="facebook_page_name_result">The feed id is : <span style="font-weight: bold;"></span></p>
		<script type="text/javascript">
		$("#facebook_page_name_get").on('submit', function(){

		    $.get( "http://graph.facebook.com/"+$(this).find('input.field.shun').val(), function( data ) {
		        $( "#facebook_page_name_result span" ).html( data.id );
		        console.log(data);
		    })
		    .fail(function() {
		        $( "#facebook_page_name_result span" ).html( "error (are you sure that short name exists ?)" );
		    });
		    return false;
		});
		</script>
	</div>
</div>
