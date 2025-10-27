<?php
declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2024 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace JmapClient\Requests;

class RequestFactory {

    private static function instance(string $class, ...$arguments): mixed {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Class $class does not exist");
        }
        return new $class(...$arguments);
    }

    public static function get(string $module, ...$arguments): RequestSet {
        $class = RequestClasses::getCommand($module . '/get');
        return self::instance($class, ...$arguments);
    }

    public static function set(string $module, ...$arguments): RequestSet {
        $class = RequestClasses::getCommand($module . '/set');
        return self::instance($class, ...$arguments);
    }

    public static function changes(string $module, ...$arguments): RequestChanges {
        $class = RequestClasses::getCommand($module . '/changes');
        return self::instance($class, ...$arguments);
    }

    public static function query(string $module, ...$arguments): RequestQuery {
        $class = RequestClasses::getCommand($module . '/query');
        return self::instance($class, ...$arguments);
    }

    public static function queryChanges(string $module, ...$arguments): RequestQueryChanges {
        $class = RequestClasses::getCommand($module . '/queryChanges');
        return self::instance($class, ...$arguments);
    }

    public static function parse(string $module, ...$arguments): RequestParse {
        $class = RequestClasses::getCommand($module . '/parse');
        return self::instance($class, ...$arguments);
    }

    public static function upload(string $module, ...$arguments): RequestUpload {
        $class = RequestClasses::getCommand($module . '/upload');
        return self::instance($class, ...$arguments);
    }

}
