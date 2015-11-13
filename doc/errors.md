# Exceptions provided by the library

These classes you can directly use or define your classes on the basis of their.

All intermediate errors (which have children) are interfaces.

All classes are inherited from `Error` and from `Runtime` or `Logic` (indicated in parentheses).

 * NotFound - an element of a container is not found
    * [FieldNotExists](classes/FieldNotExists.md) (logic) - a static container has not the specific field
    * [ItemNotFound](classes/ItemNotFound.md) (runtime) - a container not contains an item in this time
    * [AdapterNotDefined](classes/AdapterNotDefined.md) (logic) - an adapter is not defined for this service
 * [InvalidConfig](classes/InvalidConfig.md) (logic) - a configuration has invalid format
 * [RequiresOverride](classes/RequiresOverride.md) (logic) - a method requires override
 * Init
    * [AlreadyInited](classes/AlreadyInited.md) (logic) - attempt to initialize an already initialized object
    * [NotInited](classes/NotInited.md) (logic) - attempt to use not initialized object
 * Forbidden - an action is forbidden
    * ReadOnly
        * [PropertyReadOnly](classes/PropertyReadOnly.md) (logic) - a property is readonly
        * [ContainerReadOnly](classes/ContainerReadOnly.md) (logic) - a container is readonly
    * [Pointless](classes/Pointless.md) (logic) - an operation is pointless in this context
    * [Disabled](classes/Disabled.md) (logic) - a service is disabled
 * [ActionNotAllowed](classes/ActionNotAllowed.md) - this action is not allowed for this object
 * InvalidValue - wrong format of a values
    * [NotValid](classes/NotValid.md) - value is not passed through the validators
    * [TypingError](classes/TypingError.md) - value has an wrong type
 * DependencyError - an error associated with dependency (extension, composer package, plugin)
    * [NotInstalled](classes/NotInstalled.md) - a dependency is not installed
 * [InvalidFormat](classes/InvalidFormat.md) - invalid format of some string

## Constructors and methods

Constructors of all these classes take as last arguments (optional)

 * `previous` (Exception) - the previous exception used for the exception chaining
 * `thrower` (object|string) - see [truncate backtrace](backtrace.md)

All other arguments also are optional.

Such arguments as container or service can be a string (the name of service) or an object (itself service).
