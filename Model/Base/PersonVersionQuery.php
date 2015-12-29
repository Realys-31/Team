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
use Team\Model\PersonVersion as ChildPersonVersion;
use Team\Model\PersonVersionQuery as ChildPersonVersionQuery;
use Team\Model\Map\PersonVersionTableMap;

/**
 * Base class that represents a query for the 'person_version' table.
 *
 *
 *
 * @method     ChildPersonVersionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPersonVersionQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method     ChildPersonVersionQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method     ChildPersonVersionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPersonVersionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildPersonVersionQuery orderByVersion($order = Criteria::ASC) Order by the version column
 * @method     ChildPersonVersionQuery orderByVersionCreatedAt($order = Criteria::ASC) Order by the version_created_at column
 * @method     ChildPersonVersionQuery orderByVersionCreatedBy($order = Criteria::ASC) Order by the version_created_by column
 * @method     ChildPersonVersionQuery orderByPersonTeamLinkIds($order = Criteria::ASC) Order by the person_team_link_ids column
 * @method     ChildPersonVersionQuery orderByPersonTeamLinkVersions($order = Criteria::ASC) Order by the person_team_link_versions column
 * @method     ChildPersonVersionQuery orderByPersonImageIds($order = Criteria::ASC) Order by the person_image_ids column
 * @method     ChildPersonVersionQuery orderByPersonImageVersions($order = Criteria::ASC) Order by the person_image_versions column
 * @method     ChildPersonVersionQuery orderByPersonFunctionLinkIds($order = Criteria::ASC) Order by the person_function_link_ids column
 * @method     ChildPersonVersionQuery orderByPersonFunctionLinkVersions($order = Criteria::ASC) Order by the person_function_link_versions column
 *
 * @method     ChildPersonVersionQuery groupById() Group by the id column
 * @method     ChildPersonVersionQuery groupByFirstName() Group by the first_name column
 * @method     ChildPersonVersionQuery groupByLastName() Group by the last_name column
 * @method     ChildPersonVersionQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPersonVersionQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildPersonVersionQuery groupByVersion() Group by the version column
 * @method     ChildPersonVersionQuery groupByVersionCreatedAt() Group by the version_created_at column
 * @method     ChildPersonVersionQuery groupByVersionCreatedBy() Group by the version_created_by column
 * @method     ChildPersonVersionQuery groupByPersonTeamLinkIds() Group by the person_team_link_ids column
 * @method     ChildPersonVersionQuery groupByPersonTeamLinkVersions() Group by the person_team_link_versions column
 * @method     ChildPersonVersionQuery groupByPersonImageIds() Group by the person_image_ids column
 * @method     ChildPersonVersionQuery groupByPersonImageVersions() Group by the person_image_versions column
 * @method     ChildPersonVersionQuery groupByPersonFunctionLinkIds() Group by the person_function_link_ids column
 * @method     ChildPersonVersionQuery groupByPersonFunctionLinkVersions() Group by the person_function_link_versions column
 *
 * @method     ChildPersonVersionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPersonVersionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPersonVersionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPersonVersionQuery leftJoinPerson($relationAlias = null) Adds a LEFT JOIN clause to the query using the Person relation
 * @method     ChildPersonVersionQuery rightJoinPerson($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Person relation
 * @method     ChildPersonVersionQuery innerJoinPerson($relationAlias = null) Adds a INNER JOIN clause to the query using the Person relation
 *
 * @method     ChildPersonVersion findOne(ConnectionInterface $con = null) Return the first ChildPersonVersion matching the query
 * @method     ChildPersonVersion findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPersonVersion matching the query, or a new ChildPersonVersion object populated from the query conditions when no match is found
 *
 * @method     ChildPersonVersion findOneById(int $id) Return the first ChildPersonVersion filtered by the id column
 * @method     ChildPersonVersion findOneByFirstName(string $first_name) Return the first ChildPersonVersion filtered by the first_name column
 * @method     ChildPersonVersion findOneByLastName(string $last_name) Return the first ChildPersonVersion filtered by the last_name column
 * @method     ChildPersonVersion findOneByCreatedAt(string $created_at) Return the first ChildPersonVersion filtered by the created_at column
 * @method     ChildPersonVersion findOneByUpdatedAt(string $updated_at) Return the first ChildPersonVersion filtered by the updated_at column
 * @method     ChildPersonVersion findOneByVersion(int $version) Return the first ChildPersonVersion filtered by the version column
 * @method     ChildPersonVersion findOneByVersionCreatedAt(string $version_created_at) Return the first ChildPersonVersion filtered by the version_created_at column
 * @method     ChildPersonVersion findOneByVersionCreatedBy(string $version_created_by) Return the first ChildPersonVersion filtered by the version_created_by column
 * @method     ChildPersonVersion findOneByPersonTeamLinkIds(array $person_team_link_ids) Return the first ChildPersonVersion filtered by the person_team_link_ids column
 * @method     ChildPersonVersion findOneByPersonTeamLinkVersions(array $person_team_link_versions) Return the first ChildPersonVersion filtered by the person_team_link_versions column
 * @method     ChildPersonVersion findOneByPersonImageIds(array $person_image_ids) Return the first ChildPersonVersion filtered by the person_image_ids column
 * @method     ChildPersonVersion findOneByPersonImageVersions(array $person_image_versions) Return the first ChildPersonVersion filtered by the person_image_versions column
 * @method     ChildPersonVersion findOneByPersonFunctionLinkIds(array $person_function_link_ids) Return the first ChildPersonVersion filtered by the person_function_link_ids column
 * @method     ChildPersonVersion findOneByPersonFunctionLinkVersions(array $person_function_link_versions) Return the first ChildPersonVersion filtered by the person_function_link_versions column
 *
 * @method     array findById(int $id) Return ChildPersonVersion objects filtered by the id column
 * @method     array findByFirstName(string $first_name) Return ChildPersonVersion objects filtered by the first_name column
 * @method     array findByLastName(string $last_name) Return ChildPersonVersion objects filtered by the last_name column
 * @method     array findByCreatedAt(string $created_at) Return ChildPersonVersion objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildPersonVersion objects filtered by the updated_at column
 * @method     array findByVersion(int $version) Return ChildPersonVersion objects filtered by the version column
 * @method     array findByVersionCreatedAt(string $version_created_at) Return ChildPersonVersion objects filtered by the version_created_at column
 * @method     array findByVersionCreatedBy(string $version_created_by) Return ChildPersonVersion objects filtered by the version_created_by column
 * @method     array findByPersonTeamLinkIds(array $person_team_link_ids) Return ChildPersonVersion objects filtered by the person_team_link_ids column
 * @method     array findByPersonTeamLinkVersions(array $person_team_link_versions) Return ChildPersonVersion objects filtered by the person_team_link_versions column
 * @method     array findByPersonImageIds(array $person_image_ids) Return ChildPersonVersion objects filtered by the person_image_ids column
 * @method     array findByPersonImageVersions(array $person_image_versions) Return ChildPersonVersion objects filtered by the person_image_versions column
 * @method     array findByPersonFunctionLinkIds(array $person_function_link_ids) Return ChildPersonVersion objects filtered by the person_function_link_ids column
 * @method     array findByPersonFunctionLinkVersions(array $person_function_link_versions) Return ChildPersonVersion objects filtered by the person_function_link_versions column
 *
 */
abstract class PersonVersionQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Team\Model\Base\PersonVersionQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\Team\\Model\\PersonVersion', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPersonVersionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPersonVersionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \Team\Model\PersonVersionQuery) {
            return $criteria;
        }
        $query = new \Team\Model\PersonVersionQuery();
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
     * @return ChildPersonVersion|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PersonVersionTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PersonVersionTableMap::DATABASE_NAME);
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
     * @return   ChildPersonVersion A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, FIRST_NAME, LAST_NAME, CREATED_AT, UPDATED_AT, VERSION, VERSION_CREATED_AT, VERSION_CREATED_BY, PERSON_TEAM_LINK_IDS, PERSON_TEAM_LINK_VERSIONS, PERSON_IMAGE_IDS, PERSON_IMAGE_VERSIONS, PERSON_FUNCTION_LINK_IDS, PERSON_FUNCTION_LINK_VERSIONS FROM person_version WHERE ID = :p0 AND VERSION = :p1';
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
            $obj = new ChildPersonVersion();
            $obj->hydrate($row);
            PersonVersionTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return ChildPersonVersion|array|mixed the result, formatted by the current formatter
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
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PersonVersionTableMap::ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PersonVersionTableMap::VERSION, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PersonVersionTableMap::ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PersonVersionTableMap::VERSION, $key[1], Criteria::EQUAL);
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
     * @see       filterByPerson()
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PersonVersionTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PersonVersionTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonVersionTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%'); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstName)) {
                $firstName = str_replace('*', '%', $firstName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonVersionTableMap::FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%'); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastName)) {
                $lastName = str_replace('*', '%', $lastName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PersonVersionTableMap::LAST_NAME, $lastName, $comparison);
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
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PersonVersionTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PersonVersionTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonVersionTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PersonVersionTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PersonVersionTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonVersionTableMap::UPDATED_AT, $updatedAt, $comparison);
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
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByVersion($version = null, $comparison = null)
    {
        if (is_array($version)) {
            $useMinMax = false;
            if (isset($version['min'])) {
                $this->addUsingAlias(PersonVersionTableMap::VERSION, $version['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($version['max'])) {
                $this->addUsingAlias(PersonVersionTableMap::VERSION, $version['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonVersionTableMap::VERSION, $version, $comparison);
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
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByVersionCreatedAt($versionCreatedAt = null, $comparison = null)
    {
        if (is_array($versionCreatedAt)) {
            $useMinMax = false;
            if (isset($versionCreatedAt['min'])) {
                $this->addUsingAlias(PersonVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($versionCreatedAt['max'])) {
                $this->addUsingAlias(PersonVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonVersionTableMap::VERSION_CREATED_AT, $versionCreatedAt, $comparison);
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
     * @return ChildPersonVersionQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PersonVersionTableMap::VERSION_CREATED_BY, $versionCreatedBy, $comparison);
    }

    /**
     * Filter the query on the person_team_link_ids column
     *
     * @param     array $personTeamLinkIds The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonTeamLinkIds($personTeamLinkIds = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_TEAM_LINK_IDS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($personTeamLinkIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($personTeamLinkIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($personTeamLinkIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_TEAM_LINK_IDS, $personTeamLinkIds, $comparison);
    }

    /**
     * Filter the query on the person_team_link_ids column
     * @param     mixed $personTeamLinkIds The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonTeamLinkId($personTeamLinkIds = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($personTeamLinkIds)) {
                $personTeamLinkIds = '%| ' . $personTeamLinkIds . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $personTeamLinkIds = '%| ' . $personTeamLinkIds . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_TEAM_LINK_IDS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $personTeamLinkIds, $comparison);
            } else {
                $this->addAnd($key, $personTeamLinkIds, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_TEAM_LINK_IDS, $personTeamLinkIds, $comparison);
    }

    /**
     * Filter the query on the person_team_link_versions column
     *
     * @param     array $personTeamLinkVersions The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonTeamLinkVersions($personTeamLinkVersions = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_TEAM_LINK_VERSIONS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($personTeamLinkVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($personTeamLinkVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($personTeamLinkVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_TEAM_LINK_VERSIONS, $personTeamLinkVersions, $comparison);
    }

    /**
     * Filter the query on the person_team_link_versions column
     * @param     mixed $personTeamLinkVersions The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonTeamLinkVersion($personTeamLinkVersions = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($personTeamLinkVersions)) {
                $personTeamLinkVersions = '%| ' . $personTeamLinkVersions . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $personTeamLinkVersions = '%| ' . $personTeamLinkVersions . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_TEAM_LINK_VERSIONS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $personTeamLinkVersions, $comparison);
            } else {
                $this->addAnd($key, $personTeamLinkVersions, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_TEAM_LINK_VERSIONS, $personTeamLinkVersions, $comparison);
    }

    /**
     * Filter the query on the person_image_ids column
     *
     * @param     array $personImageIds The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonImageIds($personImageIds = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_IMAGE_IDS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($personImageIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($personImageIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($personImageIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_IMAGE_IDS, $personImageIds, $comparison);
    }

    /**
     * Filter the query on the person_image_ids column
     * @param     mixed $personImageIds The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonImageId($personImageIds = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($personImageIds)) {
                $personImageIds = '%| ' . $personImageIds . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $personImageIds = '%| ' . $personImageIds . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_IMAGE_IDS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $personImageIds, $comparison);
            } else {
                $this->addAnd($key, $personImageIds, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_IMAGE_IDS, $personImageIds, $comparison);
    }

    /**
     * Filter the query on the person_image_versions column
     *
     * @param     array $personImageVersions The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonImageVersions($personImageVersions = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_IMAGE_VERSIONS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($personImageVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($personImageVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($personImageVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_IMAGE_VERSIONS, $personImageVersions, $comparison);
    }

    /**
     * Filter the query on the person_image_versions column
     * @param     mixed $personImageVersions The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonImageVersion($personImageVersions = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($personImageVersions)) {
                $personImageVersions = '%| ' . $personImageVersions . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $personImageVersions = '%| ' . $personImageVersions . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_IMAGE_VERSIONS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $personImageVersions, $comparison);
            } else {
                $this->addAnd($key, $personImageVersions, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_IMAGE_VERSIONS, $personImageVersions, $comparison);
    }

    /**
     * Filter the query on the person_function_link_ids column
     *
     * @param     array $personFunctionLinkIds The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionLinkIds($personFunctionLinkIds = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_FUNCTION_LINK_IDS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($personFunctionLinkIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($personFunctionLinkIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($personFunctionLinkIds as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_FUNCTION_LINK_IDS, $personFunctionLinkIds, $comparison);
    }

    /**
     * Filter the query on the person_function_link_ids column
     * @param     mixed $personFunctionLinkIds The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionLinkId($personFunctionLinkIds = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($personFunctionLinkIds)) {
                $personFunctionLinkIds = '%| ' . $personFunctionLinkIds . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $personFunctionLinkIds = '%| ' . $personFunctionLinkIds . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_FUNCTION_LINK_IDS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $personFunctionLinkIds, $comparison);
            } else {
                $this->addAnd($key, $personFunctionLinkIds, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_FUNCTION_LINK_IDS, $personFunctionLinkIds, $comparison);
    }

    /**
     * Filter the query on the person_function_link_versions column
     *
     * @param     array $personFunctionLinkVersions The values to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionLinkVersions($personFunctionLinkVersions = null, $comparison = null)
    {
        $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_FUNCTION_LINK_VERSIONS);
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            foreach ($personFunctionLinkVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_SOME) {
            foreach ($personFunctionLinkVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addOr($key, $value, Criteria::LIKE);
                } else {
                    $this->add($key, $value, Criteria::LIKE);
                }
            }

            return $this;
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            foreach ($personFunctionLinkVersions as $value) {
                $value = '%| ' . $value . ' |%';
                if ($this->containsKey($key)) {
                    $this->addAnd($key, $value, Criteria::NOT_LIKE);
                } else {
                    $this->add($key, $value, Criteria::NOT_LIKE);
                }
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_FUNCTION_LINK_VERSIONS, $personFunctionLinkVersions, $comparison);
    }

    /**
     * Filter the query on the person_function_link_versions column
     * @param     mixed $personFunctionLinkVersions The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::CONTAINS_ALL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionLinkVersion($personFunctionLinkVersions = null, $comparison = null)
    {
        if (null === $comparison || $comparison == Criteria::CONTAINS_ALL) {
            if (is_scalar($personFunctionLinkVersions)) {
                $personFunctionLinkVersions = '%| ' . $personFunctionLinkVersions . ' |%';
                $comparison = Criteria::LIKE;
            }
        } elseif ($comparison == Criteria::CONTAINS_NONE) {
            $personFunctionLinkVersions = '%| ' . $personFunctionLinkVersions . ' |%';
            $comparison = Criteria::NOT_LIKE;
            $key = $this->getAliasedColName(PersonVersionTableMap::PERSON_FUNCTION_LINK_VERSIONS);
            if ($this->containsKey($key)) {
                $this->addAnd($key, $personFunctionLinkVersions, $comparison);
            } else {
                $this->addAnd($key, $personFunctionLinkVersions, $comparison);
            }
            $this->addOr($key, null, Criteria::ISNULL);

            return $this;
        }

        return $this->addUsingAlias(PersonVersionTableMap::PERSON_FUNCTION_LINK_VERSIONS, $personFunctionLinkVersions, $comparison);
    }

    /**
     * Filter the query by a related \Team\Model\Person object
     *
     * @param \Team\Model\Person|ObjectCollection $person The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function filterByPerson($person, $comparison = null)
    {
        if ($person instanceof \Team\Model\Person) {
            return $this
                ->addUsingAlias(PersonVersionTableMap::ID, $person->getId(), $comparison);
        } elseif ($person instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PersonVersionTableMap::ID, $person->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPerson() only accepts arguments of type \Team\Model\Person or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Person relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function joinPerson($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Person');

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
            $this->addJoinObject($join, 'Person');
        }

        return $this;
    }

    /**
     * Use the Person relation Person object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Team\Model\PersonQuery A secondary query class using the current class as primary query
     */
    public function usePersonQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPerson($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Person', '\Team\Model\PersonQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPersonVersion $personVersion Object to remove from the list of results
     *
     * @return ChildPersonVersionQuery The current query, for fluid interface
     */
    public function prune($personVersion = null)
    {
        if ($personVersion) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PersonVersionTableMap::ID), $personVersion->getId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PersonVersionTableMap::VERSION), $personVersion->getVersion(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the person_version table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonVersionTableMap::DATABASE_NAME);
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
            PersonVersionTableMap::clearInstancePool();
            PersonVersionTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildPersonVersion or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildPersonVersion object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PersonVersionTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PersonVersionTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        PersonVersionTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PersonVersionTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // PersonVersionQuery
