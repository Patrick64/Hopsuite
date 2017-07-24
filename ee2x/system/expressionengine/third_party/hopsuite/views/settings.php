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
				<strong><label for="facebook_app_id"><?=lang('label_fcbk_app_id')?></label></strong>
				<div class="subtext"><?=lang('label_sub_fcbk_app_id')?></div>
			</td>
			<td>
				<input type="text" name="facebook_app_id" id="facebook_app_id" value="<?=$settings['facebook_app_id']?>">&nbsp;
			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="facebook_app_secret"><?=lang('label_fcbk_app_secret')?></label></strong>
				<div class="subtext"><?=lang('label_sub_fcbk_app_secret')?></div>
			</td>
			<td>
				<input type="text" name="facebook_app_secret" id="facebook_app_secret" value="<?=$settings['facebook_app_secret']?>">&nbsp;
			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="twitter_token"><?=lang('label_twitter_token')?></label></strong>
				<div class="subtext"><?=lang('label_sub_twitter_token')?></div>
			</td>
			<td>
				<input type="text" name="twitter_token" id="twitter_token" value="<?=$settings['twitter_token']?>">&nbsp;

			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="twitter_token_secret"><?=lang('label_twitter_token_secret')?></label></strong>
				<div class="subtext"><?=lang('label_sub_twitter_token_secret')?></div>
			</td>
			<td>
				<input type="text" name="twitter_token_secret" id="twitter_token_secret" value="<?=$settings['twitter_token_secret']?>">&nbsp;

			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="twitter_consumer_key"><?=lang('label_twitter_cons_key')?></label></strong>
				<div class="subtext"><?=lang('label_sub_twitter_cons_key')?></div>
			</td>
			<td>
				<input type="text" name="twitter_consumer_key" id="twitter_consumer_key" value="<?=$settings['twitter_consumer_key']?>">&nbsp;

			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="twitter_consumer_secret"><?=lang('label_twitter_cons_key_secret')?></label></strong>
				<div class="subtext"><?=lang('label_sub_twitter_cons_key_secret')?></div>
			</td>
			<td>
				<input type="text" name="twitter_consumer_secret" id="twitter_consumer_secret" value="<?=$settings['twitter_consumer_secret']?>">&nbsp;

			</td>
		</tr>
		<tr class="<?php echo alternator('even', 'odd');?>">
			<td>
				<strong><label for="instagram_access_token"><?=lang('label_instagram_access_token')?></label></strong>
				<div class="subtext"><?=lang('label_sub_instagram_access_token')?></div>
			</td>
			<td>
				<input type="text" name="instagram_access_token" id="instagram_access_token" value="<?=$settings['instagram_access_token']?>">&nbsp;

			</td>
		</tr>
	</tbody>
</table>
<?=form_submit(array('name' => 'submit', 'value' => lang('settings_save'), 'class' => 'submit'))?>
<?=form_close()?>
