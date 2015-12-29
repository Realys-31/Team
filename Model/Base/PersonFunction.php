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
use Team\Model\PersonFunction as ChildPersonFunction;
use Team\Model\PersonFunctionI18n as ChildPersonFunctionI18n;
use Team\Model\PersonFunctionI18nQuery as ChildPersonFunctionI18nQuery;
use Team\Model\PersonFunctionLink as ChildPersonFunctionLink;
use Team\Model\PersonFunctionLinkQuery as ChildPersonFunctionLinkQuery;
use Team\Model\PersonFunctionLinkVersionQuery as ChildPersonFunctionLinkVersionQuery;
use Team\Model\PersonFunctionQuery as ChildPersonFunctionQuery;
use Team\Model\PersonFunctionVersion as ChildPersonFunctionVersion;
use Team\Model\PersonFunctionVersionQuery as ChildPersonFunctionVersionQuery;
use Team\Model\Map\PersonFunctionLinkVersionTableMap;
use Team\Model\Map\PersonFunctionTableMap;
use Team\Model\Map\PersonFunctionVersionTableMap;

abstract class PersonFunction implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Team\\Model\\Map\\PersonFunctionTableMap';


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
     * The value for the code field.
     * @var        string
     */
    protected $code;

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
     * The value for the version field.
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $version;

    /**
     * The value for the version_created_at field.
     * @var        string
     */
    protected $version_created_at;

    /**
     * The value for the version_created_by field.
     * @var        string
     */
    protected $version_created_by;

    /**
     * @var        ObjectCollection|ChildPersonFunctionLink[] Collection to store aggregation of ChildPersonFunctionLink objects.
     */
    protected $collPersonFunctionLinks;
    protected $collPersonFunctionLinksPartial;

    /**
     * @var        ObjectCollection|ChildPersonFunctionI18n[] Collection to store aggregation of ChildPersonFunctionI18n objects.
     */
    protected $collPersonFunctionI18ns;
    protected $collPersonFunctionI18nsPartial;

    /**
     * @var        ObjectCollection|ChildPersonFunctionVersion[] Collection to store aggregation of ChildPersonFunctionVersion objects.
     */
    protected $collPersonFunctionVersions;
    protected $collPersonFunctionVersionsPartial;

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
     * @var        array[ChildPersonFunctionI18n]
     */
    protected $currentTranslations;

    // versionable behavior


    /**
     * @var bool
     */
    protected $enforceVersion = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $personFunctionLinksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $personFunctionI18nsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection
     */
    protected $personFunctionVersionsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->version = 0;
    }

    /**
     * Initializes internal state of Team\Model\Base\PersonFunction object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
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
     * Compares this with another <code>PersonFunction</code> instance.  If
     * <code>obj</code> is an instance of <code>PersonFunction</code>, delegates to
     * <code>equals(PersonFunction)</code>.  Otherwise, returns <code>false</code>.
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
     * @return PersonFunction The current object, for fluid interface
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
     * @return PersonFunction The current object, for fluid interface
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
     * Get the [code] column value.
     *
     * @return   string
     */
    public function getCode()
    {

        return $this->code;
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
     * Get the [version] column value.
     *
     * @return   int
     */
    public function getVersion()
    {

        return $this->version;
    }

    /**
     * Get the [optionally formatted] temporal [version_created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw \DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or \DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getVersionCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->version_created_at;
        } else {
            return $this->version_created_at instanceof \DateTime ? $this->version_created_at->format($format) : null;
        }
    }

    /**
     * Get the [version_created_by] column value.
     *
     * @return   string
     */
    public function getVersionCreatedBy()
    {

        return $this->version_created_by;
    }

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PersonFunctionTableMap::ID] = true;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [code] column.
     *
     * @param      string $v new value
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[PersonFunctionTableMap::CODE] = true;
        }


        return $this;
    } // setCode()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[PersonFunctionTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[PersonFunctionTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [version] column.
     *
     * @param      int $v new value
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[PersonFunctionTableMap::VERSION] = true;
        }


        return $this;
    } // setVersion()

    /**
     * Sets the value of [version_created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function setVersionCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->version_created_at !== null || $dt !== null) {
            if ($dt !== $this->version_created_at) {
                $this->version_created_at = $dt;
                $this->modifiedColumns[PersonFunctionTableMap::VERSION_CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setVersionCreatedAt()

    /**
     * Set the value of [version_created_by] column.
     *
     * @param      string $v new value
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function setVersionCreatedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->version_created_by !== $v) {
            $this->version_created_by = $v;
            $this->modifiedColumns[PersonFunctionTableMap::VERSION_CREATED_BY] = true;
        }


        return $this;
    } // setVersionCreatedBy()

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
            if ($this->version !== 0) {
                return false;
            }

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


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PersonFunctionTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PersonFunctionTableMap::translateFieldName('Code', TableMap::TYPE_PHPNAME, $indexType)];
            $this->code = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PersonFunctionTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PersonFunctionTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PersonFunctionTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PersonFunctionTableMap::translateFieldName('VersionCreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->version_created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PersonFunctionTableMap::translateFieldName('VersionCreatedBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version_created_by = (null !== $col) ? (string) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 7; // 7 = PersonFunctionTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \Team\Model\PersonFunction object", 0, $e);
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
            $con = Propel::getServiceContainer()->getReadConnection(PersonFunctionTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPersonFunctionQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collPersonFunctionLinks = null;

            $this->collPersonFunctionI18ns = null;

            $this->collPersonFunctionVersions = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see PersonFunction::setDeleted()
     * @see PersonFunction::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonFunctionTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildPersonFunctionQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PersonFunctionTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // versionable behavior
            if ($this->isVersioningNecessary()) {
                $this->setVersion($this->isNew() ? 1 : $this->getLastVersionNumber($con) + 1);
                if (!$this->isColumnModified(PersonFunctionTableMap::VERSION_CREATED_AT)) {
                    $this->setVersionCreatedAt(time());
                }
                $createVersion = true; // for postSave hook
            }
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(PersonFunctionTableMap::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(PersonFunctionTableMap::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(PersonFunctionTableMap::UPDATED_AT)) {
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
                // versionable behavior
                if (isset($createVersion)) {
                    $this->addVersion($con);
                }
                PersonFunctionTableMap::addInstanceToPool($this);
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

            if ($this->personFunctionI18nsScheduledForDeletion !== null) {
                if (!$this->personFunctionI18nsScheduledForDeletion->isEmpty()) {
                    \Team\Model\PersonFunctionI18nQuery::create()
                        ->filterByPrimaryKeys($this->personFunctionI18nsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personFunctionI18nsScheduledForDeletion = null;
                }
            }

                if ($this->collPersonFunctionI18ns !== null) {
            foreach ($this->collPersonFunctionI18ns as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->personFunctionVersionsScheduledForDeletion !== null) {
                if (!$this->personFunctionVersionsScheduledForDeletion->isEmpty()) {
                    \Team\Model\PersonFunctionVersionQuery::create()
                        ->filterByPrimaryKeys($this->personFunctionVersionsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->personFunctionVersionsScheduledForDeletion = null;
                }
            }

                if ($this->collPersonFunctionVersions !== null) {
            foreach ($this->collPersonFunctionVersions as $referrerFK) {
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

        $this->modifiedColumns[PersonFunctionTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PersonFunctionTableMap::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PersonFunctionTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(PersonFunctionTableMap::CODE)) {
            $modifiedColumns[':p' . $index++]  = 'CODE';
        }
        if ($this->isColumnModified(PersonFunctionTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(PersonFunctionTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }
        if ($this->isColumnModified(PersonFunctionTableMap::VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'VERSION';
        }
        if ($this->isColumnModified(PersonFunctionTableMap::VERSION_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'VERSION_CREATED_AT';
        }
        if ($this->isColumnModified(PersonFunctionTableMap::VERSION_CREATED_BY)) {
            $modifiedColumns[':p' . $index++]  = 'VERSION_CREATED_BY';
        }

        $sql = sprintf(
            'INSERT INTO person_function (%s) VALUES (%s)',
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
                    case 'CODE':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case 'CREATED_AT':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'UPDATED_AT':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'VERSION':
                        $stmt->bindValue($identifier, $this->version, PDO::PARAM_INT);
                        break;
                    case 'VERSION_CREATED_AT':
                        $stmt->bindValue($identifier, $this->version_created_at ? $this->version_created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'VERSION_CREATED_BY':
                        $stmt->bindValue($identifier, $this->version_created_by, PDO::PARAM_STR);
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
        $pos = PersonFunctionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getCode();
                break;
            case 2:
                return $this->getCreatedAt();
                break;
            case 3:
                return $this->getUpdatedAt();
                break;
            case 4:
                return $this->getVersion();
                break;
            case 5:
                return $this->getVersionCreatedAt();
                break;
            case 6:
                return $this->getVersionCreatedBy();
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
        if (isset($alreadyDumpedObjects['PersonFunction'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PersonFunction'][$this->getPrimaryKey()] = true;
        $keys = PersonFunctionTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCode(),
            $keys[2] => $this->getCreatedAt(),
            $keys[3] => $this->getUpdatedAt(),
            $keys[4] => $this->getVersion(),
            $keys[5] => $this->getVersionCreatedAt(),
            $keys[6] => $this->getVersionCreatedBy(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collPersonFunctionLinks) {
                $result['PersonFunctionLinks'] = $this->collPersonFunctionLinks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPersonFunctionI18ns) {
                $result['PersonFunctionI18ns'] = $this->collPersonFunctionI18ns->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collPersonFunctionVersions) {
                $result['PersonFunctionVersions'] = $this->collPersonFunctionVersions->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = PersonFunctionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
                $this->setCode($value);
                break;
            case 2:
                $this->setCreatedAt($value);
                break;
            case 3:
                $this->setUpdatedAt($value);
                break;
            case 4:
                $this->setVersion($value);
                break;
            case 5:
                $this->setVersionCreatedAt($value);
                break;
            case 6:
                $this->setVersionCreatedBy($value);
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
        $keys = PersonFunctionTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setCode($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setCreatedAt($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setUpdatedAt($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setVersion($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setVersionCreatedAt($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setVersionCreatedBy($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PersonFunctionTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PersonFunctionTableMap::ID)) $criteria->add(PersonFunctionTableMap::ID, $this->id);
        if ($this->isColumnModified(PersonFunctionTableMap::CODE)) $criteria->add(PersonFunctionTableMap::CODE, $this->code);
        if ($this->isColumnModified(PersonFunctionTableMap::CREATED_AT)) $criteria->add(PersonFunctionTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PersonFunctionTableMap::UPDATED_AT)) $criteria->add(PersonFunctionTableMap::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PersonFunctionTableMap::VERSION)) $criteria->add(PersonFunctionTableMap::VERSION, $this->version);
        if ($this->isColumnModified(PersonFunctionTableMap::VERSION_CREATED_AT)) $criteria->add(PersonFunctionTableMap::VERSION_CREATED_AT, $this->version_created_at);
        if ($this->isColumnModified(PersonFunctionTableMap::VERSION_CREATED_BY)) $criteria->add(PersonFunctionTableMap::VERSION_CREATED_BY, $this->version_created_by);

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
        $criteria = new Criteria(PersonFunctionTableMap::DATABASE_NAME);
        $criteria->add(PersonFunctionTableMap::ID, $this->id);

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
     * @param      object $copyObj An object of \Team\Model\PersonFunction (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCode($this->getCode());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setVersionCreatedAt($this->getVersionCreatedAt());
        $copyObj->setVersionCreatedBy($this->getVersionCreatedBy());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getPersonFunctionLinks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonFunctionLink($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPersonFunctionI18ns() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonFunctionI18n($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getPersonFunctionVersions() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addPersonFunctionVersion($relObj->copy($deepCopy));
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
     * @return                 \Team\Model\PersonFunction Clone of current object.
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
        if ('PersonFunctionLink' == $relationName) {
            return $this->initPersonFunctionLinks();
        }
        if ('PersonFunctionI18n' == $relationName) {
            return $this->initPersonFunctionI18ns();
        }
        if ('PersonFunctionVersion' == $relationName) {
            return $this->initPersonFunctionVersions();
        }
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
     * If this ChildPersonFunction is new, it will return
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
                    ->filterByPersonFunction($this)
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
     * @return   ChildPersonFunction The current object (for fluent API support)
     */
    public function setPersonFunctionLinks(Collection $personFunctionLinks, ConnectionInterface $con = null)
    {
        $personFunctionLinksToDelete = $this->getPersonFunctionLinks(new Criteria(), $con)->diff($personFunctionLinks);


        $this->personFunctionLinksScheduledForDeletion = $personFunctionLinksToDelete;

        foreach ($personFunctionLinksToDelete as $personFunctionLinkRemoved) {
            $personFunctionLinkRemoved->setPersonFunction(null);
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
                ->filterByPersonFunction($this)
                ->count($con);
        }

        return count($this->collPersonFunctionLinks);
    }

    /**
     * Method called to associate a ChildPersonFunctionLink object to this object
     * through the ChildPersonFunctionLink foreign key attribute.
     *
     * @param    ChildPersonFunctionLink $l ChildPersonFunctionLink
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
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
        $personFunctionLink->setPersonFunction($this);
    }

    /**
     * @param  PersonFunctionLink $personFunctionLink The personFunctionLink object to remove.
     * @return ChildPersonFunction The current object (for fluent API support)
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
            $personFunctionLink->setPersonFunction(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this PersonFunction is new, it will return
     * an empty collection; or if this PersonFunction has previously
     * been saved, it will retrieve related PersonFunctionLinks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in PersonFunction.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return Collection|ChildPersonFunctionLink[] List of ChildPersonFunctionLink objects
     */
    public function getPersonFunctionLinksJoinPerson($criteria = null, $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildPersonFunctionLinkQuery::create(null, $criteria);
        $query->joinWith('Person', $joinBehavior);

        return $this->getPersonFunctionLinks($query, $con);
    }

    /**
     * Clears out the collPersonFunctionI18ns collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPersonFunctionI18ns()
     */
    public function clearPersonFunctionI18ns()
    {
        $this->collPersonFunctionI18ns = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPersonFunctionI18ns collection loaded partially.
     */
    public function resetPartialPersonFunctionI18ns($v = true)
    {
        $this->collPersonFunctionI18nsPartial = $v;
    }

    /**
     * Initializes the collPersonFunctionI18ns collection.
     *
     * By default this just sets the collPersonFunctionI18ns collection to an empty array (like clearcollPersonFunctionI18ns());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonFunctionI18ns($overrideExisting = true)
    {
        if (null !== $this->collPersonFunctionI18ns && !$overrideExisting) {
            return;
        }
        $this->collPersonFunctionI18ns = new ObjectCollection();
        $this->collPersonFunctionI18ns->setModel('\Team\Model\PersonFunctionI18n');
    }

    /**
     * Gets an array of ChildPersonFunctionI18n objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPersonFunction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildPersonFunctionI18n[] List of ChildPersonFunctionI18n objects
     * @throws PropelException
     */
    public function getPersonFunctionI18ns($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonFunctionI18nsPartial && !$this->isNew();
        if (null === $this->collPersonFunctionI18ns || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonFunctionI18ns) {
                // return empty collection
                $this->initPersonFunctionI18ns();
            } else {
                $collPersonFunctionI18ns = ChildPersonFunctionI18nQuery::create(null, $criteria)
                    ->filterByPersonFunction($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPersonFunctionI18nsPartial && count($collPersonFunctionI18ns)) {
                        $this->initPersonFunctionI18ns(false);

                        foreach ($collPersonFunctionI18ns as $obj) {
                            if (false == $this->collPersonFunctionI18ns->contains($obj)) {
                                $this->collPersonFunctionI18ns->append($obj);
                            }
                        }

                        $this->collPersonFunctionI18nsPartial = true;
                    }

                    reset($collPersonFunctionI18ns);

                    return $collPersonFunctionI18ns;
                }

                if ($partial && $this->collPersonFunctionI18ns) {
                    foreach ($this->collPersonFunctionI18ns as $obj) {
                        if ($obj->isNew()) {
                            $collPersonFunctionI18ns[] = $obj;
                        }
                    }
                }

                $this->collPersonFunctionI18ns = $collPersonFunctionI18ns;
                $this->collPersonFunctionI18nsPartial = false;
            }
        }

        return $this->collPersonFunctionI18ns;
    }

    /**
     * Sets a collection of PersonFunctionI18n objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $personFunctionI18ns A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildPersonFunction The current object (for fluent API support)
     */
    public function setPersonFunctionI18ns(Collection $personFunctionI18ns, ConnectionInterface $con = null)
    {
        $personFunctionI18nsToDelete = $this->getPersonFunctionI18ns(new Criteria(), $con)->diff($personFunctionI18ns);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->personFunctionI18nsScheduledForDeletion = clone $personFunctionI18nsToDelete;

        foreach ($personFunctionI18nsToDelete as $personFunctionI18nRemoved) {
            $personFunctionI18nRemoved->setPersonFunction(null);
        }

        $this->collPersonFunctionI18ns = null;
        foreach ($personFunctionI18ns as $personFunctionI18n) {
            $this->addPersonFunctionI18n($personFunctionI18n);
        }

        $this->collPersonFunctionI18ns = $personFunctionI18ns;
        $this->collPersonFunctionI18nsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PersonFunctionI18n objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PersonFunctionI18n objects.
     * @throws PropelException
     */
    public function countPersonFunctionI18ns(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonFunctionI18nsPartial && !$this->isNew();
        if (null === $this->collPersonFunctionI18ns || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonFunctionI18ns) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPersonFunctionI18ns());
            }

            $query = ChildPersonFunctionI18nQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPersonFunction($this)
                ->count($con);
        }

        return count($this->collPersonFunctionI18ns);
    }

    /**
     * Method called to associate a ChildPersonFunctionI18n object to this object
     * through the ChildPersonFunctionI18n foreign key attribute.
     *
     * @param    ChildPersonFunctionI18n $l ChildPersonFunctionI18n
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function addPersonFunctionI18n(ChildPersonFunctionI18n $l)
    {
        if ($l && $locale = $l->getLocale()) {
            $this->setLocale($locale);
            $this->currentTranslations[$locale] = $l;
        }
        if ($this->collPersonFunctionI18ns === null) {
            $this->initPersonFunctionI18ns();
            $this->collPersonFunctionI18nsPartial = true;
        }

        if (!in_array($l, $this->collPersonFunctionI18ns->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonFunctionI18n($l);
        }

        return $this;
    }

    /**
     * @param PersonFunctionI18n $personFunctionI18n The personFunctionI18n object to add.
     */
    protected function doAddPersonFunctionI18n($personFunctionI18n)
    {
        $this->collPersonFunctionI18ns[]= $personFunctionI18n;
        $personFunctionI18n->setPersonFunction($this);
    }

    /**
     * @param  PersonFunctionI18n $personFunctionI18n The personFunctionI18n object to remove.
     * @return ChildPersonFunction The current object (for fluent API support)
     */
    public function removePersonFunctionI18n($personFunctionI18n)
    {
        if ($this->getPersonFunctionI18ns()->contains($personFunctionI18n)) {
            $this->collPersonFunctionI18ns->remove($this->collPersonFunctionI18ns->search($personFunctionI18n));
            if (null === $this->personFunctionI18nsScheduledForDeletion) {
                $this->personFunctionI18nsScheduledForDeletion = clone $this->collPersonFunctionI18ns;
                $this->personFunctionI18nsScheduledForDeletion->clear();
            }
            $this->personFunctionI18nsScheduledForDeletion[]= clone $personFunctionI18n;
            $personFunctionI18n->setPersonFunction(null);
        }

        return $this;
    }

    /**
     * Clears out the collPersonFunctionVersions collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addPersonFunctionVersions()
     */
    public function clearPersonFunctionVersions()
    {
        $this->collPersonFunctionVersions = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collPersonFunctionVersions collection loaded partially.
     */
    public function resetPartialPersonFunctionVersions($v = true)
    {
        $this->collPersonFunctionVersionsPartial = $v;
    }

    /**
     * Initializes the collPersonFunctionVersions collection.
     *
     * By default this just sets the collPersonFunctionVersions collection to an empty array (like clearcollPersonFunctionVersions());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initPersonFunctionVersions($overrideExisting = true)
    {
        if (null !== $this->collPersonFunctionVersions && !$overrideExisting) {
            return;
        }
        $this->collPersonFunctionVersions = new ObjectCollection();
        $this->collPersonFunctionVersions->setModel('\Team\Model\PersonFunctionVersion');
    }

    /**
     * Gets an array of ChildPersonFunctionVersion objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildPersonFunction is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return Collection|ChildPersonFunctionVersion[] List of ChildPersonFunctionVersion objects
     * @throws PropelException
     */
    public function getPersonFunctionVersions($criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonFunctionVersionsPartial && !$this->isNew();
        if (null === $this->collPersonFunctionVersions || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collPersonFunctionVersions) {
                // return empty collection
                $this->initPersonFunctionVersions();
            } else {
                $collPersonFunctionVersions = ChildPersonFunctionVersionQuery::create(null, $criteria)
                    ->filterByPersonFunction($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collPersonFunctionVersionsPartial && count($collPersonFunctionVersions)) {
                        $this->initPersonFunctionVersions(false);

                        foreach ($collPersonFunctionVersions as $obj) {
                            if (false == $this->collPersonFunctionVersions->contains($obj)) {
                                $this->collPersonFunctionVersions->append($obj);
                            }
                        }

                        $this->collPersonFunctionVersionsPartial = true;
                    }

                    reset($collPersonFunctionVersions);

                    return $collPersonFunctionVersions;
                }

                if ($partial && $this->collPersonFunctionVersions) {
                    foreach ($this->collPersonFunctionVersions as $obj) {
                        if ($obj->isNew()) {
                            $collPersonFunctionVersions[] = $obj;
                        }
                    }
                }

                $this->collPersonFunctionVersions = $collPersonFunctionVersions;
                $this->collPersonFunctionVersionsPartial = false;
            }
        }

        return $this->collPersonFunctionVersions;
    }

    /**
     * Sets a collection of PersonFunctionVersion objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $personFunctionVersions A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return   ChildPersonFunction The current object (for fluent API support)
     */
    public function setPersonFunctionVersions(Collection $personFunctionVersions, ConnectionInterface $con = null)
    {
        $personFunctionVersionsToDelete = $this->getPersonFunctionVersions(new Criteria(), $con)->diff($personFunctionVersions);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->personFunctionVersionsScheduledForDeletion = clone $personFunctionVersionsToDelete;

        foreach ($personFunctionVersionsToDelete as $personFunctionVersionRemoved) {
            $personFunctionVersionRemoved->setPersonFunction(null);
        }

        $this->collPersonFunctionVersions = null;
        foreach ($personFunctionVersions as $personFunctionVersion) {
            $this->addPersonFunctionVersion($personFunctionVersion);
        }

        $this->collPersonFunctionVersions = $personFunctionVersions;
        $this->collPersonFunctionVersionsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related PersonFunctionVersion objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related PersonFunctionVersion objects.
     * @throws PropelException
     */
    public function countPersonFunctionVersions(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collPersonFunctionVersionsPartial && !$this->isNew();
        if (null === $this->collPersonFunctionVersions || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collPersonFunctionVersions) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getPersonFunctionVersions());
            }

            $query = ChildPersonFunctionVersionQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByPersonFunction($this)
                ->count($con);
        }

        return count($this->collPersonFunctionVersions);
    }

    /**
     * Method called to associate a ChildPersonFunctionVersion object to this object
     * through the ChildPersonFunctionVersion foreign key attribute.
     *
     * @param    ChildPersonFunctionVersion $l ChildPersonFunctionVersion
     * @return   \Team\Model\PersonFunction The current object (for fluent API support)
     */
    public function addPersonFunctionVersion(ChildPersonFunctionVersion $l)
    {
        if ($this->collPersonFunctionVersions === null) {
            $this->initPersonFunctionVersions();
            $this->collPersonFunctionVersionsPartial = true;
        }

        if (!in_array($l, $this->collPersonFunctionVersions->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddPersonFunctionVersion($l);
        }

        return $this;
    }

    /**
     * @param PersonFunctionVersion $personFunctionVersion The personFunctionVersion object to add.
     */
    protected function doAddPersonFunctionVersion($personFunctionVersion)
    {
        $this->collPersonFunctionVersions[]= $personFunctionVersion;
        $personFunctionVersion->setPersonFunction($this);
    }

    /**
     * @param  PersonFunctionVersion $personFunctionVersion The personFunctionVersion object to remove.
     * @return ChildPersonFunction The current object (for fluent API support)
     */
    public function removePersonFunctionVersion($personFunctionVersion)
    {
        if ($this->getPersonFunctionVersions()->contains($personFunctionVersion)) {
            $this->collPersonFunctionVersions->remove($this->collPersonFunctionVersions->search($personFunctionVersion));
            if (null === $this->personFunctionVersionsScheduledForDeletion) {
                $this->personFunctionVersionsScheduledForDeletion = clone $this->collPersonFunctionVersions;
                $this->personFunctionVersionsScheduledForDeletion->clear();
            }
            $this->personFunctionVersionsScheduledForDeletion[]= clone $personFunctionVersion;
            $personFunctionVersion->setPersonFunction(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->code = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->version = null;
        $this->version_created_at = null;
        $this->version_created_by = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collPersonFunctionLinks) {
                foreach ($this->collPersonFunctionLinks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPersonFunctionI18ns) {
                foreach ($this->collPersonFunctionI18ns as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collPersonFunctionVersions) {
                foreach ($this->collPersonFunctionVersions as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        // i18n behavior
        $this->currentLocale = 'en_US';
        $this->currentTranslations = null;

        $this->collPersonFunctionLinks = null;
        $this->collPersonFunctionI18ns = null;
        $this->collPersonFunctionVersions = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PersonFunctionTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     ChildPersonFunction The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[PersonFunctionTableMap::UPDATED_AT] = true;

        return $this;
    }

    // i18n behavior

    /**
     * Sets the locale for translations
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     *
     * @return    ChildPersonFunction The current object (for fluent API support)
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
     * @return ChildPersonFunctionI18n */
    public function getTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!isset($this->currentTranslations[$locale])) {
            if (null !== $this->collPersonFunctionI18ns) {
                foreach ($this->collPersonFunctionI18ns as $translation) {
                    if ($translation->getLocale() == $locale) {
                        $this->currentTranslations[$locale] = $translation;

                        return $translation;
                    }
                }
            }
            if ($this->isNew()) {
                $translation = new ChildPersonFunctionI18n();
                $translation->setLocale($locale);
            } else {
                $translation = ChildPersonFunctionI18nQuery::create()
                    ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                    ->findOneOrCreate($con);
                $this->currentTranslations[$locale] = $translation;
            }
            $this->addPersonFunctionI18n($translation);
        }

        return $this->currentTranslations[$locale];
    }

    /**
     * Remove the translation for a given locale
     *
     * @param     string $locale Locale to use for the translation, e.g. 'fr_FR'
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return    ChildPersonFunction The current object (for fluent API support)
     */
    public function removeTranslation($locale = 'en_US', ConnectionInterface $con = null)
    {
        if (!$this->isNew()) {
            ChildPersonFunctionI18nQuery::create()
                ->filterByPrimaryKey(array($this->getPrimaryKey(), $locale))
                ->delete($con);
        }
        if (isset($this->currentTranslations[$locale])) {
            unset($this->currentTranslations[$locale]);
        }
        foreach ($this->collPersonFunctionI18ns as $key => $translation) {
            if ($translation->getLocale() == $locale) {
                unset($this->collPersonFunctionI18ns[$key]);
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
     * @return ChildPersonFunctionI18n */
    public function getCurrentTranslation(ConnectionInterface $con = null)
    {
        return $this->getTranslation($this->getLocale(), $con);
    }


        /**
         * Get the [label] column value.
         *
         * @return   string
         */
        public function getLabel()
        {
        return $this->getCurrentTranslation()->getLabel();
    }


        /**
         * Set the value of [label] column.
         *
         * @param      string $v new value
         * @return   \Team\Model\PersonFunctionI18n The current object (for fluent API support)
         */
        public function setLabel($v)
        {    $this->getCurrentTranslation()->setLabel($v);

        return $this;
    }

    // versionable behavior

    /**
     * Enforce a new Version of this object upon next save.
     *
     * @return \Team\Model\PersonFunction
     */
    public function enforceVersioning()
    {
        $this->enforceVersion = true;

        return $this;
    }

    /**
     * Checks whether the current state must be recorded as a version
     *
     * @return  boolean
     */
    public function isVersioningNecessary($con = null)
    {
        if ($this->alreadyInSave) {
            return false;
        }

        if ($this->enforceVersion) {
            return true;
        }

        if (ChildPersonFunctionQuery::isVersioningEnabled() && ($this->isNew() || $this->isModified()) || $this->isDeleted()) {
            return true;
        }
        // to avoid infinite loops, emulate in save
        $this->alreadyInSave = true;
        foreach ($this->getPersonFunctionLinks(null, $con) as $relatedObject) {
            if ($relatedObject->isVersioningNecessary($con)) {
                $this->alreadyInSave = false;

                return true;
            }
        }
        $this->alreadyInSave = false;


        return false;
    }

    /**
     * Creates a version of the current object and saves it.
     *
     * @param   ConnectionInterface $con the connection to use
     *
     * @return  ChildPersonFunctionVersion A version object
     */
    public function addVersion($con = null)
    {
        $this->enforceVersion = false;

        $version = new ChildPersonFunctionVersion();
        $version->setId($this->getId());
        $version->setCode($this->getCode());
        $version->setCreatedAt($this->getCreatedAt());
        $version->setUpdatedAt($this->getUpdatedAt());
        $version->setVersion($this->getVersion());
        $version->setVersionCreatedAt($this->getVersionCreatedAt());
        $version->setVersionCreatedBy($this->getVersionCreatedBy());
        $version->setPersonFunction($this);
        if ($relateds = $this->getPersonFunctionLinks($con)->toKeyValue('Id', 'Version')) {
            $version->setPersonFunctionLinkIds(array_keys($relateds));
            $version->setPersonFunctionLinkVersions(array_values($relateds));
        }
        $version->save($con);

        return $version;
    }

    /**
     * Sets the properties of the current object to the value they had at a specific version
     *
     * @param   integer $versionNumber The version number to read
     * @param   ConnectionInterface $con The connection to use
     *
     * @return  ChildPersonFunction The current object (for fluent API support)
     */
    public function toVersion($versionNumber, $con = null)
    {
        $version = $this->getOneVersion($versionNumber, $con);
        if (!$version) {
            throw new PropelException(sprintf('No ChildPersonFunction object found with version %d', $version));
        }
        $this->populateFromVersion($version, $con);

        return $this;
    }

    /**
     * Sets the properties of the current object to the value they had at a specific version
     *
     * @param ChildPersonFunctionVersion $version The version object to use
     * @param ConnectionInterface   $con the connection to use
     * @param array                 $loadedObjects objects that been loaded in a chain of populateFromVersion calls on referrer or fk objects.
     *
     * @return ChildPersonFunction The current object (for fluent API support)
     */
    public function populateFromVersion($version, $con = null, &$loadedObjects = array())
    {
        $loadedObjects['ChildPersonFunction'][$version->getId()][$version->getVersion()] = $this;
        $this->setId($version->getId());
        $this->setCode($version->getCode());
        $this->setCreatedAt($version->getCreatedAt());
        $this->setUpdatedAt($version->getUpdatedAt());
        $this->setVersion($version->getVersion());
        $this->setVersionCreatedAt($version->getVersionCreatedAt());
        $this->setVersionCreatedBy($version->getVersionCreatedBy());
        if ($fkValues = $version->getPersonFunctionLinkIds()) {
            $this->clearPersonFunctionLinks();
            $fkVersions = $version->getPersonFunctionLinkVersions();
            $query = ChildPersonFunctionLinkVersionQuery::create();
            foreach ($fkValues as $key => $value) {
                $c1 = $query->getNewCriterion(PersonFunctionLinkVersionTableMap::ID, $value);
                $c2 = $query->getNewCriterion(PersonFunctionLinkVersionTableMap::VERSION, $fkVersions[$key]);
                $c1->addAnd($c2);
                $query->addOr($c1);
            }
            foreach ($query->find($con) as $relatedVersion) {
                if (isset($loadedObjects['ChildPersonFunctionLink']) && isset($loadedObjects['ChildPersonFunctionLink'][$relatedVersion->getId()]) && isset($loadedObjects['ChildPersonFunctionLink'][$relatedVersion->getId()][$relatedVersion->getVersion()])) {
                    $related = $loadedObjects['ChildPersonFunctionLink'][$relatedVersion->getId()][$relatedVersion->getVersion()];
                } else {
                    $related = new ChildPersonFunctionLink();
                    $related->populateFromVersion($relatedVersion, $con, $loadedObjects);
                    $related->setNew(false);
                }
                $this->addPersonFunctionLink($related);
                $this->collPersonFunctionLinksPartial = false;
            }
        }

        return $this;
    }

    /**
     * Gets the latest persisted version number for the current object
     *
     * @param   ConnectionInterface $con the connection to use
     *
     * @return  integer
     */
    public function getLastVersionNumber($con = null)
    {
        $v = ChildPersonFunctionVersionQuery::create()
            ->filterByPersonFunction($this)
            ->orderByVersion('desc')
            ->findOne($con);
        if (!$v) {
            return 0;
        }

        return $v->getVersion();
    }

    /**
     * Checks whether the current object is the latest one
     *
     * @param   ConnectionInterface $con the connection to use
     *
     * @return  Boolean
     */
    public function isLastVersion($con = null)
    {
        return $this->getLastVersionNumber($con) == $this->getVersion();
    }

    /**
     * Retrieves a version object for this entity and a version number
     *
     * @param   integer $versionNumber The version number to read
     * @param   ConnectionInterface $con the connection to use
     *
     * @return  ChildPersonFunctionVersion A version object
     */
    public function getOneVersion($versionNumber, $con = null)
    {
        return ChildPersonFunctionVersionQuery::create()
            ->filterByPersonFunction($this)
            ->filterByVersion($versionNumber)
            ->findOne($con);
    }

    /**
     * Gets all the versions of this object, in incremental order
     *
     * @param   ConnectionInterface $con the connection to use
     *
     * @return  ObjectCollection A list of ChildPersonFunctionVersion objects
     */
    public function getAllVersions($con = null)
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(PersonFunctionVersionTableMap::VERSION);

        return $this->getPersonFunctionVersions($criteria, $con);
    }

    /**
     * Compares the current object with another of its version.
     * <code>
     * print_r($book->compareVersion(1));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param   integer             $versionNumber
     * @param   string              $keys Main key used for the result diff (versions|columns)
     * @param   ConnectionInterface $con the connection to use
     * @param   array               $ignoredColumns  The columns to exclude from the diff.
     *
     * @return  array A list of differences
     */
    public function compareVersion($versionNumber, $keys = 'columns', $con = null, $ignoredColumns = array())
    {
        $fromVersion = $this->toArray();
        $toVersion = $this->getOneVersion($versionNumber, $con)->toArray();

        return $this->computeDiff($fromVersion, $toVersion, $keys, $ignoredColumns);
    }

    /**
     * Compares two versions of the current object.
     * <code>
     * print_r($book->compareVersions(1, 2));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param   integer             $fromVersionNumber
     * @param   integer             $toVersionNumber
     * @param   string              $keys Main key used for the result diff (versions|columns)
     * @param   ConnectionInterface $con the connection to use
     * @param   array               $ignoredColumns  The columns to exclude from the diff.
     *
     * @return  array A list of differences
     */
    public function compareVersions($fromVersionNumber, $toVersionNumber, $keys = 'columns', $con = null, $ignoredColumns = array())
    {
        $fromVersion = $this->getOneVersion($fromVersionNumber, $con)->toArray();
        $toVersion = $this->getOneVersion($toVersionNumber, $con)->toArray();

        return $this->computeDiff($fromVersion, $toVersion, $keys, $ignoredColumns);
    }

    /**
     * Computes the diff between two versions.
     * <code>
     * print_r($book->computeDiff(1, 2));
     * => array(
     *   '1' => array('Title' => 'Book title at version 1'),
     *   '2' => array('Title' => 'Book title at version 2')
     * );
     * </code>
     *
     * @param   array     $fromVersion     An array representing the original version.
     * @param   array     $toVersion       An array representing the destination version.
     * @param   string    $keys            Main key used for the result diff (versions|columns).
     * @param   array     $ignoredColumns  The columns to exclude from the diff.
     *
     * @return  array A list of differences
     */
    protected function computeDiff($fromVersion, $toVersion, $keys = 'columns', $ignoredColumns = array())
    {
        $fromVersionNumber = $fromVersion['Version'];
        $toVersionNumber = $toVersion['Version'];
        $ignoredColumns = array_merge(array(
            'Version',
            'VersionCreatedAt',
            'VersionCreatedBy',
        ), $ignoredColumns);
        $diff = array();
        foreach ($fromVersion as $key => $value) {
            if (in_array($key, $ignoredColumns)) {
                continue;
            }
            if ($toVersion[$key] != $value) {
                switch ($keys) {
                    case 'versions':
                        $diff[$fromVersionNumber][$key] = $value;
                        $diff[$toVersionNumber][$key] = $toVersion[$key];
                        break;
                    default:
                        $diff[$key] = array(
                            $fromVersionNumber => $value,
                            $toVersionNumber => $toVersion[$key],
                        );
                        break;
                }
            }
        }

        return $diff;
    }
    /**
     * retrieve the last $number versions.
     *
     * @param Integer $number the number of record to return.
     * @return PropelCollection|array \Team\Model\PersonFunctionVersion[] List of \Team\Model\PersonFunctionVersion objects
     */
    public function getLastVersions($number = 10, $criteria = null, $con = null)
    {
        $criteria = ChildPersonFunctionVersionQuery::create(null, $criteria);
        $criteria->addDescendingOrderByColumn(PersonFunctionVersionTableMap::VERSION);
        $criteria->limit($number);

        return $this->getPersonFunctionVersions($criteria, $con);
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
