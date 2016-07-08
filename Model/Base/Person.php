<?php

namespace Team\Model\Base;

use \DateTime;
use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use Team\Model\Person as ChildPerson;
use Team\Model\PersonFunctionLink as ChildPersonFunctionLink;
use Team\Model\PersonFunctionLinkQuery as ChildPersonFunctionLinkQuery;
use Team\Model\PersonI18n as ChildPersonI18n;
use Team\Model\PersonI18nQuery as ChildPersonI18nQuery;
use Team\Model\PersonImage as ChildPersonImage;
use Team\Model\PersonImageQuery as ChildPersonImageQuery;
use Team\Model\PersonQuery as ChildPersonQuery;
use Team\Model\PersonTeamLink as ChildPersonTeamLink;
use Team\Model\PersonTeamLinkQuery as ChildPersonTeamLinkQuery;
use Team\Model\Map\PersonTableMap;

abstract class Person implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Team\\Model\\Map\\PersonTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the first_name field.
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the last_name field.
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        ObjectCollection|ChildPersonTeamLink[] Collection to store aggregation of ChildPersonTeamLink objects.
     */
    protected $collPersonTeamLinks;
    protected $collPersonTeamLinksPartial;

    /**
     * @var        ObjectCollection|ChildPersonImage[] Collection to store aggregation of ChildPersonImage objects.
     */
    protected $collPersonImages;
    protected $collPersonImagesPartial;

    /**
     * @var        ObjectCollection|ChildPersonFunctionLink[] Collection to store aggregation of ChildPersonFunctionLink objects.
     */
    protected $collPersonFunctionLinks;
    protected $collPersonFunctionLinksPartial;

    /**
     * @var        ObjectCollection|ChildPersonI18n[] Collection to store aggregation of ChildPersonI18n objects.
     */
    protected $collPersonI18ns;
    protected $collPersonI18nsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // i18n behavior

    /**
     * Current locale
     * @var        string
     */
    protected $currentLocale = 'en_US';

    /**
     * Current translation objects
     * @var        array[ChildPersonI18n]
     */
    protected $currentTranslations;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $personTeamLinksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $personImagesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $personFunctionLinksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $personI18nsScheduledForDeletion = null;

    /**
     * Initializes internal state of Team\Model\Base\Person object.
     */
    public function __construct()
    {
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Person</code> instance.  If
     * <code>obj</code> is an instance of <code>Person</code>, delegates to
     * <code>equals(Person)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        $thisclazz = get_class($this);
        if (!is_object($obj) || !($obj instanceof $thisclazz)) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey()
            || null === $obj->getPrimaryKey())  {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return Person The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     *
     * @return Person The current object, for fluid interface
     */
    public function importFrom($parser, $data)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), TableMap::TYPE_PHPNAME);

        return $this;
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return   int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return   string
     */
    public function getFirstName()
    {

        return $this->first_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return   string
     */
    public function getLastName()
    {

        return $this->last_name;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTime ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTime ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PersonTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [first_name] column.
     *
     * @param      string $v new value
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[PersonTableMap::FIRST_NAME] = true;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param      string $v new value
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[PersonTableMap::LAST_NAME] = true;
        }


        return $this;
    } // setLastName()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[PersonTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[PersonTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PersonTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PersonTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->first_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PersonTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PersonTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PersonTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 5; // 5 = PersonTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \Team\Model\Person object", 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PersonTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPersonQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPersonTeamLinks = null;

            $this->collPersonImages = null;

            $this->collPersonFunctionLinks = null;

            $this->collPersonI18ns = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Person::setDeleted()
     * @see Person::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildPersonQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PersonTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PersonTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PersonTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PersonTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->personTeamLinksScheduledForDeletion !== null) {
                if (!$this->personTeamLinksScheduledForDeletion->isEmpty()) {
                    \Team\Model\PersonTeamLinkQuery::create()
                        ->filterByPrimaryKeys($this->personTeamLinksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personTeamLinksScheduledForDeletion = null;
                }
            }

                if ($this->collPersonTeamLinks !== null) {
            foreach ($this->collPersonTeamLinks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->personImagesScheduledForDeletion !== null) {
                if (!$this->personImagesScheduledForDeletion->isEmpty()) {
                    \Team\Model\PersonImageQuery::create()
                        ->filterByPrimaryKeys($this->personImagesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personImagesScheduledForDeletion = null;
                }
            }

                if ($this->collPersonImages !== null) {
            foreach ($this->collPersonImages as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->personFunctionLinksScheduledForDeletion !== null) {
                if (!$this->personFunctionLinksScheduledForDeletion->isEmpty()) {
                    \Team\Model\PersonFunctionLinkQuery::create()
                        ->filterByPrimaryKeys($this->personFunctionLinksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personFunctionLinksScheduledForDeletion = null;
                }
            }

                if ($this->collPersonFunctionLinks !== null) {
            foreach ($this->collPersonFunctionLinks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->personI18nsScheduledForDeletion !== null) {
                if (!$this->personI18nsScheduledForDeletion->isEmpty()) {
                    \Team\Model\PersonI18nQuery::create()
                        ->filterByPrimaryKeys($this->personI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personI18nsScheduledForDeletion = null;
                }
            }

                if ($this->collPersonI18ns !== null) {
            foreach ($this->collPersonI18ns as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[PersonTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PersonTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PersonTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(PersonTableMap::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'FIRST_NAME';
        }
        if ($this->isColumnModified(PersonTableMap::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'LAST_NAME';
        }
        if ($this->isColumnModified(PersonTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(PersonTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }

        $sql = sprintf(
            'INSERT INTO person (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'FIRST_NAME':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case 'LAST_NAME':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PersonTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getFirstName();
                break;
            case 2:
                return $this->getLastName();
                break;
            case 3:
                return $this->getCreatedAt();
                break;
            case 4:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Person'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Person'][$this->getPrimaryKey()] = true;
        $keys = PersonTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFirstName(),
            $keys[2] => $this->getLastName(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPersonTeamLinks) {
                $result['PersonTeamLinks'] = $this->collPersonTeamLinks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPersonImages) {
                $result['PersonImages'] = $this->collPersonImages->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPersonFunctionLinks) {
                $result['PersonFunctionLinks'] = $this->collPersonFunctionLinks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPersonI18ns) {
                $result['PersonI18ns'] = $this->collPersonI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param      string $name
     * @param      mixed  $value field value
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return void
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = PersonTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @param      mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setFirstName($value);
                break;
            case 2:
                $this->setLastName($value);
                break;
            case 3:
                $this->setCreatedAt($value);
                break;
            case 4:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_STUDLYPHPNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = PersonTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setFirstName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setLastName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCreatedAt($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUpdatedAt($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PersonTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PersonTableMap::ID)) $criteria->add(PersonTableMap::ID, $this->id);
        if ($this->isColumnModified(PersonTableMap::FIRST_NAME)) $criteria->add(PersonTableMap::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(PersonTableMap::LAST_NAME)) $criteria->add(PersonTableMap::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(PersonTableMap::CREATED_AT)) $criteria->add(PersonTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PersonTableMap::UPDATED_AT)) $criteria->add(PersonTableMap::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(PersonTableMap::DATABASE_NAME);
        $criteria->add(PersonTableMap::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return   int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Team\Model\Person (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPersonTeamLinks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonTeamLink($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPersonImages() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonImage($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPersonFunctionLinks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonFunctionLink($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPersonI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonI18n($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return                 \Team\Model\Person Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('PersonTeamLink' == $relationName) {
            return $this->initPersonTeamLinks();
        }
        if ('PersonImage' == $relationName) {
            return $this->initPersonImages();
        }
        if ('PersonFunctionLink' == $relationName) {
            return $this->initPersonFunctionLinks();
        }
        if ('PersonI18n' == $relationName) {
            return $this->initPersonI18ns();
        }
    }

    /**
     * Clears out the collPersonTeamLinks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPersonTeamLinks()
     */
    public function clearPersonTeamLinks()
    {
        $this->collPersonTeamLinks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPersonTeamLinks collection loaded partially.
     */
    public function resetPartialPersonTeamLinks($v = true)
    {
        $this->collPersonTeamLinksPartial = $v;
    }

    /**
     * Initializes the collPersonTeamLinks collection.
     *
     * By default this just sets the collPersonTeamLinks collection to an empty array (like clearcollPersonTeamLinks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonTeamLinks($overrideExisting = true)
    {
        if (null !== $this->collPersonTeamLinks && !$overrideExisting) {
            return;
        }
        $this->collPersonTeamLinks = new ObjectCollection();
        $this->collPersonTeamLinks->setModel('\Team\Model\PersonTeamLink');
    }

    /**
     * Gets an array of ChildPersonTeamLink objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPerson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildPersonTeamLink[] List of ChildPersonTeamLink objects
     * @throws PropelException
     */
    public function getPersonTeamLinks($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonTeamLinksPartial && !$this->isNew();
        if (null === $this->collPersonTeamLinks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonTeamLinks) {
                // return empty collection
                $this->initPersonTeamLinks();
            } else {
                $collPersonTeamLinks = ChildPersonTeamLinkQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPersonTeamLinksPartial && count($collPersonTeamLinks)) {
                        $this->initPersonTeamLinks(false);

                        foreach ($collPersonTeamLinks as $obj) {
                            if (false == $this->collPersonTeamLinks->contains($obj)) {
                                $this->collPersonTeamLinks->append($obj);
                            }
                        }

                        $this->collPersonTeamLinksPartial = true;
                    }

                    reset($collPersonTeamLinks);

                    return $collPersonTeamLinks;
                }

                if ($partial && $this->collPersonTeamLinks) {
                    foreach ($this->collPersonTeamLinks as $obj) {
                        if ($obj->isNew()) {
                            $collPersonTeamLinks[] = $obj;
                        }
                    }
                }

                $this->collPersonTeamLinks = $collPersonTeamLinks;
                $this->collPersonTeamLinksPartial = false;
            }
        }

        return $this->collPersonTeamLinks;
    }

    /**
     * Sets a collection of PersonTeamLink objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $personTeamLinks A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildPerson The current object (for fluent API support)
     */
    public function setPersonTeamLinks(Collection $personTeamLinks, ConnectionInterface $con = null)
    {
        $personTeamLinksToDelete = $this->getPersonTeamLinks(new Criteria(), $con)->diff($personTeamLinks);


        $this->personTeamLinksScheduledForDeletion = $personTeamLinksToDelete;

        foreach ($personTeamLinksToDelete as $personTeamLinkRemoved) {
            $personTeamLinkRemoved->setPerson(null);
        }

        $this->collPersonTeamLinks = null;
        foreach ($personTeamLinks as $personTeamLink) {
            $this->addPersonTeamLink($personTeamLink);
        }

        $this->collPersonTeamLinks = $personTeamLinks;
        $this->collPersonTeamLinksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PersonTeamLink objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PersonTeamLink objects.
     * @throws PropelException
     */
    public function countPersonTeamLinks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonTeamLinksPartial && !$this->isNew();
        if (null === $this->collPersonTeamLinks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonTeamLinks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPersonTeamLinks());
            }

            $query = ChildPersonTeamLinkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPerson($this)
                ->count($con);
        }

        return count($this->collPersonTeamLinks);
    }

    /**
     * Method called to associate a ChildPersonTeamLink object to this object
     * through the ChildPersonTeamLink foreign key attribute.
     *
     * @param    ChildPersonTeamLink $l ChildPersonTeamLink
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function addPersonTeamLink(ChildPersonTeamLink $l)
    {
        if ($this->collPersonTeamLinks === null) {
            $this->initPersonTeamLinks();
            $this->collPersonTeamLinksPartial = true;
        }

        if (!in_array($l, $this->collPersonTeamLinks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonTeamLink($l);
        }

        return $this;
    }

    /**
     * @param PersonTeamLink $personTeamLink The personTeamLink object to add.
     */
    protected function doAddPersonTeamLink($personTeamLink)
    {
        $this->collPersonTeamLinks[]= $personTeamLink;
        $personTeamLink->setPerson($this);
    }

    /**
     * @param  PersonTeamLink $personTeamLink The personTeamLink object to remove.
     * @return ChildPerson The current object (for fluent API support)
     */
    public function removePersonTeamLink($personTeamLink)
    {
        if ($this->getPersonTeamLinks()->contains($personTeamLink)) {
            $this->collPersonTeamLinks->remove($this->collPersonTeamLinks->search($personTeamLink));
            if (null === $this->personTeamLinksScheduledForDeletion) {
                $this->personTeamLinksScheduledForDeletion = clone $this->collPersonTeamLinks;
                $this->personTeamLinksScheduledForDeletion->clear();
            }
            $this->personTeamLinksScheduledForDeletion[]= clone $personTeamLink;
            $personTeamLink->setPerson(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Person is new, it will return
     * an empty collection; or if this Person has previously
     * been saved, it will retrieve related PersonTeamLinks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Person.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildPersonTeamLink[] List of ChildPersonTeamLink objects
     */
    public function getPersonTeamLinksJoinTeam($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPersonTeamLinkQuery::create(null, $criteria);
        $query->joinWith('Team', $joinBehavior);

        return $this->getPersonTeamLinks($query, $con);
    }

    /**
     * Clears out the collPersonImages collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPersonImages()
     */
    public function clearPersonImages()
    {
        $this->collPersonImages = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPersonImages collection loaded partially.
     */
    public function resetPartialPersonImages($v = true)
    {
        $this->collPersonImagesPartial = $v;
    }

    /**
     * Initializes the collPersonImages collection.
     *
     * By default this just sets the collPersonImages collection to an empty array (like clearcollPersonImages());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonImages($overrideExisting = true)
    {
        if (null !== $this->collPersonImages && !$overrideExisting) {
            return;
        }
        $this->collPersonImages = new ObjectCollection();
        $this->collPersonImages->setModel('\Team\Model\PersonImage');
    }

    /**
     * Gets an array of ChildPersonImage objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPerson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildPersonImage[] List of ChildPersonImage objects
     * @throws PropelException
     */
    public function getPersonImages($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonImagesPartial && !$this->isNew();
        if (null === $this->collPersonImages || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonImages) {
                // return empty collection
                $this->initPersonImages();
            } else {
                $collPersonImages = ChildPersonImageQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPersonImagesPartial && count($collPersonImages)) {
                        $this->initPersonImages(false);

                        foreach ($collPersonImages as $obj) {
                            if (false == $this->collPersonImages->contains($obj)) {
                                $this->collPersonImages->append($obj);
                            }
                        }

                        $this->collPersonImagesPartial = true;
                    }

                    reset($collPersonImages);

                    return $collPersonImages;
                }

                if ($partial && $this->collPersonImages) {
                    foreach ($this->collPersonImages as $obj) {
                        if ($obj->isNew()) {
                            $collPersonImages[] = $obj;
                        }
                    }
                }

                $this->collPersonImages = $collPersonImages;
                $this->collPersonImagesPartial = false;
            }
        }

        return $this->collPersonImages;
    }

    /**
     * Sets a collection of PersonImage objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $personImages A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildPerson The current object (for fluent API support)
     */
    public function setPersonImages(Collection $personImages, ConnectionInterface $con = null)
    {
        $personImagesToDelete = $this->getPersonImages(new Criteria(), $con)->diff($personImages);


        $this->personImagesScheduledForDeletion = $personImagesToDelete;

        foreach ($personImagesToDelete as $personImageRemoved) {
            $personImageRemoved->setPerson(null);
        }

        $this->collPersonImages = null;
        foreach ($personImages as $personImage) {
            $this->addPersonImage($personImage);
        }

        $this->collPersonImages = $personImages;
        $this->collPersonImagesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PersonImage objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PersonImage objects.
     * @throws PropelException
     */
    public function countPersonImages(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonImagesPartial && !$this->isNew();
        if (null === $this->collPersonImages || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonImages) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPersonImages());
            }

            $query = ChildPersonImageQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPerson($this)
                ->count($con);
        }

        return count($this->collPersonImages);
    }

    /**
     * Method called to associate a ChildPersonImage object to this object
     * through the ChildPersonImage foreign key attribute.
     *
     * @param    ChildPersonImage $l ChildPersonImage
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function addPersonImage(ChildPersonImage $l)
    {
        if ($this->collPersonImages === null) {
            $this->initPersonImages();
            $this->collPersonImagesPartial = true;
        }

        if (!in_array($l, $this->collPersonImages->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonImage($l);
        }

        return $this;
    }

    /**
     * @param PersonImage $personImage The personImage object to add.
     */
    protected function doAddPersonImage($personImage)
    {
        $this->collPersonImages[]= $personImage;
        $personImage->setPerson($this);
    }

    /**
     * @param  PersonImage $personImage The personImage object to remove.
     * @return ChildPerson The current object (for fluent API support)
     */
    public function removePersonImage($personImage)
    {
        if ($this->getPersonImages()->contains($personImage)) {
            $this->collPersonImages->remove($this->collPersonImages->search($personImage));
            if (null === $this->personImagesScheduledForDeletion) {
                $this->personImagesScheduledForDeletion = clone $this->collPersonImages;
                $this->personImagesScheduledForDeletion->clear();
            }
            $this->personImagesScheduledForDeletion[]= clone $personImage;
            $personImage->setPerson(null);
        }

        return $this;
    }

    /**
     * Clears out the collPersonFunctionLinks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPersonFunctionLinks()
     */
    public function clearPersonFunctionLinks()
    {
        $this->collPersonFunctionLinks = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPersonFunctionLinks collection loaded partially.
     */
    public function resetPartialPersonFunctionLinks($v = true)
    {
        $this->collPersonFunctionLinksPartial = $v;
    }

    /**
     * Initializes the collPersonFunctionLinks collection.
     *
     * By default this just sets the collPersonFunctionLinks collection to an empty array (like clearcollPersonFunctionLinks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonFunctionLinks($overrideExisting = true)
    {
        if (null !== $this->collPersonFunctionLinks && !$overrideExisting) {
            return;
        }
        $this->collPersonFunctionLinks = new ObjectCollection();
        $this->collPersonFunctionLinks->setModel('\Team\Model\PersonFunctionLink');
    }

    /**
     * Gets an array of ChildPersonFunctionLink objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPerson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildPersonFunctionLink[] List of ChildPersonFunctionLink objects
     * @throws PropelException
     */
    public function getPersonFunctionLinks($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonFunctionLinksPartial && !$this->isNew();
        if (null === $this->collPersonFunctionLinks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonFunctionLinks) {
                // return empty collection
                $this->initPersonFunctionLinks();
            } else {
                $collPersonFunctionLinks = ChildPersonFunctionLinkQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPersonFunctionLinksPartial && count($collPersonFunctionLinks)) {
                        $this->initPersonFunctionLinks(false);

                        foreach ($collPersonFunctionLinks as $obj) {
                            if (false == $this->collPersonFunctionLinks->contains($obj)) {
                                $this->collPersonFunctionLinks->append($obj);
                            }
                        }

                        $this->collPersonFunctionLinksPartial = true;
                    }

                    reset($collPersonFunctionLinks);

                    return $collPersonFunctionLinks;
                }

                if ($partial && $this->collPersonFunctionLinks) {
                    foreach ($this->collPersonFunctionLinks as $obj) {
                        if ($obj->isNew()) {
                            $collPersonFunctionLinks[] = $obj;
                        }
                    }
                }

                $this->collPersonFunctionLinks = $collPersonFunctionLinks;
                $this->collPersonFunctionLinksPartial = false;
            }
        }

        return $this->collPersonFunctionLinks;
    }

    /**
     * Sets a collection of PersonFunctionLink objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $personFunctionLinks A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildPerson The current object (for fluent API support)
     */
    public function setPersonFunctionLinks(Collection $personFunctionLinks, ConnectionInterface $con = null)
    {
        $personFunctionLinksToDelete = $this->getPersonFunctionLinks(new Criteria(), $con)->diff($personFunctionLinks);


        $this->personFunctionLinksScheduledForDeletion = $personFunctionLinksToDelete;

        foreach ($personFunctionLinksToDelete as $personFunctionLinkRemoved) {
            $personFunctionLinkRemoved->setPerson(null);
        }

        $this->collPersonFunctionLinks = null;
        foreach ($personFunctionLinks as $personFunctionLink) {
            $this->addPersonFunctionLink($personFunctionLink);
        }

        $this->collPersonFunctionLinks = $personFunctionLinks;
        $this->collPersonFunctionLinksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PersonFunctionLink objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PersonFunctionLink objects.
     * @throws PropelException
     */
    public function countPersonFunctionLinks(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonFunctionLinksPartial && !$this->isNew();
        if (null === $this->collPersonFunctionLinks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonFunctionLinks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPersonFunctionLinks());
            }

            $query = ChildPersonFunctionLinkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPerson($this)
                ->count($con);
        }

        return count($this->collPersonFunctionLinks);
    }

    /**
     * Method called to associate a ChildPersonFunctionLink object to this object
     * through the ChildPersonFunctionLink foreign key attribute.
     *
     * @param    ChildPersonFunctionLink $l ChildPersonFunctionLink
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function addPersonFunctionLink(ChildPersonFunctionLink $l)
    {
        if ($this->collPersonFunctionLinks === null) {
            $this->initPersonFunctionLinks();
            $this->collPersonFunctionLinksPartial = true;
        }

        if (!in_array($l, $this->collPersonFunctionLinks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonFunctionLink($l);
        }

        return $this;
    }

    /**
     * @param PersonFunctionLink $personFunctionLink The personFunctionLink object to add.
     */
    protected function doAddPersonFunctionLink($personFunctionLink)
    {
        $this->collPersonFunctionLinks[]= $personFunctionLink;
        $personFunctionLink->setPerson($this);
    }

    /**
     * @param  PersonFunctionLink $personFunctionLink The personFunctionLink object to remove.
     * @return ChildPerson The current object (for fluent API support)
     */
    public function removePersonFunctionLink($personFunctionLink)
    {
        if ($this->getPersonFunctionLinks()->contains($personFunctionLink)) {
            $this->collPersonFunctionLinks->remove($this->collPersonFunctionLinks->search($personFunctionLink));
            if (null === $this->personFunctionLinksScheduledForDeletion) {
                $this->personFunctionLinksScheduledForDeletion = clone $this->collPersonFunctionLinks;
                $this->personFunctionLinksScheduledForDeletion->clear();
            }
            $this->personFunctionLinksScheduledForDeletion[]= clone $personFunctionLink;
            $personFunctionLink->setPerson(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Person is new, it will return
     * an empty collection; or if this Person has previously
     * been saved, it will retrieve related PersonFunctionLinks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Person.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildPersonFunctionLink[] List of ChildPersonFunctionLink objects
     */
    public function getPersonFunctionLinksJoinPersonFunction($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPersonFunctionLinkQuery::create(null, $criteria);
        $query->joinWith('PersonFunction', $joinBehavior);

        return $this->getPersonFunctionLinks($query, $con);
    }

    /**
     * Clears out the collPersonI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPersonI18ns()
     */
    public function clearPersonI18ns()
    {
        $this->collPersonI18ns = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPersonI18ns collection loaded partially.
     */
    public function resetPartialPersonI18ns($v = true)
    {
        $this->collPersonI18nsPartial = $v;
    }

    /**
     * Initializes the collPersonI18ns collection.
     *
     * By default this just sets the collPersonI18ns collection to an empty array (like clearcollPersonI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonI18ns($overrideExisting = true)
    {
        if (null !== $this->collPersonI18ns && !$overrideExisting) {
            return;
        }
        $this->collPersonI18ns = new ObjectCollection();
        $this->collPersonI18ns->setModel('\Team\Model\PersonI18n');
    }

    /**
     * Gets an array of ChildPersonI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPerson is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildPersonI18n[] List of ChildPersonI18n objects
     * @throws PropelException
     */
    public function getPersonI18ns($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonI18nsPartial && !$this->isNew();
        if (null === $this->collPersonI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonI18ns) {
                // return empty collection
                $this->initPersonI18ns();
            } else {
                $collPersonI18ns = ChildPersonI18nQuery::create(null, $criteria)
                    ->filterByPerson($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPersonI18nsPartial && count($collPersonI18ns)) {
                        $this->initPersonI18ns(false);

                        foreach ($collPersonI18ns as $obj) {
                            if (false == $this->collPersonI18ns->contains($obj)) {
                                $this->collPersonI18ns->append($obj);
                            }
                        }

                        $this->collPersonI18nsPartial = true;
                    }

                    reset($collPersonI18ns);

                    return $collPersonI18ns;
                }

                if ($partial && $this->collPersonI18ns) {
                    foreach ($this->collPersonI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collPersonI18ns[] = $obj;
                        }
                    }
                }

                $this->collPersonI18ns = $collPersonI18ns;
                $this->collPersonI18nsPartial = false;
            }
        }

        return $this->collPersonI18ns;
    }

    /**
     * Sets a collection of PersonI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $personI18ns A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildPerson The current object (for fluent API support)
     */
    public function setPersonI18ns(Collection $personI18ns, ConnectionInterface $con = null)
    {
        $personI18nsToDelete = $this->getPersonI18ns(new Criteria(), $con)->diff($personI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->personI18nsScheduledForDeletion = clone $personI18nsToDelete;

        foreach ($personI18nsToDelete as $personI18nRemoved) {
            $personI18nRemoved->setPerson(null);
        }

        $this->collPersonI18ns = null;
        foreach ($personI18ns as $personI18n) {
            $this->addPersonI18n($personI18n);
        }

        $this->collPersonI18ns = $personI18ns;
        $this->collPersonI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PersonI18n objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PersonI18n objects.
     * @throws PropelException
     */
    public function countPersonI18ns(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonI18nsPartial && !$this->isNew();
        if (null === $this->collPersonI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPersonI18ns());
            }

            $query = ChildPersonI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPerson($this)
                ->count($con);
        }

        return count($this->collPersonI18ns);
    }

    /**
     * Method called to associate a ChildPersonI18n object to this object
     * through the ChildPersonI18n foreign key attribute.
     *
     * @param    ChildPersonI18n $l ChildPersonI18n
     * @return   \Team\Model\Person The current object (for fluent API support)
     */
    public function addPersonI18n(ChildPersonI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collPersonI18ns === null) {
            $this->initPersonI18ns();
            $this->collPersonI18nsPartial = true;
        }

        if (!in_array($l, $this->collPersonI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonI18n($l);
        }

        return $this;
    }

    /**
     * @param PersonI18n $personI18n The personI18n object to add.
     */
    protected function doAddPersonI18n($personI18n)
    {
        $this->collPersonI18ns[]= $personI18n;
        $personI18n->setPerson($this);
    }

    /**
     * @param  PersonI18n $personI18n The personI18n object to remove.
     * @return ChildPerson The current object (for fluent API support)
     */
    public function removePersonI18n($personI18n)
    {
        if ($this->getPersonI18ns()->contains($personI18n)) {
            $this->collPersonI18ns->remove($this->collPersonI18ns->search($personI18n));
            if (null === $this->personI18nsScheduledForDeletion) {
                $this->personI18nsScheduledForDeletion = clone $this->collPersonI18ns;
                $this->personI18nsScheduledForDeletion->clear();
            }
            $this->personI18nsScheduledForDeletion[]= clone $personI18n;
            $personI18n->setPerson(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->first_name = null;
        $this->last_name = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collPersonTeamLinks) {
                foreach ($this->collPersonTeamLinks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPersonImages) {
                foreach ($this->collPersonImages as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPersonFunctionLinks) {
                foreach ($this->collPersonFunctionLinks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPersonI18ns) {
                foreach ($this->collPersonI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        $this->collPersonTeamLinks = null;
        $this->collPersonImages = null;
        $this->collPersonFunctionLinks = null;
        $this->collPersonI18ns = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PersonTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     ChildPerson The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PersonTableMap::UPDATED_AT] = true;

        return $this;
    }

    // i18n behavior

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     *
     * @return    ChildPerson The current object (for fluent API support)
     */
    public function setLocale($locale = 'en_US')
    {
        $this->currentLocale = $locale;

        return $this;
    }

    /**
     * Gets the locale for translations
     *
     * @return    string $locale Locale to use for the translation, e.g. 'fr_FR'
     */
    public function getLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the current translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildPersonI18n */
    public function getTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collPersonI18ns) {
                foreach ($this->collPersonI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new ChildPersonI18n();
                $translation->setLocale($locale);
            } else {
                $translation = ChildPersonI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addPersonI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return    ChildPerson The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!$this->isNew()) {
            ChildPersonI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collPersonI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collPersonI18ns[$key]);
                break;
            }
        }

        return $this;
    }

    /**
     * Returns the current translation
     *
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ChildPersonI18n */
    public function getCurrentTranslation(ConnectionInterface $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [description] column value.
         *
         * @return   string
         */
        public function getDescription()
        {
        return $this->getCurrentTranslation()->getDescription();
    }


        /**
         * Set the value of [description] column.
         *
         * @param      string $v new value
         * @return   \Team\Model\PersonI18n The current object (for fluent API support)
         */
        public function setDescription($v)
        {    $this->getCurrentTranslation()->setDescription($v);

        return $this;
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {

    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {

    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
