<?php
/**
 * Demo Library page.
 *
 * Ready-made Elementor section templates built only with Free Widgets. The
 * library is fetched from a hosted JSON manifest (cached 6 hours) and each demo
 * is imported as an Elementor saved template. This file renders the screen and
 * resolves the manifest; the import handler lives alongside it.
 *
 * @package FreeWidgetsForElementor
 */

namespace FWFE\Admin;

use FWFE\Helpers\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Renders the Demo Library screen and resolves the remote manifest.
 */
final class Demo_Library {

	/**
	 * Admin page slug.
	 */
	const SLUG = 'fwfe-demo-library';

	/**
	 * Transient key for the cached manifest.
	 */
	const TRANSIENT = 'fwfe_demo_manifest';

	/**
	 * Manifest cache lifetime.
	 */
	const CACHE_TTL = 6 * HOUR_IN_SECONDS;

	/**
	 * admin-post action for a one-click import (handler wired in Hooks\Admin).
	 */
	const IMPORT_ACTION = 'fwfe_demo_import';

	/**
	 * The resolved manifest URL. Filterable so the local test rig can point it
	 * at a served copy without touching the frozen public constant.
	 *
	 * @return string
	 */
	public static function manifest_url() {
		return (string) apply_filters( 'fwfe_demo_manifest_url', FWFE_DEMO_MANIFEST_URL );
	}

	/**
	 * Fetch and validate the manifest, cached in a transient.
	 *
	 * @param bool $force Bypass the cache.
	 * @return array|\WP_Error Validated manifest array, or WP_Error on failure.
	 */
	public static function get_manifest( $force = false ) {
		if ( ! $force ) {
			$cached = get_transient( self::TRANSIENT );
			if ( is_array( $cached ) ) {
				return $cached;
			}
		}

		// wp_safe_remote_get validates the host (blocks internal/loopback by
		// default) — defense-in-depth against a tampered manifest URL.
		$response = wp_safe_remote_get(
			self::manifest_url(),
			array(
				'timeout' => 10,
				'headers' => array( 'Accept' => 'application/json' ),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}
		if ( 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return new \WP_Error( 'fwfe_http', __( 'The demo library could not be reached.', 'free-widgets-for-elementor' ) );
		}

		$data = self::validate_manifest( json_decode( wp_remote_retrieve_body( $response ), true ) );
		if ( is_wp_error( $data ) ) {
			return $data;
		}

		set_transient( self::TRANSIENT, $data, self::CACHE_TTL );
		return $data;
	}

	/**
	 * Validate the raw manifest structure and normalise its demos.
	 *
	 * @param mixed $data Decoded JSON.
	 * @return array|\WP_Error
	 */
	private static function validate_manifest( $data ) {
		if ( ! is_array( $data ) || empty( $data['demos'] ) || ! is_array( $data['demos'] ) ) {
			return new \WP_Error( 'fwfe_manifest', __( 'The demo library response was not valid.', 'free-widgets-for-elementor' ) );
		}
		if ( isset( $data['plugin'] ) && 'free-widgets-for-elementor' !== $data['plugin'] ) {
			return new \WP_Error( 'fwfe_manifest', __( 'The demo library is for a different plugin.', 'free-widgets-for-elementor' ) );
		}

		$demos = array();
		foreach ( $data['demos'] as $demo ) {
			if ( ! is_array( $demo ) || empty( $demo['id'] ) || empty( $demo['file'] ) ) {
				continue;
			}
			$file = esc_url_raw( $demo['file'] );
			if ( ! $file || ! in_array( wp_parse_url( $file, PHP_URL_SCHEME ), array( 'http', 'https' ), true ) ) {
				continue;
			}
			$demos[] = array(
				'id'          => sanitize_key( $demo['id'] ),
				'name'        => isset( $demo['name'] ) ? sanitize_text_field( $demo['name'] ) : $demo['id'],
				'description' => isset( $demo['description'] ) ? sanitize_text_field( $demo['description'] ) : '',
				'version'     => isset( $demo['version'] ) ? sanitize_text_field( $demo['version'] ) : '',
				'requires'    => isset( $demo['requires'] ) ? sanitize_text_field( $demo['requires'] ) : '',
				'category'    => isset( $demo['category'] ) ? sanitize_text_field( $demo['category'] ) : '',
				'tags'        => isset( $demo['tags'] ) && is_array( $demo['tags'] ) ? array_map( 'sanitize_text_field', $demo['tags'] ) : array(),
				// Widget slugs the demo uses — drives the enable check on import.
				'widgets'     => isset( $demo['widgets'] ) && is_array( $demo['widgets'] ) ? array_map( 'sanitize_key', $demo['widgets'] ) : array(),
				'featured'    => ! empty( $demo['featured'] ),
				'is_new'      => ! empty( $demo['new'] ),
				'preview'     => isset( $demo['preview'] ) ? esc_url_raw( $demo['preview'] ) : '',
				'file'        => $file,
			);
		}

		if ( ! $demos ) {
			return new \WP_Error( 'fwfe_manifest', __( 'The demo library is empty right now.', 'free-widgets-for-elementor' ) );
		}

		return array(
			'schema_version' => isset( $data['schema_version'] ) ? absint( $data['schema_version'] ) : 1,
			'demos'          => $demos,
		);
	}

	/**
	 * Render the Demo Library page.
	 *
	 * @return void
	 */
	public function render() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// "Refresh" clears the 6-hour cache so newly published demos appear at once.
		if ( isset( $_GET['fwfe_refresh'], $_GET['_wpnonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'fwfe_demo_refresh' ) ) {
			delete_transient( self::TRANSIENT );
		}

		$manifest    = self::get_manifest();
		$refresh_url = wp_nonce_url(
			add_query_arg( 'fwfe_refresh', '1', admin_url( 'admin.php?page=' . self::SLUG ) ),
			'fwfe_demo_refresh'
		);
		?>
		<div class="wrap fwfe-wrap fwfe-demo-library">
			<h1>
				<?php esc_html_e( 'Demo Library', 'free-widgets-for-elementor' ); ?>
				<a href="<?php echo esc_url( $refresh_url ); ?>" class="page-title-action"><?php esc_html_e( 'Refresh', 'free-widgets-for-elementor' ); ?></a>
			</h1>
			<p class="fwfe-tagline"><?php esc_html_e( 'Ready-made sections built with Free Widgets. Import one, then drop it into any page from Templates &rarr; Saved Templates.', 'free-widgets-for-elementor' ); ?></p>

			<?php $this->import_notice(); ?>

			<?php if ( is_wp_error( $manifest ) ) : ?>
				<div class="notice notice-warning inline">
					<p><strong><?php esc_html_e( 'The demo library is unavailable right now.', 'free-widgets-for-elementor' ); ?></strong> <?php echo esc_html( $manifest->get_error_message() ); ?></p>
				</div>
			<?php else : ?>
				<div class="fwfe-demo-grid">
					<?php
					foreach ( $manifest['demos'] as $demo ) {
						$this->card( $demo );
					}
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render a single demo card.
	 *
	 * @param array $demo Normalised demo entry.
	 * @return void
	 */
	private function card( $demo ) {
		$can_import = '' === $demo['requires'] || version_compare( FWFE_VERSION, $demo['requires'], '>=' );
		?>
		<div class="fwfe-demo-card">
			<div class="fwfe-demo-card__preview">
				<?php if ( $demo['preview'] ) : ?>
					<img src="<?php echo esc_url( $demo['preview'] ); ?>" alt="<?php echo esc_attr( $demo['name'] ); ?>" loading="lazy" />
				<?php endif; ?>
				<?php if ( $demo['featured'] ) : ?>
					<span class="fwfe-demo-badge fwfe-demo-badge--featured"><?php esc_html_e( 'Featured', 'free-widgets-for-elementor' ); ?></span>
				<?php elseif ( $demo['is_new'] ) : ?>
					<span class="fwfe-demo-badge fwfe-demo-badge--new"><?php esc_html_e( 'New', 'free-widgets-for-elementor' ); ?></span>
				<?php endif; ?>
			</div>
			<div class="fwfe-demo-card__body">
				<h3 class="fwfe-demo-card__title"><?php echo esc_html( $demo['name'] ); ?></h3>
				<?php if ( $demo['description'] ) : ?>
					<p class="fwfe-demo-card__desc"><?php echo esc_html( $demo['description'] ); ?></p>
				<?php endif; ?>
				<?php if ( $demo['category'] ) : ?>
					<p class="fwfe-demo-card__cat"><?php echo esc_html( $demo['category'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="fwfe-demo-card__actions">
				<?php if ( $can_import ) : ?>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<input type="hidden" name="action" value="<?php echo esc_attr( self::IMPORT_ACTION ); ?>" />
						<input type="hidden" name="demo_id" value="<?php echo esc_attr( $demo['id'] ); ?>" />
						<?php wp_nonce_field( self::IMPORT_ACTION . '_' . $demo['id'] ); ?>
						<?php submit_button( __( 'Import', 'free-widgets-for-elementor' ), 'primary', 'submit', false ); ?>
					</form>
				<?php else : ?>
					<button type="button" class="button" disabled><?php esc_html_e( 'Import', 'free-widgets-for-elementor' ); ?></button>
					<p class="fwfe-demo-card__requires">
						<?php
						/* translators: %s: required plugin version. */
						printf( esc_html__( 'Requires Free Widgets For Elementor %s+', 'free-widgets-for-elementor' ), esc_html( $demo['requires'] ) );
						?>
					</p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Show the import result notice at the top of the page.
	 *
	 * @return void
	 */
	private function import_notice() {
		// Display only; the import itself was nonce-checked in handle_import().
		if ( ! isset( $_GET['fwfe_import'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		$status = sanitize_key( wp_unslash( $_GET['fwfe_import'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'ok' === $status ) {
			$new_id   = isset( $_GET['fwfe_new'] ) ? absint( wp_unslash( $_GET['fwfe_new'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$edit_url = $new_id ? admin_url( 'post.php?post=' . $new_id . '&action=elementor' ) : '';
			$list_url = admin_url( 'edit.php?post_type=elementor_library' );
			?>
			<div class="notice notice-success is-dismissible">
				<p>
					<strong><?php esc_html_e( 'Demo imported.', 'free-widgets-for-elementor' ); ?></strong>
					<?php esc_html_e( 'It has been added to your saved templates.', 'free-widgets-for-elementor' ); ?>
					<?php if ( $edit_url ) : ?>
						<a href="<?php echo esc_url( $edit_url ); ?>"><?php esc_html_e( 'Edit in Elementor', 'free-widgets-for-elementor' ); ?></a> &middot;
					<?php endif; ?>
					<a href="<?php echo esc_url( $list_url ); ?>"><?php esc_html_e( 'View saved templates', 'free-widgets-for-elementor' ); ?></a>
				</p>
			</div>
			<?php
		} elseif ( 'fail' === $status ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'Sorry, that demo could not be imported. Please try again.', 'free-widgets-for-elementor' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Handle a one-click import (admin-post). Wired in Hooks\Admin.
	 *
	 * @return void
	 */
	public static function handle_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'free-widgets-for-elementor' ) );
		}

		$demo_id = isset( $_POST['demo_id'] ) ? sanitize_key( wp_unslash( $_POST['demo_id'] ) ) : '';
		check_admin_referer( self::IMPORT_ACTION . '_' . $demo_id );

		$new_id = $demo_id ? self::import_demo( $demo_id ) : 0;

		wp_safe_redirect(
			add_query_arg(
				$new_id
					? array(
						'page'        => self::SLUG,
						'fwfe_import' => 'ok',
						'fwfe_new'    => $new_id,
					)
					: array(
						'page'        => self::SLUG,
						'fwfe_import' => 'fail',
					),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	/**
	 * Resolve a demo from the manifest, fetch its template file and create the
	 * Elementor saved template (sideloading its images).
	 *
	 * @param string $demo_id Demo id.
	 * @return int New template post ID, or 0 on failure.
	 */
	public static function import_demo( $demo_id ) {
		$manifest = self::get_manifest();
		if ( is_wp_error( $manifest ) ) {
			return 0;
		}

		// Resolve the entry from the manifest — never trust a posted file URL.
		$entry = null;
		foreach ( $manifest['demos'] as $demo ) {
			if ( $demo['id'] === $demo_id ) {
				$entry = $demo;
				break;
			}
		}
		if ( ! $entry ) {
			return 0;
		}

		// Re-check the version requirement server-side.
		if ( '' !== $entry['requires'] && ! version_compare( FWFE_VERSION, $entry['requires'], '>=' ) ) {
			return 0;
		}

		$response = wp_safe_remote_get( $entry['file'], array( 'timeout' => 15 ) );
		if ( is_wp_error( $response ) || 200 !== (int) wp_remote_retrieve_response_code( $response ) ) {
			return 0;
		}
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! is_array( $data ) || empty( $data['content'] ) || ! is_array( $data['content'] ) ) {
			return 0;
		}

		// Enable any widgets the demo relies on, so they render instead of
		// showing Elementor's "widget not found" placeholder.
		if ( ! empty( $entry['widgets'] ) ) {
			Helper::enable_widgets( $entry['widgets'] );
		}

		$title = ! empty( $data['title'] ) ? sanitize_text_field( $data['title'] ) : $entry['name'];
		$type  = ! empty( $data['type'] ) ? sanitize_key( $data['type'] ) : 'section';

		return self::create_template( $data['content'], $title, $type );
	}

	/**
	 * Create an Elementor saved template from a content tree, sideloading every
	 * image it references and rewriting the media id/url in place.
	 *
	 * @param array  $content Elementor elements tree (_elementor_data).
	 * @param string $title   Template title.
	 * @param string $type    Elementor library type ('section' or 'page').
	 * @return int New template post ID, or 0 on failure.
	 */
	private static function create_template( $content, $title, $type ) {
		$post_id = wp_insert_post(
			array(
				'post_type'   => 'elementor_library',
				'post_status' => 'publish',
				'post_title'  => $title,
			),
			true
		);
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return 0;
		}

		// Sideload images and rewrite each media object's id + url in the tree.
		$map = array();
		self::rewrite_images( $content, $map, $post_id );

		update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $content ) ) );
		update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
		update_post_meta( $post_id, '_elementor_template_type', $type );
		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			update_post_meta( $post_id, '_elementor_version', ELEMENTOR_VERSION );
		}

		// Categorise it under the right Saved Templates tab (Sections / Pages).
		wp_set_object_terms( $post_id, $type, 'elementor_library_type' );

		// Drop any stale CSS cache so Elementor regenerates it on first view.
		delete_post_meta( $post_id, '_elementor_css' );

		return (int) $post_id;
	}

	/**
	 * Walk an Elementor node tree, sideload every image and rewrite its id/url.
	 *
	 * An Elementor media object is an array carrying both `id` and `url`; link
	 * controls carry `url` but no `id`, so they are left untouched.
	 *
	 * @param array $node    Node (passed by reference, mutated in place).
	 * @param array $map     URL => array( id, url ) cache, so each source image
	 *                       is downloaded only once.
	 * @param int   $post_id Template post the attachments are attached to.
	 * @return void
	 */
	private static function rewrite_images( &$node, &$map, $post_id ) {
		if ( ! is_array( $node ) ) {
			return;
		}

		// Is this node itself a media object { id, url }?
		if ( array_key_exists( 'id', $node ) && array_key_exists( 'url', $node ) && is_string( $node['url'] ) && '' !== $node['url'] ) {
			$src = $node['url'];
			if ( ! isset( $map[ $src ] ) ) {
				$att_id      = self::sideload_image( $src, $post_id );
				$map[ $src ] = $att_id
					? array(
						'id'  => $att_id,
						'url' => wp_get_attachment_url( $att_id ),
					)
					: null;
			}
			if ( ! empty( $map[ $src ] ) ) {
				$node['id']  = $map[ $src ]['id'];
				$node['url'] = $map[ $src ]['url'];
			}
			return;
		}

		foreach ( $node as &$child ) {
			if ( is_array( $child ) ) {
				self::rewrite_images( $child, $map, $post_id );
			}
		}
		unset( $child );
	}

	/**
	 * Download a remote image into the Media Library.
	 *
	 * @param string $url     Image URL (http/https).
	 * @param int    $post_id Post to attach it to.
	 * @return int Attachment ID, or 0 on failure.
	 */
	private static function sideload_image( $url, $post_id ) {
		$url = esc_url_raw( $url );
		if ( ! $url || ! in_array( wp_parse_url( $url, PHP_URL_SCHEME ), array( 'http', 'https' ), true ) ) {
			return 0;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$tmp = download_url( $url, 15 );
		if ( is_wp_error( $tmp ) ) {
			return 0;
		}

		$file = array(
			'name'     => basename( wp_parse_url( $url, PHP_URL_PATH ) ),
			'tmp_name' => $tmp,
		);

		// media_handle_sideload validates the file type itself; non-images are rejected.
		$id = media_handle_sideload( $file, $post_id );
		if ( is_wp_error( $id ) ) {
			if ( file_exists( $tmp ) ) {
				wp_delete_file( $tmp );
			}
			return 0;
		}

		return (int) $id;
	}
}
