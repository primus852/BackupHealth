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

        /**
         * @param $project_id
         * @return JsonResponse
         */
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

            $version = 'unknown';
            try {
                $conn = new \PDO('mysql:dbname=' . $data['db'] . ';host=' . $data['hostname'] . ':' . $data['port'], $data['username'], $sc->decrypt($data['pass']));
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $v = $conn->getAttribute(\PDO::ATTR_SERVER_VERSION);

                preg_match("/^[0-9\.]+/", $v, $match);
                $version = $match[0];

            } catch (\PDOException $e) {
                return new JsonResponse(array(
                    'result' => 'success',
                    'message' => 'Ping finished.',
                    'extra' => array(
                        'result' => 'offline',
                        'classes' => 'text-danger',
                        'id' => $data['id'],
                        'version' => $version,
                    )
                ));
            }

            $conn = null;

            return new JsonResponse(array(
                'result' => 'success',
                'message' => 'Ping finished.',
                'extra' => array(
                    'result' => 'online',
                    'classes' => 'text-success',
                    'id' => $data['id'],
                    'version' => $version,
                )
            ));

        }

        public function mysql_benchmark($project_id)
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
                $conn = new \PDO('mysql:dbname=' . $data['db'] . ';host=' . $data['hostname'] . ':' . $data['port'], $data['username'], $sc->decrypt($data['pass']));
                $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                /* Start Timer */
                $startTime = microtime(true);

                /* Create the query */
                $stmt = $conn->prepare('SELECT BENCHMARK(100000,ENCODE(RAND(),RAND()));', array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));

                /* Execute the query */
                $stmt->execute();

                /* Stop Timer */
                $stopTime = microtime(true);

            } catch (\PDOException $e) {
                return new JsonResponse(array(
                    'result' => 'error',
                    'message' => 'Benchmark failed.',
                    'extra' => array(
                        'type' => 'mysql_error',
                        'message' => $e->getMessage(),
                    )
                ));
            }

            $conn = null;

            /* ms for query */
            $result = round(($stopTime - $startTime) * 1000, 0) . 'ms';

            if ($result <= Config::BM_GOOD_BELOW) {
                $classes = 'text-success';
            } elseif ($result > Config::BM_GOOD_BELOW && $result <= Config::BM_AVG_MAX) {
                $classes = 'text-warning';
            } else {
                $classes = 'text-danger';
            }

            return new JsonResponse(array(
                'result' => 'success',
                'message' => 'Benchmark finished.',
                'extra' => array(
                    'result' => $result,
                    'classes' => $classes,
                    'id' => $data['id'],
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
                    'result' => $result,
                    'classes' => $classes,
                    'id' => $data['id']
                )
            ));

        }

        public function get_status_code($project_id)
        {

            $data = $this->query_by_id('projects_ping', $project_id);

            if ($data === null) {
                return new JsonResponse(array(
                    'result' => 'error',
                    'message' => 'Could not find ping entry for project #' . $project_id,
                ));
            }

            $ch = curl_init($data['url']);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, Config::CURL_TIMEOUT);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            switch ($code) {
                case 200:
                    $classes = 'text-success';
                    break;
                case 301:
                case 302:
                    $classes = 'text-warning';
                    break;
                default:
                    $classes = 'text-danger';
                    break;
            }

            return new JsonResponse(array(
                'result' => 'success',
                'message' => 'Ping finished.',
                'extra' => array(
                    'result' => $code,
                    'classes' => $classes,
                    'id' => $data['id']
                )
            ));

        }


    }
}