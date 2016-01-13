<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?=form_open($action_url, '', $form_hidden)?>
<table class="mainTable padTable" border="0" cellspacing="0" cellpadding="0">
	<thead>
		<tr class="even">
			<th style="width:50%;" class="">Preference</th><th>Setting</th>
		</tr>
	</thead>
	<tbody>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="cache_ttl">Cache life</label></strong><div class="subtext">Time in minutes before refreshing data. Be careful, loading data from social networks can be long.</div>
			</td>
			<td>
				<input type="text" name="cache_ttl" id="cache_ttl" value="<?=$settings['cache_ttl']?>">&nbsp;
				<?php if (isset($form_error_cache)) echo $form_error_cache;?>
			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="facebook_app_token">Facebook App Token</label></strong><div class="subtext">The Facebook app token in order to access Facebook posts.</div>
			</td>
			<td>
				<input type="text" name="facebook_app_token" id="facebook_app_token" value="<?=$settings['facebook_app_token']?>">&nbsp;

			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="twitter_token">Twitter Token</label></strong><div class="subtext">Subject of the email sent when a 404 occurs.</div>
			</td>
			<td>
				<input type="text" name="twitter_token" id="twitter_token" value="<?=$settings['twitter_token']?>">&nbsp;

			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="twitter_token_secret">Twitter Token Secret</label></strong><div class="subtext">Subject of the email sent when a 404 occurs.</div>
			</td>
			<td>
				<input type="text" name="twitter_token_secret" id="twitter_token_secret" value="<?=$settings['twitter_token_secret']?>">&nbsp;

			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="twitter_consumer_key">Twitter Consumer Key</label></strong><div class="subtext">Subject of the email sent when a 404 occurs.</div>
			</td>
			<td>
				<input type="text" name="twitter_consumer_key" id="twitter_consumer_key" value="<?=$settings['twitter_consumer_key']?>">&nbsp;

			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="twitter_consumer_secret">Twitter Consumer Key Secret</label></strong><div class="subtext">Subject of the email sent when a 404 occurs.</div>
			</td>
			<td>
				<input type="text" name="twitter_consumer_secret" id="twitter_consumer_secret" value="<?=$settings['twitter_consumer_secret']?>">&nbsp;

			</td>
		</tr>
	</tbody>
</table>
<?=form_submit(array('name' => 'submit', 'value' => lang('settings_save'), 'class' => 'submit'))?>
<?=form_close()?>
