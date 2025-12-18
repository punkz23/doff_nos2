<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SoaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'soa_no'=>$this->soa_no,
            'soa_from'=>date('Y/m/d',strtotime($this->soa_from)),
            'soa_to'=>date('Y/m/d',strtotime($this->soa_to)),
            'tdate'=>date('Y/m/d',strtotime($this->transactiondate)),
            'total_amount'=>$this->orcr_detail->sum('withdraw'),
            'balance'=>($this->orcr_detail->sum('withdraw')+$this->adjustment_add->sum('adjustment_amount'))-($this->orcr_detail->sum('deposit')+$this->adjustment_less->sum('adjustment_amount')),
            'soa_duedate'=>$this->soa_duedate!=null ? date('m/d/Y',strtotime($this->soa_duedate)) : '',
            'transactioncode'=>$this->transactioncode,
            'waybill_no'=>$this->waybill_no,
            'ttype'=>substr($this->transactioncode,0,2),
        ];
    }
}
