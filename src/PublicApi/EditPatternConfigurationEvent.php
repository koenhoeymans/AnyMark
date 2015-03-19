<?php

/**
 * @package AnyMark
 */
namespace AnyMark\PublicApi;

/**
 * This event is thrown once after the pattern configuration is loaded. The
 * event name is `EditPatternConfigurationEvent`.
 * 
 * @package AnyMark
 * @eventname EditPatternConfigurationEvent
 */
interface EditPatternConfigurationEvent
{
	/**
	 * Set the implementation to use for a pattern name. This can
	 * be a class name or an object.
	 * 
	 * @param string $name The name of the pattern.
	 * @param string|object $implementation The class name or object.
	 */
	public function setImplementation($name, $implementation);

	/**
	 * Adds a pattern to the configuration. It can be added to an alias
	 * or as a subpattern, choosing where to place it.
	 * 
	 * This method uses a fluent interface.
	 * 
	 * Example:
	 * 
	 *     $configuration
	 *     	->add('strong')
	 *     	->toAlias('inline')
	 *     	->last();
	 *    
	 *    $configuration
	 *    	->add('strong')
	 *    	->toParent('italic')
	 *    	->first();
	 * 
	 * @param string $name
	 * @return \AnyMark\PublicApi\AddToAliasOrParent
	 */
	public function add($name);
}