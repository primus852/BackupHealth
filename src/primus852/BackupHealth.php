<?php

namespace primus852;

{
    class BackupHealth extends Database
    {


        public function __construct(bool $connect = true)
        {
            parent::__construct($connect);
        }

        /**
         * @param $file
         * @param array $params
         * @return string
         */
        function getTemplate($file, $params = array()) {

            ob_start();
            extract($params);

            require_once $file;

            $template = ob_get_contents();
            ob_end_clean();

            return $template;

        }


    }
}