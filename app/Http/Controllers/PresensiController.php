<?php

namespace App\Http\Controllers;

use App\Enums\StatusPengajuanPresensi;
use App\Models\JobTrain;
use App\Models\PesertaMagang;
use App\Models\LokasiKantor;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function index()
    {
        $title = 'Presensi';
        $presensiPesertaMagang = DB::table('presensi')
            ->where('npm', auth()->guard('peserta_magang')->user()->npm)
            ->where('tanggal_presensi', date('Y-m-d'))
            ->first();
        $lokasiKantor = LokasiKantor::where('is_used', true)->first();
        return view('dashboard.presensi.index', compact('title', 'presensiPesertaMagang', 'lokasiKantor'));
    }

    public function store(Request $request)
    {
        $jenisPresensi = $request->jenis;
        $npm = auth()->guard('peserta_magang')->user()->npm;
        $tglPresensi = Carbon::now()->format('Y-m-d');
        $jam = Carbon::now()->format('H:i:s');
        $hari = Carbon::now()->format('N');

        $hariKerja = ($hari == 5) ? 'Jumat' : 'Senin-Kamis';
        $jamKerja = DB::table('jam_kerja')->where('hari', $hariKerja)->first();

        if (!$jamKerja) {
        return response()->json([
            'status' => 500,
            'success' => false,
            'message' => 'Jam kerja tidak ditemukan.',
        ]);
    }

        $lokasi = $request->lokasi;
        $folderPath = "public/unggah/presensi/";
        $folderName = $npm . "-" . $tglPresensi . "-" . $jenisPresensi;

        $lokasiKantor = LokasiKantor::where('is_used', true)->first();
        $langtitudeKantor = $lokasiKantor->latitude;
        $longtitudeKantor = $lokasiKantor->longitude;
        $lokasiUser = explode(",", $lokasi);
        $langtitudeUser = $lokasiUser[0];
        $longtitudeUser = $lokasiUser[1];

        $jarak = round($this->validation_radius_presensi($langtitudeKantor, $longtitudeKantor, $langtitudeUser, $longtitudeUser), 2);
        if ($jarak > 33) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => "Anda berada di luar radius kantor. Jarak Anda " . $jarak . " meter dari kantor",
                'jenis_error' => "radius",
            ]);
        }

        $image = $request->image;
        $imageParts = explode(";base64", $image);
        $imageBase64 = base64_decode($imageParts[1]);

        $fileName = $folderName . ".png";
        $file = $folderPath . $fileName;

        if ($jenisPresensi == "masuk") {
            $data = [
                "npm" => $npm,
                "tanggal_presensi" => $tglPresensi,
                "jam_masuk" => $jam,
                "foto_masuk" => $fileName,
                "lokasi_masuk" => $lokasi,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
            ];
            $store = DB::table('presensi')->insert($data);
        } elseif ($jenisPresensi == "keluar") {
            $data = [
                "jam_keluar" => $jam,
                "foto_keluar" => $fileName,
                "lokasi_keluar" => $lokasi,
                "updated_at" => Carbon::now(),
            ];
            $store = DB::table('presensi')
                ->where('pmk', auth()->guard('peserta_magang')->user()->npm)
                ->where('tanggal_presensi', date('Y-m-d'))
                ->update($data);
        }

        if ($store) {
            Storage::put($file, $imageBase64);
        } else {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => "Gagal presensi",
            ]);
        }

        return response()->json([
            'status' => 200,
            'data' => $data,
            'success' => true,
            'message' => "Berhasil presensi",
            'jenis_presensi' => $jenisPresensi,
        ]);
    }

    public function validation_radius_presensi($langtitudeKantor, $longtitudeKantor, $langtitudeUser, $longtitudeUser)
    {
        $theta = $longtitudeKantor - $longtitudeUser;
        $hitungKoordinat = (sin(deg2rad($langtitudeKantor)) * sin(deg2rad($langtitudeUser))) + (cos(deg2rad($langtitudeKantor)) * cos(deg2rad($langtitudeUser)) * cos(deg2rad($theta)));
        $miles = rad2deg(acos($hitungKoordinat)) * 60 * 1.1515;

        // $feet = $miles * 5280;
        // $yards = $feet / 3;

        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return $meters;
    }

    public function history()
    {
        $title = 'Riwayat Presensi';
        $riwayatPresensi = DB::table("presensi")
            ->where('npm', auth()->guard('peserta_magang')->user()->npm)
            ->orderBy("tanggal_presensi", "asc")
            ->paginate(10);
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return view('dashboard.presensi.history', compact('title', 'riwayatPresensi', 'bulan'));
    }

    public function searchHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $data = DB::table('presensi')
            ->where('npm', auth()->guard('peserta_magang')->user()->npm)
            ->whereMonth('tanggal_presensi', $bulan)
            ->whereYear('tanggal_presensi', $tahun)
            ->orderBy("tanggal_presensi", "asc")
            ->get();
        return view('dashboard.presensi.search-history', compact('data'));
    }

    public function pengajuanPresensi()
    {
        $title = "Izin PesertaMagang";
        $riwayatPengajuanPresensi = DB::table("pengajuan_presensi")
            ->where('npm', auth()->guard('peserta_magang')->user()->npm)
            ->orderBy("tanggal_pengajuan", "asc")
            ->paginate(10);
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return view('dashboard.presensi.izin.index', compact('title', 'riwayatPengajuanPresensi', 'bulan'));
    }

    public function pengajuanPresensiCreate()
    {
        $title = "Form Pengajuan Presensi";
        $statusPengajuan = StatusPengajuanPresensi::cases();
        return view('dashboard.presensi.izin.create', compact('title', 'statusPengajuan'));
    }

    public function pengajuanPresensiStore(Request $request)
    {
        $npm = auth()->guard('peserta_magang')->user()->npm;
        $tanggal_pengajuan = $request->tanggal_pengajuan;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $cekPengajuan = DB::table('pengajuan_presensi')
            ->where('npm', auth()->guard('peserta_magang')->user()->npm)
            ->whereDate('tanggal_pengajuan', Carbon::make($tanggal_pengajuan)->format('Y-m-d'))
            ->where(function (Builder $query) {
                $query->where('status_approved', 0)
                    ->orWhere('status_approved', 1);
            })
            ->first();

        if ($cekPengajuan) {
            return to_route('peserta_magang.izin')->with("error", "Anda sudah menambahkan pengajuan pada tanggal " . Carbon::make($tanggal_pengajuan)->format('d-m-Y'));
        } else {
            $store = DB::table('pengajuan_presensi')->insert([
                'npm' => $npm,
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'status' => $status,
                'keterangan' => $keterangan,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        if ($store) {
            return to_route('peserta_magang.izin')->with("success", "Berhasil menambahkan pengajuan");

        } else {
            return to_route('peserta_magang.izin')->with("error", "Gagal menambahkan pengajuan");
        }
    }

    public function searchPengajuanHistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $data = DB::table('pengajuan_presensi')
            ->where('npm', auth()->guard('peserta_magang')->user()->npm)
            ->whereMonth('tanggal_pengajuan', $bulan)
            ->whereYear('tanggal_pengajuan', $tahun)
            ->orderBy("tanggal_pengajuan", "asc")
            ->get();
        return view('dashboard.presensi.izin.search-history', compact('data'));
    }

    public function monitoringPresensi(Request $request)
    {
        $query = DB::table('presensi as p')
            ->join('peserta_magang as k', 'p.npm', '=', 'k.npm')
            ->join('jobtrain as d', 'k.jobtrain_id', '=', 'd.id')
            ->orderBy('k.nama_lengkap', 'asc')
            ->orderBy('d.kode', 'asc')
            ->select('p.*', 'k.nama_lengkap as nama_peserta_magang', 'd.nama as nama_jobtrain');

        if ($request->tanggal_presensi) {
            $query->whereDate('p.tanggal_presensi', $request->tanggal_presensi);
        } else {
            $query->whereDate('p.tanggal_presensi', Carbon::now());
        }

        $monitoring = $query->paginate(10);

        $lokasiKantor = LokasiKantor::where('is_used', true)->first();

        return view('admin.monitoring-presensi.index', compact('monitoring', 'lokasiKantor'));
    }

    public function viewLokasi(Request $request)
    {
        if ($request->tipe == "lokasi_masuk") {
            $data = DB::table('presensi')->where('npm', $request->npm)->first('lokasi_masuk');
            return $data;
        } elseif ($request->tipe == "lokasi_keluar") {
            $data = DB::table('presensi')->where('npm', $request->npm)->first('lokasi_keluar');
            return $data;
        }
    }

    public function laporan(Request $request)
    {
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $peserta_magang= PesertaMagang::orderBy('nama_lengkap', 'asc')->get();
        return view('admin.laporan.presensi', compact('bulan', 'peserta_magang'));
    }

    public function laporanPresensiPesertaMagang(Request $request)
    {
        $title = 'Laporan Presensi Peserta Magang';
        $bulan = $request->bulan;
        $peserta_magang = PesertaMagang::query()
            ->with('jobtrain')
            ->where('npm', $request->peserta_magang)
            ->first();
        $riwayatPresensi = DB::table("presensi")
            ->where('npm', $request->peserta_magang)
            ->whereMonth('tanggal_presensi', Carbon::make($bulan)->format('m'))
            ->whereYear('tanggal_presensi', Carbon::make($bulan)->format('Y'))
            ->orderBy("tanggal_presensi", "asc")
            ->get();

             $jamKerja = DB::table('jam_kerja')->where('id', 1)->first();

        // return view('admin.laporan.pdf.presensi-peserta_magang', compact('title', 'bulan', 'peserta_magang', 'riwayatPresensi'));
        $pdf = Pdf::loadView('admin.laporan.pdf.presensi-peserta magang', compact('title', 'bulan', 'peserta_magang', 'riwayatPresensi'));
        return $pdf->stream($title . ' ' . $peserta_magang->nama_lengkap . '.pdf');
    }

    public function laporanPresensiSemuaPesertaMagang(Request $request)
    {
        $title = 'Laporan Presensi Semua Peserta Magang';
        $bulan = $request->bulan;
        $riwayatPresensi = DB::table("presensi as p")
            ->join('peserta_magang as k', 'p.npm', '=', 'k.npm')
            ->join('jobtrain as d', 'k.jobtrain_id', '=', 'd.id')
            ->whereMonth('tanggal_presensi', Carbon::make($bulan)->format('m'))
            ->whereYear('tanggal_presensi', Carbon::make($bulan)->format('Y'))
            ->select(
                'p.npm',
                'k.nama_lengkap as nama_peserta_magang',
                'k.pendidikan as pendidikan_peserta_magang',
                'd.nama as nama_jobtrain'
            )
            ->selectRaw("COUNT(p.npm) as total_kehadiran, SUM(IF (jam_masuk > '08:00',1,0)) as total_terlambat")
            ->groupBy(
                'p.npm',
                'k.nama_lengkap',
                'k.pendidikan',
                'd.nama'
            )
            ->orderBy('k.nama_lengkap', 'asc')
            ->get();

        // return view('admin.laporan.pdf.presensi-semua-peserta_magang', compact('title', 'bulan', 'riwayatPresensi'));
        $pdf = Pdf::loadView('admin.laporan.pdf.presensi-semua-peserta magang', compact('title', 'bulan', 'riwayatPresensi'));
        return $pdf->stream($title . '.pdf');
    }

    public function indexAdmin(Request $request)
    {
        $title = 'Administrasi Presensi';

        $jobtrain = JobTrain::get();

        $query = DB::table('pengajuan_presensi as p')
            ->join('peserta_magang as k', 'k.npm', '=', 'p.npm')
            ->join('jobtrain as d', 'k.jobtrain_id', '=', 'd.id')
            ->where('p.tanggal_pengajuan', '>=', Carbon::now()->startOfMonth()->format("Y-m-d"))
            ->where('p.tanggal_pengajuan', '<=', Carbon::now()->endOfMonth()->format("Y-m-d"))
            ->select('p.*', 'k.nama_lengkap as nama_peserta_magang', 'd.nama as nama_jobtrain', 'd.id as id_jobtrain')
            ->orderBy('p.tanggal_pengajuan', 'asc');

        if ($request->npm) {
            $query->where('k.npm', 'LIKE', '%' . $request->npm . '%');
        }
        if ($request->PesertaMagang) {
            $query->where('k.nama_lengkap', 'LIKE', '%' . $request->PesertaMagang . '%');
        }
        if ($request->jobtrain) {
            $query->where('d.id', $request->jobtrain);
        }
        if ($request->tanggal_awal) {
            $query->WhereDate('p.tanggal_pengajuan', '>=', Carbon::parse($request->tanggal_awal)->format('Y-m-d'));
        }
        if ($request->tanggal_akhir) {
            $query->WhereDate('p.tanggal_pengajuan', '<=', Carbon::parse($request->tanggal_akhir)->format('Y-m-d'));
        }
        if ($request->status) {
            $query->Where('p.status', $request->status);
        }
        if ($request->status_approved) {
            $query->Where('p.status_approved', $request->status_approved);
        }

        $pengajuan = $query->paginate(10);

        return view('admin.monitoring-presensi.administrasi-presensi', compact('title', 'pengajuan', 'jobtrain'));
    }

    public function persetujuanPresensi(Request $request)
    {
        if ($request->ajuan == "terima") {
            $pengajuan = DB::table('pengajuan_presensi')->where('id', $request->id)->update([
                'status_approved' => 2
            ]);
            if ($pengajuan) {
                return response()->json(['success' => true, 'message' => 'Pengajuan presensi telah diterima']);
            } else {
                return response()->json(['success' => false, 'message' => 'Pengajuan presensi gagal diterima']);
            }

        } elseif ($request->ajuan == "tolak") {
            $pengajuan = DB::table('pengajuan_presensi')->where('id', $request->id)->update([
                'status_approved' => 3
            ]);
            if ($pengajuan) {
                return response()->json(['success' => true, 'message' => 'Pengajuan presensi telah ditolak']);
            } else {
                return response()->json(['success' => false, 'message' => 'Pengajuan presensi gagal ditolak']);
            }

        } elseif ($request->ajuan == "batal") {
            $pengajuan = DB::table('pengajuan_presensi')->where('id', $request->id)->update([
                'status_approved' => 1
            ]);
            if ($pengajuan) {
                return response()->json(['success' => true, 'message' => 'Pengajuan presensi telah dibatalkan']);
            } else {
                return response()->json(['success' => false, 'message' => 'Pengajuan presensi gagal dibatalkan']);
            }
        }
    }
}
