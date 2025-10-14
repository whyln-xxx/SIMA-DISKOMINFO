<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __("Administrasi Presensi") }}
            </h2>
        </div>
    </x-slot>

    <div class="container mx-auto px-5 pt-5">
        <div>
            <form action="{{ route("admin.administrasi-presensi") }}" method="get" enctype="multipart/form-data" class="my-3">
                <div class="flex w-full flex-wrap gap-2 md:flex-nowrap items-end">
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">NPM</span>
                        </div>
                        <input type="text" name="npm" placeholder="NPM" class="input input-bordered w-full" value="{{ request()->npm }}" />
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Nama Peserta</span>
                        </div>
                        <input type="text" name="peserta" placeholder="Nama Peserta" class="input input-bordered w-full" value="{{ request()->peserta_magang }}" />
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Job Train</span>
                        </div>
                        <select name="jobtrain" class="select select-bordered">
                            <option value="0">Semua Job Train</option>
                            @foreach ($jobtrain as $item)
                                <option value="{{ $item->id }}" {{ request()->jobtrain == $item->id ? "selected" : "" }}>{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Tanggal Awal</span>
                        </div>
                        <input type="date" name="tanggal_awal" class="input input-bordered w-full" value="{{ request()->tanggal_awal ? request()->tanggal_awal : \Carbon\Carbon::now()->startOfMonth()->format("Y-m-d") }}" />
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Tanggal Akhir</span>
                        </div>
                        <input type="date" name="tanggal_akhir" class="input input-bordered w-full" value="{{ request()->tanggal_akhir ? request()->tanggal_akhir : \Carbon\Carbon::now()->endOfMonth()->format("Y-m-d") }}" />
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Status</span>
                        </div>
                        <select name="status" class="select select-bordered">
                            <option value="0">Semua Status</option>
                            <option value="I" {{ request()->status == 'I' ? "selected" : "" }}>Izin</option>
                            <option value="S" {{ request()->status == 'S' ? "selected" : "" }}>Sakit</option>
                        </select>
                    </label>
                    <label class="form-control w-full max-w-xs">
                        <div class="label">
                            <span class="label-text">Status Approved</span>
                        </div>
                        <select name="status_approved" class="select select-bordered">
                            <option value="0">Semua Aksi</option>
                            <option value="1" {{ request()->status_approved == 1 ? "selected" : "" }}>Pending</option>
                            <option value="2" {{ request()->status_approved == 2 ? "selected" : "" }}>Diterima</option>
                            <option value="3" {{ request()->status_approved == 3 ? "selected" : "" }}>Ditolak</option>
                        </select>
                    </label>
                    <button type="submit" class="btn btn-success w-full md:w-14">
                        <i class="ri-search-2-line text-lg text-white"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="w-full overflow-x-auto rounded-md bg-slate-200 px-10">
            <table id="tabelPresensi" class="table mb-4 w-full border-collapse items-center border-gray-200 align-top dark:border-white/40">
                <thead class="text-sm text-gray-800 dark:text-gray-700">
                    <tr>
                        <th></th>
                        <th>Nama Peserta / NPM</th>
                        <th>Job train</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuan as $value => $item)
                        <tr class="hover">
                            <td class="font-bold">{{ $value + 1 }}</td>
                            <td class="text-slate-500 dark:text-slate-700">{{ $item->nama_peserta_magang }} - {{ $item->npm }}</td>
                            <td class="text-slate-500 dark:text-slate-700">{{ $item->nama_jobtrain }}</td>
                            <td class="text-slate-500 dark:text-slate-700">{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format("l, d-m-Y") }}</td>
                            <td class="text-slate-500 dark:text-slate-700">
                                @if ($item->status == "I")
                                    <span>Izin</span>
                                @elseif ($item->status == "S")
                                    <span>Sakit</span>
                                @endif
                            </td>
                            <td class="text-slate-500 dark:text-slate-700">{{ $item->keterangan }}</td>
                            <td class="flex justify-center gap-2">
                                @if ($item->status_approved == 1)
                                    <label class="btn btn-warning btn-sm tooltip flex items-center" data-tip="Diterima" onclick="return terima_button('{{ $item->id }}', '{{ $item->nama_peserta_magang }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}', 'terima')">
                                        <i class="ri-checkbox-circle-line"></i>
                                    </label>
                                    <label class="btn btn-error btn-sm tooltip flex items-center" data-tip="Ditolak" onclick="return tolak_button('{{ $item->id }}', '{{ $item->nama_peserta_magang }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}', 'tolak')">
                                        <i class="ri-close-circle-line"></i>
                                    </label>
                                @elseif ($item->status_approved == 2)
                                    <div class="flex items-center gap-2">
                                        <div class="badge badge-success">Diterima</div>
                                        <label class="btn btn-error btn-sm tooltip flex items-center" data-tip="Dibatalkan" onclick="return batal_button('{{ $item->id }}', '{{ $item->nama_peserta_magang }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}', 'batal')">
                                            <i class="ri-close-circle-line"></i>
                                        </label>
                                    </div>
                                @elseif ($item->status_approved == 3)
                                    <div class="flex items-center gap-2">
                                        <div class="badge badge-error">Ditolak</div>
                                        <label class="btn btn-error btn-sm tooltip flex items-center" data-tip="Dibatalkan" onclick="return batal_button('{{ $item->id }}', '{{ $item->nama_peserta_magang }}', '{{ \Carbon\Carbon::parse($item->tanggal_pengajuan)->format('d-m-Y') }}', 'batal')">
                                            <i class="ri-close-circle-line"></i>
                                        </label>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mx-3 mb-5">
                {{ $pengajuan->links() }}
            </div>
        </div>
    </div>

    <script>
        @if (session()->has("success"))
            Swal.fire({
                title: 'Berhasil',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        @if (session()->has("error"))
            Swal.fire({
                title: 'Gagal',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonColor: '#6419E6',
                confirmButtonText: 'OK',
            });
        @endif

        function terima_button(id, peserta_magang, tanggal, ajuan) {
            Swal.fire({
                title: 'Pengajuan Presensi Diterima',
                html: "<p>Apakah Anda menerima pengajuan presensi?</p>" +
                    "<div class='divider'></div>" +
                    "<div class='flex flex-col'>" +
                    "<b>Peserta: " + peserta_magang + "</b>" +
                    "<b>Tanggal Pengajuan: " + tanggal + "</b>" +
                    "</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Terima',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route("admin.administrasi-presensi.persetujuan") }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "ajuan": ajuan
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.responseJSON.message
                            })
                        }
                    });
                }
            })
        }

        function tolak_button(id, peserta_magang, tanggal, ajuan) {
            Swal.fire({
                title: 'Pengajuan Presensi Ditolak',
                html: "<p>Apakah Anda menolak pengajuan presensi?</p>" +
                    "<div class='divider'></div>" +
                    "<div class='flex flex-col'>" +
                    "<b>Peserta Magang: " + peserta_magang + "</b>" +
                    "<b>Tanggal Pengajuan: " + tanggal + "</b>" +
                    "</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Tolak',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route("admin.administrasi-presensi.persetujuan") }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "ajuan": ajuan
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.responseJSON.message
                            })
                        }
                    });
                }
            })
        }

        function batal_button(id, peserta_magang, tanggal, ajuan) {
            Swal.fire({
                title: 'Pengajuan Presensi Dibatalkan',
                html: "<p>Apakah Anda membatalkan pengajuan presensi?</p>" +
                    "<div class='divider'></div>" +
                    "<div class='flex flex-col'>" +
                    "<b>Peserta: " + peserta_magang + "</b>" +
                    "<b>Tanggal Pengajuan: " + tanggal + "</b>" +
                    "</div>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6419E6',
                cancelButtonColor: '#F87272',
                confirmButtonText: 'Batalkan',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "{{ route("admin.administrasi-presensi.persetujuan") }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": id,
                            "ajuan": ajuan
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#6419E6',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(response) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.responseJSON.message
                            })
                        }
                    });
                }
            })
        }
    </script>
</x-app-layout>
