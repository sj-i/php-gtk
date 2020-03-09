<?php

/**
 * php-gtk (http://toknot.com)
 *
 * @copyright  Copyright (c) 2019 Szopen Xiao (Toknot.com)
 * @license    http://toknot.com/LICENSE.txt New BSD License
 * @link       https://github.com/chopins/php-gtk
 * @version    0.1
 */

namespace Gtk;

use Gtk\FFI;
use FFI\CData;
use InvalidArgumentException;
use SplObjectStorage;
use Gtk\Gtk;
use Gtk\Atk;
use Gtk\Pixbuf;
use Gtk\Pango;

(function () {
    foreach([
    'PHP_GTK_ID_GLIB' => 'glib',
    'PHP_GTK_ID_GIO' => 'gio',
    'PHP_GTK_ID_GOBJECT' => 'gobject',
    'PHP_GTK_ID_GTK' => 'gtk',
    'PHP_GTK_ID_PANGO' => 'pango',
    'PHP_GTK_ID_GDK' => 'gdk',
    'PHP_GTK_ID_PIXBUF' => 'pixbuf',
    'PHP_GTK_ID_ATK' => 'atk',
    'PHP_GTK_DEV_DEBUG' => false,
    ] as $k => $n) {
        if(!defined($k)) {
            define($k, $n);
        }
    }
})();

class PHPGtk
{

    private $gtkLib = null;
    private static $unmanagedCData = null;
    private $libdir = '';

    const GLIB_ID = PHP_GTK_ID_GLIB;
    const GIO_ID = PHP_GTK_ID_GIO;
    const GOBJECT_ID = PHP_GTK_ID_GOBJECT;
    const GTK_ID = PHP_GTK_ID_GTK;
    const GDK_ID = PHP_GTK_ID_GDK;
    const PANGO_ID = PHP_GTK_ID_PANGO;
    const PIXBUF_ID = PHP_GTK_ID_PIXBUF;
    const ATK_ID = PHP_GTK_ID_ATK;

    public static $gtkDllMap = [
        self::GLIB_ID => ['name' => 'libglib', 'header' => ['glib']],
        self::GIO_ID => ['name' => 'libgio', 'header' => ['gio']],
        self::GOBJECT_ID => ['name' => 'libgobject', 'header' => ['gtype', 'gobject']],
        self::GTK_ID => ['name' => 'libgtk', 'header' => ['gtkfunc']],
        self::GDK_ID => ['name' => 'libgdk', 'header' => ['gdk']],
        self::PIXBUF_ID => ['name' => 'libgdk_pixbuf', 'header' => ['pixbuf']],
        self::PANGO_ID => ['name' => 'libpango', 'header' => ['pango']],
        self::ATK_ID => ['name' => 'libatk', 'header' => ['atk']],
    ];

    public function __construct($libdir = null)
    {
        static $enable = false;
        if($enable) {
            return;
        }
        $enable = true;
        $this->complieDefineValue();
        $this->autoload();
        self::$unmanagedCData = new SplObjectStorage;
        $this->libdir = $libdir;
        $this->gtkLib = new Gtk($this, $libdir);
    }

    private function complieDefineValue()
    {
        if(!defined('PHP_OS_WIN') && strcasecmp(PHP_OS_FAMILY, 'Windows') === 0) {
            define('PHP_OS_WIN', true);
        } else {
            define('PHP_OS_WIN', false);
        }
    }

    public function str0(string $str, string $begin): bool
    {
        return strpos($str, $begin) === 0;
    }

    public function new($type, $owned = true, $persistent = false, FFI $ffi = null): CData
    {
        if($ffi) {
            $cdata = $ffi->new($type, $owned, $persistent);
        } elseif($this->gtkLib) {
            $cdata = $this->gtkLib->new($type, $owned, $persistent);
        } else {
            $cdata = FFI::new($type, $owned, $persistent);
        }
        if(!$owned) {
            self::$unmanagedCData->attach($cdata);
        }
        return $cdata;
    }

    public function free($cdata = null): bool
    {
        if($cdata) {
            FFI::free($cdata);
            if(self::$unmanagedCData->contains($cdata)) {
                self::$unmanagedCData->detach($cdata);
            }
            return true;
        }
        foreach(self::$unmanagedCData as $cdata) {
            FFI::free($cdata);
            self::$unmanagedCData->detach($cdata);
        }
        return true;
    }

    public function trunCast(CData $i, array $type, $ffi = null)
    {
        if(count($type) < 1) {
            throw new InvalidArgumentException(__METHOD__ . "() paramter 2 can not empty array");
        }
        foreach($type as $t) {
            $i = $this->cast($t, $i, $ffi);
        }
        return $i;
    }

    public function cast($type, CData $i, $ffi = null)
    {
        return $ffi ? $ffi->cast($type, $i) : ($this->gtkLib ? $this->gtkLib->cast($type, $i) : FFI::cast($type, $i));
    }

    public function addr($v)
    {
        return FFI::addr($v);
    }

    public function atk()
    {
        return new Atk($this, $this->libdir);
    }

    public function gdk()
    {
        return new Pixbuf($this, $this->libdir);
    }

    public function pango()
    {
        return new Pango($this, $this->libdir);
    }

    public function __get($name)
    {
        return $this->gtkLib->$name;
    }

    public function __set($name, $v)
    {
        return $this->gtkLib->$name = $v;
    }

    public function __call($name, $arguments)
    {
        return $this->gtkLib->$name(...$arguments);
    }

    public function autoload()
    {
        spl_autoload_register(function ($class) {
            $classInfo = explode('\\', $class);
            array_shift($classInfo);
            array_unshift($classInfo, __DIR__);
            $path = join(DIRECTORY_SEPARATOR, $classInfo) . '.php';
            if(file_exists($path)) {
                include_once $path;
            }
        });
    }

    public function __destruct()
    {
        $this->free();
    }

}
