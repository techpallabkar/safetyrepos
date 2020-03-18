<?php 

class MainDao extends DbCon {

    public $_table;
    public $db;
    public $fetch_assoc;
    public $fetch_obj;
    private $pdo_error;
    public $lastInsertedId = 0;
    public $pagging = array(
        'start' => 0,
        'limit' => 30,
        'conditions' => '',
        'max_row' => 0,
        'data' => array(),
        'page' => 1,
        'sql' => ''
    );
    public $page = 1;

    public function __construct() {
        parent::__construct();
        $this->pagging = (object) $this->pagging;
        $this->fetch_assoc = PDO::FETCH_ASSOC;
        $this->fetch_obj = PDO::FETCH_OBJ;
    }

    public function get_datafromdata($clause) {
        $query = "SELECT * FROM division_department WHERE parent_id = " . $clause;
        return $this->select($query);
    }

    public function save($modelData = array()) {
        if (empty($modelData) || $this->_table == "")
            return false;

        $smtp = '';
        $update = 0;
        $insert_query = 'INSERT INTO ' . $this->_table . '(' . implode(',', array_keys($modelData)) . ') 
		VALUES(:' . implode(',:', array_keys($modelData)) . ')';
        $update_query = 'UPDATE ' . $this->_table . ' SET ';

        if (array_key_exists('id', $modelData))
            if ($modelData['id'] > 0)
                $update++;
        if (!$update)
            $smtp = $this->db->prepare($insert_query);

        $set_str = '';
        foreach ($modelData as $field => $value) {
            if ($field == 'id')
                continue;
            $value = ( $value != "" ) ? trim(addslashes($value)) : $value;
            if ($update) {
                $set_str .= "," . $field . "=:" . $field;
            } elseif (!$update) {
                $smtp->bindValue(':' . $field, $value);
            }
        }
        if ($update) {
            $set_str = trim($set_str, ',');
            $update_query .= $set_str . ' WHERE id = :id';
            $smtp = $this->db->prepare($update_query);
            foreach ($modelData as $field => $value) {
                $smtp->bindValue(':' . $field, $value);
            }
        }

        $smtp->execute();
        $this->set_query_error($smtp);
        $returned = 0;
        if (!$update) {
            $this->lastInsertedId = $this->db->lastInsertId();
            $returned = $this->lastInsertedId;
        } else {
            $returned = $smtp->rowCount();
        }

        return ( $this->pdo_error != "" ) ? false : $returned;
    }

    public function select($query, $data = array(), $single = 0) {

        $execute = array();
        $query = ($query != "") ? $query : "SELECT * FROM " . $this->_table;
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $field => $val) {
                $execute[":" . $field] = $val;
                if (!$i)
                    $query .= " WHERE " . $field . " = :" . $field;
                if ($i)
                    $query .= " AND " . $field . " = :" . $field;
                $i++;
            }
        }
        $result = $this->db->prepare($query);
        $result->execute($execute);
        $this->set_query_error($result);
        return (!$single ) ? $result->fetchAll($this->fetch_obj) : $result->fetch($this->fetch_obj);
    }

    public function del($data = array()) {
        if (empty($data))
            return FALSE;

        $delete = 0;
        $query = "DELETE FROM " . $this->_table;
        if (!empty($data)) {
            $i = 0;
            foreach ($data as $field => $val) {
                $execute[":" . $field] = $val;
                if (!$i)
                    $query .= " WHERE " . $field . " = :" . $field;
                if ($i)
                    $query .= " AND " . $field . " = :" . $field;
                $i++;
            }
        }
        $result = $this->db->prepare($query);

        try {
            $delete = $result->execute($execute);
        } catch (PDOException $e) {
            die("Error: " . $e->getMessage());
        }
        return $delete;
    }

    public function set_query_error($smtp) {
        $errorInfo = $smtp->errorInfo();
        $this->pdo_error = isset($errorInfo[2]) ? $errorInfo[2] : "";
    }

    public function get_query_error() {
        return $this->pdo_error;
    }

    public function toggle_activation($data = array()) {
        if (empty($data))
            return FALSE;

        $query = "UPDATE `" . $this->_table . "` SET `status_id` = :status_id WHERE `id` = :id";
        $result = $this->db->prepare($query);
        $result->execute(array(":status_id" => $data["status_id"], ":id" => $data["id"]));

        return $result->rowCount();
    }

    public function getUploads($table_id = 0, $tagtable = '', $id = 0) {
        if (!$table_id && $tagtable == '' && !$id)
            return array();
        $tagtable = ($tagtable == '') ? $this->_table : $tagtable;

        $query = "SELECT * FROM `uploads` WHERE 1";
        if ($tagtable != "")
            $query .= " AND `table_name` = '" . $tagtable . "'";
        if ($table_id > 0)
            $query .= " AND `table_id` = " . $table_id;
        if ($id > 0)
            $query .= " AND `id` = " . $id;
        $resutl = $this->db->prepare($query);
        $resutl->execute();
        return $resutl->fetchAll();
    }

    public function get_activities($query = Null) {
        $query = "";
        $single = "";
        $table = "activity_type_master";
        $execute = array();

        $query = "select * from activity_type_master where activity_name!='' ORDER BY orderID ASC";
        $result = $this->db->prepare($query);
        $result->execute($execute);
        $this->set_query_error($result);
        return (!$single ) ? $result->fetchAll($this->fetch_obj) : $result->fetch($this->fetch_obj);
    }

    public function update_activity_participants($activity_id, $random_no) {
        if (empty($activity_id))
            return FALSE;
        $table = "activity_participants_mapping";
        //update activity id to activity_participants_mapping
        $query = "UPDATE `" . $table . "` SET `activity_id` = :status_id WHERE `token_id` = :id";
        $result = $this->db->prepare($query);
        $result->execute(array(":status_id" => $activity_id, ":id" => $random_no));

        //delete garbage records
        $querys = "DELETE FROM `" . $table . "` WHERE token_id='$random_no' AND `activity_id` IS NULL";
        $results = $this->db->prepare($querys);
        $results->execute();

        /* update activity_participant_category_id  to activity_participants_mapping */
        $querynew = "SELECT * FROM activity_participant_category_mapping WHERE `activity_id` = :activity_id";
        $resultxx = $this->db->prepare($querynew);
        $resultxx->execute(array(":activity_id" => $activity_id));
        $allrows = $resultxx->fetchAll();

        foreach ($allrows as $val) {
            $queryxxx = "UPDATE `" . $table . "` SET `activity_participant_category_id` = :apc_id WHERE `participant_cat_id` = :participant_cat_id AND activity_id = :activity_id";
            $resultxxx = $this->db->prepare($queryxxx);
            $resultxxx->execute(array(":apc_id" => $val->id, ":activity_id" => $activity_id, ":participant_cat_id" => $val->participant_cat_id));
        }
        /* update activity_participant_category_id  to activity_participants_mapping */
        return $result->rowCount();
    }

    public function update_incident_finding_mapping($activity_id, $random_no) {
        if (empty($activity_id))
            return FALSE;
        $table = "incident_finding_mapping";
        //update activity id to activity_participants_mapping
        $query = "UPDATE `" . $table . "` SET `incident_id` = :incident_id WHERE `token_id` = :id";
        $result = $this->db->prepare($query);
        $result->execute(array(":incident_id" => $activity_id, ":id" => $random_no));

        //delete garbage records
        $querys = "DELETE FROM `" . $table . "` WHERE token_id='$random_no' AND `incident_id` IS NULL";
        $results = $this->db->prepare($querys);
        $results->execute();

        /* update activity_participant_category_id  to activity_participants_mapping */
        /* $querynew = "SELECT * FROM activity_participant_category_mapping WHERE `activity_id` = :activity_id";
          $resultxx  = $this->db->prepare($querynew);
          $resultxx->execute( array( ":activity_id" => $activity_id ));
          $allrows = $resultxx->fetchAll();

          foreach($allrows as $val)
          {
          $queryxxx = "UPDATE `".$table."` SET `activity_participant_category_id` = :apc_id WHERE `participant_cat_id` = :participant_cat_id AND activity_id = :activity_id";
          $resultxxx  = $this->db->prepare($queryxxx);
          $resultxxx->execute( array( ":apc_id" => $val->id, ":activity_id" => $activity_id, ":participant_cat_id" => $val->participant_cat_id ) );
          } */
        /* update activity_participant_category_id  to activity_participants_mapping */
        return $result->rowCount();
    }

    public function update_audit_violation_mapping($activity_id, $random_no) {
        if (empty($activity_id))
            return FALSE;
        $table = "audit_violation_mapping";
        //update activity id to activity_participants_mapping
        $query = "UPDATE `" . $table . "` SET `audit_id` = :audit_id WHERE `token_id` = :id";
        $result = $this->db->prepare($query);
        $result->execute(array(":audit_id" => $activity_id, ":id" => $random_no));

        //delete garbage records
        $querys = "DELETE FROM `" . $table . "` WHERE token_id='$random_no' AND `audit_id` IS NULL";
        $results = $this->db->prepare($querys);
        $results->execute();
        return $result->rowCount();
    }

    public function update_mappingID($activity_id, $random_no, $cols = Null, $table = Null) {
        if (empty($activity_id))
            return FALSE;
        $query = "UPDATE `" . $table . "` SET `" . $cols . "` = :column_name WHERE `token_id` = :id";
        $result = $this->db->prepare($query);
        $result->execute(array(":column_name" => $activity_id, ":id" => $random_no));

        //delete garbage records
        $querys = "DELETE FROM `" . $table . "` WHERE token_id='$random_no' AND `" . $cols . "` IS NULL";
        $results = $this->db->prepare($querys);
        $results->execute();
        return $result->rowCount();
    }

    public function update_ppe_audit_violation_mapping($activity_id, $random_no) {
        if (empty($activity_id))
            return FALSE;
        $table = "ppe_audit_deviation_mapping";
        //update activity id to activity_participants_mapping
        $query = "UPDATE `" . $table . "` SET `ppe_audit_id` = :audit_id WHERE `token_id` = :id";
        $result = $this->db->prepare($query);
        $result->execute(array(":audit_id" => $activity_id, ":id" => $random_no));

        //delete garbage records
        $querys = "DELETE FROM `" . $table . "` WHERE token_id='$random_no' AND `ppe_audit_id` IS NULL";
        $results = $this->db->prepare($querys);
        $results->execute();
        return $result->rowCount();
    }

    public function get_value_by_table($id, $field, $table) {
        $query = "SELECT " . $field . " FROM " . $table . " WHERE id=" . $id;
         $results = $this->db->prepare($query);
        $results->execute();
        return $results->fetchAll();
    }
    public function getStatisticMasterData($id = null) {
        $clause = "";
        if(!empty($id)) {
            $clause = ' AND id='.$id;
        }
        $query = "SELECT *  FROM statistical_type_master WHERE name!=''".$clause;
        $results = $this->db->prepare($query);
        $results->execute();
        return $results->fetchAll();
    }
    
    public function checkExistMisTree($treeid) {
        $querynew = "SELECT * FROM mistree_report WHERE tree_id = ".$treeid;
        $results = $this->db->prepare($querynew);
        $results->execute();
        return $results->rowCount();
    }

    function backup_tables($host, $user, $pass, $name, $tables = '*') {
        date_default_timezone_set('Asia/Calcutta');
        $link = mysqli_connect($host, $user, $pass, $name);

        //get all of the tables
        if ($tables == '*') {

            $tables = array();
            $Q1 = "SHOW TABLES";
            $result = $this->db->prepare($Q1);
            $result->execute();
            $allrows = $result->fetchAll();
            foreach ($allrows as $keys => $rowx) {
                $tables[] = $rowx->Tables_in_cesc_safety_management;
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
        $return = '';
        //cycle through
        foreach ($tables as $table) {

            $result = mysqli_query($link, 'SELECT * FROM ' . $table);
            $num_fields = mysqli_num_fields($result);

            $return .= 'DROP TABLE ' . $table . ';';
            $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE ' . $table));
            $return .= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $return .= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        //$row[$j] = ereg_replace("\n","\\n",$row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return .= ',';
                        }
                    }
                    $return .= ");\n";
                }
            }
            $return .= "\n\n\n";
        }
        $handle = fopen(BASE_PATH . '/db/db-backup-' . date("Y-m-d_H_i_s") . '-' . (md5(implode(',', $tables))) . '.sql', 'w+');
        if ($handle) {
            fwrite($handle, $return);
            fclose($handle);
        }
    }
public function fetchAllData($tablename,$clause = 1) {
        if(empty($tablename)) return false;
        $where_clause = ($clause == '') ? 1 : $clause;
        $query   = " SELECT *  FROM ".$tablename." WHERE ".$where_clause;
        $results = $this->db->prepare($query);
        $results->execute();
        return $results->fetchAll();        
    }
}
