<?php
App::uses('Component', 'Controller');

class SsmReportRevisionComponent extends Component {
    public $components = array('Session');

    public function __construct(ComponentCollection $collection, array $settings = array()) {
        parent::__construct($collection, $settings);
        $this->SsmReport                = ClassRegistry::init('SsmReport');
        $this->SsmReportSlide           = ClassRegistry::init('SsmReportSlide');
        $this->SsmHistoryReportRevision = ClassRegistry::init('SsmHistoryReportRevision');
        $this->SsmHistoryReportSlide    = ClassRegistry::init('SsmHistoryReportSlide');
    }

    public function save_report_revision($report_id,$bk_at_create = 0) {
        // load report and report slide
        $report         = $this->SsmReport->find(
            'first',
            array(
                'conditions'=>array(
                    'id'=>$report_id
                )
            )
        );
        $report_slides  = $this->SsmReportSlide->find(
            'all',
            array(
                'conditions' => 
                array(
                    'report_id' => $report_id
                )
            )
        );
        // ------------------------------------

        // create new report revision
        $data = [
            'report_id'     => $report_id,
            'year'          => $report['SsmReport']['year'],
            'month'         => $report['SsmReport']['month'],
            'site_id'       => $report['SsmReport']['site_id'],
            'bk_at_create'  => $bk_at_create ? 1 : 0,
            'info'          => serialize(array(
                'cv_key'            =>$report['SsmReport']['cv_key'],
                'site_target_key'   =>$report['SsmReport']['site_target_key'],
                'kpi_list'          =>$report['SsmReport']['kpi_list']
            )),
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        $this->SsmHistoryReportRevision->create();
        $this->SsmHistoryReportRevision->save($data);
        $report_revision_id = $this->SsmHistoryReportRevision->getLastInsertId();
        // ------------------------------------

        // save revision report slide
        foreach ($report_slides as $slide) {
            $data = $slide['SsmReportSlide'];
            unset($data['id']);
            $data['history_report_revision_id'] = $report_revision_id;
            $this->SsmHistoryReportSlide->create();
            $this->SsmHistoryReportSlide->save($data);
        }
        // ------------------------------------
    }

    public function get_report_revisions($site_id,$year,$month) {
        $report_revisions = $this->SsmHistoryReportRevision->find('all', ['conditions' => ['year' => $year,'month'=>$month,'site_id'=>$site_id]]);
        $report_revisions_array = [];
        if(count($report_revisions) == 1 && $report_revisions[0]['SsmHistoryReportRevision']['bk_at_create'] == 1){
            return $report_revisions_array;
        }

        foreach ($report_revisions as $revision) {
            $report_revisions_array[] = $revision['SsmHistoryReportRevision'];
        }

        return $report_revisions_array;
    }

    public function restore_report_revision($site_id,$year,$month, $revision_id,$restore_to_report_id) {
        if (!$site_id || !$year || !$year || !$month || !$revision_id || !$restore_to_report_id) exit;

        $report_revisions = $this->SsmHistoryReportRevision->find('all', [
            'conditions' => [
                'id'        => $revision_id,
                'year'      => $year,
                'month'     => $month,
                'site_id'   => $site_id
            ]
        ]);
        if (!$report_revisions) exit;

        $this->SsmHistoryReportRevision->updateAll(
            ['is_active' => 0],
            ['year' => $year,'month'=>$month,'site_id'=>$site_id]
        );
        $this->SsmHistoryReportRevision->id = $revision_id;
        $this->SsmHistoryReportRevision->save(
            ['is_active' => 1]
        );

        // delete report slide data
        $this->SsmReportSlide->deleteAll(['report_id' => $restore_to_report_id]);

        // load report and report slide revision
        $report_slides = $this->SsmHistoryReportSlide->find('all', ['conditions' => ['history_report_revision_id' => $revision_id]]);
        if (!$report_slides) exit;

        // save report slide
        foreach ($report_slides as $slide) {
            $data = $slide['SsmHistoryReportSlide'];
            $data['report_id'] = $restore_to_report_id;
            unset($data['history_report_revision_id']);
            $this->SsmReportSlide->create();
            $this->SsmReportSlide->save($data);
        }
        // ------------------------------------
        $response = [
            'status' => 'success'
        ];
        echo json_encode($response);
        exit;
    }
}