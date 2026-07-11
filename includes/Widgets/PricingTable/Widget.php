<?php
/**
 * Pricing Table widget.
 *
 * A single pricing plan: header, price, feature list (repeater) and a button,
 * with an optional "featured" highlight. Pure HTML/CSS — no JavaScript.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\PricingTable;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

/**
 * Pricing Table.
 */
class Widget extends Widget_Base {

	/**
	 * Machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-pricing-table';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Pricing Table', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-price-table';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'pricing', 'price', 'table', 'plan', 'package', 'fwfe' );
	}

	/**
	 * Register the Content controls. (Style controls come in the Style tab step.)
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ============================================================= HEADER */
		$this->start_controls_section(
			'section_header',
			array(
				'label' => esc_html__( 'Header', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Plan Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Professional', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'   => esc_html__( 'Subtitle', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'For growing teams', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'featured',
			array(
				'label'        => esc_html__( 'Featured', 'free-widgets-for-elementor' ),
				'description'  => esc_html__( 'Highlight this table as the recommended plan.', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'badge_text',
			array(
				'label'       => esc_html__( 'Badge Text', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Optional ribbon, e.g. "Popular". Leave blank to hide.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Popular', 'free-widgets-for-elementor' ),
			)
		);

		$this->end_controls_section();

		/* ============================================================== PRICE */
		$this->start_controls_section(
			'section_price',
			array(
				'label' => esc_html__( 'Price', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'currency',
			array(
				'label'   => esc_html__( 'Currency Symbol', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '$',
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => esc_html__( 'Price', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => '29',
			)
		);

		$this->add_control(
			'period',
			array(
				'label'   => esc_html__( 'Period', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( '/mo', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'original_price',
			array(
				'label'       => esc_html__( 'Original Price', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Optional. Shown struck-through beside the price.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			)
		);

		$this->end_controls_section();

		/* =========================================================== FEATURES */
		$this->start_controls_section(
			'section_features',
			array(
				'label' => esc_html__( 'Features', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Feature', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Feature item', 'free-widgets-for-elementor' ),
			)
		);

		$repeater->add_control(
			'state',
			array(
				'label'   => esc_html__( 'State', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'included',
				'options' => array(
					'included' => esc_html__( 'Included', 'free-widgets-for-elementor' ),
					'excluded' => esc_html__( 'Excluded', 'free-widgets-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'features',
			array(
				'label'       => esc_html__( 'Feature List', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array(
						'text'  => esc_html__( '10 GB storage', 'free-widgets-for-elementor' ),
						'state' => 'included',
					),
					array(
						'text'  => esc_html__( 'Unlimited projects', 'free-widgets-for-elementor' ),
						'state' => 'included',
					),
					array(
						'text'  => esc_html__( 'Priority support', 'free-widgets-for-elementor' ),
						'state' => 'included',
					),
					array(
						'text'  => esc_html__( 'Custom domain', 'free-widgets-for-elementor' ),
						'state' => 'excluded',
					),
				),
			)
		);

		$this->end_controls_section();

		/* ============================================================= BUTTON */
		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Button', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Get Started', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'   => esc_html__( 'Button Link', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::URL,
				'dynamic' => array( 'active' => true ),
				'default' => array( 'url' => '#' ),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * Register the Style-tab controls (Box, Badge, Header, Price, Features, Button).
	 *
	 * @return void
	 */
	protected function register_style_controls() {

		/* ========================================================= STYLE: BOX */
		$this->start_controls_section(
			'section_style_box',
			array(
				'label' => esc_html__( 'Box', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'box_background',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'selector' => '{{WRAPPER}} .fwfe-pricing-table',
			)
		);

		$this->add_responsive_control(
			'box_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 60 ) ),
				'default'   => array( 'size' => 12 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .fwfe-pricing-table',
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-pricing-table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'featured_accent',
			array(
				'label'       => esc_html__( 'Featured Accent', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Border and badge colour used when "Featured" is on.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '#6366f1',
				'selectors'   => array(
					'{{WRAPPER}} .fwfe-pricing-table--featured' => 'border-color: {{VALUE}}; box-shadow: 0 0 0 2px {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================= STYLE: BADGE */
		$this->start_controls_section(
			'section_style_badge',
			array(
				'label' => esc_html__( 'Badge', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'badge_bg',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'badge_typography',
				'selector' => '{{WRAPPER}} .fwfe-pricing-table__badge',
			)
		);

		$this->end_controls_section();

		/* ====================================================== STYLE: HEADER */
		$this->start_controls_section(
			'section_style_header',
			array(
				'label' => esc_html__( 'Header', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .fwfe-pricing-table__title',
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => esc_html__( 'Subtitle Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__subtitle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .fwfe-pricing-table__subtitle',
			)
		);

		$this->end_controls_section();

		/* ======================================================= STYLE: PRICE */
		$this->start_controls_section(
			'section_style_price',
			array(
				'label' => esc_html__( 'Price', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Price Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#111827',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__currency, {{WRAPPER}} .fwfe-pricing-table__amount' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .fwfe-pricing-table__amount',
			)
		);

		$this->add_control(
			'period_color',
			array(
				'label'     => esc_html__( 'Period Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__period' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'original_color',
			array(
				'label'     => esc_html__( 'Original Price Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__original' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ==================================================== STYLE: FEATURES */
		$this->start_controls_section(
			'section_style_features',
			array(
				'label' => esc_html__( 'Features', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'features_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__feature-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'features_typography',
				'selector' => '{{WRAPPER}} .fwfe-pricing-table__feature-text',
			)
		);

		$this->add_responsive_control(
			'features_gap',
			array(
				'label'     => esc_html__( 'Row Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 40 ) ),
				'default'   => array( 'size' => 10 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__feature' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'included_icon_color',
			array(
				'label'     => esc_html__( 'Included Icon', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#16a34a',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__feature--included .fwfe-pricing-table__feature-icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'excluded_icon_color',
			array(
				'label'     => esc_html__( 'Excluded Icon', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#9ca3af',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__feature--excluded .fwfe-pricing-table__feature-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .fwfe-pricing-table__feature--excluded .fwfe-pricing-table__feature-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ====================================================== STYLE: BUTTON */
		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => esc_html__( 'Button', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .fwfe-pricing-table__button',
			)
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_tab_normal',
			array( 'label' => esc_html__( 'Normal', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			array( 'label' => esc_html__( 'Hover', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'button_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__button:hover, {{WRAPPER}} .fwfe-pricing-table__button:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_hover',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4f46e5',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__button:hover, {{WRAPPER}} .fwfe-pricing-table__button:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-pricing-table__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 60 ) ),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-pricing-table__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'button_full_width',
			array(
				'label'        => esc_html__( 'Full Width', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => array(
					'{{WRAPPER}} .fwfe-pricing-table__button' => 'display: block; width: 100%;',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend output. Pure HTML/CSS.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$featured = 'yes' === ( $settings['featured'] ?? '' );
		$classes  = 'fwfe-pricing-table' . ( $featured ? ' fwfe-pricing-table--featured' : '' );
		$title    = $settings['title'] ?? '';
		$subtitle = $settings['subtitle'] ?? '';
		$currency = $settings['currency'] ?? '';
		$price    = $settings['price'] ?? '';
		$period   = $settings['period'] ?? '';
		$badge    = isset( $settings['badge_text'] ) ? trim( (string) $settings['badge_text'] ) : '';
		$original = isset( $settings['original_price'] ) ? trim( (string) $settings['original_price'] ) : '';
		$features = ( isset( $settings['features'] ) && is_array( $settings['features'] ) ) ? $settings['features'] : array();

		$has_button = '' !== trim( (string) ( $settings['button_text'] ?? '' ) );
		if ( $has_button ) {
			$this->add_render_attribute( 'button', 'class', 'fwfe-pricing-table__button' );
			if ( ! empty( $settings['button_link']['url'] ) ) {
				$this->add_link_attributes( 'button', $settings['button_link'] );
			}
		}
		?>
		<div class="<?php echo esc_attr( $classes ); ?>">

			<?php if ( '' !== $badge ) : ?>
				<span class="fwfe-pricing-table__badge"><?php echo esc_html( $badge ); ?></span>
			<?php endif; ?>

			<div class="fwfe-pricing-table__header">
				<?php if ( '' !== trim( (string) $title ) ) : ?>
					<h3 class="fwfe-pricing-table__title"><?php echo esc_html( $title ); ?></h3>
				<?php endif; ?>
				<?php if ( '' !== trim( (string) $subtitle ) ) : ?>
					<p class="fwfe-pricing-table__subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>

			<div class="fwfe-pricing-table__price">
				<?php if ( '' !== $original ) : ?>
					<span class="fwfe-pricing-table__original"><?php echo esc_html( $currency . $original ); ?></span>
				<?php endif; ?>
				<span class="fwfe-pricing-table__currency"><?php echo esc_html( $currency ); ?></span>
				<span class="fwfe-pricing-table__amount"><?php echo esc_html( $price ); ?></span>
				<?php if ( '' !== trim( (string) $period ) ) : ?>
					<span class="fwfe-pricing-table__period"><?php echo esc_html( $period ); ?></span>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $features ) ) : ?>
				<ul class="fwfe-pricing-table__features">
					<?php foreach ( $features as $feature ) : ?>
						<?php
						$state    = ( isset( $feature['state'] ) && 'excluded' === $feature['state'] ) ? 'excluded' : 'included';
						$li_class = 'fwfe-pricing-table__feature fwfe-pricing-table__feature--' . $state;
						?>
						<li class="<?php echo esc_attr( $li_class ); ?>">
							<span class="fwfe-pricing-table__feature-icon" aria-hidden="true"><?php echo self::icon( $state ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed inline SVG from self::icon(). ?></span>
							<span class="fwfe-pricing-table__feature-text"><?php echo esc_html( $feature['text'] ?? '' ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<?php if ( $has_button ) : ?>
				<div class="fwfe-pricing-table__footer">
					<a <?php echo $this->get_render_attribute_string( 'button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor escapes attribute strings. ?>>
						<?php echo esc_html( $settings['button_text'] ); ?>
					</a>
				</div>
			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Fixed inline SVG tick / cross for a feature state. Uses currentColor so
	 * it inherits the icon colour set in the Style tab.
	 *
	 * @param string $state 'included' or 'excluded'.
	 * @return string Safe, fixed SVG markup.
	 */
	private static function icon( $state ) {
		if ( 'excluded' === $state ) {
			return '<svg viewBox="0 0 20 20" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 5l10 10M15 5L5 15"/></svg>';
		}
		return '<svg viewBox="0 0 20 20" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10l4 4 8-9"/></svg>';
	}
}
