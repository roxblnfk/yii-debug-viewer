<?php

namespace Yiisoft\Yii\Debug\Viewer\Actions\DB;

use Yiisoft\Yii\Debug\Viewer\Panels\DbPanel;

/**
 * ExplainAction provides EXPLAIN information for SQL queries
 */
class ExplainAction
{
    public DbPanel $panel;

    public function run($seq, $tag)
    {
        $this->controller->loadData($tag);

        $timings = $this->panel->calculateTimings();

        if (!isset($timings[$seq])) {
            throw new HttpException(404, 'Log message not found.');
        }

        $query = $timings[$seq]['info'];

        $results = $this->panel->getDb()->createCommand('EXPLAIN ' . $query)->queryAll();

        $output[] = '<table class="table"><thead><tr>' . implode(array_map(function ($key) {
            return '<th>' . $key . '</th>';
        }, array_keys($results[0]))) . '</tr></thead><tbody>';

        foreach ($results as $result) {
            $output[] = '<tr>' . implode(array_map(function ($value) {
                return '<td>' . (empty($value) ? 'NULL' : htmlspecialchars($value)) . '</td>';
            }, $result)) . '</tr>';
        }
        $output[] = '</tbody></table>';
        return implode($output);
    }
}
