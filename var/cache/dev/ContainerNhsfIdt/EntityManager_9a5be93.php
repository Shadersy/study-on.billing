<?php

namespace ContainerNhsfIdt;
include_once \dirname(__DIR__, 4).'/vendor/doctrine/persistence/lib/Doctrine/Persistence/ObjectManager.php';
include_once \dirname(__DIR__, 4).'/vendor/doctrine/orm/lib/Doctrine/ORM/EntityManagerInterface.php';
include_once \dirname(__DIR__, 4).'/vendor/doctrine/orm/lib/Doctrine/ORM/EntityManager.php';

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{

    /**
     * @var \Doctrine\ORM\EntityManager|null wrapped object, if the proxy is initialized
     */
    private $valueHoldera7708 = null;

    /**
     * @var \Closure|null initializer responsible for generating the wrapped object
     */
    private $initializer96717 = null;

    /**
     * @var bool[] map of public properties of the parent class
     */
    private static $publicPropertiesdd94a = [
        
    ];

    public function getConnection()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getConnection', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getConnection();
    }

    public function getMetadataFactory()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getMetadataFactory', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getMetadataFactory();
    }

    public function getExpressionBuilder()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getExpressionBuilder', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getExpressionBuilder();
    }

    public function beginTransaction()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'beginTransaction', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->beginTransaction();
    }

    public function getCache()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getCache', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getCache();
    }

    public function transactional($func)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'transactional', array('func' => $func), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->transactional($func);
    }

    public function commit()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'commit', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->commit();
    }

    public function rollback()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'rollback', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->rollback();
    }

    public function getClassMetadata($className)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getClassMetadata', array('className' => $className), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getClassMetadata($className);
    }

    public function createQuery($dql = '')
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'createQuery', array('dql' => $dql), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->createQuery($dql);
    }

    public function createNamedQuery($name)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'createNamedQuery', array('name' => $name), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->createNamedQuery($name);
    }

    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->createNativeQuery($sql, $rsm);
    }

    public function createNamedNativeQuery($name)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->createNamedNativeQuery($name);
    }

    public function createQueryBuilder()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'createQueryBuilder', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->createQueryBuilder();
    }

    public function flush($entity = null)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'flush', array('entity' => $entity), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->flush($entity);
    }

    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->find($className, $id, $lockMode, $lockVersion);
    }

    public function getReference($entityName, $id)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getReference($entityName, $id);
    }

    public function getPartialReference($entityName, $identifier)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getPartialReference($entityName, $identifier);
    }

    public function clear($entityName = null)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'clear', array('entityName' => $entityName), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->clear($entityName);
    }

    public function close()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'close', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->close();
    }

    public function persist($entity)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'persist', array('entity' => $entity), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->persist($entity);
    }

    public function remove($entity)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'remove', array('entity' => $entity), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->remove($entity);
    }

    public function refresh($entity)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'refresh', array('entity' => $entity), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->refresh($entity);
    }

    public function detach($entity)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'detach', array('entity' => $entity), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->detach($entity);
    }

    public function merge($entity)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'merge', array('entity' => $entity), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->merge($entity);
    }

    public function copy($entity, $deep = false)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->copy($entity, $deep);
    }

    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->lock($entity, $lockMode, $lockVersion);
    }

    public function getRepository($entityName)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getRepository', array('entityName' => $entityName), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getRepository($entityName);
    }

    public function contains($entity)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'contains', array('entity' => $entity), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->contains($entity);
    }

    public function getEventManager()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getEventManager', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getEventManager();
    }

    public function getConfiguration()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getConfiguration', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getConfiguration();
    }

    public function isOpen()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'isOpen', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->isOpen();
    }

    public function getUnitOfWork()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getUnitOfWork', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getUnitOfWork();
    }

    public function getHydrator($hydrationMode)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getHydrator($hydrationMode);
    }

    public function newHydrator($hydrationMode)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->newHydrator($hydrationMode);
    }

    public function getProxyFactory()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getProxyFactory', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getProxyFactory();
    }

    public function initializeObject($obj)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'initializeObject', array('obj' => $obj), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->initializeObject($obj);
    }

    public function getFilters()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'getFilters', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->getFilters();
    }

    public function isFiltersStateClean()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'isFiltersStateClean', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->isFiltersStateClean();
    }

    public function hasFilters()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'hasFilters', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return $this->valueHoldera7708->hasFilters();
    }

    /**
     * Constructor for lazy initialization
     *
     * @param \Closure|null $initializer
     */
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;

        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();

        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $instance, 'Doctrine\\ORM\\EntityManager')->__invoke($instance);

        $instance->initializer96717 = $initializer;

        return $instance;
    }

    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;

        if (! $this->valueHoldera7708) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHoldera7708 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);

        }

        $this->valueHoldera7708->__construct($conn, $config, $eventManager);
    }

    public function & __get($name)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, '__get', ['name' => $name], $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        if (isset(self::$publicPropertiesdd94a[$name])) {
            return $this->valueHoldera7708->$name;
        }

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera7708;

            $backtrace = debug_backtrace(false, 1);
            trigger_error(
                sprintf(
                    'Undefined property: %s::$%s in %s on line %s',
                    $realInstanceReflection->getName(),
                    $name,
                    $backtrace[0]['file'],
                    $backtrace[0]['line']
                ),
                \E_USER_NOTICE
            );
            return $targetObject->$name;
        }

        $targetObject = $this->valueHoldera7708;
        $accessor = function & () use ($targetObject, $name) {
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __set($name, $value)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, '__set', array('name' => $name, 'value' => $value), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera7708;

            $targetObject->$name = $value;

            return $targetObject->$name;
        }

        $targetObject = $this->valueHoldera7708;
        $accessor = function & () use ($targetObject, $name, $value) {
            $targetObject->$name = $value;

            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();

        return $returnValue;
    }

    public function __isset($name)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, '__isset', array('name' => $name), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera7708;

            return isset($targetObject->$name);
        }

        $targetObject = $this->valueHoldera7708;
        $accessor = function () use ($targetObject, $name) {
            return isset($targetObject->$name);
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();

        return $returnValue;
    }

    public function __unset($name)
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, '__unset', array('name' => $name), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');

        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHoldera7708;

            unset($targetObject->$name);

            return;
        }

        $targetObject = $this->valueHoldera7708;
        $accessor = function () use ($targetObject, $name) {
            unset($targetObject->$name);

            return;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $accessor();
    }

    public function __clone()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, '__clone', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        $this->valueHoldera7708 = clone $this->valueHoldera7708;
    }

    public function __sleep()
    {
        $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, '__sleep', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;

        return array('valueHoldera7708');
    }

    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }

    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializer96717 = $initializer;
    }

    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializer96717;
    }

    public function initializeProxy() : bool
    {
        return $this->initializer96717 && ($this->initializer96717->__invoke($valueHoldera7708, $this, 'initializeProxy', array(), $this->initializer96717) || 1) && $this->valueHoldera7708 = $valueHoldera7708;
    }

    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHoldera7708;
    }

    public function getWrappedValueHolderValue()
    {
        return $this->valueHoldera7708;
    }


}

if (!\class_exists('EntityManager_9a5be93', false)) {
    \class_alias(__NAMESPACE__.'\\EntityManager_9a5be93', 'EntityManager_9a5be93', false);
}
