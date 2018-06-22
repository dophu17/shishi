<?php
App::uses('HtmlHelper', 'View/Helper');

class ExpiredContractsHelper extends HtmlHelper
{
    public function createBootstrapPopoverDataContent($expiredContracts)
    {
        $html = '<ul>';

        foreach ($expiredContracts as $expiredContract) {
            $html .= '<li>' . $expiredContract['site_name'] . '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}