<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

      @font-face {
            font-family: 'Inter';
            src: url('/fonts/Inter.ttf') format('truetype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        .btn-login:hover {
            background: #1f7ae0; /* azul un poco más oscuro */
            color: #fff;
        }

        .menu-icon {
            width: 16px;
            height: 16px;
            color: #66aef7;
            flex-shrink: 0;
        }

        body {
            background: #f5f7fb;
           font-family: 'Inter', sans-serif;
        }
        .inventory-card {
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 25px 20px;

            max-width: 260px;
            margin: 0 auto;
            text-align: center;

            background: #fff;
        }
        
        .section-divider {
            border-top: 1px solid #e5e7eb;
            margin-top: 25px;
            padding-top: 20px;
        }

        /* INVENTORY */
        .inventory-title {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            letter-spacing: 1px;
            margin-bottom: 6px;
            margin-top: 6px;
        }

        /* Live Updates */
        .inventory-subtitle {
            font-size: 10px;
            font-weight: 700;
            color: #111827;
        }

        .form-control {
            height: 56px;
            padding-left: 45px;

            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;

            color: #ffffff;
            font-size: 16px;

            outline: none;
            box-shadow: none;

            transition: all 0.2s ease;
        }

       

       .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 40px;
            height: 50px; /* importante para control */
        }

        .input-icon .icon {
            position: absolute;
            left: 12px;
            top: 70%;
            transform: translateY(-50%);
            
            width: 20px;
            height: 20px;
            pointer-events: none;
        }

        .container-login {
            max-width: 400px;
            margin: 60px auto;
            text-align: center;
        }

       .logo-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto; /* opcional para centrar */
        }

        .logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .title {
            color: #258cf4;
            font-weight: 700;
            font-size: 36px;
            white-space: nowrap;
             font-family: 'Inter', sans-serif;
        }

        .subtitle {
            font-size: 14px;
            color: #575e6b;
            margin-bottom: 20px;
        }

        .card-login {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .form-label {
            font-size: 12px;
            color: #666;
            text-align: left;
            display: block;
        }

      .footer-full {
            margin: 25px -25px -25px -25px; /* 👈 rompe el padding */
            padding: 15px;
            border-top: 2px solid #e5e7eb;
            background: #f9fafb;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .alert-custom {
            margin: 0 0 15px 0;
            padding: 12px;
            border-radius: 10px;
            background: #fee2e2;
            color: #b91c1c;
            font-size: 13px;
            text-align: left;
        }

        .header-full {
            margin: -25px -25px 25px -25px; /* 👈 rompe el padding arriba */
            padding: 20px;

            background: #f9fafb;

            /* 🔥 borde inferior más marcado */
            border-bottom: 2px solid #e5e7eb;

            /* 🔥 bordes redondeados arriba */
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        .footer-full a {
            color: #2f6fed;
            font-weight: 500;
            text-decoration: none;
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px rgba(47,111,237,0.2);
        }

        .btn-login {
            background: #258cf4;
            border: none;
            height: 45px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .divider {
            margin: 20px 0;
            border-top: 1px solid #eee;
        }

        .inventory-card {
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 15px;
            font-size: 12px;
            color: #666;
            max-width: 150px;
            margin: 0 auto; /* centra la card */
            text-align: center;
        }

       .footer {
            font-size: 13px;
            color: #6b7280;
            text-align: center;
        }

        .footer a {
            color: #2f6fed;
            font-weight: 500;
        }
    </style>
</head>

<body>

<div class="container-login">

    <!-- ICON -->
    <div class="logo-icon">
         <img src="{{ asset('images/login-img.png') }}" alt="Logo" width="50">
    </div>

    <!-- TITLE -->
    <div class="title">RFID Warehouse Software</div>
    <div class="subtitle">By KrakenLabs</div>

    <!-- CARD -->
    <div class="card-login">
        <div class="header-full">
                <!-- puedes dejarlo vacío o meter algo después -->
            </div>
            @if ($errors->any())
                <div class="alert-custom">
                    {{ $errors->first() }}
                </div>
            @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf

           <div class="mb-3 text-start input-icon">
                <label class="form-label fw-bold">USERNAME / ID</label>
                <input type="text" name="email" class="form-control" placeholder="e.g. mchen_92">
                <img src="images/user.png" class="icon">
            </div>

            <div class="mb-3 text-start input-icon">
                <label class="form-label  fw-bold">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
                <img src="images/password.png" class="icon">
            </div>

            <button class="btn btn-login text-white w-100">
                Login to Dashboard →
            </button>

        </form>

        <div class="divider"></div>

        <!-- INVENTORY CARD -->
        <div class="inventory-card">
            <svg class="menu-icon" viewBox="0 0 522 546.40729" fill="#66aef7">
                        <path
                            d="M 261,541.4073 A 97.416533,97.416533 0 0 1 214.06667,529.15343 L 55.82453,442.2841 A 97.655467,97.655467 0 0 1 5,356.95076 V 189.39023 A 97.621333,97.621333 0 0 1 55.7904,104.0569 L 213.82773,17.153434 a 97.143467,97.143467 0 0 1 94.1056,0 L 466.17547,104.09103 A 97.655467,97.655467 0 0 1 517,189.42436 v 167.5264 A 97.621333,97.621333 0 0 1 466.24373,442.2841 L 308.17227,529.15343 A 97.416533,97.416533 0 0 1 261,541.4073 Z m 0,-498.995196 a 60.0064,60.0064 0 0 0 -28.9792,7.50933 L 73.84693,136.8249 a 60.142933,60.142933 0 0 0 -31.30026,52.56533 v 167.5264 a 60.177067,60.177067 0 0 0 31.4368,52.56533 l 158.24213,86.9376 a 59.767467,59.767467 0 0 0 58.02667,0 l 158.07146,-86.9376 a 60.177067,60.177067 0 0 0 31.36854,-52.56533 V 189.39023 A 60.177067,60.177067 0 0 0 448.25547,136.8249 L 289.8768,49.887304 A 60.0064,60.0064 0 0 0 261,42.412104 Z"
                        />
                        <path
                            d="M 261,294.48676 35.1056,171.26543 A 18.74206,18.74206 0 0 1 53.05973,138.3609 L 261,251.8201 468.94027,138.39503 a 18.7392,18.7392 0 0 1 17.92,32.90453 z"
                        />
                        <path
                            d="m 261,529.15343 a 18.7392,18.7392 0 0 1 -18.7392,-18.7392 v -237.2608 a 18.773335,18.773335 0 1 1 37.54667,0 v 237.2608 A 18.7392,18.7392 0 0 1 261,529.15343 Z"
                        />
            </svg>
            <div class="inventory-title">INVENTORY</div>
            <div class="inventory-subtitle">Live Updates</div>
        </div>

            <!-- FOOTER -->
        <div class="footer-full">
            Problemas para acceder?
            <a href="#" class = "fw-bold">Contact IT Support</a>
        </div>

    </div>
</div>

</body>
</html>