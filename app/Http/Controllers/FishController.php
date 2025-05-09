<?php

namespace App\Http\Controllers;

use App\Models\FishTemplate;
use App\Http\Requests\FishStoreRequest;
use Illuminate\Http\Request;

class FishController extends Controller
{

    private $fish;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FishTemplate $fish)
    {
        $this->middleware('auth');
        $this->fish = $fish;
    }

    /**
     * Index resource
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $fish = FishTemplate::get();

        $fish->each(function ($fish) {
            $fish->append('avatar');
            $fish->append('created');
        });

        return response()->json([
            'data' => $fish
        ]);
    }

    /**
     * Get single resource
     *
     * @param FishTemplate $fish
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($fish) {

        $fish = $this->fish->find($fish);
        $fish->append('avatar');
        $fish->append('avatar_filename');
        $fish->append('created_mm_dd_yyyy');
        $fish->append('created');

        return response()->json([
            'data' => $fish
        ]);
    }

    /**
     * Update single resource
     *
     * @param FishStoreRequest $request
     * @param FishTemplate $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update( FishStoreRequest $request) {

        $fish = $this->fish->find($request->id);
        $fish->fill($request->all());
        $fish->save();

        $fish->append('avatar');

        return response()->json([
            'status' => true,
            'data' => $fish
        ]);
    }

    /**
     * Store new resource
     *
     * @param FishStoreRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( FishStoreRequest $request ) {
        $fish = new FishTemplate;
        $fish->fill($request->all());
        $fish->save();

        return response()->json([
            'status' => true,
            'created' => true,
            'data' => [
                'id' => $fish->id
            ]
        ]);
    }

    /**
     * Destroy single resource
     *
     * @param FishTemplate $client
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($fish) {
        $fish = $this->fish->find($fish);
        $fish->delete();

        return response()->json([
            'status' => true
        ]);
    }

    /**
     * Destroy resources by ids
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroyMass( Request $request ) {
        $request->validate([
            'ids' => 'required|array'
        ]);

        FishTemplate::destroy($request->ids);

        return response()->json([
            'status' => true
        ]);
    }
}
