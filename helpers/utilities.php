<?php

namespace OurNew\BotCore\Helpers;

/**
 * Render an HTML message
 * @param string $view
 * @param array $values
 * @return string
 */
function message(string $view, array $values = []): string
{
    return rescue(function () use ($view, $values) {
        //render view
        $message = view("bot-core::messages.$view", $values)->render();
        
        //remove line breaks
        $message = preg_replace('/\r\n|\r|\n/', '', $message);
        
        //replace <br> with \n
        $message = str_replace(['<br>', '</br>', '<BR>', '</BR>'], "\n", $message);
        
        return $message;
    }, 'messages.' . $view, true);
}