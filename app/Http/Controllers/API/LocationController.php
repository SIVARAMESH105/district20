<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\States;
use App\Models\Union;
use App\Models\StateCode;
use App\Models\District;
use App\Helpers\CommonHelper;

/**
 * Class LocationController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Http\Controllers\Controller
 * @package App\Models\Chapter
 * @package App\Models\States
 * @package App\Models\Union
 * @package App\Models\StateCode
 * @package App\Models\District
 * @package App\Helpers\CommonHelper
 */
class LocationController extends Controller 
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
     * This function is used to get chapters
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getChapters(Request $request) { 
    	if($request->chapter_id) {
            $query = Chapter::where('id', $request->chapter_id)->where('is_active', 1);
    	} else {
            $query = Chapter::where('is_active', 1);
    	}
        $chapters = CommonHelper::customPagination($query);
    	if(count($chapters)) {
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'Chapters are available',
                                        'data' => $chapters
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Empty chapters!.',
                                        'data' => [],
                                    ], $this->errorCode);
        }
    }

    /**
     * This function is used to get states
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getStates(Request $request) {    	
    	if($request->state_id) {
            $query = States::where('id', $request->state_id);
    	} else if($request->chapter_id) {
    		$query = (new States)->getChapterStates($request->chapter_id);
    	} else {
            $query = new States;
    	}
        $states = CommonHelper::customPagination($query);
    	if(count($states)) {
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'States are available',
                                        'data' => $states
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Empty states!.',
                                        'data' => [],
                                    ], $this->errorCode);
        }
    }
    
    /**
     * This function is used to get unions
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getUnions(Request $request) {
        if($request->union_id) {
            $query = Union::where('id', $request->union_id);
        } else if($request->state_id) {
            $query = (new Union)->getStateUnions($request->state_id);
        } else if($request->chapter_id) {
            $query = (new Union)->getChapterUnions($request->chapter_id);
        } else {
            $query = new Union;            
        }
        $union = CommonHelper::customPagination($query);
        if(count($union)) {
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'Unions are available',
                                        'data' => $union
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Empty unions!.',
                                        'data' => [],
                                    ], $this->errorCode);
        }
    } 
    /**
     * This function is used to get State Codes
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getStateCodes(Request $request) {
        $query = new StateCode;
        $stateCodes = CommonHelper::customPagination($query);
        if(count($stateCodes)) {
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'State codes are available',
                                        'data' => $stateCodes
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Empty state codes!.',
                                        'data' => [],
                                    ], $this->errorCode);
        }
    } 
    /**
     * This function is used to get Districts
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getDistricts(Request $request) {
        $query = new District;
        $districts = CommonHelper::customPagination($query);
        if(count($districts)) {
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'Districts are available',
                                        'data' => $districts
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Empty districts!.',
                                        'data' => [],
                                    ], $this->errorCode);
        }
    } 
    
}