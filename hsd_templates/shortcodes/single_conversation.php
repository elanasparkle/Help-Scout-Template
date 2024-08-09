<div id="hsd_support_conversation" data-item-status="<?php echo esc_attr( $item['status'] ) ?>">
	<header id="conversation_header" class="entry-header clearfix">
		
		<?php do_action( 'hsd_single_conversation_pre_title', $item ) ?>

		<h1 class="entry-title title"><?php echo esc_attr( $item['subject'] ) ?></h1>

		<?php do_action( 'hsd_single_conversation_post_title', $item ) ?>

		<div class="author">
			
			<?php do_action( 'hsd_single_conversation_pre_posted_on', $item ) ?>

			<span class="posted-on">

				<span class="label label-<?php hsd_status_class( $item['status'] ) ?>"><?php hsd_status_label( $item['status'] ) ?></span>

				<?php do_action( 'hsd_single_conversation_status', $item ) ?>

				<?php
					$name = esc_attr( $customer['firstName'] ) . ' ' . esc_attr( $customer['lastName'] );
					$time = '<time datetime="'.esc_attr( $item['createdAt'] ).'">'.date_i18n( get_option( 'date_format' ), strtotime( esc_attr( $item['createdAt'] ) ) ).'</time>';

					printf( __( 'Posted by %s on %s', 'help-scout-desk' ), $name, $time ); ?>
			</span>
		
			<?php do_action( 'hsd_single_conversation_posted_on', $item ) ?>
		
		</div>
		
		<?php do_action( 'hsd_single_conversation_post_header', $item ) ?>

	</header><!-- /conversation_header -->

	<a href="<?php echo get_permalink( $post_id ) ?>" class="button hsd_goback"><?php esc_attr_e( 'Go back', 'help-scout-desk' ) ?></a>

	<?php do_action( 'hsd_single_conversation_go_back', $item ) ?>

	<section id="hsd_conversation_thread"  class="clearfix">
		
		<?php do_action( 'hsd_single_conversation_thread_start', $item ) ?>

		<?php
		$threadcount = (int) count( $threads );
		foreach ( $threads as $key => $data ) :  ?>
			<?php if ( $data['type'] !== 'lineitem' ) : ?>
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<span class="avatar pull-left"><?php echo get_avatar( $data['createdBy']['email'], 36 ) ?></span>
						<h3 class="panel-title pull-right">
							<?php
								$name = esc_attr( $data['createdBy']['first'] ) . ' ' . esc_attr( $data['createdBy']['last'] );
								$time = '<time datetime="'.esc_attr( $data['createdAt'] ).'">'.date_i18n( get_option( 'date_format' ), strtotime( esc_attr( $data['createdAt'] ) ) ).'</time>';

								printf( __( '%s on %s', 'help-scout-desk' ), $name, $time ); ?>
						</h3>
					</div>
					<div class="panel-body">
						<div class="conversation_body clearfix">
							<div class="message">
								<?php echo wpautop( self::linkify( $data['body'] ) ); ?>
							</div>
							<!-- Image Attachments will be imgs -->
							<?php if ( isset( $data['_embedded']['attachments'] ) && ! empty( $data['_embedded']['attachments'] ) ) : ?>
								<div class="img_attachments_wrap clearfix">
									<ul class="attachments img_attachments clearfix">
									<?php foreach ( $data['_embedded']['attachments'] as $key => $att_data ) : ?>
										<?php
											/**
											 * v2 API Doesn't include a URL for attachments (yet)
											 */
										if ( ! isset( $att_data['_links']['web']['href'] ) ) {
											continue;
										} ?>
										<?php if ( in_array( $att_data['mimeType'], array( 'image/jpeg', 'image/jpg', 'image/png', 'image/gif' ) ) ) : ?>
											<li class="image_att">
												<a target="_blank" href="<?php echo esc_url( $att_data['_links']['web']['href'] ) ?>" class="file fancyimg" title="View Attachment"><img src="<?php echo esc_url( $att_data['_links']['web']['href'] ) ?>" alt="<?php echo esc_attr( $att_data['filename'] ) ?>"></a>
											</li>
										<?php endif ?>
									<?php endforeach ?>
									</ul>
								</div>
								<!-- All Attachments will be linked -->
								<div class="attachments_wrap clearfix">
									<h5><?php _e( 'Attachments', 'help-scout-desk' ) ?></h5>
									<?php if ( isset( $data['_embedded']['attachments'] ) && ! empty( $data['_embedded']['attachments'] ) ) : ?>	
										<ul class="attachments file_attachments">
										<?php foreach ( $data['_embedded']['attachments'] as $key => $att_data ) : ?>
											<li class="file_att">
												<?php
													/**
													 * v2 API Doesn't include a URL for attachments (yet)
													 */
												if ( ! isset( $att_data['_links']['web']['href'] ) ) :  ?>
													<a href="javascript:alert('<?php _e( 'Attachments are temporarily disabled.', 'sprout-invoices' ) ?>')"><?php echo esc_attr( $att_data['filename'] ) ?></a>
												<?php else : ?>
													<a target="_blank" href="<?php echo esc_url( $att_data['_links']['web']['href'] ) ?>" class="file fancyimg" title="View Attachment"><?php echo esc_attr( $att_data['filename'] ) ?></a>
												<?php endif ?>
											</li>
										<?php endforeach ?>
										</ul>
									<?php endif ?>
								</div>
							<?php endif ?>
						</div>
					</div>
					<div class="panel-footer">
						<?php
							printf( __( '<b>%s</b> of %s', 'help-scout-desk' ), $threadcount, esc_attr( count( $threads ) ) ); ?>
					</div>
				</div>
			<?php else : ?>
				<div class="panel panel-default line_item">
					<div class="panel-heading clearfix">
						<?php
						$name = esc_attr( $data['createdBy']['first'] ) . ' ' . esc_attr( $data['createdBy']['last'] );
						$time = '<time datetime="'.esc_attr( $data['createdAt'] ).'">'.date_i18n( get_option( 'date_format' ), strtotime( esc_attr( $data['createdAt'] ) ) ).'</time>';
						$status = sprintf( '<span class="label label-%s">%s</span>', esc_attr( hsd_get_status_class( $data['status'] ) ), wp_strip_all_tags( hsd_get_status_label( $data['status'] ) ) );
						printf( __( '%s by %s on %s', 'help-scout-desk' ), $status, $name, $time ); ?>
					</div>
				</div>
			<?php endif ?>
			<?php $threadcount--; // update thread count ?>
		<?php
		endforeach; ?>
		<?php do_action( 'hsd_single_conversation_thread_end', $item ) ?>
	</section><!-- #hsd_conversation_thread -->

</div><!-- #hsd_support_conversation -->