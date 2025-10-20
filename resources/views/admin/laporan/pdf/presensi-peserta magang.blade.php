<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $title . ' ' . $peserta_magang->nama_lengkap . '.pdf' }}</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        .title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: 800;
            line-height: 1.5rem;
        }

        table {
            border-collapse: collapse;
        }

        .identitas-peserta_magang {
            margin-top: 2rem;
        }

        .identitas-peserta_magang td {
            padding: 0.25rem;
        }

        .presensi-peserta_magang {
            width: 100%;
            margin-top: 1.5rem;
        }

        .presensi-peserta_magang tbody>tr>td {
            text-align: center;
            padding: 0.5rem;
        }

        .presensi-peserta_magang th {
            font-weight: bold;
            background: salmon;
            padding: 0.5rem;
            font-size: 14px;
        }

        .presensi-peserta_magang>tbody>tr>td {
            font-size: 12px;
        }

        .presensi-peserta_magang,
        .presensi-peserta_magang>thead>tr>th,
        .presensi-peserta_magang>tbody>tr>td {
            border: 1px solid black;
            padding: 0.5rem
        }

        .pengesahan-atasan {
            width: 100%;
            margin-top: 2rem;
        }

        .atasan td {
            text-align: center;
            vertical-align: bottom;
            height: 10rem;
        }

        .tempat td {
            text-align: right;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">
        <table style="width: 100%">
            <tr>
                <td style="width: 30px;">
                    <img src="{{ public_path('img/logo 2.png') }}" alt="logo" width="100" height="100" style="border-radius: 21px" />
                </td>
                <td>
                    <span class="title" style="margin-left: 0.5rem;">
                        {{ strtoupper($title) }} <br>
                    </span>
                    <span class="title" style="margin-left: 0.5rem;">
                        PERIODE {{ strtoupper(\Carbon\Carbon::make($bulan)->format("F")) }} TAHUN {{ \Carbon\Carbon::make($bulan)->format("Y") }} <br>
                    </span>
                    <span class="title" style="margin-left: 0.5rem;">
                        DISKOMINFO GARUT <br>
                    </span>
                    <span style="margin-left: 0.5rem;">
                        <i>Jl. Pembangunan No.181, Sukagalih, Kec. Tarogong Kidul, Kabupaten Garut, Jawa Barat 44151
                    </span></i>
                    </span
                </td>
            </tr>
        </table>

        <table class="identitas-peserta_magang">
            <tr>
                <td rowspan="7">
                    @if ($peserta_magang->foto)
                        <img src="{{ public_path("storage/unggah/peserta_magang/$peserta_magang->foto") }}" alt="foto-peserta_magang" width="100" height="150" style="border-radius: 0.5rem" />
                    @else
                        <img src="{{ public_path("img/team-2.jpg") }}" alt="foto-peserta_magang" width="100" height="150" style="border-radius: 0.5rem" />
                    @endif
                </td>
            </tr>
            <tr>
                <td>NPM</td>
                <td>:</td>
                <td>{{ $peserta_magang->npm }}</td>
            </tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $peserta_magang->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Pendidikan</td>
                <td>:</td>
                <td>{{ $peserta_magang->pendidikan }}</td>
            </tr>
            <tr>
                <td>JobTrain</td>
                <td>:</td>
                <td>{{ $peserta_magang->jobtrain->nama }}</td>
            </tr>
            <tr>
                <td>Email / Telepon</td>
                <td>:</td>
                <td>{{ $peserta_magang->email }} / {{ $peserta_magang->telepon }}</td>
            </tr>
        </table>

        <table class="presensi-peserta_magang">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Foto</th>
                    <th>Jam Keluar</th>
                    <th>Foto</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayatPresensi as $value => $item)
                    <tr>
                        <td>
                            {{ $value + 1 . "." }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::make($item->tanggal_presensi)->format("d-m-Y") }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::make($item->jam_masuk)->format("H:i") }}
                        </td>
                        <td>
                            <img src="{{ public_path("storage/unggah/presensi/$item->foto_masuk") }}" alt="{{ $item->foto_masuk }}" width="50" height="50" style="border-radius: 0.5rem" />
                        </td>
                        <td>
                            @if ($item->jam_keluar)
                                {{ \Carbon\Carbon::make($item->jam_keluar)->format("H:i") }}
                            @else
                                <div>Belum Presensi</div>
                            @endif
                        </td>
                        <td>
                            @if ($item->foto_keluar)
                                <img src="{{ public_path("storage/unggah/presensi/$item->foto_keluar") }}" alt="{{ $item->foto_keluar }}" width="50" height="50" style="border-radius: 0.5rem" />
                            @else
                                <img src="{{ public_path("img/") }}" alt="{{ $item->foto_keluar }}" width="50" height="50" style="border-radius: 0.5rem" />
                            @endif
                        </td>
                        <td>
                            @if ($item->jam_masuk > Carbon\Carbon::make("07:30:00")->format("H:i:s"))
                                @php
                                    $masuk = Carbon\Carbon::make($item->jam_masuk);
                                    $batas = Carbon\Carbon::make($jamKerja->jam_masuk);
                                    $diff = $masuk->diff($batas);
                                    if ($diff->format("%h") != 0) {
                                        $selisih = $diff->format("%h jam %I menit");
                                    } else {
                                        $selisih = $diff->format("%I menit");
                                    }
                                @endphp
                                <div>Terlambat <br> {{ $selisih }}</div>
                            @else
                                <div>Tepat Waktu</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="pengesahan-atasan">
            <tr class="tempat">
                <td colspan="2">
                    Garut, {{ \Carbon\Carbon::now()->format("d F Y") }}
                </td>
            </tr>
            <tr class="atasan">
                <td>
                    <u>Bu Laras<u> <br>
                    <i><b>Pembimbing</b></i>
                </td>
                <td>
                    <u></u> <br>
                    <i><b>Direktur</b></i>
                </td>
            </tr>
        </table>
    </section>

</body>

</html>
