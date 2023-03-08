<?php

namespace backend\modules\conf\controllers;

use Yii;
use yii\console\Controller;

class WeeklyReportCommand extends Controller
{
    public function actionSendReport()
    {
        // Fetch item counts from the database
        $counts = Yii::$app->db->createCommand("SELECT 
            (SELECT COUNT(*) FROM adgg_uat.view_icow_farmers) AS `farmsCount`, 
            (SELECT COUNT(*) FROM adgg_uat.view_icow_animals) AS `animalsCount`, 
            (SELECT COUNT(*) FROM adgg_uat.view_milking_event_icow) AS `milkingEventCount`, 
            (SELECT COUNT(*) FROM adgg_uat.view_calving_event_icow) AS `calvingEventCount`
        ")->queryOne();

        // Compose email body with the counts in a table
        $table = "<table>
                    <tr>
                        <th>Icow Total Records collected</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td>iCow Farmers</td>
                        <td>{$counts['farmsCount']}</td>
                    </tr>
                    <tr>
                        <td>iCow Animals</td>
                        <td>{$counts['animalsCount']}</td>
                    </tr>
                    <tr>
                        <td>Milking Events</td>
                        <td>{$counts['milkingEventCount']}</td>
                    </tr>
                    <tr>
                        <td>Calving Events</td>
                        <td>{$counts['calvingEventCount']}</td>
                    </tr>
                </table>";

        // Send email
        Yii::$app->mailer->compose()
            ->setFrom('noreply.adgg@gmail.com')
            ->setTo('d.mogaka@cgiar.org')
            ->setSubject('Weekly Report')
            ->setHtmlBody($table)
            ->send();

        echo "Weekly report email sent.\n";
    }

}
