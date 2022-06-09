<?php

namespace app;

use function file_exists;
use function spl_autoload_register;
use function spl_autoload_unregister;
use function str_replace;
use function trim;

/**
 * @property array  $autoload_function
 * @property        $namespace
 * @property string $file_location
 */
class Autoload
{

    /**
     * @var null
     */
    private $method = null;

    /**
     * @return System|null
     */
    public function load(): ?System
    {
        if (!is_null($this->method)) {
            return $this->method;
        }
        spl_autoload_register($this->autoload_function = [
            $this,
            'includes',
        ], false, false);
        $this->method = $method = (new System());
        spl_autoload_unregister($this->autoload_function);
        $method->register(true);
        return $method;
    }

    /**
     * @param $namespace
     *
     * @return void
     */
    protected function includes($namespace): void
    {
        if (!is_null(($this->namespace = $namespace))) {
            $this->attach_a_file();
        }
    }

    /**
     * @return void
     */
    private function attach_a_file(): void
    {
        if ($this->check_file_exists()) {
            require_once $this->file_location;
        }
    }

    /**
     * @return bool
     */
    private function check_file_exists(): bool
    {
        $this->file_location = $this->converter_filename();
        return file_exists($this->file_location);
    }

    /**
     * @return string
     */
    private function converter_filename(): string
    {
        return INC_ROOT . '/' . trim(str_replace(
                '\\', '/', $this->namespace
            ), '/') . '.php';
    }
}