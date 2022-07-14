<!DOCTYPE html>
<html dir="@if( Config::get('app.locale') == 'ar' || $general_setting->is_rtl){{'rtl'}}@endif">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{url('manifest.json')}}">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css">


    <link rel="preload" href="<?php echo asset('vendor/bootstrap-toggle/css/bootstrap-toggle.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/bootstrap-toggle/css/bootstrap-toggle.min.css') ?>" rel="stylesheet"></noscript>
    <link rel="preload" href="<?php echo asset('vendor/bootstrap/css/bootstrap-datepicker.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/bootstrap/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet"></noscript>
    <link rel="preload" href="<?php echo asset('vendor/jquery-timepicker/jquery.timepicker.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/jquery-timepicker/jquery.timepicker.min.css') ?>" rel="stylesheet"></noscript>
    <link rel="preload" href="<?php echo asset('vendor/bootstrap/css/awesome-bootstrap-checkbox.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/bootstrap/css/awesome-bootstrap-checkbox.css') ?>" rel="stylesheet"></noscript>
    <link rel="preload" href="<?php echo asset('vendor/bootstrap/css/bootstrap-select.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/bootstrap/css/bootstrap-select.min.css') ?>" rel="stylesheet"></noscript>
    <!-- Font Awesome CSS-->
    <link rel="preload" href="<?php echo asset('vendor/font-awesome/css/font-awesome.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/font-awesome/css/font-awesome.min.css') ?>" rel="stylesheet"></noscript>
    <!-- Drip icon font-->
    <link rel="preload" href="<?php echo asset('vendor/dripicons/webfont.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/dripicons/webfont.css') ?>" rel="stylesheet"></noscript>
    <!-- Google fonts - Roboto -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Nunito:400,500,700" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css?family=Nunito:400,500,700" rel="stylesheet"></noscript>
    <!-- jQuery Circle-->
    <link rel="preload" href="<?php echo asset('css/grasp_mobile_progress_circle-1.0.0.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('css/grasp_mobile_progress_circle-1.0.0.min.css') ?>" rel="stylesheet"></noscript>
    <!-- Custom Scrollbar-->
    <link rel="preload" href="<?php echo asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css') ?>" rel="stylesheet"></noscript>

    <!-- date range stylesheet-->
    <link rel="preload" href="<?php echo asset('vendor/daterange/css/daterangepicker.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/daterange/css/daterangepicker.min.css') ?>" rel="stylesheet"></noscript>
    <!-- table sorter stylesheet-->
    <link rel="preload" href="<?php echo asset('vendor/datatable/dataTables.bootstrap4.min.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="<?php echo asset('vendor/datatable/dataTables.bootstrap4.min.css') ?>" rel="stylesheet"></noscript>
    <link rel="preload" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css" rel="stylesheet"></noscript>
    <link rel="preload" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css" rel="stylesheet"></noscript>

    <link rel="stylesheet" href="<?php echo asset('css/style.default.css') ?>" id="theme-stylesheet" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('css/dropzone.css') ?>">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?php echo asset('css/custom-'.$general_setting->theme) ?>" type="text/css" id="custom-style">
     @if( Config::get('app.locale') == 'ar' || $general_setting->is_rtl)
      <!-- RTL css -->
      <link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap-rtl.min.css') ?>" type="text/css">
      <link rel="stylesheet" href="<?php echo asset('css/custom-rtl.css') ?>" type="text/css" id="custom-style">
    @endif
  </head>
<body>
  @php $userr_id = auth()->user()->id; @endphp
  
<div class="page">
<header class="container-fluid">
        <nav class="navbar">
            <a id="toggle-btn" href="#" class="menu-btn"><i class="fa fa-bars"> </i></a>


            <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">

            <li class="nav-item"><a id="switch-theme" data-toggle="tooltip" title="{{trans('file.Switch Theme')}}"><i class="dripicons-brightness-max"></i></a></li>
            <li class="nav-item"><a id="btnFullscreen" data-toggle="tooltip" title="{{trans('file.Full Screen')}}"><i class="dripicons-expand"></i></a></li>

                <li class="nav-item">
                <a rel="nofollow" data-toggle="tooltip" title="{{__('Notifications')}}" class="nav-link dropdown-item"><i class="dripicons-bell"></i><span class="badge badge-danger notification-number">{{ count(\Auth::user()->unreadNotifications) > 0 ?  count(\Auth::user()->unreadNotifications) : '' }}</span>
                </a>
                @php $user_notifications = auth()->user()->notifications()->get(); @endphp
                <ul class="right-sidebar" id="notify">
                    @foreach($user_notifications as $noti)
                    @if($noti->noti_type == "formapprove" && $noti->read_at == NULL)
                        <li style="background-color: lightgrey"><a href="{{url('approved_dashboard',$noti->id)}}">{{ $noti->data['message'] }}</a></li>
                    
                        @elseif($noti->noti_type == "formreject" && $noti->read_at == NULL)
                        <li style="background-color: lightgrey"><a href="{{url('reShowSubmitForm',$noti->id)}}">{{ $noti->data['message'] }}</a></li>
                        
                        elseif($noti->noti_type == "formresubmit" && $noti->read_at == NULL)
                        <li style="background-color: lightgrey"><a href="{{url('reShowSubmitForm',$noti->id)}}">{{ $noti->data['message'] }}</a></li>
                        
                        @elseif($noti->noti_type == "formapprove" && $noti->read_at != NULL )
                        <li style="background-color: white"><a href="{{url('approved_dashboard',$noti->id)}}">{{ $noti->data['message'] }}</a></li>
                        
                        @elseif($noti->noti_type == "formreject" && $noti->read_at != NULL)
                        <li style="background-color: white"><a href="{{url('reShowSubmitForm',$noti->id)}}">{{ $noti->data['message'] }}</a></li>
                        
                        @elseif($noti->noti_type == "formresubmit" && $noti->read_at != NULL)
                        <li style="background-color: white"><a href="{{url('reShowSubmitForm',$noti->id)}}">{{ $noti->data['message'] }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </li>

            <li class="nav-item">
                    <a rel="nofollow" title="{{trans('file.language')}}" data-toggle="tooltip" class="nav-link dropdown-item"><i class="dripicons-web"></i></a>
                    <ul class="right-sidebar">
                        <li>
                        <a href="{{ url('language_switch/en') }}" class="btn btn-link"> English</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/es') }}" class="btn btn-link"> Español</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/ar') }}" class="btn btn-link"> عربى</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/s_chinese') }}" class="btn btn-link">中国人</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/t_chinese') }}" class="btn btn-link">中國人</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/pt_BR') }}" class="btn btn-link"> Portuguese</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/fr') }}" class="btn btn-link"> Français</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/de') }}" class="btn btn-link"> Deutsche</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/id') }}" class="btn btn-link"> Malay</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/hi') }}" class="btn btn-link"> हिंदी</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/vi') }}" class="btn btn-link"> Tiếng Việt</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/ru') }}" class="btn btn-link"> русский</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/bg') }}" class="btn btn-link"> български</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/tr') }}" class="btn btn-link"> Türk</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/it') }}" class="btn btn-link"> Italiano</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/nl') }}" class="btn btn-link"> Nederlands</a>
                        </li>
                        <li>
                        <a href="{{ url('language_switch/lao') }}" class="btn btn-link"> Lao</a>
                        </li>
                    </ul>
            </li>
            <li class="nav-item">
                <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i class="dripicons-user"></i> <span>{{ucfirst(Auth::user()->name)}}</span> <i class="fa fa-angle-down"></i>
                </a>
                <ul class="right-sidebar">
                    <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"><i class="dripicons-power"></i>
                        {{trans('file.logout')}}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    </li>
                </ul>
            </li>
            </ul>
        </nav>
      </header>

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div>
@endif
  

<section>
<form action="{{route('formSave')}}" method="post" enctype="multipart/form-data">
    @csrf

<div class="container">
    <div class="row">
        <div class="col-lg-12">
              <div class="row card p-2">
                        <div class="pull-left">
                          <h2 class="text-center">{{$form->form_name}}</h2>
                       </div>
                    </div>
               </div>
        </div>
<input type="hidden" name="form" value="{{ $form->id }}">
<div class="row card p-2">
   
    @foreach($form_fields as $f)
        @php $field_value = App\FormFieldData::where('user_id', auth()->user()->id)->where('form_id', $form->id)
        ->where('field_id', $f->id)->first(); @endphp
    <div class="col-xs-12 col-sm-12 col-md-12">
        @if($f->field_type == 1)
          <div class="form-group col-md-12">
               <label for="">{{$f->field_label}}</label>
               <input type="text" name="{{ $f->field_name }}" value="" class="form-control">
        </div>
        @elseif($f->field_type == 2)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <textarea name="{{ $f->field_name }}" id="" cols="30" rows="10"class="form-control"></textarea>
        </div>
        @elseif($f->field_type == 3)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="file" name="{{ $f->field_name }}" value="">
        </div>
        @elseif($f->field_type == 4)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="radio" name="{{ $f->field_name }}" >
        </div>
        @elseif($f->field_type == 6)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="email" name="{{ $f->field_name }}" class="form-control" value="">
        </div>
        @elseif($f->field_type == 5)
        <div class="form-group col-md-12">
            <label for="">{{$f->field_label}}</label>
            <input type="password" name="{{ $f->field_name }}" class="form-control" value="">
        </div>
        @endif
    </div>
    @endforeach
    
    <div class="ml-4">
    <button class="btn btn-primary" type="submit" >Save</button>
    </div>
</div>
        </div>

    </div>
</div>

</form>
</section>
@php $count = count(\Auth::user()->unreadNotifications); @endphp
</div>
<script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery-ui.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/jquery/bootstrap-datepicker.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.timepicker.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/popper.js/umd/popper.min.js') ?>">
    </script>
    <script type="text/javascript" src="<?php echo asset('vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/bootstrap-toggle/js/bootstrap-toggle.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/bootstrap/js/bootstrap-select.min.js') ?>"></script>

    <script type="text/javascript" src="<?php echo asset('js/grasp_mobile_progress_circle-1.0.0.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/jquery.cookie/jquery.cookie.js') ?>">
    </script>
    <script type="text/javascript" src="<?php echo asset('vendor/chart.js/Chart.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('js/charts-custom.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/jquery-validation/jquery.validate.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')?>"></script>
      <script type="text/javascript" src="<?php echo asset('js/front_rtl.js') ?>"></script>
      <script type="text/javascript" src="<?php echo asset('js/front.js') ?>"></script>

    <script type="text/javascript" src="<?php echo asset('vendor/daterange/js/moment.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/daterange/js/knockout-3.4.2.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/daterange/js/daterangepicker.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/tinymce/js/tinymce/tinymce.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('js/dropzone.js') ?>"></script>

    <!-- table sorter js-->
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/pdfmake.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/vfs_fonts.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/jquery.dataTables.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/dataTables.bootstrap4.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/dataTables.buttons.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/buttons.bootstrap4.min.js') ?>">"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/buttons.colVis.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/buttons.html5.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/buttons.print.min.js') ?>"></script>

    <script type="text/javascript" src="<?php echo asset('vendor/datatable/sum().js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('vendor/datatable/dataTables.checkboxes.min.js') ?>"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript">
        var theme = <?php echo json_encode($theme); ?>;
        if(theme == 'dark') {
            $('body').addClass('dark-mode light-mode');
            $('#switch-theme i').addClass('dripicons-brightness-low');
        }
        else {
            $('body').removeClass('dark-mode light-mode');
            $('#switch-theme i').addClass('dripicons-brightness-max');
        }
        $('#switch-theme').click(function() {
            if(theme == 'light') {
                theme = 'dark';
                var url = <?php echo json_encode(route('switchTheme', 'dark')); ?>;
                $('body').addClass('dark-mode light-mode');
                $('#switch-theme i').addClass('dripicons-brightness-low');
            }
            else {
                theme = 'light';
                var url = <?php echo json_encode(route('switchTheme', 'light')); ?>;
                $('body').removeClass('dark-mode light-mode');
                $('#switch-theme i').addClass('dripicons-brightness-max');
            }

            $.get(url, function(data) {
                console.log('theme changed to '+theme);
            });
        });

        var alert_product = <?php echo json_encode($alert_product) ?>;

      if ($(window).outerWidth() > 1199) {
          $('nav.side-navbar').removeClass('shrink');
      }
      function myFunction() {
          setTimeout(showPage, 150);
      }
      function showPage() {
        document.getElementById("loader").style.display = "none";
        document.getElementById("content").style.display = "block";
      }

      $("div.alert").delay(3000).slideUp(750);

      function confirmDelete() {
          if (confirm("Are you sure want to delete?")) {
              return true;
          }
          return false;
      }

      $("li#notification-icon").on("click", function (argument) {
          $.get('notifications/mark-as-read', function(data) {
              $("span.notification-number").text(alert_product);
          });
      });

      $("a#add-expense").click(function(e){
        e.preventDefault();
        $('#expense-modal').modal();
      });

      $("a#send-notification").click(function(e){
        e.preventDefault();
        $('#notification-modal').modal();
      });

      $("a#add-account").click(function(e){
        e.preventDefault();
        $('#account-modal').modal();
      });

      $("a#account-statement").click(function(e){
        e.preventDefault();
        $('#account-statement-modal').modal();
      });

      $("a#profitLoss-link").click(function(e){
        e.preventDefault();
        $("#profitLoss-report-form").submit();
      });

      $("a#report-link").click(function(e){
        e.preventDefault();
        $("#product-report-form").submit();
      });

      $("a#purchase-report-link").click(function(e){
        e.preventDefault();
        $("#purchase-report-form").submit();
      });

      $("a#sale-report-link").click(function(e){
        e.preventDefault();
        $("#sale-report-form").submit();
      });

      $("a#payment-report-link").click(function(e){
        e.preventDefault();
        $("#payment-report-form").submit();
      });

      $("a#warehouse-report-link").click(function(e){
        e.preventDefault();
        $('#warehouse-modal').modal();
      });

      $("a#user-report-link").click(function(e){
        e.preventDefault();
        $('#user-modal').modal();
      });

      $("a#customer-report-link").click(function(e){
        e.preventDefault();
        $('#customer-modal').modal();
      });

      $("a#supplier-report-link").click(function(e){
        e.preventDefault();
        $('#supplier-modal').modal();
      });

      $("a#due-report-link").click(function(e){
        e.preventDefault();
        $("#due-report-form").submit();
      });

      $(".daterangepicker-field").daterangepicker({
          callback: function(startDate, endDate, period){
            var start_date = startDate.format('YYYY-MM-DD');
            var end_date = endDate.format('YYYY-MM-DD');
            var title = start_date + ' To ' + end_date;
            $(this).val(title);
            $('#account-statement-modal input[name="start_date"]').val(start_date);
            $('#account-statement-modal input[name="end_date"]').val(end_date);
          }
      });

      $('.date').datepicker({
         format: "dd-mm-yyyy",
         autoclose: true,
         todayHighlight: true
       });

      $('.selectpicker').selectpicker({
          style: 'btn-link',
      });
    </script>
    <script src="//js.pusher.com/3.1/pusher.min.js"></script>
	
	<script type="text/javascript">
      var notification_count   = $('span.notification-number').text();
      var notifications = $('#notify');
 
    var pusher = new Pusher('8f84bff0e7643b0e9609', {
        encrypted: true
      });

      // Subscribe to the channel we specified in our Laravel Event
      var channel = pusher.subscribe('status-liked');

      channel.bind('App\\Events\\FormApprove', function(data) {
        console.log(data)
        notificationsCount = notification_count;
        var user_id = '<?php echo $userr_id; ?>';
        var count = '<?php echo(isset($count) ? $count : 0); ?>';

        var existingNotifications = notifications.html();
        var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
        var newNotificationHtml = "";
        
      
        if(user_id == data.data.receiver){
             newNotificationHtml = `
                <li class="notifications" style="background-color: lightgrey">
                <a href="/`+ data.data.url +`/`+ data.data.id +`" class="btn btn-link">`+data.data.message+`</a>
                </li>
            `;
            notifications.html(newNotificationHtml + existingNotifications);
            notificationsCount =  parseInt(count) + parseInt(1);
            $('span.notification-number').html(notificationsCount);
        }
       
        
        
        // console.log(newNotificationHtml)
        

        

      });


    </script>
</body>

</html>

