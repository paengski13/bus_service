<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
    <head>
        <title>Bus Service incube8</title>

        <!-- Meta -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ URL::to('assets/img/favicon.ico') }}">

        <!-- CSS Global Compulsory -->
        <link rel="stylesheet" href="{{ URL::to('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('assets/css/style.css') }}">

        <!-- CSS Implementing Plugins -->
        <link rel="stylesheet" href="{{ URL::to('assets/plugins/line-icons/line-icons.css') }}">
        <link rel="stylesheet" href="{{ URL::to('assets/plugins/font-awesome/css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ URL::to('assets/plugins/sky-forms/version-2.0.1/css/custom-sky-forms.css') }}">
        <link rel="stylesheet" href="{{ URL::to('assets/css/plugins/hover-effect/css/custom-hover-effects.css') }}">  
        
        <link rel="stylesheet" href="{{ URL::to('assets/plugins/flexslider/flexslider.css') }}"> 
        <link rel="stylesheet" href="{{ URL::to('assets/plugins/bxslider/jquery.bxslider.css') }}">             
        <link rel="stylesheet" href="{{ URL::to('assets/plugins/horizontal-parallax/css/horizontal-parallax.css') }}">
        <link rel="stylesheet" href="{{ URL::to('assets/css/pages/feature_timeline2.css') }}">
        
        <!-- CSS Theme -->    
        <!--link rel="stylesheet" href="{{ URL::to('assets/css/themes/default.css') }}" id="style_color"-->
        <link rel="stylesheet" href="{{ URL::to('assets/css/theme-colors/default.css') }}" id="style_color">
        <link rel="stylesheet" href="{{ URL::to('assets/css/theme-skins/dark.css') }}">
        
        <!-- CSS Customization -->
        <link rel="stylesheet" href="{{ URL::to('assets/css/custom.css') }}">
        
        <!-- JS -->
        <script type="text/javascript" src="{{ URL::to('assets/plugins/jquery/jquery.min.js') }}"></script>

        <!--[if lt IE 9]>
            <script src="{{ URL::to('assets/plugins/html5.js') }}"></script>
            <script src="{{ URL::to('assets/plugins/respond.js') }}"></script>
            <link rel="stylesheet" href="{{ URL::to('assets/css/custom_ie8.css') }}">
            <script src="{{ URL::to('assets/js/custom_ie8.js') }}"></script>
        <![endif]-->
    </head> 

    <body class="boxed-layout container">
        <!-- Message -->
        <div class="modal fade" id="message_alert" tabindex="-1" role="dialog" aria-labelledby="message_alert_label" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button-->
                        <h4 class="modal-title" id="message_alert_label">Message</h4>
                    </div>
                    <div class="modal-body">
                        <p id="message_content"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-u btn-u-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="message_alert_small" tabindex="-1" role="dialog" aria-labelledby="message_alert_small_label" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button-->
                        <h4 class="modal-title" id="message_alert_small_label">Message</h4>
                    </div>
                    <div class="modal-body">
                        <p id="message_content_small"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-u btn-u-default" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Message -->

        <div class="wrapper boxed-layout-btn">
            @include("header")
            
            @yield("content")

            @include("footer")
        </div>

        <!-- JS Global Compulsory -->
        <script type="text/javascript" src="{{ URL::to('assets/plugins/jquery/jquery-migrate.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::to('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        
        <!-- JS Implementing Plugins -->
        <input type="hidden" name="imgUp" value="{{ URL::to('assets/img/up.png') }}"/>
        <script type="text/javascript" src="{{ URL::to('assets/plugins/back-to-top.js') }}"></script>
        <script type="text/javascript" src="{{ URL::to('assets/plugins/flexslider/jquery.flexslider-min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::to('assets/plugins/horizontal-parallax/js/sequence.jquery-min.js') }}"></script>
        @if (Request::path() == 'home')
        <script type="text/javascript" src="{{ URL::to('assets/plugins/horizontal-parallax/js/horizontal-parallax.js') }}"></script>
        @endif
        <!-- Masking Form -->
        <script src="{{ URL::to('assets/plugins/sky-forms/version-2.0.1/js/jquery.maskedinput.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::to('assets/plugins/bxslider/jquery.bxslider.js') }}"></script>
        <!-- Datepicker Form -->
        <script src="{{ URL::to('assets/plugins/sky-forms/version-2.0.1/js/jquery-ui.min.js') }}"></script>
        <!-- Login Form -->
        <script type="text/javascript" src="{{ URL::to('assets/plugins/sky-forms/version-2.0.1/js/jquery.form.min.js') }}"></script>
        <!-- Validation Form -->
        <script type="text/javascript" src="{{ URL::to('assets/plugins/sky-forms/version-2.0.1/js/jquery.validate.min.js') }}"></script>
        <!-- JS Page Level -->
        <script type="text/javascript" src="{{ URL::to('assets/js/app.js') }}"></script>
        <script type="text/javascript" src="{{ URL::to('assets/js/plugins/masking.js') }}"></script>
        <script type="text/javascript" src="{{ URL::to('assets/js/plugins/datepicker.js') }}"></script>

        <!--script type="text/javascript" src="{{ URL::to('assets/js/forms/local_expense.js') }}"></script-->
        
        <script type="text/javascript" src="{{ URL::to('assets/js/pages/dynamic.js') }}"></script>
        <script type="text/javascript" src="{{ URL::to('assets/js/pages/field.js') }}"></script>

        <script type="text/javascript">
            jQuery(document).ready(function() {
                App.init();
                Masking.initMasking();
                Datepicker.initDatepicker();
                
                // for record deletion
                $('.open-ConfirmDelete').click(function () {
                    $("#form_delete").attr("action", $("input[name=path]").val() + "/" + $(this).val());
                });
            });

            
        </script>

        <!-- setup base url -->
        <input type="hidden" name="base_url" value="{{ URL::to('') }}"/>
        
        <!-- setup delimeter -->
        <input type="hidden" name="delimeter" value="{{ $shareView['delimeter'] }}"/>

    </body>
</html> 