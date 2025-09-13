@extends('layout.main')
@section('title', 'Data Akun')
@section('container')
    <style>
    @media (max-width: 640px) {
  .p-7 {
    padding: 10px !important;
  }
}
        .select2-container--default .select2-selection--single {
            margin-left: -10px;
            border: none;
        }

        .dark .select2-container--default .select2-selection--single {
            background-color: rgba(0, 0, 0, 0);
            margin-left: -10px;
            border: none;
        }
    </style>
    <div class="px-2 py-1 mb-4">
        <div class="px-2 py-1 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Data Akun</h2>
            <div x-data="modals">
                <button type="button" @click="toggle"
                    class="px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    + Tambah Akun Baru
                </button>
                <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                   :class="open &amp;&amp; '!block'">
                    <div class="flex items-center justify-center min-h-screen px-4" @click.self="open = false">
                        <div x-show="open" x-transition="" x-transition.duration.300=""
                            class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                            style="display: none;">
                            <div
                                class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                <h5 class="font-semibold text-lg">Tambah Akun Baru</h5>
                                <button type="button"
                                    class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                    @click="toggle">
                                    <svg class="w-5 h-5" width="32" height="32" viewBox="0 0 32 32" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                            fill="currentcolor"></path>
                                        <path
                                            d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                            fill="currentcolor"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-5">
                                <form action="{{ route('akun.create') }}" method="POST">
                                    @csrf
                                    <div
                                        class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama Akun <span style="color: red">*</span></label>
                                        <input type="text" name="nama_akun" placeholder="Nama Akun" class="form-input">
                                    </div>
                                    <div
                                        class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                        <label class="block text-xs text-black/40 dark:text-white/40 mb-1">Jenis
                                            Akun <span style="color: red">*</span></label>
                                        <select name="jenis_akun"
                                            class="select2 form-select py-2.5 px-3 text-sm text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-black dark:focus:border-white focus:ring-0 focus:shadow-none w-[150px]">
                                            <option value="pemasukan">Pemasukan</option>
                                            <option value="pengeluaran">Pengeluaran</option>
                                        </select>
                                    </div>
                                    <button type="submit"
                                        class=" w-full px-2 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                        Simpan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
        <div class="">
            <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-3 rounded-md">
                <p class="mb-2 text-sm font-semibold">Pemasukan</p>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th>Nama Akun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach ($akun->where('jenis_akun', 'pemasukan') as $data)
                                <tr>
                                    <td class="whitespace-nowrap">{{ $no++ }}</td>
                                    <td>
                                        <div x-data="modals">
                                            <button type="button" @click="toggle">{{ $data->nama_akun }}</button>
                                            <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                                             :class="open &amp;&amp; '!block'">
                                                <div class="flex items-center justify-center min-h-screen px-4"
                                                    @click.self="open = false">
                                                    <div x-show="open" x-transition="" x-transition.duration.300=""
                                                        class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                                                        style="display: none;">
                                                        <div
                                                            class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                                            <h5 class="font-semibold text-lg">Edit Akun</h5>
                                                            <button type="button"
                                                                class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                                                @click="toggle">
                                                                <svg class="w-5 h-5" width="32" height="32"
                                                                    viewBox="0 0 32 32" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                                                        fill="currentcolor"></path>
                                                                    <path
                                                                        d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                                                        fill="currentcolor"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="p-5">
                                                            <form action="{{ route('akun.update') }}" method="POST">
                                                                @csrf
                                                                <input type="text" value="{{ $data->id }}"
                                                                    name="id" hidden>
                                                                <div
                                                                    class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                                    <label
                                                                        class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama
                                                                        Akun</label>
                                                                    <input type="text" name="nama_akun"
                                                                        placeholder="Nama Akun" class="form-input"
                                                                        value="{{ $data->nama_akun }}">
                                                                </div>
                                                                <div
                                                                    class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                                    <label
                                                                        class="block text-xs text-black/40 dark:text-white/40 mb-1">Jenis
                                                                        Akun</label>
                                                                    <select name="jenis_akun"
                                                                        class="select2 form-select py-2.5 px-3 text-sm text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-black dark:focus:border-white focus:ring-0 focus:shadow-none w-[150px]">
                                                                        <option value="pemasukan"
                                                                            {{ $data->jenis_akun == 'pemasukan' ? 'selected' : '' }}>
                                                                            Pemasukan</option>
                                                                        <option value="pengeluaran"
                                                                            {{ $data->jenis_akun == 'pengeluaran' ? 'selected' : '' }}>
                                                                            Pengeluaran</option>
                                                                    </select>
                                                                </div>
                                                                <div class="flex justify-between items-center gap-2 mt-4">
                                                                    <button type="submit"
                                                                        class="w-full px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                                                        Simpan
                                                                    </button>

                                                                    <a href="{{ route('akun.delete', $data->id) }}"
                                                                        class="w-full text-center px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                                                        Hapus
                                                                    </a>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($akun->where('jenis_akun', 'pemasukan')->count() == 0)
                                <tr>
                                    <td>Data Tdak Ditemukan</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="">
            <div class="border bg-lightwhite dark:bg-white/5 dark:border-white/10 border-black/10 p-3 rounded-md">
                <p class="mb-2 text-sm font-semibold">Pengeluaran</p>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th>Nama Akun</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach ($akun->where('jenis_akun', 'pengeluaran') as $data)
                                <tr>
                                    <td class="whitespace-nowrap">{{ $no++ }}</td>
                                    <td>
                                        <div x-data="modals">
                                            <button type="button" @click="toggle">{{ $data->nama_akun }}</button>
                                            <div class="fixed inset-0 bg-black/60 dark:bg-white/10 z-[999] hidden overflow-y-auto"
                                               :class="open &amp;&amp; '!block'">
                                                <div class="flex items-center justify-center min-h-screen px-4"
                                                    @click.self="open = false">
                                                    <div x-show="open" x-transition="" x-transition.duration.300=""
                                                        class="bg-white dark:bg-black relative shadow-3xl border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8"
                                                        style="display: none;">
                                                        <div
                                                            class="flex bg-white dark:bg-black border-b border-black/10 dark:border-white/10 items-center justify-between px-5 py-3">
                                                            <h5 class="font-semibold text-lg">Edit Akun</h5>
                                                            <button type="button"
                                                                class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white"
                                                                @click="toggle">
                                                                <svg class="w-5 h-5" width="32" height="32"
                                                                    viewBox="0 0 32 32" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M24.2929 6.29289L6.29289 24.2929C6.10536 24.4804 6 24.7348 6 25C6 25.2652 6.10536 25.5196 6.29289 25.7071C6.48043 25.8946 6.73478 26 7 26C7.26522 26 7.51957 25.8946 7.70711 25.7071L25.7071 7.70711C25.8946 7.51957 26 7.26522 26 7C26 6.73478 25.8946 6.48043 25.7071 6.29289C25.5196 6.10536 25.2652 6 25 6C24.7348 6 24.4804 6.10536 24.2929 6.29289Z"
                                                                        fill="currentcolor"></path>
                                                                    <path
                                                                        d="M7.70711 6.29289C7.51957 6.10536 7.26522 6 7 6C6.73478 6 6.48043 6.10536 6.29289 6.29289C6.10536 6.48043 6 6.73478 6 7C6 7.26522 6.10536 7.51957 6.29289 7.70711L24.2929 25.7071C24.4804 25.8946 24.7348 26 25 26C25.2652 26 25.5196 25.8946 25.7071 25.7071C25.8946 25.5196 26 25.2652 26 25C26 24.7348 25.8946 24.4804 25.7071 24.2929L7.70711 6.29289Z"
                                                                        fill="currentcolor"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <div class="p-5">
                                                            <form action="{{ route('akun.update') }}" method="POST">
                                                                @csrf
                                                                <input type="text" value="{{ $data->id }}"
                                                                    name="id" hidden>
                                                                <div
                                                                    class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                                    <label
                                                                        class="block text-xs text-black/40 dark:text-white/40 mb-1">Nama
                                                                        Akun</label>
                                                                    <input type="text" name="nama_akun"
                                                                        placeholder="Nama Akun" class="form-input"
                                                                        value="{{ $data->nama_akun }}">
                                                                </div>
                                                                <div
                                                                    class="mb-4 relative bg-white dark:bg-white/5 py-4 px-5 rounded-lg border border-black/10">
                                                                    <label
                                                                        class="block text-xs text-black/40 dark:text-white/40 mb-1">Jenis
                                                                        Akun</label>
                                                                    <select name="jenis_akun"
                                                                        class="select2 form-select py-2.5 px-3 text-sm text-black dark:text-white border border-black/10 dark:border-white/10 rounded-lg focus:border-black dark:focus:border-white focus:ring-0 focus:shadow-none w-[150px]">
                                                                        <option value="pemasukan"
                                                                            {{ $data->jenis_akun == 'pemasukan' ? 'selected' : '' }}>
                                                                            Pemasukan</option>
                                                                        <option value="pengeluaran"
                                                                            {{ $data->jenis_akun == 'pengeluaran' ? 'selected' : '' }}>
                                                                            Pengeluaran</option>
                                                                    </select>
                                                                </div>
                                                                <div class="flex justify-between items-center gap-2 mt-4">
                                                                    <button type="submit"
                                                                        class="w-full px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                                                        Simpan
                                                                    </button>

                                                                    <a href="{{ route('akun.delete', $data->id) }}"
                                                                        class="w-full text-center px-4 py-2 text-sm bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                                                                        Hapus
                                                                    </a>
                                                                </div>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($akun->where('jenis_akun', 'pengeluaran')->count() == 0)
                                <tr>
                                    <td>Data Tdak Ditemukan</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                width: '100%'
            });
        });
    </script>
@endsection
