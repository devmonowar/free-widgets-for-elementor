<?php
/**
 * Call To Action (CTA) widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Cta;

use FWFE\Base\Widget_Base;
use FWFE\Helpers\Icon;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

defined( 'ABSPATH' ) || exit;

/**
 * A call-to-action box with title, text and a button.
 */
class Widget extends Widget_Base {

	/**
	 * Allowed title tags.
	 *
	 * @return array
	 */
	private function allowed_tags() {
		return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
	}

	/**
	 * Widget machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-cta';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Call To Action', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'cta', 'call to action', 'banner', 'button', 'fwfe' );
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
				'label' => esc_html__( 'Call To Action', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Ready to get started?', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Join thousands of happy users today. It only takes a minute.', 'free-widgets-for-elementor' ),
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
				'label'       => esc_html__( 'Button Link', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'https://your-link.com', 'free-widgets-for-elementor' ),
				'default'     => array( 'url' => '#' ),
			)
		);

		$this->add_control(
			'button_icon',
			array(
				'label' => esc_html__( 'Button Icon', 'free-widgets-for-elementor' ),
				'type'  => Controls_Manager::ICONS,
				'skin'  => 'inline',
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
					'{{WRAPPER}} .fwfe-cta' => 'text-align: {{VALUE}};',
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

		/* ------------------------------------------------------- Style: Box */
		$this->start_controls_section(
			'section_style_box',
			array(
				'label' => esc_html__( 'Box', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_background',
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .fwfe-cta',
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 48,
					'right'    => 32,
					'bottom'   => 48,
					'left'     => 32,
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-cta' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* --------------------------------------------------- Style: Content */
		$this->start_controls_section(
			'section_style_content',
			array(
				'label' => esc_html__( 'Content', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-cta__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title Typography', 'free-widgets-for-elementor' ),
				'selector' => '{{WRAPPER}} .fwfe-cta__title',
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255,255,255,0.85)',
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-cta__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'label'    => esc_html__( 'Description Typography', 'free-widgets-for-elementor' ),
				'selector' => '{{WRAPPER}} .fwfe-cta__description',
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------- Style: Button */
		$this->start_controls_section(
			'section_style_button',
			array(
				'label' => esc_html__( 'Button', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_cta_button' );

		$this->start_controls_tab(
			'tab_cta_btn_normal',
			array( 'label' => esc_html__( 'Normal', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'btn_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-cta__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-cta__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_cta_btn_hover',
			array( 'label' => esc_html__( 'Hover', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'btn_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-cta__button:hover, {{WRAPPER}} .fwfe-cta__button:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'btn_bg_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-cta__button:hover, {{WRAPPER}} .fwfe-cta__button:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-cta__button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$tag      = in_array( $settings['title_tag'], $this->allowed_tags(), true ) ? $settings['title_tag'] : 'h2';
		$has_btn  = '' !== trim( (string) $settings['button_text'] );
		$has_icon = ! empty( $settings['button_icon']['value'] );

		echo '<div class="fwfe-cta"><div class="fwfe-cta__inner">';

		if ( '' !== trim( (string) $settings['title'] ) ) {
			printf(
				'<%1$s class="fwfe-cta__title">%2$s</%1$s>',
				tag_escape( $tag ),
				esc_html( $settings['title'] )
			);
		}

		if ( '' !== trim( (string) $settings['description'] ) ) {
			echo '<div class="fwfe-cta__description">' . esc_html( $settings['description'] ) . '</div>';
		}

		if ( $has_btn ) {
			$this->add_render_attribute( 'button', 'class', 'fwfe-cta__button' );
			if ( ! empty( $settings['button_link']['url'] ) ) {
				$this->add_link_attributes( 'button', $settings['button_link'] );
			}
			echo '<a ' . $this->get_render_attribute_string( 'button' ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by Elementor.
			if ( $has_icon ) {
				echo '<span class="fwfe-cta__button-icon">';
				Icon::render( $settings['button_icon'] );
				echo '</span>';
			}
			echo '<span class="fwfe-cta__button-text">' . esc_html( $settings['button_text'] ) . '</span>';
			echo '</a>';
		}

		echo '</div></div>';
	}
}
