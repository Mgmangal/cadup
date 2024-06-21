<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="" />
<link href="{{asset('front/img/favicon.ico')}}" rel="icon">
<link href="{{asset('front/img/android-chrome-512x512.png')}}" rel="shortcut icon">
<title>ERP-CADUP2.0</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{asset('front/css/bootstrap.min.css')}}" />
<link href="{{asset('front/css/animate.min.css')}}" rel="stylesheet">
<!-- Icomoon Icons CSS -->
<link rel="stylesheet" href="{{asset('front/fonts/icomoon/icomoon.css')}}" />

<!-- Master CSS -->
<link rel="stylesheet" href="{{asset('front/css/main.css')}}" />
</head>

<body>
<div class="login-bg"> <img src="{{asset('front/img/login-bg.jpg')}}" class=""></div>
<section class="height-100vh d-flex align-items-center login page-section-ptb">
  <div class="container">
    <div class="login-fancy-bg-main">
      <div class="row no-gutters login-screen vertical-align" style="overflow: hidden">
        <div class="col-lg-8 col-md-12">
          <div class="login-fancy"> <a href="{{route('welcome')}}" class="wow fadeIn" data-wow-delay="0.1s"><img src="{{asset('front/img/logo.jpg')}}" alt="logo"></a>
            <h2 class="wow fadeIn" data-wow-delay="0.1s"><span>ERP Solution for Civil Aviation Department, Government of Uttar Pradesh </span></h2>
            <ul class="list-unstyled pos-bot">
              <li class="list-inline-item wow fadeIn" data-wow-delay="0.1s" ><a href="javascript:void(0);">
                <aside class="wow zoomIn" data-wow-delay="0.1s"> <img src="{{asset('front/img/login-icon/01.jpg')}}" alt=""> </aside>
            <span class="wow fadeIn" data-wow-delay="0.1s">Flight Operations <br>
                Module </span> </a> </li>
              <li class="list-inline-item wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
                 <aside class="wow zoomIn" data-wow-delay="0.2s"> <img src="{{asset('front/img/login-icon/02.jpg')}}" alt=""> </aside>
              <span class="wow fadeIn" data-wow-delay="0.2s">Engineering and <br>
                Aircraft Maintenance <br>
                Module </span> </a></li>
              <li class="list-inline-item wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
                <aside class="wow zoomIn" data-wow-delay="0.3s"> <img src="{{asset('front/img/login-icon/03.jpg')}}" alt=""> </aside>
              <span class="wow fadeIn" data-wow-delay="0.3s">Inventory/Stores<br>
                Module </span> </a></li>
              <li class="list-inline-item wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
               <aside class="wow zoomIn" data-wow-delay="0.4s"> <img src="{{asset('front/img/login-icon/04.jpg')}}" alt=""> </aside>
               <span class="wow fadeIn" data-wow-delay="0.4s">Procurement and <br>
                Orders Module </span> </a></li>
              <li class="list-inline-item wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
                 <aside class="wow zoomIn" data-wow-delay="0.5s"> <img src="{{asset('front/img/login-icon/05.jpg')}}" alt=""> </aside>
                <span class="wow fadeIn" data-wow-delay="0.5s">Accounts Module </span> </a></li>
              <li class="list-inline-item wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
              <aside class="wow zoomIn" data-wow-delay="0.6s"> <img src="{{asset('front/img/login-icon/06.jpg')}}" alt=""> </aside>
                <span class="wow fadeIn" data-wow-delay="0.6s">Human Resources<br>
                Module </span> </a></li>
            </ul>
            <div class="clearfix"></div>
			  <div class="m-none">
            <div class="row">
              <div class="col-lg-3 col-md-12"> </div>
              <div class="col-lg-9 col-md-12">
                <p class="login-text wow fadeIn" data-wow-delay="0.1s">CADUP provides air transport to VVIP/VIP and other dignitaries in planned manner. Considering the safety, security of VVIP/VIPs and abiding the Civil Aviation Requirements (CAR) Aircraft maintenance, periodic schedules, quality control, sufficient Inventory is very essential. Upgradation of ERP will be required to provide Simple, User friendly, Automated and Integrated solution to CADUP. </p>
              </div>
            </div>
				  </div>
			  
            <div class="left-pic"> <img src="{{asset('front/img/yogi-ji-right.png')}}" alt="" class="img-fluid wow fadeInLeft" data-wow-delay="0.1s"> </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-12 bg-white login-right-bg">
          <div class="login-fancy pb-40 clearfix wow fadeIn" data-wow-delay="0.1s">
            <div class="formhd"><img src="{{asset('front/img/login-icon/01-form.jpg')}}" class="img-fluid">
              <h3>Flight Operations</h3>
            </div>
            <div class="formhd_subhd">
              <h4>Login to your account</h4>
              <p>Enter your username & password to login</p>
            </div>
            <form method="POST" action="{{ route('login') }}">
                            @csrf
            <div class="section-field mb-3"> 
              <!-- <label class="mb-10" for="name">User name* </label> -->
              <input id="name" class="web form-control" type="text" placeholder="User name" name="email">
            </div>
            <div class="section-field mb-3"> 
              <!-- <label class="mb-10" for="Password">Password* </label> -->
              <input id="Password" class="Password form-control" type="password" placeholder="Password"	name="password">
            </div>
            <div class="section-field">
              <div class="remember-checkbox mb-4">
                <input type="checkbox" class="form-control" name="remember" id="two" value="1">
                <label for="two"> Remember me</label>
                <!-- <a href="forgot-pwd.html" class="float-right">Forgot Password?</a> --> 
              </div>
            </div>
            <button type="submit" class="button w-100"> <span>Log in</span> </button>
            </form>
            <!--<div class="formhd_subhd_footer">-->
            <!--  <p>Don’t have a account? <a href="">Register Now</a></p>-->
            <!--</div>-->
          </div>
        </div>
      </div>
      <div class="main-footer-text-left pt-3 pl-2"> © Copyright 2024-25 | ERP - CADUP 2.0 - Civil Aviation Department, GoUP </div>
    </div>
  </div>
</section>
<script src="{{asset('front/js/jquery-3.4.1.min.js')}}"></script> 
<script src="{{asset('front/js/wow.min.js')}}"></script> 
<script>
	   // Initiate the wowjs
    new WOW().init();

	</script>
</body>
</html>