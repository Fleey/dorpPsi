<?php

use Illuminate\Contracts\Routing\ResponseFactory;

if (!function_exists('json')) {

    /**
     * 返回成功结果
     *
     * @param string $data
     * @return \Illuminate\Http\Response
     */
    function json($data = '')
    {
        /* @var $factory ResponseFactory */
        $factory = app(ResponseFactory::class);

        if (is_array($data))
            $content = $data;
        else
            $content = [
                'status'  => true,
                'message' => $data
            ];

        return $factory->make($content, 200);
    }
}
if (!function_exists('error')) {
    /**
     * 返回失败结果
     *
     * @param string $data
     * @return \Illuminate\Http\Response
     */
    function error($data = '')
    {
        /* @var $factory ResponseFactory */
        $factory = app(ResponseFactory::class);

        if (is_array($data))
            $content = $data;
        else
            $content = [
                'status'  => false,
                'message' => $data
            ];

        return $factory->make($content, 400);
    }
}
