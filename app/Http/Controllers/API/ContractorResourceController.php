<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\ContractorResource;
use App\Helpers\CommonHelper;

/**
 * Class ContractorResourceController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Http\Controllers\Controller
 * @package App\Models\ContractorResource
 * @package App\Helpers\CommonHelper
 */
class ContractorResourceController extends Controller 
{
    public $successCode = 200;
    public $errorCode = 401;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->base_url = url('/').'/';
    }
    /**
     * This function is used to get contractor resource
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getContractorResource(Request $request) {
        if($request->resource_id) {
            $query = ContractorResource::where('id', $request->resource_id);
        } else {
            if($request->chapter_id) {
                $query = ContractorResource::where('chapter_id', $request->chapter_id);
            } else {
                $query = new ContractorResource;
            }
    	}
        $resource = CommonHelper::customPagination($query);
    	if(count($resource)) {
            return response()->json([
                                        'status'=>1,
                                        'message' => 'Contractor resource are available',
                                        'base_url' => $this->base_url,
                                        'data' => $resource
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'data' => [],
                                        'message'=>'Empty contractor resource!.',
                                    ], $this->errorCode);
        }
    }
}