<?php
/**
 * Accordion widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Accordion;

use FWFE\Base\Widget_Base;
use FWFE\Helpers\Icon;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * An accessible accordion (one panel open at a time toggling).
 */
class Widget extends Widget_Base {

	/**
	 * Widget machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-accordion';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Accordion', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-accordion';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'accordion', 'toggle', 'faq', 'fwfe' );
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ---------------------------------------------------------- Content */
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Accordion', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'item_title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Accordion Title', 'free-widgets-for-elementor' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'item_content',
			array(
				'label'   => esc_html__( 'Content', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Accordion content goes here. Edit this text.', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Items', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ item_title }}}',
				'default'     => array(
					array(
						'item_title'   => esc_html__( 'Accordion Item #1', 'free-widgets-for-elementor' ),
						'item_content' => esc_html__( 'Content for the first item.', 'free-widgets-for-elementor' ),
					),
					array(
						'item_title'   => esc_html__( 'Accordion Item #2', 'free-widgets-for-elementor' ),
						'item_content' => esc_html__( 'Content for the second item.', 'free-widgets-for-elementor' ),
					),
				),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				),
			)
		);

		$this->add_control(
			'first_open',
			array(
				'label'        => esc_html__( 'Open First Item', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'   => esc_html__( 'Icon', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'selected_active_icon',
			array(
				'label'   => esc_html__( 'Active Icon', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * Register style controls.
	 *
	 * @return void
	 */
	protected function register_style_controls() {

		/* ------------------------------------------------------- Style: Title */
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => esc_html__( 'Header', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-accordion__trigger' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_active_color',
			array(
				'label'     => esc_html__( 'Active Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-accordion__trigger[aria-expanded="true"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_bg',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-accordion__trigger' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .fwfe-accordion__trigger',
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-accordion__trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ----------------------------------------------------- Style: Content */
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => esc_html__( 'Content', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-accordion__content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .fwfe-accordion__content',
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 15,
					'right'    => 15,
					'bottom'   => 15,
					'left'     => 15,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-accordion__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-accordion__item' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['items'] ) ) {
			return;
		}

		$base       = $this->get_id();
		$tag        = in_array( $settings['title_tag'], $this->allowed_tags(), true ) ? $settings['title_tag'] : 'h3';
		$first_open = ( 'yes' === $settings['first_open'] );

		echo '<div class="fwfe-accordion">';

		foreach ( $settings['items'] as $index => $item ) {
			$is_open   = ( 0 === $index && $first_open );
			$header_id = 'fwfe-acc-' . $base . '-h' . $index;
			$panel_id  = 'fwfe-acc-' . $base . '-p' . $index;
			$expanded  = $is_open ? 'true' : 'false';
			?>
			<div class="fwfe-accordion__item">
				<<?php echo tag_escape( $tag ); ?> class="fwfe-accordion__header">
					<button type="button" class="fwfe-accordion__trigger" id="<?php echo esc_attr( $header_id ); ?>" aria-expanded="<?php echo esc_attr( $expanded ); ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
						<span class="fwfe-accordion__title"><?php echo esc_html( $item['item_title'] ); ?></span>
						<span class="fwfe-accordion__icon" aria-hidden="true">
							<span class="fwfe-accordion__icon-closed"><?php Icon::render( $settings['selected_icon'] ); ?></span>
							<span class="fwfe-accordion__icon-open"><?php Icon::render( $settings['selected_active_icon'] ); ?></span>
						</span>
					</button>
				</<?php echo tag_escape( $tag ); ?>>
				<div class="fwfe-accordion__panel" id="<?php echo esc_attr( $panel_id ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $header_id ); ?>" <?php echo $is_open ? '' : 'hidden'; ?>>
					<div class="fwfe-accordion__content"><?php echo wp_kses_post( $item['item_content'] ); ?></div>
				</div>
			</div>
			<?php
		}

		echo '</div>';
	}

	/**
	 * Allowed title tags.
	 *
	 * @return array
	 */
	private function allowed_tags() {
		return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div' );
	}
}
