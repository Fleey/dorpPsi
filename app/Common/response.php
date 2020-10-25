<?php

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\MessageBag;

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

if (!function_exists('backMessageBag')) {
    /**
     * 返回一个自定义的messageBag
     * @param string $type
     * @param string $title
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    function backMessageBag(string $type, string $title, string $message)
    {
        $msgBag = new MessageBag(compact('title', 'message'));
        return back()->with([
            $type => $msgBag
        ]);
    }
}

if (!function_exists('backSuccess')) {
    /**
     * 返回一个成功 messageBag
     * @param string $title
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    function backSuccess(string $title, string $message)
    {
        return backMessageBag('success', $title, $message);
    }
}

if (!function_exists('backWarning')) {
    /**
     * 返回一个警告 messageBag
     * @param string $title
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    function backWarning(string $title, string $message)
    {
        return backMessageBag('warning', $title, $message);
    }
}

if (!function_exists('backError')) {
    /**
     * 返回一个错误 messageBag
     * @param string $title
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    function backError(string $title, string $message)
    {
        return backMessageBag('error', $title, $message);
    }
}
if (!function_exists('toastrError')) {
    /**
     * 封建toastr错误返回信息
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    function toastrError(string $message)
    {
        return response()->json([
            'status'     => false,
            'validation' => '',
            'message'    => $message
        ]);
    }
}
