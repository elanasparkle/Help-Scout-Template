<?php

$per_page = apply_filters( 'hsd_pagination_per_page', 10 );
$offset = ( $current_page - 1 ) * $per_page;

$total_conversations = ( isset( $conversations['page']->totalElements ) ) ? (int) $conversations['page']->totalElements : (int) count( $conversations );
$total_pages = $total_conversations / $per_page;


$current_conversations = $conversations;
unset( $current_conversations['page'] ); ?>

<table id="hsd_support_table">
	<thead>
		<tr>
			<th><?php _e( 'Status', 'help-scout-desk' ) ?></th>
			<th><?php _e( 'Conversation', 'help-scout-desk' ) ?></th>
			<th><?php _e( 'Waiting Since', 'help-scout-desk' ) ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if ( ! empty( $current_conversations ) ) : ?>

			<?php foreach ( $current_conversations as $key => $data ) :

				if ( ! isset( $data['conversation']['id'] ) ) {
					continue;
				}

				$last_thread_preview = '';
				$conversation_created = $data['conversation']['createdAt'];
				$last_thread_updated = $data['conversation']['createdAt'];

				if ( ! empty( $data['threads'] ) ) {
					$found = false;
					foreach ( $data['threads'] as $thk => $thdata ) {
						if ( $thdata['type'] === 'lineitem' ) {
							continue;
						}
						if ( strtotime( $thdata['createdAt'] ) > strtotime( $last_thread_updated ) ) {
							$last_thread_updated = $thdata['createdAt'];
							$last_thread_preview = ( isset( $thdata['body'] ) ) ? $thdata['body'] : '' ;
						}
					}
				}

				$single_url = esc_url( add_query_arg( array( 'conversation_id' => esc_attr( $data['conversation']['id'] ) ), get_permalink( $post_id ) ) );
				?>
				<tr class="status-<?php echo sanitize_html_class( hsd_get_status_class( $data['conversation']['status'] ) ) ?>">

					<td>
						<a href="<?php echo esc_url( add_query_arg( array( 'status' => esc_attr( $data['conversation']['status'] ) ), get_permalink( $post_id ) ) ) ?>"">
							<span class="label label-<?php echo sanitize_html_class( hsd_get_status_class( $data['conversation']['status'] ) ) ?>"><?php echo wp_strip_all_tags( hsd_get_status_label( $data['conversation']['status'] ) ) ?></span>
						</a>
					</td>
					<td>
						<a href="<?php echo $single_url ?>">

							<div class="title_wrap">
								<title><?php echo wp_strip_all_tags( $data['conversation']['subject'] ); ?></title>

								<span class="time_wrap">
									<time datetime="<?php echo esc_attr( $data['conversation']['createdAt'] ) ?>">
										<?php printf( __( 'Created: %s', 'help-scout-desk' ), date_i18n( get_option( 'date_format' ), strtotime( esc_attr( $conversation_created ) ) ) ) ?>
									</time>
								</span>
								<span class="thread_count"><span class="badge"><?php echo absint( $data['conversation']['threads'] ) ?></span></span>
							</div>

						</a>

						<a href="<?php echo $single_url ?>">

							<span class="conversation_preview"><?php echo sa_get_truncate( $last_thread_preview, 30 ) ?></span>

						</a>
						<span class="tags_wrap">
							<?php
							$tags = HSD_Tags::get_converstation_tags( $data['conversation'] );
							?>
							<?php if ( ! empty( $tags ) ) :  ?>
								<?php foreach ( $tags as $tag ) :  ?>
									&nbsp;<span class="badge"><?php echo wp_strip_all_tags( $tag ) ?></span>
								<?php endforeach ?>
							<?php endif ?>
						</span>
					</td>

					<td>
						<time><?php echo hsd_get_waiting_since( strtotime( esc_attr( $last_thread_updated ) ) ) ?></time>
					</td>
				</tr>
			<?php endforeach ?>

		<?php else : ?>
			<tr><td colspan="5" rowspan="3"><?php _e( 'No support requests found.', 'help-scout-desk' ) ?></td></tr>
		<?php endif ?>

	</tbody>
</table>