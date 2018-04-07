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

<script type="text/javascript">
    /** check if the addressinput has the address filled
     *  when dealer page is load at first time
     *  if yes then do the search with that address
     */
    jQuery(document).ready(function($) {
        var load_map_from_url = true;
        $('#map_sidebar').on('contentchanged',function()
        {
            if(load_map_from_url == true)
            {
                var postcode =  $('.search-bar-item .search_item input#addressInput').val();
                if(postcode !=='')
                {
                    load_map_from_url = false;
                    cslmap.searchLocations();

                }
            }

        });

    });


</script>
