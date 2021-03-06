<?php
$store_user    = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info    = $store_user->get_shop_info();
$social_info   = $store_user->get_social_profiles();
$store_tabs    = dokan_get_store_tabs( $store_user->get_id() );
$social_fields = dokan_get_social_profile_fields();

$dokan_appearance = get_option( 'dokan_appearance' );
$profile_layout   = empty( $dokan_appearance['store_header_template'] ) ? 'default' : $dokan_appearance['store_header_template'];
$store_address    = dokan_get_seller_short_address( $store_user->get_id(), false );

$general_settings = get_option( 'dokan_general', [] );
$banner_width     = ! empty( $general_settings['store_banner_width'] ) ? $general_settings['store_banner_width'] : 625;

if ( ( 'default' === $profile_layout ) || ( 'layout2' === $profile_layout ) ) {
    $profile_img_class = 'profile-img-circle';
} else {
    $profile_img_class = 'profile-img-square';
}

if ( 'layout3' === $profile_layout ) {
    unset( $store_info['banner'] );

    $no_banner_class = ' profile-frame-no-banner';
    $no_banner_class_tabs = ' dokan-store-tabs-no-banner';

} else {
    $no_banner_class = '';
    $no_banner_class_tabs = '';
}

$userId = $store_user->get_id();
//var_dump($userId);
$userMeta = get_user_meta($userId);
$userData = get_userdata($userId);
//var_dump($userData);
$userCity = $userMeta['billing_city'][0];
$userRegistrationDate = $userData->user_registered;
$userNickname = $userData->display_name;
?>

<?php //var_dump($store_user);?>
<div id="storeHeader">
    <div class="profile-frame<?php echo $no_banner_class; ?>">

        <div class="profile-info-box profile-layout-<?php echo $profile_layout; ?>">
            <?php if ( $store_user->get_banner() ) { ?>
                <img src="<?php echo $store_user->get_banner(); ?>"
                    alt="<?php echo $store_user->get_shop_name(); ?>"
                    title="<?php echo $store_user->get_shop_name(); ?>"
                    class="profile-info-img">
            <?php } else { ?>
                <div class="profile-info-img dummy-image">&nbsp;</div>
            <?php } ?>

            <div class="profile-info-summery-wrapper dokan-clearfix">
                <div class="profile-info-summery">
                    <div class="profile-info-head">
                        <div class="profile-img <?php echo $profile_img_class; ?>">
                            <?php echo get_avatar( $store_user->get_id(), 150 ); ?>
                        </div>
                        <?php if ( ! empty( $store_user->get_shop_name() ) && 'default' === $profile_layout ) { ?>
                            <h1 class="store-name"><?php echo esc_html( $store_user->get_shop_name() ); ?></h1>
                        <?php } ?>
                    </div>

                    <div class="profile-info">
                    
                        <?php if ( ! empty( $store_user->get_shop_name() ) && 'default' !== $profile_layout ) { ?>
                            <h1 class="store-name"><?php echo esc_html(  ); ?></h1>
                        <?php } ?>

                        <ul class="dokan-store-info">
                            <?php if ( isset( $store_address ) && !empty( $store_address ) ) { ?>
                                <li class="dokan-store-address"><i class="fa fa-map-marker"></i>
                                    <?php echo esc_html($userCity); ?>
                                </li>
                            <?php } ?>

                            <?php if ( !empty( $userRegistrationDate ) ) { ?>
                                <li class="dokan-store-register">
                                    <i class="fa fa-calendar"></i>
                                    <p><?php echo esc_html(date( "d/m/Y", strtotime( $userRegistrationDate ) )); ?></p>
                                </li>
                            <?php } ?>



                            <li class="dokan-store-rating">
                                <i class="fa fa-star"></i>
                                <?php dokan_get_readable_seller_rating( $store_user->get_id() ); ?>
                            </li>
                            
                            <?php do_action( 'dokan_store_header_info_fields',  $store_user->get_id() ); ?>
                        </ul>

                        <?php if ( $social_fields ) { ?>
                            <div class="store-social-wrapper">
                                <ul class="store-social">
                                    <?php foreach( $social_fields as $key => $field ) { ?>
                                        <?php if ( !empty( $social_info[ $key ] ) ) { ?>
                                            <li>
                                                <a href="<?php echo esc_url( $social_info[ $key ] ); ?>" target="_blank"><i class="fa fa-<?php echo $field['icon']; ?>"></i></a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?> 
                    </div> <!-- .profile-info -->
                    <div id="userDescription" class="profile-info">
                        <p class="textWriteStyle">
                             <?php echo esc_html( !empty($userMeta['user_description'][0]) ? '"'. $userMeta['user_description'][0] . '"' : $userNickname . ' n\'a pas encore écrit son message de présentation.' ) ;?>
                        </p>
                    </div>
                </div><!-- .profile-info-summery -->
            </div><!-- .profile-info-summery-wrapper -->
        </div> <!-- .profile-info-box -->
    </div> <!-- .profile-frame -->

    <?php if ( $store_tabs ) { ?>
        <div class="dokan-store-tabs<?php echo $no_banner_class_tabs; ?>">
            <ul class="dokan-list-inline">
                <?php foreach( $store_tabs as $key => $tab ) { ?>
                    <li><a href="<?php echo esc_url( $tab['url'] ); ?>"><?php echo $tab['title']; ?></a></li>
                <?php } ?>
                <?php do_action( 'dokan_after_store_tabs', $store_user->get_id() ); ?>
            </ul>
        </div>
    <?php } ?>
</div>