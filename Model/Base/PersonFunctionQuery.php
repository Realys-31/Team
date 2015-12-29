<?php

namespace Team\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use Team\Model\PersonFunction as ChildPersonFunction;
use Team\Model\PersonFunctionI18nQuery as ChildPersonFunctionI18nQuery;
use Team\Model\PersonFunctionQuery as ChildPersonFunctionQuery;
use Team\Model\Map\PersonFunctionTableMap;

/**
 * Base class that represents a query for the 'person_function' table.
 *
 *
 *
 * @method     ChildPersonFunctionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPersonFunctionQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method     ChildPersonFunctionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPersonFunctionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildPersonFunctionQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildPersonFunctionQuery orderByVersionCreatedAt($order = Criteria::ASC) Order by the version_created_at column
 * @method     ChildPersonFunctionQuery orderByVersionCreatedBy($order = Criteria::ASC) Order by the version_created_by column
 *
 * @method     ChildPersonFunctionQuery groupById() Group by the id column
 * @method     ChildPersonFunctionQuery groupByCode() Group by the code column
 * @method     ChildPersonFunctionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPersonFunctionQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildPersonFunctionQuery groupByVersion() Group by the version column
 * @method     ChildPersonFunctionQuery groupByVersionCreatedAt() Group by the version_created_at column
 * @method     ChildPersonFunctionQuery groupByVersionCreatedBy() Group by the version_created_by column
 *
 * @method     ChildPersonFunctionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPersonFunctionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPersonFunctionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPersonFunctionQuery leftJoinPersonFunctionLink($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonFunctionLink relation
 * @method     ChildPersonFunctionQuery rightJoinPersonFunctionLink($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonFunctionLink relation
 * @method     ChildPersonFunctionQuery innerJoinPersonFunctionLink($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonFunctionLink relation
 *
 * @method     ChildPersonFunctionQuery leftJoinPersonFunctionI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonFunctionI18n relation
 * @method     ChildPersonFunctionQuery rightJoinPersonFunctionI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonFunctionI18n relation
 * @method     ChildPersonFunctionQuery innerJoinPersonFunctionI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonFunctionI18n relation
 *
 * @method     ChildPersonFunctionQuery leftJoinPersonFunctionVersion($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonFunctionVersion relation
 * @method     ChildPersonFunctionQuery rightJoinPersonFunctionVersion($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonFunctionVersion relation
 * @method     ChildPersonFunctionQuery innerJoinPersonFunctionVersion($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonFunctionVersion relation
 *
 * @method     ChildPersonFunction findOne(ConnectionInterface $con = null) Return the first ChildPersonFunction matching the query
 * @method     ChildPersonFunction findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPersonFunction matching the query, or a new ChildPersonFunction object populated from the query conditions when no match is found
 *
 * @method     ChildPersonFunction findOneById(int $id) Return the first ChildPersonFunction filtered by the id column
 * @method     ChildPersonFunction findOneByCode(string $code) Return the first ChildPersonFunction filtered by the code column
 * @method     ChildPersonFunction findOneByCreatedAt(string $created_at) Return the first ChildPersonFunction filtered by the created_at column
 * @method     ChildPersonFunction findOneByUpdatedAt(string $updated_at) Return the first ChildPersonFunction filtered by the updated_at column
 * @method     ChildPersonFunction findOneByVersion(int $version) Return the first ChildPersonFunction filtered by the version column
 * @method     ChildPersonFunction findOneByVersionCreatedAt(string $version_created_at) Return the first ChildPersonFunction filtered by the version_created_at column
 * @method     ChildPersonFunction findOneByVersionCreatedBy(string $version_created_by) Return the first ChildPersonFunction filtered by the version_created_by column
 *
 * @method     array findById(int $id) Return ChildPersonFunction objects filtered by the id column
 * @method     array findByCode(string $code) Return ChildPersonFunction objects filtered by the code column
 * @method     array findByCreatedAt(string $created_at) Return ChildPersonFunction objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildPersonFunction objects filtered by the updated_at column
 * @method     array findByVersion(int $version) Return ChildPersonFunction objects filtered by the version column
 * @method     array findByVersionCreatedAt(string $version_created_at) Return ChildPersonFunction objects filtered by the version_created_at column
 * @method     array findByVersionCreatedBy(string $version_created_by) Return ChildPersonFunction objects filtered by the version_created_by column
 *
 */
abstract class PersonFunctionQuery extends ModelCriteria
{

    // versionable behavior

    /**
     * Whether the versioning is enabled
     */
    static $isVersioningEnabled = true;

    /**
     * Initializes internal state of \Team\Model\Base\PersonFunctionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\Team\\Model\\PersonFunction', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPersonFunctionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPersonFunctionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \Team\Model\PersonFunctionQuery) {
            return $criteria;
        }
        $query = new \Team\Model\PersonFunctionQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPersonFunction|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PersonFunctionTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PersonFunctionTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildPersonFunction A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, CODE, CREATED_AT, UPDATED_AT, VERSION, VERSION_CREATED_AT, VERSION_CREATED_BY FROM person_function WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildPersonFunction();
            $obj->hydrate($row);
            PersonFunctionTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildPersonFunction|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PersonFunctionTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PersonFunctionTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PersonFunctionTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PersonFunctionTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonFunctionTableMap::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PersonFunctionTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PersonFunctionTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PersonFunctionTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PersonFunctionTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the version column
     *
     * Example usage:
     * <code>
     * $query->filterByVersion(1234); // WHERE version = 1234
     * $query->filterByVersion(array(12, 34)); // WHERE version IN (12, 34)
     * $query->filterByVersion(array('min' => 12)); // WHERE version > 12
     * </code>
     *
     * @param     mixed $version The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(PersonFunctionTableMap::VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(PersonFunctionTableMap::VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionTableMap::VERSION, $version, $comparison);
    }

    /**
     * Filter the query on the version_created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByVersionCreatedAt('2011-03-14'); // WHERE version_created_at = '2011-03-14'
     * $query->filterByVersionCreatedAt('now'); // WHERE version_created_at = '2011-03-14'
     * $query->filterByVersionCreatedAt(array('max' => 'yesterday')); // WHERE version_created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $versionCreatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedAt($versionCreatedAt = null, $comparison = null)
    {
        if (is_array($versionCreatedAt)) {
            $useMinMax = false;
            if (isset($versionCreatedAt['min'])) {
                $this->addUsingAlias(PersonFunctionTableMap::VERSION_CREATED_AT, $versionCreatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($versionCreatedAt['max'])) {
                $this->addUsingAlias(PersonFunctionTableMap::VERSION_CREATED_AT, $versionCreatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionTableMap::VERSION_CREATED_AT, $versionCreatedAt, $comparison);
    }

    /**
     * Filter the query on the version_created_by column
     *
     * Example usage:
     * <code>
     * $query->filterByVersionCreatedBy('fooValue');   // WHERE version_created_by = 'fooValue'
     * $query->filterByVersionCreatedBy('%fooValue%'); // WHERE version_created_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $versionCreatedBy The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedBy($versionCreatedBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($versionCreatedBy)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $versionCreatedBy)) {
                $versionCreatedBy = str_replace('*', '%', $versionCreatedBy);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonFunctionTableMap::VERSION_CREATED_BY, $versionCreatedBy, $comparison);
    }

    /**
     * Filter the query by a related \Team\Model\PersonFunctionLink object
     *
     * @param \Team\Model\PersonFunctionLink|ObjectCollection $personFunctionLink  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionLink($personFunctionLink, $comparison = null)
    {
        if ($personFunctionLink instanceof \Team\Model\PersonFunctionLink) {
            return $this
                ->addUsingAlias(PersonFunctionTableMap::ID, $personFunctionLink->getFunctionId(), $comparison);
        } elseif ($personFunctionLink instanceof ObjectCollection) {
            return $this
                ->usePersonFunctionLinkQuery()
                ->filterByPrimaryKeys($personFunctionLink->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonFunctionLink() only accepts arguments of type \Team\Model\PersonFunctionLink or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonFunctionLink relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function joinPersonFunctionLink($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonFunctionLink');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PersonFunctionLink');
        }

        return $this;
    }

    /**
     * Use the PersonFunctionLink relation PersonFunctionLink object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Team\Model\PersonFunctionLinkQuery A secondary query class using the current class as primary query
     */
    public function usePersonFunctionLinkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonFunctionLink($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonFunctionLink', '\Team\Model\PersonFunctionLinkQuery');
    }

    /**
     * Filter the query by a related \Team\Model\PersonFunctionI18n object
     *
     * @param \Team\Model\PersonFunctionI18n|ObjectCollection $personFunctionI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionI18n($personFunctionI18n, $comparison = null)
    {
        if ($personFunctionI18n instanceof \Team\Model\PersonFunctionI18n) {
            return $this
                ->addUsingAlias(PersonFunctionTableMap::ID, $personFunctionI18n->getId(), $comparison);
        } elseif ($personFunctionI18n instanceof ObjectCollection) {
            return $this
                ->usePersonFunctionI18nQuery()
                ->filterByPrimaryKeys($personFunctionI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonFunctionI18n() only accepts arguments of type \Team\Model\PersonFunctionI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonFunctionI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function joinPersonFunctionI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonFunctionI18n');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PersonFunctionI18n');
        }

        return $this;
    }

    /**
     * Use the PersonFunctionI18n relation PersonFunctionI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Team\Model\PersonFunctionI18nQuery A secondary query class using the current class as primary query
     */
    public function usePersonFunctionI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinPersonFunctionI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonFunctionI18n', '\Team\Model\PersonFunctionI18nQuery');
    }

    /**
     * Filter the query by a related \Team\Model\PersonFunctionVersion object
     *
     * @param \Team\Model\PersonFunctionVersion|ObjectCollection $personFunctionVersion  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionVersion($personFunctionVersion, $comparison = null)
    {
        if ($personFunctionVersion instanceof \Team\Model\PersonFunctionVersion) {
            return $this
                ->addUsingAlias(PersonFunctionTableMap::ID, $personFunctionVersion->getId(), $comparison);
        } elseif ($personFunctionVersion instanceof ObjectCollection) {
            return $this
                ->usePersonFunctionVersionQuery()
                ->filterByPrimaryKeys($personFunctionVersion->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonFunctionVersion() only accepts arguments of type \Team\Model\PersonFunctionVersion or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonFunctionVersion relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function joinPersonFunctionVersion($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonFunctionVersion');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'PersonFunctionVersion');
        }

        return $this;
    }

    /**
     * Use the PersonFunctionVersion relation PersonFunctionVersion object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Team\Model\PersonFunctionVersionQuery A secondary query class using the current class as primary query
     */
    public function usePersonFunctionVersionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonFunctionVersion($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonFunctionVersion', '\Team\Model\PersonFunctionVersionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPersonFunction $personFunction Object to remove from the list of results
     *
     * @return ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function prune($personFunction = null)
    {
        if ($personFunction) {
            $this->addUsingAlias(PersonFunctionTableMap::ID, $personFunction->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the person_function table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonFunctionTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PersonFunctionTableMap::clearInstancePool();
            PersonFunctionTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildPersonFunction or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildPersonFunction object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonFunctionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PersonFunctionTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        PersonFunctionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PersonFunctionTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PersonFunctionTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PersonFunctionTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PersonFunctionTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PersonFunctionTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PersonFunctionTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PersonFunctionTableMap::CREATED_AT);
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'PersonFunctionI18n';

        return $this
            ->joinPersonFunctionI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildPersonFunctionQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('PersonFunctionI18n');
        $this->with['PersonFunctionI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildPersonFunctionI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonFunctionI18n', '\Team\Model\PersonFunctionI18nQuery');
    }

    // versionable behavior

    /**
     * Checks whether versioning is enabled
     *
     * @return boolean
     */
    static public function isVersioningEnabled()
    {
        return self::$isVersioningEnabled;
    }

    /**
     * Enables versioning
     */
    static public function enableVersioning()
    {
        self::$isVersioningEnabled = true;
    }

    /**
     * Disables versioning
     */
    static public function disableVersioning()
    {
        self::$isVersioningEnabled = false;
    }

} // PersonFunctionQuery
