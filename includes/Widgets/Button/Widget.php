<?php
/**
 * Button widget.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\Button;

use FWFE\Base\Widget_Base;
use FWFE\Helpers\Icon;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

defined( 'ABSPATH' ) || exit;

/**
 * A flexible call-to-action button with optional icon.
 */
class Widget extends Widget_Base {

	/**
	 * Widget machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-button';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Button', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'button', 'link', 'cta', 'fwfe' );
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
				'label' => esc_html__( 'Button', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Text', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
				'default' => esc_html__( 'Click Here', 'free-widgets-for-elementor' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array( 'active' => true ),
				'placeholder' => esc_html__( 'https://your-link.com', 'free-widgets-for-elementor' ),
				'default'     => array( 'url' => '#' ),
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => esc_html__( 'Size', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => array(
					'sm' => esc_html__( 'Small', 'free-widgets-for-elementor' ),
					'md' => esc_html__( 'Medium', 'free-widgets-for-elementor' ),
					'lg' => esc_html__( 'Large', 'free-widgets-for-elementor' ),
					'xl' => esc_html__( 'Extra Large', 'free-widgets-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'       => esc_html__( 'Icon', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'     => esc_html__( 'Icon Position', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'row',
				'options'   => array(
					'row'         => esc_html__( 'Before', 'free-widgets-for-elementor' ),
					'row-reverse' => esc_html__( 'After', 'free-widgets-for-elementor' ),
				),
				'condition' => array( 'selected_icon[value]!' => '' ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-button' => 'flex-direction: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_indent',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 50 ) ),
				'default'   => array( 'size' => 8 ),
				'condition' => array( 'selected_icon[value]!' => '' ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-button' => 'gap: {{SIZE}}{{UNIT}};',
				),
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
					'{{WRAPPER}} .fwfe-button__wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->register_style_controls();
	}

	/**
	 * Register the Style-tab controls.
	 *
	 * @return void
	 */
	protected function register_style_controls() {

		/* ------------------------------------------------------------ Style */
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Button', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .fwfe-button',
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array( 'label' => esc_html__( 'Normal', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array( 'label' => esc_html__( 'Hover', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-button:hover, {{WRAPPER}} .fwfe-button:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-button:hover, {{WRAPPER}} .fwfe-button:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .fwfe-button',
			)
		);

		$this->add_responsive_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .fwfe-button',
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => esc_html__( 'Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$tag = 'span';
		$this->add_render_attribute( 'button', 'class', array( 'fwfe-button', 'fwfe-button--' . $settings['size'] ) );

		if ( ! empty( $settings['link']['url'] ) ) {
			$tag = 'a';
			$this->add_link_attributes( 'button', $settings['link'] );
		}

		$has_icon = ! empty( $settings['selected_icon']['value'] );

		$this->add_render_attribute( 'wrapper', 'class', 'fwfe-button__wrapper' );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by Elementor. ?>>
			<<?php echo tag_escape( $tag ); ?> <?php echo $this->get_render_attribute_string( 'button' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by Elementor. ?>>
				<?php if ( $has_icon ) : ?>
					<span class="fwfe-button__icon"><?php Icon::render( $settings['selected_icon'] ); ?></span>
				<?php endif; ?>
				<span class="fwfe-button__text"><?php echo esc_html( $settings['text'] ); ?></span>
			</<?php echo tag_escape( $tag ); ?>>
		</div>
		<?php
	}
}
