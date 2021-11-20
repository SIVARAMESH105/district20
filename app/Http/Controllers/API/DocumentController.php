<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Documents;
use App\Models\DocumentType;
use App\Helpers\CommonHelper;

/**
 * Class DocumentController
 * namespace App\Http\Controllers
 * @package Illuminate\Http\Request
 * @package App\Http\Controllers\Controller
 * @package App\Models\Documents
 * @package App\Models\DocumentType
 * @package App\Helpers\CommonHelper
 */
class DocumentController extends Controller 
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
    public function getDocuments(Request $request) {
        if($request->document_id) {
            $documents = (new Documents)->getDocumentById($request->document_id);
        } else if($request->chapter_id || $request->state_id || $request->union_id || $request->document_type || $request->q) {
            $query = (new Documents)->getDocuments($request->chapter_id, $request->state_id, $request->union_id, $request->document_type, $request->q);
            $documents = CommonHelper::customPagination($query);
        } else {
            $query = new Documents;
            $documents = CommonHelper::customPagination($query);
        }
        if(count($documents)) {
            return response()->json([
                                        'status'=>1,
                                        'message' => 'Documents are available',
                                        'base_url' => $this->base_url,
                                        'data' => $documents
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'data' => [],
                                        'message'=>'Empty document!.',
                                    ], $this->errorCode);
        }
    }
    /**
     * This function is used to get document types
     *
     * @param Request $request
     * @return JSON
     * @author Techaffinity:vinothcl
     */
    public function getDocumentTypes(Request $request) {
        $documentTypes = DocumentType::all();
        if(count($documentTypes)) {
            return response()->json([
                                        'status'=>1,
                                        'base_url' => $this->base_url,
                                        'message' => 'Documents types are available',
                                        'data' => $documentTypes
                                     ], $this->successCode);
        } else { 
            return response()->json([
                                        'status'=>0,
                                        'base_url' => $this->base_url,
                                        'message'=>'Empty document!.',
                                    ], $this->errorCode);
        }
    }
}