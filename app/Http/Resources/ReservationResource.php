<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'trainer' => $this->trainer->name,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];
    }
}
