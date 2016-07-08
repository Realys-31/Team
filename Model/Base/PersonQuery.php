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
use Team\Model\Person as ChildPerson;
use Team\Model\PersonI18nQuery as ChildPersonI18nQuery;
use Team\Model\PersonQuery as ChildPersonQuery;
use Team\Model\Map\PersonTableMap;

/**
 * Base class that represents a query for the 'person' table.
 *
 *
 *
 * @method     ChildPersonQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildPersonQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method     ChildPersonQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method     ChildPersonQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildPersonQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildPersonQuery groupById() Group by the id column
 * @method     ChildPersonQuery groupByFirstName() Group by the first_name column
 * @method     ChildPersonQuery groupByLastName() Group by the last_name column
 * @method     ChildPersonQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildPersonQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildPersonQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildPersonQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildPersonQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildPersonQuery leftJoinPersonTeamLink($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonTeamLink relation
 * @method     ChildPersonQuery rightJoinPersonTeamLink($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonTeamLink relation
 * @method     ChildPersonQuery innerJoinPersonTeamLink($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonTeamLink relation
 *
 * @method     ChildPersonQuery leftJoinPersonImage($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonImage relation
 * @method     ChildPersonQuery rightJoinPersonImage($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonImage relation
 * @method     ChildPersonQuery innerJoinPersonImage($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonImage relation
 *
 * @method     ChildPersonQuery leftJoinPersonFunctionLink($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonFunctionLink relation
 * @method     ChildPersonQuery rightJoinPersonFunctionLink($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonFunctionLink relation
 * @method     ChildPersonQuery innerJoinPersonFunctionLink($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonFunctionLink relation
 *
 * @method     ChildPersonQuery leftJoinPersonI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the PersonI18n relation
 * @method     ChildPersonQuery rightJoinPersonI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PersonI18n relation
 * @method     ChildPersonQuery innerJoinPersonI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the PersonI18n relation
 *
 * @method     ChildPerson findOne(ConnectionInterface $con = null) Return the first ChildPerson matching the query
 * @method     ChildPerson findOneOrCreate(ConnectionInterface $con = null) Return the first ChildPerson matching the query, or a new ChildPerson object populated from the query conditions when no match is found
 *
 * @method     ChildPerson findOneById(int $id) Return the first ChildPerson filtered by the id column
 * @method     ChildPerson findOneByFirstName(string $first_name) Return the first ChildPerson filtered by the first_name column
 * @method     ChildPerson findOneByLastName(string $last_name) Return the first ChildPerson filtered by the last_name column
 * @method     ChildPerson findOneByCreatedAt(string $created_at) Return the first ChildPerson filtered by the created_at column
 * @method     ChildPerson findOneByUpdatedAt(string $updated_at) Return the first ChildPerson filtered by the updated_at column
 *
 * @method     array findById(int $id) Return ChildPerson objects filtered by the id column
 * @method     array findByFirstName(string $first_name) Return ChildPerson objects filtered by the first_name column
 * @method     array findByLastName(string $last_name) Return ChildPerson objects filtered by the last_name column
 * @method     array findByCreatedAt(string $created_at) Return ChildPerson objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildPerson objects filtered by the updated_at column
 *
 */
abstract class PersonQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \Team\Model\Base\PersonQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\Team\\Model\\Person', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildPersonQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildPersonQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \Team\Model\PersonQuery) {
            return $criteria;
        }
        $query = new \Team\Model\PersonQuery();
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
     * @return ChildPerson|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PersonTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PersonTableMap::DATABASE_NAME);
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
     * @return   ChildPerson A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, FIRST_NAME, LAST_NAME, CREATED_AT, UPDATED_AT FROM person WHERE ID = :p0';
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
            $obj = new ChildPerson();
            $obj->hydrate($row);
            PersonTableMap::addInstanceToPool($obj, (string) $key);
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
     * @return ChildPerson|array|mixed the result, formatted by the current formatter
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
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PersonTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PersonTableMap::ID, $keys, Criteria::IN);
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
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PersonTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PersonTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonTableMap::ID, $id, $comparison);
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
     * @return ChildPersonQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PersonTableMap::FIRST_NAME, $firstName, $comparison);
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
     * @return ChildPersonQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PersonTableMap::LAST_NAME, $lastName, $comparison);
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
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PersonTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PersonTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonTableMap::CREATED_AT, $createdAt, $comparison);
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
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PersonTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PersonTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersonTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \Team\Model\PersonTeamLink object
     *
     * @param \Team\Model\PersonTeamLink|ObjectCollection $personTeamLink  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterByPersonTeamLink($personTeamLink, $comparison = null)
    {
        if ($personTeamLink instanceof \Team\Model\PersonTeamLink) {
            return $this
                ->addUsingAlias(PersonTableMap::ID, $personTeamLink->getPersonId(), $comparison);
        } elseif ($personTeamLink instanceof ObjectCollection) {
            return $this
                ->usePersonTeamLinkQuery()
                ->filterByPrimaryKeys($personTeamLink->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonTeamLink() only accepts arguments of type \Team\Model\PersonTeamLink or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonTeamLink relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function joinPersonTeamLink($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonTeamLink');

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
            $this->addJoinObject($join, 'PersonTeamLink');
        }

        return $this;
    }

    /**
     * Use the PersonTeamLink relation PersonTeamLink object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Team\Model\PersonTeamLinkQuery A secondary query class using the current class as primary query
     */
    public function usePersonTeamLinkQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonTeamLink($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonTeamLink', '\Team\Model\PersonTeamLinkQuery');
    }

    /**
     * Filter the query by a related \Team\Model\PersonImage object
     *
     * @param \Team\Model\PersonImage|ObjectCollection $personImage  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterByPersonImage($personImage, $comparison = null)
    {
        if ($personImage instanceof \Team\Model\PersonImage) {
            return $this
                ->addUsingAlias(PersonTableMap::ID, $personImage->getPersonId(), $comparison);
        } elseif ($personImage instanceof ObjectCollection) {
            return $this
                ->usePersonImageQuery()
                ->filterByPrimaryKeys($personImage->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonImage() only accepts arguments of type \Team\Model\PersonImage or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonImage relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function joinPersonImage($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonImage');

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
            $this->addJoinObject($join, 'PersonImage');
        }

        return $this;
    }

    /**
     * Use the PersonImage relation PersonImage object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Team\Model\PersonImageQuery A secondary query class using the current class as primary query
     */
    public function usePersonImageQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPersonImage($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonImage', '\Team\Model\PersonImageQuery');
    }

    /**
     * Filter the query by a related \Team\Model\PersonFunctionLink object
     *
     * @param \Team\Model\PersonFunctionLink|ObjectCollection $personFunctionLink  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterByPersonFunctionLink($personFunctionLink, $comparison = null)
    {
        if ($personFunctionLink instanceof \Team\Model\PersonFunctionLink) {
            return $this
                ->addUsingAlias(PersonTableMap::ID, $personFunctionLink->getPersonId(), $comparison);
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
     * @return ChildPersonQuery The current query, for fluid interface
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
     * Filter the query by a related \Team\Model\PersonI18n object
     *
     * @param \Team\Model\PersonI18n|ObjectCollection $personI18n  the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function filterByPersonI18n($personI18n, $comparison = null)
    {
        if ($personI18n instanceof \Team\Model\PersonI18n) {
            return $this
                ->addUsingAlias(PersonTableMap::ID, $personI18n->getId(), $comparison);
        } elseif ($personI18n instanceof ObjectCollection) {
            return $this
                ->usePersonI18nQuery()
                ->filterByPrimaryKeys($personI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPersonI18n() only accepts arguments of type \Team\Model\PersonI18n or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PersonI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function joinPersonI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PersonI18n');

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
            $this->addJoinObject($join, 'PersonI18n');
        }

        return $this;
    }

    /**
     * Use the PersonI18n relation PersonI18n object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Team\Model\PersonI18nQuery A secondary query class using the current class as primary query
     */
    public function usePersonI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinPersonI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonI18n', '\Team\Model\PersonI18nQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildPerson $person Object to remove from the list of results
     *
     * @return ChildPersonQuery The current query, for fluid interface
     */
    public function prune($person = null)
    {
        if ($person) {
            $this->addUsingAlias(PersonTableMap::ID, $person->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the person table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonTableMap::DATABASE_NAME);
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
            PersonTableMap::clearInstancePool();
            PersonTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildPerson or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildPerson object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(PersonTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(PersonTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        PersonTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            PersonTableMap::clearRelatedInstancePool();
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
     * @return     ChildPersonQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PersonTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildPersonQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PersonTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildPersonQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PersonTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildPersonQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PersonTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildPersonQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PersonTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildPersonQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PersonTableMap::CREATED_AT);
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildPersonQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'PersonI18n';

        return $this
            ->joinPersonI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    ChildPersonQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('PersonI18n');
        $this->with['PersonI18n']->setIsWithOneToMany(false);

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
     * @return    ChildPersonI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PersonI18n', '\Team\Model\PersonI18nQuery');
    }

} // PersonQuery
