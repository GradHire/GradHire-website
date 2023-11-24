<?php

namespace app\src\core\lib;

class StackTrace
{
    public static function print($exception): void
    {
        $ex = htmlspecialchars($exception->getMessage());
        $code = $exception->getCode();
        $file = htmlspecialchars($exception->getFile());
        $line = $exception->getLine();
        echo <<<HTML
        <link rel="stylesheet" href="/resources/css/output.css">
        <div class="m-4 p-2 border-[1px] border-zinc-200 flex flex-col gap-2">
<p class="text-red-600 font-extrabold">ERROR</p>
<p><span class="font-bold">Code:</span> $code</p>
<p><span class="font-bold">Exception:</span> $ex</p>
<p><span class="font-bold">Error called here:</span> $file on line $line</p>
<p class="font-bold">Stack trace: </p>
HTML;

        $trace = $exception->getTrace();

        echo '<table class="table-auto w-full divide-y-2 divide-zinc-200 bg-white text-sm">';
        echo '<thead>
<tr>
<th class="border px-4 py-2 text-left">ID</th>
<th class="border px-4 py-2 text-left">Function</th>
<th class="border px-4 py-2 text-left">Line</th>
<th class="border px-4 py-2 text-left">File</th>
</tr></thead>';
        echo '<tbody>';
        $i = 0;

        foreach ($trace as $item) {
            if (!isset($item['file']) || htmlspecialchars($item['file']) === "")
                continue;
            echo '<tr>';

            echo '<td class="border px-4 py-2 w-[20px]">';
            echo $i;
            echo '</td>';

            echo '<td class="border px-4 py-2 w-[300px]">';
            if (isset($item['function'])) {
                echo htmlspecialchars($item['function']);
            }
            echo '</td>';

            echo '<td class="border px-4 py-2 w-[100px]">';
            if (isset($item['line'])) {
                echo $item['line'];
            }
            echo '</td>';

            echo '<td class="border px-4 py-2">';
            echo htmlspecialchars($item['file']);
            echo '</td>';

            echo '</tr>';
            $i++;
        }

        echo '</tbody></table>';
        echo '</div>';
    }
}