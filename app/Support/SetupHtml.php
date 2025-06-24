<?php

namespace App\Support;

class SetupHtml
{
    public static function index($data)
    {
        $metaCharset = new ElementHtml('meta');
        $metaCharset->charset = "utf-8";

        $metaViewport = new ElementHtml('meta');
        $metaViewport->name = "viewport";
        $metaViewport->content = "width=device-width, initial-scale=1.0, maximum-scale=1.0";

        $head = new ElementHtml('head');
        $head->add($metaCharset->show());
        $head->add($metaViewport->show());

        $body = new ElementHtml('body');
        $body->add($data);

        $html = new ElementHtml('html');
        $html->add($head->show());
        $html->add($body->show());

        return "<!DOCTYPE html>" . $html->show();
    }
}