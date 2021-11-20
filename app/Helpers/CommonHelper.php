<?php
namespace App\Helpers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Models\Directories;
use App\Models\DirectoriesImport;
use App\Models\Notification;
use App\Models\NotificationAction;
use App\Models\DocumentsImport;
use App\Models\DocumentLocationImport;
use App\Models\Documents;
use App\Models\DocumentLocation;
use App\Models\Chapter;
use App\Models\States;
use App\Models\Union;
use App\Models\DocumentType;
use DB;
use Auth;
use Log;

/**
 * Class CommonHelper
 * namespace App\Helpers
 * @package Illuminate\Http\Request
 */
class CommonHelper
{    
    /**
     * This function is used to assign pagination
     *
     * @param Request $request
     * @return array
     * @author Techaffinity:vinothcl
    */
    public static function customPagination($query) {
        $info = \Request::all();
        $is_pagination = isset($info['pagination'])?$info['pagination']:'';
        if($is_pagination) {
            $page = isset($info['page'])?$info['page']:1;
            $take = 10; // count
            $skip = 0;
            if ($page > 1) {
                $skip = $take * ($page - 1);
            }   
            if ($skip) {
                $data = $query->skip($skip)->take($take)->get();
            } else {
                $data = $query->take($take)->get();
            }         
            return $data;
        } else {
            return $query->get();
        }
    }
    /**
     * This function is used to import contractor or chapter directory
     *
     * @param Request $request
     * @return array
     * @author Techaffinity:vinothcl
    */
    public static function importContractorElseChapterDirectory($request, $type) {
        if (!$request->hasFile('excel_file')) {
            return false;
        }
        if (!in_array($request->file('excel_file')->getClientMimeType(), ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return false;
        }
        $reader = IOFactory::load($request->file('excel_file')->getRealPath());
        $sheetCount = $reader->getSheetCount();
        if($sheetCount<0){
            return false;
        }
        $data = [];
        $file_cols = array(    
                            'B' => 'Company',
                            'C' => 'Contact',
                            'D' => 'Position',
                            'E' => 'Address',
                            'F' => 'City',
                            'G' => 'State',
                            'H' => 'Zipcode',
                            'I' => 'Phone',
                            'J' => 'Fax',
                            'K' => 'Email',
                            'L' => 'Website',
                            'M' => 'Photo (optional)',
                        );
        $db_cols = array(
                            'B' => 'name',
                            'C' => 'contact',
                            'D' => 'position',
                            'E' => 'address',
                            'F' => 'city',
                            'G' => 'state',
                            'H' => 'zipcode',
                            'I' => 'phone',
                            'J' => 'fax',
                            'K' => 'email',
                            'L' => 'website',
                            'M' => 'profile_pic',
                        );
        $col = [];
        $rows = [];
        for($v=0; $v<$sheetCount; $v++) {
            $worksheet = $reader->getSheet($v);
            foreach ($worksheet->getRowIterator() as $excel_row) {       
                $cellIterator = $excel_row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                if ($excel_row->getRowIndex() == 1) {
                    foreach ($cellIterator as $indexVal=>$cell) {
                        if($indexVal =='A') {
                            $sheetName = (preg_replace('/\p{C}+/u', '', $cell->getValue()) ? : '');
                        }
                    }
                    if($sheetName)
                        $data[$sheetName]['cols'] = $file_cols;
                } else {
                    foreach ($cellIterator as $index=>$cell) {
                        if($index!='A') {
                            $val = (preg_replace('/\p{C}+/u', '', $cell->getValue()) ? : '');
                            if($val) {
                                if($index == 'M')
                                    $rows[$index] = 'directories/'.$val;
                                else
                                    $rows[$index] = $val;
                            } else {
                                continue;
                            }
                                                 
                        }
                    }
                    if(count($rows) && $sheetName)
                        $data[$sheetName]['rows'][] = $rows;
                }
                $col = [];
                $rows = [];
            }
        }
        $reader->disconnectWorksheets();
        unset($reader);
        foreach($data as $chapterVal=>$dataVal)
        {
            if(!self::getChapterId($chapterVal)){
                return false;
            }
        }
        $created = false;
        DB::table('directories_import')->delete();
        foreach($data as $chapterVal=>$dataVal)
        {
            foreach ($dataVal['rows'] as $rows) {
                $insertArray = [];
                foreach ($rows as $key=>$rowVal) {
                    $insertArray[$db_cols[$key]] = $rowVal;
                    $insertArray['chapter_id'] = self::getChapterId($chapterVal);
                    $insertArray['type'] = $type;
                    $insertArray['created_by'] = Auth::id();
                    $insertArray['updated_by'] = Auth::id();               
                }
                $created = DirectoriesImport::create($insertArray);
            }            
        }        
        if($created){
            $directories = DirectoriesImport::get()->toArray();
            foreach($directories as $directory){
                unset($directory['id']);
                $created = Directories::create($directory);
            }
            DB::table('directories_import')->delete();
            $created = true;
        } 
        return $created;
    }
    /**
     * This function is used to import Jatc directory
     *
     * @param Request $request
     * @return array
     * @author Techaffinity:vinothcl
    */
    public static function importJatcDirectory($request, $type) {
        if (!$request->hasFile('excel_file')) {
            return false;
        }
        if (!in_array($request->file('excel_file')->getClientMimeType(), ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return false;
        }
        $reader = IOFactory::load($request->file('excel_file')->getRealPath());
        $sheetCount = $reader->getSheetCount();
        if($sheetCount<0){
            return false;
        }
        $data = [];
        $file_cols = array(    
                            'A' =>'Chapter Name',
                            'B' =>'Name',
                            'C' =>'Position',
                            'D' =>'Address',
                            'E' =>'City',
                            'F' =>'State',
                            'G' =>'Zipcode',
                            'H' =>'Phone',
                            'I' =>'Fax',
                            'J' =>'Email',
                            'K' =>'Website',
                            'L' =>'photo (optional)'
                        );
        $db_cols = array(
                            'A' =>'chapter_id',                            
                            'B' => 'contact',
                            'C' => 'position',
                            'D' => 'address',
                            'E' => 'city',
                            'F' => 'state',
                            'G' => 'zipcode',
                            'H' => 'phone',
                            'I' => 'fax',
                            'J' => 'email',
                            'K' => 'website',
                            'L' => 'profile_pic',
                        );
        $col = [];
        $rows = [];
        $worksheet = $reader->getSheet(0);
        $data['cols'] = $file_cols;
        foreach ($worksheet->getRowIterator() as $excel_row) {       
            $cellIterator = $excel_row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            if ($excel_row->getRowIndex() != 1) {
                foreach ($cellIterator as $index=>$cell) {
                    $val = (preg_replace('/\p{C}+/u', '', $cell->getValue()) ? : '');
                    if($val) {
                        if($index == 'M'){
                            $rows[$db_cols[$index]] = 'directories/'.$val;
                        } else if($index == 'A'){
                            $rows[$db_cols[$index]] = self::getChapterId(trim($val));
                        }else{
                            $rows[$db_cols[$index]] = $val;
                        }                            
                    } else {
                        continue;
                    }
                }
                if(count($rows))
                    $data['rows'][] = $rows;
            }
            $col = [];
            $rows = [];
        }        
        $reader->disconnectWorksheets();
        unset($reader);
        $created = false;
        DB::table('directories_import')->delete();
        foreach($data['rows'] as $row) {
            $row['type'] = $type;
            $row['created_by'] = Auth::id();
            $row['updated_by'] = Auth::id();
            $created = DirectoriesImport::create($row);               
        }        
        if($created){
            $directories = DirectoriesImport::get()->toArray();
            foreach($directories as $directory){
                unset($directory['id']);
                $created = Directories::create($directory);
            }
            DB::table('directories_import')->delete();  
            $created = true;          
        } 
        return $created;
    }

    /**
     * This function is used to import Ibew directory
     *
     * @param Request $request
     * @return array
     * @author Techaffinity:vinothcl
    */
    public static function importIbewDirectory($request, $type) {
        if (!$request->hasFile('excel_file')) {
            return false;
        }
        if (!in_array($request->file('excel_file')->getClientMimeType(), ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return false;
        }
        $reader = IOFactory::load($request->file('excel_file')->getRealPath());
        $sheetCount = $reader->getSheetCount();
        if($sheetCount<0){
            return false;
        }
        $data = [];
        $file_cols = array(    
                            'B' => 'Company',
                            'C' => 'Contact',
                            'D' => 'Position',
                            'E' => 'Address',
                            'F' => 'City',
                            'G' => 'State',
                            'H' => 'Zipcode',
                            'I' => 'Phone',
                            'J' => 'Fax',
                            'K' => 'Email',
                            'L' => 'Website',
                            'M' => 'Photo (optional)',
                        );
        $db_cols = array(
                            'B' => 'name',
                            'C' => 'contact',
                            'D' => 'position',
                            'E' => 'address',
                            'F' => 'city',
                            'G' => 'state',
                            'H' => 'zipcode',
                            'I' => 'phone',
                            'J' => 'fax',
                            'K' => 'email',
                            'L' => 'website',
                            'M' => 'profile_pic',
                        );
        $col = [];
        $rows = [];
        for($v=0; $v<$sheetCount; $v++) {
            $worksheet = $reader->getSheet($v);
            foreach ($worksheet->getRowIterator() as $excel_row) {       
                $cellIterator = $excel_row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                if ($excel_row->getRowIndex() == 1) {
                    foreach ($cellIterator as $indexVal=>$cell) {
                        if($indexVal =='A') {
                            $sheetName = (preg_replace('/\p{C}+/u', '', $cell->getValue()) ? : '');
                        }
                    }
                    if($sheetName)
                        $data[$sheetName]['cols'] = $file_cols;
                } else {
                    foreach ($cellIterator as $index=>$cell) {
                        if($index!='A') {
                            $val = (preg_replace('/\p{C}+/u', '', $cell->getValue()) ? : '');
                            if($val) {
                                if($index == 'M')
                                    $rows[$index] = 'directories/'.$val;
                                else
                                    $rows[$index] = $val;
                            } else {
                                continue;
                            }
                                                 
                        }
                    }
                    if(count($rows) && $sheetName)
                        $data[$sheetName]['rows'][] = $rows;
                }
                $col = [];
                $rows = [];
            }
        }
        //return $data;
        $reader->disconnectWorksheets();
        unset($reader);
        foreach($data as $districtVal=>$dataVal)
        {
            if(!self::getDistrictId(str_replace(" ","",$districtVal))){
                return false;
            }
        }
        $created = false;
        DB::table('directories_import')->delete();
        foreach($data as $districtVal=>$dataVal)
        {
            foreach ($dataVal['rows'] as $rows) {
                $insertArray = [];
                foreach ($rows as $key=>$rowVal) {
                    $insertArray[$db_cols[$key]] = $rowVal;
                    $insertArray['district'] = self::getDistrictId(str_replace(" ","",$districtVal));
                    $insertArray['type'] = $type;
                    $insertArray['created_by'] = Auth::id();
                    $insertArray['updated_by'] = Auth::id();               
                }
                $created = DirectoriesImport::create($insertArray);
            }            
        }        
        if($created){
            $directories = DirectoriesImport::get()->toArray();
            foreach($directories as $directory){
                unset($directory['id']);
                $created = Directories::create($directory);
            }
            DB::table('directories_import')->delete();
            $created = true;
        } 
        return $created;
    }  
    /**
     * This function is used to get chapter ID
     *
     * @param Request $request
     * @return int
     * @author Techaffinity:vinothcl
    */
    public static function getChapterId($chapterVal) {
        $chapter = DB::table('chapters')->where('chapter_name', 'LIKE', '%'.$chapterVal.'%')->first();
        return $chapter ? $chapter->id : '';
    }
    /**
     * This function is used to get district ID
     *
     * @param Request $request
     * @return int
     * @author Techaffinity:vinothcl
    */
    public static function getDistrictId($chapterVal) {
        $district = DB::table('districts')->where('name', 'LIKE', '%'.$chapterVal.'%')->first();
        return $district ? $district->id : '';
    }
    /**
     * This function is used to get total un seen notification count by userId
     *
     * @param Request $request
     * @return int
     * @author Techaffinity:vinothcl
    */
    public static function getUnSeenNotificationCountByUserId() {
        $totalCount = Notification::count();
        $seenCount = NotificationAction::where('user_id', Auth::id())->where('seen', 1)->count();
        return $totalCount - $seenCount;
    }  
    /**
     * This function is used to import documents
     *
     * @param Request $request
     * @return array
     * @author Techaffinity:vinothcl
    */
    public static function importDocuments($request, $chapter) {
        self::createimportDocumentsTables();
        if (!$request->hasFile('excel_file')) {
            return false;
        }
        if (!in_array($request->file('excel_file')->getClientMimeType(), ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return false;
        }
        $reader = IOFactory::load($request->file('excel_file')->getRealPath());
        $sheetCount = $reader->getSheetCount();
        if($sheetCount<0){
            return false;
        }
        $data = [];

        $file_cols = array(    
                            'A' => 'Document title',
                            'B' => 'Document full path',
                            'C' => 'Document Type',
                            'D' => 'Display Title',
                            'E' => 'Expiration Date',
                            'F' => 'signature required'
                        );
        $file_cols_Status = array(    
                            'A' => '',
                            'B' => '',
                            'C' => '',
                            'D' => '',
                            'E' => '',
                            'F' => ''
                        );
        $db_cols = array(
                            'A' => 'document_path',
                            'B' => 'document_full_path',
                            'C' => 'document_type',
                            'D' => 'document_name',
                            'E' => 'expiration_date',
                            'F' => 'signature_required'                            
                        );
        $col = [];
        $rows = [];
        for($v=0; $v<$sheetCount; $v++) {
            $worksheet = $reader->getSheet($v);
            foreach ($worksheet->getRowIterator() as $excel_row) {  
                $cellIterator = $excel_row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                 if($v ==0)
                 { 
                    $sheetName = 'documents';
                    if ($excel_row->getRowIndex() == 1) {
                        foreach ($cellIterator as $indexVal=>$cell) {
                            if(
                                $indexVal =='A' || 
                                $indexVal =='B' || 
                                $indexVal =='C' || 
                                $indexVal =='D' || 
                                $indexVal =='E' || 
                                $indexVal =='F'
                                ) {
                                $data[$sheetName]['cols'][$indexVal] = $file_cols[$indexVal];
                                $data[$sheetName]['colStatus'][$indexVal] = $file_cols_Status[$indexVal];
                            } else {
                                if($cell->getValue()){
                                    $data[$sheetName]['cols'][$indexVal] = trim($cell->getValue());
                                    $data[$sheetName]['colStatus'][$indexVal] = self::checkISUnionOrState(trim($cell->getValue()));
                                }                                    
                                else {
                                    continue;
                                }                                    
                            }
                        }                            
                    } else {
                        foreach ($cellIterator as $index=>$cell) {
                            $val = (preg_replace('/\p{C}+/u', '', $cell->getValue()) ? : '');
                            if($val) {
                                $rows[$index] = $val;
                            } else {
                                continue;
                            }
                        }
                    }
                    if(count($rows) && $sheetName)
                        $data[$sheetName]['rows'][] = $rows;
                 }
                 if($v==1){
                    foreach ($cellIterator as $index=>$cell) {
                        $val = (preg_replace('/\p{C}+/u', '', $cell->getValue()) ? : '');
                        if($val) {
                            $data['documentsTypes'][] = $val;
                        } else {
                            continue;
                        }
                    }
                 }
                $col = [];
                $rows = [];
            }
        }
        //return $data;
        $reader->disconnectWorksheets();
        unset($reader);
        $created = false;
        DB::table('documents_import')->delete();
        DB::table('document_locations_import')->delete();
        $cols = $data['documents']['cols'];
        $colStatus = $data['documents']['colStatus'];
        $rows = $data['documents']['rows'];
        $documentsTypes = $data['documentsTypes']; // here we can create new documentsTypes values
        //Log::channel('db_migration')->info($data['documents']);    
        foreach($rows as $row) {
            $insertArray = [];            
            foreach ($row as $key=>$rowVal) {
                if(isset($db_cols[$key])){
                    if($key == 'C')
                        $insertArray[$db_cols[$key]] = self::getDocumentTypeID($rowVal);
                    else if($key == 'E')
                        $insertArray[$db_cols[$key]] = self::excelDateToPhp($rowVal);
                    else if($key == 'F')
                        $insertArray[$db_cols[$key]] = $rowVal?1:0;
                    else
                        $insertArray[$db_cols[$key]] = $rowVal;
                }
                $insertArray['created_by'] = Auth::id();
                $insertArray['updated_by'] = Auth::id();   
                $insertArray['created_at'] = date('Y-m-d H:i:s');
                $insertArray['updated_at'] = date('Y-m-d H:i:s');               
            }
            $dID = DocumentsImport::insertGetId($insertArray);            
            foreach ($row as $key=>$rowVal) {
                if($key != 'A' && $key != 'B' && $key != 'C' && $key != 'D' && $key != 'E' && $key != 'F') {                    
                    //$locArray = self::getLocationValuesByName($cols[$key], $colStatus[$key], $chapter);  
                    $locArray = self::getLocationValuesByName($cols, $colStatus, $key);
                    $locArray['document_id'] = $dID;
                    $locArray['chapter_id'] = $chapter;
                    $locArray['created_at'] = date('Y-m-d H:i:s');
                    $locArray['updated_at'] = date('Y-m-d H:i:s');
                    Log::channel('db_migration')->info($locArray);                  
                    DocumentLocationImport::insertGetId($locArray);
                }
                $created = true;                        
            }
        }        
        // here moving values to live table
        if(self::documentsTableMoveToLive()) {
            DB::statement("DROP TABLE IF EXISTS `documents_bkp`;");
            DB::statement("DROP TABLE IF EXISTS `document_locations_bkp`;");
            DB::statement("DROP TABLE IF EXISTS `documents_import`;");
            DB::statement("DROP TABLE IF EXISTS `document_locations_import`;");
            Log::channel('db_migration')->info('---Documents Import backup tables deleted');
            Log::channel('db_migration')->info('---Documents Imported Successfully');
            return true;
        } else {
            return false;
        }        
    }
    public static function getLocationValuesByName($cols, $colStatus, $key){
        if($colStatus[$key] == 'state') {
            $state = States::where('state_name', $cols[$key])->first();
            return ['state_id'=> $state['id'], 'union_id'=> null];
        } else {
            $lastStateIndexFinal = '';
            $lastStateIndexValue = '';
            foreach($colStatus as $colStatusIndex=>$colStatusVal){
                if($colStatusVal == 'state'){
                    $lastStateIndexValue = $colStatusIndex;
                }
                if($colStatusIndex == $key){
                    $lastStateIndexFinal = $lastStateIndexValue;
                }                
            }
            $stateName = $cols[$lastStateIndexFinal];
            $state = States::where('state_name', $stateName)->first();
            $union = Union::leftJoin('states', 'states.id','=', 'unions.state_id')
                     ->where('union_name', $cols[$key])
                     ->where('state_id', $state['id'])
                     ->select('state_id', 'unions.id')
                     ->first();     
            return ['state_id'=> $union['state_id'], 'union_id'=> $union['id']];
        }
    }
    public static function excelDateToPhp($excelDate){
        return date("Y-m-d H:i:s", ((($excelDate - (25567 + 2)) * 86400 * 1000) / 1000));
    }   

    public static function documentsTableMoveToLive(){
        Log::channel('db_migration')->info('---Documents Import -> moving to live table');
        $imported = false;
        $documents = DB::table('documents_import')->get()->toArray();
        foreach ($documents as $key => $document) {
            $insertArray['document_type'] = $document->document_type;
            $insertArray['document_name'] = $document->document_name;
            $insertArray['document_path'] = $document->document_path;
            $insertArray['document_full_path'] = $document->document_full_path;
            $insertArray['expiration_date'] = $document->expiration_date;
            $insertArray['signature_required'] = $document->signature_required;
            $insertArray['created_by'] = Auth::id();
            $insertArray['updated_by'] = Auth::id();   
            $insertArray['created_at'] = date('Y-m-d H:i:s');
            $insertArray['updated_at'] = date('Y-m-d H:i:s');
            $dID = Documents::insertGetId($insertArray);
            $locations = DocumentLocationImport::where('document_id', $document->id)->get()->toArray();            
            foreach ($locations as $key1=>$location) {
                $loc['document_id'] = $dID;
                $loc['chapter_id'] = $location['chapter_id'];
                $loc['state_id'] = $location['state_id'];
                $loc['union_id'] = $location['union_id'];
                $loc['created_at'] = date('Y-m-d H:i:s');
                $loc['updated_at'] = date('Y-m-d H:i:s');          
                DocumentLocation::insertGetId($loc);
                $imported = true;                        
            }
        }
        Log::channel('db_migration')->info('---Documents Import -> moved to live table');
        return $imported;
    }
    
    public static function checkISUnionOrState($val){
        if(States::where('state_name', $val)->first()){
            return 'state';
        } else if(Union::where('union_name', $val)->first()){
            return 'union';
        } else {
            Log::channel('db_migration')->info('---Unable to find state or union values--->'.$val);
            echo 'Unable to find state or union values';
            exit;
        }
    }
    public static function getDocumentTypeID($dtVal){
        $val = DocumentType::where('document_type_name', $dtVal)->first();
        if($val){
            return $val->id;
        } else {
            Log::channel('db_migration')->info('---new document type created--->'.$dtVal);
            $data['document_type_name'] = $dtVal;
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');   
            return DocumentType::insertGetId($data);
        }
    }
    
    public static function createimportDocumentsTables(){
        Log::channel('db_migration')->info('---Documents Import Tables Started');

        DB::statement("DROP TABLE IF EXISTS `documents_import`;");
        DB::statement("
            CREATE TABLE `documents_import` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `document_type` int(11) DEFAULT NULL,
            `document_name` varchar(255) NOT NULL,
            `document_path` varchar(255) NOT NULL,
            `document_full_path` varchar(255) DEFAULT NULL,
            `expiration_date` datetime DEFAULT NULL,
            `signature_required` tinyint(1) DEFAULT '0',
            `created_by` int(11) NOT NULL,
            `updated_by` int(11) NOT NULL,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            KEY `document_type` (`document_type`),
            KEY `document_name` (`document_name`),
            KEY `document_path` (`document_path`),
            KEY `document_full_path` (`document_full_path`),
            KEY `expiration_date` (`expiration_date`),
            KEY `created_by` (`created_by`),
            KEY `updated_by` (`updated_by`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

        DB::statement("DROP TABLE IF EXISTS `document_locations_import`;");
        DB::statement("
            CREATE TABLE `document_locations_import` (
            `id` int(20) NOT NULL AUTO_INCREMENT,
            `document_id` int(11) NOT NULL,
            `chapter_id` int(11) NOT NULL,
            `state_id` int(11) NOT NULL,
            `union_id` int(11) NULL,
            `created_at` timestamp NULL DEFAULT NULL,
            `updated_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `document_id` (`document_id`),
            KEY `state_id` (`state_id`),
            KEY `union_id` (`union_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        Log::channel('db_migration')->info('---Documents Import Tables created');

        //to delete live document tables
        $deleteLiveDocuments = false;
        //$deleteLiveDocuments = true;
        if($deleteLiveDocuments){
            Log::channel('db_migration')->info('---Documents Live Tables deleted');
            DB::table('document_locations')->delete();
            DB::table('documents')->delete();            
            DB::statement("ALTER TABLE documents AUTO_INCREMENT = 1;");
            DB::statement("ALTER TABLE document_locations AUTO_INCREMENT = 1;");
        }

        DB::statement("DROP TABLE IF EXISTS `documents_bkp`;");
        DB::statement("DROP TABLE IF EXISTS `document_locations_bkp`;");
        DB::statement("
            CREATE TABLE documents_bkp AS SELECT * FROM documents;");
        DB::statement("
            CREATE TABLE document_locations_bkp AS SELECT * FROM document_locations;");

        Log::channel('db_migration')->info('---Documents Import Backup Tables created');
    }    
}