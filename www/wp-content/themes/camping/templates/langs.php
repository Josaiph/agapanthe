<ul class="language_box">
	<li>
		<b><img src="<?php echo get_template_directory_uri(); ?>/images/flag_<?php echo $_COOKIE['lang']; ?>.gif" alt="" /></b>
		<ul class="dropbox">
			<?php if($_COOKIE['lang']!=='fr') : ?>
			<li><a href="?lang=fr"><img src="<?php echo get_template_directory_uri(); ?>/images/flag_fr.gif" alt="fr" /></a></li>
			<?php else: ?>
			<li><a href="?lang=us"><img src="<?php echo get_template_directory_uri(); ?>/images/flag_us.gif" alt="us" /></a></li>
			<?php endif; ?>
		</ul>
	</li>
</ul>