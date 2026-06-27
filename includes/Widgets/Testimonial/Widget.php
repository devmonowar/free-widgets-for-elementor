<?php
/**
 * Testimonial widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Testimonial;

use FWFE\Base\Widget_Base;
use FWFE\Helpers\Image;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * A single testimonial / quote card.
 */
class Widget extends Widget_Base {

	/**
	 * Widget machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-testimonial';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Testimonial', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-testimonial';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'testimonial', 'quote', 'review', 'fwfe' );
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
				'label' => esc_html__( 'Testimonial', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'content',
			array(
				'label'   => esc_html__( 'Content', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 6,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'This product completely changed how our team works. Highly recommended!', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Image', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
			)
		);

		$this->add_control(
			'name',
			array(
				'label'   => esc_html__( 'Name', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Jane Smith', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Marketing Director', 'free-widgets-for-elementor' ),
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
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-testimonial' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------------------ Style */
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => esc_html__( 'Content Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-testimonial__content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'label'    => esc_html__( 'Content Typography', 'free-widgets-for-elementor' ),
				'selector' => '{{WRAPPER}} .fwfe-testimonial__content',
			)
		);

		$this->add_responsive_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Image Size', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'range'     => array(
					'px' => array(
						'min' => 30,
						'max' => 150,
					),
				),
				'default'   => array( 'size' => 56 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-testimonial__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Name Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-testimonial__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Title Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-testimonial__title' => 'color: {{VALUE}};',
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

		$has_image = ! empty( $settings['image']['url'] );
		$has_name  = '' !== trim( (string) $settings['name'] );
		$has_title = '' !== trim( (string) $settings['title'] );

		echo '<div class="fwfe-testimonial">';

		if ( '' !== trim( (string) $settings['content'] ) ) {
			echo '<div class="fwfe-testimonial__content">' . esc_html( $settings['content'] ) . '</div>';
		}

		if ( $has_image || $has_name || $has_title ) {
			echo '<div class="fwfe-testimonial__meta">';

			if ( $has_image ) {
				echo '<div class="fwfe-testimonial__image">';
				Image::render( $settings['image'], 'thumbnail', $settings['name'] );
				echo '</div>';
			}

			if ( $has_name || $has_title ) {
				echo '<div class="fwfe-testimonial__author">';
				if ( $has_name ) {
					echo '<div class="fwfe-testimonial__name">' . esc_html( $settings['name'] ) . '</div>';
				}
				if ( $has_title ) {
					echo '<div class="fwfe-testimonial__title">' . esc_html( $settings['title'] ) . '</div>';
				}
				echo '</div>';
			}

			echo '</div>';
		}

		echo '</div>';
	}
}
