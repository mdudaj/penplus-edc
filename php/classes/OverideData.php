<?php
class OverideData
{
    private $_pdo;
    function __construct()
    {
        try {
            $this->_pdo = new PDO('mysql:host=' . config::get('mysql/host') . ';dbname=' . config::get('mysql/db'), config::get('mysql/username'), config::get('mysql/password'));
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public function unique($table, $field, $value)
    {
        if ($this->get($table, $field, $value)) {
            return true;
        } else {
            return false;
        }
    }

    public function getNo($table)
    {
        $query = $this->_pdo->query("SELECT * FROM $table");
        $num = $query->rowCount();
        return $num;
    }

    public function getNo1()
    {
        $query = $this->_pdo->query("SELECT DISTINCT patient_id
        FROM symptoms
        WHERE hba1c IS NOT NULL
        AND status = 1
        AND visit_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE();");
        $num = $query->rowCount();
        return $num;
    }

    public function getNo1_1()
    {
        $query = $this->_pdo->query("SELECT DISTINCT s.patient_id
        FROM symptoms s
        JOIN diabetic d ON s.patient_id = d.patient_id
        WHERE (s.hba1c IS NOT NULL AND s.hba1c != '')
        AND s.status = 1
        AND s.visit_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 MONTH) AND CURDATE()
        AND d.diagnosis = 1
        ");
        $num = $query->rowCount();
        return $num;
    }


    public function getNo2($table, $field, $value, $field1, $value1)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 = '$value1'");
        $num = $query->rowCount();
        return $num;
    }



    public function getCount0($table, $field, $value, $field1, $value1)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 >= '$value1'");
        $num = $query->rowCount();
        return $num;
    }

    public function getCountStatus($table, $field, $value, $field1, $value1, $field2, $value2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 >= '$value1' AND  $field2 = '$value2'");
        $num = $query->rowCount();
        return $num;
    }

    public function getCountStatus1($table, $field, $value, $field1, $value1, $field2, $value2, $field3, $value3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 >= '$value1' AND  $field2 = '$value2' AND  $field3 <= '$value3'");
        $num = $query->rowCount();
        return $num;
    }


    public function getCount($table, $field, $value)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value'");
        $num = $query->rowCount();
        return $num;
    }

    public function getCount1($table, $field, $value, $field1, $value1)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 = '$value1'");
        $num = $query->rowCount();
        return $num;
    }

    public function countData($table, $field, $value, $field1, $value1)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 = '$value1'");
        $num = $query->rowCount();
        return $num;
    }

    public function countData1($table, $field, $value, $field1, $value1, $field2, $value2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 = '$value1' AND $field2 = '$value2'");
        $num = $query->rowCount();
        return $num;
    }

    public function countData3($table, $where, $id, $where2, $id2, $where3, $id3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 = '$id3'");
        $num = $query->rowCount();
        return $num;
    }

    public function countData2($table, $field, $value, $field1, $value1, $field2, $value2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 = '$value1' AND $field2 = '$value2'");
        $num = $query->rowCount();
        return $num;
    }

    public function countData2Active($table, $field, $value, $field1, $value1, $field2, $value2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 = '$value1' AND $field2 < '$value2'");
        $num = $query->rowCount();
        return $num;
    }

    public function countData2Locked($table, $field, $value, $field1, $value1, $field2, $value2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 = '$value1' AND $field2 >= '$value2'");
        $num = $query->rowCount();
        return $num;
    }

    public function countData4($table, $field, $value, $field1, $value1, $field2, $value2, $field3, $value3, $field4, $value4)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND ($field1 = '$value1' OR $field2 = '$value2' OR $field3 = '$value3') AND $field4 = '$value4'");
        $num = $query->rowCount();
        return $num;
    }

    public function countData5($table, $field, $value, $field1, $value1, $field2, $value2, $field3, $value3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND ($field1 = '$value1' OR $field2 = '$value2' OR $field3 = '$value3')");
        $num = $query->rowCount();
        return $num;
    }

    public function countData6($table, $field, $value, $field1, $value1, $field2, $value2, $field3, $value3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND ($field1 = '$value1' AND $field2 = '$value2' AND $field3 = '$value3')");
        $num = $query->rowCount();
        return $num;
    }
    public function getData($table)
    {
        $query = $this->_pdo->query("SELECT * FROM $table");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getcolumns($table, $id, $date, $firstname, $age)
    {
        $query = $this->_pdo->query("SELECT $id,$date, $firstname, $age FROM $table");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getData2($table, $field, $value)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getNews($table, $where, $id, $where2, $id2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getNewsAsc0($table, $where, $id, $where2, $id2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' ORDER BY $where2 ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getNewsAsc022($table, $where, $id, $where2, $id2, $id3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' ORDER BY $id3 ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getNewsAsc($table, $where, $id, $where2, $id2, $id3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' ORDER BY $id3 ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getNewsAsc1($table, $where, $id, $where2, $id2, $where3, $id3, $id4)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 = '$id3' ORDER BY $id4");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get2($table, $where, $id, $where2, $id2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get3($table, $where, $id, $where2, $id2, $where3, $id3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 = '$id3'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function get1($table, $where, $id, $where1, $id1, $where2, $id2, $where3, $id3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where = '$id' OR $where1 = '$id1') AND $where2 = '$id2' AND $where3 = '$id3'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get6($table, $where, $id, $where2, $id2, $where3, $id3, $where4, $id4)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 = '$id3' AND $where4 = '$id4'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // public function get3($table, $where, $id, $where2, $id2, $where3, $id3)
    // {
    //     $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 = '$id3'");
    //     $result = $query->fetchAll(PDO::FETCH_ASSOC);
    //     return $result;
    // }

    public function get4($table, $where, $id, $where2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 >= 20");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get5($table, $where, $id, $id2, $where2)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $id2 >= '$where2'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getSumD($table, $variable)
    {
        $query = $this->_pdo->query("SELECT SUM($variable) FROM $table");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getSumD1($table, $variable, $field, $value)
    {
        $query = $this->_pdo->query("SELECT SUM($variable) FROM $table WHERE $field = '$value' ");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getSumD2($table, $variable, $field, $value, $field1, $value1)
    {
        $query = $this->_pdo->query("SELECT SUM($variable) FROM $table WHERE $field = '$value' AND $field1 = '$value1'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getIn($table, $where, $id)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where IN '$id'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getInNo($table, $where, $id)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where IN '$id'");
        $num = $query->rowCount();
        return $num;
    }


    public function get($table, $where, $id)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get0($table, $where, $id, $where1, $id1)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where1 >= '$id1'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAsc($table, $where, $id)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' ORDER BY 'medication_id' ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getArray($table, $where, $id)
    {
        $query = $this->_pdo->query("SELECT 'cardiac' FROM $table WHERE $where = '$id'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getRQ1($table)
    {
        $query = $this->_pdo->query("SELECT * FROM $table");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_new($table, $where, $id, $where1, $type)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where1 = '$type'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function searchBtnDate2($table, $var, $value, $var1, $value1)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $var >= '$value' AND $var1 <= '$value1'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function delete($table, $field, $value)
    {
        return $this->_pdo->query("DELETE FROM $table WHERE $field = $value");
    }

    public function lastRow($table, $value)
    {
        $query = $this->_pdo->query("SELECT * FROM $table ORDER BY $value DESC LIMIT 1");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getlastRow($table, $where, $value, $id)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE  $where='$value' ORDER BY $id DESC LIMIT 1");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getlastRow1($table, $where, $value, $where1, $value1, $id)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE  $where='$value' AND $where1='$value1' ORDER BY $id DESC LIMIT 1");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimit3($table, $where, $id, $where2, $id2, $where3, $id3, $page, $numRec)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 = '$id3' limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimit3Active($table, $where, $id, $where2, $id2, $where3, $id3, $page, $numRec)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 < '$id3' limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimit3Locked($table, $where, $id, $where2, $id2, $where3, $id3, $page, $numRec)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 >= '$id3' limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimit2($table, $field, $value, $field1, $value1, $value2, $field2, $page, $numRec)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $field = '$value' AND $field1 = '$value1' AND $value2 = '$field2' limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getWithLimit1($table, $where, $id, $where2, $id2, $page, $numRec)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getWithLimit($table, $where, $id, $page, $numRec)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimit0($table, $page, $numRec)
    {
        $query = $this->_pdo->query("SELECT * FROM $table limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataLimit($table, $page, $numRec)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE 1 limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function tableHeader($table)
    {
        $query = $this->_pdo->query("DESCRIBE $table");
        $result = $query->fetchAll(PDO::FETCH_COLUMN);
        return $result;
    }

    public function firstRow($table, $param, $id, $where, $client_id)
    {
        $query = $this->_pdo->query("SELECT DISTINCT $param FROM $table WHERE $where = '$client_id' ORDER BY '$id' ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function firstRow1($table, $param, $id, $where, $client_id, $where1, $id1)
    {
        $query = $this->_pdo->query("SELECT DISTINCT $param FROM $table WHERE $where = '$client_id' AND $where1 = '$id1' ORDER BY '$id' ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function firstRow2($table, $param, $id, $where, $client_id, $where1, $id1, $where2, $id2)
    {
        $query = $this->_pdo->query("SELECT DISTINCT $param FROM $table WHERE $where = '$client_id' AND $where1 = '$id1'  AND $where2 = '$id2' ORDER BY '$id' ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function clearDataTable($table)
    {
        $query = $this->_pdo->query("TRUNCATE TABLE `$table`");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // public function clearDataTable($table)
    // {
    //     $query = $this->_pdo->query("TRUNCATE TABLE `$table`");
    //     $result = $query->fetchAll(PDO::FETCH_ASSOC);
    //     return $result;
    // }

    public function AllTables()
    {
        $query = $this->_pdo->query("SHOW TABLES");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function AllTablesCont()
    {
        $query = $this->_pdo->query("SHOW TABLES");
        $num = $query->rowCount();
        return $num;
    }

    public function AllDatabasesCount()
    {
        $query = $this->_pdo->query("SHOW DATABASES");
        $num = $query->rowCount();
        return $num;
    }

    public function AllDatabases()
    {
        $query = $this->_pdo->query("SHOW DATABASES");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function SelectTests($table, $id1, $id2, $table2, $appointment_id, $id3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $id1 IN (SELECT $id2 FROM $table2 WHERE $appointment_id = '$id3')");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function SelectTests1($table, $id1, $test_ids)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $id1 IN (" . (implode(',', $test_ids)) . ") order by name asc");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    // public function SelectTests1()
    // {
    //     $query = $this->_pdo->query("select a.*,
    //     (select id from test_list
    //     from
    //     (select appointment_test_list
    //     from visit a left join
    //     (select distinct (a.study_id) from visit a where a.study_id not in ('') ) g on a.study_id = g.study_id
    //     where
    //     g.study_id is not null) a where (case when a.visit_code = 'D0' then a.expected_date in ('') else a.expected_date < CURDATE() end) and a.visit_status is null order by a.study_id");
    //     $result = $query->fetchAll(PDO::FETCH_ASSOC);
    //     return $result;
    // }


    public function setSiteId($table, $site_id, $value1, $value2)
    {
        $query = $this->_pdo->query("UPDATE $table SET $site_id='$value1' WHERE $value2");
        $num = $query->rowCount();
        return $num;
    }

    public function setStudyId($table, $study_id, $value1, $value2)
    {
        $query = $this->_pdo->query("UPDATE $table SET $study_id='$value1' WHERE $value2");
        $num = $query->rowCount();
        return $num;
    }

    public function UnsetStudyId($table, $study_id, $value1, $value2)
    {
        $query = $this->_pdo->query("UPDATE $table SET $study_id='$value1' WHERE $value2");
        $num = $query->rowCount();
        return $num;
    }


    public function DoctorConfirm($table, $site_id, $value1, $value2)
    {
        $query = $this->_pdo->query("UPDATE $table SET $site_id='$value1' WHERE $value2");
        $num = $query->rowCount();
        return $num;
    }

    public function getNews7Month()
    {
        $query = $this->_pdo->query("SELECT * FROM clients WHERE MONTH(created_on) >= MONTH(NOW() - INTERVAL 2 MONTH)
                            AND (YEAR(created_on) <= YEAR(NOW() - INTERVAL 0 MONTH))");

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function getNews7Month2()
    {
        $query = $this->_pdo->query("SELECT * FROM clients WHERE MONTH(created_on) >= MONTH(NOW() - INTERVAL 2 MONTH)
                            AND (YEAR(created_on) <= YEAR(NOW() - INTERVAL 0 MONTH))");
        $num = $query->rowCount();
        return $num;
    }

    public function getMonthData()
    {
        $query = $this->_pdo->query("SELECT YEAR(created_on) AS year, MONTH(created_on) AS month, COUNT(*) AS records_count 
          FROM clients 
          GROUP BY YEAR(created_on), MONTH(created_on) 
          ORDER BY YEAR(created_on), MONTH(created_on)");

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getMonthSum()
    {
        $query = $this->_pdo->query("SELECT MONTHNAME(created_on) as monthname, SUM(status) as amount FROM clients WHERE status = 1 GROUP BY monthname");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getMonthCount()
    {
        $query = $this->_pdo->query("SELECT MONTHNAME(created_on) as monthname, COUNT(status) as amount FROM clients WHERE status = 1 GROUP BY monthname");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getMonthCountSite($site_id)
    {
        $query = $this->_pdo->query("SELECT site_id, MONTHNAME(created_on) as monthname,COUNT(*) as count_data FROM clients WHERE site_id = '$site_id' AND status = 1 GROUP BY monthname, site_id ORDER BY monthname, site_id");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getMonthCountSiteTest($startDate, $endDate)
    {
        $query = $this->_pdo->query("SELECT site_id as site, MONTH(created_on) as month, COUNT(*) as count_data
        FROM clients
        WHERE created_on BETWEEN '$startDate' AND '$endDate'
        GROUP BY site, MONTH(created_on)
        ORDER BY site, MONTH(created_on)");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }



    public function getDataPoints()
    {
        $query = $this->_pdo->query("SELECT * FROM descriptionlabels INNER JOIN datapoints ON descriptionlabels.id = datapoints.descriptionlabelid");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataRegister($where, $value)
    {
        $query = $this->_pdo->query("SELECT site_id as site_id, COUNT(*) AS count FROM clients WHERE $where = '$value' GROUP BY site_id");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataRegister1()
    {
        $query = $this->_pdo->query("SELECT MONTHNAME(clinic_date) , site_id, COUNT(*) as count FROM clients GROUP BY date_registered, site_id");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataRegister2()
    {
        $query = $this->_pdo->query("SELECT DATE_FORMAT(clinic_date, '%Y-%m') AS month, gender, COUNT(*) AS count FROM registration_data GROUP BY month, gender");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataRegister3($where, $value)
    {
        $query = $this->_pdo->query("SELECT DATE_FORMAT(clinic_date, '%Y-%m') AS monthname, site_id as site_id, COUNT(*) AS count FROM clients WHERE $where = '$value' GROUP BY monthname, site_id ORDER BY monthname");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataRegister4($where, $value, $where1, $value1)
    {
        $query = $this->_pdo->query("SELECT DATE_FORMAT(clinic_date, '%Y-%m') AS monthname, site_id as site_id, COUNT(*) AS count FROM clients WHERE $where = '$value' AND $where1 = '$value1' GROUP BY monthname, site_id ORDER BY monthname");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function getDataRegister5($where, $value)
    {
        $query = $this->_pdo->query("SELECT MONTHNAME(clinic_date) AS monthname, dignosis_type as dignosis_type, COUNT(*) AS count FROM clients WHERE $where = '$value' GROUP BY monthname, dignosis_type");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataRegister6($where, $value)
    {
        $query = $this->_pdo->query("SELECT MONTHNAME(clinic_date) AS monthname, dignosis_type as dignosis_type, COUNT(*) AS count FROM clients WHERE $where = '$value' GROUP BY monthname, dignosis_type");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataRegisterGraphs($where, $value)
    {
        $query = $this->_pdo->query("SELECT visit_day ,height ,weight,muac ,bmi ,systolic ,dystolic , pr, site_id FROM vital WHERE $where = '$value' GROUP BY visit_day");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getGraphsTotalVital($where, $value)
    {
        $query = $this->_pdo->query("SELECT visit_day as visit_day ,AVG(height) as height ,AVG(weight) as weight,AVG(muac) as muac,AVG(bmi) as bmi,AVG(systolic) as systolic,AVG(dystolic) as dystolic ,AVG(pr) as pr,AVG(site_id) as site_id FROM vital WHERE $where = '$value'  GROUP BY visit_day");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getGraphsTotalHba1c($where, $value)
    {
        $query = $this->_pdo->query("SELECT visit_day as visit_day ,AVG(hba1c) as hba1c FROM symptoms WHERE $where = '$value'  GROUP BY visit_day");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataStaff($table, $where, $id, $where2, $id2, $where3, $id3, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 < '$id3' ORDER BY $name ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataStaffCount($table, $where, $id, $where2, $id2, $where3, $id3, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 < '$id3' ORDER BY $name ASC");
        $num = $query->rowCount();
        return $num;
    }

    public function getDataStaff1($table, $where, $id, $where2, $id2, $where3, $id3, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 >= '$id3' ORDER BY $name ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataStaff1Count($table, $where, $id, $where2, $id2, $where3, $id3, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where2 = '$id2' AND $where3 >= '$id3' ORDER BY $name ASC");
        $num = $query->rowCount();
        return $num;
    }


    public function getDataAsc($table, $where, $id, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' ORDER BY $name ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataAsc1($table, $where, $id, $where1, $id1, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where1 = '$id1' ORDER BY $name ASC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataDesc($table, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE 1 ORDER BY $name DESC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataDesc1($table, $where, $id, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' ORDER BY $name DESC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataDesc2($table, $where, $id, $where1, $id1, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where1 = '$id1' ORDER BY $name DESC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getDataDesc3($table, $where, $id, $where1, $id1, $where2, $id2, $name)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where = '$id' AND $where1 = '$id1' AND $where2 = '$id2' ORDER BY $name DESC");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchDetails($table, $searchTerm, $where1, $where2, $where3)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where1 LIKE '%$searchTerm%' OR $where2 LIKE '%$searchTerm%' OR $where3 LIKE '%$searchTerm%'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function fetchDetails1($table, $searchTerm, $where1)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE $where1 LIKE '%$searchTerm%'");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimit1SearchCount($table, $where, $id, $where1, $id1, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%') AND ($where = '$id' AND $where1 = '$id1')");
        $num = $query->rowCount();
        return $num;
    }

    public function getWithLimit1Search($table, $where, $id, $where1, $id1, $page, $numRec, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%') AND ($where = '$id' AND $where1 = '$id1') limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimit3Search($table, $where, $id, $where1, $id1, $where2, $id2, $page, $numRec, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%') AND ($where = '$id' AND $where1 = '$id1' AND $where2 = '$id2') limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    public function getWithLimit3SearchCount($table, $where, $id, $where1, $id1, $where2, $id2, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%') AND ($where = '$id' AND $where1 = '$id1' AND $where2 = '$id2')");
        $num = $query->rowCount();
        return $num;
    }

    public function getWithLimitSearch0($table, $page, $numRec, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%') limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimitSearch($table, $where, $id, $page, $numRec, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%') AND ($where = '$id') limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimit0SearchCount($table, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%')");
        $num = $query->rowCount();
        return $num;
    }


    public function getWithLimit0Search($table, $page, $numRec, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%')) limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getWithLimitSearchCount($table, $where, $id, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%') AND ($where = '$id')");
        $num = $query->rowCount();
        return $num;
    }


    public function getDataLimitSearch($table, $page, $numRec, $searchTerm, $where3, $where4, $where5, $where6)
    {
        $query = $this->_pdo->query("SELECT * FROM $table WHERE ($where3 LIKE '%$searchTerm%' OR $where4 LIKE '%$searchTerm%' OR $where5 LIKE '%$searchTerm%' OR $where6 LIKE '%$searchTerm%') limit $page,$numRec");
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
