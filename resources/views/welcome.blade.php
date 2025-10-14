<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMA Diskominfo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg:#F7EFE3; /* soft beige */
            --text:#111827; /* slate-900 */
            --muted:#374151; /* slate-700 */
            --brand:#4F46E5; /* indigo-600 */
            --brand-dark:#4328B7; /* deep indigo for gradient */
            --karyawan:#5B21B6; /* purple-800 */
            --karyawan-2:#7C3AED; /* purple-600 */
            --admin:#22C55E; /* green-500 */
            --admin-2:#16A34A; /* green-600 */
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0;
            font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,'Apple Color Emoji','Segoe UI Emoji',sans-serif;
            color:var(--text);
            background:var(--bg);
        }
        .container{max-width:1200px;margin:0 auto;padding:24px}
        .topbar{display:flex;align-items:center;justify-content:space-between}
        .brand{display:flex;align-items:center;gap:10px;font-weight:800;letter-spacing:.2px}
        .brand .logo{width:36px;height:36px;border-radius:8px;background:#0ea5e9;display:grid;place-items:center;color:white;font-size:18px}
        .brand .title{font-weight:800}
        .brand .accent{color:#10b981}
        .nav a{display:inline-block;margin-left:12px;text-decoration:none;color:var(--text);padding:8px 14px;border-radius:10px;border:1px solid #ffffff70;background:#fff;box-shadow:0 10px 30px rgba(0,0,0,.06)}
        .nav a:hover{box-shadow:0 12px 38px rgba(0,0,0,.12)}

        .hero{display:grid;grid-template-columns:1.1fr .9fr;align-items:center;gap:40px;padding:32px 0}
        .hero h1{font-size:56px;line-height:1.05;margin:0 0 18px 0;color:#4C1D95 /* purple-900 */}
        .hero p{font-size:16px;line-height:1.7;color:var(--muted);max-width:720px;margin:0 0 24px}
        .cta{display:flex;gap:16px;align-items:center}
        .btn{appearance:none;border:none;cursor:pointer;padding:14px 24px;border-radius:999px;font-weight:700;letter-spacing:.2px;text-decoration:none;display:inline-flex;align-items:center;gap:10px;color:white;box-shadow:0 16px 40px rgba(0,0,0,.12)}
        .btn-purple{background:linear-gradient(135deg,var(--karyawan),var(--karyawan-2))}
        .btn-green{background:linear-gradient(135deg,var(--admin),var(--admin-2))}
        .btn:hover{transform:translateY(-1px)}
        .btn:active{transform:translateY(0)}

        .hero-figure{position:relative}
        .blob{position:absolute;inset:auto 0 0 0;margin:auto;width:82%;height:58%;background:#3B2C71;border-radius:28px;filter:blur(.5px)}
        .photo{position:relative;border-radius:20px;max-width:100%;height:auto}

        @media (max-width: 980px){
            .hero{grid-template-columns:1fr;gap:24px}
            .hero h1{font-size:40px}
            .cta{flex-wrap:wrap}
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="topbar">
            <a href="{{ url('/') }}" class="brand" aria-label="Brand">
                {{-- Logo menggunakan helper asset() dan kelas CSS logo-img --}}
                <img src="{{ asset('img/admin 1.svg') }}" alt="Logo Diskominfo" class="logo-img" /> 
                <div class="title">SIMA <span class="accent">DISKOMINFO</span></div>
            </a>
            <!-- login links removed per request -->
        </header>

        <main class="hero">
            <section>
                <h1>Selamat Datang,<br>Peserta Magang<br>Diskominfo Garut</h1>
                <p>
                    Portal presensi ini didesain untuk mendukung perjalanan profesional Anda. Hanya dengan beberapa klik, Anda dapat memastikan kehadiran terekam, menjaga disiplin, dan fokus sepenuhnya pada pengembangan skill Anda. Disiplin adalah kunci untuk menguasai teknologi. Mari wujudkan potensi Anda!
                </p>
                <div class="cta">
                    @auth('peserta_magang')
                        <a class="btn btn-purple" href="{{ route('peserta_magang.dashboard') }}">Buka Dashboard Peserta</a>
                    @else
                        <a class="btn btn-purple" href="{{ route('login.view') }}">Login Job Training</a>
                    @endauth

                    @auth
                        <a class="btn btn-green" href="{{ route('admin.dashboard') }}">Buka Dashboard Admin</a>
                    @else
                        <a class="btn btn-green" href="{{ route('login') }}">Login Admin</a>
                    @endauth
                </div>
            </section>
        </main>
    </div>
</body>
</html>
