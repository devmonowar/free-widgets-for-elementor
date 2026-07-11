<?php
/**
 * Countdown Timer widget.
 *
 * Counts down to a target date/time, showing Days / Hours / Minutes / Seconds.
 * The due date is converted to a UTC timestamp on the server (timezone-correct)
 * and emitted as data-due (seconds); initial values are computed server-side so
 * the timer is never blank before the vanilla JS takes over.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Countdown;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * Countdown Timer.
 */
class Widget extends Widget_Base {

	/**
	 * Machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-countdown';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Countdown Timer', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-countdown';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'countdown', 'timer', 'clock', 'date', 'event', 'fwfe' );
	}

	/**
	 * Register the Content controls. (Style controls come in the Style tab step.)
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ============================================================ GENERAL */
		$this->start_controls_section(
			'section_countdown',
			array(
				'label' => esc_html__( 'Countdown', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'       => esc_html__( 'Due Date', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'The countdown ends at this date and time (your site timezone).', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::DATE_TIME,
				/* Default to roughly one week out so the widget is never empty. */
				'default'     => gmdate( 'Y-m-d H:i', time() + WEEK_IN_SECONDS ),
			)
		);

		$this->end_controls_section();

		/* ============================================================== UNITS */
		$this->start_controls_section(
			'section_units',
			array(
				'label' => esc_html__( 'Units', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_days',
			array(
				'label'        => esc_html__( 'Show Days', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_days',
			array(
				'label'     => esc_html__( 'Days Label', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Days', 'free-widgets-for-elementor' ),
				'condition' => array( 'show_days' => 'yes' ),
			)
		);

		$this->add_control(
			'show_hours',
			array(
				'label'        => esc_html__( 'Show Hours', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_hours',
			array(
				'label'     => esc_html__( 'Hours Label', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Hours', 'free-widgets-for-elementor' ),
				'condition' => array( 'show_hours' => 'yes' ),
			)
		);

		$this->add_control(
			'show_minutes',
			array(
				'label'        => esc_html__( 'Show Minutes', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_minutes',
			array(
				'label'     => esc_html__( 'Minutes Label', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Minutes', 'free-widgets-for-elementor' ),
				'condition' => array( 'show_minutes' => 'yes' ),
			)
		);

		$this->add_control(
			'show_seconds',
			array(
				'label'        => esc_html__( 'Show Seconds', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'label_seconds',
			array(
				'label'     => esc_html__( 'Seconds Label', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Seconds', 'free-widgets-for-elementor' ),
				'condition' => array( 'show_seconds' => 'yes' ),
			)
		);

		$this->end_controls_section();

		/* ============================================================ EXPIRED */
		$this->start_controls_section(
			'section_expired',
			array(
				'label' => esc_html__( 'Expired', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'expired_message',
			array(
				'label'       => esc_html__( 'Expired Message', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Shown when the countdown reaches zero. Leave blank to hide.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array( 'active' => true ),
				'default'     => esc_html__( 'This offer has expired.', 'free-widgets-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * Register the Style-tab controls (Box, Number, Label, Expired).
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
					'flex-start' => array(
						'title' => esc_html__( 'Left', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'     => array(
						'title' => esc_html__( 'Center', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => esc_html__( 'Right', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-countdown__units'   => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} .fwfe-countdown__expired' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'box_background',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#111827',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-countdown__unit' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-countdown__unit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
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
					'{{WRAPPER}} .fwfe-countdown__unit' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_gap',
			array(
				'label'     => esc_html__( 'Gap Between Units', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 80 ) ),
				'default'   => array( 'size' => 16 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-countdown__units' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_min_width',
			array(
				'label'     => esc_html__( 'Unit Min Width', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 200 ) ),
				'default'   => array( 'size' => 72 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-countdown__unit' => 'min-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ====================================================== STYLE: NUMBER */
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
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-countdown__number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'selector' => '{{WRAPPER}} .fwfe-countdown__number',
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
				'default'   => '#d1d5db',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-countdown__label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .fwfe-countdown__label',
			)
		);

		$this->add_responsive_control(
			'label_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 40 ) ),
				'default'   => array( 'size' => 6 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-countdown__label' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ===================================================== STYLE: EXPIRED */
		$this->start_controls_section(
			'section_style_expired',
			array(
				'label' => esc_html__( 'Expired Message', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'expired_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#dc2626',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-countdown__expired' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'expired_typography',
				'selector' => '{{WRAPPER}} .fwfe-countdown__expired',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend render.
	 *
	 * The due date (Elementor stores it in the site's local timezone) is turned
	 * into a UTC epoch on the server so the JS is timezone-correct, and the
	 * initial values are computed server-side so nothing flashes empty.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$due_raw = isset( $settings['due_date'] ) ? trim( (string) $settings['due_date'] ) : '';
		$due_ts  = 0;
		if ( '' !== $due_raw ) {
			try {
				$dt     = new \DateTime( $due_raw, wp_timezone() );
				$due_ts = $dt->getTimestamp();
			} catch ( \Exception $e ) {
				$due_ts = 0;
			}
		}

		$remaining = max( 0, $due_ts - time() );
		$expired   = ( $due_ts > 0 && 0 === $remaining );

		$days    = (int) floor( $remaining / DAY_IN_SECONDS );
		$hours   = (int) floor( ( $remaining % DAY_IN_SECONDS ) / HOUR_IN_SECONDS );
		$minutes = (int) floor( ( $remaining % HOUR_IN_SECONDS ) / MINUTE_IN_SECONDS );
		$seconds = (int) ( $remaining % MINUTE_IN_SECONDS );

		$units = array(
			'days'    => array(
				'show'  => 'yes' === ( $settings['show_days'] ?? 'yes' ),
				'value' => $days,
				'label' => (string) ( $settings['label_days'] ?? '' ),
			),
			'hours'   => array(
				'show'  => 'yes' === ( $settings['show_hours'] ?? 'yes' ),
				'value' => $hours,
				'label' => (string) ( $settings['label_hours'] ?? '' ),
			),
			'minutes' => array(
				'show'  => 'yes' === ( $settings['show_minutes'] ?? 'yes' ),
				'value' => $minutes,
				'label' => (string) ( $settings['label_minutes'] ?? '' ),
			),
			'seconds' => array(
				'show'  => 'yes' === ( $settings['show_seconds'] ?? 'yes' ),
				'value' => $seconds,
				'label' => (string) ( $settings['label_seconds'] ?? '' ),
			),
		);

		$expired_message = isset( $settings['expired_message'] ) ? trim( (string) $settings['expired_message'] ) : '';

		$this->add_render_attribute(
			'wrapper',
			array(
				'class'    => 'fwfe-countdown',
				'data-due' => (string) $due_ts,
			)
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by Elementor. ?>>
			<div class="fwfe-countdown__units"<?php echo $expired ? ' hidden' : ''; ?>>
				<?php foreach ( $units as $key => $unit ) : ?>
					<?php if ( $unit['show'] ) : ?>
						<div class="fwfe-countdown__unit fwfe-countdown__unit--<?php echo esc_attr( $key ); ?>">
							<span class="fwfe-countdown__number" data-unit="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( sprintf( '%02d', $unit['value'] ) ); ?></span>
							<?php if ( '' !== trim( $unit['label'] ) ) : ?>
								<span class="fwfe-countdown__label"><?php echo esc_html( $unit['label'] ); ?></span>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php if ( '' !== $expired_message ) : ?>
				<div class="fwfe-countdown__expired"<?php echo $expired ? '' : ' hidden'; ?>><?php echo esc_html( $expired_message ); ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
