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
<div class="login-bg">	<img src="{{asset('front/img/login-bg.jpg')}}" class=""></div>
<section class="height-100vh d-flex align-items-center login page-section-ptb">
  <div class="container">
    <div class="login-fancy-bg-main">
      <div class="row no-gutters login-screen vertical-align">
        <div class="col-lg-9 col-md-12">
          <div class="login-fancy-main"> <a href="{{route('welcome')}}" class="wow fadeIn" data-wow-delay="0.1s"><img src="{{asset('front/img/Erp-Cadup-logo.png')}}" alt="logo"></a>
            <h2 class="wow fadeIn" data-wow-delay="0.1s"><span>ERP Solution for Civil Aviation Department, Government of Uttar Pradesh </span></h2>
            <p class="wow fadeIn" data-wow-delay="0.1s">CADUP provides air transport to VVIP/VIP and other dignitaries in planned manner. Considering the safety, security of VVIP/VIPs and abiding the Civil Aviation Requirements (CAR) Aircraft maintenance, periodic schedules, quality control, sufficient Inventory is very essential. Upgradation of ERP will be required to provide Simple, User friendly, Automated and Integrated solution to CADUP. </p>
            <ul class="list-unstyled-main">
              <li class="list-inline-item-mailn wow fadeIn" data-wow-delay="0.1s"> <a href="javascript:void(0);">
                <aside class="wow zoomIn" data-wow-delay="0.1s"> <img src="{{asset('front/img/login-icon/Flight-Operations.png')}}" alt=""> </aside>
                <span class="wow fadeIn" data-wow-delay="0.1s">Flight Operations <br>
                Module </span> </a> </li>
              <li class="list-inline-item-main wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
             <aside class="wow zoomIn" data-wow-delay="0.2s"><img src="{{asset('front/img/login-icon/engineering-aircraft-maintainance.png')}}" alt=""> </aside>
                <span class="wow fadeIn" data-wow-delay="0.2s">Engineering and <br>
                Aircraft Maintenance <br>
                Module </span> </a></li>
              <li class="list-inline-item-main wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
                <aside class="wow zoomIn" data-wow-delay="0.3s"> <img src="{{asset('front/img/login-icon/Inventory-stores-module.png')}}" alt=""> </aside>
               <span class="wow fadeIn" data-wow-delay="0.3s">Inventory/Stores<br>
                Module </span> </a></li>
              <li class="list-inline-item-main wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
              <aside class="wow zoomIn" data-wow-delay="0.4s"> <img src="{{asset('front/img/login-icon/procurement-order-module.png')}}" alt=""> </aside>
               <span class="wow fadeIn" data-wow-delay="0.4s">Procurement and <br>
                Orders Module </span> </a></li>
              <li class="list-inline-item-main wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
            <aside class="wow zoomIn" data-wow-delay="0.5s"> <img src="{{asset('front/img/login-icon/accounts-module.png')}}" alt=""> </aside>
                <span class="wow fadeIn" data-wow-delay="0.5s">Accounts Module </span> </a></li>
              <li class="list-inline-item-main wow fadeIn" data-wow-delay="0.1s"><a href="javascript:void(0);">
              <aside class="wow zoomIn" data-wow-delay="0.6s"> <img src="{{asset('front/img/login-icon/human-resources-module.png')}}" alt=""> </aside>
                <span class="wow fadeIn" data-wow-delay="0.6s">Human Resources<br>
                Module </span> </a></li>
            </ul>
          </div>
        </div>
        <div class="col-lg-3 col-md-12 m-none">
          <div class="right-pic"> <img src="{{asset('front/img/yogi-ji-left.png')}}" alt="" class="img-fluid wow fadeInRight" data-wow-delay="0.1s"> </div>
        </div>
         @if (Route::has('login'))
        <a href="{{ route('login') }}" class="arrow-button wow pulse" data-wow-delay="0.2s"><span class="label">Enter</span><span class="arrow"></span></a> </div>
         @endif
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






