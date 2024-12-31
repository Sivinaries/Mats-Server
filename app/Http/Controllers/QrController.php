<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{

    public function index()
    {
        return view('scanner');
    }

    public function show($id)
    {
        $product = Menu::findOrFail($id);

        $qrUrl = route('showproduct', ['id' => $product->id]);

        $qrCode = QrCode::size(400)->generate($qrUrl);

        $filename = "qrcodes/". $product->sku . ".svg";
        Storage::disk('public')->put($filename, $qrCode);

        return view('qrcode', ['filename' => $filename, 'product' => $product]);
    }
}
