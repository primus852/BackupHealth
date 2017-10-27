<?php

namespace primus852;


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
    const DB_TABLE = "project";

    /* ---- SimpleCrypt ---- */
    const SC_KEY = 'changeme';
    const SC_IV = 'metoo';

}