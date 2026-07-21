<?php
/**
 * Post Grid widget.
 *
 * Displays posts in a responsive CSS-grid of cards (featured image, title,
 * meta, excerpt and read-more). Query built with WP_Query from sanitized
 * controls. Pure HTML/CSS — no JavaScript.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Widgets\PostGrid;

use FWFE\Base\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Post Grid.
 */
class Widget extends Widget_Base {

	/**
	 * Machine name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'fwfe-post-grid';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Grid', 'free-widgets-for-elementor' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Search keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'fwfe', 'post', 'posts', 'blog', 'grid', 'recent', 'article' );
	}

	/**
	 * Register the Content controls. (Style controls come in the Style tab step.)
	 *
	 * @return void
	 */
	protected function register_controls() {

		/* ============================================================== QUERY */
		$this->start_controls_section(
			'section_query',
			array(
				'label' => esc_html__( 'Query', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'post_type',
			array(
				'label'   => esc_html__( 'Post Type', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->get_post_type_options(),
			)
		);

		$this->add_control(
			'category',
			array(
				'label'       => esc_html__( 'Category', 'free-widgets-for-elementor' ),
				'description' => esc_html__( 'Limit to a category. Only applies to the standard Post type.', 'free-widgets-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => $this->get_category_options(),
				'condition'   => array( 'post_type' => 'post' ),
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => esc_html__( 'Posts Per Page', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 50,
				'default' => 6,
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => esc_html__( 'Order By', 'free-widgets-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'       => esc_html__( 'Date', 'free-widgets-for-elementor' ),
					'title'      => esc_html__( 'Title', 'free-widgets-for-elementor' ),
					'menu_order' => esc_html__( 'Menu Order', 'free-widgets-for-elementor' ),
					'rand'       => esc_html__( 'Random', 'free-widgets-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'     => esc_html__( 'Order', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => array(
					'ASC'  => esc_html__( 'Ascending', 'free-widgets-for-elementor' ),
					'DESC' => esc_html__( 'Descending', 'free-widgets-for-elementor' ),
				),
				'condition' => array( 'orderby!' => 'rand' ),
			)
		);

		$this->end_controls_section();

		/* ============================================================= LAYOUT */
		$this->start_controls_section(
			'section_layout',
			array(
				'label' => esc_html__( 'Layout', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'          => esc_html__( 'Columns', 'free-widgets-for-elementor' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
				),
				'selectors'      => array(
					'{{WRAPPER}} .fwfe-post-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'        => esc_html__( 'Show Featured Image', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'free-widgets-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'free-widgets-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'image_size',
			array(
				'label'     => esc_html__( 'Image Size', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'medium_large',
				'options'   => $this->get_image_size_options(),
				'condition' => array( 'show_image' => 'yes' ),
			)
		);

		$this->add_control(
			'show_title',
			array(
				'label'        => esc_html__( 'Show Title', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'     => esc_html__( 'Title HTML Tag', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => array(
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				),
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$this->add_control(
			'show_meta',
			array(
				'label'        => esc_html__( 'Show Meta (Date)', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'        => esc_html__( 'Show Excerpt', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => esc_html__( 'Excerpt Length (words)', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 100,
				'default'   => 20,
				'condition' => array( 'show_excerpt' => 'yes' ),
			)
		);

		$this->add_control(
			'show_read_more',
			array(
				'label'        => esc_html__( 'Show Read More', 'free-widgets-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'read_more_text',
			array(
				'label'     => esc_html__( 'Read More Text', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array( 'active' => true ),
				'default'   => esc_html__( 'Read More', 'free-widgets-for-elementor' ),
				'condition' => array( 'show_read_more' => 'yes' ),
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

		/* ======================================================== STYLE: GRID */
		$this->start_controls_section(
			'section_style_grid',
			array(
				'label' => esc_html__( 'Grid', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => esc_html__( 'Columns Gap', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array( 'px' => array( 'max' => 80 ) ),
				'default'    => array(
					'size' => 24,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-post-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================== STYLE: CARD */
		$this->start_controls_section(
			'section_style_card',
			array(
				'label' => esc_html__( 'Card', 'free-widgets-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_background',
			array(
				'label'     => esc_html__( 'Background', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .fwfe-post-grid__card',
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 60 ) ),
				'default'   => array( 'size' => 12 ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__card' => 'border-radius: {{SIZE}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .fwfe-post-grid__card',
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => esc_html__( 'Content Padding', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array(
					'top'      => 20,
					'right'    => 20,
					'bottom'   => 20,
					'left'     => 20,
					'unit'     => 'px',
					'isLinked' => true,
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-post-grid__body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================= STYLE: IMAGE */
		$this->start_controls_section(
			'section_style_image',
			array(
				'label'     => esc_html__( 'Image', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_image' => 'yes' ),
			)
		);

		$this->add_responsive_control(
			'image_height',
			array(
				'label'      => esc_html__( 'Height', 'free-widgets-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array( 'px' => array( 'max' => 600 ) ),
				'default'    => array(
					'size' => 200,
					'unit' => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .fwfe-post-grid__image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover; width: 100%;',
				),
			)
		);

		$this->add_responsive_control(
			'image_radius',
			array(
				'label'     => esc_html__( 'Border Radius', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array( 'px' => array( 'max' => 60 ) ),
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__image img' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/* ======================================================= STYLE: TITLE */
		$this->start_controls_section(
			'section_style_title',
			array(
				'label'     => esc_html__( 'Title', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_title' => 'yes' ),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__title'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .fwfe-post-grid__title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => esc_html__( 'Hover Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__title a:hover, {{WRAPPER}} .fwfe-post-grid__title a:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .fwfe-post-grid__title',
			)
		);

		$this->end_controls_section();

		/* ======================================================== STYLE: META */
		$this->start_controls_section(
			'section_style_meta',
			array(
				'label'     => esc_html__( 'Meta', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_meta' => 'yes' ),
			)
		);

		$this->add_control(
			'meta_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__meta' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .fwfe-post-grid__meta',
			)
		);

		$this->end_controls_section();

		/* ===================================================== STYLE: EXCERPT */
		$this->start_controls_section(
			'section_style_excerpt',
			array(
				'label'     => esc_html__( 'Excerpt', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_excerpt' => 'yes' ),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .fwfe-post-grid__excerpt',
			)
		);

		$this->end_controls_section();

		/* =================================================== STYLE: READ MORE */
		$this->start_controls_section(
			'section_style_read_more',
			array(
				'label'     => esc_html__( 'Read More', 'free-widgets-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'show_read_more' => 'yes' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'read_more_typography',
				'selector' => '{{WRAPPER}} .fwfe-post-grid__read-more',
			)
		);

		$this->start_controls_tabs( 'read_more_tabs' );

		$this->start_controls_tab(
			'read_more_tab_normal',
			array( 'label' => esc_html__( 'Normal', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'read_more_color',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#6366f1',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__read-more' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'read_more_tab_hover',
			array( 'label' => esc_html__( 'Hover', 'free-widgets-for-elementor' ) )
		);

		$this->add_control(
			'read_more_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'free-widgets-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#4f46e5',
				'selectors' => array(
					'{{WRAPPER}} .fwfe-post-grid__read-more:hover, {{WRAPPER}} .fwfe-post-grid__read-more:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Frontend output. Builds a WP_Query from sanitized args and renders cards.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$query = $this->build_query( $settings );

		if ( ! $query->have_posts() ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<p class="fwfe-post-grid__empty">' . esc_html__( 'No posts found.', 'free-widgets-for-elementor' ) . '</p>';
			}
			wp_reset_postdata();
			return;
		}

		$show_image     = 'yes' === ( $settings['show_image'] ?? '' );
		$show_title     = 'yes' === ( $settings['show_title'] ?? '' );
		$show_meta      = 'yes' === ( $settings['show_meta'] ?? '' );
		$show_excerpt   = 'yes' === ( $settings['show_excerpt'] ?? '' );
		$show_read_more = 'yes' === ( $settings['show_read_more'] ?? '' );

		$image_size    = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'medium_large';
		$excerpt_words = isset( $settings['excerpt_length'] ) ? absint( $settings['excerpt_length'] ) : 20;
		if ( $excerpt_words < 1 ) {
			$excerpt_words = 20;
		}
		$read_more_text = isset( $settings['read_more_text'] ) ? $settings['read_more_text'] : esc_html__( 'Read More', 'free-widgets-for-elementor' );

		$title_tag = ( ! empty( $settings['title_tag'] ) && in_array( $settings['title_tag'], $this->allowed_heading_tags(), true ) ) ? $settings['title_tag'] : 'h3';
		?>
		<div class="fwfe-post-grid">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$permalink = get_permalink();
				?>
				<article class="fwfe-post-grid__card">

					<?php if ( $show_image && has_post_thumbnail() ) : ?>
						<a class="fwfe-post-grid__image" href="<?php echo esc_url( $permalink ); ?>">
							<?php
							echo get_the_post_thumbnail(
								get_the_ID(),
								$image_size,
								array( 'class' => 'fwfe-post-grid__image-img' )
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_the_post_thumbnail() returns escaped, safe markup.
							?>
						</a>
					<?php endif; ?>

					<div class="fwfe-post-grid__body">

						<?php if ( $show_meta ) : ?>
							<div class="fwfe-post-grid__meta">
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<?php echo esc_html( get_the_date() ); ?>
								</time>
							</div>
						<?php endif; ?>

						<?php if ( $show_title ) : ?>
							<<?php echo tag_escape( $title_tag ); ?> class="fwfe-post-grid__title">
								<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
							</<?php echo tag_escape( $title_tag ); ?>>
						<?php endif; ?>

						<?php if ( $show_excerpt ) : ?>
							<div class="fwfe-post-grid__excerpt">
								<?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), $excerpt_words ) ); ?>
							</div>
						<?php endif; ?>

						<?php if ( $show_read_more && '' !== trim( (string) $read_more_text ) ) : ?>
							<a class="fwfe-post-grid__read-more" href="<?php echo esc_url( $permalink ); ?>">
								<?php echo esc_html( $read_more_text ); ?>
							</a>
						<?php endif; ?>

					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php
		wp_reset_postdata();
	}

	/**
	 * Build a sanitized WP_Query from the widget settings.
	 *
	 * @param array $settings Widget settings for display.
	 * @return WP_Query
	 */
	private function build_query( $settings ) {
		$post_type = ! empty( $settings['post_type'] ) ? sanitize_key( $settings['post_type'] ) : 'post';
		if ( ! post_type_exists( $post_type ) ) {
			$post_type = 'post';
		}

		$per_page = isset( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 6;
		if ( $per_page < 1 ) {
			$per_page = 6;
		}

		$orderby         = ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date';
		$allowed_orderby = array( 'date', 'title', 'menu_order', 'rand' );
		if ( ! in_array( $orderby, $allowed_orderby, true ) ) {
			$orderby = 'date';
		}

		$order = ( isset( $settings['order'] ) && 'ASC' === strtoupper( $settings['order'] ) ) ? 'ASC' : 'DESC';

		$args = array(
			'post_type'           => $post_type,
			'posts_per_page'      => $per_page,
			'orderby'             => $orderby,
			'order'               => $order,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		);

		if ( 'post' === $post_type && ! empty( $settings['category'] ) ) {
			$args['cat'] = absint( $settings['category'] );
		}

		return new WP_Query( $args );
	}

	/**
	 * Public post type options (excludes attachment).
	 *
	 * @return array
	 */
	private function get_post_type_options() {
		$options = array();
		$types   = get_post_types( array( 'public' => true ), 'objects' );

		foreach ( $types as $type ) {
			if ( 'attachment' === $type->name ) {
				continue;
			}
			$options[ $type->name ] = $type->labels->singular_name;
		}

		if ( empty( $options ) ) {
			$options['post'] = esc_html__( 'Post', 'free-widgets-for-elementor' );
		}

		return $options;
	}

	/**
	 * Category options for the post-type term filter.
	 *
	 * @return array
	 */
	private function get_category_options() {
		$options = array( '' => esc_html__( 'All Categories', 'free-widgets-for-elementor' ) );

		$terms = get_terms(
			array(
				'taxonomy'   => 'category',
				'hide_empty' => false,
			)
		);

		if ( is_array( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( isset( $term->term_id, $term->name ) ) {
					$options[ $term->term_id ] = $term->name;
				}
			}
		}

		return $options;
	}

	/**
	 * Registered image size options.
	 *
	 * @return array
	 */
	private function get_image_size_options() {
		$sizes   = get_intermediate_image_sizes();
		$options = array();

		foreach ( $sizes as $size ) {
			$options[ $size ] = ucwords( str_replace( array( '_', '-' ), ' ', $size ) );
		}

		$options['full'] = esc_html__( 'Full', 'free-widgets-for-elementor' );

		return $options;
	}
}
