<?php
namespace console\controllers;

use fedemotta\cronjob\models\CronJob;
use common\models\UsersFiles;
use yii\console\Controller;

/**
 * FileController controller
 */
class FileController extends Controller {

    /**
     * Run User::removeDeletesFiles for a period of time
     * @param string $from
     * @param string $to
     * @return int exit code
     */
    // public function actionInit($from, $to){
    //     $dates  = CronJob::getDateRange($from, $to);
    //     $command = CronJob::run($this->id, $this->action->id, 0, CronJob::countDateRange($dates));
    //     if ($command === false){
    //         return Controller::EXIT_CODE_ERROR;
    //     }else{
    //         foreach ($dates as $date) {
    //             //this is the function to execute for each day
               
    //         }
           
    //         $command->finish();
    //         return Controller::EXIT_CODE_NORMAL;
    //     }
    // }
    /**
     * Run User::removeDeletesFiles for today only as the default action
     * @return int exit code
     */
    public function actionRemoveFiles(){
        echo 'start at '. date("Y-m-d").PHP_EOL;
        $isAllDelted = UsersFiles::removeDeletesFiles();
        echo 'End at '. date("Y-m-d").PHP_EOL;
        // return $this->actionInit(date("Y-m-d"), date("Y-m-d"));
    }
}
?>