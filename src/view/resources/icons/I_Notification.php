<?php

namespace app\src\view\resources\icons;

class I_Notification
{
    public static function render(string $class = ""): string
    {
        return
            <<<HTML
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                     class="{$class} ">
                    <path fill-rule="evenodd"
                          d="M10 2a6 6 0 00-6 6c0 1.887-.454 3.665-1.257 5.234a.75.75 0 00.515 1.076 32.91 32.91 0 003.256.508 3.5 3.5 0 006.972 0 32.903 32.903 0 003.256-.508.75.75 0 00.515-1.076A11.448 11.448 0 0116 8a6 6 0 00-6-6zM8.05 14.943a33.54 33.54 0 003.9 0 2 2 0 01-3.9 0z"
                          clip-rule="evenodd"/>
                </svg>
            HTML;
    }

}