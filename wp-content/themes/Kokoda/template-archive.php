<?php 

/**
 * Template Name: Caravan Archive Page
 *
 * this template show all the old caravans
 *
 * dealer go to this page to check the specs and other details
 */

get_template_part('archive-header'); ?>

<?php $banner_img = get_field('page_banner'); ?>

<div class="banner-wrap">
    <div class="banner container-fluid">
        <div class="row">
            <div class="banner-content">
                <h1>Caravans Archive</h1>
            </div>
        </div>
    </div>
</div>

<div class="stripe center archive-listing">
    <div class="container-fluid">
       <div class="row">
           <div class="col-lg-2  col-md-3 col-sm-12 filter-archive-panel">
                   <div class="filter">
                       <h2>Filter By</h2>
                       <!--- add new size select --->

                       <!-- Custom select structure for filter price -->
                       <div class="form-group" class="filter-price-form">
                           <label>Price</label>
                           <div class="select_mate" data-mate-select="active" >
                               <select class="filters-select" data-filter-group="filter-price" >
                                   <option value="*" data-filter-value="">All</option>
                                   <option value=".price-41-50" data-filter-value=".price-41-50">$41k - $50k</option>
                                   <option value=".price-51-60" data-filter-value=".price-51-60">$51k - $60k</option>
                                   <option value=".price-61-70" data-filter-value=".price-61-70">$61k - $70k</option>
                                   <option value=".price-71-80" data-filter-value=".price-71-80">$71k - $80k</option>
                                   <option value=".price-81-90" data-filter-value=".price-81-90">$81k - $90k</option>
                                   <option value=".price-91-100" data-filter-value=".price-91-100">$91k - $100k</option>
                                   <option value=".price-100" data-filter-value=".price-91">$100k+</option>
                               </select>
                               <p class="selecionado_opcion"  onclick="open_select(this)" ></p>
                               <span onclick="open_select(this)" class="icon_select_mate" >
                                   <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"/>
                                    <path d="M0-.75h24v24H0z" fill="none"/>
                                </svg>
                               </span>
                               <div class="cont_list_select_mate">
                                   <ul class="cont_select_int">  </ul>
                               </div>
                           </div>
                           <!-- Custom select structure -->
                       </div> <!-- End div center   -->

                       <!-- Custom select structure for filter size -->
                       <div class="form-group" class="filter-size-form">
                           <label>Size</label>
                           <div class="select_mate" data-mate-select="active" >
                               <select class="filters-select"  data-filter-group="filter-size">
                                   <option value="*" data-filter-value="">All</option>
                                   <option value=".size-14-19" data-filter-value=".size-14-19">14" - 19"</option>
                                   <option value=".size-20-25" data-filter-value=".size-20-25">20" - 25"</option>
                                   <option value=".size-26" data-filter-value=".size-26">26"+</option>
                               </select>
                               <p class="selecionado_opcion"  onclick="open_select(this)" ></p>
                               <span onclick="open_select(this)" class="icon_select_mate" >
                                   <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"/>
                                    <path d="M0-.75h24v24H0z" fill="none"/>
                                </svg>
                               </span>
                               <div class="cont_list_select_mate">
                                   <ul class="cont_select_int">  </ul>
                               </div>
                           </div>
                           <!-- Custom select structure for filter size-->
                       </div>
                       <!-- Custom select structure for filter type -->
                       <div class="form-group" class="filter-terrain-form">
                           <label>Terrain</label>
                           <div class="select_mate" data-mate-select="active" >
                               <select class="filters-select"  data-filter-group="filter-terrain">
                                   <option value="*" data-filter-value="">All</option>
                                   <option value=".terrain-on-road" data-filter-value=".terrain-on-road">On Road</option>
                                   <option value=".terrain-off-road" data-filter-value=".terrain-off-road">Off Road</option>
                                   <option value=".terrain-semi-off-road" data-filter-value=".terrain-semi-off-road">Semi Off Road</option>
                               </select>
                               <p class="selecionado_opcion"  onclick="open_select(this)" ></p>
                               <span onclick="open_select(this)" class="icon_select_mate" >
                                   <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"/>
                                    <path d="M0-.75h24v24H0z" fill="none"/>
                                    </svg>
                                </span>
                               <div class="cont_list_select_mate">
                                   <ul class="cont_select_int">  </ul>
                               </div>
                           </div>
                           <!-- Custom select structure for filter size-->
                       </div>

                       <!-- Custom select structure for filter type -->
                       <div class="form-group" class="filter-type-form">
                           <label>Type</label>
                           <div class="select_mate" data-mate-select="active" >
                               <select class="filters-select"  data-filter-group="filter-type">
                                   <option value="*" data-filter-value="">All</option>
                                   <option value=".type-caravan" data-filter-value=".type-caravan">caravan</option>
                                   <option value=".type-hyprid" data-filter-value=".type-hyprid">Hyprid</option>
                                   <option value=".type-camper" data-filter-value=".type-camper">Camper</option>
                               </select>
                               <p class="selecionado_opcion"  onclick="open_select(this)" ></p>
                               <span onclick="open_select(this)" class="icon_select_mate" >
                                   <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"/>
                                        <path d="M0-.75h24v24H0z" fill="none"/>
                                    </svg>
                                </span>
                               <div class="cont_list_select_mate">
                                   <ul class="cont_select_int">  </ul>
                               </div>
                           </div>
                           <!-- Custom select structure for filter size-->
                       </div>

                       <!-- Custom select structure for filter type -->
                       <div class="form-group" class="filter-type-form">
                           <label>Family</label>
                           <div class="select_mate" data-mate-select="active" >
                               <select class="filters-select"  data-filter-group="filter-family-bunk">
                                   <option value="*" data-filter-value="">All</option>
                                   <option value=".family-bunk" data-filter-value=".family-bunk">Family Bunk</option>
                               </select>
                               <p class="selecionado_opcion"  onclick="open_select(this)" ></p>
                               <span onclick="open_select(this)" class="icon_select_mate" >
                                   <svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"/>
                                    <path d="M0-.75h24v24H0z" fill="none"/>
                                    </svg>
                                </span>
                               <div class="cont_list_select_mate">
                                   <ul class="cont_select_int">  </ul>
                               </div>
                           </div>
                           <!-- Custom select structure for filter size-->
                       </div>



                       <script type="text/javascript">
                           window.onload = function(){
                               crear_select();
                           }

                           var Navegador_ = (window.navigator.userAgent||window.navigator.vendor||window.opera),
                               Firfx = /Firefox/i.test(Navegador_),
                               Mobile_ = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(Navegador_),
                               FirfoxMobile = (Firfx && Mobile_);

                           var li = new Array();
                           function crear_select(){
                               var div_cont_select = document.querySelectorAll("[data-mate-select='active']");
                               var select_ = '';
                               for (var e = 0; e < div_cont_select.length; e++) {
                                   div_cont_select[e].setAttribute('data-indx-select',e);
                                   div_cont_select[e].setAttribute('data-selec-open','false');
                                   var ul_cont = document.querySelectorAll("[data-indx-select='"+e+"'] > .cont_list_select_mate > ul");
                                   select_ = document.querySelectorAll("[data-indx-select='"+e+"'] >select")[0];

                                   var select_optiones = select_.options;
                                   document.querySelectorAll("[data-indx-select='"+e+"']  > .selecionado_opcion ")[0].setAttribute('data-n-select',e);
                                   document.querySelectorAll("[data-indx-select='"+e+"']  > .icon_select_mate ")[0].setAttribute('data-n-select',e);
                                   for (var i = 0; i < select_optiones.length; i++) {
                                       li[i] = document.createElement('li');
                                       if (select_optiones[i].selected == true || select_.value == select_optiones[i].innerHTML ) {
                                           li[i].className = 'active';
                                           document.querySelector("[data-indx-select='"+e+"']  > .selecionado_opcion ").innerHTML = select_optiones[i].innerHTML;
                                       };
                                       li[i].setAttribute('data-index',i);
                                       li[i].setAttribute('data-selec-index',e);
                                       // funcion click al selecionar
                                       li[i].addEventListener( 'click', function(){  _select_option(this.getAttribute('data-index'),this.getAttribute('data-selec-index')); });

                                       li[i].innerHTML = select_optiones[i].innerHTML;
                                       ul_cont[0].appendChild(li[i]);

                                   }; // Fin For select_optiones
                               }; // fin for divs_cont_select
                           } // Fin Function


                           var cont_slc = 0;
                           function open_select(idx){
                               var idx1 =  idx.getAttribute('data-n-select');
                               var ul_cont_li = document.querySelectorAll("[data-indx-select='"+idx1+"'] .cont_select_int > li");
                               var hg = 0;
                               var slect_open = document.querySelectorAll("[data-indx-select='"+idx1+"']")[0].getAttribute('data-selec-open');
                               var slect_element_open = document.querySelectorAll("[data-indx-select='"+idx1+"'] select")[0];
                                   for (var i = 0; i < ul_cont_li.length; i++) {
                                       hg += ul_cont_li[i].offsetHeight;
                                   };
                                   if (slect_open == 'false')
                                   {
                                       document.querySelectorAll("[data-indx-select='"+idx1+"']")[0].setAttribute('data-selec-open','true');
                                       document.querySelectorAll("[data-indx-select='"+idx1+"'] > .cont_list_select_mate > ul")[0].style.height = hg+"px";
                                       document.querySelectorAll("[data-indx-select='"+idx1+"'] > .icon_select_mate")[0].style.transform = 'rotate(180deg)';
                                   }
                                   else
                                   {
                                       document.querySelectorAll("[data-indx-select='"+idx1+"']")[0].setAttribute('data-selec-open','false');
                                       document.querySelectorAll("[data-indx-select='"+idx1+"'] > .icon_select_mate")[0].style.transform = 'rotate(0deg)';
                                       document.querySelectorAll("[data-indx-select='"+idx1+"'] > .cont_list_select_mate > ul")[0].style.height = "0px";
                                   }

                           } // fin function open_select

                           function salir_select(indx)
                           {
                               var select_ = document.querySelectorAll("[data-indx-select='"+indx+"'] > select")[0];
                               document.querySelectorAll("[data-indx-select='"+indx+"'] > .cont_list_select_mate > ul")[0].style.height = "0px";
                               document.querySelector("[data-indx-select='"+indx+"'] > .icon_select_mate").style.transform = 'rotate(0deg)';
                               document.querySelectorAll("[data-indx-select='"+indx+"']")[0].setAttribute('data-selec-open','false');
                           }


                           function _select_option(indx,selc)
                           {

                               var select_ = document.querySelectorAll("[data-indx-select='"+selc+"'] > select")[0];

                               var li_s = document.querySelectorAll("[data-indx-select='"+selc+"'] .cont_select_int > li");
                               var p_act = document.querySelectorAll("[data-indx-select='"+selc+"'] > .selecionado_opcion")[0].innerHTML = li_s[indx].innerHTML;
                               var select_optiones = document.querySelectorAll("[data-indx-select='"+selc+"'] > select > option");
                               for (var i = 0; i < li_s.length; i++) {
                                   if (li_s[i].className == 'active') {
                                       li_s[i].className = '';
                                   };
                                   li_s[indx].className = 'active';

                               };
                               select_optiones[indx].selected = true;
                               select_.selectedIndex = indx;

                               // Create a new 'change' event for select element & dispatch it
                               var event = new Event('change');
                               select_.dispatchEvent(event);

                               salir_select(selc);
                           }
                       </script>
                       <!-- end adding -->
                   </div>
           </div>
           <div class="col-lg-5 col-md-4 col-sm-12 archive-list-panel">
               <div class="featured clearfix item-list archive-item-list">
                   <?php
                            $listing_category = get_field('page_category');

                            $args = array(
                           'post_type' => 'product',
                           'tax_query' => array(
                               array(
                                   'taxonomy' => 'product-cat',
                                   'field'    => 'term_id',
                                   'terms'    => $listing_category,
                               ),
                           ),
                           'orderby' => 'menu_order',
                           'order' => 'ASC',
                           'nopaging' => true
                       );
                   ?>


                   <?php $archive  =  get_posts($args); ?>

                   <?php foreach ($archive as $caravan):  ?>
                       <?php
                       $product_img = get_field('banner_image',$caravan->ID);
                       $badge_img = get_field('banner_badge',$caravan->ID);

                       //add filter value for each item
                       //filter price and size
                       $filter_price = get_field('price_thousands',$caravan->ID);
                       $filter_size = get_field('size_feet',$caravan->ID);

                       if($filter_price <= 51) :
                           $filter_price = "price-41-50";
                       elseif($filter_price > 51 && $filter_price <= 61) :
                           $filter_price = "price-51-60";
                       elseif($filter_price > 61 && $filter_price <= 71) :
                           $filter_price = "price-61-70";
                       elseif($filter_price > 71 && $filter_price <= 81) :
                           $filter_price = "price-71-80";
                       elseif($filter_price > 81 && $filter_price <= 91) :
                           $filter_price = "price-81-90";
                       elseif($filter_price > 91 && $filter_price <= 101) :
                           $filter_price = "price-91-100";
                       elseif($filter_price > 100) :
                           $filter_price = "price-91";
                       endif;

                       if($filter_size <= 19) :
                           $filter_size = "size-14-19";
                       elseif($filter_size > 19 && $filter_size <= 25) :
                           $filter_size = "size-20-25";
                       elseif($filter_size > 25) :
                           $filter_size = "size-26";
                       endif;

                       //fitler type, terrain and family bunk
                       $terms =  get_the_terms( $caravan, 'product-cat' );
                       $hyprid_filter = array('Hybrid - Semi Off-Road','Hybrid - On-Road','Hybrid - Off-Road');
                       $caravan_filter= array('Caravan - Off-Road','Caravan - On-Road','Caravan - Semi Off-Road');
                       $camper_filter = array('Camper - Off-Road','Camper - On-Road','Camper - Semi Off-Road');
                       $filter_type = '';
                       $filter_terrain='';
                       $filter_family = '';
                       foreach ( $terms as $term )
                       {
                           if(in_array( $term->name ,array('Caravan - Family Bunk')))
                           {
                               $filter_family = 'family-bunk';
                           }


                           if(in_array( $term->name ,$hyprid_filter))
                           {
                                $split = explode(' - ',$term->name);
                                $filter_type = 'type-hyprid';
                                $filter_terrain= 'terrain-' .  str_replace(' ','-',strtolower($split[1])) ;
                                break;

                           }
                           elseif(in_array( $term->name ,$camper_filter))
                           {
                               $split = explode(' - ',$term->name);
                               $filter_type = 'type-camper';
                               $filter_terrain= 'terrain-' . str_replace(' ','-',strtolower($split[1])) ;
                               break;
                           }
                           elseif(in_array( $term->name ,$caravan_filter))
                           {
                               $split = explode(' - ',$term->name);
                               $filter_type = 'type-caravan';
                               $filter_terrain= 'terrain-' .  str_replace(' ','-',strtolower($split[1])) ;
                               break;
                           }
                           else
                           {

                                //do nothing
                           }
                       }



                       ?>

                       <?php //Starting Element Row ?>
                       <div class="row">
                           <div class="item archive-item <?php echo $filter_price; ?> <?php echo $filter_size; ?> <?php echo $filter_type ?> <?php echo $filter_terrain; ?> <?php echo $filter_family; ?> col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <a class="cd-btn js-cd-panel-trigger" data-panel="main" href="#" caravan-id="<?php echo $caravan->ID; ?>"  caravan-title="<?php echo get_the_title($caravan); ?>" >
                                   <?php if($product_img): ?>
                                       <div class="item-img">
                                           <?php if(!empty($badge_img)): ?>
                                               <div class="banner-badge" style="background-image:url('<?php echo $badge_img['url'] ?>')"></div>
                                           <?php endif; ?>
                                           <img alt="caravan image" src="<?php echo $product_img['sizes']['medium']; ?>"/>
                                       </div>
                                   <?php endif; ?>
                                   <div class="item-details">
                                       <div class="details">
                                           <h3><?php echo get_the_title($caravan); ?></h3>
                                           <div class="product-meta clearfix">
                                               <?php if(get_field('price_thousands',$caravan->ID)): ?><span class="price"><strong>Price: </strong>$<?php the_field('price_thousands',$caravan->ID); ?>,<?php the_field('price_hundreds',$caravan->ID); ?><i>+ORC</i></span><?php endif; ?>
                                               <?php if(get_field('size_feet',$caravan->ID)): ?><span class="size"><strong>Size: </strong><?php the_field('size_feet',$caravan->ID); ?>'<?php if(get_field('size_inches',$caravan->ID)): ?><?php the_field('size_inches',$caravan->ID); ?>"<?php endif; ?></span><?php endif; ?>
                                               <?php if(get_field('occupants',$caravan->ID)): ?><span class="occupants"><strong>Occupants: </strong><?php the_field('occupants',$caravan->ID); ?></span><br/><br/><?php endif; ?>

                                               <?php if(get_field('tare',$caravan->ID)): ?><span class="tare"><strong>Tare (approx): </strong><?php the_field('tare',$caravan->ID); ?></span><?php endif; ?>
                                               <?php if(get_field('ball_weight',$caravan->ID)): ?><span class="ball"><strong>Ball weight (approx): </strong><?php the_field('ball_weight',$caravan->ID); ?></span><?php endif; ?>

                                           </div>
                                       </div>
                                   </div>
                               </a>
                           </div>
                       </div>
                   <?php endforeach; ?>
                </div>
            </div>


           <div class="col-lg-5 col-md-5 col-sm-12 archive-item-detail-panel">
                <div class="archive-listing-item-detail-pane-notice">
                    <h3>Select the caravan to display the detail on this panel </h3>
                </div>
               <div class="archive-item-detail-loading-panel">
                   <img src="<?php echo get_stylesheet_directory_uri(); ?>/_img/loading-progress.svg" class="loading-icon" alt="loading icon" width="250"/>
                   <p class="loading-icon-text">Loading...</p>
               </div>
                <div class="archive-listing-item-detail-pane js-cd-panel-main cd-panel--stay-right">
                        <div class="cd-panel__container">
                            <div class="cd-panel__content">
                            </div> <!-- cd-panel__content -->
                        </div> <!-- cd-panel__container -->

                        <div id="clear" style="clear:both;"></div>
                </div>
           </div>
       </div>
    </div>
</div>


<div class="archive-listing-item-detail-pane cd-panel--from-right js-cd-panel-main">
    <header class="cd-panel__header">
        <h1>Title Goes Here</h1>
        <a href="#0" class="cd-panel__close js-cd-close">Close</a>
    </header>

    <div class="cd-panel__container">
        <div class="cd-panel__content">

        </div> <!-- cd-panel__content -->
    </div> <!-- cd-panel__container -->
</div>



<?php get_footer(); ?>


<script type="text/javascript">
    jQuery(function($) {
        // Slide In Panel - by CodyHouse.co
        var panelTriggers = document.getElementsByClassName('js-cd-panel-trigger');
        if (panelTriggers.length > 0) {
            for (var i = 0; i < panelTriggers.length; i++) {
                (function (i) {
                    var panelClass = 'js-cd-panel-' + panelTriggers[i].getAttribute('data-panel') + ' cd-panel--from-right';
                    var  panel = document.getElementsByClassName(panelClass)[0];

                    // open panel when clicking on trigger btn
                    panelTriggers[i].addEventListener('click', function (event) {
                        var data = {
                            'action':'archiveitem',
                            'post_id': $(this).attr('caravan-id')
                        };
                        var url = "<?php echo site_url() ?>/wp-admin/admin-ajax.php";

                        //set the title for panel
                        $('.archive-listing-item-detail-pane.js-cd-panel-main .cd-panel__header h1').html( $(this).attr('caravan-title'));

                        //loading the caravan detail before open panel
                        $.ajax({
                            url: url,
                            data: data,
                            type: "POST",
                            beforeSend: function() {
                                if(window.outerWidth <= 991)
                                {
                                    $('#loading-icon-panel').show();
                                }
                                else
                                {
                                    $(".archive-listing-item-detail-pane.js-cd-panel-main.cd-panel--stay-right").removeClass('cd-panel--is-visible');
                                    $('.archive-item-detail-loading-panel').show();
                                }

                            },
                            success:function(data){

                                $('div.archive-listing-item-detail-pane .cd-panel__container .cd-panel__content').html(data); // insert data

                                // Product Gallery
                                $('.archive-listing-item-detail-pane .flexslider').flexslider({
                                    slideshow: false,
                                    animation: "slide",
                                    animationLoop: true,
                                    itemWidth: 300,
                                    itemMargin: 2,
                                    maxItems: 3,
                                    controlNav: false
                                });

                                if(window.outerWidth <= 991)
                                {
                                    $('#loading-icon-panel').hide();
                                    addClass(panel, 'cd-panel--is-visible');
                                }
                                else
                                {
                                    $('.archive-item-detail-loading-panel').hide();
                                    $(".archive-listing-item-detail-pane.js-cd-panel-main.cd-panel--stay-right").addClass('cd-panel--is-visible');
                                }
                            }
                        });
                        event.preventDefault();

                    });

                    //close panel when clicking on 'x' or outside the panel
                    panel.addEventListener('click', function (event) {
                        if (hasClass(event.target, 'js-cd-close') || hasClass(event.target, panelClass)) {
                            event.preventDefault();
                            removeClass(panel, 'cd-panel--is-visible');
                        }
                    });
                })(i);
            }
        }

        //class manipulations - needed if classList is not supported
        //https://jaketrent.com/post/addremove-classes-raw-javascript/
        function hasClass(el, className) {
            if (el.classList) return el.classList.contains(className);
            else return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
        }

        function addClass(el, className) {
            if (el.classList) el.classList.add(className);
            else if (!hasClass(el, className)) el.className += " " + className;
        }

        function removeClass(el, className) {
            if (el.classList) el.classList.remove(className);
            else if (hasClass(el, className)) {
                var reg = new RegExp('(\\s|^)' + className + '(\\s|$)');
                el.className = el.className.replace(reg, ' ');
            }
        }
    });

</script>
