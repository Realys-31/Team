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
use Team\Model\PersonFunctionLinkVersion as ChildPersonFunctionLinkVersion;
use Team\Model\PersonFunctionLinkVersionQuery as ChildPersonFunctionLinkVersionQuery;
use Team\Model\Map\PersonFunctionLinkVersionTableMap;

/**
 * Base class that represents a query for the 'person_function_link_version' table.
 *
 *
 *
 * @method     ChildPersonFunctionLinkVersionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPersonFunctionLinkVersionQuery orderByPersonId($order = Criteria::ASC) Order by the person_id column
 * @method     ChildPersonFunctionLinkVersionQuery orderByFunctionId($order = Criteria::ASC) Order by the function_id column
 * @method     ChildPersonFunctionLinkVersionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPersonFunctionLinkVersionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildPersonFunctionLinkVersionQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildPersonFunctionLinkVersionQuery orderByVersionCreatedAt($order = Criteria::ASC) Order by the version_created_at column
 * @method     ChildPersonFunctionLinkVersionQuery orderByVersionCreatedBy($order = Criteria::ASC) Order by the version_created_by column
 * @method     ChildPersonFunctionLinkVersionQuery orderByPersonIdVersion($order = Criteria::ASC) Order by the person_id_version column
 * @method     ChildPersonFunctionLinkVersionQuery orderByFunctionIdVersion($order = Criteria::ASC) Order by the function_id_version column
 *
 * @method     ChildPersonFunctionLinkVersionQuery groupById() Group by the id column
 * @method     ChildPersonFunctionLinkVersionQuery groupByPersonId() Group by the person_id column
 * @method     ChildPersonFunctionLinkVersionQuery groupByFunctionId() Group by the function_id column
 * @method     ChildPersonFunctionLinkVersionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPersonFunctionLinkVersionQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildPersonFunctionLinkVersionQuery groupByVersion() Group by the version column
 * @method     ChildPersonFunctionLinkVersionQuery groupByVersionCreatedAt() Group by the version_created_at column
 * @method     ChildPersonFunctionLinkVersionQuery groupByVersionCreatedBy() Group by the version_created_by column
 * @method     ChildPersonFunctionLinkVersionQuery groupByPersonIdVersion() Group by the person_id_version column
 * @method     ChildPersonFunctionLinkVersionQuery groupByFunctionIdVersion() Group by the function_id_version column
 *
 * @method     ChildPersonFunctionLinkVersionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPersonFunctionLinkVersionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPersonFunctionLinkVersionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPersonFunctionLinkVersionQuery leftJoinPersonFunctionLink($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonFunctionLink relation
 * @method     ChildPersonFunctionLinkVersionQuery rightJoinPersonFunctionLink($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonFunctionLink relation
 * @method     ChildPersonFunctionLinkVersionQuery innerJoinPersonFunctionLink($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonFunctionLink relation
 *
 * @method     ChildPersonFunctionLinkVersion findOne(ConnectionInterface $con = null) Return the first ChildPersonFunctionLinkVersion matching the query
 * @method     ChildPersonFunctionLinkVersion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPersonFunctionLinkVersion matching the query, or a new ChildPersonFunctionLinkVersion object populated from the query conditions when no match is found
 *
 * @method     ChildPersonFunctionLinkVersion findOneById(int $id) Return the first ChildPersonFunctionLinkVersion filtered by the id column
 * @method     ChildPersonFunctionLinkVersion findOneByPersonId(int $person_id) Return the first ChildPersonFunctionLinkVersion filtered by the person_id column
 * @method     ChildPersonFunctionLinkVersion findOneByFunctionId(int $function_id) Return the first ChildPersonFunctionLinkVersion filtered by the function_id column
 * @method     ChildPersonFunctionLinkVersion findOneByCreatedAt(string $created_at) Return the first ChildPersonFunctionLinkVersion filtered by the created_at column
 * @method     ChildPersonFunctionLinkVersion findOneByUpdatedAt(string $updated_at) Return the first ChildPersonFunctionLinkVersion filtered by the updated_at column
 * @method     ChildPersonFunctionLinkVersion findOneByVersion(int $version) Return the first ChildPersonFunctionLinkVersion filtered by the version column
 * @method     ChildPersonFunctionLinkVersion findOneByVersionCreatedAt(string $version_created_at) Return the first ChildPersonFunctionLinkVersion filtered by the version_created_at column
 * @method     ChildPersonFunctionLinkVersion findOneByVersionCreatedBy(string $version_created_by) Return the first ChildPersonFunctionLinkVersion filtered by the version_created_by column
 * @method     ChildPersonFunctionLinkVersion findOneByPersonIdVersion(int $person_id_version) Return the first ChildPersonFunctionLinkVersion filtered by the person_id_version column
 * @method     ChildPersonFunctionLinkVersion findOneByFunctionIdVersion(int $function_id_version) Return the first ChildPersonFunctionLinkVersion filtered by the function_id_version column
 *
 * @method     array findById(int $id) Return ChildPersonFunctionLinkVersion objects filtered by the id column
 * @method     array findByPersonId(int $person_id) Return ChildPersonFunctionLinkVersion objects filtered by the person_id column
 * @method     array findByFunctionId(int $function_id) Return ChildPersonFunctionLinkVersion objects filtered by the function_id column
 * @method     array findByCreatedAt(string $created_at) Return ChildPersonFunctionLinkVersion objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildPersonFunctionLinkVersion objects filtered by the updated_at column
 * @method     array findByVersion(int $version) Return ChildPersonFunctionLinkVersion objects filtered by the version column
 * @method     array findByVersionCreatedAt(string $version_created_at) Return ChildPersonFunctionLinkVersion objects filtered by the version_created_at column
 * @method     array findByVersionCreatedBy(string $version_created_by) Return ChildPersonFunctionLinkVersion objects filtered by the version_created_by column
 * @method     array findByPersonIdVersion(int $person_id_version) Return ChildPersonFunctionLinkVersion objects filtered by the person_id_version column
 * @method     array findByFunctionIdVersion(int $function_id_version) Return ChildPersonFunctionLinkVersion objects filtered by the function_id_version column
 *
 */
abstract class PersonFunctionLinkVersionQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Team\Model\Base\PersonFunctionLinkVersionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\Team\\Model\\PersonFunctionLinkVersion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPersonFunctionLinkVersionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPersonFunctionLinkVersionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \Team\Model\PersonFunctionLinkVersionQuery) {
            return $criteria;
        }
        $query = new \Team\Model\PersonFunctionLinkVersionQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$id, $version] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildPersonFunctionLinkVersion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PersonFunctionLinkVersionTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PersonFunctionLinkVersionTableMap::DATABASE_NAME);
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
     * @return   ChildPersonFunctionLinkVersion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, PERSON_ID, FUNCTION_ID, CREATED_AT, UPDATED_AT, VERSION, VERSION_CREATED_AT, VERSION_CREATED_BY, PERSON_ID_VERSION, FUNCTION_ID_VERSION FROM person_function_link_version WHERE ID = :p0 AND VERSION = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildPersonFunctionLinkVersion();
            $obj->hydrate($row);
            PersonFunctionLinkVersionTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildPersonFunctionLinkVersion|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PersonFunctionLinkVersionTableMap::ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PersonFunctionLinkVersionTableMap::VERSION, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PersonFunctionLinkVersionTableMap::ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PersonFunctionLinkVersionTableMap::VERSION, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @see       filterByPersonFunctionLink()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the person_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPersonId(1234); // WHERE person_id = 1234
     * $query->filterByPersonId(array(12, 34)); // WHERE person_id IN (12, 34)
     * $query->filterByPersonId(array('min' => 12)); // WHERE person_id > 12
     * </code>
     *
     * @param     mixed $personId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByPersonId($personId = null, $comparison = null)
    {
        if (is_array($personId)) {
            $useMinMax = false;
            if (isset($personId['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::PERSON_ID, $personId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($personId['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::PERSON_ID, $personId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::PERSON_ID, $personId, $comparison);
    }

    /**
     * Filter the query on the function_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFunctionId(1234); // WHERE function_id = 1234
     * $query->filterByFunctionId(array(12, 34)); // WHERE function_id IN (12, 34)
     * $query->filterByFunctionId(array('min' => 12)); // WHERE function_id > 12
     * </code>
     *
     * @param     mixed $functionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByFunctionId($functionId = null, $comparison = null)
    {
        if (is_array($functionId)) {
            $useMinMax = false;
            if (isset($functionId['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::FUNCTION_ID, $functionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($functionId['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::FUNCTION_ID, $functionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::FUNCTION_ID, $functionId, $comparison);
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
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::UPDATED_AT, $updatedAt, $comparison);
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
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::VERSION, $version, $comparison);
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
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedAt($versionCreatedAt = null, $comparison = null)
    {
        if (is_array($versionCreatedAt)) {
            $useMinMax = false;
            if (isset($versionCreatedAt['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($versionCreatedAt['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt, $comparison);
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
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::VERSION_CREATED_BY, $versionCreatedBy, $comparison);
    }

    /**
     * Filter the query on the person_id_version column
     *
     * Example usage:
     * <code>
     * $query->filterByPersonIdVersion(1234); // WHERE person_id_version = 1234
     * $query->filterByPersonIdVersion(array(12, 34)); // WHERE person_id_version IN (12, 34)
     * $query->filterByPersonIdVersion(array('min' => 12)); // WHERE person_id_version > 12
     * </code>
     *
     * @param     mixed $personIdVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByPersonIdVersion($personIdVersion = null, $comparison = null)
    {
        if (is_array($personIdVersion)) {
            $useMinMax = false;
            if (isset($personIdVersion['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::PERSON_ID_VERSION, $personIdVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($personIdVersion['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::PERSON_ID_VERSION, $personIdVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::PERSON_ID_VERSION, $personIdVersion, $comparison);
    }

    /**
     * Filter the query on the function_id_version column
     *
     * Example usage:
     * <code>
     * $query->filterByFunctionIdVersion(1234); // WHERE function_id_version = 1234
     * $query->filterByFunctionIdVersion(array(12, 34)); // WHERE function_id_version IN (12, 34)
     * $query->filterByFunctionIdVersion(array('min' => 12)); // WHERE function_id_version > 12
     * </code>
     *
     * @param     mixed $functionIdVersion The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByFunctionIdVersion($functionIdVersion = null, $comparison = null)
    {
        if (is_array($functionIdVersion)) {
            $useMinMax = false;
            if (isset($functionIdVersion['min'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::FUNCTION_ID_VERSION, $functionIdVersion['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($functionIdVersion['max'])) {
                $this->addUsingAlias(PersonFunctionLinkVersionTableMap::FUNCTION_ID_VERSION, $functionIdVersion['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonFunctionLinkVersionTableMap::FUNCTION_ID_VERSION, $functionIdVersion, $comparison);
    }

    /**
     * Filter the query by a related \Team\Model\PersonFunctionLink object
     *
     * @param \Team\Model\PersonFunctionLink|ObjectCollection $personFunctionLink The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionLink($personFunctionLink, $comparison = null)
    {
        if ($personFunctionLink instanceof \Team\Model\PersonFunctionLink) {
            return $this
                ->addUsingAlias(PersonFunctionLinkVersionTableMap::ID, $personFunctionLink->getId(), $comparison);
        } elseif ($personFunctionLink instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PersonFunctionLinkVersionTableMap::ID, $personFunctionLink->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildPersonFunctionLinkVersion $personFunctionLinkVersion Object to remove from the list of results
     *
     * @return ChildPersonFunctionLinkVersionQuery The current query, for fluid interface
     */
    public function prune($personFunctionLinkVersion = null)
    {
        if ($personFunctionLinkVersion) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PersonFunctionLinkVersionTableMap::ID), $personFunctionLinkVersion->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PersonFunctionLinkVersionTableMap::VERSION), $personFunctionLinkVersion->getVersion(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the person_function_link_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonFunctionLinkVersionTableMap::DATABASE_NAME);
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
            PersonFunctionLinkVersionTableMap::clearInstancePool();
            PersonFunctionLinkVersionTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildPersonFunctionLinkVersion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildPersonFunctionLinkVersion object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PersonFunctionLinkVersionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PersonFunctionLinkVersionTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        PersonFunctionLinkVersionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PersonFunctionLinkVersionTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // PersonFunctionLinkVersionQuery
