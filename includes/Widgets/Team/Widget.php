<?php
/**
 * Team member widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Team;

use FWFE\Base\Widget_Base;
use FWFE\Helpers\Image;
use FWFE\Helpers\Icon;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || exit;

/**
 * A team member card with photo, role, bio and social links.
 */
class Widget extends Widget_Base {

	/**
	 * Allowed name tags.
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
		return 'fwfe-team';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Team', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'team', 'member', 'person', 'staff', 'fwfe' );
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
				'label' => esc_html__( 'Team Member', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Photo', 'free-widgets-for-elementor' ),
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
				'default' => esc_html__( 'John Doe', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'position',
			array(
				'label'   => esc_html__( 'Position', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'CEO & Founder', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'bio',
			array(
				'label'   => esc_html__( 'Bio', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'A short bio about this team member.', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'name_tag',
			array(
				'label'   => esc_html__( 'Name HTML Tag', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'social_icon',
			array(
				'label'   => esc_html__( 'Icon', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fab fa-facebook-f',
					'library' => 'fa-brands',
				),
			)
		);

		$repeater->add_control(
			'social_link',
			array(
				'label'       => esc_html__( 'Link', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'social_icons',
			array(
				'label'       => esc_html__( 'Social Links', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(),
				'title_field' => '{{{ social_link.url }}}',
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
					'{{WRAPPER}} .fwfe-team' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		/* ------------------------------------------------------ Style: Card */
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Photo Size', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 40,
						'max' => 400,
					),
				),
				'default'   => array( 'size' => 150 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-team__image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'image_radius',
			array(
				'label'      => esc_html__( 'Photo Radius', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'size' => 50,
					'unit' => '%',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
					'%'  => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-team__image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'name_color',
			array(
				'label'     => esc_html__( 'Name Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-team__name' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'name_typography',
				'label'    => esc_html__( 'Name Typography', 'free-widgets-for-elementor' ),
				'selector' => '{{WRAPPER}} .fwfe-team__name',
			)
		);

		$this->add_control(
			'position_color',
			array(
				'label'     => esc_html__( 'Position Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-team__position' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bio_color',
			array(
				'label'     => esc_html__( 'Bio Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-team__bio' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_color',
			array(
				'label'     => esc_html__( 'Social Icon Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-team__social-link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .fwfe-team__social-link svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'social_size',
			array(
				'label'     => esc_html__( 'Social Icon Size', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 10,
						'max' => 50,
					),
				),
				'default'   => array( 'size' => 18 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-team__social-link' => 'font-size: {{SIZE}}{{UNIT}};',
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

		$tag = in_array( $settings['name_tag'], $this->allowed_tags(), true ) ? $settings['name_tag'] : 'h3';

		echo '<div class="fwfe-team">';

		if ( ! empty( $settings['image']['url'] ) ) {
			echo '<div class="fwfe-team__image">';
			Image::render( $settings['image'], 'medium', $settings['name'] );
			echo '</div>';
		}

		echo '<div class="fwfe-team__body">';

		if ( '' !== trim( (string) $settings['name'] ) ) {
			printf(
				'<%1$s class="fwfe-team__name">%2$s</%1$s>',
				tag_escape( $tag ),
				esc_html( $settings['name'] )
			);
		}

		if ( '' !== trim( (string) $settings['position'] ) ) {
			echo '<div class="fwfe-team__position">' . esc_html( $settings['position'] ) . '</div>';
		}

		if ( '' !== trim( (string) $settings['bio'] ) ) {
			echo '<p class="fwfe-team__bio">' . esc_html( $settings['bio'] ) . '</p>';
		}

		if ( ! empty( $settings['social_icons'] ) ) {
			echo '<div class="fwfe-team__social">';
			foreach ( $settings['social_icons'] as $item ) {
				if ( empty( $item['social_link']['url'] ) || empty( $item['social_icon']['value'] ) ) {
					continue;
				}
				$key = 'social_' . $item['_id'];
				$this->add_render_attribute( $key, 'class', 'fwfe-team__social-link' );
				$this->add_link_attributes( $key, $item['social_link'] );
				echo '<a ' . $this->get_render_attribute_string( $key ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by Elementor.
				Icon::render( $item['social_icon'] );
				echo '</a>';
			}
			echo '</div>';
		}

		echo '</div></div>';
	}
}
