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
use Gtk\App;
use Gtk\GLib;
use ReflectionCFunction;
use ReflectionException;

class GObject extends GLib
{

    protected static ?FFI $ffi = null;

    protected const ID = App::GOBJECT_ID;
    protected const MATCH_FULL = ['g_source_set_closure', 'g_source_set_dummy_callback'];
    protected const MATCH_PREFIX = [
        'g_binding_', 'GBinding', 'g_cclosure_', 'GClass', 'GClosure', 'g_closure_',
        'g_object_', 'g_signal_', 'GSignal', 'g_flags_',
        'g_param_', 'g_type_', 'g_strdup_', 'g_value_', 'GValue',
        'g_weak_', 'GWeak'
    ];
    protected const UNIMPLEMENT = [];
    protected const GLOBAL_VAL = ['g_param_spec_types' => 0];

    protected static function compileVersion()
    {
        
    }

    public function g_signal_connect($instance, $detailed_signal, $c_handler, $data = null)
    {
        return $this->g_signal_connect_data($instance, $detailed_signal, $c_handler, $data, null, null);
    }

    public function G_CALLBACK($fn)
    {
        if(is_callable($fn) && is_array($fn) && ($fn[0] === $this->main || $fn[0] === $this)) {
            try {
                $ref = new ReflectionCFunction(self::$ffi, $fn[1]);
                $argc = $ref->cfuncNumArgs();
                return function (...$p) use($argc, $fn) {
                    $p = array_splice($p, $argc - 1);
                    return $fn[0]->$fn[1](...$p);
                };
            } catch(ReflectionException $e) {
                
            }
        }
        return $fn;
    }

    public function g_signal_connect_swapped($instance, $detailedSignal, $chandler, $data)
    {
        return $this->g_signal_connect_data($instance, $detailedSignal, $chandler, $data, NULL, GtkEnum::G_CONNECT_SWAPPED);
    }

    public function g_signal_connect_after($instance, $detailedSignal, $chandler, $data)
    {
        return $this->g_signal_connect_data($instance, $detailedSignal, $chandler, $data, NULL, GtkEnum::G_CONNECT_AFTER);
    }

    public function g_signal_handlers_disconnect_by_data($instance, $data)
    {
        return $this->g_signal_handlers_disconnect_matched($instance, GtkEnum::G_SIGNAL_MATCH_DATA, 0, 0, NULL, NULL, $data);
    }

    public function g_signal_handlers_block_by_func($instance, $func, $data)
    {
        $sig = $this->new('GSignalMatchType');
        $sig->cdata = (GtkEnum::G_SIGNAL_MATCH_FUNC | GtkEnum::G_SIGNAL_MATCH_DATA);
        return $this->g_signal_handlers_block_matched($instance, $sig, 0, 0, NULL, $func, $data);
    }

    public function g_signal_handlers_unblock_by_func($instance, $func, $data)
    {
        $sig = $this->new('GSignalMatchType');
        $sig->cdata = (GtkEnum::G_SIGNAL_MATCH_FUNC | GtkEnum::G_SIGNAL_MATCH_DATA);
        return $this->g_signal_handlers_unblock_matched($instance, $sig, 0, 0, NULL, $func, $data);
    }

    public function g_signal_handlers_disconnect_by_func($instance, $func, $data)
    {
        $sig = $this->new('GSignalMatchType');
        $sig->cdata = (GtkEnum::G_SIGNAL_MATCH_FUNC | GtkEnum::G_SIGNAL_MATCH_DATA);
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

    public function G_TYPE_FROM_INTERFACE($gi)
    {
        return $this->cast('GTypeInterface*', $gi)[0]->g_type;
    }

    public function G_TYPE_INSTANCE_GET_PRIVATE($v, $gt, $ct)
    {
        trigger_error('G_TYPE_INSTANCE_GET_PRIVATE() macro is deprecated since 2.58 of gobject', E_DEPRECATED);
        $p = $this->cast('GTypeInstance*', $v);
        $gp = $this->g_type_instance_get_private($p, $gt);
        return $this->cast("$ct*", $gp);
    }

    public function G_TYPE_CLASS_GET_PRIVATE($v, $gt, $ct)
    {
        $p = $this->cast('GTypeClass*', $v);
        return $this->cast("$ct*", $this->g_type_class_get_private($p, $gt));
    }

}
