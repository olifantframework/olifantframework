<?php
namespace Olifant\Utils;

use ReflectionClass;

class Docblock
{
    private static function normalizeVarExport($export)
    {
        $export = str_replace(PHP_EOL, '', $export);
        $export = str_replace('array ()', '[]', $export);

        return $export;
    }

    private static function buildRow($item, $static = false)
    {
        $line = '@method';
        if ($static) {
            $line .= ' static';
        }

        if ($item['return']) {
            $line .= ' ' . $item['return'];
        }

        $line .= ' ' . $item['name'];

        if ($item['params']) {
            $fn = function ($a) {
                return (
                    $a['type'] . ' ' . $a['name'] .
                    (
                        isset($a['default'])
                        ? (' = ' . $a['default'])
                        : ''
                    )
                );
            };
            $args = array_map($fn, $item['params']);
            $line .= '(' . implode(', ', $args) . ')';
        } else {
            $line .= '()';
        }

        if ($item['desc']) {
            $line .= ' ' . $item['desc'];
        }

        return $line;
    }

    private static function parseParams($doc, array $reflectionParams)
    {
        preg_match_all('~@param\s(.+?)\s{1,10}(.+?)(\s|$)~', $doc, $args);

        $params = [];
        if ($args) {
            foreach ($args[0] as $key => $line) {
                $params[] = [
                    'type' => $args[1][$key],
                    'name' => $args[2][$key]
                ];

                if (isset($reflectionParams[$key]) and $reflectionParams[$key]->isDefaultValueAvailable()) {
                    $params[count($params) -1]['default'] = self::normalizeVarExport(var_export(
                        $reflectionParams[$key]->getDefaultValue(),
                        true
                    ));
                }
            }
        }

        return $params;
    }

    private static function parseDesc($doc)
    {
        $descEnd = strpos($doc,'@');
        $desc = $descEnd ? substr($doc, 0, $descEnd) : '';
        $desc = str_replace(PHP_EOL, ' ', $desc);
        $desc = trim(str_replace(['/**','  *','    *','    '],'',$desc));

        return $desc;
    }

    private static function parseReturn($doc)
    {
        preg_match_all('~@return\s(.+?)\s~', $doc, $return);

        return isset($return[1]) ? reset($return[1]) : null;
    }

    public static function genFacadeAutocomplete($key)
    {
        $instance = \Olifant\App::make($key);

        $class = new ReflectionClass($instance);
        $methods = $class->getMethods();
        $methods = array_filter($methods, function($item) {
            return $item->isPublic() and !in_array($item->getName(), ['__construct','__destruct']);
        });

        $parsed = array();
        foreach ($methods as $method) {
            $item = [
                'desc' => null,
                'params' => [],
                'return' => null
            ];

            $item['name'] = $method->getName();
            $doc = $method->getDocComment();
            if ($doc) {
                $item['desc'] = self::parseDesc($doc);
                $item['params'] = self::parseParams($doc, $method->getParameters());
                $item['return'] = self::parseReturn($doc);
            }

            $parsed[] = $item;
        }

        $com = '/**' . PHP_EOL;
        foreach($parsed as $p) {
            $com .= '* ' . self::buildRow($p) . PHP_EOL;
        }
        $com .= '*' . PHP_EOL;
        foreach($parsed as $p) {
            $com .= '* ' . self::buildRow($p, true) . PHP_EOL;
        }
        $com .= '*/';

        die($com);
    }
}