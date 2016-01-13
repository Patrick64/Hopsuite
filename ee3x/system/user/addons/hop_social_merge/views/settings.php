<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="box">
	<div class="tbl-ctrls">
		<h1><?=lang('nav_settings')?></h1>
		<?=form_open($action_url, '', $form_hidden)?>
		<fieldset class="col-group required <?php if (isset($form_error_cache)) echo 'invalid';?>">
			<div class="setting-txt col w-8">
				<h3><?= lang('label_cache_ttl')?></h3>
				<em><?= lang('label_sub_cache_ttl')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="cache_ttl" id="cache_ttl" value="<?=$settings['cache_ttl']?>">
				<?php if (isset($form_error_cache)) echo $form_error_cache;?>
			</div>
		</fieldset>
		
		<fieldset class="col-group ">
			<div class="setting-txt col w-8">
				<h3><?= lang('label_fcbk_app_token')?></h3>
				<em><?= lang('label_sub_fcbk_app_token')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="facebook_app_token" id="facebook_app_token" value="<?=$settings['facebook_app_token']?>">
			</div>
		</fieldset>
		
		<fieldset class="col-group ">
			<div class="setting-txt col w-8">
				<h3><?= lang('label_twitter_token')?></h3>
				<em><?= lang('label_sub_twitter_token')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="twitter_token" id="twitter_token" value="<?=$settings['twitter_token']?>">
			</div>
		</fieldset>
		
		<fieldset class="col-group ">
			<div class="setting-txt col w-8">
				<h3><?= lang('label_twitter_token_secret')?></h3>
				<em><?= lang('label_sub_twitter_token_secret')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="twitter_token_secret" id="twitter_token_secret" value="<?=$settings['twitter_token_secret']?>">
			</div>
		</fieldset>
		
		<fieldset class="col-group ">
			<div class="setting-txt col w-8">
				<h3><?= lang('label_twitter_cons_key')?></h3>
				<em><?= lang('label_sub_twitter_cons_key')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="twitter_consumer_key" id="twitter_consumer_key" value="<?=$settings['twitter_consumer_key']?>">
			</div>
		</fieldset>
		
		<fieldset class="col-group ">
			<div class="setting-txt col w-8">
				<h3><?= lang('label_twitter_cons_key_secret')?></h3>
				<em><?= lang('label_sub_twitter_cons_key_secret')?></em>
			</div>
			<div class="setting-field col w-8 last">
				<input type="text" name="twitter_consumer_secret" id="twitter_consumer_secret" value="<?=$settings['twitter_consumer_secret']?>">
			</div>
		</fieldset>
		
		<fieldset class="form-ctrls">
			<?=form_submit(array('name' => 'submit', 'value' => lang('settings_save'), 'class' => 'btn submit'))?>
		</fieldset>
		<?=form_close()?>
	</div>
</div>
