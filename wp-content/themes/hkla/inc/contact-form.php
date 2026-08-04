<?php
/**
 * Contact form.
 *
 * Preferred: a WPForms/Gravity Forms shortcode stored in Site Settings
 * (contact_form_shortcode). Until that is configured, the theme renders a
 * native accessible form with honeypot + nonce + server-side validation,
 * delivered via wp_mail to the Site Settings email.
 *
 * @package hkla
 */

/**
 * Render the form. Called from page-contact.php.
 */
function hkla_contact_form() {
	$shortcode = hkla_setting( 'contact_form_shortcode' );
	if ( $shortcode ) {
		echo do_shortcode( $shortcode );
		return;
	}

	$state  = $_GET['contact'] ?? '';
	$errors = get_transient( 'hkla_contact_errors_' . hkla_contact_visitor_key() );
	delete_transient( 'hkla_contact_errors_' . hkla_contact_visitor_key() );
	?>
	<?php if ( 'sent' === $state ) : ?>
		<p class="form-confirm" role="status">
			<?php esc_html_e( 'Thank you. Your message is with our senior team, and we respond to every inquiry.', 'hkla' ); ?>
		</p>
	<?php else : ?>
		<?php if ( $errors ) : ?>
			<div class="form-errors" role="alert" tabindex="-1" id="form-errors">
				<p><?php esc_html_e( 'Please correct the following before sending:', 'hkla' ); ?></p>
				<ul>
					<?php foreach ( (array) $errors as $error ) : ?>
						<li><?php echo esc_html( $error ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
		<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="hkla_contact">
			<?php wp_nonce_field( 'hkla_contact', 'hkla_contact_nonce' ); ?>
			<p class="visually-hidden" aria-hidden="true">
				<label for="hkla-website">Leave this field empty</label>
				<input type="text" id="hkla-website" name="hkla_website" tabindex="-1" autocomplete="off">
			</p>
			<div class="form-field">
				<label for="cf-name"><?php esc_html_e( 'Name', 'hkla' ); ?></label>
				<input type="text" id="cf-name" name="cf_name" required autocomplete="name">
			</div>
			<div class="form-field">
				<label for="cf-email"><?php esc_html_e( 'Email', 'hkla' ); ?></label>
				<input type="email" id="cf-email" name="cf_email" required autocomplete="email">
			</div>
			<div class="form-field">
				<label for="cf-org"><?php esc_html_e( 'Organization', 'hkla' ); ?></label>
				<input type="text" id="cf-org" name="cf_org" autocomplete="organization">
			</div>
			<div class="form-field">
				<label for="cf-type"><?php esc_html_e( 'Inquiry type', 'hkla' ); ?></label>
				<select id="cf-type" name="cf_type">
					<option value="new-project"><?php esc_html_e( 'New project', 'hkla' ); ?></option>
					<option value="rfp"><?php esc_html_e( 'RFP', 'hkla' ); ?></option>
					<option value="collaboration"><?php esc_html_e( 'Collaboration', 'hkla' ); ?></option>
					<option value="press"><?php esc_html_e( 'Press', 'hkla' ); ?></option>
					<option value="careers"><?php esc_html_e( 'Careers', 'hkla' ); ?></option>
				</select>
			</div>
			<div class="form-field">
				<label for="cf-message"><?php esc_html_e( 'Message', 'hkla' ); ?></label>
				<textarea id="cf-message" name="cf_message" rows="6" required></textarea>
			</div>
			<button type="submit" class="button"><?php esc_html_e( 'Send message', 'hkla' ); ?></button>
		</form>
	<?php endif;
}

/**
 * Per-visitor key for stashing validation errors across the redirect.
 */
function hkla_contact_visitor_key() {
	return md5( ( $_SERVER['REMOTE_ADDR'] ?? '' ) . '|' . ( $_SERVER['HTTP_USER_AGENT'] ?? '' ) );
}

/**
 * Handle submission: nonce, honeypot, validation, mail, redirect.
 */
function hkla_contact_handle() {
	$back = wp_get_referer() ?: home_url( '/contact/' );

	if ( ! isset( $_POST['hkla_contact_nonce'] ) || ! wp_verify_nonce( $_POST['hkla_contact_nonce'], 'hkla_contact' ) ) {
		wp_safe_redirect( $back );
		exit;
	}

	// Honeypot: silently accept and drop.
	if ( ! empty( $_POST['hkla_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'sent', $back ) );
		exit;
	}

	$name    = sanitize_text_field( wp_unslash( $_POST['cf_name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['cf_email'] ?? '' ) );
	$org     = sanitize_text_field( wp_unslash( $_POST['cf_org'] ?? '' ) );
	$type    = sanitize_key( $_POST['cf_type'] ?? '' );
	$message = sanitize_textarea_field( wp_unslash( $_POST['cf_message'] ?? '' ) );

	$allowed_types = array( 'new-project', 'rfp', 'collaboration', 'press', 'careers' );

	$errors = array();
	if ( '' === $name ) {
		$errors[] = __( 'Name is required.', 'hkla' );
	}
	if ( ! is_email( $email ) ) {
		$errors[] = __( 'A valid email address is required.', 'hkla' );
	}
	if ( '' === $message ) {
		$errors[] = __( 'A message is required.', 'hkla' );
	}
	if ( ! in_array( $type, $allowed_types, true ) ) {
		$type = 'new-project';
	}

	if ( $errors ) {
		set_transient( 'hkla_contact_errors_' . hkla_contact_visitor_key(), $errors, 5 * MINUTE_IN_SECONDS );
		wp_safe_redirect( add_query_arg( 'contact', 'error', $back ) . '#form-errors' );
		exit;
	}

	$to = ( 'rfp' === $type && hkla_setting( 'rfp_email' ) ) ? hkla_setting( 'rfp_email' ) : hkla_setting( 'email', get_option( 'admin_email' ) );

	$body  = "Name: {$name}\n";
	$body .= "Email: {$email}\n";
	$body .= 'Organization: ' . ( $org ?: 'Not given' ) . "\n";
	$body .= "Inquiry type: {$type}\n\n";
	$body .= $message . "\n";

	wp_mail(
		$to,
		sprintf( '[hklainc.com] %s inquiry from %s', ucfirst( str_replace( '-', ' ', $type ) ), $name ),
		$body,
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);

	wp_safe_redirect( add_query_arg( 'contact', 'sent', home_url( '/contact/' ) ) );
	exit;
}
add_action( 'admin_post_hkla_contact', 'hkla_contact_handle' );
add_action( 'admin_post_nopriv_hkla_contact', 'hkla_contact_handle' );
