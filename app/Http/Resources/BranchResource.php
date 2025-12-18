<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Crypt;
class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'branchoffice_no'=>Crypt::encryptString($this->branchoffice_no),
            'branchoffice_description'=>$this->branchoffice_description
        ];
    }
}
