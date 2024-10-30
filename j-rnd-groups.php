<?php
/*
Plugin Name: Jet Random Groups Widget
URI: http://milordk.ru
Author: Jettochkin
Author URI: http://milordk.ru
Plugin URI: http://milordk.ru/r-lichnoe/opyt-l/cms/prodolzhaem-widget-o-stroenie-jet-random-groups-widget.html
Donate URI: http://milordk.ru/uslugi.html
Description: ru-Вывод случайных групп в виде аватар. en-Provides random avatart groups.  For BuddyPress 1.2.5.x use: <a href="http://wordpress.org/extend/plugins/jet-unit-site-could/">Jet Site Unit Could</a>
Tags: BuddyPress, Wordpress MU, meta, group, widget
Version: 1.3
*/
?>
<?php

class JetRandomGMetaList extends WP_Widget {

function jet_has_site_groups( $args = '' ) {
	global $site_groups_template;

	$defaults = array(
		'type' => 'random',
		'per_page' => $instance['number'],
		'max' => false
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );
	
	if ( $max ) {
		if ( $per_page > $max )
			$per_page = $max;
	}
		
if (BP_VERSION == '1.1.3') {
		$site_groups_template = new BP_Groups_Site_Groups_Template( $type, $per_page, $max );
	return apply_filters( 'bp_has_site_groups', $site_groups_template->has_groups(), &$site_groups_template );
	}
	else
	{
	$groups_template = new BP_Groups_Groups_Template( $type, $per_page, $max );
	return apply_filters( 'bp_has_groups', $groups_template->has_groups(), &$groups_template );	
	}
}

	function JetRandomGMetaList() {
		parent::WP_Widget(false, $name = __('Jet Random Groups Meta List','jetrandomgmetalist') );
	}

	function widget($args, $instance) {
		extract( $args );
		echo $before_widget;
		$keytitle = $instance['jgtitle']; ?>
<?php if ( $keytitle ) { ?>
		<a href="<?php echo get_option('home') ?>/<?php echo BP_GROUPS_SLUG ?>" title="<?php _e( 'Groups', 'buddypress' ) ?>">
<?php } ?>
		<?php echo $before_title.$instance['title'].$after_title; ?>
<?php if ($keytitle ) { ?>
		</a>
<?php } ?>
	<?php $argj = 'type=random&max='.$instance["number"].'&per_page=20'; ?>
<?php /* Check Version */ ?>
	<?php if (BP_VERSION == '1.1.3') { ?>		   
			<?php if ( bp_has_site_groups( $argj ) ) : ?>
				<div class="avatar-block">
					<?php while ( bp_site_groups() ) : bp_the_site_group(); ?>
							<span class="item-avatar">
								<a href="<?php bp_the_site_group_link() ?>" title="<?php bp_the_site_group_name() ?> | <?php bp_the_site_group_last_active() ?> | <?php bp_the_site_group_member_count(); ?>"><?php bp_the_site_group_avatar_thumb() ?></a>
							</span>
					<?php endwhile; ?>	
				</div>
				<?php do_action( 'bp_directory_groups_featured' ) ?>				
			<?php else: ?>
				<div id="message" class="info">
					<p><?php _e( 'No groups found.', 'buddypress' ) ?></p>
				</div>
			<?php endif; ?>
<?php } ?>

<?php if (BP_VERSION == '1.2.3') { ?> 
			<?php if ( bp_has_groups( $argj ) ) : ?>
				<div class="avatar-block">					
					<?php while ( bp_groups() ) : bp_the_group(); ?>
							<span class="item-avatar">
								<a href="<?php bp_group_permalink() ?>" title="<?php bp_group_name() ?> | <?php bp_group_last_active() ?> | <?php bp_group_member_count(); ?>"><?php bp_group_avatar_thumb() ?></a>
							</span>
					<?php endwhile; ?>	
				</div>
				<?php do_action( 'bp_directory_groups_featured' ) ?>	
				
			<?php else: ?>

				<div id="message" class="info">
					<p><?php _e( 'No groups found.', 'buddypress' ) ?></p>
				</div>
			<?php endif; ?>
<?php } ?>

<?php if ((BP_VERSION == '1.2.4.1') || (BP_VERSION == '1.2.5')) { ?> 
			<?php if ( bp_has_groups( $argj ) ) : ?>
				<div class="avatar-block">					
					<?php while ( bp_groups() ) : bp_the_group(); ?>
							<span class="item-avatar">
								<a href="<?php bp_group_permalink() ?>" title="<?php bp_group_name() ?> | <?php bp_group_last_active() ?> | <?php bp_group_member_count(); ?>"><?php bp_group_avatar('type=thumb&width=50&height=50') ?></a>
							</span>
					<?php endwhile; ?>	
				</div>
				<?php do_action( 'bp_directory_groups_featured' ) ?>	
				
			<?php else: ?>

				<div id="message" class="info">
					<p><?php _e( 'No groups found.', 'buddypress' ) ?></p>
				</div>
			<?php endif; ?>
<?php } ?><?	echo $after_widget;
 }

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['jgtitle'] = strip_tags($new_instance['jgtitle']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'number'=>''));
		$title = strip_tags( $instance['title']); 
		$number = strip_tags( $instance['number']);
		$jgtitle = strip_tags( $instance['jgtitle']); ?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'buddypress'); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo attribute_escape( stripslashes( $title ) ); ?>" /></label></p>
		<p><?php 
		if (WPLANG == 'ru_RU' or WPLANG == 'ru_RU_lite' ) { echo 'Количество групп для отображения:'; } else { echo 'Groups count for show:'; }
		?></p>
		<p><input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo attribute_escape( stripslashes( $number ) ); ?>" /></label></p>
<p><?php if ( WPLANG == 'ru_RU' or WPLANG == 'ru_RU_litle') { 
            echo 'Использовать ссылку на все группы:';
        }else{
                echo 'To use the link for the all groups:';
        } ?>&nbsp;
		<input class="checkbox" type="checkbox" <?php if ($jgtitle) {echo 'checked="checked"';} ?> id="<?php echo $this->get_field_id('jgtitle'); ?>" name="<?php echo $this->get_field_name('jgtitle'); ?>" value="1" /></p>
<?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget("JetRandomGMetaList");'));

?>