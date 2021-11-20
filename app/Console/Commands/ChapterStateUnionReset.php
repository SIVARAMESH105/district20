<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\ChapterImport;
use App\Models\StatesImport;
use App\Models\UnionImport;
use App\Models\Chapter;
use App\Models\States;
use App\Models\Union;
use Exception;
use Log;
use DB;

// Note : php artisan ChapterStateUnionReset

class ChapterStateUnionReset extends Command 
{
    /**
    * The console command name.
    *
    * @var string
    */
    protected $name = 'ChapterStateUnionReset';

    protected $signature = 'ChapterStateUnionReset';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Used to reset chapter state and union.';

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle() {
      Log::channel('db_migration')->info('---ChapterStateUnionReset Started');
      $data = array(
                      [
                        'id'=>1,
                        'chapter_name'=>'Northwest Line',
                        'chapter_image'=>'',
                        'is_active'=>0,
                        'states'=>[],
                      ],
                      [
                        'id'=>2,
                        'chapter_name'=>'Western Line',
                        'chapter_image'=>'',
                        'is_active'=>0,
                        'states'=>[],
                      ],
                      [
                        'id'=>3,
                        'chapter_name'=>'Southwestern Line',
                        'chapter_image'=>'/map/Southwestern/Southwestern.png',
                        'is_active'=>1,
                        'states'=>[
                            'Texas' => [
                                        'image' =>'/map/Southwestern/texas-swepco.jpg',
                                        'unions' => [
                                                      'SWEPCO 738', 'SWEPCO 436', '66', '220', '583', '602', '681', '278', '738', '2286'
                                                    ]
                            ],
                            'Kansas' => [
                                        'image' =>'/map/Southwestern/Kansas-Local 304-local95.jpg',
                                        'unions' => ['304']
                            ],
                            'New Mexico' => [
                                        'image' =>'/map/Southwestern/New-Mexico-Local-611.png',
                                        'unions' => ['611']
                            ],
                            'Arizona' => [
                                        'image' =>'/map/Southwestern/Arizona-Local-769.png',
                                        'unions' => ['769']
                            ],
                            'Oklahoma' => [
                                        'image' =>'/map/Southwestern/Oklahoma-Local-1002.png',
                                        'unions' => ['1002']
                            ],
                          ],
                      ],
                      [
                        'id'=>4,
                        'chapter_name'=>'Missouri Valley Line',
                        'chapter_image'=>'/map/Missouri Line/MissouriLine.png',
                        'is_active'=>1,
                        'states'=>[
                                    'North Dakota' => [
                                                        'image' =>'/map/Missouri Line/North-Dakota-Local-714-&-1426.png',
                                                        'unions' => []
                                                      ],
                                    'South Dakota' => [
                                                        'image' =>'/map/Missouri Line/South-Dakota-Local-426-&-1250.png',
                                                        'unions' => ['426', '714', '1250', '1426']
                                                      ],
                                    'Missouri' =>     [
                                                        'image' =>'/map/Missouri Line/Missouri-Local-2-&-53+local95.jpg',
                                                        'unions' => ['2', '53', '95']
                                                      ],
                                    'Iowa' =>         [
                                                        'image' =>'/map/Missouri Line/Iowa-Local-55.png',
                                                        'unions' => ['55']
                                                      ],
                                    'Minnesota' =>    [
                                                        'image' =>'/map/Missouri Line/Minnesota-Local-160.png',
                                                        'unions' => ['160']
                                                      ],
                                    'Wisconsin' =>    [
                                                        'image' =>'/map/Missouri Line/Wisconsin-Local-953-&-2150.png',
                                                        'unions' => ['953', '2150']
                                                      ],
                                    'Nebraska' =>     [
                                                        'image' =>'/map/Missouri Line/Nebraska-Local-1525.png ',
                                                        'unions' => ['1525']
                                                        ],
                                  ],
                      ],
                      [
                        'id'=>5,
                        'chapter_name'=>'Southeastern Line',
                        'chapter_image'=>'/map/southeaastern-SLCC/southeaastern-SLCC.png',
                        'is_active'=>1,
                        'states'=>[
                                  'Louisiana'=>   [
                                                    'image' =>'/map/southeaastern-SLCC/Louisiana-Local-995-swepco.jpg',
                                                    'unions' => ['995', 'SWEPCO 995'],
                                                    ],
                                  'Mississippi'=> [
                                                    'image' =>'/map/southeaastern-SLCC/Mississippi.jpg',
                                                    'unions' => ['852', '903'],
                                                    ],
                                  'Alabama'=>     [
                                                    'image' =>'/map/southeaastern-SLCC/Alabama.jpg',
                                                    'unions' => ['558', '443', '175'],
                                                    ],
                                  'Georgia'=>     [
                                                    'image' =>'/map/southeaastern-SLCC/Georgia.jpg',
                                                    'unions' => ['175', '84'],
                                                    ],
                                  'Florida'=>     [
                                                    'image' =>'/map/southeaastern-SLCC/Florida.jpg',
                                                    'unions' => ['676', '222'],
                                                    ],
                                  'Arkansas'=>    [
                                                    'image' =>'/map/southeaastern-SLCC/Arkansas-Local-swepco.jpg',
                                                    'unions' => ['700', '1516', '295', '301', '436', 'SWEPCO 436', 'SWEPCO 700'],
                                                    ],
                                  'Tennessee'=>   [
                                                    'image' =>'/map/southeaastern-SLCC/Tennessee.jpg',
                                                    'unions' => ['1925', '474', '429', '270', '175', '760', '934'],
                                                    ],
                                  'Carolinas'=>   [
                                                    'image' =>'/map/southeaastern-SLCC/Carolinas.jpg',
                                                    'unions' => ['238', '379', '342', '553', '495', '776']
                                                    ],

                                ]
                      ],
                      [
                        'id'=>6,
                        'chapter_name'=>'American Line Builders',
                        'chapter_image'=>'/map/American Line Builder/American-line-builder.jpg',
                        'is_active'=>1,
                        'states'=>[
                                    'Illinois' =>         [
                                                            'image' =>'/map/American Line Builder/Illinois-Local-51,-145,-196,-309,-649-and-702,-193.jpg',
                                                            'unions' => ['51', '145', '193', '196', '309', '649', '702']
                                                            ],
                                    'Indiana' =>          [
                                                            'image' =>'/map/American Line Builder/Indiana-Local-1393.png',
                                                            'unions' => ['702', '1393']
                                                            ],
                                    'Kentucky' =>         [
                                                            'image' =>'/map/American Line Builder/Kentucky.jpg',
                                                            'unions' => ['369','317']
                                                            ],
                                    'Maryland' =>         [
                                                            'image' =>'/map/American Line Builder/Maryland.jpg',
                                                            'unions' => ['70']
                                                            ],
                                    'Michigan' =>         [
                                                            'image' =>'/map/American Line Builder/Michigan.jpg',
                                                            'unions' => ['17', '876']
                                                            ],
                                    'Ohio' =>             [
                                                            'image' =>'/map/American Line Builder/Ohio.jpg',
                                                            'unions' => ['71', '245']
                                                            ],
                                    'Virginia' =>         [
                                                            'image' =>'/map/American Line Builder/Virginia.jpg',
                                                            'unions' => ['934']
                                                            ],
                                    'Washington, D.C.' => [
                                                            'image' =>'/map/American Line Builder/washington-dc.png',
                                                            'unions' => ['70']
                                                            ],

                                    'West Virginia' =>    [
                                                            'image' =>'/map/American Line Builder/West-Virginia.jpg',
                                                            'unions' => ['317']
                                                            ],
                                                        
                                  ]
                      ],
                      [
                        'id'=>7,
                        'chapter_name'=>'Northeastern Line',
                        'chapter_image'=>'',
                        'is_active'=>0,
                        'states'=>[],
                      ],
                      [
                        'id'=>8,
                        'chapter_name'=>'SELCAT',
                        'chapter_image'=>'',
                        'is_active'=>0,
                        'states'=>[],
                      ],
                      [
                        'id'=>9,
                        'chapter_name'=>'Mountain States Line',
                        'chapter_image'=>'',
                        'is_active'=>0,
                        'states'=>[],
                      ],
                      [
                        'id'=>10,
                        'chapter_name'=>'California Nevada Electrical',
                        'chapter_image'=>'',
                        'is_active'=>0,
                        'states'=>[],
                      ],
                      [
                        'id'=>11,
                        'chapter_name'=>'Alaska Line',
                        'chapter_image'=>'',
                        'is_active'=>0,
                        'states'=>[],
                      ]
                    );
      $this->createImportTables();
      $countUnions = 0;
      //chapter
      foreach ($data as $index => $chapter) {
        $chapterNew = [
                        'id' => $chapter['id'],
                        'chapter_name' => trim($chapter['chapter_name']),
                        'chapter_image' => trim($chapter['chapter_image']),
                        'is_active' => $chapter['is_active'],
                        'created_at' => date('y-m-d H:i:s'),
                        'updated_at' => date('y-m-d H:i:s')
                      ];
        $chapterID = ChapterImport::insertGetId($chapterNew);
        //State
        foreach ($chapter['states'] as $stateName => $stateInfo) {
            $state = [
                        'chapter_id' => $chapterID,
                        'state_name' => trim($stateName),
                        'state_image' => isset($stateInfo['image'])?$stateInfo['image']:'',
                        'created_at' => date('y-m-d H:i:s'),
                        'updated_at' => date('y-m-d H:i:s')
                      ];
            $stateID = StatesImport::insertGetId($state);
            if(isset($stateInfo['unions']) && count($stateInfo['unions'])){
              //Unions
              foreach ($stateInfo['unions'] as $index => $union) {
                $unionVal = [
                            'state_id' => $stateID,
                            'union_name' => trim($union),
                            'created_at' => date('y-m-d H:i:s'),
                            'updated_at' => date('y-m-d H:i:s')
                          ];
                $unionId = UnionImport::insertGetId($unionVal);
                echo $unionId.' ';
                $countUnions++;
              }
            }          
        }
      }
      Log::channel('db_migration')->info('---Totally '.$countUnions.' Created');
      if($unionId!=0){
        Log::channel('db_migration')->info('---Moving chapter, state, union to live table');
        DB::statement("DROP TABLE IF EXISTS `document_locations_bkp_on_last_CSU_Reset`;");
        DB::statement("
          CREATE TABLE `document_locations_bkp_on_last_CSU_Reset` (
          `id` int(20) NOT NULL AUTO_INCREMENT,
          `document_id` int(11) NOT NULL,
          `chapter_id` int(11) NOT NULL,
          `state_id` int(11) NOT NULL,
          `union_id` int(11) DEFAULT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `document_id` (`document_id`),
          KEY `state_id` (`state_id`),
          KEY `union_id` (`union_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
              DB::statement("ALTER TABLE document_locations_bkp_on_last_CSU_Reset AUTO_INCREMENT = 1;");
        DB::statement("INSERT INTO document_locations_bkp_on_last_CSU_Reset SELECT * FROM document_locations;");
        

        DB::table('document_locations')->delete();
        DB::table('unions')->delete();
        DB::table('states')->delete();
        DB::table('chapters')->delete();

        DB::statement("ALTER TABLE chapters AUTO_INCREMENT = 1;");
        DB::statement("ALTER TABLE states AUTO_INCREMENT = 1;");
        DB::statement("ALTER TABLE unions AUTO_INCREMENT = 1;");
        DB::statement("ALTER TABLE document_locations AUTO_INCREMENT = 1;");

        DB::statement("INSERT INTO chapters SELECT * FROM chapters_import;");
        DB::statement("INSERT INTO states SELECT * FROM states_import;");
        DB::statement("INSERT INTO unions SELECT * FROM unions_import;");

        DB::statement("DROP TABLE IF EXISTS `unions_import`;");
        DB::statement("DROP TABLE IF EXISTS `states_import`;");
        DB::statement("DROP TABLE IF EXISTS `chapters_import`;");        
        
        Log::channel('db_migration')->info('---Moving chapter, state, union to live table - completed');
      }
    }
    public function createImportTables() {
      Log::channel('db_migration')->info('---Import Tables Started');
      
      DB::statement("DROP TABLE IF EXISTS `chapters_import`;");
      DB::statement("
        CREATE TABLE `chapters_import` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `chapter_name` varchar(128) NOT NULL,
          `chapter_image` varchar(128) NOT NULL,
          `is_active` tinyint(1) DEFAULT '0',
          `created_at` datetime DEFAULT NULL,
          `updated_at` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `chapter_name` (`chapter_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
      DB::statement("ALTER TABLE chapters_import AUTO_INCREMENT = 1;");

      DB::statement("DROP TABLE IF EXISTS `states_import`;");
      DB::statement("
        CREATE TABLE `states_import` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `chapter_id` int(11) NOT NULL,
          `state_name` varchar(128) NOT NULL,
          `state_image` varchar(128) DEFAULT NULL,
          `created_at` datetime DEFAULT NULL,
          `updated_at` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `chapter_id` (`chapter_id`),
          KEY `state_name` (`state_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
      DB::statement("ALTER TABLE states_import AUTO_INCREMENT = 1;");

      DB::statement("DROP TABLE IF EXISTS `unions_import`;");
      DB::statement("
        CREATE TABLE `unions_import` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `state_id` int(11) NOT NULL,
          `union_name` varchar(128) NOT NULL,
          `created_at` datetime DEFAULT NULL,
          `updated_at` datetime DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `state_id` (`state_id`),
          KEY `union_name` (`union_name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
      DB::statement("ALTER TABLE unions_import AUTO_INCREMENT = 1;");
        Log::channel('db_migration')->info('---Import Tables Created');
    }
}