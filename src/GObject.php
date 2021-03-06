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
use Gtk\PHPGtk;
use Gtk\GLib;
use Toknot\ReflectionCFunction;

class GObject extends GLib
{

    protected static ?FFI $ffi = null;

    protected const ID = PHPGtk::GOBJECT_ID;
    protected const UNIMPLEMENT = [];
    const G_TYPE_FUNDAMENTAL_SHIFT = 2;
    const G_TYPE_RESERVED_GLIB_FIRST = 22;

    protected static function compileVersion()
    {
        
    }

    public function g_signal_connect($instance, $detailed_signal, $c_handler, $data = null)
    {
        return $this->g_signal_connect_data($instance, $detailed_signal, $c_handler, $data, null, null);
    }

    /**
     * 
     * @param callable $fn  if is array, 3rd element is C function flag
     * @return callable
     */
    public function G_CALLBACK($fn)
    {
        if(is_array($fn) && isset($fn[2])) {
            $ffi = $fn[0]->getFFIOfFunc($fn[1]);
            if(!$ffi) {
                return $fn;
            }
            $ref = new ReflectionCFunction($ffi->getFFI(), $fn[1]);
            return $ref->getClosure();
        }
        return $fn;
    }

    public function g_signal_connect_swapped($instance, $detailedSignal, $chandler, $data)
    {
        return $this->g_signal_connect_data($instance, $detailedSignal, $chandler, $data, NULL, self::$ffi->G_CONNECT_SWAPPED);
    }

    public function g_signal_connect_after($instance, $detailedSignal, $chandler, $data)
    {
        return $this->g_signal_connect_data($instance, $detailedSignal, $chandler, $data, NULL, self::$ffi->G_CONNECT_AFTER);
    }

    public function g_signal_handlers_disconnect_by_data($instance, $data)
    {
        return $this->g_signal_handlers_disconnect_matched($instance, self::$ffi->G_SIGNAL_MATCH_DATA, 0, 0, NULL, NULL, $data);
    }

    public function g_signal_handlers_block_by_func($instance, $func, $data)
    {
        $sig = $this->new('GSignalMatchType');
        $sig->cdata = (self::$ffi->G_SIGNAL_MATCH_FUNC | self::$ffi->G_SIGNAL_MATCH_DATA);
        return $this->g_signal_handlers_block_matched($instance, $sig, 0, 0, NULL, $func, $data);
    }

    public function g_signal_handlers_unblock_by_func($instance, $func, $data)
    {
        $sig = $this->new('GSignalMatchType');
        $sig->cdata = (self::$ffi->G_SIGNAL_MATCH_FUNC | self::$ffi->G_SIGNAL_MATCH_DATA);
        return $this->g_signal_handlers_unblock_matched($instance, $sig, 0, 0, NULL, $func, $data);
    }

    public function g_signal_handlers_disconnect_by_func($instance, $func, $data)
    {
        $sig = $this->new('GSignalMatchType');
        $sig->cdata = (self::$ffi->G_SIGNAL_MATCH_FUNC | self::$ffi->G_SIGNAL_MATCH_DATA);
        return $this->g_signal_handlers_disconnect_matched($instance, $sig, 0, 0, NULL, $func, $data);
    }

    public function _MACRO_G_TYPE_CHECK_INSTANCE_CAST($ins, $gtype, $ctype)
    {
        $p = self::$ffi->cast('GTypeInstance*', $ins);
        return $this->cast("$ctype*", $this->g_type_check_instance_cast($p, $gtype));
    }

    public function _MACRO_G_TYPE_CHECK_CLASS_CAST($cp, $gt, $ct)
    {
        $p = $this->cast('GTypeClass*', $cp);
        return $this->cast("$ct*", $this->g_type_check_class_cast($p, $gt));
    }

    public function G_TYPE_CHECK_INSTANCE_TYPE($cp, $gt)
    {
        $p = $this->cast('GTypeInstance*', $cp);
        return $this->g_type_check_class_is_a($p, $gt);
    }

    public function G_TYPE_CHECK_CLASS_TYPE($cp, $gt)
    {
        $p = $this->cast('GTypeClass*', $cp);
        return $this->g_type_check_class_is_a($p, $gt);
    }

    public function _MACRO_G_TYPE_CHECK_VALUE($value)
    {
        $p = $this->cast('GValue*', $value);
        return $this->g_type_check_value($p);
    }

    public function G_TYPE_INSTANCE_GET_CLASS($cp, $gt, $ct)
    {
        $p = $this->cast('GTypeInstance*', $cp);
        return $this->cast("$ct*", $p[0]->g_class);
    }

    public function G_TYPE_INSTANCE_GET_INTERFACE($cp, $gt, $ct)
    {
        $p = $this->cast('GTypeInstance*', $cp);
        return $this->cast("$ct*", $this->g_type_interface_peek($p, $gt));
    }

    public function G_TYPE_CHECK_INSTANCE_FUNDAMENTAL_TYPE($cp, $gt)
    {
        $p = $this->cast('GTypeInstance*', $cp);
        return $this->g_type_check_instance_is_fundamentally_a($p, $gt);
    }

    public function G_TYPE_MAKE_FUNDAMENTAL($x)
    {
        return $x << self::G_TYPE_FUNDAMENTAL_SHIFT;
    }

    public function G_TYPE_OBJECT()
    {
        return $this->G_TYPE_MAKE_FUNDAMENTAL(20);
    }

    public function G_TYPE_GTYPE()
    {
        return $this->g_gtype_get_type();
    }

    public function G_IS_OBJECT($obj)
    {
        return $this->G_TYPE_CHECK_INSTANCE_FUNDAMENTAL_TYPE($obj, $this->G_TYPE_OBJECT());
    }

    public function G_IS_OBJECT_CLASS($class)
    {
        return $this->G_TYPE_CHECK_CLASS_TYPE($class, $this->G_TYPE_OBJECT());
    }

    public function G_TYPE_CHECK_VALUE_TYPE($v, $gt)
    {
        $p = $this->cast('GValue*', $v);
        return $this->g_type_check_value_holds($p, $gt);
    }

    public function G_TYPE_FROM_INSTANCE($cp)
    {
        $p = $this->cast('GTypeInstance*', $cp);
        return $p[0]->g_class;
    }

    public function G_TYPE_FROM_CLASS($cp)
    {
        return $this->cast('GTypeClass*', $cp)[0]->g_type;
    }

    public function G_OBJECT_CLASS_TYPE($class)
    {
        return $this->G_TYPE_FROM_CLASS($class);
    }

    public function G_OBJECT_TYPE($o)
    {
        return $this->G_TYPE_FROM_INSTANCE($o);
    }

    public function G_OBJECT_TYPE_NAME($obj)
    {
        $id = $this->G_OBJECT_TYPE($obj);
        return $this->g_type_name($id[0]->g_type);
    }

    public function G_OBJECT_CLASS_NAME($obj)
    {
        $id = $this->G_OBJECT_CLASS_TYPE($obj);
        return $this->g_type_name($id);
    }

    public function G_TYPE_FROM_INTERFACE($gi)
    {
        return $this->cast('GTypeInterface*', $gi)[0]->g_type;
    }

    public function G_TYPE_INSTANCE_GET_PRIVATE($v, $gt, $ct)
    {
        trigger_error('G_TYPE_INSTANCE_GET_PRIVATE() macro is deprecated since 2.58 of gobject', E_DEPRECATED);
        $p = $this->cast('GTypeInstance*', $v);
        $gp = self::$ffi->g_type_instance_get_private($p, $gt);
        return $this->cast("$ct*", $gp);
    }

    public function G_TYPE_CLASS_GET_PRIVATE($v, $gt, $ct)
    {
        $p = $this->cast('GTypeClass*', $v);
        return $this->cast("$ct*", self::$ffi->g_type_class_get_private($p, $gt));
    }

}
