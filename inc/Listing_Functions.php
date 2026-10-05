<?php
/**
 * @author  RadiusTheme
 * @since   1.0.0
 * @version 1.0.0
 */

namespace RadiusTheme\Cldoctor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Link;
use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use Rtcl\Models\RtclCFGField;
use Rtcl\Controllers\Hooks\TemplateHooks;
use RtclBooking\Hooks\ActionHooks;
use RtclStore\Controllers\Hooks\TemplateHooks as StoreHooks;
use RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingFrontendHooks as RtrbFrontendHooks;

class Listing_Functions {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_action( 'init', [ $this, 'rtcl_action_hook' ] );
		add_action( 'init', [ $this, 'rtcl_filter_hook' ] );
		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_icon_list_modify' ] );
		add_action( 'wp_ajax_rtcl_advanced_search_by_type', [ $this, 'rtcl_advanced_search_by_type_func' ] );
		add_action( 'wp_ajax_nopriv_rtcl_advanced_search_by_type', [ $this, 'rtcl_advanced_search_by_type_func' ] );
		add_filter( 'rtcl_get_icon_class_list', [ $this, 'rtcl_icon_list_modify' ] );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function theme_support() {
		add_theme_support( 'rtcl' );
	}

	public function rtcl_action_hook() {
		$rtcl_view = isset( $_GET['view'] ) ? sanitize_text_field( wp_unslash( $_GET['view'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $rtcl_view ) && in_array( $rtcl_view, [ 'grid', 'list' ], true ) ) {
			$view = $rtcl_view;
		} else {
			$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
		}

		/* = Listing Archive Hooks
		=====================================================================================================*/
		//Remove Hooks
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		remove_action( 'rtcl_sidebar', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_badges' ], 30 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );


		//Add Hooks 
		add_filter( 'rtcl_bootstrap_dequeue', '__return_false' );

		/* = Listing Single Hooks
		=====================================================================================================*/
		// remove action
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_gallery' ], 30 );
		add_action( 'cldoctor_listing_galley', [ __CLASS__, 'listing_details_gallery' ], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [
			TemplateHooks::class,
			'add_single_listing_inner_sidebar_custom_field'
		], 10 );
		remove_action( 'rtcl_single_listing_inner_sidebar', [
			TemplateHooks::class,
			'add_single_listing_inner_sidebar_action'
		], 20 );
		if ( class_exists( 'RtclStore' ) ) {
			remove_action( 'rtcl_single_store_information', [ StoreHooks::class, 'store_social_media' ], 40 );
			add_action( 'rtcl_single_store_information', [ StoreHooks::class, 'store_social_media' ], 60 );
		}
		// add action
		if ( 'list' === $view ) {
			remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'listing_price' ], 80 );
			add_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_category_price' ], 15 );
		}
        // Seller Verification
        if ( class_exists('RtclSellerVerification' ) ) {
	        remove_action( 'rtcl_listing_seller_information', [ \RtclSellerActionHooks::class, 'listing_sidebar_verified_author' ], 5 );
        }
		remove_action( 'rtcl_single_listing_content', [ TemplateHooks::class, 'add_single_listing_title' ], 5 );

		add_filter( 'rtcl_general_listing_label_settings_options', [ $this, 'cldoctor_custom_listing_options' ] );

		add_filter( 'cldoctor_single_listing_time_format', [ $this, 'cldoctor_change_listing_time_format' ], 20, 1 );

		/* = Override plugin options
		=====================================================================================================*/
		add_filter( 'rtcl_get_listing_display_options', [ $this, 'listing_extra_options' ] );

		/* = Booking addon
		=====================================================================================================*/
		remove_action( 'rtcl_after_single_listing_sidebar', [ ActionHooks::class, 'listing_booking_submit_form' ] );
		add_action( 'cldoctor_booking_form', [ __CLASS__, 'doctor_booking_modal' ] );

		/* ===== Radius Booking ===== */
		if ( class_exists( RtrbFrontendHooks::class ) ) {
            // Primary hooks (core CL template).
            remove_action( 'rtcl_single_listing_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
            // Fallback hooks used by various themes.
            remove_action( 'rtcl_after_single_listing_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
            remove_action( 'rtcl_single_listing_inner_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
		}
	}

	public function rtcl_filter_hook() {
		// Change Grid Column for listing
		add_filter( 'rtcl_listings_grid_columns_class', function () {
			if (is_page_template('templates/listing-map.php')) {
				$columns = 'columns-' . Options::$options['listing_map_grid_cols'];
			} else {
				$columns = 'columns-' . Options::$options['listing_archive_columns'];
			}
			return $columns;
		} );

		add_filter( 'rtcl_listing_the_excerpt', function ( $excerpt ) {
			return wp_trim_words( $excerpt, 25 );
		} );

		// Override Related Listing Item Number
		add_filter( 'rtcl_related_slider_options', function ( $slider_options ) {
			$slider_options = [
				"loop"         => false,
				"autoplay"     => [
					"delay"                => 3000,
					"disableOnInteraction" => false,
					"pauseOnMouseEnter"    => true
				],
				"speed"        => 1000,
				"spaceBetween" => 20,
				"breakpoints"  => [
					0    => [
						"slidesPerView" => 1
					],
					768  => [
						"slidesPerView" => 2
					],
					992  => [
						"slidesPerView" => 3
					],
					1200 => [
						"slidesPerView" => 4
					]
				]
			];

			return $slider_options;
		} );

		add_filter( 'rtcl_widget_filter_ad_type_title', function() {
			return esc_html__( 'Select Type', 'cldoctor' );
		});

		add_filter( 'rtcl_widget_filter_fields', function( $fields ) {
			$fields['max_price_limit'] = [
				'label' => esc_html__( 'Max Price Limit', 'cldoctor' ),
				'type'  => 'text',
				'default'  => '100',
			];
			return $fields;
		});

		add_filter( 'rtcl_widget_filter_update_values', function(  $instance, $new_instance ) {
			$instance['max_price_limit'] = ! empty( $new_instance['max_price_limit'] ) ? $new_instance['max_price_limit'] : 100;
			return $instance;
		}, 11, 2 );

		add_filter( 'rtcl_widget_filter_default_values', function(  $defaults, $instance ) {
			$defaults['max_price_limit'] = ! empty( $instance['max_price_limit'] ) ? $instance['max_price_limit'] : 100;
			return $defaults;
		}, 10, 2 );
	}

	public function rtcl_advanced_search_by_type_func() {
			// phpcs:disable WordPress.Security.NonceVerification.Missing
			$ad_type = isset( $_POST['ad_type'] ) ? sanitize_text_field( wp_unslash( $_POST['ad_type'] ) ) : 'all';
			$style   = isset( $_POST['style'] ) ? sanitize_text_field( wp_unslash( $_POST['style'] ) ) : 'standard';
			// phpcs:enable WordPress.Security.NonceVerification.Missing
			$cat_text = esc_html__( 'Select Category', 'cldoctor' );
			$orderby = strtolower( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_orderby', 'name' ) );
			$order   = strtoupper( Functions::get_option_item( 'rtcl_archive_listing_settings', 'taxonomy_order', 'ASC' ) );
			$selected_category = false;
			if ( get_query_var( 'rtcl_category' ) && $category = get_term_by( 'slug', get_query_var( 'rtcl_category' ), rtcl()->category ) ) {
				$selected_category = $category;
			}
		?>
			<div class="form-group">
				<i class="icon-select-category"></i>
				<?php if ( $style == 'suggestion' || $style == 'standard' ): ?>
					<div class="rtcl-search-input-button cldoctor-search-style-2 rtin-category">
						<?php
						$cat_args = [
							'show_option_none'  => $cat_text,
							'option_none_value' => '',
							'taxonomy'          => rtcl()->category,
							'name'              => 'rtcl_category',
							'id'                => 'rtcl-category-search-' . wp_rand(),
							'class'             => 'form-control rtcl-category-search rtcl-category-search-ajax',
							'selected'          => get_query_var( 'rtcl_category' ),
							'hierarchical'      => true,
							'value_field'       => 'slug',
							'depth'             => Functions::get_category_depth_limit(),
							'orderby'           => $orderby,
							'order'             => ( 'DESC' === $order ) ? 'DESC' : 'ASC',
							'show_count'        => false,
							'hide_empty'        => false,
						];
						if($ad_type !== 'all'){
							$cat_args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
								array(
									'key' => '_rtcl_types',
									'value' => $ad_type,
								)
							);
						}
						if ( '_rtcl_order' === $orderby ) {
							$cat_args['orderby']  = 'meta_value_num';
							$cat_args['meta_key'] = '_rtcl_order'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
						}
						wp_dropdown_categories( $cat_args );
						?>
					</div>
				<?php elseif ( $style == 'dependency' ): ?>
					<div class="rtcl-search-input-button cldoctor-search-style-2 cldoctor-search-dependency rtin-category">
						<?php
							$args = [
								'show_option_none'  => $cat_text,
								'option_none_value' => - 1,
								'taxonomy'          => rtcl()->category,
								'name'              => 'c',
								'class'             => 'form-control rtcl-category-search',
								'selected'          => $selected_category ? $selected_category->term_id : 0,
							];

							if($ad_type !== 'all'){
								$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
									array(
										'key' => '_rtcl_types',
										'value' => $ad_type,
									)
								);
							}
							Functions::dropdown_terms( $args );
						?>
					</div>
				<?php else: ?>
					<div class="rtcl-search-input-button rtcl-search-input-category">
						<span class="search-input-label category-name">
							<?php echo esc_html( $selected_category ? $selected_category->name : $cat_text ); ?>
						</span>
						<input type="hidden" name="rtcl_category" class="rtcl-term-field"
								value="<?php echo esc_attr( $selected_category ? $selected_category->slug : '' ); ?>">
					</div>
				<?php endif; ?>
			</div>
		<?php
		wp_die();
	}

	public function listing_extra_options( $options ) {
		$options['show_doctor_designation'] = esc_html__( 'Doctor Designation', 'cldoctor' );
		$options['show_doctor_meet_time'] = esc_html__( 'Doctor Meet Time', 'cldoctor' );
		$options['show_hospital_time'] = esc_html__( 'Hospital Time', 'cldoctor' );
		$options['show_hospital_slogan'] = esc_html__( 'Hospital Slogan', 'cldoctor' );
		$options['show_raview_rating'] = esc_html__( 'Reviews', 'cldoctor' );
		return $options;
	}

	public function cldoctor_custom_listing_options( $options ) {
		$options['cldoctor_listing_section'] = [
			'title' => esc_html__( 'Listing Settings', 'cldoctor' ),
			'type'  => 'section',
		];
		$options['phone_button_label'] = [
			'title'   => esc_html__( 'Hospital Phone Label', 'cldoctor' ),
			'type'    => 'text',
			'default' => 'Call for Details',
		];
		$options['booking_button_label'] = [
			'title'   => esc_html__( 'Booking Button Label', 'cldoctor' ),
			'type'    => 'text',
			'default' => 'Make An Appointment',
		];
		return $options;
	}

	public static function get_listing_type( $listing ) {
		$listing_types = Functions::get_listing_types();
		$listing_types = empty( $listing_types ) ? [] : $listing_types;

		$type = $listing->get_ad_type();

		if ( $type && ! empty( $listing_types[ $type ] ) ) {
			$result = [
				'label' => $listing_types[ $type ],
				'icon'  => 'fa-tags',
			];
		} else {
			$result = false;
		}

		return $result;
	}

	public static function get_favourites_link( $post_id ) {
		$has_favourites = get_option( 'rtcl_general_settings' );
		if ( isset( $has_favourites['has_favourites'] ) && 'yes' !== $has_favourites['has_favourites'] ) {
			return;
		}
		if ( is_user_logged_in() ) {
			if ( $post_id == 0 ) {
				global $post;
				$post_id = $post->ID;
			}

			$favourites = (array) get_user_meta( get_current_user_id(), 'rtcl_favourites', true );

			if ( in_array( $post_id, $favourites ) ) {
				return '<a href="javascript:void(0)" class="rtcl-favourites" class="rtcl-favourites rtcl-active" data-id="' . $post_id . '"><span class="rtcl-icon rtcl-icon-heart"></span></a>';
			} else {
				return '<a href="javascript:void(0)" class="rtcl-favourites" data-id="' . $post_id . '"><i class="rtcl-icon rtcl-icon-heart-empty"></i></a>';
			}
		} else {
			return '<a href="#" class="rtcl-favourites" data-bs-toggle="modal" data-bs-target="#logoutModalCenter" title="' . esc_html__( "Favourites", 'cldoctor' )
			       . '"><i class="rtcl-icon rtcl-icon-heart-empty"></i></a>';
		}
	}

	public static function logout_user_favourite() {
		global $listing;
		?>
        <!-- Modal -->
        <div class="modal fade" id="logoutModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="logoutModalTitle"><?php esc_html_e( 'Login', 'cldoctor' ); ?></h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="share-icon">
                            <form id="rtcl-login-form" class="form-horizontal" method="post">
								<?php do_action( 'rtcl_login_form_start' ); ?>
                                <div class="form-group">
                                    <label for="rtcl-user-login" class="control-label">
										<?php esc_html_e( 'Username or E-mail', 'cldoctor' ); ?>
                                        <strong class="rtcl-required">*</strong>
                                    </label>
                                    <input type="text" name="username" autocomplete="username"
                                           value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['username'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing ?>"
                                           id="rtcl-user-login" class="form-control" required/>
                                </div>

                                <div class="form-group">
                                    <label for="rtcl-user-pass" class="control-label">
										<?php esc_html_e( 'Password', 'cldoctor' ); ?>
                                        <strong class="rtcl-required">*</strong>
                                    </label>
                                    <input type="password" name="password" id="rtcl-user-pass"
                                           autocomplete="current-password"
                                           class="form-control" required/>
                                </div>

								<?php do_action( 'rtcl_login_form' ); ?>

                                <div class="form-group">
                                    <div id="rtcl-login-g-recaptcha" class="mb-2"></div>
                                    <div id="rtcl-login-g-recaptcha-message"></div>
                                </div>

                                <div class="form-group d-flex align-items-center">
                                    <button type="submit" name="rtcl-login" class="btn btn-primary" value="login">
										<?php esc_html_e( 'Login', 'cldoctor' ); ?>
                                    </button>
                                    <div class="form-check">
                                        <input type="checkbox" name="rememberme" id="rtcl-rememberme" value="forever">
                                        <label class="form-check-label" for="rtcl-rememberme">
											<?php esc_html_e( 'Remember Me', 'cldoctor' ); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p class="rtcl-forgot-password">
                                        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot your password?', 'cldoctor' ); ?></a>
                                    </p>
                                </div>
								<?php do_action( 'rtcl_login_form_end' ); ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	public static function cldoctor_the_time($theId) {
		global $listing;
		$published_date = get_post_datetime( $theId );
		$present_date = new \DateTime("now");
		$past = $published_date->format( 'Y-m-d 04:06:48' );
		$now = $present_date->format( 'Y-m-d 04:06:48' );
		$unix_published_date = get_post_timestamp($theId, 'date');
		$post_age = floor((time() - $unix_published_date) / 86400);
		if($past === $now){
			esc_html_e('Today', 'cldoctor');
		} else {
			if ($post_age < 1) {
				esc_html_e('Today', 'cldoctor');
			} else {
				$listing->the_time();
			}
		}
	}

	public static function cldoctor_listing_categories() {
		global $listing;
		if ( $listing->has_category() && $listing->can_show_category() ){
			$category  = $listing->get_categories();
			$category  = end( $category );
			$cat_color = get_term_meta( $category->term_id, 'rt_category_color', true );
			$cat_color = ! empty( $cat_color ) ? ( strpos( $cat_color, '#' ) === 0 ? $cat_color : '#' . $cat_color ) : '';
		?>
		<a href="<?php echo esc_url( Link::get_category_page_link( $category ) ); ?>" class="category-list">
			<?php if ( $cat_color ) : ?>
			<span class="cat-color-dot" style="background-color: <?php echo esc_attr( $cat_color ); ?>;"></span>
			<?php endif; ?>
			<?php echo esc_html( $category->name ); ?>
		</a>
		<?php }
	}

	public static function cldoctor_listing_list_categories() {
		global $listing;
		if ( $listing->has_category() ){
			$category = $listing->get_categories();
			$category = end( $category );
			$term_id = $category->term_id;
		?>
		<a href="<?php echo esc_url( Link::get_category_page_link( $category ) ); ?>" class="category-list">
			<?php echo esc_html( $category->name ); ?>
		</a>
		<?php }
	}

	public static function cldoctor_listing_excerpt( $excerpt_length ) { ?>
        <p><?php echo wp_kses_post( Helper::cldoctor_excerpt( $excerpt_length ) ); ?></p>
	<?php }

	public static function get_advanced_search_field_html( $field_id ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea>',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
						if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && sanitize_text_field( wp_unslash( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ) == $choice ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
					}

					$field_html
						= sprintf(
						'<div class="search-item search-select"><select name="filters[_field_%d]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
						absint( $field->getFieldId() ),
						$id,
						$field->getLabel(),
						$options_html
					);
					break;
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? (array) $_GET['filters'][ '_field_' . $field->getFieldId() ] : []; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item checkbox-wrapper">%s</div>', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><div class="search-check-box">%s</div></div>', $check_options );
					break;
				case 'number':
					$hidden_field = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( wp_unslash( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ) : '' // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( wp_unslash( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ) : '' // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					);

					$field_html = sprintf(
						'<div class="search-item">
							<div class="price-range">
								<label>%s</label>
								<input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
								%s
							</div>
						</div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf( // phpcs:ignore WordPress.Security.NonceVerification.Recommended
							'data-from="%s"',
							absint( wp_unslash( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf( // phpcs:ignore WordPress.Security.NonceVerification.Recommended
							'data-to="%s"',
							absint( wp_unslash( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ) // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'date':
					$filters 	= ! empty( $_GET['filters'] ) ? (array) $_GET['filters'] : []; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
					$metaKey    = $field->getMetaKey();
					$value      = ! empty( $filters[ $metaKey ] ) ? esc_attr( $filters[ $metaKey ] ) : null;

					$field_html .= ! empty( $filters[ $id ] ) ? esc_attr( $filters[ $id ] ) : null;
						$isOpen     = $value ? ' is-open' : null;
						$field_html .= sprintf(
							'<div class="form-group">
								<div class="ui-field">
									<input id="filters[%1$s]" autocomplete="off" name="filters[_field_%2$s]" type="text" value="%3$s" data-options="%4$s" class="ui-input form-control rtcl-date" placeholder="%5$s">
								</div>	
							</div>',
							esc_attr( $id ),
							absint( $field->getFieldId() ),
							esc_attr( $value ),
							htmlspecialchars(
								wp_json_encode(
									$field->getDateFieldOptions(
										[
											'singleDatePicker' => $field->getDateSearchableType() === 'single',
											'autoUpdateInput'  => false,
										]
									)
								)
							),
							esc_attr__( 'dd/mm/yyyy', 'cldoctor' ),
						);
					break;
			}
		}

		return $field_html;
	}

	public static function doctor_booking_modal( $listing_id ) {
		if (class_exists('RtclBooking')) {
		?>
        <!-- Modal -->
        <div class="modal fade social-share" id="doctorBookingModal_<?php echo esc_attr( $listing_id ); ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="socialShareModalTitle"><?php esc_html_e( 'Book an Appointment', 'cldoctor' ); ?></h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="close">
							<i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body">
						<?php ActionHooks::listing_booking_submit_form( $listing_id ); ?>
                    </div>
                </div>
            </div>
        </div>
		<?php
		}
	}

	public static function get_share_link() {
		global $listing;
		?>
        <!-- Modal -->
        <div class="modal fade social-share" id="exampleModalCenter" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="socialShareModalTitle"><?php esc_html_e( 'Share This Link Via', 'cldoctor' ); ?></h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="share-icon">
							<?php $listing->the_social_share(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}

	public static function get_repost_abuse(){ ?>
		<!-- Modal -->
		<div class="modal fade report-abuse" id="rtcl-report-abuse-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
					<form id="rtcl-report-abuse-form" class="form-vertical">
						<div class="modal-header">
							<h5 class="modal-title" id="rtcl-report-abuse-modal-label"><?php esc_html_e('Report Abuse', 'cldoctor'); ?></h5>
							<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
								<i class="fa-solid fa-xmark"></i></button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="rtcl-report-abuse-message"><?php esc_html_e('Your Complaint', 'cldoctor'); ?>
									<span class="rtcl-star">*</span></label>
								<textarea class="form-control" name="message" id="rtcl-report-abuse-message" rows="3" placeholder="<?php esc_attr_e('Message... ', 'cldoctor'); ?>" required></textarea>
							</div>
							<div id="rtcl-report-abuse-g-recaptcha"></div>
							<div id="rtcl-report-abuse-message-display"></div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary"><?php esc_html_e('Submit', 'cldoctor'); ?></button>
						</div>
					</form>
				</div>
            </div>
        </div>
		<?php
	}

	public static function cldoctor_single_listing_meta() {
		global $listing;
		$time_format = apply_filters( 'cldoctor_single_listing_time_format', 'F j, Y g:i a' );
		$date        = date_i18n( $time_format,  get_the_time( 'U' ) );
		$has_meta = ( $listing->has_location() && $listing->can_show_location() ) || $listing->can_show_date() || $listing->can_show_views() ? true : false;
		if ( $has_meta ):
		?>
		<!-- Meta data -->
		<div class="rtcl-listing-meta">
			<ul class="rtcl-listing-meta-data">
				<?php if ( $listing->has_location() && $listing->can_show_location() ): ?>
					<li class="rt-location">
						<i class="icon-location-pointer" aria-hidden="true"></i>
						<?php $listing->the_locations( true, true ); ?>
					</li>
				<?php endif; ?>

				<?php if ( $listing->can_show_date() ): ?>
					<li><i class="far fa-clock" aria-hidden="true"></i><?php echo esc_html( $date );?></li>
				<?php endif; ?>

				<?php if ( $listing->can_show_views() ): ?>
					<li class="rt-views"><i class="fa fa-eye" aria-hidden="true"></i><?php /* translators: %s is the number of views */
					echo esc_html( sprintf( esc_html__( '%s views', 'cldoctor' ), number_format_i18n( $listing->get_view_counts() ) ) );?></li>
				<?php endif; ?>
			</ul>
		</div>
		<?php endif;
	}

	public static function listing_details_gallery() {
		global $listing;

		$detailOption = Functions::get_option_item( 'rtcl_single_listing_settings', 'display_options_detail', [] );

		$video_urls = [];
		if ( ! Functions::is_video_urls_disabled() ) {
			$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
			$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
		}
		// Image Gallery
		$images = $listing->get_images();
		unset($images[0]);
		$total_gallery_image = count( $images );
		if ($total_gallery_image < 2) {
			$item_count = 'items-one';
		} elseif ($total_gallery_image < 3 ) {
			$item_count = 'items-two';
		} elseif ($total_gallery_image < 4) {
			$item_count = 'items-three';
		} elseif ($total_gallery_image < 5) {
			$item_count = 'items-four';
		} else {
			$item_count = 'items-five';
		}
		if ( $total_gallery_image ) {
		?>

            <div class="photo-swip-gallery-wrap <?php echo esc_attr($item_count); ?>">
				<?php
					if ( !in_array('video_url', $detailOption) ){
						if ( ! empty( $video_urls ) ) { ?>
						<div class="listing-gallery-item">
							<div class="video-info rtcl-slider-video-item ratio-16x9">
								<iframe class="rtcl-lightbox-iframe"
										src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_urls[0] ) ) ?>"
										style="height: 404px"
										frameborder="0" webkitAllowFullScreen
										mozallowfullscreen allowFullScreen></iframe>
							</div>
						</div>
					<?php }
					}
					$counter = 0;
					foreach ( $images as $image ) {
						++$counter;
						$img_size = 'rtcl-thumbnail';
						?>
						<div class="listing-gallery-item photoswip-item image-size-<?php echo esc_attr($img_size.' item-'.$counter); ?>">
							<?php
								$img_url = wp_get_attachment_image_url( $image->ID, 'full' );
								$getimagesize = getimagesize( $img_url );
								$width = $getimagesize[0];
								$height = $getimagesize[1];
							?>
							<a class="listing-popup-btn" href="<?php echo esc_url( $img_url ); ?>" data-width="<?php echo esc_attr($width); ?>" data-height="<?php echo esc_attr($height); ?>">
								<?php
									echo wp_get_attachment_image( $image->ID, $img_size );
								?>
							</a>
						</div>
					<?php
					}
				?>
            </div>
		<?php }
	}

	public static function listing_layout_class() {
		if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) ) {
			$bodyLayout = Options::$options['listing_archive_box_layout'] ? Options::$options['listing_archive_box_layout'] : 'container';
		}
		$ccols = $bodyLayout != 'container' ? '9' : '8';
		$listing_layout_class = is_active_sidebar('rtcl-archive-sidebar') && Options::$options['listing_layout'] != 'full-width' ? 'col-lg-'.$ccols : 'col-12';
		if ( Options::$options['listing_layout'] == 'right-sidebar' ) {
			$listing_layout_class = $listing_layout_class.' order-lg-1';
		} elseif ( Options::$options['listing_layout'] == 'left-sidebar' ) {
			$listing_layout_class = $listing_layout_class.' order-lg-2';
		} else {
			$listing_layout_class = $listing_layout_class;
		}
		echo esc_attr( apply_filters( 'listing_layout_class', $listing_layout_class ) );
	}

	public static function listing_sidebar() {
		if ( Options::$options['listing_layout'] == 'right-sidebar' || Options::$options['listing_layout'] == 'left-sidebar' && Options::$options['listing_layout'] != 'full-width' && is_active_sidebar('rtcl-archive-sidebar') ) {
			get_sidebar( 'listing' );
		}
	}

	public static function listing_sidebar_class() {
		if ( class_exists('Rtcl') && class_exists( 'RtclPro' ) ) {
			$bodyLayout = Options::$options['listing_archive_box_layout'] ? Options::$options['listing_archive_box_layout'] : 'container';
		}
		$scols = $bodyLayout != 'container' ? '3' : '4';
		if ( Options::$options['listing_layout'] == 'right-sidebar' ) {
			echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-lg-'.$scols.' order-lg-2 listing-sidebar-right' ) );
		} else {
			echo esc_attr( apply_filters( 'rt_sidebar_class', 'col-lg-'.$scols.' order-lg-1 listing-sidebar-left' ) );
		}
	}

	public static function listing_categories_slug() {
		if (class_exists('RtclPro')) {
			$terms = get_terms( "rtcl_category" );
			if(!empty($terms)){
			$category_links = array();
			foreach ($terms as $key => $value) {
				$category_links[$value->term_id] = $value->name;
			}
			return $category_links;
			}
		}
	}

	public static function listing_cat_icon( $term_id, $icon_type = NULL ) {
		$cat_img  = $cat_icon = $icon = null;
		$image_id = get_term_meta( $term_id, '_rtcl_image', true );
		if ( $image_id ) {
			$image_attributes = wp_get_attachment_image_src( (int) $image_id, 'medium' );
			$image            = $image_attributes[0];
			if ( '' !== $image ) {
				$cat_img = sprintf( '<img src="%s" class="rtcl-cat-img" alt="%s"/>', $image, esc_attr__( 'Category Image', 'cldoctor' ) );
			}
		}
		$icon_id = get_term_meta( $term_id, '_rtcl_icon', true );
		if ( $icon_id ) {
			$cat_icon = sprintf( '<span class="rtcl-cat-icon rtcl-icon rtcl-icon-%s"></span>', $icon_id );
		}

		$icon = $icon_type == 'icon' ? $cat_icon : $cat_img;

		return $icon;
	}

	public function cldoctor_change_listing_time_format( $string ) {

		$time_format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
		if ( empty( $time_format ) ) {
			$time_format = $string;
		}

		return $time_format;
	}

	public static function get_min_max_meta_value( $key, $type = 'max' ) {
		global $wpdb;
		$sql   = "SELECT " . $type . "( cast( meta_value as UNSIGNED ) ) FROM {$wpdb->postmeta} WHERE meta_key='%s'"; // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$query = $wpdb->prepare( $sql, $key ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		$value = $wpdb->get_var( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

		return $value;
	}

	public static function listing_price_range() {
		$global_price_min     = self::get_min_max_meta_value( 'price', 'min' );
		$global_price_max     = absint( self::get_min_max_meta_value( 'price' ) );
		$global_max_price_max = absint( self::get_min_max_meta_value( '_rtcl_max_price' ) );
		$max_price            = max( $global_price_max, $global_max_price_max );
		$price_range          = [];

		$price_range['min_price'] = $global_price_min ?? 0;
		$price_range['max_price'] = $max_price;

		return $price_range;
	}

	/**
	 * @param Listing $listing
	 * @param $phone
	 * @param $whatsapp_number
	 */
	public static function cldoctor_seller_phone_number( $listing, $media_type = '' ) {
		if ( is_a( $listing, Listing::class ) ) {
			$phone           = get_post_meta( $listing->get_id(), 'phone', true );
			if ( $phone ) {
				$mobileClass   = wp_is_mobile() ? " rtcl-mobile" : null;
				$phone_options = [];
				if ( $phone ) {
					$phone_options = [
						'safe_phone'   => mb_substr( $phone, 0, mb_strlen( $phone ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' ),
						'phone_hidden' => mb_substr( $phone, - 3 )
					];
				}

				$phone_options = apply_filters( 'rtcl_phone_number_options', $phone_options, [
					'phone'           => $phone
				] )
				?>
                <div class='rtcl-list-group-item reveal-phone<?php echo esc_attr( $mobileClass ); ?>'
                     data-options="<?php
				     echo esc_attr( wp_json_encode( $phone_options ) ); ?>"
                     data-id="<?php echo esc_attr( $listing->get_id() ); ?>">
                    <div class='media'>
                        <div class='media-body'>
                            <div class='numbers'><?php
								if ( $phone && $media_type == 'phone' ) {
									echo esc_html( $phone_options['safe_phone'] );
								} ?></div>
                        </div>
                    </div>
                </div>
				<?php
			}
		}
	}

	/**
	 * @param Listing $listing
	 */
	public static function cldoctor_seller_whatsapp_number( $listing ) {
		if ( is_a( $listing, Listing::class ) ) {
			$whatsapp_number = get_post_meta( $listing->get_id(), '_rtcl_whatsapp_number', true );
			if ( $whatsapp_number && ! Functions::is_field_disabled( 'whatsapp_number' ) ) {
				// wa.me takes digits only, without the plus sign or a leading zero.
				$digits = ltrim( preg_replace( '/\D/', '', $whatsapp_number ), '0' );
				?>
                <div class='rtcl-list-group-item'>
                    <div class='media'>
                        <div class='media-body'>
                            <div class='numbers'>
                                <a href="<?php echo esc_url( 'https://wa.me/' . $digits ); ?>"
                                   target="_blank" rel="noopener"><?php
									echo esc_html( $whatsapp_number ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
		}
	}

	public function rtcl_icon_list_modify( $icons_lists ) {
		$new_icons = [
			" icon-surgeon",
			" icon-grid",
			" icon-filter",
			" icon-warning-empty",
			" icon-tag",
			" icon-flower",
			" icon-tags",
			" icon-minus",
			" icon-paper-plane-1",
			" icon-paper-plane",
			" icon-plus",
			" icon-quote",
			" icon-reply",
			" icon-search",
			" icon-list",
			" icon-calendar",
			" icon-check",
			" icon-chevron-down",
			" icon-chevron-left",
			" icon-location",
			" icon-user-1",
			" icon-user",
			" icon-down-arrow",
			" icon-tree",
			" icon-email",
			" icon-play",
			" icon-cart",
			" icon-clock",
			" icon-arrow-circle",
			" icon-phone",
			" icon-user-2",
			" icon-comment",
			" icon-medicine",
			" icon-ei_user",
			" icon-nimbus_marketing",
			" icon-education",
			" icon-round-border-tick",
			" icon-award",
			" icon-image",
			" icon-video",
			" icon-neurology",
			" icon-share",
			" icon-icon-location-2",
			" icon-location-3",
			" icon-carbon_forum",
			" icon-comment-2",
			" icon-check-2",
			" icon-codicon_feedback",
			" icon-doctor",
			" icon-eye",
			" icon-grid-2",
			" icon-i",
			" icon-location-1",
			" icon-location-pointer",
			" icon-location-pointer-2",
			" icon-lock",
			" icon-paediatrician",
			" icon-phone-2",
			" icon-round-tick",
			" icon-star",
			" icon-subscibe-email",
			" icon-user-3",
			" icon-arrow-double",
			" icon-heart-1",
			" icon-article",
			" icon-available-location",
			" icon-calender-2",
			" icon-calender-3",
			" icon-calender-4",
			" icon-plus-1",
			" icon-select-category",
			" icon-registration",
			" icon-pdf",
			" icon-arrow-right-1",
			" icon-like",
			" icon-heart",
			" icon-cardiology",
			" icon-medicine-1",
			" icon-orthopaedist",
			" icon-trust-badge",
			" icon-lab-equipment",
			" icon-doctor-2",
			" icon-find-doctor",
			" icon-appointment",
			" icon-create-account",
			" icon-dollar-1",
			" icon-dr-file",
			" icon-location-4",
			" icon-oncologist",
			" icon-solar_star-fall",
			" icon-stethoscope-2",
			" icon-stethoscope-1",
			" icon-social-likes",
			" icon-urology",
			" icon-dermatologist",
			" icon-youtube",
			" icon-arrow-curve-1",
			" icon-arrow-curve-2",
		];

		return array_merge( $icons_lists, $new_icons );
	}
}