<?php $general_setting = DB::table('general_settings')->find(1); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo asset('vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css">

    <!-- Google fonts - Roboto -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Nunito:400,500,700" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link href="https://fonts.googleapis.com/css?family=Nunito:400,500,700" rel="stylesheet"></noscript>
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?php echo asset('css/auth.css') ?>" id="theme-stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo asset('vendor/jquery/jquery.min.js') ?>"></script>
  </head>
  <body>
    <div class="page login-page">
      <div class="container">
        <div class="form-outer text-center d-flex align-items-center">
          <div class="form-inner">
            @if(Session::has('message'))
            <p class="bg-success text-white p-2 rounded">{{Session::get('message')}}</p>
            @endif
            <div class="logo"><img src="{{url('logo', $general_setting->site_logo)}}" width="110"></div>
            <form method="POST" action="{{ route('do-register') }}">
                @csrf
              <div class="form-group-material">
                <input id="register-username" type="text" name="name" required class="input-material">
                <label for="register-username" class="label-material">{{trans('file.UserName')}} *</label>
                @if ($errors->has('name'))
                    <p>
                        <strong>{{ $errors->first('name') }}</strong>
                    </p>
                @endif
              </div>
              <div class="form-group-material">
                <input id="register-username" type="text" name="shop_name" required class="input-material">
                <label for="register-username" class="label-material">{{trans('file.ShopName')}} *</label>
                @if ($errors->has('shop_name'))
                    <p>
                        <strong>{{ $errors->first('shop_name') }}</strong>
                    </p>
                @endif
              </div>
              <div class="form-group-material">
                <input id="register-email" type="email" name="email" required class="input-material">
                <label for="register-email" class="label-material">{{trans('file.Email')}} *</label>
                @if ($errors->has('email'))
                    <p>
                        <strong>{{ $errors->first('email') }}</strong>
                    </p>
                @endif
              </div>
              
              <div class="form-group-material">
              <!-- <label for="register-username" class="label-material">{{trans('file.Role')}} *</label> -->
              <div class="input-group">
                                            <select required class="form-control selectpicker" name="role">
                                              <option value="10">Retailer</option>
                                                
                                                
                                            </select>
                                        </div>
              </div>
              <!-- <div class="form-group-material">
                <input id="password" type="password" class="input-material" name="password" required>
                <label for="passowrd" class="label-material">{{trans('file.Password')}} *</label>
                @if ($errors->has('password'))
                    <p>
                        <strong>{{ $errors->first('password') }}</strong>
                    </p>
                @endif
              </div> -->
              <!-- <div class="form-group-material">
                <input id="password-confirm" type="password" name="password_confirmation" required class="input-material">
                <label for="password-confirm" class="label-material">{{trans('file.Confirm Password')}} *</label>
              </div> -->
              <input id="register" type="submit" value="Register" class="btn btn-primary">
            </form><p>{{trans('file.Already have an account')}}? </p><a href="{{url('login')}}" class="signup">{{trans('file.LogIn')}}</a>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      // ------------------------------------------------------- //
    // Material Inputs
    // ------------------------------------------------------ //

        var materialInputs = $('input.input-material');

        // activate labels for prefilled values
        materialInputs.filter(function() { return $(this).val() !== ""; }).siblings('.label-material').addClass('active');

        // move label on focus
        materialInputs.on('focus', function () {
            $(this).siblings('.label-material').addClass('active');
        });

        // remove/keep label on blur
        materialInputs.on('blur', function () {
            $(this).siblings('.label-material').removeClass('active');

            if ($(this).val() !== '') {
                $(this).siblings('.label-material').addClass('active');
            } else {
                $(this).siblings('.label-material').removeClass('active');
            }
        });


        $("#biller-id").hide();
        $("#warehouse-id").hide();
        $("#customer-section").hide();

        $("#role-id").on("change", function () {
            if($(this).val() == '5') {
              $("#customer-section").show(300);
              $(".customer-field").prop('required', true);
              $("#biller-id").hide(300);
              $("#warehouse-id").hide(300);
              $("select[name='biller_id']").prop('required', false);
              $("select[name='warehouse_id']").prop('required', false);
            }
            else if($(this).val() > 2) {
              $("#customer-section").hide(300);
              $("#biller-id").show(300);
              $("#warehouse-id").show(300);
              $("select[name='biller_id']").prop('required', true);
              $("select[name='warehouse_id']").prop('required', true);
              $(".customer-field").prop('required', false);
            }
            else {
              $("#biller-id").hide(300);
              $("#warehouse-id").hide(300);
              $("#customer-section").hide(300);
              $("select[name='biller_id']").prop('required', false);
              $("select[name='warehouse_id']").prop('required', false);
              $(".customer-field").prop('required', false);
            }
        });
    </script>
  </body>
</html>
