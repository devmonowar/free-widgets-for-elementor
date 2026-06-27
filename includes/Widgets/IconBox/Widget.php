<?php
/**
 * Icon Box widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\IconBox;

use FWFE\Base\Widget_Base;
use FWFE\Helpers\Icon;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * An icon paired with a title and description.
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
		return 'fwfe-icon-box';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Icon Box', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-icon-box';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'icon', 'box', 'feature', 'service', 'fwfe' );
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
				'label' => esc_html__( 'Icon Box', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'selected_icon',
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
			'title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'This is the heading', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet.', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
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
			'link',
			array(
				'label'       => esc_html__( 'Link', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'https://your-link.com', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'        => esc_html__( 'Icon Position', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'top',
				'options'      => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					),
					'top'   => array(
						'title' => esc_html__( 'Top', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'free-widgets-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'fwfe-icon-box--',
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------------- Style: Icon */
		$this->start_controls_section(
			'section_style_icon',
			array(
				'label' => esc_html__( 'Icon', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => esc_html__( 'Size', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 12,
						'max' => 120,
					),
				),
				'default'   => array( 'size' => 40 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-icon-box__icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_colors' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array( 'label' => esc_html__( 'Normal', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-icon-box__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .fwfe-icon-box__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array( 'label' => esc_html__( 'Hover', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-icon-box:hover .fwfe-icon-box__icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .fwfe-icon-box:hover .fwfe-icon-box__icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'     => esc_html__( 'Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'   => array( 'size' => 16 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-icon-box' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ---------------------------------------------------- Style: Content */
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
				'selectors' => array(
					'{{WRAPPER}} .fwfe-icon-box__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title Typography', 'free-widgets-for-elementor' ),
				'selector' => '{{WRAPPER}} .fwfe-icon-box__title',
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'     => esc_html__( 'Title Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'default'   => array( 'size' => 10 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-icon-box__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'desc_color',
			array(
				'label'     => esc_html__( 'Description Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-icon-box__description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'label'    => esc_html__( 'Description Typography', 'free-widgets-for-elementor' ),
				'selector' => '{{WRAPPER}} .fwfe-icon-box__description',
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

		$has_icon  = ! empty( $settings['selected_icon']['value'] );
		$has_title = '' !== trim( (string) $settings['title'] );
		$has_desc  = '' !== trim( (string) $settings['description'] );

		if ( ! $has_icon && ! $has_title && ! $has_desc ) {
			return;
		}

		$tag      = in_array( $settings['title_tag'], $this->allowed_tags(), true ) ? $settings['title_tag'] : 'h3';
		$has_link = ! empty( $settings['link']['url'] );

		if ( $has_link ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		}

		echo '<div class="fwfe-icon-box">';

		if ( $has_icon ) {
			echo '<div class="fwfe-icon-box__icon-wrap"><span class="fwfe-icon-box__icon">';
			Icon::render( $settings['selected_icon'] );
			echo '</span></div>';
		}

		if ( $has_title || $has_desc ) {
			echo '<div class="fwfe-icon-box__content">';

			if ( $has_title ) {
				$title_text = esc_html( $settings['title'] );
				if ( $has_link ) {
					$title_text = '<a class="fwfe-icon-box__link" ' . $this->get_render_attribute_string( 'link' ) . '>' . $title_text . '</a>';
				}
				printf(
					'<%1$s class="fwfe-icon-box__title">%2$s</%1$s>',
					tag_escape( $tag ),
					$title_text // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- title escaped via esc_html; link attrs escaped by Elementor.
				);
			}

			if ( $has_desc ) {
				echo '<p class="fwfe-icon-box__description">' . esc_html( $settings['description'] ) . '</p>';
			}

			echo '</div>';
		}

		echo '</div>';
	}
}
