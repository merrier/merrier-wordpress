<?php

class widget_ui_posts extends WP_Widget {
	/*function widget_ui_posts() {
		$widget_ops = array( 'classname' => 'widget_ui_posts', 'description' => '图文展示（最新文章+热门文章+随机文章）' );
		$this->WP_Widget( 'widget_ui_posts', 'D-聚合文章', $widget_ops );
	}*/

	function __construct(){
		parent::__construct( 'widget_ui_posts', 'DUX 聚合文章', array( 'classname' => 'widget_ui_posts' ) );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title   = apply_filters('widget_name', $instance['title']);
		$limit   = isset($instance['limit']) ? $instance['limit'] : 6;
		$cat     = isset($instance['cat']) ? $instance['cat'] : '';
		$orderby = isset($instance['orderby']) ? $instance['orderby'] : 'comment_count';
		$img     = isset($instance['img']) ? $instance['img'] : '';
		$comn     = isset($instance['comn']) ? $instance['comn'] : '';

		$style='';
		if( !$img ) $style = ' class="nopic"';
		echo $before_widget;
		echo $before_title.$title.$after_title; 
		echo '<ul'.$style.'>';
		echo dtheme_posts_list( $orderby,$limit,$cat,$img,$comn );
		echo '</ul>';
		echo $after_widget;
	}

	function form( $instance ) {
		$defaults = array( 
			'title' => '热门文章', 
			'limit' => 6, 
			'cat' => '', 
			'orderby' => 'comment_count', 
			'img' => '',
			'comn' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>
		<p>
			<label>
				标题：
				<input style="width:100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		<p>
			<label>
				排序：
				<select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>>评论数</option>
					<option value="date" <?php selected('date', $instance['orderby']); ?>>发布时间</option>
					<option value="rand" <?php selected('rand', $instance['orderby']); ?>>随机</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				分类限制：
				<a style="font-weight:bold;color:#f60;text-decoration:none;" href="javascript:;" title="格式：1,2 &nbsp;表限制ID为1,2分类的文章&#13;格式：-1,-2 &nbsp;表排除分类ID为1,2的文章&#13;也可直接写1或者-1；注意逗号须是英文的">？</a>
				<input style="width:100%;" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $instance['cat']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input style="width:100%;" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['img'], 'on' ); ?> id="<?php echo $this->get_field_id('img'); ?>" name="<?php echo $this->get_field_name('img'); ?>">显示图片
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['comn'], 'on' ); ?> id="<?php echo $this->get_field_id('comn'); ?>" name="<?php echo $this->get_field_name('comn'); ?>">显示评论数
			</label>
		</p>
		
	<?php
	}
}


function dtheme_posts_list($orderby,$limit,$cat,$img,$comn) {
	$args = array(
		'order'            => 'DESC',
		'cat'              => $cat,
		'orderby'          => $orderby,
		'showposts'        => $limit,
		'category__not_in' => array(211),
		'ignore_sticky_posts' => 1
	);
	query_posts($args);
	while (have_posts()) : the_post(); 
?>
<li><a<?php echo _post_target_blank() ?> href="<?php the_permalink(); ?>"><?php if( $img ){echo '<span class="thumbnail">'; echo _get_post_thumbnail(); echo '</span>'; }else{$img = '';} ?><span class="text"><?php the_title(); ?><?php echo get_the_subtitle() ?></span><span class="muted"><?php the_time('Y-m-d');?></span><?php if( $comn ){ ?><span class="muted"><?php echo '评论(', comments_number('0', '1', '%'), ')'; ?></span><?php } ?></a></li>
<?php
	
    endwhile; wp_reset_query();
}