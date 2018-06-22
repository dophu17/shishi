<?php
App::uses('Shell', 'Console');

class DeleteReportRevisionShell extends Shell {
    public $uses = array('SsmHistoryReportRevision');

    public function main() {
        $sql = 'DELETE     ssm_history_report_revisions, ssm_history_report_slides 
                FROM       ssm_history_report_revisions 
                INNER JOIN ssm_history_report_slides 
                ON         ssm_history_report_revisions.id = ssm_history_report_slides.history_report_revision_id 
                WHERE      DATE_ADD(created_at, INTERVAL 6 MONTH) < \'' . date('Y-m-d H:i:s') . '\'';
        if ($this->SsmHistoryReportRevision->query($sql)) {
            $this->out('success');
            exit;
        }
        $this->out($sql);
    }
}