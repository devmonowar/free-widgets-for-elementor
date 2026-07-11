<?php
/**
 * Progress / Skill Bar widget.
 *
 * A labelled horizontal bar that fills to a target percentage, animating from 0
 * when scrolled into view (vanilla JS + IntersectionObserver, reduced-motion
 * aware).
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\ProgressBar;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * Progress / Skill Bar.
 */
class Widget extends Widget_Base {

	/**
	 * Machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-progress-bar';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Progress Bar', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-skill-bar';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'progress', 'skill', 'bar', 'percentage', 'meter', 'fwfe' );
	}

	/**
	 * Register the Content controls. (Style controls come in the Style tab step.)
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ============================================================ CONTENT */
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Progress', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Label', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Web Design', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'percent',
			array(
				'label'   => esc_html__( 'Percentage', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'step'    => 1,
				'default' => 75,
			)
		);

		$this->add_control(
			'show_percent',
			array(
				'label'        => esc_html__( 'Show Percentage', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'percent_position',
			array(
				'label'     => esc_html__( 'Percentage Position', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'outer',
				'options'   => array(
					'outer' => esc_html__( 'Beside Label', 'free-widgets-for-elementor' ),
					'inner' => esc_html__( 'Inside Bar', 'free-widgets-for-elementor' ),
				),
				'condition' => array( 'show_percent' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * Register the Style-tab controls (Bar, Label, Percentage).
	 *
	 * @return void
	 */
	protected function register_style_controls() {

		/* ========================================================= STYLE: BAR */
		$this->start_controls_section(
			'section_style_bar',
			array(
				'label' => esc_html__( 'Bar', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'bar_height',
			array(
				'label'     => esc_html__( 'Height', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 80 ) ),
				'default'   => array( 'size' => 18 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-progress-bar__track' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'track_bg',
			array(
				'label'     => esc_html__( 'Track Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e5e7eb',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-progress-bar__track' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'fill_color',
			array(
				'label'     => esc_html__( 'Fill Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-progress-bar__fill' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'bar_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 40 ) ),
				'default'   => array( 'size' => 999 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-progress-bar__track' => 'border-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .fwfe-progress-bar__fill' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================= STYLE: LABEL */
		$this->start_controls_section(
			'section_style_label',
			array(
				'label' => esc_html__( 'Label', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1f2937',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-progress-bar__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .fwfe-progress-bar__label',
			)
		);

		$this->add_responsive_control(
			'label_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 40 ) ),
				'default'   => array( 'size' => 8 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-progress-bar__head' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ================================================== STYLE: PERCENTAGE */
		$this->start_controls_section(
			'section_style_percent',
			array(
				'label'     => esc_html__( 'Percentage', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_percent' => 'yes' ),
			)
		);

		$this->add_control(
			'percent_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1f2937',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-progress-bar__percent' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'percent_typography',
				'selector' => '{{WRAPPER}} .fwfe-progress-bar__percent',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend output. JS reads data-percent from __fill and animates its width.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$title   = isset( $settings['title'] ) ? trim( (string) $settings['title'] ) : '';
		$percent = isset( $settings['percent'] ) ? (float) $settings['percent'] : 0;
		if ( $percent < 0 ) {
			$percent = 0;
		}
		if ( $percent > 100 ) {
			$percent = 100;
		}

		$show_percent = 'yes' === ( $settings['show_percent'] ?? '' );
		$position     = ( isset( $settings['percent_position'] ) && 'inner' === $settings['percent_position'] ) ? 'inner' : 'outer';
		$percent_text = $show_percent ? ( (string) $percent . '%' ) : '';
		?>
		<div class="fwfe-progress-bar">

			<?php if ( '' !== $title || ( $show_percent && 'outer' === $position ) ) : ?>
				<div class="fwfe-progress-bar__head">
					<?php if ( '' !== $title ) : ?>
						<span class="fwfe-progress-bar__label"><?php echo esc_html( $title ); ?></span>
					<?php endif; ?>
					<?php if ( $show_percent && 'outer' === $position ) : ?>
						<span class="fwfe-progress-bar__percent fwfe-progress-bar__percent--outer"><?php echo esc_html( $percent_text ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="fwfe-progress-bar__track" role="progressbar" aria-valuenow="<?php echo esc_attr( (string) $percent ); ?>" aria-valuemin="0" aria-valuemax="100"<?php echo '' !== $title ? ' aria-label="' . esc_attr( $title ) . '"' : ''; ?>>
				<div class="fwfe-progress-bar__fill" data-percent="<?php echo esc_attr( (string) $percent ); ?>">
					<?php if ( $show_percent && 'inner' === $position ) : ?>
						<span class="fwfe-progress-bar__percent fwfe-progress-bar__percent--inner"><?php echo esc_html( $percent_text ); ?></span>
					<?php endif; ?>
				</div>
			</div>

		</div>
		<?php
	}
}
