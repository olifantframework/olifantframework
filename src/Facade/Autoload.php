<?php
namespace Olifant;

/**
* @method getPrefixes()
* @method getPrefixesPsr4()
* @method getFallbackDirs()
* @method getFallbackDirsPsr4()
* @method getClassMap()
* @method addClassMap(array $classMap)
* @method add(string $prefix, array|string $paths, bool $prepend = false) Registers a set of PSR-0 directories for a given prefix, either appending or prepending to the ones previously set for this prefix.
* @method addPsr4(string $prefix, array|string $paths, bool $prepend = false) Registers a set of PSR-4 directories for a given namespace, either appending or prepending to the ones previously set for this namespace.
* @method set(string $prefix, array|string $paths) Registers a set of PSR-0 directories for a given prefix, replacing any others previously set for this prefix.
* @method setPsr4(string $prefix, array|string $paths) Registers a set of PSR-4 directories for a given namespace, replacing any others previously set for this namespace.
* @method setUseIncludePath(bool $useIncludePath) Turns on searching the include path for class files.
* @method bool getUseIncludePath() Can be used to check if the autoloader uses the include path to check for classes.
* @method setClassMapAuthoritative(bool $classMapAuthoritative) Turns off searching the prefix and fallback directories for classes that have not been registered with the class map.
* @method bool isClassMapAuthoritative() Should class lookup fail if not found in the current class map?
* @method register(bool $prepend = false) Registers this instance as an autoloader.
* @method unregister()
* @method bool|null loadClass( string $class) Loads the given class or interface.
* @method string|false findFile(string $class) Finds the path to the file where the class is defined.
*
* @method static getPrefixes()
* @method static getPrefixesPsr4()
* @method static getFallbackDirs()
* @method static getFallbackDirsPsr4()
* @method static getClassMap()
* @method static addClassMap(array $classMap)
* @method static add(string $prefix, array|string $paths, bool $prepend = false) Registers a set of PSR-0 directories for a given prefix, either appending or prepending to the ones previously set for this prefix.
* @method static addPsr4(string $prefix, array|string $paths, bool $prepend = false) Registers a set of PSR-4 directories for a given namespace, either appending or prepending to the ones previously set for this namespace.
* @method static set(string $prefix, array|string $paths) Registers a set of PSR-0 directories for a given prefix, replacing any others previously set for this prefix.
* @method static setPsr4(string $prefix, array|string $paths) Registers a set of PSR-4 directories for a given namespace, replacing any others previously set for this namespace.
* @method static setUseIncludePath(bool $useIncludePath) Turns on searching the include path for class files.
* @method static bool getUseIncludePath() Can be used to check if the autoloader uses the include path to check for classes.
* @method static setClassMapAuthoritative(bool $classMapAuthoritative) Turns off searching the prefix and fallback directories for classes that have not been registered with the class map.
* @method static bool isClassMapAuthoritative() Should class lookup fail if not found in the current class map?
* @method static register(bool $prepend = false) Registers this instance as an autoloader.
* @method static unregister()
* @method static bool|null loadClass( string $class) Loads the given class or interface.
* @method static string|false findFile(string $class) Finds the path to the file where the class is defined.
*/
class Autoload extends Facade
{
	public static function getKey()
	{
		return 'autoload';
	}
}