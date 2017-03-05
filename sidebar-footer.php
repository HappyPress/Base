<?php
/**
 * The sidebar containing the footer widget areas.
 * If there are no active widgets, the sidebar will be hidden completely.
 *
 * @package HappyPress Base
 * @since HappyPress Base 1.0
 */
?>

<?php
// Count how many footer sidebars are active so we can work out how many containers we need
$FooterSidebars = 0;
for ( $x = 1; $x <= 4; $x++ ) {
	if ( is_active_sidebar( 'sidebar-footer' . $x ) ) {
		$FooterSidebars++;
	}
}

// If there's one or more one active sidebars, create a row and add them
if ( $FooterSidebars > 0 ) { ?>
	<footer class="site-footer grid-container" role="contentinfo">
		<?php
		// Work out the container class name based on the number of active footer sidebars
		$ContainerClass = 'grid-' . intval( 100 / $FooterSidebars ) . ' tablet-grid-' . intval( 100 / $FooterSidebars ) . ' mobile-grid-100';

		// Display the active footer sidebars
		for ( $x = 1; $x <= 4; $x++ ) {
			if ( is_active_sidebar( 'sidebar-footer' . $x ) ) { ?>
				<div class="<?php echo $ContainerClass?>">
					<div class="widget-area" role="complementary">
						<?php dynamic_sidebar( 'sidebar-footer' . $x ); ?>
					</div>
				</div> <!-- /.<?php echo $ContainerClass?> -->
			<?php }
		} ?>
	</footer> <!-- /.site-footer.grid-container -->
<?php } ?>
