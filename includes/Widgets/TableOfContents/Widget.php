<?php
/**
 * Table of Contents widget.
 *
 * Renders an empty shell; the vanilla JS (assets/js/widgets/table-of-contents.js)
 * scans the surrounding content for headings at runtime, builds the list, and
 * wires smooth-scroll + scrollspy. PHP cannot know the headings in advance
 * because they may come from native content or other Elementor sections.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\TableOfContents;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

/**
 * Table of Contents.
 */
class Widget extends Widget_Base {

	/**
	 * Machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-table-of-contents';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Table of Contents', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'toc', 'table of contents', 'contents', 'anchor', 'headings', 'navigation', 'fwfe' );
	}

	/**
	 * Register the Content controls. (Style controls come in the Style tab step.)
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ============================================================= SOURCE */
		$this->start_controls_section(
			'section_source',
			array(
				'label' => esc_html__( 'Source', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'heading_levels',
			array(
				'label'    => esc_html__( 'Heading Levels', 'free-widgets-for-elementor' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'default'  => array( 'h2', 'h3' ),
				'options'  => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
			)
		);

		$this->add_control(
			'min_headings',
			array(
				'label'       => esc_html__( 'Minimum Headings', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Hide the widget on the frontend if fewer headings than this are found.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 20,
				'default'     => 2,
			)
		);

		$this->add_control(
			'container_selector',
			array(
				'label'       => esc_html__( 'Content Selector', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Advanced. Leave blank to auto-detect the surrounding content. Enter a CSS selector to scan a specific container instead.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			)
		);

		$this->end_controls_section();

		/* ============================================================ CONTENT */
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Table of Contents', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'numbering',
			array(
				'label'   => esc_html__( 'Numbering', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'    => esc_html__( 'None', 'free-widgets-for-elementor' ),
					'decimal' => esc_html__( 'Numbers', 'free-widgets-for-elementor' ),
					'bullet'  => esc_html__( 'Bullets', 'free-widgets-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'collapsible',
			array(
				'label'        => esc_html__( 'Collapsible', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'collapsed_by_default',
			array(
				'label'        => esc_html__( 'Collapsed by Default', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array( 'collapsible' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* =========================================================== BEHAVIOR */
		$this->start_controls_section(
			'section_behavior',
			array(
				'label' => esc_html__( 'Behavior', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'smooth_scroll',
			array(
				'label'        => esc_html__( 'Smooth Scroll', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'scroll_offset',
			array(
				'label'       => esc_html__( 'Scroll Offset (px)', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Extra space above the heading when jumping to it, e.g. to clear a sticky header.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 300,
				'default'     => 20,
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * Register the Style-tab controls (Box, Title, List, Links, Toggle).
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
			'box_max_width',
			array(
				'label'      => esc_html__( 'Max Width', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'max' => 800 ),
					'%'  => array( 'max' => 100 ),
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-toc' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'box_background',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f9fafb',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'selector' => '{{WRAPPER}} .fwfe-toc',
			)
		);

		$this->add_responsive_control(
			'box_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 60 ) ),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .fwfe-toc',
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 16,
					'right'    => 16,
					'bottom'   => 16,
					'left'     => 16,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-toc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================= STYLE: TITLE */
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#111827',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .fwfe-toc__title',
			)
		);

		$this->end_controls_section();

		/* ======================================================== STYLE: LIST */
		$this->start_controls_section(
			'section_style_list',
			array(
				'label' => esc_html__( 'List', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'item_spacing',
			array(
				'label'     => esc_html__( 'Item Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 40 ) ),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc__item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'indent_per_level',
			array(
				'label'     => esc_html__( 'Indent Per Level', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 60 ) ),
				'default'   => array( 'size' => 16 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc__list' => '--fwfe-toc-indent: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'marker_color',
			array(
				'label'       => esc_html__( 'Marker Color', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Used when Numbering is set to Numbers or Bullets.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'{{WRAPPER}} .fwfe-toc__item::marker' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================= STYLE: LINKS */
		$this->start_controls_section(
			'section_style_links',
			array(
				'label' => esc_html__( 'Links', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .fwfe-toc__link',
			)
		);

		$this->start_controls_tabs( 'link_tabs' );

		$this->start_controls_tab(
			'link_tab_normal',
			array( 'label' => esc_html__( 'Normal', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#374151',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc__link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_tab_hover',
			array( 'label' => esc_html__( 'Hover', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'link_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#111827',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc__link:hover, {{WRAPPER}} .fwfe-toc__link:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_tab_active',
			array( 'label' => esc_html__( 'Active', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'link_color_active',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc__link--active' => 'color: {{VALUE}}; font-weight: 600;',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		/* ====================================================== STYLE: TOGGLE */
		$this->start_controls_section(
			'section_style_toggle',
			array(
				'label'     => esc_html__( 'Toggle', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'collapsible' => 'yes' ),
			)
		);

		$this->add_control(
			'toggle_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6b7280',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc__toggle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_size',
			array(
				'label'     => esc_html__( 'Size', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 40 ) ),
				'default'   => array( 'size' => 18 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-toc__toggle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend output. Renders an empty shell; table-of-contents.js scans the
	 * page for headings and populates the list at runtime.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Heading levels are strictly h1-h6 here (unlike allowed_heading_tags(),
		// which also permits div/span/p for other widgets' "render as" tag controls).
		$allowed_levels = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
		$levels         = ( isset( $settings['heading_levels'] ) && is_array( $settings['heading_levels'] ) ) ? $settings['heading_levels'] : array( 'h2', 'h3' );
		$levels         = array_values( array_intersect( $levels, $allowed_levels ) );
		if ( empty( $levels ) ) {
			$levels = array( 'h2', 'h3' );
		}

		$min_headings = isset( $settings['min_headings'] ) ? absint( $settings['min_headings'] ) : 2;
		if ( $min_headings < 1 ) {
			$min_headings = 1;
		}

		$container_selector = isset( $settings['container_selector'] ) ? trim( (string) $settings['container_selector'] ) : '';

		$title = isset( $settings['title'] ) ? trim( (string) $settings['title'] ) : '';

		$numbering         = ! empty( $settings['numbering'] ) ? $settings['numbering'] : 'none';
		$allowed_numbering = array( 'none', 'decimal', 'bullet' );
		if ( ! in_array( $numbering, $allowed_numbering, true ) ) {
			$numbering = 'none';
		}

		$collapsible = 'yes' === ( $settings['collapsible'] ?? '' );
		$collapsed   = $collapsible && 'yes' === ( $settings['collapsed_by_default'] ?? '' );

		$smooth_scroll = 'yes' === ( $settings['smooth_scroll'] ?? '' );

		$scroll_offset = isset( $settings['scroll_offset'] ) ? absint( $settings['scroll_offset'] ) : 20;

		$list_id = 'fwfe-toc-list-' . $this->get_id();

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'          => 'fwfe-toc',
				'data-levels'    => implode( ',', $levels ),
				'data-min'       => (string) $min_headings,
				'data-selector'  => $container_selector,
				'data-numbering' => $numbering,
				'data-smooth'    => $smooth_scroll ? '1' : '0',
				'data-offset'    => (string) $scroll_offset,
				'data-collapsed' => $collapsed ? '1' : '0',
			)
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by Elementor. ?>>

			<?php if ( '' !== $title || $collapsible ) : ?>
				<div class="fwfe-toc__header">
					<?php if ( '' !== $title ) : ?>
						<span class="fwfe-toc__title"><?php echo esc_html( $title ); ?></span>
					<?php endif; ?>
					<?php if ( $collapsible ) : ?>
						<button type="button" class="fwfe-toc__toggle" aria-expanded="<?php echo $collapsed ? 'false' : 'true'; ?>" aria-controls="<?php echo esc_attr( $list_id ); ?>">
							<?php echo self::toggle_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed inline SVG from self::toggle_icon(). ?>
							<span class="fwfe-screen-reader-text"><?php esc_html_e( 'Toggle table of contents', 'free-widgets-for-elementor' ); ?></span>
						</button>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<nav class="fwfe-toc__nav" aria-label="<?php echo '' !== $title ? esc_attr( $title ) : esc_attr__( 'Table of Contents', 'free-widgets-for-elementor' ); ?>">
				<ol class="fwfe-toc__list fwfe-toc__list--<?php echo esc_attr( $numbering ); ?>" id="<?php echo esc_attr( $list_id ); ?>"<?php echo $collapsed ? ' hidden' : ''; ?>></ol>
				<p class="fwfe-toc__empty" hidden><?php esc_html_e( 'Add headings to your content to build the table of contents.', 'free-widgets-for-elementor' ); ?></p>
			</nav>

		</div>
		<?php
	}

	/**
	 * Fixed inline chevron SVG for the collapse toggle. Uses currentColor so it
	 * inherits the color set in the Style tab; rotates via CSS on state.
	 *
	 * @return string Safe, fixed SVG markup.
	 */
	private static function toggle_icon() {
		return '<svg viewBox="0 0 20 20" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 7.5l5 5 5-5"/></svg>';
	}
}
