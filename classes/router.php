<?php
class route
{
    public static function __callStatic($method, $args)
    {

        if ($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {

            $presets = [
                ':all' => '.*',
                ':alpha' => '[a-zA-Z]+',
                ':any' => '[^/]+',
                ':num' => '\d+|-\d+',
            ];

            $pattern = $args[0];

            foreach ($presets as $shortcode => $regex) {
                $pattern = strtr($pattern, [$shortcode => '(' . $regex . ')']);
            }
            $pattern = '~^' . $pattern . '$~';

            $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            
            if( substr($request, -1) == '/' ) $request = preg_replace('~\/(?!.*\/)~', '', $request);
                        
            preg_match($pattern, $request, $matches);

            if (!$matches) return;

            $output = $args[1]($matches);
            if (isset($output)) die($output);
        }
    }
}