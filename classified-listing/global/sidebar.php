<?php
/**
 * Sidebar
 *
 * @package     ClassifiedListing/Templates
 * @version     1.4.0
 */

use RadiusTheme\Cldoctor\Options;
use Rtcl\Helpers\Functions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$sidebar_class = 'listing-sidebar';
if (is_author()) {
    $sidebar_class .= ' sidebar-widget-area';
}
?>
<?php
if (Options::$sidebar && is_active_sidebar(Options::$sidebar)) { ?>
    <div class="<?php echo esc_attr($sidebar_class); ?>">
        <?php dynamic_sidebar(Options::$sidebar); ?>
    </div>
<?php } elseif ((Functions::is_listings() || Functions::is_listing_taxonomy()) && is_active_sidebar('rtcl-archive-sidebar')) { ?>
    <div class="<?php echo esc_attr($sidebar_class); ?>">
        <?php dynamic_sidebar('rtcl-archive-sidebar'); ?>
    </div>
<?php } else if (Functions::is_listing() && is_active_sidebar('rtcl-single-sidebar')) { ?>
    <div class="<?php echo esc_attr($sidebar_class); ?>">
        <?php dynamic_sidebar('rtcl-single-sidebar'); ?>
    </div>
<?php } ?>
