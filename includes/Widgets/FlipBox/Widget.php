<?php
/**
 * Flip Box widget.
 *
 * A card with a front and back face that flips in 3D. Default trigger is hover
 * (pure CSS — no JavaScript). An optional "click" trigger is handled by a small
 * accessible vanilla-JS file (keyboard + focus + aria). Respects reduced motion.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\FlipBox;

use FWFE\Base\Widget_Base;
use FWFE\Helpers\Icon;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

/**
 * Flip Box.
 */
class Widget extends Widget_Base {

	/**
	 * Machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-flip-box';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Flip Box', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-flip-box';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'flip', 'box', 'card', '3d', 'hover', 'flip box', 'fwfe' );
	}

	/**
	 * Allowed flip directions.
	 *
	 * @return array
	 */
	private function allowed_directions() {
		return array( 'left', 'right', 'up', 'down' );
	}

	/**
	 * Register the Content controls. (Style controls come from register_style_controls().)
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ============================================================== FRONT */
		$this->start_controls_section(
			'section_front',
			array(
				'label' => esc_html__( 'Front', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'front_icon',
			array(
				'label'   => esc_html__( 'Icon', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
			)
		);

		$this->add_control(
			'front_title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Front Title', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'front_text',
			array(
				'label'   => esc_html__( 'Text', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'A short teaser shown on the front of the card.', 'free-widgets-for-elementor' ),
			)
		);

		$this->end_controls_section();

		/* =============================================================== BACK */
		$this->start_controls_section(
			'section_back',
			array(
				'label' => esc_html__( 'Back', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'back_title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Back Title', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'back_text',
			array(
				'label'   => esc_html__( 'Text', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'More detail revealed after the card flips over.', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Learn More', 'free-widgets-for-elementor' ),
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

		/* =============================================================== FLIP */
		$this->start_controls_section(
			'section_flip',
			array(
				'label' => esc_html__( 'Flip', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'flip_direction',
			array(
				'label'   => esc_html__( 'Direction', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'left'  => esc_html__( 'Left', 'free-widgets-for-elementor' ),
					'right' => esc_html__( 'Right', 'free-widgets-for-elementor' ),
					'up'    => esc_html__( 'Up', 'free-widgets-for-elementor' ),
					'down'  => esc_html__( 'Down', 'free-widgets-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'flip_trigger',
			array(
				'label'       => esc_html__( 'Trigger', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'hover',
				'options'     => array(
					'hover' => esc_html__( 'Hover', 'free-widgets-for-elementor' ),
					'click' => esc_html__( 'Click', 'free-widgets-for-elementor' ),
				),
				'description' => esc_html__( 'Hover flips with pure CSS. Click adds an accessible flip button and keyboard support.', 'free-widgets-for-elementor' ),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * Register the Style-tab controls (Box, Front, Back, Button).
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
			'box_min_height',
			array(
				'label'      => esc_html__( 'Min Height', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 150,
						'max' => 700,
					),
				),
				'default'    => array(
					'size' => 320,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-flip-box' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_align',
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
					'{{WRAPPER}} .fwfe-flip-box__face' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 80 ) ),
				'default'   => array( 'size' => 12 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__face' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .fwfe-flip-box__face',
			)
		);

		$this->add_responsive_control(
			'face_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-flip-box__face' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================= STYLE: FRONT */
		$this->start_controls_section(
			'section_style_front',
			array(
				'label' => esc_html__( 'Front', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'front_background',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__front' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'front_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'front_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__front .fwfe-flip-box__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .fwfe-flip-box__front .fwfe-flip-box__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'front_icon_size',
			array(
				'label'     => esc_html__( 'Icon Size', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 12,
						'max' => 120,
					),
				),
				'default'   => array( 'size' => 44 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__front .fwfe-flip-box__icon'     => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .fwfe-flip-box__front .fwfe-flip-box__icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'front_title_heading',
			array(
				'label'     => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'front_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__front .fwfe-flip-box__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_title_typography',
				'selector' => '{{WRAPPER}} .fwfe-flip-box__front .fwfe-flip-box__title',
			)
		);

		$this->add_control(
			'front_text_heading',
			array(
				'label'     => esc_html__( 'Text', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'front_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#eef2ff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__front .fwfe-flip-box__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'front_text_typography',
				'selector' => '{{WRAPPER}} .fwfe-flip-box__front .fwfe-flip-box__text',
			)
		);

		$this->end_controls_section();

		/* ======================================================== STYLE: BACK */
		$this->start_controls_section(
			'section_style_back',
			array(
				'label' => esc_html__( 'Back', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'back_background',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#111827',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__back' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'back_title_heading',
			array(
				'label'     => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'back_title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__back .fwfe-flip-box__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'back_title_typography',
				'selector' => '{{WRAPPER}} .fwfe-flip-box__back .fwfe-flip-box__title',
			)
		);

		$this->add_control(
			'back_text_heading',
			array(
				'label'     => esc_html__( 'Text', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'back_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#d1d5db',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__back .fwfe-flip-box__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'back_text_typography',
				'selector' => '{{WRAPPER}} .fwfe-flip-box__back .fwfe-flip-box__text',
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
				'selector' => '{{WRAPPER}} .fwfe-flip-box__button',
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
				'default'   => '#111827',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__button' => 'background-color: {{VALUE}};',
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
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__button:hover, {{WRAPPER}} .fwfe-flip-box__button:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_bg_hover',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-flip-box__button:hover, {{WRAPPER}} .fwfe-flip-box__button:focus' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .fwfe-flip-box__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .fwfe-flip-box__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Frontend output.
	 *
	 * Hover trigger is pure CSS. Click trigger adds a flip button + data-trigger
	 * hook consumed by assets/js/widgets/flip-box.js (accessible: keyboard, focus,
	 * aria). Every dynamic value is escaped.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$direction = isset( $settings['flip_direction'] ) && in_array( $settings['flip_direction'], $this->allowed_directions(), true )
			? $settings['flip_direction']
			: 'left';
		$trigger   = ( isset( $settings['flip_trigger'] ) && 'click' === $settings['flip_trigger'] ) ? 'click' : 'hover';

		$front_title = isset( $settings['front_title'] ) ? trim( (string) $settings['front_title'] ) : '';
		$front_text  = isset( $settings['front_text'] ) ? trim( (string) $settings['front_text'] ) : '';
		$back_title  = isset( $settings['back_title'] ) ? trim( (string) $settings['back_title'] ) : '';
		$back_text   = isset( $settings['back_text'] ) ? trim( (string) $settings['back_text'] ) : '';
		$has_icon    = ! empty( $settings['front_icon']['value'] );

		$classes = array(
			'fwfe-flip-box',
			'fwfe-flip-box--' . $direction,
			'fwfe-flip-box--' . $trigger,
		);
		$this->add_render_attribute( 'wrapper', 'class', $classes );
		$this->add_render_attribute( 'wrapper', 'data-trigger', $trigger );

		$has_button = '' !== trim( (string) ( $settings['button_text'] ?? '' ) );
		if ( $has_button ) {
			$this->add_render_attribute( 'button', 'class', 'fwfe-flip-box__button' );
			if ( ! empty( $settings['button_link']['url'] ) ) {
				$this->add_link_attributes( 'button', $settings['button_link'] );
			}
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor escapes attribute strings. ?>>
			<div class="fwfe-flip-box__inner">

				<div class="fwfe-flip-box__face fwfe-flip-box__front">
					<div class="fwfe-flip-box__face-content">
						<?php if ( $has_icon ) : ?>
							<span class="fwfe-flip-box__icon">
								<?php Icon::render( $settings['front_icon'] ); ?>
							</span>
						<?php endif; ?>
						<?php if ( '' !== $front_title ) : ?>
							<h3 class="fwfe-flip-box__title"><?php echo esc_html( $front_title ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $front_text ) : ?>
							<p class="fwfe-flip-box__text"><?php echo esc_html( $front_text ); ?></p>
						<?php endif; ?>
						<?php if ( 'click' === $trigger ) : ?>
							<button type="button" class="fwfe-flip-box__flip" aria-expanded="false">
								<span class="fwfe-flip-box__flip-icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M21 3v5h-5"/></svg>
								</span>
								<span class="fwfe-flip-box__flip-label"><?php echo esc_html__( 'Flip card', 'free-widgets-for-elementor' ); ?></span>
							</button>
						<?php endif; ?>
					</div>
				</div>

				<div class="fwfe-flip-box__face fwfe-flip-box__back"<?php echo 'click' === $trigger ? ' tabindex="-1"' : ''; ?>>
					<div class="fwfe-flip-box__face-content">
						<?php if ( '' !== $back_title ) : ?>
							<h3 class="fwfe-flip-box__title"><?php echo esc_html( $back_title ); ?></h3>
						<?php endif; ?>
						<?php if ( '' !== $back_text ) : ?>
							<p class="fwfe-flip-box__text"><?php echo esc_html( $back_text ); ?></p>
						<?php endif; ?>
						<?php if ( $has_button ) : ?>
							<a <?php echo $this->get_render_attribute_string( 'button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor escapes attribute strings. ?>>
								<?php echo esc_html( $settings['button_text'] ); ?>
							</a>
						<?php endif; ?>
					</div>
				</div>

			</div>
		</div>
		<?php
	}
}
