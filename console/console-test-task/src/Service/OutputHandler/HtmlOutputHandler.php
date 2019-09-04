<?php

namespace App\Service\OutputHandler;

class HtmlOutputHandler implements OutputHandlerInterface
{
    /**
     * @param array $data
     * @param string $path
     */
    public function handle(array $data, string $path): void
    {
        $html = "<table>\n";
        $html .= "\t<tr>\n";
        foreach ($data[0] as $key => $value) {
            $html .= "\t\t<th>" . htmlspecialchars($key) . "</th>\n";
        }
        $html .= "\t</tr>\n";

        foreach ($data as $key => $value) {
            $html .= "\t<tr>\n";
            foreach ($value as $key2 => $value2) {
                $html .= "\t\t<td>" . htmlspecialchars($value2) . "</td>\n";
            }
            $html .= "\t</tr>\n";
        }

        $html .= "</table>";

        file_put_contents($path . "/output.html", $html);
        return;
    }
}