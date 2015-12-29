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
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;
use Team\Model\Person as ChildPerson;
use Team\Model\PersonQuery as ChildPersonQuery;
use Team\Model\PersonVersionQuery as ChildPersonVersionQuery;
use Team\Model\Map\PersonVersionTableMap;

abstract class PersonVersion implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\Team\\Model\\Map\\PersonVersionTableMap';


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
     * The value for the person_team_link_ids field.
     * @var        array
     */
    protected $person_team_link_ids;

    /**
     * The unserialized $person_team_link_ids value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $person_team_link_ids_unserialized;

    /**
     * The value for the person_team_link_versions field.
     * @var        array
     */
    protected $person_team_link_versions;

    /**
     * The unserialized $person_team_link_versions value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $person_team_link_versions_unserialized;

    /**
     * The value for the person_image_ids field.
     * @var        array
     */
    protected $person_image_ids;

    /**
     * The unserialized $person_image_ids value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $person_image_ids_unserialized;

    /**
     * The value for the person_image_versions field.
     * @var        array
     */
    protected $person_image_versions;

    /**
     * The unserialized $person_image_versions value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $person_image_versions_unserialized;

    /**
     * The value for the person_function_link_ids field.
     * @var        array
     */
    protected $person_function_link_ids;

    /**
     * The unserialized $person_function_link_ids value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $person_function_link_ids_unserialized;

    /**
     * The value for the person_function_link_versions field.
     * @var        array
     */
    protected $person_function_link_versions;

    /**
     * The unserialized $person_function_link_versions value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var object
     */
    protected $person_function_link_versions_unserialized;

    /**
     * @var        Person
     */
    protected $aPerson;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

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
     * Initializes internal state of Team\Model\Base\PersonVersion object.
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
     * Compares this with another <code>PersonVersion</code> instance.  If
     * <code>obj</code> is an instance of <code>PersonVersion</code>, delegates to
     * <code>equals(PersonVersion)</code>.  Otherwise, returns <code>false</code>.
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
     * @return PersonVersion The current object, for fluid interface
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
     * @return PersonVersion The current object, for fluid interface
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
     * Get the [person_team_link_ids] column value.
     *
     * @return   array
     */
    public function getPersonTeamLinkIds()
    {
        if (null === $this->person_team_link_ids_unserialized) {
            $this->person_team_link_ids_unserialized = array();
        }
        if (!$this->person_team_link_ids_unserialized && null !== $this->person_team_link_ids) {
            $person_team_link_ids_unserialized = substr($this->person_team_link_ids, 2, -2);
            $this->person_team_link_ids_unserialized = $person_team_link_ids_unserialized ? explode(' | ', $person_team_link_ids_unserialized) : array();
        }

        return $this->person_team_link_ids_unserialized;
    }

    /**
     * Test the presence of a value in the [person_team_link_ids] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasPersonTeamLinkId($value)
    {
        return in_array($value, $this->getPersonTeamLinkIds());
    } // hasPersonTeamLinkId()

    /**
     * Get the [person_team_link_versions] column value.
     *
     * @return   array
     */
    public function getPersonTeamLinkVersions()
    {
        if (null === $this->person_team_link_versions_unserialized) {
            $this->person_team_link_versions_unserialized = array();
        }
        if (!$this->person_team_link_versions_unserialized && null !== $this->person_team_link_versions) {
            $person_team_link_versions_unserialized = substr($this->person_team_link_versions, 2, -2);
            $this->person_team_link_versions_unserialized = $person_team_link_versions_unserialized ? explode(' | ', $person_team_link_versions_unserialized) : array();
        }

        return $this->person_team_link_versions_unserialized;
    }

    /**
     * Test the presence of a value in the [person_team_link_versions] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasPersonTeamLinkVersion($value)
    {
        return in_array($value, $this->getPersonTeamLinkVersions());
    } // hasPersonTeamLinkVersion()

    /**
     * Get the [person_image_ids] column value.
     *
     * @return   array
     */
    public function getPersonImageIds()
    {
        if (null === $this->person_image_ids_unserialized) {
            $this->person_image_ids_unserialized = array();
        }
        if (!$this->person_image_ids_unserialized && null !== $this->person_image_ids) {
            $person_image_ids_unserialized = substr($this->person_image_ids, 2, -2);
            $this->person_image_ids_unserialized = $person_image_ids_unserialized ? explode(' | ', $person_image_ids_unserialized) : array();
        }

        return $this->person_image_ids_unserialized;
    }

    /**
     * Test the presence of a value in the [person_image_ids] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasPersonImageId($value)
    {
        return in_array($value, $this->getPersonImageIds());
    } // hasPersonImageId()

    /**
     * Get the [person_image_versions] column value.
     *
     * @return   array
     */
    public function getPersonImageVersions()
    {
        if (null === $this->person_image_versions_unserialized) {
            $this->person_image_versions_unserialized = array();
        }
        if (!$this->person_image_versions_unserialized && null !== $this->person_image_versions) {
            $person_image_versions_unserialized = substr($this->person_image_versions, 2, -2);
            $this->person_image_versions_unserialized = $person_image_versions_unserialized ? explode(' | ', $person_image_versions_unserialized) : array();
        }

        return $this->person_image_versions_unserialized;
    }

    /**
     * Test the presence of a value in the [person_image_versions] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasPersonImageVersion($value)
    {
        return in_array($value, $this->getPersonImageVersions());
    } // hasPersonImageVersion()

    /**
     * Get the [person_function_link_ids] column value.
     *
     * @return   array
     */
    public function getPersonFunctionLinkIds()
    {
        if (null === $this->person_function_link_ids_unserialized) {
            $this->person_function_link_ids_unserialized = array();
        }
        if (!$this->person_function_link_ids_unserialized && null !== $this->person_function_link_ids) {
            $person_function_link_ids_unserialized = substr($this->person_function_link_ids, 2, -2);
            $this->person_function_link_ids_unserialized = $person_function_link_ids_unserialized ? explode(' | ', $person_function_link_ids_unserialized) : array();
        }

        return $this->person_function_link_ids_unserialized;
    }

    /**
     * Test the presence of a value in the [person_function_link_ids] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasPersonFunctionLinkId($value)
    {
        return in_array($value, $this->getPersonFunctionLinkIds());
    } // hasPersonFunctionLinkId()

    /**
     * Get the [person_function_link_versions] column value.
     *
     * @return   array
     */
    public function getPersonFunctionLinkVersions()
    {
        if (null === $this->person_function_link_versions_unserialized) {
            $this->person_function_link_versions_unserialized = array();
        }
        if (!$this->person_function_link_versions_unserialized && null !== $this->person_function_link_versions) {
            $person_function_link_versions_unserialized = substr($this->person_function_link_versions, 2, -2);
            $this->person_function_link_versions_unserialized = $person_function_link_versions_unserialized ? explode(' | ', $person_function_link_versions_unserialized) : array();
        }

        return $this->person_function_link_versions_unserialized;
    }

    /**
     * Test the presence of a value in the [person_function_link_versions] array column value.
     * @param      mixed $value
     *
     * @return boolean
     */
    public function hasPersonFunctionLinkVersion($value)
    {
        return in_array($value, $this->getPersonFunctionLinkVersions());
    } // hasPersonFunctionLinkVersion()

    /**
     * Set the value of [id] column.
     *
     * @param      int $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PersonVersionTableMap::ID] = true;
        }

        if ($this->aPerson !== null && $this->aPerson->getId() !== $v) {
            $this->aPerson = null;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [first_name] column.
     *
     * @param      string $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[PersonVersionTableMap::FIRST_NAME] = true;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [last_name] column.
     *
     * @param      string $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[PersonVersionTableMap::LAST_NAME] = true;
        }


        return $this;
    } // setLastName()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($dt !== $this->created_at) {
                $this->created_at = $dt;
                $this->modifiedColumns[PersonVersionTableMap::CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($dt !== $this->updated_at) {
                $this->updated_at = $dt;
                $this->modifiedColumns[PersonVersionTableMap::UPDATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [version] column.
     *
     * @param      int $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setVersion($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->version !== $v) {
            $this->version = $v;
            $this->modifiedColumns[PersonVersionTableMap::VERSION] = true;
        }


        return $this;
    } // setVersion()

    /**
     * Sets the value of [version_created_at] column to a normalized version of the date/time value specified.
     *
     * @param      mixed $v string, integer (timestamp), or \DateTime value.
     *               Empty strings are treated as NULL.
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setVersionCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, '\DateTime');
        if ($this->version_created_at !== null || $dt !== null) {
            if ($dt !== $this->version_created_at) {
                $this->version_created_at = $dt;
                $this->modifiedColumns[PersonVersionTableMap::VERSION_CREATED_AT] = true;
            }
        } // if either are not null


        return $this;
    } // setVersionCreatedAt()

    /**
     * Set the value of [version_created_by] column.
     *
     * @param      string $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setVersionCreatedBy($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->version_created_by !== $v) {
            $this->version_created_by = $v;
            $this->modifiedColumns[PersonVersionTableMap::VERSION_CREATED_BY] = true;
        }


        return $this;
    } // setVersionCreatedBy()

    /**
     * Set the value of [person_team_link_ids] column.
     *
     * @param      array $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setPersonTeamLinkIds($v)
    {
        if ($this->person_team_link_ids_unserialized !== $v) {
            $this->person_team_link_ids_unserialized = $v;
            $this->person_team_link_ids = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[PersonVersionTableMap::PERSON_TEAM_LINK_IDS] = true;
        }


        return $this;
    } // setPersonTeamLinkIds()

    /**
     * Adds a value to the [person_team_link_ids] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function addPersonTeamLinkId($value)
    {
        $currentArray = $this->getPersonTeamLinkIds();
        $currentArray []= $value;
        $this->setPersonTeamLinkIds($currentArray);

        return $this;
    } // addPersonTeamLinkId()

    /**
     * Removes a value from the [person_team_link_ids] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function removePersonTeamLinkId($value)
    {
        $targetArray = array();
        foreach ($this->getPersonTeamLinkIds() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setPersonTeamLinkIds($targetArray);

        return $this;
    } // removePersonTeamLinkId()

    /**
     * Set the value of [person_team_link_versions] column.
     *
     * @param      array $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setPersonTeamLinkVersions($v)
    {
        if ($this->person_team_link_versions_unserialized !== $v) {
            $this->person_team_link_versions_unserialized = $v;
            $this->person_team_link_versions = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[PersonVersionTableMap::PERSON_TEAM_LINK_VERSIONS] = true;
        }


        return $this;
    } // setPersonTeamLinkVersions()

    /**
     * Adds a value to the [person_team_link_versions] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function addPersonTeamLinkVersion($value)
    {
        $currentArray = $this->getPersonTeamLinkVersions();
        $currentArray []= $value;
        $this->setPersonTeamLinkVersions($currentArray);

        return $this;
    } // addPersonTeamLinkVersion()

    /**
     * Removes a value from the [person_team_link_versions] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function removePersonTeamLinkVersion($value)
    {
        $targetArray = array();
        foreach ($this->getPersonTeamLinkVersions() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setPersonTeamLinkVersions($targetArray);

        return $this;
    } // removePersonTeamLinkVersion()

    /**
     * Set the value of [person_image_ids] column.
     *
     * @param      array $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setPersonImageIds($v)
    {
        if ($this->person_image_ids_unserialized !== $v) {
            $this->person_image_ids_unserialized = $v;
            $this->person_image_ids = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[PersonVersionTableMap::PERSON_IMAGE_IDS] = true;
        }


        return $this;
    } // setPersonImageIds()

    /**
     * Adds a value to the [person_image_ids] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function addPersonImageId($value)
    {
        $currentArray = $this->getPersonImageIds();
        $currentArray []= $value;
        $this->setPersonImageIds($currentArray);

        return $this;
    } // addPersonImageId()

    /**
     * Removes a value from the [person_image_ids] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function removePersonImageId($value)
    {
        $targetArray = array();
        foreach ($this->getPersonImageIds() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setPersonImageIds($targetArray);

        return $this;
    } // removePersonImageId()

    /**
     * Set the value of [person_image_versions] column.
     *
     * @param      array $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setPersonImageVersions($v)
    {
        if ($this->person_image_versions_unserialized !== $v) {
            $this->person_image_versions_unserialized = $v;
            $this->person_image_versions = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[PersonVersionTableMap::PERSON_IMAGE_VERSIONS] = true;
        }


        return $this;
    } // setPersonImageVersions()

    /**
     * Adds a value to the [person_image_versions] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function addPersonImageVersion($value)
    {
        $currentArray = $this->getPersonImageVersions();
        $currentArray []= $value;
        $this->setPersonImageVersions($currentArray);

        return $this;
    } // addPersonImageVersion()

    /**
     * Removes a value from the [person_image_versions] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function removePersonImageVersion($value)
    {
        $targetArray = array();
        foreach ($this->getPersonImageVersions() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setPersonImageVersions($targetArray);

        return $this;
    } // removePersonImageVersion()

    /**
     * Set the value of [person_function_link_ids] column.
     *
     * @param      array $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setPersonFunctionLinkIds($v)
    {
        if ($this->person_function_link_ids_unserialized !== $v) {
            $this->person_function_link_ids_unserialized = $v;
            $this->person_function_link_ids = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[PersonVersionTableMap::PERSON_FUNCTION_LINK_IDS] = true;
        }


        return $this;
    } // setPersonFunctionLinkIds()

    /**
     * Adds a value to the [person_function_link_ids] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function addPersonFunctionLinkId($value)
    {
        $currentArray = $this->getPersonFunctionLinkIds();
        $currentArray []= $value;
        $this->setPersonFunctionLinkIds($currentArray);

        return $this;
    } // addPersonFunctionLinkId()

    /**
     * Removes a value from the [person_function_link_ids] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function removePersonFunctionLinkId($value)
    {
        $targetArray = array();
        foreach ($this->getPersonFunctionLinkIds() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setPersonFunctionLinkIds($targetArray);

        return $this;
    } // removePersonFunctionLinkId()

    /**
     * Set the value of [person_function_link_versions] column.
     *
     * @param      array $v new value
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function setPersonFunctionLinkVersions($v)
    {
        if ($this->person_function_link_versions_unserialized !== $v) {
            $this->person_function_link_versions_unserialized = $v;
            $this->person_function_link_versions = '| ' . implode(' | ', $v) . ' |';
            $this->modifiedColumns[PersonVersionTableMap::PERSON_FUNCTION_LINK_VERSIONS] = true;
        }


        return $this;
    } // setPersonFunctionLinkVersions()

    /**
     * Adds a value to the [person_function_link_versions] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function addPersonFunctionLinkVersion($value)
    {
        $currentArray = $this->getPersonFunctionLinkVersions();
        $currentArray []= $value;
        $this->setPersonFunctionLinkVersions($currentArray);

        return $this;
    } // addPersonFunctionLinkVersion()

    /**
     * Removes a value from the [person_function_link_versions] array column value.
     * @param      mixed $value
     *
     * @return   \Team\Model\PersonVersion The current object (for fluent API support)
     */
    public function removePersonFunctionLinkVersion($value)
    {
        $targetArray = array();
        foreach ($this->getPersonFunctionLinkVersions() as $element) {
            if ($element != $value) {
                $targetArray []= $element;
            }
        }
        $this->setPersonFunctionLinkVersions($targetArray);

        return $this;
    } // removePersonFunctionLinkVersion()

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


            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PersonVersionTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PersonVersionTableMap::translateFieldName('FirstName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->first_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PersonVersionTableMap::translateFieldName('LastName', TableMap::TYPE_PHPNAME, $indexType)];
            $this->last_name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : PersonVersionTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : PersonVersionTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : PersonVersionTableMap::translateFieldName('Version', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : PersonVersionTableMap::translateFieldName('VersionCreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->version_created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, '\DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : PersonVersionTableMap::translateFieldName('VersionCreatedBy', TableMap::TYPE_PHPNAME, $indexType)];
            $this->version_created_by = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : PersonVersionTableMap::translateFieldName('PersonTeamLinkIds', TableMap::TYPE_PHPNAME, $indexType)];
            $this->person_team_link_ids = $col;
            $this->person_team_link_ids_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : PersonVersionTableMap::translateFieldName('PersonTeamLinkVersions', TableMap::TYPE_PHPNAME, $indexType)];
            $this->person_team_link_versions = $col;
            $this->person_team_link_versions_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : PersonVersionTableMap::translateFieldName('PersonImageIds', TableMap::TYPE_PHPNAME, $indexType)];
            $this->person_image_ids = $col;
            $this->person_image_ids_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : PersonVersionTableMap::translateFieldName('PersonImageVersions', TableMap::TYPE_PHPNAME, $indexType)];
            $this->person_image_versions = $col;
            $this->person_image_versions_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : PersonVersionTableMap::translateFieldName('PersonFunctionLinkIds', TableMap::TYPE_PHPNAME, $indexType)];
            $this->person_function_link_ids = $col;
            $this->person_function_link_ids_unserialized = null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : PersonVersionTableMap::translateFieldName('PersonFunctionLinkVersions', TableMap::TYPE_PHPNAME, $indexType)];
            $this->person_function_link_versions = $col;
            $this->person_function_link_versions_unserialized = null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 14; // 14 = PersonVersionTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating \Team\Model\PersonVersion object", 0, $e);
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
        if ($this->aPerson !== null && $this->id !== $this->aPerson->getId()) {
            $this->aPerson = null;
        }
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
            $con = Propel::getServiceContainer()->getReadConnection(PersonVersionTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildPersonVersionQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aPerson = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see PersonVersion::setDeleted()
     * @see PersonVersion::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersonVersionTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ChildPersonVersionQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(PersonVersionTableMap::DATABASE_NAME);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                PersonVersionTableMap::addInstanceToPool($this);
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

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aPerson !== null) {
                if ($this->aPerson->isModified() || $this->aPerson->isNew()) {
                    $affectedRows += $this->aPerson->save($con);
                }
                $this->setPerson($this->aPerson);
            }

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


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(PersonVersionTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(PersonVersionTableMap::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'FIRST_NAME';
        }
        if ($this->isColumnModified(PersonVersionTableMap::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'LAST_NAME';
        }
        if ($this->isColumnModified(PersonVersionTableMap::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'CREATED_AT';
        }
        if ($this->isColumnModified(PersonVersionTableMap::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'UPDATED_AT';
        }
        if ($this->isColumnModified(PersonVersionTableMap::VERSION)) {
            $modifiedColumns[':p' . $index++]  = 'VERSION';
        }
        if ($this->isColumnModified(PersonVersionTableMap::VERSION_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'VERSION_CREATED_AT';
        }
        if ($this->isColumnModified(PersonVersionTableMap::VERSION_CREATED_BY)) {
            $modifiedColumns[':p' . $index++]  = 'VERSION_CREATED_BY';
        }
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_TEAM_LINK_IDS)) {
            $modifiedColumns[':p' . $index++]  = 'PERSON_TEAM_LINK_IDS';
        }
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_TEAM_LINK_VERSIONS)) {
            $modifiedColumns[':p' . $index++]  = 'PERSON_TEAM_LINK_VERSIONS';
        }
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_IMAGE_IDS)) {
            $modifiedColumns[':p' . $index++]  = 'PERSON_IMAGE_IDS';
        }
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_IMAGE_VERSIONS)) {
            $modifiedColumns[':p' . $index++]  = 'PERSON_IMAGE_VERSIONS';
        }
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_FUNCTION_LINK_IDS)) {
            $modifiedColumns[':p' . $index++]  = 'PERSON_FUNCTION_LINK_IDS';
        }
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_FUNCTION_LINK_VERSIONS)) {
            $modifiedColumns[':p' . $index++]  = 'PERSON_FUNCTION_LINK_VERSIONS';
        }

        $sql = sprintf(
            'INSERT INTO person_version (%s) VALUES (%s)',
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
                    case 'VERSION':
                        $stmt->bindValue($identifier, $this->version, PDO::PARAM_INT);
                        break;
                    case 'VERSION_CREATED_AT':
                        $stmt->bindValue($identifier, $this->version_created_at ? $this->version_created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'VERSION_CREATED_BY':
                        $stmt->bindValue($identifier, $this->version_created_by, PDO::PARAM_STR);
                        break;
                    case 'PERSON_TEAM_LINK_IDS':
                        $stmt->bindValue($identifier, $this->person_team_link_ids, PDO::PARAM_STR);
                        break;
                    case 'PERSON_TEAM_LINK_VERSIONS':
                        $stmt->bindValue($identifier, $this->person_team_link_versions, PDO::PARAM_STR);
                        break;
                    case 'PERSON_IMAGE_IDS':
                        $stmt->bindValue($identifier, $this->person_image_ids, PDO::PARAM_STR);
                        break;
                    case 'PERSON_IMAGE_VERSIONS':
                        $stmt->bindValue($identifier, $this->person_image_versions, PDO::PARAM_STR);
                        break;
                    case 'PERSON_FUNCTION_LINK_IDS':
                        $stmt->bindValue($identifier, $this->person_function_link_ids, PDO::PARAM_STR);
                        break;
                    case 'PERSON_FUNCTION_LINK_VERSIONS':
                        $stmt->bindValue($identifier, $this->person_function_link_versions, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

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
        $pos = PersonVersionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
            case 5:
                return $this->getVersion();
                break;
            case 6:
                return $this->getVersionCreatedAt();
                break;
            case 7:
                return $this->getVersionCreatedBy();
                break;
            case 8:
                return $this->getPersonTeamLinkIds();
                break;
            case 9:
                return $this->getPersonTeamLinkVersions();
                break;
            case 10:
                return $this->getPersonImageIds();
                break;
            case 11:
                return $this->getPersonImageVersions();
                break;
            case 12:
                return $this->getPersonFunctionLinkIds();
                break;
            case 13:
                return $this->getPersonFunctionLinkVersions();
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
        if (isset($alreadyDumpedObjects['PersonVersion'][serialize($this->getPrimaryKey())])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['PersonVersion'][serialize($this->getPrimaryKey())] = true;
        $keys = PersonVersionTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getFirstName(),
            $keys[2] => $this->getLastName(),
            $keys[3] => $this->getCreatedAt(),
            $keys[4] => $this->getUpdatedAt(),
            $keys[5] => $this->getVersion(),
            $keys[6] => $this->getVersionCreatedAt(),
            $keys[7] => $this->getVersionCreatedBy(),
            $keys[8] => $this->getPersonTeamLinkIds(),
            $keys[9] => $this->getPersonTeamLinkVersions(),
            $keys[10] => $this->getPersonImageIds(),
            $keys[11] => $this->getPersonImageVersions(),
            $keys[12] => $this->getPersonFunctionLinkIds(),
            $keys[13] => $this->getPersonFunctionLinkVersions(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aPerson) {
                $result['Person'] = $this->aPerson->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
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
        $pos = PersonVersionTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

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
            case 5:
                $this->setVersion($value);
                break;
            case 6:
                $this->setVersionCreatedAt($value);
                break;
            case 7:
                $this->setVersionCreatedBy($value);
                break;
            case 8:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setPersonTeamLinkIds($value);
                break;
            case 9:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setPersonTeamLinkVersions($value);
                break;
            case 10:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setPersonImageIds($value);
                break;
            case 11:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setPersonImageVersions($value);
                break;
            case 12:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setPersonFunctionLinkIds($value);
                break;
            case 13:
                if (!is_array($value)) {
                    $v = trim(substr($value, 2, -2));
                    $value = $v ? explode(' | ', $v) : array();
                }
                $this->setPersonFunctionLinkVersions($value);
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
        $keys = PersonVersionTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setFirstName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setLastName($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCreatedAt($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setUpdatedAt($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setVersion($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setVersionCreatedAt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setVersionCreatedBy($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setPersonTeamLinkIds($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setPersonTeamLinkVersions($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setPersonImageIds($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setPersonImageVersions($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setPersonFunctionLinkIds($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setPersonFunctionLinkVersions($arr[$keys[13]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(PersonVersionTableMap::DATABASE_NAME);

        if ($this->isColumnModified(PersonVersionTableMap::ID)) $criteria->add(PersonVersionTableMap::ID, $this->id);
        if ($this->isColumnModified(PersonVersionTableMap::FIRST_NAME)) $criteria->add(PersonVersionTableMap::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(PersonVersionTableMap::LAST_NAME)) $criteria->add(PersonVersionTableMap::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(PersonVersionTableMap::CREATED_AT)) $criteria->add(PersonVersionTableMap::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(PersonVersionTableMap::UPDATED_AT)) $criteria->add(PersonVersionTableMap::UPDATED_AT, $this->updated_at);
        if ($this->isColumnModified(PersonVersionTableMap::VERSION)) $criteria->add(PersonVersionTableMap::VERSION, $this->version);
        if ($this->isColumnModified(PersonVersionTableMap::VERSION_CREATED_AT)) $criteria->add(PersonVersionTableMap::VERSION_CREATED_AT, $this->version_created_at);
        if ($this->isColumnModified(PersonVersionTableMap::VERSION_CREATED_BY)) $criteria->add(PersonVersionTableMap::VERSION_CREATED_BY, $this->version_created_by);
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_TEAM_LINK_IDS)) $criteria->add(PersonVersionTableMap::PERSON_TEAM_LINK_IDS, $this->person_team_link_ids);
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_TEAM_LINK_VERSIONS)) $criteria->add(PersonVersionTableMap::PERSON_TEAM_LINK_VERSIONS, $this->person_team_link_versions);
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_IMAGE_IDS)) $criteria->add(PersonVersionTableMap::PERSON_IMAGE_IDS, $this->person_image_ids);
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_IMAGE_VERSIONS)) $criteria->add(PersonVersionTableMap::PERSON_IMAGE_VERSIONS, $this->person_image_versions);
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_FUNCTION_LINK_IDS)) $criteria->add(PersonVersionTableMap::PERSON_FUNCTION_LINK_IDS, $this->person_function_link_ids);
        if ($this->isColumnModified(PersonVersionTableMap::PERSON_FUNCTION_LINK_VERSIONS)) $criteria->add(PersonVersionTableMap::PERSON_FUNCTION_LINK_VERSIONS, $this->person_function_link_versions);

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
        $criteria = new Criteria(PersonVersionTableMap::DATABASE_NAME);
        $criteria->add(PersonVersionTableMap::ID, $this->id);
        $criteria->add(PersonVersionTableMap::VERSION, $this->version);

        return $criteria;
    }

    /**
     * Returns the composite primary key for this object.
     * The array elements will be in same order as specified in XML.
     * @return array
     */
    public function getPrimaryKey()
    {
        $pks = array();
        $pks[0] = $this->getId();
        $pks[1] = $this->getVersion();

        return $pks;
    }

    /**
     * Set the [composite] primary key.
     *
     * @param      array $keys The elements of the composite key (order must match the order in XML file).
     * @return void
     */
    public function setPrimaryKey($keys)
    {
        $this->setId($keys[0]);
        $this->setVersion($keys[1]);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return (null === $this->getId()) && (null === $this->getVersion());
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \Team\Model\PersonVersion (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setId($this->getId());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setVersion($this->getVersion());
        $copyObj->setVersionCreatedAt($this->getVersionCreatedAt());
        $copyObj->setVersionCreatedBy($this->getVersionCreatedBy());
        $copyObj->setPersonTeamLinkIds($this->getPersonTeamLinkIds());
        $copyObj->setPersonTeamLinkVersions($this->getPersonTeamLinkVersions());
        $copyObj->setPersonImageIds($this->getPersonImageIds());
        $copyObj->setPersonImageVersions($this->getPersonImageVersions());
        $copyObj->setPersonFunctionLinkIds($this->getPersonFunctionLinkIds());
        $copyObj->setPersonFunctionLinkVersions($this->getPersonFunctionLinkVersions());
        if ($makeNew) {
            $copyObj->setNew(true);
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
     * @return                 \Team\Model\PersonVersion Clone of current object.
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
     * Declares an association between this object and a ChildPerson object.
     *
     * @param                  ChildPerson $v
     * @return                 \Team\Model\PersonVersion The current object (for fluent API support)
     * @throws PropelException
     */
    public function setPerson(ChildPerson $v = null)
    {
        if ($v === null) {
            $this->setId(NULL);
        } else {
            $this->setId($v->getId());
        }

        $this->aPerson = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildPerson object, it will not be re-added.
        if ($v !== null) {
            $v->addPersonVersion($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildPerson object
     *
     * @param      ConnectionInterface $con Optional Connection object.
     * @return                 ChildPerson The associated ChildPerson object.
     * @throws PropelException
     */
    public function getPerson(ConnectionInterface $con = null)
    {
        if ($this->aPerson === null && ($this->id !== null)) {
            $this->aPerson = ChildPersonQuery::create()->findPk($this->id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aPerson->addPersonVersions($this);
             */
        }

        return $this->aPerson;
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
        $this->version = null;
        $this->version_created_at = null;
        $this->version_created_by = null;
        $this->person_team_link_ids = null;
        $this->person_team_link_ids_unserialized = null;
        $this->person_team_link_versions = null;
        $this->person_team_link_versions_unserialized = null;
        $this->person_image_ids = null;
        $this->person_image_ids_unserialized = null;
        $this->person_image_versions = null;
        $this->person_image_versions_unserialized = null;
        $this->person_function_link_ids = null;
        $this->person_function_link_ids_unserialized = null;
        $this->person_function_link_versions = null;
        $this->person_function_link_versions_unserialized = null;
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
        } // if ($deep)

        $this->aPerson = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(PersonVersionTableMap::DEFAULT_STRING_FORMAT);
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
