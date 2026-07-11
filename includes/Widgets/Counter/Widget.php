<?php
/**
 * Counter widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Counter;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * An animated number counter.
 */
class Widget extends Widget_Base {

	/**
	 * Widget machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-counter';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Counter', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'counter', 'number', 'count', 'stats', 'fwfe' );
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
				'label' => esc_html__( 'Counter', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'starting_number',
			array(
				'label'   => esc_html__( 'Starting Number', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
			)
		);

		$this->add_control(
			'ending_number',
			array(
				'label'   => esc_html__( 'Ending Number', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 250,
			)
		);

		$this->add_control(
			'prefix',
			array(
				'label'   => esc_html__( 'Number Prefix', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->add_control(
			'suffix',
			array(
				'label'   => esc_html__( 'Number Suffix', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '+',
			)
		);

		$this->add_control(
			'duration',
			array(
				'label'   => esc_html__( 'Animation Duration (ms)', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 2000,
				'min'     => 100,
				'step'    => 100,
			)
		);

		$this->add_control(
			'thousand_separator',
			array(
				'label'        => esc_html__( 'Thousand Separator', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'On', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'Off', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Happy Customers', 'free-widgets-for-elementor' ),
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
					'{{WRAPPER}} .fwfe-counter' => 'text-align: {{VALUE}};',
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

		/* ----------------------------------------------------- Style: Number */
		$this->start_controls_section(
			'section_style_number',
			array(
				'label' => esc_html__( 'Number', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'number_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-counter__number-wrap' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'selector' => '{{WRAPPER}} .fwfe-counter__number-wrap',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------------ Style: Title */
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
					'{{WRAPPER}} .fwfe-counter__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .fwfe-counter__title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-counter__title' => 'margin-top: {{SIZE}}{{UNIT}};',
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

		$from      = isset( $settings['starting_number'] ) ? (float) $settings['starting_number'] : 0;
		$to        = isset( $settings['ending_number'] ) ? (float) $settings['ending_number'] : 0;
		$duration  = isset( $settings['duration'] ) ? absint( $settings['duration'] ) : 2000;
		$separator = ( 'yes' === $settings['thousand_separator'] ) ? '1' : '0';

		$this->add_render_attribute(
			'number',
			array(
				'class'          => 'fwfe-counter__number',
				'data-from'      => $from,
				'data-to'        => $to,
				'data-duration'  => $duration,
				'data-separator' => $separator,
			)
		);
		?>
		<div class="fwfe-counter">
			<div class="fwfe-counter__number-wrap">
				<?php if ( '' !== $settings['prefix'] ) : ?>
					<span class="fwfe-counter__prefix"><?php echo esc_html( $settings['prefix'] ); ?></span>
				<?php endif; ?>
				<span <?php echo $this->get_render_attribute_string( 'number' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by Elementor. ?>><?php echo esc_html( (string) $from ); ?></span>
				<?php if ( '' !== $settings['suffix'] ) : ?>
					<span class="fwfe-counter__suffix"><?php echo esc_html( $settings['suffix'] ); ?></span>
				<?php endif; ?>
			</div>
			<?php if ( '' !== trim( (string) $settings['title'] ) ) : ?>
				<div class="fwfe-counter__title"><?php echo esc_html( $settings['title'] ); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
