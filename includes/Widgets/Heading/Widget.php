<?php
/**
 * Heading widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Heading;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;

defined( 'ABSPATH' ) || exit;

/**
 * A flexible, accessible heading widget.
 */
class Widget extends Widget_Base {

	/**
	 * Widget machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-heading';
	}

	/**
	 * Widget title shown in the panel.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Heading', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-t-letter';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'heading', 'title', 'text', 'fwfe' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ---------------------------------------------------------- Content */
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Heading', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'Enter your title', 'free-widgets-for-elementor' ),
				'default'     => esc_html__( 'Add Your Heading Text Here', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'https://your-link.com', 'free-widgets-for-elementor' ),
				'default'     => array( 'url' => '' ),
			)
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => esc_html__( 'HTML Tag', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
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
				'default' => 'h2',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => esc_html__( 'Alignment', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-heading' => 'text-align: {{VALUE}};',
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

		/* ------------------------------------------------------------ Style */
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Heading', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_color' );

		$this->start_controls_tab(
			'tab_color_normal',
			array( 'label' => esc_html__( 'Normal', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-heading__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_color_hover',
			array( 'label' => esc_html__( 'Hover', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'hover_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-heading__title:hover, {{WRAPPER}} .fwfe-heading__link:hover .fwfe-heading__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'hover_transition',
			array(
				'label'     => esc_html__( 'Transition Duration (s)', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-heading__title' => 'transition: color {{SIZE}}s;',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .fwfe-heading__title',
			)
		);

		if ( class_exists( '\Elementor\Group_Control_Text_Stroke' ) ) {
			$this->add_group_control(
				Group_Control_Text_Stroke::get_type(),
				array(
					'name'     => 'text_stroke',
					'selector' => '{{WRAPPER}} .fwfe-heading__title',
				)
			);
		}

		$this->end_controls_section();
	}

	/**
	 * Frontend render.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$title = wp_kses_post( $settings['title'] );
		if ( '' === trim( $title ) ) {
			return;
		}

		$tag = in_array( $settings['html_tag'], $this->allowed_tags(), true ) ? $settings['html_tag'] : 'h2';

		$this->add_render_attribute( 'title', 'class', 'fwfe-heading__title' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'class', 'fwfe-heading__link' );
			$this->add_link_attributes( 'link', $settings['link'] );
			$title = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $title . '</a>';
		}

		// Tag is validated against an allow-list; attribute strings are escaped by Elementor.
		printf(
			'<div class="fwfe-heading"><%1$s %2$s>%3$s</%1$s></div>',
			tag_escape( $tag ),
			$this->get_render_attribute_string( 'title' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Elementor escapes attribute strings.
			$title // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses_post applied above.
		);
	}

	/**
	 * Allowed HTML tags for the heading.
	 *
	 * @return array
	 */
	private function allowed_tags() {
		return array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
	}
}
