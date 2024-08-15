<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProyekResource;
use App\Http\Resources\TaskProyekResource;
use App\Models\Proyek;
use Illuminate\Http\Request;

class ProyekController extends Controller
{
    public function index()
    {
        $proyek = Proyek::latest()->paginate(5);
        return ProyekResource::collection($proyek);
    }

    public function taskproyek(Proyek $proyek)
    {
        $task = $proyek->tasks()->latest()->paginate(5);
        return TaskProyekResource::collection($task);
    }

    public function store(Request $request)
    {
        Request()->validate([
            'name_proyek' => ['string', 'required'],
            'description' => ['string', 'required'],
        ]);

        $data = Proyek::create([
            'name_proyek' => $request->name_proyek,
            'description' => $request->description,
        ]);

        return response([
            'message' => 'Proyek Created Succsesfully',
            'data'=> $data
        ]);
    }

    public function update(Request $request, Proyek $proyek)
    {
        Request()->validate([
            'name_proyek' => ['string', 'required'],
            'description' => ['string', 'required'],
        ]);


        $proyek->update([
            'name_proyek' => $request->name_proyek,
            'description' => $request->description,
        ]);

        return new ProyekResource($proyek);
    }

    public function delete(Proyek $proyek)
    {
        $proyek->delete();
        return response('Proyek, Deleted Succsesfully');
    }
}
