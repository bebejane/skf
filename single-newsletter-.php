<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html data-editor-version="2" class="sg-campaigns" xmlns="http://www.w3.org/1999/xhtml">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
      <!--[if !mso]><!-->
      <meta http-equiv="X-UA-Compatible" content="IE=Edge">
      <!--<![endif]-->
      <!--[if (gte mso 9)|(IE)]>
      <xml>
        <o:OfficeDocumentSettings>
          <o:AllowPNG/>
          <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
      </xml>
      <![endif]-->
      <!--[if (gte mso 9)|(IE)]>
  <style type="text/css">
    body {width: 600px;margin: 0 auto;}
    table {border-collapse: collapse;}
    table, td {mso-table-lspace: 0pt;mso-table-rspace: 0pt;}
    img {-ms-interpolation-mode: bicubic;}
  </style>
<![endif]-->
      <style type="text/css">
    body, p, div {
      font-family: arial,helvetica,sans-serif;
      font-size: 14px;
    }
    body {
      color: #282828;
    }
    body a {
      color: <?php the_field('headline_color','option'); ?>;
      text-decoration: none;
    }
    p { margin: 0; padding: 0; }
    table.wrapper {
      width:100% !important;
      table-layout: fixed;
      -webkit-font-smoothing: antialiased;
      -webkit-text-size-adjust: 100%;
      -moz-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
    }
    img.max-width {
      max-width: 100% !important;
    }
    .column.of-2 {
      width: 50%;
    }
    .column.of-3 {
      width: 33.333%;
    }
    .column.of-4 {
      width: 25%;
    }
    ul ul ul ul  {
      list-style-type: disc !important;
    }
    ol ol {
      list-style-type: lower-roman !important;
    }
    ol ol ol {
      list-style-type: lower-latin !important;
    }
    ol ol ol ol {
      list-style-type: decimal !important;
    }
    @media screen and (max-width:480px) {
      .preheader .rightColumnContent,
      .footer .rightColumnContent {
        text-align: left !important;
      }
      .preheader .rightColumnContent div,
      .preheader .rightColumnContent span,
      .footer .rightColumnContent div,
      .footer .rightColumnContent span {
        text-align: left !important;
      }
      .preheader .rightColumnContent,
      .preheader .leftColumnContent {
        font-size: 80% !important;
        padding: 5px 0;
      }
      table.wrapper-mobile {
        width: 100% !important;
        table-layout: fixed;
      }
      img.max-width {
        height: auto !important;
        max-width: 100% !important;
      }
      a.bulletproof-button {
        display: block !important;
        width: auto !important;
        font-size: 80%;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }
      .columns {
        width: 100% !important;
      }
      .column {
        display: block !important;
        width: 100% !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
      }
      .social-icon-column {
        display: inline-block !important;
      }
    }
  </style>
      <!--user entered Head Start--><!--End Head user entered-->
    </head>
    <body>


      <center class="wrapper" data-link-color="#1188E6" data-body-style="font-size:14px; font-family:arial,helvetica,sans-serif; color:#282828; background-color:#F8F8F8;">
        <div class="webkit">
          <table cellpadding="0" cellspacing="0" border="0" width="100%" class="wrapper" bgcolor="#F8F8F8">
            <tr>
              <td valign="top" bgcolor="#F8F8F8" width="100%">
                <table width="100%" role="content-container" class="outer" align="center" cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td width="100%">
                      <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                          <td>
                            <!--[if mso]>
                                  <center>
                                  <table><tr><td width="600">
                                <![endif]-->
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:600px;" align="center">
                                      <tr>
                                        <td role="modules-container" style="padding:0px 0px 0px 0px; color:#282828; text-align:left;" bgcolor="#ffffff" width="100%" align="left">
                                        <table class="module preheader preheader-hide" role="module" data-type="preheader" border="0" cellpadding="0" cellspacing="0" width="100%" style="display: none !important; mso-hide: all; visibility: hidden; opacity: 0; color: transparent; height: 0; width: 0;">
                                        <tr>
                                          <td role="module-content">
                                            <p></p>
                                          </td>
                                        </tr>
                                    </table>
  
 

    <?php

    // Check rows exists.
    if( have_rows('sections') ):

        // Loop through rows.
        while( have_rows('sections') ) : the_row();

        ?>

         <!-- Bild -->

  <?php if(get_sub_field('image')) {
      $image = get_sub_field('image');
      $size = 'large';
      $thumb = $image['sizes'][ $size ];
      ?>

  <table class="wrapper" role="module" data-type="image" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;" data-muid="b9ea7925-939b-4551-96e5-e859e5ab34d1">
    <tbody>
      <tr>
        <td style="font-size:6px; line-height:10px; padding:0px 0px 0px 0px;" valign="top" align="center">
          <img class="max-width" border="0" style="display:block; color:#000000; text-decoration:none; font-family:Helvetica, arial, sans-serif; font-size:16px; max-width:100% !important; width:100%; height:auto !important;" width="600" alt="" data-proportionally-constrained="true" data-responsive="true" src="<?php echo esc_url($thumb); ?>">
        </td>
      </tr>
    </tbody>
  </table>

  <?php } ?>

  <!-- Text -->
  
  <table class="module" role="module" data-type="text" border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;" data-muid="5f717973-c2f4-4432-90ae-01356e09e285">
    <tbody>
      <tr>
        <td style="padding:4em 3em 3em 3em; line-height:22px; text-align:inherit;" height="100%" valign="top" bgcolor="" role="module-content">
          <div><div style="font-family: inherit"><span style="font-size: 30px; font-weight:bold; color:<?php the_field('headline_color','option'); ?>"><?php the_sub_field('headline'); ?></span></div>
          <div style="font-family: inherit"><br></div>
          <div style="font-family: inherit"><?php  the_sub_field('text'); ?></div><div></div></div>
        </td>
      </tr>
    </tbody>
  </table>
  

  <?php if( get_sub_field('has_button') == true) { ?>

        <!-- BUTTON -->
  
    <table border="0" cellpadding="0" cellspacing="0" class="module" data-role="module-button" data-type="button" role="module" style="table-layout:fixed;" width="100%" data-muid="2df580f3-b9ca-4bd1-af58-e33c97a58188">
      <tbody>
        <tr>
          <td align="center" bgcolor="" class="outer-td" style="padding:0px 0px 0px 0px;">
            <table border="0" cellpadding="0" cellspacing="0" class="wrapper-mobile" style="text-align:center;">
              <tbody>
                <tr>
                <td align="center" bgcolor="#333333" class="inner-td" style="border-radius:6px; font-size:16px; text-align:center; background-color:inherit; padding-bottom: 4em">
                  <a href="<?php the_sub_field('url') ?>" style="background-color:<?php the_field('headline_color','option'); ?>; border:1px solid #333333; border-color:<?php the_field('headline_color','option'); ?>; border-radius:6px; border-width:1px; color:#ffffff; display:inline-block; font-size:14px; font-weight:normal; letter-spacing:0px; line-height:normal; padding:12px 18px 12px 18px; text-align:center; text-decoration:none; border-style:solid;" target="_blank"><?php the_sub_field('text_link') ?></a>
                </td>
                </tr>
              </tbody>
            </table>
          </td>
        </tr>
      </tbody>
    </table>

    <?php } ?>


    <?php
        // End loop.
        endwhile;

      endif;

    ?>


    <!-- FOOTER -->
      <div data-role="module-unsubscribe" class="module" role="module" data-type="unsubscribe" style="margin-top: 1em; background:#F8F8F8; color:#444444; font-size:12px; line-height:20px; padding:16px 16px 16px 16px; text-align:Center;" data-muid="4e838cf3-9892-4a6d-94d6-170e474d21e5">
      <div class="Unsubscribe--addressLine">
        <p class="Unsubscribe--senderName" style="font-size:12px; line-height:20px;">
            <?php
                    if(is_multisite()) {
                      global $blog_id;
                      $current_blog_details = get_blog_details( array( 'blog_id' => $blog_id ) );
                      echo $current_blog_details->blogname;
                    }
            ?>
        </p>
      </div>
      <!--<p style="font-size:12px; line-height:20px;"><a class="Unsubscribe--unsubscribeLink" href="{{{unsubscribe}}}" target="_blank" style="">Unsubscribe</a> - <a href="{{{unsubscribe_preferences}}}" target="_blank" class="Unsubscribe--unsubscribePreferences" style="">Unsubscribe Preferences</a></p></div></td>-->
                                      </tr>
                                    </table>
                                    <!--[if mso]>
                                  </td>
                                </tr>
                              </table>
                            </center>
                            <![endif]-->
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </center>




    </body>
  </html>