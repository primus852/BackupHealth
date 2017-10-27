<?php

namespace primus852;

use primus852\SimpleCrypt as SC;


class Database
{


    private $sql, $connected;

    /**
     * Database constructor.
     * @param bool $connect
     */
    public function __construct(bool $connect = true)
    {

        /* Default Values */
        $this->connected = false;

        /* Connect to DB if requested */
        if ($connect === true) {
            $this->connect_db();
        }
    }

    /**
     * @return bool|\primus852\JsonResponse
     */
    private function connect_db()
    {

        try {
            $this->sql = new \PDO('mysql:dbname=' . Config::DB_TABLE . ';host=' . Config::DB_HOST . ':' . Config::DB_PORT, Config::DB_USER, Config::DB_PASS);
            $this->sql->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not connect to Database',
                'extra' => array(
                    'type' => 'mysql_error',
                    'message' => $e->getMessage(),
                )
            ));
        }

        /* Set to $connected = true for other functions to check */
        $this->connected = true;

        return true;

    }

    /**
     * @param bool $active
     * @return \primus852\JsonResponse|array|null
     */
    public function list_projects(bool $active = true)
    {

        if (!$this->connected) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Cannot list projects, not connected to DB',
                'extra' => null,
            ));
        }

        $result = $this->query_result(
            'SELECT * FROM ' . Config::DB_TABLE . '.app_projects WHERE is_active = :active',
            array(
                ':active' => $active,
            )
        );

        return $result;

    }

    /**
     * @param $query
     * @param array $params
     * @return mixed
     */
    public function query_result($query, $params = array())
    {

        /* Create the query */
        $stmt = $this->sql->prepare($query, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));

        /* Execute the query */
        $stmt->execute($params);

        /* Return all */
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param string $table
     * @param int $id
     * @return mixed
     */
    public function query_by_id(string $table, int $id)
    {

        /* Create the query */
        $stmt = $this->sql->prepare('SELECT * FROM ' . Config::DB_TABLE . '.' . $table . ' WHERE id = :id', array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));

        /* Execute the query */
        $stmt->execute(array(
            ':id' => $id
        ));

        /* Return all */
        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    /**
     * @param int $project_id
     * @return mixed
     */
    public function get_project_mysql(int $project_id)
    {

        /* Create the query */
        $stmt = $this->sql->prepare('SELECT * FROM ' . Config::DB_TABLE . '.projects_mysql WHERE project_id = :project_id', array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));

        /* Execute the query */
        $stmt->execute(array(
            ':project_id' => $project_id
        ));

        /* Return all */
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param array $data
     * @param array $headings
     * @param string $db_table
     * @return string
     */
    public function display_table(array $data, array $headings, string $db_table)
    {

        /* Create SimpleCrypt Instance */
        $sc = new SC();

        /* Create Table Head */
        $table_head = '
        <table class="table table-condensed" id="' . $sc->encrypt($db_table) . '">
            <thead>
                <tr>';

        /* Create Table Body */
        $table_body = '';

        foreach ($headings as $key => $heading) {

            /* Add field to Heading */
            if (is_array($heading)) {
                $table_head .= '<th class="text-center">' . $key . '</th>';
            } else {
                $table_head .= '<th class="text-center">' . $heading . '</th>';
            }


        }

        /* Check if data is empty */
        if (!empty($data) && !empty($headings)) {


            /* Create Rows */
            foreach ($data as $row) {

                $table_row = '<tr id="row_' . $row['id'] . '">';

                foreach ($headings as $key => $heading) {

                    if ($key !== 'Action') {

                        if($heading === 'Password'){
                            $table_row .= '<td id="field_' . $sc->encrypt($key) . '_' . $row['id'] . '">***</td>';
                        }else{
                            $table_row .= '<td id="field_' . $sc->encrypt($key) . '_' . $row['id'] . '">' . $row[$key] . '</td>';
                        }


                    } else {

                        /* Array defines this as Action, creates buttons from the given array */
                        $buttons = '<div class="btn-group">';
                        foreach ($heading as $button) {

                            switch ($button['action']) {
                                case 'remove':
                                    $link = '#';
                                    break;
                                case 'edit':
                                    $link = '/ajax/load_template.php?template=' . $button['template'] . '&id=' . $row['id'];
                                    break;
                                default:
                                    $link = '#';
                                    break;
                            }

                            $buttons .= '
                        <a href="' . $link . '"
                            data-endpoint="' . $button['endpoint'] . '"
                            data-table="' . $sc->encrypt($db_table) . '"
                            data-id="' . $row['id'] . '"
                            data-hash="' . $button['hash'] . '-' . $row['id'] . '"
                            class="btn ' . $button['classes'] . ' btn-sm"
                        >
                            <i class="fa fa-' . $button['icon'] . '"></i> ' . $button['text'] . '
                        </a>';
                        }

                        $buttons .= '</div>';

                        $table_row .= '<td class="text-center">' . $buttons . '</td>';
                    }
                }

                $table_row .= '</tr>';
                $table_body .= $table_row;
            }
        }

        $table_head .= '
                </tr>
            </thead>
            <tbody id="' . $db_table . '">
        ';


        /* Create Table Footer */
        $table_foot = '
        </tbody>
        </table>
        ';

        /* Put together */
        return $table_head . $table_body . $table_foot;

    }

    public function update_list_entry(array $values, int $id, string $table)
    {

        /* Check if required keys exist */
        if ($table === '' || $table === null) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not find \'table\' in request',
                'extra' => null,
            ));
        }

        if ($id === '' || $id === null) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not find \'id\' in request',
                'extra' => null,
            ));
        }

        /* Decrypt the Table */
        $sc = new SC();
        $table = $sc->decrypt($table);

        $updateString = null;
        $valuesArray = array(
            'id' => $id
        );
        $fields = array();
        foreach ($values as $value) {

            /* check if the field is required */
            if (($value['val'] === "" || $value['val'] === null) && $value['required'] === 'true') {
                return new JsonResponse(array(
                    'result' => 'error',
                    'message' => 'The field <strong>' . $value['name'] . '</strong> cannot be empty',
                ));
            }

            /* If the type is a password, encrypt it */
            $val = $value['val'];
            if ($value['type'] === 'password') {
                $val = $sc->encrypt($val);
            }

            /* Create "enter to db" string */
            $updateString .= $sc->decrypt($value['col']) . '=:' . $sc->decrypt($value['col']) . ',';
            $valuesArray[':' . $sc->decrypt($value['col'])] = $val;
            $fields[] = array(
                'value' => $val,
                'field' => $value['col'],
            );
        }

        $sql = "UPDATE " . Config::DB_TABLE . "." . $table . " SET " . substr($updateString, 0, -1) . " WHERE id = :id;";

        /* Create the query */
        $stmt = $this->sql->prepare($sql);

        /* Execute the query */
        $stmt->execute($valuesArray);

        if (!$stmt->rowCount()) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not update row.',
                'extra' => null,
            ));
        }

        return new JsonResponse(array(
            'result' => 'success',
            'message' => 'Row updated',
            'extra' => array(
                'action' => 'update',
                'id' => $id,
                'fields' => $fields,
            ),
        ));

    }


    /**
     * @param array $values
     * @param string $table
     * @return JsonResponse
     */
    public function add_list_entry(array $values, string $table)
    {

        /* Check if required keys exist */
        if ($table === '' || $table === null) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not find \'table\' in request',
                'extra' => null,
            ));
        }

        /* Decrypt the Table */
        $sc = new SC();
        $table = $sc->decrypt($table);


        $fieldsString = null;
        $valuesString = null;
        $valuesArray = array();
        $fields = array();
        foreach ($values as $value) {

            /* check if the field is required */
            if (($value['val'] === "" || $value['val'] === null) && $value['required'] === 'true') {
                return new JsonResponse(array(
                    'result' => 'error',
                    'message' => 'The field <strong>' . $value['name'] . '</strong> cannot be empty',
                ));
            }

            /* If the type is a password, encrypt it */
            $val = $value['val'];
            if ($value['type'] === 'password') {
                $val = $sc->encrypt($val);
            }

            /* Create "enter to db" string */
            $fieldsString .= $sc->decrypt($value['col']) . ',';
            $valuesString .= ':' . $sc->decrypt($value['col']) . ',';
            $valuesArray[':' . $sc->decrypt($value['col'])] = $val;
            $fields[] = array(
                'value' => $val,
                'field' => $value['col'],
                'type' => $value['type'],
            );
        }

        $sql = "INSERT INTO " . Config::DB_TABLE . "." . $table . " (" . substr($fieldsString, 0, -1) . ") VALUES (" . substr($valuesString, 0, -1) . ");";

        /* Create the query */
        $stmt = $this->sql->prepare($sql);

        /* Execute the query */
        try {
            $stmt->execute($valuesArray);
        } catch (\PDOException $e) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not insert row.',
                'extra' => array(
                    'sql' => $sql,
                    'values' => $valuesArray,
                    'message' => $e->getMessage(),
                ),
            ));
        }


        return new JsonResponse(array(
            'result' => 'success',
            'message' => 'Row inserted',
            'extra' => array(
                'action' => 'add',
                'id' => $this->sql->lastInsertId(),
                'fields' => $fields,
            ),
        ));

    }

    /**
     * @param array $values
     * @return JsonResponse
     */
    public function remove_by_id(array $values)
    {


        /* Check if required keys exist */
        if (!array_key_exists('table', $values)) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not find \'table\' in values',
                'extra' => null,
            ));
        }

        if (!array_key_exists('id', $values)) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not find \'id\' in values',
                'extra' => null,
            ));
        }

        /* Decrypt the Table */
        $sc = new SC();
        $table = $sc->decrypt($values['table']);

        /* Create the query */
        $stmt = $this->sql->prepare('DELETE FROM ' . Config::DB_TABLE . '.' . $table . ' WHERE id = :id');

        /* Execute the query */
        $stmt->execute(array(':id' => $values['id']));

        if (!$stmt->rowCount()) {
            return new JsonResponse(array(
                'result' => 'error',
                'message' => 'Could not delete row.',
                'extra' => null,
            ));
        }

        return new JsonResponse(array(
            'result' => 'success',
            'message' => 'Row deleted',
            'extra' => array(
                'action' => 'remove',
                'id' => $values['id'],
            ),
        ));

    }


    /**
     * @return null
     */
    public function close_connection()
    {
        return $this->sql = null;
    }

}