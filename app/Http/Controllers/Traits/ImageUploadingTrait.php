<?php

namespace App\Http\Controllers\Traits;

use App\Models\Siswa;
use App\Models\TemporaryFile;
use Illuminate\Http\Request;
use Seld\PharUtils\Timestamps;

trait ImageUploadingTrait
{
    public function storeImage(Request $request)
    {

        $path = storage_path('tmp/uploads');


        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $file = $request->file('image');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }


    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('tmp/uploads', 'public');
        }
        return '';
    }

    public function uploadRevert(Request $request)
    {
        if ($image = $request->get('image')) {
            $path = storage_path('app/public/' . $image);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        return '';
    }
}
