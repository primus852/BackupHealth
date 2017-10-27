<?php

namespace primus852;

{
    class BackupHealth extends Database
    {


        public function __construct($connect = true)
        {
            parent::__construct($connect);
        }

        /**
         * @param $file
         * @param array $params
         * @return string
         */
        function getTemplate($file, $params = array())
        {

            ob_start();
            extract($params);

            require_once $file;

            $template = ob_get_contents();
            ob_end_clean();

            return $template;

        }

        public function mysql_status($project_id)
        {

            $data = $this->query_by_id('projects_mysql', $project_id);

            if ($data === null) {
                return new JsonResponse(array(
                    'result' => 'error',
                    'message' => 'Could not find mysql entry for project #' . $project_id,
                ));
            }

            $sc = new SimpleCrypt();

            try {
                $conn = new \PDO('mysql:dbname=' .$data['db']. ';host=' . $data['hostname'] . ':' . $data['port'], $data['username'], $sc->decrypt($data['pass']));
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                return new JsonResponse(array(
                    'result' => 'success',
                    'message' => 'Ping finished.',
                    'extra' => array(
                        'ping' => 'offline',
                        'classes' => 'text-danger',
                        'id' => $data['id']
                    )
                ));
            }

            return new JsonResponse(array(
                'result' => 'success',
                'message' => 'Ping finished.',
                'extra' => array(
                    'ping' => 'online',
                    'classes' => 'text-success',
                    'id' => $data['id']
                )
            ));

        }

        /**
         * @param $project_id
         * @return JsonResponse
         */
        public function ping_site($project_id)
        {

            $data = $this->query_by_id('projects_ping', $project_id);

            if ($data === null) {
                return new JsonResponse(array(
                    'result' => 'error',
                    'message' => 'Could not find ping entry for project #' . $project_id,
                ));
            }

            /* Set Ping to 0 */
            $ping = 0;

            /* Repeat */
            $i = 1;
            $counter = 0;
            while ($i <= Config::PING_REPEAT) {

                /* Start Timer */
                $startTime = microtime(true);

                /* Ping */
                $pinging = fsockopen(str_replace('https', 'ssl', $data['url']), $data['port'], $errno, $errstr, Config::PING_TIMEOUT);

                /* Stop Timer */
                $stopTime = microtime(true);

                if ($pinging) {
                    fclose($pinging);
                    $ping += ($stopTime - $startTime) * 1000;
                    $counter++;
                } else {
                    return new JsonResponse(array(
                        'result' => 'error',
                        'message' => 'Ping Error. Code: ' . $errno . ', ' . $errstr,
                    ));
                }

                $i++;
            }

            $result = 'offline';
            $classes = 'text-danger';
            if ($ping > 0) {
                $result = round(($ping / $counter), 0);

                if ($result <= Config::PING_GOOD_BELOW) {
                    $classes = 'text-success';
                } elseif ($result > Config::PING_GOOD_BELOW && $result <= Config::PING_AVG_MAX) {
                    $classes = 'text-warning';
                } else {
                    $classes = 'text-danger';
                }
                $result .= 'ms';
            }

            return new JsonResponse(array(
                'result' => 'success',
                'message' => 'Ping finished.',
                'extra' => array(
                    'ping' => $result,
                    'classes' => $classes,
                    'id' => $data['id']
                )
            ));

        }


    }
}