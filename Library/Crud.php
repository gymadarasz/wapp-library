<?php declare(strict_types = 1);

/**
 * PHP version 7.4
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */

namespace Madsoft\Library;

use RuntimeException;

/**
 * Crud
 *
 * @category  PHP
 * @package   Madsoft\Library
 * @author    Gyula Madarasz <gyula.madarasz@gmail.com>
 * @copyright 2020 Gyula Madarasz
 * @license   Copyright (c) All rights reserved.
 * @link      this
 */
class Crud
{
    const LOGICS = ['AND', 'OR'];
    const NO_CONDITION_FILTERS = [
        'AND' => '1=0',
        'OR' => '1=1',
    ];
    
    protected Safer $safer;
    protected Mysql $mysql;
    protected Session $session;

    /**
     * Method __construct
     *
     * @param Safer   $safer   safer
     * @param Mysql   $mysql   mysql
     * @param Session $session session
     */
    public function __construct(Safer $safer, Mysql $mysql, Session $session)
    {
        $this->safer = $safer;
        $this->mysql = $mysql;
        $this->session = $session;
    }
    
    public function selectOwnedRows(): array {
        
    }
    
    /**
     * Method row
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $fieldsUnsafe fieldsUnsafe
     * @param string[] $filterUnsafe filterUnsafe
     * @param int      $limit        limit
     * @param int      $offset       offset
     * @param int      $uid          uid
     *
     * @return mixed[]
     */
    public function get(
        string $tableUnsafe,
        array $fieldsUnsafe,
        array $filterUnsafe = [],
        int $limit = 1,
        int $offset = 0,
        int $uid = 0
    ): array {
        $table = $this->mysql->escape($tableUnsafe);
        $mysql = $this->mysql;
        $fields = implode(
            ', ',
            $this->safer->freez(
                static function ($value) use ($mysql, $table) {
                    return "`$table`.`" . $mysql->escape($value) . "`";
                },
                $fieldsUnsafe
            )
        );
        $query = "SELECT $fields FROM `$table`";
        
        if ($uid > -1) {
            if (!$uid) {
                $uid = (int)$this->session->get('uid');
            }
            $query .= " JOIN `ownership` "
                . "ON `ownership`.`row_id` = `$table`.`id` "
                . "AND `ownership`.`table_name` = '$table' "
                . "AND `ownership`.`user_id` = $uid";
        }
        
        $query .= $this->getWhere($table, $filterUnsafe, 'OR');
        if ($limit >= 1) {
            $query .= " LIMIT $offset, $limit";
        }
        if ($limit === 1) {
            return $this->mysql->selectOne($query);
        }
        return $this->mysql->select($query);
    }
    
    /**
     * Method getWhere
     *
     * @param string   $table        table
     * @param string[] $filterUnsafe filterUnsafe
     * @param string   $logic        logic
     *
     * @return string
     * @throws RuntimeException
     */
    protected function getWhere(
        string $table,
        array $filterUnsafe,
        string $logic
    ): string {
        $filter = $this->safer->freez([$this->mysql, 'escape'], $filterUnsafe);
        if (!in_array($logic, self::LOGICS, true)) {
            throw new RuntimeException("Invalid logic: '$logic'");
        }
        $query = '';
        if ($filter) {
            $query .= " WHERE " . $this->getConditions($table, $filter, $logic);
        }
        return $query;
    }
    
    /**
     * Method getConditions
     *
     * @param string   $table  table
     * @param string[] $filter filter
     * @param string   $logic  logic
     *
     * @return string
     */
    protected function getConditions(
        string $table,
        array $filter,
        string $logic
    ): string {
        $conds = [];
        foreach ($filter as $key => $value) {
            $conds[] = "`$table`.`$key` = '$value'";
        }
        return $conds ?
            implode(" $logic ", $conds) :
            $this::NO_CONDITION_FILTERS[$logic];
    }
    
    /**
     * Method add
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $valuesUnsafe valuesUnsafe
     * @param int      $uid          uid
     *
     * @return int
     */
    public function add(string $tableUnsafe, array $valuesUnsafe, int $uid = 0): int
    {
        $this->mysql->transStart();
        
        $ret = $this->addInsert($tableUnsafe, $valuesUnsafe);
        if (!$ret) {
            $this->mysql->transRollback();
            return 0;
        }
        
        if ($uid > -1) {
            if (!$uid) {
                $uid = (int)$this->session->get('uid');
            }
            $oid = $this->addInsert(
                'ownership',
                [
                    'table_name' => 'script',
                    'row_id' => (string)$ret,
                    'user_id' => (string)$uid,
                ]
            );
            if (!$oid) {
                $this->mysql->transRollback();
                return 0;
            }
        }
        
        $this->mysql->transCommit();
        
        return $ret;
    }
    
    /**
     * Method addInsert
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $valuesUnsafe valuesUnsafe
     *
     * @return int
     */
    protected function addInsert(string $tableUnsafe, array $valuesUnsafe): int
    {
        $table = $this->mysql->escape($tableUnsafe);
        $fields = $this->safer->freez([$this->mysql, 'escape'], $valuesUnsafe);
        $keys = implode('`, `', array_keys($fields));
        $values = implode("', '", $fields);
        $query = "INSERT INTO `$table` (`$keys`) VALUES ('$values')";
        return $this->mysql->insert($query);
    }
    
    /**
     * Method del
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $filterUnsafe filterUnsafe
     * @param int      $limit        limit
     *
     * @return int
     */
    public function del(
        string $tableUnsafe,
        array $filterUnsafe,
        int $limit = 1
    ): int {
        $table = $this->mysql->escape($tableUnsafe);
        $query = "DELETE FROM `$table`";
        $query .= $this->getWhere($table, $filterUnsafe, 'AND');
        if ($limit) {
            $query .= " LIMIT " . $limit;
        }
        return $this->mysql->delete($query);
    }
    
    /**
     * Method set
     *
     * @param string   $tableUnsafe  tableUnsafe
     * @param string[] $valuesUnsafe valuesUnsafe
     * @param string[] $filterUnsafe filterUnsafe
     *
     * @return int
     */
    public function set(
        string $tableUnsafe,
        array $valuesUnsafe,
        array $filterUnsafe
    ): int {
        $table = $this->mysql->escape($tableUnsafe);
        $fields = $this->safer->freez([$this->mysql, 'escape'], $valuesUnsafe);
        $sets = [];
        foreach ($fields as $key => $value) {
            $sets[] = "`$key` = '$value'";
        }
        $setstr = implode(', ', $sets);
        $where = $this->getWhere($table, $filterUnsafe, 'AND');
        $query = "UPDATE $table SET $setstr $where LIMIT 1";
        return $this->mysql->update($query);
    }
}
