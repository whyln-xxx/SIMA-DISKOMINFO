@if ($data->isEmpty())
    <div role="alert" class="alert alert-warning my-5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>Tidak Ada Data</span>
    </div>
@else
    <table class="table my-5 w-full border-collapse items-center border-gray-200 align-top dark:border-white/40">
        <thead class="text-sm text-gray-800 dark:text-gray-300">
            <tr>
                <th></th>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Approved</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $value => $item)
                <tr class="hover">
                    <td class="font-bold">{{ $value+1 }}</td>
                    <td class="text-slate-500 dark:text-slate-300">{{ date("l", strtotime($item->tanggal_pengajuan)) }}</td>
                    <td class="text-slate-500 dark:text-slate-300">{{ date("d-m-Y", strtotime($item->tanggal_pengajuan)) }}</td>
                    <td class="text-slate-500 dark:text-slate-300">
                        @if ($item->status == "I")
                            Izin
                        @elseif ($item->status == "S")
                            Sakit
                        @endif
                    </td>
                    <td class="text-slate-500 dark:text-slate-300">
                        @if ($item->status_approved == 0)
                            Pending
                        @elseif ($item->status_approved == 1)
                            Disetujui
                        @elseif ($item->status_approved == 2)
                            Ditolak
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

