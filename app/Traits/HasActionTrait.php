<?php
namespace App\Traits;


use Illuminate\Support\Facades\Route;

trait HasActionTrait {

    public function getIsCanEdit(): bool
    {
        return true;
    }

    public function getIsCanDelete(): bool
    {
        return true;
    }

   public function getActions() {
       $mainRoute = Route::currentRouteName();
       return [
          'update'      => $this->getIsCanEdit() ? route($mainRoute)."/".$this->id : null,
          'delete'      => $this->getIsCanDelete() ? route($mainRoute)."/".$this->id : null,
       ];
   }
}
