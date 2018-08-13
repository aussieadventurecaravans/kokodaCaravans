<?php
/**
 * Created by PhpStorm.
 * User: sonnguyen
 * Date: 7/8/18
 * Time: 11:19 AM
 */

?>

<div class="wrap">
    <h2>Quote List</h2>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-12">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">
                        <?php
                        $this->quote_obj->prepare_items();
                        $this->quote_obj->display(); ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
