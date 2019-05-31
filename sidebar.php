<?php

if ( ! is_active_sidebar( 'zijbalk' ) ) {
	return;
}
?>

<aside id='zijbalk' role="complementary">
	<?php dynamic_sidebar('zijbalk'); ?>
</aside>
