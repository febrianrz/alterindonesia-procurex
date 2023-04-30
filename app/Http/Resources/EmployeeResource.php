<?php

namespace App\Http\Resources;

use Alterindonesia\Procurex\Traits\HasActionTrait;
use App\Libraries\Auth;
use App\Models\Menu;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    use HasActionTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            "id"                    => (int) $this->id,
            "name"                  => (string) $this->nama,
            "gender"                => (string) $this->gender,
            "religion"              => (string) $this->agama,
            "email"                 => (string) $this->email,
            "bod"                   => (string) $this->tgl_lahir,
            "employee_no"           => (string) $this->emp_no,
            "employee_grade"        => (string) $this->emp_grade,
            "employee_grade_title"  => (string) $this->emp_grade_title,
        ];
    }
}
