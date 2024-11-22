<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Depot_comment;
use App\Enums\CommentTypes;

class DepotComService {

    public function add(string $comuser, int $depot, string $comtype, string $user,?string $field,?string $up_value,string $status,string $meesg): void
    {
       

        switch ($comtype) {
            case CommentTypes::DEPOTCRE:
                $msg = "Created $depot depot by $user";
                break;
            case CommentTypes::DEPOTUPD:
                if($up_value){
                    $msg = "Update $field field to $up_value by $user on $depot depot";
                }else{
                    $msg = "Update $field field empty by $user on $depot depot";
                }
                break;

            case CommentTypes::DEPOTSYNC:
                $msg = "ERP Sync by $user on $depot depot";
                break;

            case CommentTypes::DEPOTSYNCSTATUS:
                if($meesg == ''){
                    $msg = "ERP Sync is $status done by $user on $depot depot";
                }else{
                    $msg = "ERP Sync is $status with error  $meesg done by $user on $depot depot";
                }
                break;

            case CommentTypes::DEPOTUSERCOM:
                $msg = $meesg;
                break;
                
            default:
                $msg = "";
        }

        $seq_key = DB::select('SELECT DEPOT_COMMENTS_SEQ.NEXTVAL as seq_gen FROM DUAL');

        $input['DC_USER'] = $comuser;
        $input['DC_DATE'] = DB::raw("SYSDATE");
        $input['DC_ID'] = $seq_key[0]->seq_gen;
        $input['DC_DEPOT'] = $depot;
        $input['DC_TEXT'] = $msg;

        try {
            $depot_comment = Depot_comment::create($input);

            $notify_msg = 'success';

        } catch (\Exception $ex) {

            $notify_msg = 'Fail';
            
        }

    }
}