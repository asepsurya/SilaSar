<?php

namespace App\Http\Controllers\Region;

use App\Models\Regency;
use App\Models\Village;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
     public function getkabupaten(request $request){
        $id_provinsi = $request->id_provinsi;
        $option = "<option value=''> Kota/Kabupaten </option>";
        $kabupatens = Regency::where('province_id',$id_provinsi)->get();
        foreach($kabupatens as $kabupaten){
            $option.="<option value='$kabupaten->id' > $kabupaten->name </option>";
        }
        echo $option;
    }
    public function getkecamatan(request $request){
        $id_kabupaten = $request->id_kabupaten;

        $option = "<option value=''> Kecamatan </option>";
        $kecamatans = District::where('regency_id',$id_kabupaten)->get();
        foreach($kecamatans as $kecamatan){
            $option.="<option value='$kecamatan->id'> $kecamatan->name </option>";
        }
        echo $option;
    }

    public function getdesa(request $request){
        $id_kecamatan = $request->id_kecamatan;

        $option = "<option value=''> Kelurahan/Desa </option>";
        $desas = Village::where('district_id',$id_kecamatan)->get();
        foreach($desas as $desa){
            $option.= "<option value='$desa->id' > $desa->name </option>";
        }
        echo $option;
    }
}
