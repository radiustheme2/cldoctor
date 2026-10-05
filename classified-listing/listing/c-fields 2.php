<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates/listing
 * @version       3.0.0
 *
 * @var Form  $form
 * @var array $fields
 * @var int   $listing_id
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use Rtcl\Models\Form\Form;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Services\FormBuilder\FBHelper;

if ( ! is_a( $form, Form::class ) ) {
	return;
}

// Section IDs to exclude.
$exclude_section_ids = [
	'offered_services',
];

// Collect field UUIDs that belong to excluded sections.
$excluded_field_uuids = [];
$_all_sections        = method_exists( $form, 'getSections' ) ? (array) $form->getSections() : [];
foreach ( $_all_sections as $_section ) {
	$_section_id = '';
	if ( is_array( $_section ) && isset( $_section['id'] ) ) {
		$_section_id = (string) $_section['id'];
	} elseif ( is_object( $_section ) && isset( $_section->id ) ) {
		$_section_id = (string) $_section->id;
	}
	if ( $_section_id === '' || ! in_array( $_section_id, $exclude_section_ids, true ) ) {
		continue;
	}
	$_containers = [];
	if ( is_array( $_section ) && isset( $_section['containers'] ) ) {
		$_containers = $_section['containers'];
	} elseif ( is_object( $_section ) && isset( $_section->containers ) ) {
		$_containers = (array) $_section->containers;
	}
	if ( empty( $_containers ) ) {
		if ( is_array( $_section ) && isset( $_section['columns'] ) ) {
			$_containers = $_section['columns'];
		} elseif ( is_object( $_section ) && isset( $_section->columns ) ) {
			$_containers = (array) $_section->columns;
		}
	}
	foreach ( (array) $_containers as $_col ) {
		$_col_fields = [];
		if ( is_array( $_col ) && isset( $_col['fields'] ) ) {
			$_col_fields = (array) $_col['fields'];
		} elseif ( is_object( $_col ) && isset( $_col->fields ) ) {
			$_col_fields = (array) $_col->fields;
		}
		foreach ( $_col_fields as $_uuid ) {
			$excluded_field_uuids[] = (string) $_uuid;
		}
	}
}

$fields = $form->getFieldAsGroup( FBField::CUSTOM );
if ( count( $fields ) ) :
	$fields = FBHelper::reOrderCustomField( $fields );
	ob_start();
	foreach ( $fields as $index => $field ) {
		$field = new FBField( $field );
		if ( ! $field->isSingleViewAble() ) {
			continue;
		}

		// Skip fields that belong to excluded sections.
		if ( ! empty( $excluded_field_uuids ) && in_array( $field->getUuid(), $excluded_field_uuids, true ) ) {
			continue;
		}
		$value = $field->getFormattedCustomFieldValue( $listing_id );

		if ( empty( $value ) ) {
			continue;
		}
		$icon = $field->getIconData();
		?>
		<li class="rtcl-cf-<?php echo esc_attr( $field->getElement() ) ?>">
			<?php if ( $field->getElement() === 'url' ) {
				$nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
				<a href="<?php echo esc_url( $value ); ?>"
				   target="<?php echo esc_attr( $field->getTarget() ) ?>"<?php echo esc_html( $nofollow ) ?>><?php echo esc_html( $field->getLabel() ) ?></a>
			<?php } else { ?>
				<div class="cfp-label">
					<?php
					if ( ! empty( $icon['type'] ) && 'class' === $icon['type'] && ! empty( $icon['class'] ) ) {
						?>
						<div class="rtcl-field-icon"><i class="<?php echo esc_attr( $icon['class'] ) ?>"></i></div>
					<?php } ?>
					<span><?php echo esc_html( $field->getLabel() ) ?></span>
				</div>
				<div class="cfp-value">
					<?php if ( 'repeater' === $field->getElement() ) {
						$repeaterFields = $field->getData( 'fields', [] );
						if ( ! empty( $repeaterFields ) && is_array( $value ) ) { ?>
							<div class="cfp-repeater-items">
								<?php
								foreach ( $value as $repeaterIndex => $repeaterValues ) {
									?>
									<div class="cfp-repeater-item">
										<?php
										foreach ( $repeaterFields as $repeaterField ) {
											$rField = new FBField( $repeaterField );
											$value  = $repeaterValues[ $rField->getName() ] ?? '';
											?>
                                            <div class="cfp-repeater-field">
												<?php
												$rIcon = $rField->getIconData();
												if ( ! empty( $rIcon['type'] ) && 'class' === $rIcon['type'] && ! empty( $rIcon['class'] ) ) {
													?>
                                                    <div class="cfp-icon">
                                                        <i class="<?php echo esc_attr( $rIcon['class'] ); ?>"></i>
                                                    </div>
												<?php } if (!empty($rField->getLabel())){ ?>
                                                    <div class="cfp-label">
                                                        <span><?php echo esc_html( $rField->getLabel() ); ?></span>:
                                                    </div>
												<?php } if (!empty($value)){ ?>
                                                    <div class="cfp-value">
														<?php Functions::print_html( FBHelper::getFormattedFieldHtml( $value, $rField ) ); ?>
                                                    </div>
												<?php } ?>
                                            </div>
											<?php
										} ?>
									</div>
									<?php
								} ?>
							</div>
							<?php
						}
					} else {
						Functions::print_html( FBHelper::getFormattedFieldHtml( $value, $field ) );
					} ?>
				</div>
			<?php } ?>
		</li>
		<?php
	}
	$fieldData = ob_get_clean();
	if ( $fieldData ) :
		printf( '<ul class="rtcl-cf-properties">%s</ul>',
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$fieldData );
	endif; ?>
<?php endif;
