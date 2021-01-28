<?php

namespace App\Http\Controllers;

use App\Models\Step;
use App\Models\Voyage;
use App\Http\Requests\StoreStepRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class VoyageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('voyages.list');
    }


    protected function getTripTypes() {
         return collect(['' => 'Select Type', 'train' => 'Train', 'bus' => 'Bus', 'car' => 'Car', 'plane' => 'Plane']) ;
    }

    public function getVoyages(Request $request)
    {
        if ($request->ajax()) {
            $data = Voyage::latest()
                ->with(['steps' => function ($query)  {
                    $query->orderBy('departure_date', 'asc');
                }])
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('nb_of_steps', function ($voyage) {
                    return sizeof($voyage->steps);
                })
                ->editColumn('city_from_to', function ($voyage) {
                    return $voyage->city_from_to;
                })
                ->editColumn('date_from_to', function ($voyage) {
                   return $voyage->date_from_to;
                })
                ->editColumn('created_at', function ($voyage) {
                    return $voyage->created_at->format('Y-m-d H:i'); // human readable format
                })
                ->editColumn('updated_at', function ($voyage) {
                    return $voyage->updated_at->format('Y-m-d H:i'); // human readable format
                })
                ->addColumn('action', function($voyage){
                    $actionBtn = '<div class="btn-group">'
                        . '<a href="'. route('voyages.edit', [$voyage]) .'" class="edit btn btn-success btn-sm">Edit</a>'
                        . '<a href="'. route('voyages.destroy', [$voyage]) .'" class="delete btn btn-danger btn-sm">Delete</a>'
                        . '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getSteps(Request $request, $voyageId)
    {
        if ($request->ajax()) {

            $voyage = Voyage::firstOrNew(['id' => $voyageId]) ;

            $data = $voyage->steps()->orderBy('departure_date', 'ASC')->get();


            return Datatables::of($data)
                ->addIndexColumn()

                ->editColumn('departure_date', function ($voyage) {
                    return Carbon::parse($voyage->departure_date)->format('Y-m-d H:i'); // human readable format
                })
                ->editColumn('arrival_date', function ($voyage) {
                    return Carbon::parse($voyage->arrival_date)->format('Y-m-d H:i'); // human readable format
                })
                ->addColumn('action', function($row) use ($voyage, $data){
                    $actionBtn = $actionBtn = '<div class="btn-group">'
                        . '<a href="javascript:;" class="edit btn btn-success btn-sm">Edit</a>'
                        . '<a href="'. route('voyages.steps.delete', ['voyage'=>$voyage, 'id' => $row->id]) .'" class="delete btn btn-danger btn-sm">Delete</a>'
                        . '</div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * @param Request $request
     * @param $voyageId
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteStep(Request $request, $voyageId, $id) {
        if ($request->ajax()) {
            $voyage = Voyage::find($voyageId) ;
            $voyage->steps()->whereId($id)->delete() ;

            // if this was the last entry then delete the parent voyage because it should not contain empty array
            if (count($voyage->steps) == 0) {
                $voyage->delete() ;
            }

            return response()
                ->json(['message' => 'Delete', 'status' => true]) ;
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $voyage = new Voyage() ;
        $tripTypes = $this->getTripTypes();
        return view('voyages.create', compact('voyage','tripTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreStepRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStepRequest $request)
    {
        $voyage = new Voyage() ;
        $voyage->reference = Str::random(10) ;
        $voyage->save() ;

        $stepData = $request->only(['type', 'transport_number', 'departure_date', 'arrival_date', 'departure', 'arrival', 'seat', 'gate', 'baggage_drop']) ;

        $voyage->steps()->create($stepData) ;

        return redirect()->route('voyages.edit', [$voyage]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Voyage  $voyage
     * @return \Illuminate\Http\Response
     */
    public function show(Voyage $voyage)
    {
        $tripTypes = $this->getTripTypes();
        return view('voyages.edit', compact('voyage','tripTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Voyage  $voyage
     * @return \Illuminate\Http\Response
     */
    public function edit(Voyage $voyage)
    {
        $tripTypes = $this->getTripTypes();
        return view('voyages.edit', compact('voyage','tripTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreStepRequest $request
     * @param  \App\Models\Voyage  $voyage
     * @return \Illuminate\Http\Response
     */
    public function update(StoreStepRequest $request, Voyage $voyage)
    {
        $stepData = $request->only(['type', 'transport_number', 'departure_date', 'arrival_date', 'departure', 'arrival', 'seat', 'gate', 'baggage_drop']) ;

        $stepId = $request->input('step_id', 0) ;

        // Validate Step data to other existing steps
        $errorMsg = $this->validateDates($request, $voyage, $stepId) ;
        if (!empty($errorMsg)) {
            return back()->withErrors($errorMsg)->withInput();
        }

        //we are editing existing step
        if ($stepId > 0) {
            Step::find($stepId)->update($stepData) ;
        } else {
            $voyage->steps()->create($stepData) ;
        }

        return redirect()->route('voyages.edit', [$voyage]);
    }

    protected function validateDates(StoreStepRequest $request, Voyage $voyage, $stepId) {
        $errorMsg = '' ;
        $departure_date = $request->input('departure_date') ;
        $cnt1 = $voyage->steps()
            ->where('departure_date' , '<', $departure_date)
            ->where('arrival_date', '>', $departure_date)
            ->where('id', '!=', $stepId)
            ->count();

        if ($cnt1 > 0) {
            $errorMsg = 'Please verify your departure date. It should be outside the date range of other steps' ;
        }

        $arrival_date = $request->input('arrival_date') ;
        $cnt2 = $voyage->steps()
            ->where('departure_date' , '<', $arrival_date)
            ->where('arrival_date', '>', $arrival_date)
            ->where('id', '!=', $stepId)
            ->count();

        if ($cnt2 > 0) {
            $errorMsg ='Please verify your arrival date. It should be outside the date range of other steps' ;
        }

        return $errorMsg ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Voyage  $voyage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Voyage $voyage)
    {
        $voyage->delete();
        return redirect(route('voyages.index'));
    }
}
