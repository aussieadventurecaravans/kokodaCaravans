<?php
$custom_order = get_query_var('custom_order');
$caravan_id = get_query_var('caravan_id');



global $post;
$post = get_post($caravan_id,OBJECT);
setup_postdata($post);

?>

<?php



$html = '<h2 style="margin-bottom:10px;font-family: Rubik, sans-serif;">Specifications</h2> ';

?>
<?php if(have_rows('specifications')): ?>
    <?php  $specs = get_field('specifications');?>
    <?php $html .= '<div class="specifications-list-wrapper" style="width: 100%;margin-top:10px;">'; ?>
    <?php $html .='<div id="specifications-list" style="width: 100%;">'; ?>
    <?php  $specs = get_field('specifications');?>
    <?php  if($specs): ?>
        <?php $html .= '<div class="row spec-list" style="margin-right: 0;margin-left: 0;width: 100%;display: inline-block;">'; ?>
        <?php foreach ($specs as $spec): ?>
            <?php $html .= '<div class="col-sm-6" style="width:49%;float: left;height:100%;border:1px solid #000">'; ?>
            <?php $html .= '<h4 style="margin: 0;padding: 8px 10px 8px;font-size: 17px;background-color: #000;color: #fff;text-transform: uppercase;font-family:Rubik, sans-serif;">' .  $spec['group_heading'] . '</h4>'; ?>
            <?php $spec_its = $spec['specification_item'];?>
            <?php $html .= '<ul style="padding: 0;margin: 0;list-style: none;">'; ?>
            <?php  foreach ($spec_its as $spec_it) : ?>
                <?php $html .='<li style="font-size: 16px;padding: 2px 8px;">';  ?>
                <?php $html .= $spec_it['heading']; ?>
                <?php $html .= '</li>'; ?>
            <?php endforeach; ?>
            <?php $html .= '</ul>'; ?>
            <?php $html .= '</div>'; ?>
        <?php endforeach; ?>
        <?php $html .= '</div>'; ?>
    <?php endif; ?>
    <?php $html .= '</div>'; ?>
    <?php $html .= '</div>'; ?>
<?php endif; ?>


<?php


require_once KOKODA_CUSTOM_ORDER_PLUGIN_URL . 'assets/mpdf/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'c']);

$mpdf->WriteHTML($html);

//echo $html;
echo base64_encode($mpdf->Output('quote_summary.pdf', 'S'));




?>
