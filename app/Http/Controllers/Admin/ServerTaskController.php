<?php
namespace App\Http\Controllers\Admin;
use App\Repositories\LaboratoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServerTaskController extends AdminController
{	
    public function __construct()
    {
       
    }
    public function flatLaboratoriesProducts(LaboratoryRepository $laboratoryRepository)
    {
        $laboratories = $laboratoryRepository->gets();
        foreach ($laboratories as $key => $laboratory) {
            if($laboratory->user_id !='0'){
                continue;
            }
            if(!$laboratory->customized && !$laboratory->collection_product_id){
                $product = $laboratory->products->first();
                $this->output_msg($laboratory->title);
                $laboratory->update(['collection_product_id' => $product ? $product->id : 0]);
                $laboratory_products = [];
                foreach ($product->collections as $key => $collection_product) {
                    array_push($laboratory_products, $collection_product->id);
                }
                $laboratory->products()->sync($laboratory_products);
            }
        }
    }
    private function output_msg($msg){
        echo $msg.'<br>';
        ob_flush(); 
        flush();
    }
   
}
