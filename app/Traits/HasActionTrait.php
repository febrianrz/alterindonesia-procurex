<?php
namespace App\Traits;


use Illuminate\Support\Facades\Route;

trait HasActionTrait {
   public function getActions() {
       $mainRoute = Route::currentRouteName();
       return [
          'update'      => route($mainRoute)."/".$this->id,
          'delete'      => route($mainRoute)."/".$this->id,
       ];
   }
}
