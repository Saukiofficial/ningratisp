<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>NingratNet-Penyedia Layanan Internet</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.jpg') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset ('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset ('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset ('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset ('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset ('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset ('assets/css/main.css') }}" rel="stylesheet">

  <!-- =======================================================
  * Template Name: iLanding
  * Template URL: https://bootstrapmade.com/ilanding-bootstrap-landing-page-template/
  * Updated: Nov 12 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

      <a href="{{ route('index') }}" class="logo d-flex align-items-center me-auto me-xl-0">

         <img src="{{ asset('assets/img/logo.png') }}" alt="">
        <h1 class="sitename">NingratNet</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#services">Services</a></li>
          <li><a href="#produk">produk</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="#contact">Hubungi kami</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
              <div class="company-badge mb-4">
                <i class="bi bi-wifi me-2"></i>
                Buat Rumah kalian jadi lebih nyaman dengan kami
              </div>

              <h1 class="mb-4">
                <span class="accent-text">NINGRAT NETWORK</span>
              </h1>

              <p class="mb-4 mb-md-5">
                Paket Internet yang terjangkau dan cepat
              </p>

              <div class="hero-buttons">
                <a href="#contact" class="btn btn-primary me-0 me-sm-2 mx-1">Daftar Sekarang</a>
                <a href="#produk" class="btn btn-primary me-0 me-sm-2 mx-1">
                  Lihat Paket Internet
                </a>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
              <img src="{{ asset ('assets/img/illustration-1.webp') }}" alt="Hero Image" class="img-fluid">
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4 align-items-center justify-content-between">

          <div class="col-xl-5" data-aos="fade-up" data-aos-delay="200">
            <span class="about-meta">Tentang NingratNet</span>
            <h2 class="about-title">Menghadirkan Koneksi Tercepat dan terpercaya untuk anda</h2>
            <p class="about-description">Sebagai perusahan swasta yang bergerak di bidang layanan Internet  dengan biaya murah dan terjangkau NingratNet hadir untuk memenuhi kebutuhan masyarakat yang selama ini sulit mendapatkan akses internet. Semoga kehadiran layanan jaringan internet murah ini bisa mempermudah banyak orang untuk mendapatkan akses internet dan menjawab semua kebutuhan masyarakat di era modern..</p>

            <div class="row feature-list-wrapper">
              <div class="col-md-6">
                <ul class="feature-list">
                  <li><i class="bi bi-check-circle-fill"></i> Unlimited </li>
                  <li><i class="bi bi-check-circle-fill"></i> Harga Terjangkau</li>
                  <li><i class="bi bi-check-circle-fill"></i> Cocok Untuk Keluarga</li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="feature-list">
                  <li><i class="bi bi-check-circle-fill"></i> Game dan Streaming</li>
                  <li><i class="bi bi-check-circle-fill"></i> Bisnis dan Pendidikan</li>
                  <li><i class="bi bi-check-circle-fill"></i> Warkop dan Cafe</li>
                </ul>
              </div>
            </div>

            <div class="info-wrapper">
              <div class="row gy-4">

                <div class="col-lg-7">
                  <div class="contact-info d-flex align-items-center gap-2">
                    <i class="bi bi-telephone-fill"></i>
                    <div>
                      <p class="contact-label">Call us anytime</p>
                      <p class="contact-number">081232916758</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6" data-aos="fade-up" data-aos-delay="300">
            <div class="image-wrapper">
              <div class="images position-relative" data-aos="zoom-out" data-aos-delay="400">
                <img src="assets/img/about-5.webp" alt="Business Meeting" class="img-fluid main-image rounded-4">
                <img src="assets/img/about-2.webp" alt="Team Discussion" class="img-fluid small-image rounded-4">
              </div>
              <div class="experience-badge floating">
                <h3>15+ <span>Years</span></h3>
                <p>Of experience in business service</p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Clients Section -->
    <section id="clients" class="clients section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 2,
                  "spaceBetween": 40
                },
                "480": {
                  "slidesPerView": 3,
                  "spaceBetween": 60
                },
                "640": {
                  "slidesPerView": 4,
                  "spaceBetween": 80
                },
                "992": {
                  "slidesPerView": 6,
                  "spaceBetween": 120
                }
              }
            }
          </script>
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><img src="{{ asset ('assets/img/clients/client-1.png') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset ('assets/img/clients/client-2.png') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset ('assets/img/clients/client-3.png') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset ('assets/img/clients/client-4.png') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset ('assets/img/clients/client-5.png') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset ('assets/img/clients/client-6.png') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset ('assets/img/clients/client-7.png') }}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset ('assets/img/clients/client-8.png') }}" class="img-fluid" alt=""></div>
          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Clients Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
              <p>Clients</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
              <p>Projects</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="1" class="purecounter"></span>
              <p>Hours Of Support</p>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item text-center w-100 h-100">
              <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="1" class="purecounter"></span>
              <p>Workers</p>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- Services Section -->
    <section id="services" class="services section light-background">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
          <h2>Our Services</h2>
          <p>Kami menyediakan layanan internet terbaik dengan kecepatan tinggi dan uptime yang andal untuk kebutuhan Anda.</p>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

          <div class="row g-4">

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
              <div class="service-card d-flex">
                <div class="icon flex-shrink-0">
                  <i class="bi bi-activity"></i>
                </div>
                <div>
                  <h3>Kecepatan Internet Maksimal</h3>
                  <p>Kami memastikan Anda mendapatkan pengalaman internet tercepat untuk kebutuhan browsing, streaming, dan gaming Anda.</p>
                  <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div><!-- End Service Card -->

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
              <div class="service-card d-flex">
                <div class="icon flex-shrink-0">
                  <i class="bi bi-diagram-3"></i>
                </div>
                <div>
                  <h3>Uptime Tinggi</h3>
                  <p>Layanan kami dirancang untuk menjaga koneksi Anda tetap stabil dengan uptime hingga 99,9%, sehingga aktivitas online Anda tidak terganggu.</p>
                  <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div><!-- End Service Card -->

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
              <div class="service-card d-flex">
                <div class="icon flex-shrink-0">
                  <i class="bi bi-easel"></i>
                </div>
                <div>
                  <h3>Support Pelanggan 24/7</h3>
                  <p>Tim dukungan kami selalu siap membantu Anda kapan pun Anda membutuhkannya, memastikan setiap masalah dapat segera terselesaikan.</p>
                  <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div><!-- End Service Card -->

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
              <div class="service-card d-flex">
                <div class="icon flex-shrink-0">
                  <i class="bi bi-clipboard-data"></i>
                </div>
                <div>
                  <h3>Fleksibilitas Paket</h3>
                  <p>Pilih dari berbagai paket internet yang dirancang untuk memenuhi kebutuhan Anda, baik untuk penggunaan pribadi maupun bisnis.</p>
                  <a href="service-details.html" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                </div>
              </div>
            </div><!-- End Service Card -->

          </div>

        </div>

      </section>

    <!-- /Services Section -->

    <!-- Pricing Section -->
    <section id="produk" class="pricing section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Pilih Bandwidth yang Tepat untuk Kebutuhan Anda</h2> <p>Pastikan koneksi Anda stabil dan cepat untuk mendukung aktivitas online tanpa hambatan.</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4 justify-content-center">

          <!-- Basic Plan -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="pricing-card popular">
              <center><h3>Paket Miskin</h3>
              <h4>10Mbps</h4> </center>
              <div class="price">
                <center><span class="currency">Rp.</span>
                <span class="amount">150rb </span>
                <span class="period">/ Bulan</span></center>
              </div>
              <p class="description">ye mun tak andik pesse ngalak se mode bei jek langlarang, mun la sogi bhuruh melle se larangan, dek remmah tretan? cocok ?.</p>

              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Uploud & download stabil
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  cocok untuk keluarga kismin
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  ideal untuk 3 perangkat, kalau lebih mending sadar diri
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    kalau lemot jgn nyalahin , kan la taoh jek mode paketnah
                </li>
              </ul>

              <a href="https://wa.me/6285895039471?text=Halo%20saya%20ingin%20berlangganan%20paket%20ini" class="btn btn-light">
                Langganan Sekarang
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Standard Plan -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="pricing-card popular">
              <center><h3>Paket Hampir Miskin</h3>
              <h4>30Mbps</h4> </center>
              <div class="price">
                <center><span class="currency">Rp.</span>
                <span class="amount">250rb </span>
                <span class="period">/ Bulan</span></center>
              </div>
              <p class="description">ye mun tak andik pesse ngalak se mode bei jek langlarang, mun la sogi bhuruh melle se larangan, dek remmah tretan? cocok ?.</p>

              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Uploud & download stabil
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  cocok untuk keluarga kismin
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  ideal untuk 7 perangkat, kalau lebih mending sadar diri
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    kalau lemot jgn nyalahin , kan la taoh jek mode paketnah
                </li>
              </ul>

              <a href="https://wa.me/6285895039471?text=Halo%20saya%20ingin%20berlangganan%20paket%20ini" class="btn btn-light">
                Langganan Sekarang
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Premium Plan -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="pricing-card popular">
              <center><h3>Paket Hampir Kaya</h3>
              <h4>50Mbps</h4> </center>
              <div class="price">
                <center><span class="currency">Rp.</span>
                <span class="amount">300rb </span>
                <span class="period">/ Bulan</span></center>
              </div>
              <p class="description">Nah parajin nyareh pesse , male setoran tak cet macetan , kan mun ngak jriyeh padeh nyaman. beremmah bosku aman?</p>

              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Uploud & download stabil
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  cocok untuk keluarga hampir kaya
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  ideal untuk 10 perangkat, kalau lebih mending sadar diri
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    kalau lemot gpp chat admin , keng jek gut seggut
                </li>
              </ul>

              <a href="https://wa.me/6285895039471?text=Halo%20saya%20ingin%20berlangganan%20paket%20ini" class="btn btn-light">
                Langganan Sekarang
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
          <!--  produk andalan  -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="pricing-card popular">
              <center><h3>Paket Kaya </h3>
              <h4>70Mbps</h4> </center>
              <div class="price">
                <center><span class="currency">Rp.</span>
                <span class="amount">350rb </span>
                <span class="period">/ Bulan</span></center>
              </div>
              <p class="description">Mun reng sogi jek bong sombong , pesse tak egibeh mateh , tak rokaroan onggu.</p>

              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Uploud & download stabil
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  cocok untuk keluarga kaya
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  ideal untuk 15 perangkat, santak paggun
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    kalau lemot chat admin , mun tk ebeleh berarti gik ngepush ranked
                </li>
              </ul>

              <a href="https://wa.me/6285895039471?text=Halo%20saya%20ingin%20berlangganan%20paket%20ini" class="btn btn-light">
                Langganan Sekarang
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
          <!--  produk orang kaya  -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="pricing-card popular">
              <center><h3>Paket Kaya raya</h3>
              <h4>80Mbps</h4> </center>
              <div class="price">
                <center><span class="currency">Rp.</span>
                <span class="amount">400rb </span>
                <span class="period">/ Bulan</span></center>
              </div>
              <p class="description">nah selamat datang juragan , kami ada untuk melayanimu sepenuhnya ,.</p>

              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Uploud & download stabil
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  cocok untuk keluarga kaya
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  ideal untuk 20 orang , meledak bumm
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    kalau lemot jgn nyalahin , kan la taoh jek mode paketnah
                </li>
              </ul>

              <a href="https://wa.me/6285895039471?text=Halo%20saya%20ingin%20berlangganan%20paket%20ini" class="btn btn-light">
                Langganan Sekarang
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
          <!--  produk orang kaya sultan  -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="pricing-card popular">
              <center><h3>Paket Sultan</h3>
              <h4>100Mbps</h4> </center>
              <div class="price">
                <center><span class="currency">Rp.</span>
                <span class="amount">500rb </span>
                <span class="period">/ Bulan</span></center>
              </div>
              <p class="description">posetep ariah selakoh korupsi , mending ngakoh, dari pada epeghek maso prabowo!!.</p>

              <h4>Featured Included:</h4>
              <ul class="features-list">
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  Uploud & download stabil
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  cocok untuk keluarga Sultan
                </li>
                <li>
                  <i class="bi bi-check-circle-fill"></i>
                  ideal untuk 25 perangkat, cuan cuan
                </li>
                <li>
                    <i class="bi bi-check-circle-fill"></i>
                    kalau lemot keng pas tapok adminah... sala larang gik lemmot
                </li>
              </ul>

              <a href="https://wa.me/6285895039471?text=Halo%20saya%20ingin%20berlangganan%20paket%20ini" class="btn btn-light">
                Langganan Sekarang
                <i class="bi bi-arrow-right"></i>
              </a>
            </div>
          </div>
    </section><!-- /Pricing Section -->

    <!-- Faq Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Hubungi Kami</h2> <p>Jangan ragu untuk menghubungi kami untuk informasi lebih lanjut atau bantuan terkait layanan kami.</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4 g-lg-5">
          <div class="col-lg-5">
            <div class="info-box" data-aos="fade-up" data-aos-delay="200">
                <h3>Informasi Kontak</h3> <p>Hubungi kami dengan mudah untuk mendapatkan layanan terbaik dan informasi yang Anda butuhkan. Kami siap membantu Anda kapan saja.</p>

              <div class="info-item" data-aos="fade-up" data-aos-delay="300">
                <div class="icon-box">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <div class="content">
                  <h4>Our Location</h4>
                  <p>Ds Poreh, Kec. Lenteng</p>
                  <p>Kabupaten Sumenep, Jawa Timur 69461</p>
                  <p>Indonesia</p>
                </div>
              </div>

              <div class="info-item" data-aos="fade-up" data-aos-delay="400">
                <div class="icon-box">
                  <i class="bi bi-telephone"></i>
                </div>
                <div class="content">
                  <h4>No. Telepon / WA</h4>
                  <p><a href="https://wa.me/6285895039471">0858-9503-9471</a></p>
                  <p><a href="https://wa.me/6282332362701">0823-3236-2701</a></p>
                </div>
              </div>

              <div class="info-item" data-aos="fade-up" data-aos-delay="500">
                <div class="icon-box">
                  <i class="bi bi-envelope"></i>
                </div>
                <div class="content">
                  <h4>Email Address</h4>
                  <p>ningratisp@gmail.com</p>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-7">
            <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
              <h3>Get In Touch</h3>
              <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante ipsum primis.</p>

              <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                <div class="row gy-4">

                  <div class="col-md-6">
                    <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                  </div>

                  <div class="col-md-6 ">
                    <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                  </div>

                  <div class="col-12">
                    <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                  </div>

                  <div class="col-12">
                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                  </div>

                  <div class="col-12 text-center">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Your message has been sent. Thank you!</div>

                    <button type="submit" class="btn">Send Message</button>
                  </div>

                </div>
              </form>

            </div>
          </div>

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <img src="assets/img/logo.png" alt="" class="me-3">
            <span class="sitename">NingratNet</span>
          </a>
          <div class="footer-contact pt-3">
            <a>Sebagai provider internet terbaik dengan koneksi Unlimited kini hadir dengan berbagai paket internet yang sesuai dengan kebutuhan Anda.</a>
            <br><b>Ikuti Kami</b>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 ">
          <h4><b> Informasi & Support </b></h4>
          <h3><b> PT . NingratNet </b></h3>
          <p><b>Kantor</b> : Poreh daya, RT 6/RW 3, Desa Poreh, Kec. Lenteng , Kab. Sumenep, Jawa Timur 69461</p>

        </div>

        <div class="col-lg-4 col-md-6 footer-links">
          <h4><b>Produk & Layanan lainnya </b></h4>
          <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Service</a></li>
            <li><a href="#">Product</a></li>
            <li><a href="#">Contact</a></li>
          </ul>
        </div>

        {{--  <div class="col-lg-2 col-md-3 footer-links">
          <h4>Hic solutasetp</h4>
          <ul>
            <li><a href="#">Molestiae accusamus iure</a></li>
            <li><a href="#">Excepturi dignissimos</a></li>
            <li><a href="#">Suscipit distinctio</a></li>
            <li><a href="#">Dilecta</a></li>
            <li><a href="#">Sit quas consectetur</a></li>
          </ul>
        </div>  --}}

        {{--  <div class="col-lg-2 col-md-3 footer-links">
          <h4>Nobis illum</h4>
          <ul>
            <li><a href="#">Ipsam</a></li>
            <li><a href="#">Laudantium dolorum</a></li>
            <li><a href="#">Dinera</a></li>
            <li><a href="#">Trodelas</a></li>
            <li><a href="#">Flexo</a></li>
          </ul>
        </div>  --}}

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">iLanding</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset ('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset ('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset ('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset ('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset ('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset ('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>

  <!-- Main JS File -->
  <script src="{{ ('assets/js/main.js') }}"></script>

</body>

</html>
