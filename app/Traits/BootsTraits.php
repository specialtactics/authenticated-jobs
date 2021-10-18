<?php

namespace App\Traits;

/**
 * Trait BootsTraits
 *
 * This trait allows for booting and initializing traits on any class onto which it's embedded. It's very useful in
 * situations where you may need constructor-like functionality in the traits, ran from the constructor of the concrete class
 *
 * To use, simply class the "constructTraits()" method from the constructor of the concrete class.
 *
 * Booting is for static and initialization is for non-static "constructor" methods.
 * They should be called bootTraitName and initializeTraitName respectively.
 *
 * Simply use this trait on your class of choice, and then use any number of traits you wish in this class implementing
 * either the boot or initialize (or both) constructors.
 *
 * @package App\Traits
 */
trait BootsTraits
{
    /**
     * The array of booted models.
     *
     * @var array
     */
    protected static $booted = [];

    /**
     * The array of trait initializers that will be called on each new instance.
     *
     * @var array
     */
    protected static $traitInitializers = [];

    /**
     * Boot Traits if not Booted
     */
    public function constructTraits()
    {
        $this->bootIfNotBooted();

        $this->initializeTraits();
    }

    /**
     * Check if the model needs to be booted and if so, do it.
     *
     * @return void
     */
    protected function bootIfNotBooted()
    {
        if (! isset(static::$booted[static::class])) {
            static::$booted[static::class] = true;

            static::bootTraits();
        }
    }

    /**
     * Boot all of the bootable traits on the model.
     *
     * @return void
     */
    protected static function bootTraits()
    {
        $class = static::class;

        $booted = [];

        static::$traitInitializers[$class] = [];

        foreach (class_uses_recursive($class) as $trait) {
            $method = 'boot'.class_basename($trait);

            if (method_exists($class, $method) && ! in_array($method, $booted)) {
                forward_static_call([$class, $method]);

                $booted[] = $method;
            }

            if (method_exists($class, $method = 'initialize'.class_basename($trait))) {
                static::$traitInitializers[$class][] = $method;

                static::$traitInitializers[$class] = array_unique(
                    static::$traitInitializers[$class]
                );
            }
        }
    }

    /**
     * Initialize any initializable traits on the model.
     *
     * @return void
     */
    protected function initializeTraits()
    {
        foreach (static::$traitInitializers[static::class] as $method) {
            $this->{$method}();
        }
    }
}
