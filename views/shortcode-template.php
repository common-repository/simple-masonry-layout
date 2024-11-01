<?php if ($wp_query->have_posts()) : ?>
    <div class="smblog_masonry_numcol">
        <div class="sm-grid sm-effect" id="sm-grid-layout">

            <?php
            while ($wp_query->have_posts()) : $wp_query->the_post();
                $thumbnail = wp_get_attachment_url(get_post_thumbnail_id());
                $sm_date   = get_the_date(get_option('date_format'));
            ?>

                <?php if ($gallery == 'no' || $thumbnail) : ?>
                    <div class="grid-sm-boxes-in post-<?php the_ID(); ?>">
                        <div class="grid-sm-border">

                            <?php
                            if ($thumbnail) {
                                if ($sm_darkbox_enable == 1) {
                                    echo '<img class="img-responsive" src="' . esc_url($thumbnail) . '" data-darkbox="' . esc_url($thumbnail) . '"   data-darkbox-group="foo" data-darkbox-description="' . get_the_title() . '">';
                                } else {
                                    echo '<a href="' . esc_url(get_the_permalink()) . '"><img class="img-responsive" src="' . esc_url($thumbnail) . '"></a>';
                                }
                            }
                            ?>

                            <?php if ($gallery == 'yes') : ?>
                                <?php if ($sm_post_title_enable == 1) : ?>
                                    <div class="sm-gallery-title">
                                        <a href="<?php the_permalink(); ?>">
                                            <span class="sm-gallery-textPart"><?php the_title(); ?></span>
                                            <span class="sm-gallery-arrow"><?php echo '<img src="' . plugins_url('../assets/images/arrow.png', __FILE__) . '" > '; ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif ?>

                            <?php if ($gallery == 'no') : ?>
                                <div class="sm-grid-boxes-caption">
                                    <div class="sm-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                                    <div class="sm-list-inline sm-grid-boxes-news">
                                        <div class="sm-meta">
                                            <span class="sm-meta-part">
                                                <?php if ($sm_post_author_enable == 1) : ?>
                                                    <span class="sm-meta-poster">
                                                        <i class="sm-icon-author"></i><a href="<?php echo get_author_posts_url(get_the_author_meta(), get_the_author_meta('user_nicename')); ?>"><?php esc_attr(the_author_meta('display_name')); ?></a></span>
                                                <?php endif; ?>
                                                <span class="sm-meta-date"> <i class="sm-icon-date">
                                                    </i><a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j'))); ?>"><?php echo esc_attr($sm_date); ?></a> </span>
                                                <?php if ($sm_post_comment_enable == 1) : ?>
                                                    <span class="sm-meta-likes"><i class="sm-icon-comments"></i><?php echo get_comments_number(); ?></span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="sm-grid-boxes-quote">
                                        <?php
                                        if (has_excerpt()) {
                                            the_excerpt();
                                        } else {
                                            echo '</p>' . strip_shortcodes(wp_trim_words(get_the_content(), 20)) . '</p>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>

                <?php endif; ?>
            <?php endwhile; ?>

        </div>
    <?php endif; ?>
    <?php wp_reset_query(); ?>
    <?php
    if (method_exists($this, 'simpleMasonryPagination')) {
        $this->simpleMasonryPagination($wp_query->max_num_pages, "", $paged);
    }
    ?>
    </div>
