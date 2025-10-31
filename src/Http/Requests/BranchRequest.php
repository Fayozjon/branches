<?php

namespace Botble\Branches\Http\Requests;

use Botble\Support\Http\Requests\Request;

class BranchRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'history' => ['nullable'],
            'description' => ['nullable'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['nullable'],
        ];
    }
}
