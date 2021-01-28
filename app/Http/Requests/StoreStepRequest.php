<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * This validation is applied for new voyage
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'type' => 'required',
            'transport_number' => 'required',
            'departure' => 'required',
            'arrival' => 'required',
            'departure_date' => 'required|date',
            'arrival_date' => 'required|date|after:departure_date'
        ];

        $stepId = $this->request->get('step_id') ;
        if ($stepId > 0) {
            $rules['arrival'] = ['required','string','min:1','max:255',
                Rule::unique('steps')
                    ->where('voyage_id', $this->request->get('voyage_id'))
                    ->ignore($stepId)
            ];

            $rules['departure'] = ['required','string','min:1','max:255', 'different:arrival',
                Rule::unique('steps')
                    ->where('voyage_id', $this->request->get('voyage_id'))
                    ->ignore($stepId)];

        } else  {
            $rules['arrival'] = ['required','string','min:1','max:255',
                Rule::unique('steps')
                    ->where('voyage_id', $this->request->get('voyage_id'))] ;
            $rules['departure'] = ['required','string','min:1','max:255', 'different:arrival',
                Rule::unique('steps')
                    ->where('voyage_id', $this->request->get('voyage_id'))];
        }

        return $rules ;
    }
}
