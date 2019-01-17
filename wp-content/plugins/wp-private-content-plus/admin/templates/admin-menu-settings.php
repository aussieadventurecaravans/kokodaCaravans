<?php

    global $menu, $submenu, $wppcp_settings_data,$wppcp;
    extract($wppcp_settings_data);
    $user_roles = $wppcp->roles_capability->wppcp_user_roles();
    if ( ! isset( $menu ) || empty( $menu ) ) {
      return;
    }

?>

<div id="wppcp-admin-menu-permission-panel">
	<div id="wppcp-admin-menu-permission-list">
		<?php 

			foreach ( $menu as $key => $item ) { 
				
				if ( isset( $item[ 2 ] ) ) {
          $menu_slug = $item[ 2 ];
        }

        if($item[0] != ''){
				?>
				<div data-key="<?php echo $key; ?>" class="wppcp-admin-menu-permission-level1" data-slug="<?php echo $menu_slug; ?>" >
					<?php echo $item[0]; ?>
				</div>
		<?php
				}

				if ( isset( $submenu ) && ! empty( $submenu[ $menu_slug ] ) ) { 
    ?>
        <div id="wppcp-admin-menu-<?php echo $key; ?>" class="wppcp-admin-menu-permission-level1-panel">
    <?php
        foreach ( (array) $submenu[ $menu_slug ] as $subindex => $subitem ) { 
    ?>

          <div class="wppcp-admin-menu-permission-level2" data-slug="<?php echo $subitem[2]; ?>" >
						<?php echo $subitem[0]; ?>
					</div>
			<?php			
	        }
      ?>
        </div>
      <?php
	      }
			}
		?>
	</div>
	<div id="wppcp-admin-menu-permissions">
    <div id="wppcp-admin-menu-permissions-info">Click on the menu items to assign permissions. This feature only allows you to restrict backend 
      features to users based on the existing permissions. You can't use it to assign features to users.</div>
	</div>
</div>

    <!-- foreach ( $menu as $key => $item ) {
echo $item[0];
      
        if ( isset( $item[ 2 ] ) ) {
          $menu_slug = $item[ 2 ];
          // Check, if the Menu item in the current user role settings?
          // if ( in_array( $menu_slug, $mw_adminimize_menu, false )
          // ) {
            // remove_menu_page( $menu_slug );
            // Prevent access to the page with the slug, there was inactive.
        //     _mw_adminimize_check_page_access( $menu_slug );
        }


      if ( isset( $submenu ) && ! empty( $submenu[ $menu_slug ] ) ) { 
        foreach ( (array) $submenu[ $menu_slug ] as $subindex => $subitem ) { 

            // if(in_array($subitem[0], array('Library', 'All Comments'))) {         
            //   remove_submenu_page( $menu_slug, $subitem[ 2 ] );
            // }
            // Prevent access to the page with the slug, there was inactive.
            // _mw_adminimize_check_page_access( $subitem[ 2 ] );

        }
      }  
        
    } -->

