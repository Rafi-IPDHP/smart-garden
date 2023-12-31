<?php

namespace App\Http\Controllers;

use App\Models\PhTurbidity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Sensor;
use App\Models\SimpanTds;
use App\Models\UvAir;

class SensorController extends Controller
{
    public function air() {
        $air = Sensor::all();
        return view('sensor.air', compact('air'));
    }
    public function tds() {
        $tds = Sensor::all();
        return view('sensor.tds', compact('tds'));
    }

    public function ph() {
        $phTurbidity = PhTurbidity::all();
        return view('sensor.ph', compact('phTurbidity'));
    }
    public function turbidity() {
        $phTurbidity = PhTurbidity::all();
        return view('sensor.turbidity', compact('phTurbidity'));
    }
    public function uv() {
        $uvAirs = UvAir::all();
        return view('sensor.uv', compact('uvAirs'));
    }
    public function airTemperature() {
        $uvAirs = UvAir::all();
        return view('sensor.airTemperature', compact('uvAirs'));
    }
    public function airHumidity() {
        $uvAirs = UvAir::all();
        return view('sensor.airHumidity', compact('uvAirs'));
    }


    public function grafik(Request $request) {
        // Ambil jumlah total data yang ada dalam SimpanTds
        $totalData = SimpanTds::count();

        // Cek apakah jumlah data sudah mencapai 24
        if ($totalData >= 24) {
            // Hapus data dengan id terkecil (paling tua)
            $oldestData = SimpanTds::orderBy('id', 'asc')->first();
            $oldestData->delete();
        }
        SimpanTds::create(['data_tds' => $request->nilaiTds]);
    }

    public function esp8266() {
        Sensor::where('id', '1')->update(['tds' => request()->nilaiTds, 'waterTemp' => request()->waterTemp]);
        UvAir::where('id', '1')->update(['air_humidity' => request()->airHumidity, 'air_temperature' => request()->airTemperature]);
    }

    public function esp32() {
        PhTurbidity::where('id', '1')->update(['ph' => request()->nilaiPh]);
        UvAir::where('id', '1')->update(['uv' => request()->uv]);
    }

    public function grafikTds() {
        $data_tds = SimpanTds::all();
        // return view('simpanSensor.data_tds', compact('data_tds'));
        return response()->json($data_tds);
    }
}
