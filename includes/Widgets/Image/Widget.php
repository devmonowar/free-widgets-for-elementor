<?php
/**
 * Image widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Image;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Utils;

defined( 'ABSPATH' ) || exit;

/**
 * A responsive image with optional caption and link.
 */
class Widget extends Widget_Base {

	/**
	 * Widget machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-image';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Image', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'image', 'photo', 'picture', 'fwfe' );
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
				'label' => esc_html__( 'Image', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Choose Image', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array( 'active' => true ),
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'default' => 'large',
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
					'{{WRAPPER}} .fwfe-image' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'caption_source',
			array(
				'label'   => esc_html__( 'Caption', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'       => esc_html__( 'None', 'free-widgets-for-elementor' ),
					'attachment' => esc_html__( 'Attachment Caption', 'free-widgets-for-elementor' ),
					'custom'     => esc_html__( 'Custom Caption', 'free-widgets-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'caption',
			array(
				'label'     => esc_html__( 'Custom Caption', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'default'   => '',
				'condition' => array( 'caption_source' => 'custom' ),
			)
		);

		$this->add_control(
			'link_to',
			array(
				'label'   => esc_html__( 'Link', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => esc_html__( 'None', 'free-widgets-for-elementor' ),
					'file'   => esc_html__( 'Media File', 'free-widgets-for-elementor' ),
					'custom' => esc_html__( 'Custom URL', 'free-widgets-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Custom URL', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'https://your-link.com', 'free-widgets-for-elementor' ),
				'condition'   => array( 'link_to' => 'custom' ),
				'show_label'  => false,
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
				'label' => esc_html__( 'Image', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'      => esc_html__( 'Width', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px', 'vw' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-image__img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.01,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-image__img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .fwfe-image__img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-image__img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .fwfe-image__img',
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

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' );
		if ( empty( $image_html ) ) {
			return;
		}

		// Resolve link target.
		$link = '';
		if ( 'custom' === $settings['link_to'] && ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
			$link = $this->get_render_attribute_string( 'link' );
		} elseif ( 'file' === $settings['link_to'] && ! empty( $settings['image']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', esc_url( $settings['image']['url'] ) );
			$link = $this->get_render_attribute_string( 'link' );
		}

		// Resolve caption text.
		$caption = '';
		if ( 'custom' === $settings['caption_source'] ) {
			$caption = $settings['caption'];
		} elseif ( 'attachment' === $settings['caption_source'] && ! empty( $settings['image']['id'] ) ) {
			$caption = wp_get_attachment_caption( (int) $settings['image']['id'] );
		}

		echo '<figure class="fwfe-image">';

		if ( '' !== $link ) {
			echo '<a ' . $link . '>' . $image_html . '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- link escaped above, image markup from Elementor.
		} else {
			echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup generated by Elementor (escaped).
		}

		if ( ! empty( $caption ) ) {
			echo '<figcaption class="fwfe-image__caption">' . esc_html( $caption ) . '</figcaption>';
		}

		echo '</figure>';
	}
}
