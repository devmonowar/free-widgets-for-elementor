<?php
/**
 * WIDGET TEMPLATE — copy this folder to build a new widget.
 *
 * This is a reference skeleton, NOT a live widget: it is not listed in
 * Helper::widget_registry(), so it is never registered, loaded or shipped.
 * It shows the standard shape every Free Widget follows.
 *
 * ── HOW TO ADD A NEW WIDGET ───────────────────────────────────────────────
 * 1. Copy  includes/Widgets/_Template/  →  includes/Widgets/<PascalSlug>/
 *    (e.g. _Template → PricingTable).
 * 2. In the copy: set the namespace to FWFE\Widgets\<PascalSlug>, and change
 *    get_name() to 'fwfe-<slug>' (e.g. 'fwfe-pricing-table').
 * 3. Replace the demo controls in register_controls() and the markup in
 *    render() with the real widget. Keep the section layout (Content → Style)
 *    and the `.fwfe-<slug>__part` class naming.
 * 4. (Optional) add  assets/css/widgets/<slug>.css  and/or
 *    assets/js/widgets/<slug>.js  — they auto-load only when the widget is on
 *    the page (Widget_Base wires get_style_depends()/get_script_depends()).
 * 5. Register it: add  '<slug>' => __( 'Label', ... )  to
 *    Helper::widget_registry(). That single line gives it the enable toggle,
 *    conditional assets and the Free Widgets category — nothing else needed.
 * ──────────────────────────────────────────────────────────────────────────
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\_Template;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * Template widget — a headline + text block. Replace with the real widget.
 */
class Widget extends Widget_Base {

	/**
	 * Machine name — MUST be 'fwfe-<slug>' and match the registry slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-_template';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Template', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon (any eicon-* class).
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-info-box';
	}

	/**
	 * Search keywords — always include 'fwfe' so users can find our widgets.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'template', 'fwfe' );
	}

	/**
	 * Register the Content and Style control sections.
	 *
	 * @return void
	 */
	protected function register_controls() {

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
				'default' => esc_html__( 'Your title here', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'A short description goes here.', 'free-widgets-for-elementor' ),
			)
		);

		// Responsive control example — writes a CSS rule via `selectors`, so the
		// user controls it and no CSS file is needed for it.
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
					'{{WRAPPER}} .fwfe-_template' => 'text-align: {{VALUE}};',
				),
			)
		);

		/*
		 * MULTI-ITEM WIDGETS (pricing features, price-list rows, etc.):
		 * use a Repeater control — the plugin standard for lists.
		 *
		 * $repeater = new \Elementor\Repeater();
		 * $repeater->add_control( 'item_text', array( 'type' => Controls_Manager::TEXT, ... ) );
		 * $this->add_control( 'items', array(
		 *     'type'    => Controls_Manager::REPEATER,
		 *     'fields'  => $repeater->get_controls(),
		 *     'default' => array( ... ),
		 *     'title_field' => '{{{ item_text }}}',
		 * ) );
		 * Then loop $settings['items'] in render().
		 */

		$this->end_controls_section();

		/* ============================================================== STYLE */
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
				'selectors' => array(
					'{{WRAPPER}} .fwfe-_template__title' => 'color: {{VALUE}};',
				),
			)
		);

		// Group control example — typography for the title.
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .fwfe-_template__title',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend output.
	 *
	 * Rules: root class `fwfe-<slug>`, children `fwfe-<slug>__part` (BEM);
	 * ESCAPE every dynamic value (esc_html / esc_url / esc_attr / wp_kses_post);
	 * pass any JS config through `data-*` attributes (see _template.js).
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$title = isset( $settings['title'] ) ? $settings['title'] : '';
		$desc  = isset( $settings['description'] ) ? $settings['description'] : '';
		?>
		<div class="fwfe-_template">
			<?php if ( '' !== trim( (string) $title ) ) : ?>
				<h3 class="fwfe-_template__title"><?php echo esc_html( $title ); ?></h3>
			<?php endif; ?>
			<?php if ( '' !== trim( (string) $desc ) ) : ?>
				<p class="fwfe-_template__text"><?php echo esc_html( $desc ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
