@extends('layout.main')
@section('title', 'Tambah IKM')
<!-- Add these links in the <head> section of your HTML -->
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
@endsection
@section('container')


<style>
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

<form action="{{ route('ikm.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="px-2 py-1 mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Tambah IKM</h2>
        <button class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition ">+ Simpan
            Data</button>
    </div>

    @if ($errors->any())
    <div class="bg-lightyellow/50 dark:bg-lightyellow border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <strong class="font-bold">Oops!</strong>
        <span class="block sm:inline">Ada kesalahan pada input Anda:</span>
        <ul class="mt-2 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    <div class="grid grid-cols-1 2xl:grid-cols-2 gap-2">
        <div>
            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">NIK <span style="color:red">*</span></label>
                <input type="text" name="nik" placeholder="NIK" class="form-input" maxlength="16"
                    value="{{ old('nik', $item->nik ?? '') }}" />
            </div>

            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Nama <span style="color:red">*</span></label>
                <input type="text" name="nama" placeholder="Nama" class="form-input"
                    value="{{ old('nama', $item->nama ?? '') }}" />
            </div>

            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Tempat Lahir <span style="color:red">*</span></label>
                <input type="text" name="tempat_lahir" placeholder="Tempat Lahir" class="form-input"
                    value="{{ old('tempat_lahir', $item->tempat_lahir ?? '') }}" />
            </div>

            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Tanggal Lahir <span style="color:red">*</span></label>
                <input type="date" name="tanggal_lahir" class="form-input"
                    value="{{ old('tanggal_lahir', $item->tanggal_lahir ?? '') }}" />
            </div>

            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Jenis Kelamin <span style="color:red">*</span></label>
                <select name="jenis_kelamin" class="form-input" id="jenis_kelamin">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="L" {{ old('jenis_kelamin', $item->jenis_kelamin ?? '') == 'L' ? 'selected' : ''
                        }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $item->jenis_kelamin ?? '') == 'P' ? 'selected' : ''
                        }}>Perempuan</option>
                </select>
            </div>

            <!-- Alamat -->
            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Alamat <span style="color:red">*</span></label>
                <input type="text" name="alamat" placeholder="Alamat" class="form-input"
                    value="{{ old('alamat', $item->alamat ?? '') }}" />
            </div>

            <div class="flex gap-3 mb-3">
                <div class="flex-1 py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">RT <span style="color:red">*</span></label>
                    <input type="text" name="rt" placeholder="RT" class="form-input"
                        value="{{ old('rt', $item->rt ?? '') }}" />
                </div>
                <div class="flex-1 py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                    <label class="block mb-1 text-xs text-black/40 dark:text-white/40">RW <span style="color:red">*</span></label>
                    <input type="text" name="rw" placeholder="RW" class="form-input"
                        value="{{ old('rw', $item->rw ?? '') }}" />
                </div>
            </div>
            <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Provinsi <span style="color:red">*</span></label>
                <select id="provinsi" name="id_provinsi" class="form-select w-full">
                    <option value="" selected>Pilih Provinsi</option>
                    @foreach ($provinsi as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kota / Kabupaten <span style="color:red">*</span></label>
                <select id="kabupaten" name="id_kota" class="form-select w-full">
                    {{-- otomatis --}}
                </select>
            </div>

            <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kecamatan <span style="color:red">*</span></label>
                <select id="kecamatan" name="id_kecamatan" class="form-select w-full">
                    {{-- otomatis --}}
                </select>
            </div>
            <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Desa <span style="color:red">*</span></label>
                <select id="desa" name="id_desa" class="form-select w-full">
                    {{-- otomatis --}}
                </select>
            </div>

            
        </div>
        <div>
            <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Agama <span style="color:red">*</span></label>
                <select id="agama" name="agama" class="form-select w-full">
                    <option value="islam">Islam</option>
                    <option value="kristen_protestan">Kristen (Protestan)</option>
                    <option value="katolik">Katolik</option>
                    <option value="hindu">Hindu</option>
                    <option value="buddha">Buddha</option>
                    <option value="konghucu">Konghucu</option>
                    <option value="kepercayaan">Kepercayaan (Agama Lokal)</option>
                </select>
            </div>

            <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Status <span style="color:red">*</span></label>
                <select id="status_perkawinan" name="status_perkawinan" class="form-select w-full">
                    <option value="" selected>status perkawinan</option>
                    <option value="belum_menikah">Belum Menikah</option>
                    <option value="menikah">Menikah</option>
                    <option value="cerai_hidup">Cerai Hidup</option>
                    <option value="cerai_mati">Cerai Mati</option>
                </select>
            </div>


            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Pekerjaan <span style="color:red">*</span></label>
                <input type="text" name="pekerjaan" placeholder="Pekerjaan" class="form-input"
                    value="{{ old('pekerjaan', $item->pekerjaan ?? '') }}" />
            </div>

            <div class="py-4 px-5 bg-white rounded-lg border border-black/10 relative dark:bg-white/5 mb-3">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Kewarganegaraan <span style="color:red">*</span></label>
                <select id="kewarganegaraan" name="kewarganegaraan" class="form-select w-full">
                    <option value="" selected> Kewarganegaraan</option>
                    <option value="wni">Warga Negara Indonesia (WNI)</option>
                    <option value="wna">Warga Negara Asing (WNA)</option>
                </select>
            </div>

            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">No. Telepon <span style="color:red">*</span></label>
                <input type="text" name="telp" placeholder="No. Telepon" class="form-input"
                    value="{{ old('telp', $item->telp ?? '') }}" />
            </div>

            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5" hidden>
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Sosial Media</label>
                <input type="text" name="sosmed" placeholder="Sosial Media" class="form-input"
                    value="{{ old('sosmed', $item->sosmed ?? '') }}" />
            </div>

            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5" hidden>
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Website</label>
                <input type="text" name="website" placeholder="Website" class="form-input"
                    value="{{ old('website', $item->website ?? '') }}" />
            </div>

            <div class="py-4 px-5 mb-3 bg-white rounded-lg border border-black/10 relative dark:bg-white/5">
                <label class="block mb-1 text-xs text-black/40 dark:text-white/40">Email <span style="color:red">*</span></label>
                <input type="email" name="email" placeholder="Email" class="form-input"
                    value="{{ old('email', $item->email ?? '') }}"  required/>
            </div>

        </div>

    </div>
</form>


<script>
    document.addEventListener('DOMContentLoaded', function() {
            $('#jenis_kelamin').select2({
                placeholder: "Pilih Jenis Kelamin",
                width: '100%'
            });
            $('#agama').select2({
                placeholder: "Agama",
                width: '100%'
            });
            $('#status_perkawinan').select2({
                placeholder: "Status Perkawinan",
                width: '100%'
            });
            $('#kewarganegaraan').select2({
                placeholder: "kewarganegaraan",
                width: '100%'
            });
            $('#provinsi').select2({
                placeholder: "Pilih Provinsi",
                width: '100%'
            });
            $('#kabupaten').select2({
                placeholder: "Pilih Kabupaten / Kota",
                width: '100%'
            });
            $('#kecamatan').select2({
                placeholder: "Pilih Kecamatan",
                width: '100%'
            });
            $('#desa').select2({
                placeholder: "Pilih Desa",
                width: '100%'
            });
        });
</script>

<script>
    $(function(){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });

        $(function (){
            $('#provinsi').on('change',function(){
                let id_provinsi = $('#provinsi').val();

                $.ajax({
                    type : 'POST',
                    url : "{{route('getkabupaten')}}",
                    data : {id_provinsi:id_provinsi},
                    cache : false,

                    success: function(msg){
                        $('#kabupaten').removeAttr('disabled');
                        $('#kabupaten').html(msg);
                        $('#kecamatan').html('');
                        $('#desa').html('');

                    },
                    error: function(data) {
                        console.log('error:',data)
                    },
                })
            })


            $('#kabupaten').on('change',function(){
                let id_kabupaten = $('#kabupaten').val();

                $.ajax({
                    type : 'POST',
                    url : "{{route('getkecamatan')}}",
                    data : {id_kabupaten:id_kabupaten},
                    cache : false,

                    success: function(msg){
                        $('#kecamatan').removeAttr('disabled');
                        $('#kecamatan').html(msg);
                        $('#desa').html('');


                    },
                    error: function(data) {
                        console.log('error:',data)
                    },
                })
            })

            $('#kecamatan').on('change',function(){
                let id_kecamatan = $('#kecamatan').val();

                $.ajax({
                    type : 'POST',
                    url : "{{route('getdesa')}}",
                    data : {id_kecamatan:id_kecamatan},
                    cache : false,

                    success: function(msg){
                        $('#desa').removeAttr('disabled');
                        $('#desa').html(msg);


                    },
                    error: function(data) {
                        console.log('error:',data)
                    },
                })
            })
        })
    });
</script>
@endsection