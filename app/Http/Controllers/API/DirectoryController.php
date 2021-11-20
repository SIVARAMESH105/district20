<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Directories;
use App\Helpers\CommonHelper;

/**
 * Class DirectoryController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Http\Controllers\Controller
 * @package App\Models\Directories
 * @package App\Helpers\CommonHelper
 */
class DirectoryController extends Controller 
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
     * This function is used to get directories
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getDirectories(Request $request) {
        if($request->directory_id) {
            $query = Directories::where('id', $request->directory_id);
        } else {
            if($request->type || $request->chapter_id || $request->state) {
                $query = (new Directories)->getDirectoryByTypeAndChapterId($request->type, $request->chapter_id, $request->state);
            } else {
                $query = new Directories;
            }
        }
        $directories = CommonHelper::customPagination($query);
        if(count($directories)) {
            return response()->json([
                                        'status'=>1,
                                        'message' => 'Directories are available',
                                        'base_url' => $this->base_url,
                                        'data' => $directories
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'data' => [],
                                        'message'=>'Empty directory!.',
                                    ], $this->errorCode);
        }
    }
    /**
     * This function is used to get directories
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getChapterDirectoryList(Request $request) {
        $query = (new Directories)->getDirectoryByTypeAndChapterId(4, $request->chapter_id);
        $directories = CommonHelper::customPagination($query);
        if(count($directories)) {
            return response()->json([
                                        'status'=>1,
                                        'message' => 'Chapter Directories are available',
                                        'base_url' => $this->base_url,
                                        'data' => $directories
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'data' => [],
                                        'message'=>'Empty directory!.',
                                    ], $this->errorCode);
        }
    }
    
}