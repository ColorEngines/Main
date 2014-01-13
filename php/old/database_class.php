    <?php

include_once 'config_class.php';


/**
 * Description of database_class
 *
 * @author Person
 */
class database {
    //put your code here

    public $errorArray;
    private $connectResult;

    public function connect()
    {
        $this->errorArray = array();
        $this->connectResult = mysql_connect(config::$dbHost,config::$dbUser,config::$dbPassword);
        if (!$this->connectResult)
        {
                $this->errorArray['error'] = "could not connect to {config::$dbHost} with user {config::$dbUser}";
                return false;
        }

        $useDBResult = mysql_select_db(config::$dbDatabase);
        if (!$useDBResult )
        {
            $this->errorArray['error'] = "could select database " + config::$dbDatabase;
            return false;
        }
        return true;
    }


    public function disconnect()
    {

        mysql_close($this->connectResult);
    }


    public function batchNoResults($sqls)
    {
        if (!$this->connect()) return FALSE;
        foreach ($sqls as $key => $value)
        {
            $resultSet = mysql_query($sql);
        }
        $this->disconnect();
    }



    public function select($sql, $disconnect = TRUE)
    {
        $result = array();
        if (!$this->connect()) return $result;

        $rowCount = 0;
        $resultSet = mysql_query($sql);

        try
        {
            while ($row = mysql_fetch_assoc($resultSet))
            {
                $result[$rowCount] = $row;
                $rowCount++;
            }

        } catch (Exception $exc) {
            $result = array();
        }



        if ($disconnect == TRUE) $this->disconnect();
        return $result;
    }


    public function insert($sql)
    {
        if (!$this->connect()) return $result;
        $resultSet = mysql_query($sql);
        $this->disconnect();
        return TRUE;
    }




    // select all rows where $countColumn == $countValue
    // return single row
    public function rowCount($table, $countColumn,$countValue)
    {

        if (!$this->connect()) return FALSE;

        $query="select count(*) as 'row_count' from $table where $countColumn=\"$countValue\" limit 1";

        $query = str_replace("\\", "\\\\", $query);

        $sql_result = mysql_query($query) or die();
        $row = mysql_fetch_object($sql_result);
        $result = $row->row_count;
        $this->disconnect();
        return $result;
    }


    public function insertConstantKeyValue($srcArray, $table, $constantValue,$constantColumn, $keyColumn,$valueColumn)
    {
        if (!$this->connect()) return FALSE;
        foreach ($srcArray as $key => $value)
        {
            $sql = "INSERT INTO $table ($constantColumn,$keyColumn,$valueColumn) VALUES(\"$constantValue\",\"$key\",\"$value\")";

            $sql = str_replace("\\", "\\\\", $sql);
            $resultSet = mysql_query($sql);
        }
        $this->disconnect();
        
    }

    public function insertConstantValue($srcArray, $table, $constantValue,$constantColumn,$valueColumn)
    {
        if (!$this->connect()) return FALSE;
        foreach ($srcArray as $key => $value)
        {
            $sql = "INSERT INTO $table ($constantColumn,$valueColumn) VALUES(\"$constantValue\",\"$value\")";
            $sql = str_replace("\\", "\\\\", $sql);

            $resultSet = mysql_query($sql);
            //echo "insert const value [$resultSet] sql = $sql\n";

        }
        $this->disconnect();

    }

    public function insertRowSQL($srcArray,$table)
    {

        // build field names  and  values to insert
        $fields = implode(',', array_keys($srcArray) );
        $values = '"'.implode('","', array_values($srcArray) ).'"';

        $sql = "INSERT INTO $table ($fields) VALUES($values)";
        $sql = str_replace("\\", "\\\\", $sql);

        return $sql;

    }



    public function insertRows($srcArray,$table)
    {

        // build field names  and  values to insert
        $fields = implode(',', array_keys($srcArray) );
        $values = '"'.implode('","', array_values($srcArray) ).'"';

        $sql = "INSERT INTO $table ($fields) VALUES($values)";
        $sql = str_replace("\\", "\\\\", $sql);
        $resultSet = mysql_query($sql);

    }

    public function insertSingleRow($srcArray,$table)
    {
        if (!$this->connect()) return FALSE;

        // build field names  and  values to insert
        $fields = implode(',', array_keys($srcArray) );
        $values = '"'.implode('","', array_values($srcArray) ).'"';

        $sql = "INSERT INTO $table ($fields) VALUES($values)";
        $sql = str_replace("\\", "\\\\", $sql);
        $resultSet = mysql_query($sql);

        $this->disconnect();

    }



    public function insertKeyValue($srcArray, $table, $keyColumn,$valueColumn)
    {
        if (!$this->connect()) return FALSE;
        foreach ($srcArray as $key => $value)
        {
            $sql = "INSERT INTO $table ($keyColumn,$valueColumn) VALUES(\"$key\",\"$value\")";
            $sql = str_replace("\\", "\\\\", $sql);
            $resultSet = mysql_query($sql);
        }
        $this->disconnect();

    }

    public function insertColorRGB($srcArray)
    {
        // color RGB and Filename

        if (!$this->connect()) return FALSE;
        foreach ($srcArray as $rgb => $value)
        {
            // convert $rgb into 3 HEX values R G B and 3 deciaml values Rdec Gdec Bdec

            $rgbTriplet = trim($rgb,'#');

            $hexR = substr($rgbTriplet,0,2);
            $hexG = substr($rgbTriplet,2,2);
            $hexB = substr($rgbTriplet,4,2);

            $decR = hexdec($hexR);
            $decG = hexdec($hexG);
            $decB = hexdec($hexB);

            $sql = "INSERT INTO color (filename,rgb,R,G,B,Rdec,Gdec,Bdec) VALUES('$value','$rgb','$hexR','$hexG','$hexB',$decR,$decG,$decB)";
            $sql = str_replace("\\", "\\\\", $sql);
            //echo "sql = $sql\n";
            $resultSet = mysql_query($sql);
        }
        $this->disconnect();

    }



    public function deleteKeyValue($table, $keyColumn,$value)
    {
        if (!$this->connect()) return FALSE;
        $sql = "delete from $table where $keyColumn = '$value'";
        $sql = str_replace("\\", "\\\\", $sql);
        $resultSet = mysql_query($sql);
        $this->disconnect();
    }


    // select $dataColumn from $table where $keyColumn like ('$likeStr');
    public function in($table, $keyColumn,$likeCommSep, $dataColumn)
    {
        if (!$this->connect()) return FALSE;

        $like = "'".str_replace(',', "','", $likeCommSep)."'";

        $sql = "select distinct $dataColumn from $table where $keyColumn in (\"$like\")";
        $sql = str_replace("\\", "\\\\", $sql);

        $result = array();

        $rowCount = 0;
        $resultSet = mysql_query($sql);
        while ($row = mysql_fetch_assoc($resultSet))
        {
            $result[$rowCount] = $row;
            $rowCount++;
        }

        $this->disconnect();

        return $result;

    }

    public function deleteTable($table)
    {
        if (!$this->connect()) return FALSE;
        $sql = "delete from $table";
        $resultSet = mysql_query($sql);
        $this->disconnect();
    }

    public function selectKeyValue($sql, $keyColumn,$valueColumn, $disconnect = TRUE)
    {
        if (!$this->connect()) return FALSE;
        $sql = str_replace("\\", "\\\\", $sql);
        $resultSet = $this->select($sql, $disconnect);

        $result = array();
        foreach ($resultSet as $rowID => $row)
        {
            $result[$row[$keyColumn]] = $row[$valueColumn];
        }

        return $result;

    }

        public function selectKeyValueArray($sql, $keyColumn,$valueColumn)
    {
        if (!$this->connect()) return FALSE;

        $sql = str_replace("\\", "\\\\", $sql);

        $resultSet = $this->select($sql);

        $result = array();
        foreach ($resultSet as $rowID => $row)
        {
            if (array_key_exists($row[$keyColumn], $result)  == FALSE)
                $result[$row[$keyColumn]] = array();

            $result[$row[$keyColumn]][] = $row[$valueColumn];
        }

        return $result;

    }



}
?>
