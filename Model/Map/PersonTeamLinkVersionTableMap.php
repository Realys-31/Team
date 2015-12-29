<?php

namespace Team\Model\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use Team\Model\PersonTeamLinkVersion;
use Team\Model\PersonTeamLinkVersionQuery;


/**
 * This class defines the structure of the 'person_team_link_version' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class PersonTeamLinkVersionTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;
    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'Team.Model.Map.PersonTeamLinkVersionTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'thelia';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'person_team_link_version';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\Team\\Model\\PersonTeamLinkVersion';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'Team.Model.PersonTeamLinkVersion';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 10;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 10;

    /**
     * the column name for the ID field
     */
    const ID = 'person_team_link_version.ID';

    /**
     * the column name for the PERSON_ID field
     */
    const PERSON_ID = 'person_team_link_version.PERSON_ID';

    /**
     * the column name for the TEAM_ID field
     */
    const TEAM_ID = 'person_team_link_version.TEAM_ID';

    /**
     * the column name for the CREATED_AT field
     */
    const CREATED_AT = 'person_team_link_version.CREATED_AT';

    /**
     * the column name for the UPDATED_AT field
     */
    const UPDATED_AT = 'person_team_link_version.UPDATED_AT';

    /**
     * the column name for the VERSION field
     */
    const VERSION = 'person_team_link_version.VERSION';

    /**
     * the column name for the VERSION_CREATED_AT field
     */
    const VERSION_CREATED_AT = 'person_team_link_version.VERSION_CREATED_AT';

    /**
     * the column name for the VERSION_CREATED_BY field
     */
    const VERSION_CREATED_BY = 'person_team_link_version.VERSION_CREATED_BY';

    /**
     * the column name for the PERSON_ID_VERSION field
     */
    const PERSON_ID_VERSION = 'person_team_link_version.PERSON_ID_VERSION';

    /**
     * the column name for the TEAM_ID_VERSION field
     */
    const TEAM_ID_VERSION = 'person_team_link_version.TEAM_ID_VERSION';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'PersonId', 'TeamId', 'CreatedAt', 'UpdatedAt', 'Version', 'VersionCreatedAt', 'VersionCreatedBy', 'PersonIdVersion', 'TeamIdVersion', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'personId', 'teamId', 'createdAt', 'updatedAt', 'version', 'versionCreatedAt', 'versionCreatedBy', 'personIdVersion', 'teamIdVersion', ),
        self::TYPE_COLNAME       => array(PersonTeamLinkVersionTableMap::ID, PersonTeamLinkVersionTableMap::PERSON_ID, PersonTeamLinkVersionTableMap::TEAM_ID, PersonTeamLinkVersionTableMap::CREATED_AT, PersonTeamLinkVersionTableMap::UPDATED_AT, PersonTeamLinkVersionTableMap::VERSION, PersonTeamLinkVersionTableMap::VERSION_CREATED_AT, PersonTeamLinkVersionTableMap::VERSION_CREATED_BY, PersonTeamLinkVersionTableMap::PERSON_ID_VERSION, PersonTeamLinkVersionTableMap::TEAM_ID_VERSION, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'PERSON_ID', 'TEAM_ID', 'CREATED_AT', 'UPDATED_AT', 'VERSION', 'VERSION_CREATED_AT', 'VERSION_CREATED_BY', 'PERSON_ID_VERSION', 'TEAM_ID_VERSION', ),
        self::TYPE_FIELDNAME     => array('id', 'person_id', 'team_id', 'created_at', 'updated_at', 'version', 'version_created_at', 'version_created_by', 'person_id_version', 'team_id_version', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'PersonId' => 1, 'TeamId' => 2, 'CreatedAt' => 3, 'UpdatedAt' => 4, 'Version' => 5, 'VersionCreatedAt' => 6, 'VersionCreatedBy' => 7, 'PersonIdVersion' => 8, 'TeamIdVersion' => 9, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'personId' => 1, 'teamId' => 2, 'createdAt' => 3, 'updatedAt' => 4, 'version' => 5, 'versionCreatedAt' => 6, 'versionCreatedBy' => 7, 'personIdVersion' => 8, 'teamIdVersion' => 9, ),
        self::TYPE_COLNAME       => array(PersonTeamLinkVersionTableMap::ID => 0, PersonTeamLinkVersionTableMap::PERSON_ID => 1, PersonTeamLinkVersionTableMap::TEAM_ID => 2, PersonTeamLinkVersionTableMap::CREATED_AT => 3, PersonTeamLinkVersionTableMap::UPDATED_AT => 4, PersonTeamLinkVersionTableMap::VERSION => 5, PersonTeamLinkVersionTableMap::VERSION_CREATED_AT => 6, PersonTeamLinkVersionTableMap::VERSION_CREATED_BY => 7, PersonTeamLinkVersionTableMap::PERSON_ID_VERSION => 8, PersonTeamLinkVersionTableMap::TEAM_ID_VERSION => 9, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'PERSON_ID' => 1, 'TEAM_ID' => 2, 'CREATED_AT' => 3, 'UPDATED_AT' => 4, 'VERSION' => 5, 'VERSION_CREATED_AT' => 6, 'VERSION_CREATED_BY' => 7, 'PERSON_ID_VERSION' => 8, 'TEAM_ID_VERSION' => 9, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'person_id' => 1, 'team_id' => 2, 'created_at' => 3, 'updated_at' => 4, 'version' => 5, 'version_created_at' => 6, 'version_created_by' => 7, 'person_id_version' => 8, 'team_id_version' => 9, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('person_team_link_version');
        $this->setPhpName('PersonTeamLinkVersion');
        $this->setClassName('\\Team\\Model\\PersonTeamLinkVersion');
        $this->setPackage('Team.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addForeignPrimaryKey('ID', 'Id', 'INTEGER' , 'person_team_link', 'ID', true, null, null);
        $this->addColumn('PERSON_ID', 'PersonId', 'INTEGER', true, null, null);
        $this->addColumn('TEAM_ID', 'TeamId', 'INTEGER', true, null, null);
        $this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('UPDATED_AT', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addPrimaryKey('VERSION', 'Version', 'INTEGER', true, null, 0);
        $this->addColumn('VERSION_CREATED_AT', 'VersionCreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('VERSION_CREATED_BY', 'VersionCreatedBy', 'VARCHAR', false, 100, null);
        $this->addColumn('PERSON_ID_VERSION', 'PersonIdVersion', 'INTEGER', false, null, 0);
        $this->addColumn('TEAM_ID_VERSION', 'TeamIdVersion', 'INTEGER', false, null, 0);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PersonTeamLink', '\\Team\\Model\\PersonTeamLink', RelationMap::MANY_TO_ONE, array('id' => 'id', ), 'CASCADE', null);
    } // buildRelations()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \Team\Model\PersonTeamLinkVersion $obj A \Team\Model\PersonTeamLinkVersion object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize(array((string) $obj->getId(), (string) $obj->getVersion()));
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \Team\Model\PersonTeamLinkVersion object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \Team\Model\PersonTeamLinkVersion) {
                $key = serialize(array((string) $value->getId(), (string) $value->getVersion()));

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \Team\Model\PersonTeamLinkVersion object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 5 + $offset : static::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)]));
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {

            return $pks;
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? PersonTeamLinkVersionTableMap::CLASS_DEFAULT : PersonTeamLinkVersionTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     * @return array (PersonTeamLinkVersion object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PersonTeamLinkVersionTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PersonTeamLinkVersionTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PersonTeamLinkVersionTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PersonTeamLinkVersionTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PersonTeamLinkVersionTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = PersonTeamLinkVersionTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PersonTeamLinkVersionTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PersonTeamLinkVersionTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::ID);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::PERSON_ID);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::TEAM_ID);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::CREATED_AT);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::UPDATED_AT);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::VERSION);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::VERSION_CREATED_AT);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::VERSION_CREATED_BY);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::PERSON_ID_VERSION);
            $criteria->addSelectColumn(PersonTeamLinkVersionTableMap::TEAM_ID_VERSION);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.PERSON_ID');
            $criteria->addSelectColumn($alias . '.TEAM_ID');
            $criteria->addSelectColumn($alias . '.CREATED_AT');
            $criteria->addSelectColumn($alias . '.UPDATED_AT');
            $criteria->addSelectColumn($alias . '.VERSION');
            $criteria->addSelectColumn($alias . '.VERSION_CREATED_AT');
            $criteria->addSelectColumn($alias . '.VERSION_CREATED_BY');
            $criteria->addSelectColumn($alias . '.PERSON_ID_VERSION');
            $criteria->addSelectColumn($alias . '.TEAM_ID_VERSION');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(PersonTeamLinkVersionTableMap::DATABASE_NAME)->getTable(PersonTeamLinkVersionTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(PersonTeamLinkVersionTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(PersonTeamLinkVersionTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new PersonTeamLinkVersionTableMap());
      }
    }

    /**
     * Performs a DELETE on the database, given a PersonTeamLinkVersion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or PersonTeamLinkVersion object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonTeamLinkVersionTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \Team\Model\PersonTeamLinkVersion) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PersonTeamLinkVersionTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(PersonTeamLinkVersionTableMap::ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(PersonTeamLinkVersionTableMap::VERSION, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = PersonTeamLinkVersionQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) { PersonTeamLinkVersionTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { PersonTeamLinkVersionTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the person_team_link_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PersonTeamLinkVersionQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a PersonTeamLinkVersion or Criteria object.
     *
     * @param mixed               $criteria Criteria or PersonTeamLinkVersion object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonTeamLinkVersionTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from PersonTeamLinkVersion object
        }


        // Set the correct dbName
        $query = PersonTeamLinkVersionQuery::create()->mergeWith($criteria);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

} // PersonTeamLinkVersionTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
PersonTeamLinkVersionTableMap::buildTableMap();
