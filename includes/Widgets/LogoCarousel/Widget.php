<?php
/**
 * Logo Carousel widget.
 *
 * A responsive carousel of client / brand logos. Has NO per-widget JavaScript:
 * it renders the shared `.fwfe-carousel` markup and depends on the shared
 * carousel engine (assets/js/lib/carousel.js) via get_lib_script_depends().
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\LogoCarousel;

use FWFE\Base\Widget_Base;
use FWFE\Core\Assets;
use Elementor\Controls_Manager;
use Elementor\Repeater;

defined( 'ABSPATH' ) || exit;

/**
 * Logo Carousel.
 */
class Widget extends Widget_Base {

	/**
	 * Machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-logo-carousel';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Logo Carousel', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-media-carousel';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'fwfe', 'logo', 'carousel', 'clients', 'brands', 'slider', 'partners' );
	}

	/**
	 * Shared library scripts this widget depends on (the carousel engine).
	 *
	 * @return array
	 */
	protected function get_lib_script_depends() {
		return array( Assets::lib_handle( 'carousel' ) );
	}

	/**
	 * Register the Content controls. (Style controls come from register_style_controls().)
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ============================================================== LOGOS */
		$this->start_controls_section(
			'section_logos',
			array(
				'label' => esc_html__( 'Logos', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Logo', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'       => esc_html__( 'Name / Alt', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Used as the image alt text (and link title).', 'free-widgets-for-elementor' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'https://your-link.com', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'logos',
			array(
				'label'       => esc_html__( 'Logo Items', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name || "Logo" }}}',
				'default'     => array(
					array( 'name' => esc_html__( 'Brand One', 'free-widgets-for-elementor' ) ),
					array( 'name' => esc_html__( 'Brand Two', 'free-widgets-for-elementor' ) ),
					array( 'name' => esc_html__( 'Brand Three', 'free-widgets-for-elementor' ) ),
					array( 'name' => esc_html__( 'Brand Four', 'free-widgets-for-elementor' ) ),
					array( 'name' => esc_html__( 'Brand Five', 'free-widgets-for-elementor' ) ),
					array( 'name' => esc_html__( 'Brand Six', 'free-widgets-for-elementor' ) ),
				),
			)
		);

		$this->end_controls_section();

		/* =========================================================== CAROUSEL */
		$this->start_controls_section(
			'section_carousel',
			array(
				'label' => esc_html__( 'Carousel', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'per_view',
			array(
				'label'   => esc_html__( 'Slides Per View', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 12,
				'step'    => 1,
				'default' => 5,
			)
		);

		$this->add_control(
			'per_view_tablet',
			array(
				'label'   => esc_html__( 'Slides Per View (Tablet)', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 12,
				'step'    => 1,
				'default' => 3,
			)
		);

		$this->add_control(
			'per_view_mobile',
			array(
				'label'   => esc_html__( 'Slides Per View (Mobile)', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 12,
				'step'    => 1,
				'default' => 2,
			)
		);

		$this->add_control(
			'gap',
			array(
				'label'   => esc_html__( 'Gap (px)', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 120,
				'step'    => 1,
				'default' => 30,
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => esc_html__( 'Autoplay Speed (ms)', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1000,
				'max'       => 15000,
				'step'      => 250,
				'default'   => 3000,
				'condition' => array( 'autoplay' => 'yes' ),
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'        => esc_html__( 'Loop', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => esc_html__( 'Show Arrows', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_dots',
			array(
				'label'        => esc_html__( 'Show Dots', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * Register the Style-tab controls (Logo, Arrows, Dots).
	 *
	 * @return void
	 */
	protected function register_style_controls() {

		/* ======================================================== STYLE: LOGO */
		$this->start_controls_section(
			'section_style_logo',
			array(
				'label' => esc_html__( 'Logo', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'logo_max_height',
			array(
				'label'      => esc_html__( 'Max Height', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 20,
						'max' => 200,
					),
				),
				'default'    => array(
					'size' => 60,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-logo-carousel__img' => 'max-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'logo_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-logo-carousel__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'logo_tabs' );

		$this->start_controls_tab(
			'logo_tab_normal',
			array( 'label' => esc_html__( 'Normal', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'logo_opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'default'   => array( 'size' => 1 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-logo-carousel__img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'logo_grayscale',
			array(
				'label'     => esc_html__( 'Grayscale', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'   => array( 'size' => 0 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-logo-carousel__img' => 'filter: grayscale({{SIZE}}%);',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'logo_tab_hover',
			array( 'label' => esc_html__( 'Hover', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'logo_opacity_hover',
			array(
				'label'     => esc_html__( 'Opacity', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.05,
					),
				),
				'default'   => array( 'size' => 1 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-logo-carousel__item:hover .fwfe-logo-carousel__img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_control(
			'logo_grayscale_hover',
			array(
				'label'     => esc_html__( 'Grayscale', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'   => array( 'size' => 0 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-logo-carousel__item:hover .fwfe-logo-carousel__img' => 'filter: grayscale({{SIZE}}%);',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		/* ====================================================== STYLE: ARROWS */
		$this->start_controls_section(
			'section_style_arrows',
			array(
				'label'     => esc_html__( 'Arrows', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_arrows' => 'yes' ),
			)
		);

		$this->add_control(
			'arrow_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#111827',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-carousel__arrow' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrow_bg',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-carousel__arrow' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrow_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color (Hover)', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-carousel__arrow:hover, {{WRAPPER}} .fwfe-carousel__arrow:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrow_bg_hover',
			array(
				'label'     => esc_html__( 'Background (Hover)', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f3f4f6',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-carousel__arrow:hover, {{WRAPPER}} .fwfe-carousel__arrow:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'arrow_size',
			array(
				'label'     => esc_html__( 'Size', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 24,
						'max' => 72,
					),
				),
				'default'   => array( 'size' => 40 ),
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-carousel__arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================== STYLE: DOTS */
		$this->start_controls_section(
			'section_style_dots',
			array(
				'label'     => esc_html__( 'Dots', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_dots' => 'yes' ),
			)
		);

		$this->add_control(
			'dot_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1d5db',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-carousel__dot' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'dot_active_color',
			array(
				'label'     => esc_html__( 'Active Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-carousel__dot.is-active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'dots_spacing',
			array(
				'label'     => esc_html__( 'Top Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'   => array( 'size' => 16 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-carousel__dots' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend output. Renders the shared `.fwfe-carousel` markup that the
	 * carousel engine (assets/js/lib/carousel.js) auto-initialises. Every
	 * dynamic value is escaped.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$logos = ( isset( $settings['logos'] ) && is_array( $settings['logos'] ) ) ? $settings['logos'] : array();
		if ( empty( $logos ) ) {
			return;
		}

		$arrows = 'yes' === ( $settings['show_arrows'] ?? '' );
		$dots   = 'yes' === ( $settings['show_dots'] ?? '' );

		$per_view        = max( 1, (int) ( $settings['per_view'] ?? 5 ) );
		$per_view_tablet = max( 1, (int) ( $settings['per_view_tablet'] ?? 3 ) );
		$per_view_mobile = max( 1, (int) ( $settings['per_view_mobile'] ?? 2 ) );
		$gap             = max( 0, (int) ( $settings['gap'] ?? 30 ) );
		$speed           = max( 1000, (int) ( $settings['autoplay_speed'] ?? 3000 ) );

		$this->add_render_attribute(
			'carousel',
			array(
				'class'                => 'fwfe-carousel fwfe-logo-carousel',
				'role'                 => 'region',
				'aria-roledescription' => esc_attr__( 'carousel', 'free-widgets-for-elementor' ),
				'aria-label'           => esc_attr__( 'Logo carousel', 'free-widgets-for-elementor' ),
				// Translatable labels consumed by the shared carousel JS.
				/* translators: %d: slide group number. */
				'data-dot-label'       => esc_attr__( 'Go to slide group %d', 'free-widgets-for-elementor' ),
				'data-nav-label'       => esc_attr__( 'Slide navigation', 'free-widgets-for-elementor' ),
				'data-per-view'        => (string) $per_view,
				'data-per-view-tablet' => (string) $per_view_tablet,
				'data-per-view-mobile' => (string) $per_view_mobile,
				'data-gap'             => (string) $gap,
				'data-autoplay'        => 'yes' === ( $settings['autoplay'] ?? '' ) ? '1' : '0',
				'data-speed'           => (string) $speed,
				'data-loop'            => 'yes' === ( $settings['loop'] ?? '' ) ? '1' : '0',
				'data-arrows'          => $arrows ? '1' : '0',
				'data-dots'            => $dots ? '1' : '0',
			)
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'carousel' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor escapes attribute strings. ?>>
			<div class="fwfe-carousel__viewport">
				<div class="fwfe-carousel__track">
					<?php foreach ( $logos as $index => $logo ) : ?>
						<div class="fwfe-carousel__slide fwfe-logo-carousel__item">
							<?php $this->render_logo( $logo, $index ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $arrows ) : ?>
				<button type="button" class="fwfe-carousel__arrow fwfe-carousel__arrow--prev" aria-label="<?php echo esc_attr__( 'Previous', 'free-widgets-for-elementor' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
				</button>
				<button type="button" class="fwfe-carousel__arrow fwfe-carousel__arrow--next" aria-label="<?php echo esc_attr__( 'Next', 'free-widgets-for-elementor' ); ?>">
					<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
				</button>
			<?php endif; ?>

			<?php if ( $dots ) : ?>
				<div class="fwfe-carousel__dots"></div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Output one logo image, wrapped in a link when one is set.
	 *
	 * @param array $logo  One repeater row.
	 * @param int   $index Row index (for a unique render-attribute key).
	 * @return void
	 */
	private function render_logo( $logo, $index ) {
		$image = ( isset( $logo['image'] ) && is_array( $logo['image'] ) ) ? $logo['image'] : array();
		$alt   = isset( $logo['name'] ) ? trim( (string) $logo['name'] ) : '';

		$img_html = '';
		if ( ! empty( $image['id'] ) ) {
			$img_html = wp_get_attachment_image(
				(int) $image['id'],
				'medium',
				false,
				array(
					'class' => 'fwfe-logo-carousel__img',
					'alt'   => $alt,
				)
			);
		} elseif ( ! empty( $image['url'] ) ) {
			$img_html = sprintf(
				'<img class="fwfe-logo-carousel__img" src="%1$s" alt="%2$s" loading="lazy" />',
				esc_url( $image['url'] ),
				esc_attr( $alt )
			);
		}

		if ( '' === $img_html ) {
			return;
		}

		$has_link = ! empty( $logo['link']['url'] );
		if ( $has_link ) {
			$key = 'logo_link_' . $index;
			$this->add_render_attribute( $key, 'class', 'fwfe-logo-carousel__link' );
			$this->add_link_attributes( $key, $logo['link'] );
			if ( '' !== $alt ) {
				$this->add_render_attribute( $key, 'aria-label', $alt );
			}
			printf(
				'<a %1$s>%2$s</a>',
				$this->get_render_attribute_string( $key ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor escapes attribute strings.
				$img_html // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image()/escaped <img> above.
			);
			return;
		}

		echo $img_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image()/escaped <img> above.
	}
}
