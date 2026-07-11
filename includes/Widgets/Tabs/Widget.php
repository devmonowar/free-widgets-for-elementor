<?php
/**
 * Tabs widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Tabs;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * An accessible tabs widget (WAI-ARIA tabs pattern).
 */
class Widget extends Widget_Base {

	/**
	 * Widget machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-tabs';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Tabs', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'tabs', 'tab', 'fwfe' );
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
				'label' => esc_html__( 'Tabs', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tab Title', 'free-widgets-for-elementor' ),
				'dynamic' => array( 'active' => true ),
			)
		);

		$repeater->add_control(
			'tab_content',
			array(
				'label'   => esc_html__( 'Content', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Tab content goes here. Edit this text.', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => esc_html__( 'Tabs', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ tab_title }}}',
				'default'     => array(
					array(
						'tab_title'   => esc_html__( 'Tab #1', 'free-widgets-for-elementor' ),
						'tab_content' => esc_html__( 'Content for the first tab.', 'free-widgets-for-elementor' ),
					),
					array(
						'tab_title'   => esc_html__( 'Tab #2', 'free-widgets-for-elementor' ),
						'tab_content' => esc_html__( 'Content for the second tab.', 'free-widgets-for-elementor' ),
					),
				),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'        => esc_html__( 'Layout', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'horizontal',
				'options'      => array(
					'horizontal' => esc_html__( 'Horizontal', 'free-widgets-for-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'free-widgets-for-elementor' ),
				),
				'prefix_class' => 'fwfe-tabs--',
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

		/* --------------------------------------------------------- Style: Tabs */
		$this->start_controls_section(
			'section_style_tabs',
			array(
				'label' => esc_html__( 'Tabs', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tab_color',
			array(
				'label'     => esc_html__( 'Title Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-tabs__tab' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => esc_html__( 'Active Title Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-tabs__tab[aria-selected="true"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_active_border',
			array(
				'label'     => esc_html__( 'Active Indicator Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-tabs__tab[aria-selected="true"]' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_typography',
				'selector' => '{{WRAPPER}} .fwfe-tabs__tab',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------------ Style: Content */
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
					'{{WRAPPER}} .fwfe-tabs__panel' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .fwfe-tabs__panel',
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'      => 20,
					'right'    => 20,
					'bottom'   => 20,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-tabs__panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$base = $this->get_id();
		?>
		<div class="fwfe-tabs">
			<div class="fwfe-tabs__nav" role="tablist" aria-label="<?php esc_attr_e( 'Tabs', 'free-widgets-for-elementor' ); ?>">
				<?php foreach ( $settings['items'] as $index => $item ) : ?>
					<button type="button"
						class="fwfe-tabs__tab"
						role="tab"
						id="fwfe-tab-<?php echo esc_attr( $base . '-' . $index ); ?>"
						aria-controls="fwfe-tabpanel-<?php echo esc_attr( $base . '-' . $index ); ?>"
						aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>">
						<?php echo esc_html( $item['tab_title'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="fwfe-tabs__panels">
				<?php foreach ( $settings['items'] as $index => $item ) : ?>
					<div class="fwfe-tabs__panel"
						role="tabpanel"
						id="fwfe-tabpanel-<?php echo esc_attr( $base . '-' . $index ); ?>"
						aria-labelledby="fwfe-tab-<?php echo esc_attr( $base . '-' . $index ); ?>"
						tabindex="0"
						<?php echo 0 === $index ? '' : 'hidden'; ?>>
						<?php echo wp_kses_post( $item['tab_content'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
