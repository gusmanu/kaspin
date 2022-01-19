<?php
namespace App\Logging;

use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

class FirebaseLoggingHandler extends AbstractProcessingHandler
{
/**
 *
 * Reference:
 * https://github.com/markhilton/monolog-mysql/blob/master/src/Logger/Monolog/Handler/MysqlHandler.php
 */
    public function __construct($level = Logger::DEBUG, $bubble = true) {
        parent::__construct($level, $bubble);
    }

    protected function write(array $record):void
    { 
        $data = array(
            'message'        => $record['message'],
            'context'        => json_encode($record['context']),
            'level'          => $record['level'],
            'level_name'     => $record['level_name'],
            'channel'        => $record['channel'],
            'record_datetime'=> $record['datetime']->format('Y-m-d H:i:s'),
            'user'           => Auth::check() ? Auth::user()->email : '',
            'method'         => request()->getMethod() ?? '',
            'path'           => request()->path() ?? '',
            'extra'          => json_encode($record['extra']),
            'formatted'      => $record['formatted'],
            'remote_addr'    => $_SERVER['REMOTE_ADDR'],
            'user_agent'     => $_SERVER['HTTP_USER_AGENT'],
            'created_at'     => date("Y-m-d H:i:s"),
        );

        try {
            $db = new FirestoreClient([
                'keyFilePath' => base_path('firebase.json')
            ]);
            $batch = $db->batch();
            $logRef = $db->collection('kaspin_firebase')->document('kaspin_logs');
            $batch->set($logRef, $data);
            $batch->commit();
        } catch (\Exception $e){
            Log::critical($e->getMessage());
        }
        
    }
}