<?php 

/**
 * Template Name: Dealer Locator
 */

get_header(); ?>


<div class="stripe map" id="sl_div">
	<div class="container-fluid">
		<?php echo do_shortcode('[SLPLUS]'); ?>
	</div>
</div>

<div class="modal fade" id="enquiryModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <?php echo FrmFormsController::show_form(8, $key = '', $title=false, $description=false); ?>
      </div>
    </div>
  </div>
</div>

<?php get_footer(); ?>