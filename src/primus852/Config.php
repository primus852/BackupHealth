<?php

namespace primus852;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Config
{
    /* ---- MySQL ---- */
    /* -- Host -- */
    const DB_HOST = "mysql";

    /* -- TABLE -- */
    const DB_PORT = 3306;

    /* -- User -- */
    const DB_USER = "root";

    /* -- Password -- */
    const DB_PASS = "docker";

    /* -- Table -- */
    const DB_DATABASE = "project";

    /* ---- SimpleCrypt ---- */
    const SC_KEY = 'changeme';
    const SC_IV = 'metoo';

    /* ---- Ping Command ---- */
    const PING_REPEAT = 3;
    const PING_TIMEOUT = 5000; //in ms
    const PING_GOOD_BELOW = 50; //green below X ms
    const PING_AVG_MAX = 120; //yellow up to X ms

    /* ---- MySQL Benchmark Command ---- */
    const BM_GOOD_BELOW = 1000; //green below X ms
    const BM_AVG_MAX = 2500; //yellow up to X ms

    /* ---- cURL Settings ---- */
    const CURL_TIMEOUT = 10; //in seconds
}