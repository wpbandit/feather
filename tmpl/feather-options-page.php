
<div class="wrap">
<?php FeatherAdmin::print_tabs($tabs); ?>

	<div class="feather postbox">
		<form method="post" action="options.php">
			<?php settings_fields('feather-settings'); ?>
			<?php do_settings_sections('feather-'.$current_tab); ?>

			<input type="hidden" name="feather[tab]" value="<?php echo $current_tab; ?>">

			<p class="submit">
				<input type="submit" class="button-primary" value="Save Changes" />
			</p>
		</form>
	</div>
</div>
