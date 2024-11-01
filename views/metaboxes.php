<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="slider-shortcode">Copy this shortcode and paste it into your post, page, or text widget content:</label><br />
            </th>
            <td>
                <input type="text" size="65" class="code" value="<?php echo esc_attr($shortcode); ?>" readonly>
                <button type="button" class="shortcode-copy-btn button button-primary button-large" onclick="copyToClipboard('<?php echo esc_js($shortcode); ?>')">Copy</button>
                <script>
                    function copyToClipboard(text) {
                        var input = document.createElement('textarea');
                        input.innerHTML = text;
                        document.body.appendChild(input);
                        input.select();
                        var result = document.execCommand('copy');
                        document.body.removeChild(input);
                        if (result) {
                            var buttonEl = document.querySelector('.shortcode-copy-btn');
                            buttonEl.innerHTML = 'Copied';
                        }
                    }
                </script>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="sm_as_gallery">Use as Masonry Layout Gallery</label>
            </th>
            <td>
               <input type="checkbox" name="sm_as_gallery" class="sm-ui-toggle" value="1" <?php checked(1, $sm_as_gallery, true); ?> />
            </td>
        </tr>


        <tr>
            <th scope="row">
                <label for="sm_post_type">Choose Post Type</label>
            </th>
            <td>
                <select name="sm_post_type" class="js-post-type">
                    <?php foreach ($post_types as $post_type) {
                    ?>
                        <option value="<?php echo $post_type->name; ?>" <?php if ($post_type->name == $sm_post_type) echo 'selected="selected"'; ?>><?php echo $post_type->labels->name; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>


        <tr>
            <th scope="row">
                <label for="sm_post_type">Choose Category</label>
            </th>
            <td>
                <select name="sm_category">
                    <option value="none">None</option>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?php echo $cat->slug; ?>" <?php if ($cat->slug == $sm_category) echo 'selected="selected"'; ?>> <?php echo $cat->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th scope="row">
                <label for="simple_post_per_page">No.of Posts To Display</label>
            </th>
            <td>
                <input type="number" name="simple_post_per_page" id="simple_post_per_page" value="<?php echo $simple_post_per_page ? esc_attr($simple_post_per_page) : '10'; ?>" min="1" step="1" />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="simple_post_orderby">Posts Order By</label>
            </th>
            <td>
                <select name="simple_post_orderby">
                    <?php foreach ($simple_order_by as $simple_order_by_key => $simple_order_by_value) { ?>
                        <option value="<?php echo $simple_order_by_key; ?>" <?php if ($simple_post_orderby == $simple_order_by_key) echo 'selected="selected"'; ?>><?php echo $simple_order_by_value; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="simple_post_order">Posts Order</label>
            </th>
            <td>
                <select name="simple_post_order">
                    <?php foreach ($simple_order as $simple_order_key => $simple_order_value) { ?>
                        <option value="<?php echo $simple_order_key; ?>" <?php if ($simple_post_order == $simple_order_key) echo 'selected="selected"'; ?>><?php echo $simple_order_value; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="simple_post_darkbox">Display Darkbox Gallery Popup</label>
            </th>
            <td>
                <input type="checkbox" name="simple_post_darkbox" class="sm-ui-toggle" value="1" <?php checked(1, $simple_post_darkbox, true); ?> />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="simple_post_author">Display Post Author</label>
            </th>
            <td>
                <input type="checkbox" name="simple_post_author" class="sm-ui-toggle" value="1" <?php checked(1, $simple_post_author, true); ?> />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="sm_post_comment">Display Post Comments</label>
            </th>
            <td>
                <input type="checkbox" name="sm_post_comment" class="sm-ui-toggle" value="1" <?php checked(1, $sm_post_comment, true); ?> />
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="sm_post_title">Display Post Title URL in gallery</label>
            </th>
            <td>
                <input type="checkbox" name="sm_post_title" class="sm-ui-toggle" value="1" <?php checked(1, $sm_post_title, true); ?> />
                <span class="description">This setting is only used for <b>Use as Masonry Layout Gallery</b>.</span>
            </td>
        </tr>
    </tbody>
</table>
