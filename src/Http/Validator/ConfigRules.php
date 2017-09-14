<?php

namespace CoreCMF\Storage\Http\Validator;

use CoreCMF\Core\Support\Validator\Rules as coreRules;
class ConfigRules extends coreRules
{
    public function index(){
        $disks = "
            (rule, value, callback) => {
                if (value == undefined) {
                  callback('请输入磁盘名称');
                } else {
                  ".$this->asyncField(route('api.storage.config.check'),'{
                    disks:this.fromData.disks,
                    id:this.fromData.id
                  }')."
                }
            }
        ";
        return [
            'disks'=> [
                ['required' => true,  'validator' => $disks, 'trigger'=> 'blur']
            ],
            'bucket'=> [
                [ 'required'=> true, 'message'=> '请输入Bucket名称', 'trigger'=> 'blur' ]
            ],
        ];
    }
}
